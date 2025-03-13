<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M19612SiopeVersao21 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

          DROP SEQUENCE IF EXISTS pessoal.siopequalificacaogrupo_id_seq;
          CREATE SEQUENCE pessoal.siopequalificacaogrupo_id_seq
             INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
   
          create table IF NOT EXISTS pessoal.siopequalificacaogrupo (
   
             si05_id        int4 NOT NULL DEFAULT nextval('pessoal.siopequalificacaogrupo_id_seq'),
             si05_descricao varchar NOT NULL,
             CONSTRAINT siopequalificacaogrupo_id_pk PRIMARY KEY (si05_id)
          );
   
          COMMENT ON TABLE pessoal.siopequalificacaogrupo IS '{"descricao": "Tabela de cadastro dos tipos de qualificações do servidor",
                                                          "sigla": "si05",
                                                          "dataincl": "2022-02-09",
                                                          "rotulo": "siopequalificacaogrupo",
                                                          "tipotabela": "0",
                                                          "naolibclass": "false",
                                                          "naolibfunc": "false",
                                                          "naolibprog": "false",
                                                          "naolibform": "false"
                                                         }';
   
          COMMENT ON COLUMN pessoal.siopequalificacaogrupo.si05_id IS '{ "descricao": "Código do Grupo da Qualificação",
                                                                    "rotulo": "Código do Grupo da Qualificação",
                                                                    "rotulorel": "Código do Grupo da Qualificação",
                                                                    "maiusculo": false,
                                                                    "autocompl": false,
                                                                    "aceitatipo": 1,
                                                                    "tamanho": 10,
                                                                    "tipoobj": "text"
                                                                  }' ;

          COMMENT ON COLUMN pessoal.siopequalificacaogrupo.si05_descricao IS '{ "descricao": "Grupo da Qualificação",
                                                                           "rotulo": " Grupo da Qualificação",
                                                                           "rotulorel": " Grupo da Qualificação",
                                                                           "maiusculo": false,
                                                                           "autocompl": false,
                                                                           "aceitatipo": 3,
                                                                           "tamanho": 200,
                                                                           "tipoobj": "text"
                                                                         }' ;

          INSERT INTO pessoal.siopequalificacaogrupo (si05_descricao)
            VALUES ('Art. 61 da LBD');

          INSERT INTO pessoal.siopequalificacaogrupo (si05_descricao)
            VALUES ('Art. 1 da Lei nº 13.935/2019');

          ALTER TABLE pessoal.siopequalificacao ADD column si04_qualifgrupo integer;

          COMMENT ON COLUMN pessoal.siopequalificacao.si04_qualifgrupo IS '{ "descricao": "Código do Grupo da Qualificação",
                                                                          "rotulo": "Código do Grupo da Qualificação",
                                                                          "rotulorel": "Código do Grupo da Qualificação",
                                                                          "maiusculo": false,
                                                                          "autocompl": false,
                                                                          "aceitatipo": 1,
                                                                          "tamanho": 10,
                                                                          "tipoobj": "text"
                                                                        }' ;

          ALTER TABLE ONLY pessoal.siopequalificacao
            ADD CONSTRAINT siopequalificacao_qualifgrupo_fk FOREIGN KEY (si04_qualifgrupo) REFERENCES pessoal.siopequalificacaogrupo(si05_id);

          UPDATE pessoal.siopequalificacao
            SET si04_descricao = replace(si04_descricao, 'Art. 61 da LBD - ', ''),
                si04_qualifgrupo = 1
            WHERE si04_id < 6 ;

          UPDATE pessoal.siopequalificacao
            SET si04_descricao = replace(si04_descricao, 'Art. 1 da Lei nº 13.935/2019 - ', ''),
                si04_qualifgrupo = 2
            WHERE si04_id > 5 ;

          INSERT INTO pessoal.siopequalificacao (si04_descricao, si04_qualifgrupo)
                                    VALUES ('Outros', 1);

          INSERT INTO pessoal.siopequalificacao (si04_descricao, si04_qualifgrupo)
                                    VALUES ('Outros', 2);

          ALTER TABLE pessoal.siopequalificacao ALTER COLUMN si04_qualifgrupo SET not null;

          SELECT fc_gera_dicionario_apartir_tabela('pessoal', 'siopequalificacaogrupo');
          SELECT fc_gera_dicionario_apartir_tabela('pessoal', 'siopequalificacao');

          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;


SQL
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL

          ALTER TABLE pessoal.siopequalificacao DROP column IF EXISTS si04_qualifgrupo;

          DELETE FROM pessoal.siopequalificacao WHERE si04_descricao = 'Outros' ;

          SELECT setval('siopequalificacao_id_seq', (select max(si04_id) from pessoal.siopequalificacao));

          UPDATE pessoal.siopequalificacao
            SET si04_descricao = 'Art. 61 da LBD - '||si04_descricao
            WHERE si04_id < 6 ;

          UPDATE pessoal.siopequalificacao
            SET si04_descricao = 'Art. 1 da Lei nº 13.935/2019 - '||si04_descricao
            WHERE si04_id > 5 ;

          SELECT fc_remove_dicionario_tabela('pessoal', 'siopequalificacaogrupo');

          DROP TABLE IF EXISTS pessoal.siopequalificacaogrupo;

          DROP SEQUENCE IF EXISTS pessoal.siopequalificacaogrupo_id_seq;


SQL
        );

    }

}
