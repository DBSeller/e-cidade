<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21707FumamAlteraEstruturaSituacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upTabelas();
    }

    public function upTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            ALTER TABLE fumam.associadoservicos DROP column if exists transicaosituac;
            ALTER TABLE fumam.associadoservicos add column transicaosituac bool not null default true;
            UPDATE fumam.associadoservicos SET transicaosituac = false WHERE fm12_situacao = 2;
            ALTER TABLE fumam.associadoservicos DROP column if exists fm12_situacao;
            ALTER TABLE fumam.associadoservicos add column fm12_situacao bool not null default true;
            UPDATE fumam.associadoservicos SET fm12_situacao = transicaosituac WHERE transicaosituac = false;
            ALTER TABLE fumam.associadoservicos DROP column if exists transicaosituac;
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm12_situacao',
                   '{ "descricao": "Situação do Serviço",
                      "rotulo": "Situação do Serviço",
                      "rotulorel": "Situação do Serviço",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 5,
                      "tamanho": 1,
                      "tipoobj": "text"
                    }') ;

            ALTER TABLE fumam.profissionaisprestadores DROP column if exists transicaosituac;
            ALTER TABLE fumam.profissionaisprestadores add column transicaosituac bool not null default true;
            UPDATE fumam.profissionaisprestadores SET transicaosituac = false WHERE fm07_situacao = 2;
            ALTER TABLE fumam.profissionaisprestadores DROP column if exists fm07_situacao;
            ALTER TABLE fumam.profissionaisprestadores add column fm07_situacao bool not null default true;
            UPDATE fumam.profissionaisprestadores SET fm07_situacao = transicaosituac WHERE transicaosituac = false;
            ALTER TABLE fumam.profissionaisprestadores DROP column if exists transicaosituac;
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.profissionaisprestadores.fm07_situacao',
                   '{ "descricao": "Situação do Profissional",
                      "rotulo": "Situação do Profissional",
                      "rotulorel": "Situação do Profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 5,
                      "tamanho": 1,
                      "tipoobj": "text"
                    }') ;

            ALTER TABLE fumam.servicosprestadores DROP column if exists transicaosituac;
            ALTER TABLE fumam.servicosprestadores add column transicaosituac bool not null default true;
            UPDATE fumam.servicosprestadores SET transicaosituac = false WHERE fm08_situacao = 2;
            ALTER TABLE fumam.servicosprestadores DROP column if exists fm08_situacao;
            ALTER TABLE fumam.servicosprestadores add column fm08_situacao bool not null default true;
            UPDATE fumam.servicosprestadores SET fm08_situacao = transicaosituac WHERE transicaosituac = false;
            ALTER TABLE fumam.servicosprestadores DROP column if exists transicaosituac;
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.servicosprestadores.fm08_situacao',
                   '{ "descricao": "Situação do Serv. Prestado",
                      "rotulo": "Situação do Serv. Prestado",
                      "rotulorel": "Situação do Serv. Prestado",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 5,
                      "tamanho": 1,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.servicosprestadores.fm08_codigo',
                   '{ "descricao": "Serviços Prestados",
                      "rotulo": "Serviços Prestados",
                      "rotulorel": "Serviços Prestados",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.servicosprestadores.fm08_servico',
                   '{ "descricao": "Código do Serviço",
                      "rotulo": "Código do Serviço",
                      "rotulorel": "Código do Serviço",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

SQL
        );

    }

    public function upDicionario()
    {
        return true;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downTabelas();
    }

    public function downDicionario()
    {
        return true;
    }

    public function downTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            ALTER TABLE fumam.associadoservicos DROP column if exists transicaosituac;
            ALTER TABLE fumam.associadoservicos add column transicaosituac integer not null default 1;
            UPDATE fumam.associadoservicos SET transicaosituac = 2 WHERE fm12_situacao = false;
            ALTER TABLE fumam.associadoservicos DROP column if exists fm12_situacao;
            ALTER TABLE fumam.associadoservicos add column fm12_situacao integer not null default 1;
            UPDATE fumam.associadoservicos SET fm12_situacao = transicaosituac WHERE transicaosituac = 2;
            ALTER TABLE fumam.associadoservicos DROP column if exists transicaosituac;
            ALTER TABLE ONLY fumam.associadoservicos
                ADD CONSTRAINT associadoservicos_situacao_fk FOREIGN KEY (fm12_situacao) REFERENCES fumam.associadosituacao(fm02_situacao);
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm12_situacao',
                   '{ "descricao": "Situação do Serviço",
                      "rotulo": "Situação do Serviço",
                      "rotulorel": "Situação do Serviço",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            ALTER TABLE fumam.profissionaisprestadores DROP column if exists transicaosituac;
            ALTER TABLE fumam.profissionaisprestadores add column transicaosituac integer not null default 1;
            UPDATE fumam.profissionaisprestadores SET transicaosituac = 2 WHERE fm07_situacao = false;
            ALTER TABLE fumam.profissionaisprestadores DROP column if exists fm07_situacao;
            ALTER TABLE fumam.profissionaisprestadores add column fm07_situacao integer not null default 1;
            UPDATE fumam.profissionaisprestadores SET fm07_situacao = transicaosituac WHERE transicaosituac = 2;
            ALTER TABLE fumam.profissionaisprestadores DROP column if exists transicaosituac;
            ALTER TABLE ONLY fumam.profissionaisprestadores
                ADD CONSTRAINT profissionaisprestadores_situacao_fk FOREIGN KEY (fm07_situacao) REFERENCES fumam.associadosituacao(fm02_situacao);
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.profissionaisprestadores.fm07_situacao',
                   '{ "descricao": "Situação do Profissional",
                      "rotulo": "Situação do Profissional",
                      "rotulorel": "Situação do Profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            ALTER TABLE fumam.servicosprestadores DROP column if exists transicaosituac;
            ALTER TABLE fumam.servicosprestadores add column transicaosituac integer not null default 1;
            UPDATE fumam.servicosprestadores SET transicaosituac = 2 WHERE fm08_situacao = false;
            ALTER TABLE fumam.servicosprestadores DROP column if exists fm08_situacao;
            ALTER TABLE fumam.servicosprestadores add column fm08_situacao integer not null default 1;
            UPDATE fumam.servicosprestadores SET fm08_situacao = transicaosituac WHERE transicaosituac = 2;
            ALTER TABLE fumam.servicosprestadores DROP column if exists transicaosituac;
            ALTER TABLE ONLY fumam.servicosprestadores
                ADD CONSTRAINT profissionaisprestadores_situacao_fk FOREIGN KEY (fm08_situacao) REFERENCES fumam.associadosituacao(fm02_situacao);
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.servicosprestadores.fm08_situacao',
                   '{ "descricao": "Situação do Serv. Prestado",
                      "rotulo": "Situação do Serv. Prestado",
                      "rotulorel": "Situação do Serv. Prestado",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

SQL
        );
    }
}
