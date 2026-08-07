<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19876S2230s10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        -- Novo grupo de periodo aquisitivo
        insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem )
        values ( 4000244 ,3000023 ,'Informações referentes ao período aquisitivo de férias' ,'informacaoes_referentes_periodo_aquisistivo_ferias' ,'perAquis' ,3 );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura )
        values ( 4000360 ,2 ,4000244 ,'Data de início do período aquisitivo de férias.' ,'data_inicio_aquisitivo_ferias' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'dtInicio' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001484 ,4000360 ,'Resposta 1' ,'resposta-1623b3cfcdab00' ,'false' ,0 ,'' ,'dtInicio' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura )
        values ( 4000361 ,2 ,4000244 ,'Data fim do período aquisitivo de férias.' ,'data_fim_aquisitivo_ferias' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'dtFim' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001485 ,4000361 ,'Resposta 1' ,'resposta-1623b3cfce110d' ,'false' ,0 ,'' ,'dtFim' );
        -- GRUPO ADICIONADO P/ MANDATO ELETIVO
        insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000245 ,3000023 ,'Afastamento para exercício de mandato eletivo' ,'afastamento-mandato-eletivo' ,'infoMandElet' ,5 );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000362 ,2 ,4000245 ,'CNPJ do Mandato eletivo' ,'cnpj_mand_eletivo' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'cnpjMandElet' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001486 ,4000362 ,'Resposta 1' ,'resposta-c5b5bbfa7e9405' ,'false' ,0 ,'' ,'cnpjMandElet' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000363 ,2 ,4000245 ,'Indicar se o servidor optou pela remuneração ' ,'indicador_remuneracao_cargo_efetivo' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'indRemunCargo' ,'false' );
