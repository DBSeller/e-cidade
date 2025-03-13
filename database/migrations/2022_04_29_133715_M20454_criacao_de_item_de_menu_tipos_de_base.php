<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20454CriacaoDeItemDeMenuTiposDeBase extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->upDicionario();
	
		Schema::create('secretariadeeducacao.tipobase', function (Blueprint $table) {
			$table->increments('ed182_id');
			$table->string('ed182_descricao', 40);
			$table->integer('ed182_estrutura_curricular');
			$table->jsonb('ed182_tipo_itinerario_informativo')->nullable();
			$table->jsonb('ed182_compos_itinerario_integrado')->nullable();
			$table->integer('ed182_tipo_curso_itinerario_tec_prof')->nullable();
			$table->boolean('ed182_itinerario_concomitante')->nullable();
			$table->boolean('ed182_ativo')->default(true);
		});

		DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('secretaria.tipobase');");
		
		$dados = [
			['ed182_descricao' => 'Base Comum', 'ed182_estrutura_curricular' => 0],
			['ed182_descricao' => 'Educação Infantil', 'ed182_estrutura_curricular' => 2],
			['ed182_descricao' => 'Base Diversificada', 'ed182_estrutura_curricular' => 0]
		];

		foreach ($dados as $linha) {
			DB::table('secretariadeeducacao.tipobase')->insert($linha);
		}

		$dados = [
			['schema' => 'escola.regencia', 'campo' => 'ed59_tipobase', 'constraint' => 'regencia_i_tipobase_fk'],
			['schema' => 'escola.histmpsdisc', 'campo' => 'ed65_tipobase', 'constraint' => 'histmpsdisc_i_tipobase_fk'],
			['schema' => 'escola.histmpsdiscfora', 'campo' => 'ed100_tipobase', 'constraint' => 'histmpsdiscfora_i_tipobase_fk'],
			['schema' => 'escola.basemps', 'campo' => 'ed34_tipobase', 'constraint' => 'basemps_i_tipobase_fk']
		];

		foreach ($dados as $linha) {
			Schema::table($linha['schema'], function (Blueprint $table) use ($linha) {
				$table->integer($linha['campo'])->nullable()->default(null);
	
				$table->foreign($linha['campo'], $linha['constraint'])
				->references('ed182_id')
				->on('secretariadeeducacao.tipobase');
			});
		}

		$dados = [
			['schema' => 'escola.regencia', 'campo' => 'ed59_tipobase', 'basecomum' => 'ed59_basecomum'],
			['schema' => 'escola.histmpsdisc', 'campo' => 'ed65_tipobase', 'basecomum' => 'ed65_basecomum'],
			['schema' => 'escola.histmpsdiscfora', 'campo' => 'ed100_tipobase', 'basecomum' => 'ed100_basecomum'],
			['schema' => 'escola.basemps', 'campo' => 'ed34_tipobase', 'basecomum' => 'ed34_basecomum']
		];

		foreach ($dados as $linha) {
			DB::table($linha['schema'])->where($linha['basecomum'], true)->update([$linha['campo'] => 1]);
			DB::table($linha['schema'])->where($linha['basecomum'], false)->update([$linha['campo'] => 3]);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->downDicionario();
		$dados = [
			['schema' => 'escola.regencia', 'campo' => 'ed59_tipobase'],
			['schema' => 'escola.histmpsdisc', 'campo' => 'ed65_tipobase'],
			['schema' => 'escola.histmpsdiscfora', 'campo' => 'ed100_tipobase'],
			['schema' => 'escola.basemps', 'campo' => 'ed34_tipobase']
		];
		foreach ($dados as $linha) {
			Schema::table($linha['schema'], function (Blueprint $table) use ($linha) {
				$table->dropColumn($linha['campo']);
			});
		}
		Schema::dropIfExists('secretariadeeducacao.tipobase');
	}

	public function upDicionario()
	{
		DB::table('configuracoes.db_itensmenu')->insert([
			'id_item' => 228653,
			'descricao' => 'Tipos de Base',
			'help' => 'Tipos de Base',
			'funcao' => 'edu1_tiposdebase001.php',
			'itemativo' => '1',
			'manutencao' => '1',
			'desctec' => 'Inclusão, alteração e exclusão de Tipos de Base.',
			'libcliente' => 'true'
		]);

		DB::table('configuracoes.db_menu')->insert([
			'id_item' => 3470,
			'id_item_filho' => 228653,
			'menusequencia' => 46,
			'modulo' => 7159
		]);

		DB::table('configuracoes.db_sysarquivo')->insert([
			'codarq' => 1010913,
			'nomearq' => 'tipobase',
			'descricao' => 'Tipos de Base',
			'sigla' => 'ed182',
			'dataincl' => '2022-05-03',
			'rotulo' => 'Tipos de Base',
			'tipotabela' => 0,
			'naolibclass' => 'f',
			'naolibfunc' => 'f',
			'naolibprog' => 'f',
			'naolibform' => 'f'
		]);

		DB::table('configuracoes.db_sysarqmod')->insert([
			'codmod' => 61,
			'codarq' => 1010913
		]);

		$dados = [
			//tabela nova
			[1014055,'ed182_id','int4','ID','0', 'ID',11,'f','f','f',1,'text','ID'],
			[1014056,'ed182_descricao','varchar(40)','Descrição','', 'Descrição',40,'f','f','f',0,'text','Descrição'],
			[1014057,'ed182_estrutura_curricular','int4','Estrutura Curricular','0', 'Estrutura Curricular',11,'f','f','f',1,'text','Estrutura Curricular'],
			[1014058,'ed182_tipo_itinerario_informativo','int4','Tipo de Itinerario Informativo','0', 'Tipo de Itinerario Informativo',11,'t','f','f',1,'text','Tipo de Itinerario Informativo'],
			[1014061,'ed182_compos_itinerario_integrado','int4','Composição do Itinerario Informativo Integrado','0', 'Composição do Itinerario',11,'t','f','f',1,'text','Composição do Itinerarario'],
			[1014062,'ed182_tipo_curso_itinerario_tec_prof','int4','Tipo do curso do itinerário de formação técnica e profissional','0', 'Tipo do Cruso',11,'t','f','f',1,'text','Tipo do Cruso'],
			[1014063,'ed182_itinerario_concomitante','bool','Itinerario Concomitante','f', 'Itinerario Concomitante',1,'t','f','f',5,'text','Itinerario Concomitante'],
			[1014064,'ed182_ativo','bool','Ativo','f', 'Ativo',1,'f','f','f',5,'text','Ativo'],
			// --tabelas existentes
			[1013994, 'ed59_tipobase', 'int4', 'Tipo de Base', 'null', 'Tipo de Base', 11, 't', 'f', 'f', 1, 'text', 'Tipo de Base'],
			[1013995, 'ed34_tipobase', 'int4', 'Tipo de Base', 'null', 'Tipo de Base', 11, 't', 'f', 'f', 1, 'text', 'Tipo de Base'],
			[1014001, 'ed65_tipobase', 'int4', 'Tipo de Base', 'null', 'Tipo de Base', 11, 't', 'f', 'f', 1, 'text', 'Tipo de Base'],
			[1014002, 'ed100_tipobase', 'int4', 'Tipo de Base', 'null', 'Tipo de Base', 11, 't', 'f', 'f', 1, 'text', 'Tipo de Base']
		];
		
		foreach ($dados as $linha) {
			DB::table('configuracoes.db_syscampo')->insert([
				'codcam' => $linha[0],
				'nomecam' => $linha[1],
				'conteudo' => $linha[2],
				'descricao' => $linha[3],
				'valorinicial' => $linha[4],
				'rotulo' => $linha[5],
				'tamanho' => $linha[6],
				'nulo' => $linha[7],
				'maiusculo' => $linha[8],
				'autocompl' => $linha[9],
				'aceitatipo' => $linha[10],
				'tipoobj' => $linha[11],
				'rotulorel' => $linha[12]
			]);
		}

		$dados = [
			// tabela nova
			[1010913,1014055,1,0],
			[1010913,1014056,2,0],
			[1010913,1014057,3,0],
			[1010913,1014058,4,0],
			[1010913,1014061,5,0],
			[1010913,1014062,6,0],
			[1010913,1014063,7,0],
			[1010913,1014064,8,0],
			// - tabelas ja existentes 
			[1010084,1013994,17,0],
			[1010061,1013995,15,0],
			[1010133,1014001,15,0],
			[1010159,1014002,14,0]
		];

		foreach ($dados as $linha) {
			DB::table('configuracoes.db_sysarqcamp')->insert([
				'codarq' => $linha[0],
				'codcam' => $linha[1],
				'seqarq' => $linha[2],
				'codsequencia' => $linha[3]
			]);
		}

		DB::table('configuracoes.db_sysindices')->insert([
			'codind' => 1008769,
			'nomeind' => 'tipobase_ed182_id_pkey',
			'codarq' => 1010913,
			'campounico' => '1'
		]);

		DB::table('configuracoes.db_sysprikey')->insert([
			'codarq' => 1010913,
			'codcam' => 1014055,
			'sequen' => 1,
			'camiden' => 1014055
		]);

		$dados = [
			[1010084,1013994,1,1010913,0],
			[1010133,1014001,1,1010913,0],
			[1010159,1014002,1,1010913,0],
			[1010061,1013995,1,1010913,0]
		];

		foreach ($dados as $linha) {
			DB::table('configuracoes.db_sysforkey')->insert([
				'codarq' => $linha[0],
				'codcam' => $linha[1],
				'sequen' => $linha[2],
				'referen' => $linha[3],
				'tipoobjrel' => $linha[4]
			]);
		}
	}

	public function downDicionario()
	{
		DB::table('configuracoes.db_itensmenu')->where('id_item', '=', 228653)->delete();
		DB::table('configuracoes.db_menu')->where('id_item_filho', '=', 228653)->delete();
				
		$dados = [1014055, 1014056, 1014057, 1014058, 1014061, 1014062, 1014063, 1014064];
		DB::table('configuracoes.db_sysarqcamp')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysindices')->where('codind', 1008769)->delete();
		DB::table('configuracoes.db_sysprikey')->where('codarq', 1010913)->delete();
		DB::table('configuracoes.db_syscampo')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysarqmod')->where('codarq', 1010913)->delete();
		DB::table('configuracoes.db_sysarquivo')->where('codarq', 1010913)->delete();

		$dados = [1013995, 1013994, 1014001, 1014002];
		DB::table('configuracoes.db_sysforkey')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysarqcamp')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_syscampo')->whereIn('codcam', $dados)->delete();
	}
}
