<?php

use Classes\PostgresMigration;

class M10565AdicionaItemLista extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_estruturavalor values (
                (select nextval('db_estruturavalor_db121_sequencial_seq')),
                150000,
                '14.14',
                'Guincho intramunicipal, guindastes e içamento.',
                144,
                2,
                2
            );
            
            insert into issgruposervico (
                q126_sequencial,
                q126_db_estruturavalor)
                values (
                    (select nextval('issgruposervico_q126_sequencial_seq')),
                    (select currval('db_estruturavalor_db121_sequencial_seq'))
            );

            insert into issconfiguracaogruposervico ( 
                q136_sequencial, 
                q136_issgruposervico, 
                q136_exercicio, 
                q136_tipotributacao, 
                q136_valor, 
                q136_localpagamento
                ) values (
                    (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
                    (select currval('issgruposervico_q126_sequencial_seq')),
                    2018,
                    2,
                    2,
                    1
            );
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
        delete 
        from 
            issconfiguracaogruposervico 
        where 
            q136_sequencial  = (
                select 
                    q136_sequencial
                from 
                    issconfiguracaogruposervico
                    inner join issgruposervico on q136_issgruposervico = q126_sequencial
                    inner join db_estruturavalor on q126_db_estruturavalor = db121_sequencial
                where 
                    db121_db_estrutura = 150000 and 
                    db121_estrutural = '14.14' and 
                    q136_exercicio = 2018
                );

        delete 
        from 
            issgruposervico 
        where 
            q126_sequencial  = (
                select 
                    q126_sequencial
                from 
                    issgruposervico
                    inner join db_estruturavalor on q126_db_estruturavalor = db121_sequencial
                where 
                    db121_db_estrutura = 150000 and 
                    db121_estrutural = '14.14'
            );
        
        delete 
        from 
            db_estruturavalor 
        where 
            db121_db_estrutura = 150000 and 
            db121_estrutural = '14.14';
SQL;
        $this->execute($sql);
    }
}
