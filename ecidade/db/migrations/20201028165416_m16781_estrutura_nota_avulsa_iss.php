<?php

use Classes\PostgresMigration;

class M16781EstruturaNotaAvulsaIss extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->upEstruturaRegraEmissao();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $sql = <<<SQL
                insert into db_sysarquivo values (1010627, 'confvencissqnavulso', 'Tabela de configuração de Vencimento issqn avulso.', 'j178', '2020-10-28', 'Tabela de configuração de Vencimento issqn avulso', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (3,1010627);
                insert into db_syscampo values(1011870,'j178_receita','int4','Campo da receita do débito.','0', 'Receita',10,'f','f','f',1,'text','Receita');
                insert into db_syscampo values(1011871,'j178_histdebito','int8','Campo de histórico do débito.','0', 'Histórico Débito',10,'f','f','f',1,'text','Histórico Débito');
                insert into db_syscampo values(1011872,'j178_tipodebito','int8','Campo de tipo do débito.','0', 'Tipo Débito',10,'f','f','f',1,'text','Tipo Débito');
                insert into db_syscampo values(1011873,'j178_diavenc','int8','Campo do dia de vencimento do débito.','0', 'Dia Vencimento',10,'t','f','f',1,'text','Dia Vencimento');
                delete from db_sysarqcamp where codarq = 1010627;
                insert into db_sysarqcamp values(1010627,1011870,1,0);
                insert into db_sysarqcamp values(1010627,1011871,2,0);
                insert into db_sysarqcamp values(1010627,1011872,3,0);
                insert into db_sysarqcamp values(1010627,1011873,4,0);
                delete from db_sysforkey where codarq = 1010627 and referen = 0;
                insert into db_sysforkey values(1010627,1011871,1,73,0);
                delete from db_sysforkey where codarq = 1010627 and referen = 0;
                insert into db_sysforkey values(1010627,1011870,1,75,0);
                delete from db_sysforkey where codarq = 1010627 and referen = 0;
                insert into db_sysforkey values(1010627,1011872,1,82,0);
                insert into db_syscampo values(1011874,'j178_anousu','date','Campo do ano.','null', 'Ano',10,'f','f','f',1,'text','Ano');
                delete from db_sysarqcamp where codarq = 1010627;
                insert into db_sysarqcamp values(1010627,1011870,1,0);
                insert into db_sysarqcamp values(1010627,1011871,2,0);
                insert into db_sysarqcamp values(1010627,1011872,3,0);
                insert into db_sysarqcamp values(1010627,1011873,4,0);
                insert into db_sysarqcamp values(1010627,1011874,5,0);


SQL;
        $this->execute($sql);
    }

    public function downDicionario()
    {
        $sql = <<<SQL
                delete from db_sysarqcamp where codarq = 1010627;
                delete from db_sysforkey where codarq = 1010627;
                delete from db_sysarqcamp where codarq = 1010627;
                delete from db_syscampo where codcam in (1011870, 1011871, 1011872, 1011873, 1011874);
                delete from db_sysarqmod where codarq = 1010627;
                delete from db_sysarquivo where codarq = 1010627;



SQL;
        $this->execute($sql);

    }

    public function upEstrutura()
    {
        $sql = <<<SQL
                CREATE TABLE issqn.confvencissqnavulso (
                    j178_receita int not null,
                    j178_histdebito int not null,
                    j178_tipodebito int not null,
                    j178_diavenc int,
                    j178_anousu int not null,

                    CONSTRAINT fk_receita FOREIGN KEY (j178_receita) REFERENCES tabrec(k02_codigo),
                    CONSTRAINT fk_hist FOREIGN KEY (j178_histdebito) REFERENCES  histcalc(k01_codigo),
                    CONSTRAINT fk_tipodebito FOREIGN KEY (j178_tipodebito) REFERENCES arretipo(k00_tipo),
                    CONSTRAINT confvencissqnavulso_unique_anousu UNIQUE (j178_anousu)

                )

SQL;

        $this->execute($sql);

    }

    public function downEstrutura()
    {
        $sql = <<<SQL
               DROP TABLE IF EXISTS issqn.confvencissqnavulso
SQL;

        $this->execute($sql);
        $this->downEstruturaRegraEmissao();
    }

    private function upEstruturaRegraEmissao()
    {
        $this->execute(<<<SQL
            INSERT INTO caixa.cadtipomod VALUES (30, 'RECIBO DA CGF NOTA AVULSA');

            INSERT INTO cadmodcarne
                (
                    k47_sequencial,
                    k47_descr,
                    k47_tipoconvenio
                )
            VALUES
                (
                    103,
                    'RECIBO ISSQN NOTA AVULSA',
                    2
                );

            INSERT INTO modcarnepadrao
                (
                    k48_sequencial,
                    k48_cadconvenio,
                    k48_cadtipomod,
                    k48_instit,
                    k48_dataini,
                    k48_datafim,
                    k48_parcini,
                    k48_parcfim
                )
            VALUES
                (
                    nextval('modcarnepadrao_k48_sequencial_seq'),
                    (SELECT ar11_sequencial FROM cadconvenio LIMIT 1),
                    30,
                    1,
                    '1900-01-01',
                    '2099-12-31',
                    0,
                    0
                );

            INSERT INTO modcarnepadraocadmodcarne
                (
                    m01_sequencial,
                    m01_cadmodcarne,
                    m01_modcarnepadrao
                )
            VALUES
                (
                    nextval('modcarnepadraocadmodcarne_m01_sequencial_seq'),
                    103,
                    currval('modcarnepadrao_k48_sequencial_seq')
                );
SQL
        );
    }

    private function downEstruturaRegraEmissao()
    {
        $this->execute(<<<SQL
            DELETE FROM modcarnepadraocadmodcarne WHERE m01_cadmodcarne = 103;
            DELETE FROM modcarnepadrao WHERE k48_cadtipomod = 30;
            DELETE FROM cadmodcarne WHERE k47_sequencial = 103;
            DELETE FROM cadtipomod WHERE k46_sequencial = 30;
SQL
        );
    }
}