SQL;
        DB::connection()->getPdo()->exec($sql);

        $grupos = [3000253, 3000254];
        foreach ($grupos as $grupo) {
            $this->deletaGrupo($grupo);
        }
        $this->upAcertoBase();
        $this->upCamposDinamicos();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->retornaPerguntas();
        $this->deletaGrupo(4000244);
        $this->deletaGrupo(4000245);
        $this->downCamposDinamimcos();
        $this->downAcertoBase();
    }

    private function upCamposDinamicos()
    {
        $this->upAdicionaCamposDinamimcos();
        $this->upRemoveCamposDinamicos();
    }

    private function upAdicionaCamposDinamimcos()
    {
        $sql = <<<SQL
			create temp table dados_originais_ferias as
				(SELECT
					db_cadattdinamico.db118_sequencial,
					db_cadattdinamicoatributos.db109_sequencial
				FROM
					esocial.grupomotivoafastamentoesocial
					INNER JOIN motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
					INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
					inner join configuracoes.db_cadattdinamicoatributos on db118_sequencial = db109_db_cadattdinamico
				WHERE eso09_sequencial = 15);

			-- select * from dados_originais_ferias;
			create temp table dados_clonados_ferias as (
				SELECT
					*
				FROM
					configuracoes.mapeamentoatributosesocial
				WHERE
					db39_campoorigem IN (
						SELECT
							db109_sequencial
				     	FROM
				     		dados_originais_ferias
						)
				);

			-- select * from dados_clonados_ferias;
			create temp table grupos_dados_inserir as (
				SELECT
					*
				FROM
					configuracoes.db_cadattdinamicoatributos
				WHERE
					db109_sequencial IN
				    (
						SELECT
							db39_camponovo
				 		FROM
				 			dados_clonados_ferias
					)
				);

			-- select * from grupos_dados_inserir;
			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'Data Inicial do Periodo Aquisitivo',
					NULL,
					3,
					'dtInicio_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_ferias
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'Data Final do Periodo Aquisitivo',
					NULL,
					3,
					'dtFim_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_ferias
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'Data Inicial do Periodo Aquisitivo',
					NULL,
					3,
					'dtInicio_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'Data Final do Periodo Aquisitivo',
					NULL,
					3,
					'dtFim_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir
				group by
					db109_db_cadattdinamico)
				);


			-- inserindo dados na tabela de mapeamento
			insert into configuracoes.mapeamentoatributosesocial
				(
					select
						a.db109_sequencial,
						b.db109_sequencial
					from
						configuracoes.db_cadattdinamicoatributos b
						left join configuracoes.db_cadattdinamicoatributos a on a.db109_nome = b.db109_nome and a.db109_sequencial != b.db109_sequencial
					where
						b.db109_db_cadattdinamico in (select db118_sequencial from dados_originais_ferias group by db118_sequencial) and b.db109_nome in ('dtInicio_esocial', 'dtFim_esocial')
				        and a.db109_sequencial is not null and b.db109_sequencial is not null
			);

			-- infoMandElet
			create temp table dados_originais_mandelet as
				(SELECT
					db_cadattdinamico.db118_sequencial,
					db_cadattdinamicoatributos.db109_sequencial
				FROM
					esocial.grupomotivoafastamentoesocial
					INNER JOIN motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
					INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
					inner join configuracoes.db_cadattdinamicoatributos on db118_sequencial = db109_db_cadattdinamico
				WHERE eso09_sequencial in (22, 13, 12));

			create temp table dados_clonados_mandelet as (
				SELECT
					*
				FROM
					configuracoes.mapeamentoatributosesocial
				WHERE
					db39_campoorigem IN (
						SELECT
							db109_sequencial
				     	FROM
				     		dados_originais_mandelet
						)
				);

			create temp table grupos_dados_inserir_mandelet as (
				SELECT
					*
				FROM
					configuracoes.db_cadattdinamicoatributos
				WHERE
					db109_sequencial IN
				    (
						SELECT
							db39_camponovo
				 		FROM
				 			dados_clonados_mandelet
					)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'CNPJ do órgão no qual o trabalhador exercerá o mandato eletivo',
					NULL,
					1,
					'cnpjMandElet_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_mandelet
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'Indicar se o servidor optou pela remuneração do cargo efetivo',
					NULL,
					6,
					'indRemunCargo_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_mandelet
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'CNPJ do órgão no qual o trabalhador exercerá o mandato eletivo',
					NULL,
					1,
					'cnpjMandElet_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir_mandelet
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'Indicar se o servidor optou pela remuneração do cargo efetivo',
					NULL,
					3,
					'indRemunCargo_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir_mandelet
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.db_cadattdinamicoatributosopcoes (
				(SELECT
					nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
					db109_sequencial,
					'N',
					'NÃO'
				from
					db_cadattdinamicoatributos
				where
					db109_nome = 'indRemunCargo_esocial'
				)
			);
        	insert into configuracoes.db_cadattdinamicoatributosopcoes (
				(SELECT
					nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
					currval('db_cadattdinamicoatributos_db109_sequencial_seq'),
					'S',
					'SIM'
					from
					db_cadattdinamicoatributos
				where
					db109_nome = 'indRemunCargo_esocial'
				)
			);

			-- inserindo dados na tabela de mapeamento
			insert into configuracoes.mapeamentoatributosesocial
				(
					select
						a.db109_sequencial,
						b.db109_sequencial
					from
						configuracoes.db_cadattdinamicoatributos b
						left join configuracoes.db_cadattdinamicoatributos a on a.db109_nome = b.db109_nome and a.db109_sequencial != b.db109_sequencial
					where
						b.db109_db_cadattdinamico in (select db118_sequencial from dados_originais_mandelet group by db118_sequencial) and b.db109_nome in ('cnpjMandElet_esocial', 'indRemunCargo_esocial')
				        and a.db109_sequencial is not null and b.db109_sequencial is not null
			);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upAcertoBase()
    {
        $sql = <<<SQL
        UPDATE esocial.motivoafastamentoesocial SET eso09_grupomotivoafastamentoesocial=11 where eso09_grupomotivoafastamentoesocial=12;
        INSERT INTO esocial.motivoafastamentoesocial values(24, 'Mandato sindical - Afastamento temporário para exercício de mandato sindical', 12);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downAcertoBase()
    {
        $sql = <<<SQL
        DELETE FROM esocial.motivoafastamentoesocial where eso09_grupomotivoafastamentoesocial=12;
        UPDATE esocial.motivoafastamentoesocial SET eso09_grupomotivoafastamentoesocial=12 where eso09_grupomotivoafastamentoesocial=11;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upRemoveCamposDinamicos()
    {
        $this->removeCampos("'cid_esocial', 'uf_orgao_classe_esocial', 'tipo_orgao_medico_esocial', 'orgao_classe_esocial', 'nome_medico_esocial'");
    }

    private function downCamposDinamimcos()
    {
        $this->downAdicionaCamposDinamicos();
        $this->downRemoveCamposDinamicos();
    }


    private function downRemoveCamposDinamicos()
    {
        $this->removeCampos("'dtInicio_esocial', 'dtFim_esocial', 'indRemunCargo_esocial', 'cnpjMandElet_esocial'");
    }

    private function downAdicionaCamposDinamicos()
    {
        $sql = <<<SQL
			create temp table dados_originais_cat as
				(SELECT
					db_cadattdinamico.db118_sequencial,
					db_cadattdinamicoatributos.db109_sequencial
				FROM
					esocial.grupomotivoafastamentoesocial
					INNER JOIN motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
					INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
					inner join configuracoes.db_cadattdinamicoatributos on db118_sequencial = db109_db_cadattdinamico
				WHERE eso09_sequencial in (1, 3));

			create temp table dados_clonados_cat as (
				SELECT
					*
				FROM
					configuracoes.mapeamentoatributosesocial
				WHERE
					db39_campoorigem IN (
						SELECT
							db109_sequencial
				     	FROM
				     		dados_originais_cat
						)
				);

			create temp table grupos_dados_inserir as (
				SELECT
					*
				FROM
					configuracoes.db_cadattdinamicoatributos
				WHERE
					db109_sequencial IN
				    (
						SELECT
							db39_camponovo
				 		FROM
				 			dados_clonados_cat
					)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'Número de inscrição do órgão de classe',
					NULL,
					1,
					'tipo_orgao_medico_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_cat
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db118_sequencial,
					NULL,
					'Órgão de classe',
					NULL,
					6,
					'orgao_classe_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					dados_originais_cat
				group by
					db118_sequencial)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'CID',
					NULL,
					1,
					'cid_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'Nome do médico/dentista',
					NULL,
					1,
					'nome_medico_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.db_cadattdinamicoatributos (
				(SELECT
					nextval('db_cadattdinamicoatributos_db109_sequencial_seq') AS sequencial,
					db109_db_cadattdinamico,
					NULL,
					'UF do órgão de classe',
					NULL,
					3,
					'uf_orgao_classe_esocial',
					TRUE,
					TRUE,
					TRUE,
				   NULL
				FROM
					grupos_dados_inserir
				group by
					db109_db_cadattdinamico)
				);

			insert into configuracoes.mapeamentoatributosesocial
				(
					select
						a.db109_sequencial,
						b.db109_sequencial
					from
						configuracoes.db_cadattdinamicoatributos b
						left join configuracoes.db_cadattdinamicoatributos a on a.db109_nome = b.db109_nome and a.db109_sequencial != b.db109_sequencial
					where
						b.db109_db_cadattdinamico in (select db118_sequencial from dados_originais_cat group by db118_sequencial) and b.db109_nome in ('cid_esocial', 'uf_orgao_classe_esocial', 'tipo_orgao_medico_esocial', 'orgao_classe_esocial', 'nome_medico_esocial')
			);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function removeCampos($campos)
    {
        $sql = <<<SQL
            delete from configuracoes.mapeamentoatributosesocial where db39_camponovo in (
                SELECT
                    db109_sequencial
                FROM
					configuracoes.db_cadattdinamicoatributos
					INNER JOIN configuracoes.db_cadattdinamico ON db109_db_cadattdinamico = db118_sequencial
					inner join esocial.grupomotivoafastamentoesocial on  eso10_db_cadattdinamico = 	db118_sequencial
					inner JOIN esocial.motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial

                where db109_nome in (${campos})
            );

            delete from configuracoes.mapeamentoatributosesocial where db39_campoorigem in (
                SELECT
                    db109_sequencial
                FROM
					configuracoes.db_cadattdinamicoatributos
					INNER JOIN configuracoes.db_cadattdinamico ON db109_db_cadattdinamico = db118_sequencial
					inner join esocial.grupomotivoafastamentoesocial on  eso10_db_cadattdinamico = 	db118_sequencial
					inner JOIN esocial.motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
                where db109_nome in (${campos})
            );

            delete from configuracoes.db_cadattdinamicoatributosvalor where db110_db_cadattdinamicoatributos in (
                SELECT
                    db109_sequencial
                FROM
					configuracoes.db_cadattdinamicoatributos
                where db109_nome in ({$campos})
            );


            delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos in (
                SELECT
                    db109_sequencial
                FROM
					configuracoes.db_cadattdinamicoatributos
                where db109_nome in (${campos})
            );

            delete from configuracoes.db_cadattdinamicoatributos where db109_sequencial in (
                SELECT
                    db109_sequencial
                FROM
					configuracoes.db_cadattdinamicoatributos
                where db109_nome in (${campos})
            );
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    public function deletaGrupo($db102_sequencial)
    {
        $sql = <<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial})));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial}));
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial});
            delete from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial};
            delete from habitacao.avaliacaogrupopergunta where db102_sequencial = ${db102_sequencial};
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function retornaPerguntas()
    {
        $sql = <<<SQL
            -- Grupo de Informacoes do Atestado
            insert into avaliacaogrupopergunta values (3000253, 3000023, 'Informações complementares relativas ao atestado médico', 'informacoes-complementares-relativas-ao-atestado-m', 'infoAtestado', 1);
            insert into avaliacaopergunta values (3001081, 2, 3000253, 'CID - Classificação Internacional de Doenças', false, true, 1, 'cid-classificacao-internacional-de-doencas', 1, '', 0, false, '', 'codCID');
            insert into avaliacaoperguntaopcao values (3004101, 3001081, 'Resposta 1', false, 'resposta-15b16d32bc5e64', 0, '', 'codCID');
            insert into avaliacaopergunta values (3001082, 2, 3000253, 'Quantidade de Dias Afastado', true, true, 2, 'quantidade-de-dias-afastado', 1, '', 0, false, '', 'qtdDiasAfast');
            insert into avaliacaoperguntaopcao values (3004102, 3001082, 'Resposta 1', false, 'resposta-15b16d32bcd82a', 0, '', 'qtdDiasAfast');
            -- Grupo de Informacoes do Emitente
            insert into avaliacaogrupopergunta values (3000254, 3000023, 'Médico/Dentista que emitiu o atestado', 'medicodentista-que-emitiu-o-atestado', 'emitente', 1);
            insert into avaliacaopergunta values (3001083, 2, 3000254, 'Nome do médico/dentista que emitiu o atestado.', true, true, 1, 'nome_do_medicodentista_que_emitiu_o_atestado', 1, '', 0, false, '', 'nmEmit');
            insert into avaliacaoperguntaopcao values (3004103, 3001083, 'Resposta 1', false, 'resposta-15b16d32bd7dcd', 0, '', 'nmEmit');
            insert into avaliacaopergunta values (3001084, 2, 3000254, 'Órgão de classe', true, true, 2, 'orgao_de_classee', 1, '', 0, false, '', 'ideOC');
            insert into avaliacaoperguntaopcao values (3004104, 3001084, 'Resposta 1', false, 'resposta-15b16d32be050b', 0, '', 'ideOC');
            insert into avaliacaopergunta values (3001085, 2, 3000254, 'Número de Inscrição no Órgão de Classe', true, true, 3, 'numero_de_inscricao_no_orgao_de_classe', 1, '', 0, false, '', 'nrOc');
            insert into avaliacaoperguntaopcao values (3004105, 3001085, 'Resposta 1', false, 'resposta-15b16d32be8e21', 0, '', 'nrOc');
            insert into avaliacaopergunta values (3001086, 2, 3000254, 'Sigla da UF do órgão de classe', false, true, 4, 'sigla_da_uf_do_orgao_de_classe', 1, '', 0, false, '', 'ufOC');
            insert into avaliacaoperguntaopcao values (3004106, 3001086, 'Resposta 1', false, 'resposta-15b16d32bf1559', 0, '', 'ufOC');
SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
