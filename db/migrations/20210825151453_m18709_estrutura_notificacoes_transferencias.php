<?php

use Classes\PostgresMigration;

class M18709EstruturaNotificacoesTransferencias extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
        $this->parametrosDefault();

        $this->itemMenuUp();

        $this->execute("select configuracoes.fc_auditoria_cria_funcao('secretariadeeducacao.parametrosnotificacao');");
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
        $this->itemMenuDown();
    }

    private function dicionarioUp()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010823, 'parametrosnotificacao', 'Parametros de configuração, para notificação das transferências dos alunos entre escolas da rede e fora da rede', 'ed177', '2021-08-25', 'Parametros de Notificações de Trnasferências', 0, 't', 't', 't', 't' );
insert into db_sysarqmod values (61,1010823);

insert into db_syscampo values(1013402,'ed177_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1013403,'ed177_notificar_escolas','bool','Parâmetro para saber se é para notificar escolas cada vez que acontecer uma transferências','f', 'Notificar escolas',1,'f','f','f',5,'text','Notificar escolas');
insert into db_syscampo values(1013404,'ed177_notificar_secretaria','bool','Parâmetro para saber se é para notificar a secretaría de educação cada vez que acontecer uma transferência','f', 'Notificar secretaría de educação',1,'f','f','f',5,'text','Notificar secretaría de educação');
insert into db_syscampo values(1013405,'ed177_email_secretaria','varchar(255)','E-mail da secretaría de educação do município','', 'E-mail da secretaría de educação',255,'t','f','f',0,'text','E-mail da secretaría de educação');

delete from db_sysarqcamp where codarq = 1010823;
insert into db_sysarqcamp values(1010823,1013402,1,0);
insert into db_sysarqcamp values(1010823,1013403,2,0);
insert into db_sysarqcamp values(1010823,1013404,3,0);
insert into db_sysarqcamp values(1010823,1013405,4,0);

delete from db_sysprikey where codarq = 1010823;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010823,1013402,1,1013402);
SQL
        );
    }

    private function dicionarioDown()
    {
        $this->execute(<<<SQL
            delete from db_sysprikey where codarq = 1010823;
            delete from db_sysarqcamp where codarq = 1010823;
            delete from db_syscampo where codcam in (1013402, 1013403, 1013404, 1013405);
            delete from db_sysarqmod where codarq = 1010823;
            delete from db_sysarquivo where codarq = 1010823;
SQL
        );
    }

    private function estruturaUp()
    {
        $this->execute(<<<SQL
            create table secretariadeeducacao.parametrosnotificacao
            (
                ed177_codigo serial,
                ed177_notificar_escolas boolean not null,
                ed177_notificar_secretaria boolean not null,
                ed177_email_secretaria varchar
           );

           create index parametrosnotificacao_ed177_codigo_pk on parametrosnotificacao (ed177_codigo);
SQL
        );
    }

    private function estruturaDown()
    {
        $this->execute(<<<SQL
            drop table secretariadeeducacao.parametrosnotificacao;
SQL
        );
    }

    private function parametrosDefault()
    {
        $this->execute(<<<SQL
            insert into parametrosnotificacao
                    (ed177_notificar_escolas, ed177_notificar_secretaria, ed177_email_secretaria)
            values (false, false, '')
SQL
        );
    }

    private function itemMenuUp()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228564,
                        'Notificação de Transferências',
                        'Notificação de Transferências',
                        'sec4_email_transferencia001.php',
                        '1',
                        '1',
                        'Menu de configuração das notificações de transferência de alunos entre escolas por email.',
                        'true');
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (9081, 228564, 5, 7159);
SQL
        );
    }

    private function itemMenuDown()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228564;
            delete from db_itensmenu where id_item = 228564;
SQL
        );
    }
}
