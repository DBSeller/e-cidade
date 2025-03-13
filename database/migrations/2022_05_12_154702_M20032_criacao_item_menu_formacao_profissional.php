<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20032CriacaoItemMenuFormacaoProfissional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		$this->upDicionario();
		
		$this->upTabelaCensoAreasPos();
		
        Schema::create('escola.rhformacaosuperior', function (Blueprint $table) {
			$table->increments('ed183_id');
			$table->integer('ed183_cgm');
			$table->string('ed183_nomecurso', 200);
            $table->integer('ed183_tipoformacao');
			$table->integer('ed183_areaformacao');
            $table->integer('ed183_anoconclusao');

            $table->foreign('ed183_cgm', 'rhformacaosuperior_i_cgm_fk')
			->references('z01_numcgm')
			->on('protocolo.cgm');

			$table->foreign('ed183_areaformacao', 'rhformacaosuperior_i_areaformacao_fk')
			->references('ed184_id')
			->on('escola.censoareaspos');
		});

		DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('escola.rhformacaosuperior');");

		$this->deletaPergunta();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$this->downDicionario();
		Schema::dropIfExists('escola.rhformacaosuperior');
		$this->downTabelaCensoAreasPos();
    }


    public function upDicionario()
	{
        DB::table('configuracoes.db_itensmenu')->insert([
			'id_item' => 228661,
			'descricao' => 'Registro Pós-Graduação',
			'help' => 'Pós-Graduação do profissional',
			'funcao' => 'edu1_formacaoprofissional001.php',
			'itemativo' => '1',
			'manutencao' => '1',
			'desctec' => 'Cadastro, alteração e exclusão de Pós-Graduação do profissional',
			'libcliente' => 'true'
		]);

		DB::table('configuracoes.db_menu')->insert([
			'id_item' => 1100881,
			'id_item_filho' => 228661,
			'menusequencia' => 4,
			'modulo' => 1100747
		]);

        DB::table('configuracoes.db_sysarquivo')->insert([
			'codarq' => 1010920,
			'nomearq' => 'rhformacaosuperior',
			'descricao' => 'Formação Superior do Profissional',
			'sigla' => 'ed183',
			'dataincl' => '2022-05-13',
			'rotulo' => 'Formação Superior do Profissional',
			'tipotabela' => 0,
			'naolibclass' => 'f',
			'naolibfunc' => 'f',
			'naolibprog' => 'f',
			'naolibform' => 'f'
		]);

		DB::table('configuracoes.db_sysarqmod')->insert([
			'codmod' => 1008004,
			'codarq' => 1010920
		]);

        $dados = [
			//tabela nova
			[1014102,'ed183_id','int4','ID','0', 'ID',11,'f','f','f',1,'text','ID'],
            [1014103,'ed183_cgm','int4','CGM do Profissional','0', 'CGM do Profissional',11,'f','f','f',1,'text','CGM do Profissional'],
			[1014104,'ed183_tipoformacao','int4','Tipo de Formação','0', 'Tipo de Formação',11,'f','f','f',1,'text','Tipo de Formação'],
			[1014105,'ed183_areaformacao','int4','Área da Fromação','', 'Área da Fromação',11,'f','f','f',0,'text','Área da Fromação'],
			[1014106,'ed183_nomecurso','varchar(200)','Nome do Curso','', 'Nome do Curso',200,'f','t','f',0,'text','Nome do Curso'],
			[1014107,'ed183_anoconclusao','int4','Ano de Conslusão','0', 'Ano de Conslusão',11,'f','f','f',1,'text','Ano de Conslusão']
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
			[1010920,1014102,1,0],
            [1010920,1014103,2,0],
            [1010920,1014106,3,0],
            [1010920,1014104,4,0],
            [1010920,1014105,5,0],
            [1010920,1014107,6,0]
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
			'codind' => 1008773,
			'nomeind' => 'rhformacaosuperior_pkey',
			'codarq' => 1010920,
			'campounico' => '0'
		]);

        DB::table('configuracoes.db_syscadind')->insert([
			'codind' => 1008773,
			'codcam' => 1014102,
			'sequen' => 1
		]);

		DB::table('configuracoes.db_sysprikey')->insert([
			'codarq' => 1010920,
			'codcam' => 1014102,
			'sequen' => 1,
			'camiden' => 1014102
		]);

        DB::table('configuracoes.db_sysforkey')->insert([
            'codarq' => 1010920,
            'codcam' => 1014103,
            'sequen' => 1,
            'referen' => 42,
            'tipoobjrel' => 0
        ]);

		DB::table('configuracoes.db_sysforkey')->insert([
            'codarq' => 1010920,
            'codcam' => 1014105,
            'sequen' => 1,
            'referen' => 1010922,
            'tipoobjrel' => 0
        ]);
	}

	public function downDicionario()
	{
		DB::table('configuracoes.db_itensmenu')->where('id_item', '=', 228661)->delete();
		DB::table('configuracoes.db_menu')->where('id_item_filho', '=', 228661)->delete();

        $dados = [1014102, 1014103, 1014106, 1014104, 1014105, 1014107];
		DB::table('configuracoes.db_sysforkey')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysarqcamp')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysindices')->where('codind', 1008773)->delete();
        DB::table('configuracoes.db_syscadind')->where('codind', 1008773)->delete();
		DB::table('configuracoes.db_sysprikey')->where('codarq', 1010920)->delete();
		DB::table('configuracoes.db_syscampo')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysarqmod')->where('codarq', 1010920)->delete();
		DB::table('configuracoes.db_sysarquivo')->where('codarq', 1010920)->delete();
	}

	public function upTabelaCensoAreasPos()
	{
		$this->upDicionarioTabelaCensoAreasPos();

		Schema::create('escola.censoareaspos', function (Blueprint $table) {
			$table->integer('ed184_id')->primary();
			$table->string('ed184_descricao', 200);
		});

        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('escola.censoareaspos');");

        $dados = [
            ['ed184_id' => 1, 'ed184_descricao' =>	'Educação'],
            ['ed184_id' => 2, 'ed184_descricao' =>	'Artes e humanidades'],
            ['ed184_id' => 3, 'ed184_descricao' =>	'Ciências sociais, comunicação e informação'],
            ['ed184_id' => 4, 'ed184_descricao' =>	'Negócios, administração e direito'],
            ['ed184_id' => 5, 'ed184_descricao' =>	'Ciências naturais, matemática e estatística'],
            ['ed184_id' => 6, 'ed184_descricao' =>	'Computação e Tecnologias da Informação e Comunicação (TIC)'],
            ['ed184_id' => 7, 'ed184_descricao' =>	'Engenharia, produção e construção'],
            ['ed184_id' => 8, 'ed184_descricao' =>	'Agricultura, silvicultura, pesca e veterinária'],
            ['ed184_id' => 9, 'ed184_descricao' =>	'Saúde e bem-estar'],
            ['ed184_id' => 10, 'ed184_descricao' =>	'Serviços'],
            ['ed184_id' => 99, 'ed184_descricao' =>	'Programas básicos']
       ];

		foreach ($dados as $linha) {
			DB::table('escola.censoareaspos')->insert($linha);
		}
	}

	public function downTabelaCensoAreasPos()
	{
		$this->downDicionarioTabelaCensoAreasPos();

        Schema::dropIfExists('escola.censoareaspos');
	}

	public function upDicionarioTabelaCensoAreasPos()
	{	
		DB::table('configuracoes.db_sysarquivo')->insert([
			'codarq' => 1010922,
			'nomearq' => 'censoareaspos',
			'descricao' => 'Áreas de Pós Graduação',
			'sigla' => 'ed184',
			'dataincl' => '2022-05-19',
			'rotulo' => 'Áreas de Pós Graduação',
			'tipotabela' => 0,
			'naolibclass' => 't',
			'naolibfunc' => 't',
			'naolibprog' => 't',
			'naolibform' => 't'
		]);

		DB::table('configuracoes.db_sysarqmod')->insert([
			'codmod' => 1008004,
			'codarq' => 1010922
		]);

        $dados = [
			//tabela nova
            [1014129,'ed184_id','int4','Código Censo da Área','0', 'ID',11,'f','f','f',1,'text','ID'],
            [1014130,'ed184_descricao','varchar(200)','Descrição da Área','', 'Descrição',200,'f','f','f',0,'text','Descrição da Área']
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
			[1010922,1014129,1,0],
            [1010922,1014130,2,0]
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
			'codind' => 1008777,
			'nomeind' => 'censoareaspos_pkey',
			'codarq' => 1010922,
			'campounico' => '0'
		]);

        DB::table('configuracoes.db_syscadind')->insert([
			'codind' => 1008777,
			'codcam' => 1014129,
			'sequen' => 1
		]);

		DB::table('configuracoes.db_sysprikey')->insert([
			'codarq' => 1010922,
			'codcam' => 1014129,
			'sequen' => 1,
			'camiden' => 1014129
		]);
	}

	public function downDicionarioTabelaCensoAreasPos()
	{
		$dados = [1014129, 1014130];
		DB::table('configuracoes.db_sysarqcamp')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysindices')->where('codind', 1008777)->delete();
        DB::table('configuracoes.db_syscadind')->where('codind', 1008777)->delete();
		DB::table('configuracoes.db_sysprikey')->where('codarq', 1010922)->delete();
		DB::table('configuracoes.db_syscampo')->whereIn('codcam', $dados)->delete();
		DB::table('configuracoes.db_sysarqmod')->where('codarq', 1010922)->delete();
		DB::table('configuracoes.db_sysarquivo')->where('codarq', 1010922)->delete();
	}

	public function deletaPergunta()
	{
		
		DB::connection()->getPdo()->exec(<<<SQL
			delete from avaliacaogrupoperguntaresposta
			where db108_avaliacaoresposta in(
				select db106_sequencial
					from avaliacaoresposta
				where db106_avaliacaoperguntaopcao in(
					select db104_sequencial
						from avaliacaoperguntaopcao
					where db104_avaliacaopergunta = 3000012
				)
			);

			delete from avaliacaoresposta
			where db106_avaliacaoperguntaopcao in(
				select db104_sequencial
					from avaliacaoperguntaopcao
				where db104_avaliacaopergunta = 3000012
			);

			delete from avaliacaoperguntaopcaolayoutcampo
			where ed313_avaliacaoperguntaopcao in(
				select db104_sequencial
					from avaliacaoperguntaopcao
				where db104_avaliacaopergunta = 3000012
			);
		
			delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 3000012;
			delete from avaliacaopergunta where db103_sequencial = 3000012;			
SQL
		);

	}
}
