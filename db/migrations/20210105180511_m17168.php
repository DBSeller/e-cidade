<?php

use Classes\PostgresMigration;

class M17168 extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();
        $this->migracao();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function dicionario()
    {
        $this->execute(<<<SQL
            insert into db_syscampo
            values (1011941,'l44_obrigalicitacao','bool','Se deve obrigar informar número da licitação','f', 'Obriga informar licitação',1,'f','f','f',5,'text','Obriga informar licitação'),
                   (1011942,'pc50_ativo','bool','Se o Tipo de Compra esta ativo para novos cadastros','t', 'Ativo',1,'f','f','f',5,'text','Ativo');

            insert into db_sysarqcamp
            values (3145,1011941,6,0),
                   (866,1011942,4,0);
SQL
        );
    }

    private function estrutura()
    {
        $this->execute(<<<SQL
        alter table empenho.empempenho alter column e60_numerol type varchar(25);
        alter table empenho.empautoriza alter column e54_numerl type varchar(25);

        update empempenho set e60_numerol = trim(e60_numerol);
        update empautoriza set e54_numerl = trim(e54_numerl);

        alter table compras.pctipocompra add column pc50_ativo boolean default true;
        alter table licitacao.pctipocompratribunal add column l44_obrigalicitacao boolean default false;
SQL
        );
    }

    private function dicionarioDown()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam in (1011941, 1011942);
            delete from db_syscampo where codcam in (1011941, 1011942);
SQL
        );
    }

    private function estruturaDown()
    {
        $this->execute(<<<SQL
            alter table compras.pctipocompra drop column pc50_ativo;
            alter table licitacao.pctipocompratribunal drop column l44_obrigalicitacao;
SQL
        );
    }

    private function migracao()
    {
        // informar os tipos onde a licitação é obrigatória
        $this->execute(<<<SQL
            update licitacao.pctipocompratribunal set l44_obrigalicitacao = true
             where l44_uf = 'RS' and l44_sigla in ('PRD', 'PRI', 'CNV', 'TMP', 'CNC', 'PRP', 'PRE', 'RIN', 'CNS', 'RPO', 'PRD', 'CHP', 'CPC', 'RDC', 'CPP', 'MAI', 'LEI', 'ESE', 'EST', 'LEE', 'RDE', 'PDE');
SQL
        );

        // migrar o campo número licitacao da autorização
        $this->execute(<<<SQL
            with dados as (
              select e54_autori,
                     substring(e54_numerl, 1, length(e54_numerl) - 4) as numero,
                     substring(e54_numerl, length(e54_numerl) - 3) as ano
                from empenho.empautoriza
               where e54_numerl is not null
                 and e54_numerl <> ' '
                 and e54_numerl <> ''
                 and length(e54_numerl) > 4
                 and strpos(e54_numerl, '/') = 0
            )
            update empenho.empautoriza set e54_numerl = numero || '/' || ano
              from dados
             where empautoriza.e54_autori = dados.e54_autori;
SQL
        );

        // migrar o campo número licitacao do empenho
        $this->execute(<<<SQL
            with dados as (
              select e60_numemp,
                     substring(e60_numerol, 1, length(e60_numerol) - 4) as numero,
                     substring(e60_numerol, length(e60_numerol) - 3) as ano
                from empenho.empempenho
               where e60_numerol is not null
                 and e60_numerol <> ' '
                 and e60_numerol <> ''
                 and length(e60_numerol) > 4
                 and strpos(e60_numerol, '/') = 0
            )
            update empenho.empempenho set e60_numerol = numero || '/' || ano
              from dados
             where empempenho.e60_numemp = dados.e60_numemp;
SQL
        );

    }
}
