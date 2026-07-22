<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22423AfastamentosTemporarios extends Migration
{

	public function geraTabelaTemporaria()
	{
		$sql =  <<<SQL
			-- Pegamos os dados originais de atributos dinamicos com motivo do eSocial = 1 e 3
			-- Codigo 1
			create temp table dados_originais_codigo1 as
				(SELECT
					db_cadattdinamico.db118_sequencial,
					db_cadattdinamicoatributos.db109_sequencial
				FROM
					esocial.grupomotivoafastamentoesocial
					INNER JOIN motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
					INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
					inner join configuracoes.db_cadattdinamicoatributos on db118_sequencial = db109_db_cadattdinamico
				WHERE eso09_sequencial = 1);
			-- Codigo 3
			create temp table dados_originais_codigo3 as
				(SELECT
					db_cadattdinamico.db118_sequencial,
					db_cadattdinamicoatributos.db109_sequencial
				FROM
					esocial.grupomotivoafastamentoesocial
					INNER JOIN motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
					INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
					inner join configuracoes.db_cadattdinamicoatributos on db118_sequencial = db109_db_cadattdinamico
				WHERE eso09_sequencial = 3);

			-- Pegamos os dados filhos (clones) dos atributos vinculados com motivo esocial 1 e 3
			-- Codigo 1
			create temp table dados_clonados_codigo1 as (
				SELECT
					*
				FROM
					configuracoes.mapeamentoatributosesocial
				WHERE
					db39_campoorigem IN (
						SELECT
							db109_sequencial
				     	FROM
				     		dados_originais_codigo1
						)
				);
			
			-- Codigo 3
			create temp table dados_clonados_codigo3 as (
				SELECT
					*
				FROM
					configuracoes.mapeamentoatributosesocial
				WHERE
					db39_campoorigem IN (
						SELECT
							db109_sequencial
				     	FROM
				     		dados_originais_codigo3
						)
				);

			-- Pegamos os atributos onde serao inseridos os dados
			-- Codigo 1
			create temp table grupos_dados_inserir_codigo1 as (
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
				 			dados_clonados_codigo1
					)
				);

			-- Codigo 3
			create temp table grupos_dados_inserir_codigo3 as (
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
				 			dados_clonados_codigo3
					)
				);

SQL;
		DB::connection()->getPdo()->exec($sql);
	}

    public function up()
    {
		$this->geraTabelaTemporaria();
        $sql = <<<SQL
			-- Adicionamos a opcao 1 no codigo 3 e a opcao 3 no codigo 1
			-- codigo 1 pai
			insert
                into
                db_cadattdinamicoatributosopcoes (
                select
                    nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                    db18_cadattdinamicoatributos,
                    '3',
                    'Cod. 3: Acidente/Doença não relacionada ao trabalho'
                from
                    db_cadattdinamicoatributosopcoes dc
                where
                    db18_cadattdinamicoatributos = (
						select
							dc.db109_sequencial
						from
							db_cadattdinamicoatributos dc
							inner join 	dados_originais_codigo1 doc1 on doc1.db109_sequencial = dc.db109_sequencial
						where
							db109_nome = 'motivo_esocial'
						)
					);

			-- Codigo 1 filho
			insert
                into
                db_cadattdinamicoatributosopcoes (				
					select
						nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
						db18_cadattdinamicoatributos,
						'3',
                    	'Cod. 3: Acidente/Doença não relacionada ao trabalho'
			        from
            			db_cadattdinamicoatributosopcoes dc
					where
						db18_cadattdinamicoatributos in (
							select
								dc.db109_sequencial
							from
								db_cadattdinamicoatributos dc
								inner join 	grupos_dados_inserir_codigo1 doc1 on doc1.db109_sequencial = dc.db109_sequencial
							where
								dc.db109_nome = 'motivo_esocial'

					)
			);

			-- Codigo 3 pai
			insert
                into
                db_cadattdinamicoatributosopcoes (
					select
						nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
						db18_cadattdinamicoatributos,
						'1',
						'Cod. 1: Acidente/doença do trabalho'
					from
						db_cadattdinamicoatributosopcoes dc
					where
						db18_cadattdinamicoatributos = (
						select
							dc.db109_sequencial
						from
							db_cadattdinamicoatributos dc
							inner join 	dados_originais_codigo3 doc3 on doc3.db109_sequencial = dc.db109_sequencial
						where
							db109_nome = 'motivo_esocial'
					)
			);

			-- Codigo 3 filho
			insert
                into
                db_cadattdinamicoatributosopcoes (				
					select
						nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
						db18_cadattdinamicoatributos,
						'1',
						'Cod. 1: Acidente/doença do trabalho'
			        from
            			db_cadattdinamicoatributosopcoes dc
					where
						db18_cadattdinamicoatributos in (
							select
								dc.db109_sequencial
							from
								db_cadattdinamicoatributos dc
								inner join 	grupos_dados_inserir_codigo3 doc3 on doc3.db109_sequencial = dc.db109_sequencial
							where
								dc.db109_nome = 'motivo_esocial'

					)
			);
SQL;
		DB::connection()->getPdo()->exec($sql);
    }

	public function down() {
		$this->geraTabelaTemporaria();
	
		$sql = <<<SQL

		-- Removendo opcao 3 do motivo 1 - PAI
		delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_sequencial in (
			SELECT
				db18_sequencial
			FROM
				configuracoes.db_cadattdinamicoatributos cd
				inner join dados_originais_codigo1 doc1 on cd.db109_sequencial = doc1.db109_sequencial
					inner join configuracoes.db_cadattdinamicoatributosopcoes on db18_cadattdinamicoatributos = cd.db109_sequencial
			where 
				cd.db109_nome in ('motivo_esocial') 
				and db18_opcao = '3'
		);

		-- Removendo opcao 3 do motivo 1 - FILHO
		delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_sequencial in (
			SELECT
				db18_sequencial
			FROM
				configuracoes.db_cadattdinamicoatributos cd
				inner join grupos_dados_inserir_codigo1 doc1 on cd.db109_sequencial = doc1.db109_sequencial
					inner join configuracoes.db_cadattdinamicoatributosopcoes on db18_cadattdinamicoatributos = cd.db109_sequencial
			where 
				cd.db109_nome in ('motivo_esocial') 
				and db18_opcao = '3'
		);


		-- Removendo opcao 1 do motivo 3 - PAI
		delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_sequencial in (
			SELECT
				db18_sequencial
			FROM
				configuracoes.db_cadattdinamicoatributos cd
				inner join dados_originais_codigo3 doc3 on cd.db109_sequencial = doc3.db109_sequencial
					inner join configuracoes.db_cadattdinamicoatributosopcoes on db18_cadattdinamicoatributos = cd.db109_sequencial
			where 
				cd.db109_nome in ('motivo_esocial') 
				and db18_opcao = '1'
		);

		-- Removendo opcao 1 do motivo 3 - FILHO
		delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_sequencial in (
			SELECT
				db18_sequencial
			FROM
				configuracoes.db_cadattdinamicoatributos cd
				inner join grupos_dados_inserir_codigo3 doc3 on cd.db109_sequencial = doc3.db109_sequencial
					inner join configuracoes.db_cadattdinamicoatributosopcoes on db18_cadattdinamicoatributos = cd.db109_sequencial
			where 
				cd.db109_nome in ('motivo_esocial') 
				and db18_opcao = '1'
		);

SQL;

        DB::connection()->getPdo()->exec($sql);
	}

}
