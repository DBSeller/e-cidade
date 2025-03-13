<?php

use Classes\PostgresMigration;

class M18025UnificacaoIniciais extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            insert into juridico.situacao values (10, 'Inicial Alterada', 1);

            create table juridico.iniciaisunificadas
            (
                v45_sequencial serial not null,
                v45_inicialprimaria integer not null,
                v45_inicialsecundaria integer not null,
                v45_certidao integer not null,
                v45_dataunificacao timestamp not null,
                v45_usuario integer not null,
                constraint iniciaisunificadas_pk primary key (v45_sequencial),
                constraint inicialprimaria_inicial_fk foreign key (v45_inicialprimaria) references inicial(v50_inicial),
                constraint inicialsecundaria_inicial_fk foreign key (v45_inicialsecundaria) references inicial(v50_inicial),
                constraint certidao_certid_fk foreign key (v45_certidao) references certid(v13_certid),
                constraint usuario_db_usuarios_fk foreign key (v45_usuario) references db_usuarios(id_usuario)
            );
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            delete from juridico.situacao where v52_codsit = 10;

            drop table juridico.iniciaisunificadas;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228502 ,'Unificação de Iniciais' ,'Unificação de Iniciais' ,'jur01_unificainicial001.php' ,'1' ,'1' ,'Rotina para unificação de iniciais' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1789 ,228502 ,7 ,313 );

            insert into db_sysarquivo values (1010802, 'iniciaisunificadas', 'Guarda as iniciais que foram unificadas pela rotina de unificação.', 'v45', '2021-05-24', 'Iniciais Unificadas', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (21,1010802);

            insert into db_syscampo values(1013262,'v45_sequencial','int4','Sequencial da tabela iniciaisunificadas.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013263,'v45_inicialprimaria','int4','Salva a inicial primária.','0', 'Inicial Primária',11,'f','f','f',1,'text','Inicial Primária');
            insert into db_syscampo values(1013264,'v45_inicialsecundaria','int4','Salva a inicial secundária.','0', 'Inicial Secundária',11,'f','f','f',1,'text','Inicial Secundária');
            insert into db_syscampo values(1013265,'v45_certidao','int4','Salva a certidão','0', 'Certidão',11,'f','f','f',1,'text','Certidão');
            insert into db_syscampo values(1013266,'v45_dataunificacao','text','Salva a data da unificação','', 'Data Unificação',19,'f','f','f',0,'text','Data Unificação');
            insert into db_syscampo values(1013267,'v45_usuario','int4','Salva o usuário que executou a unificação.','0', 'Usuário',11,'f','f','f',1,'text','Usuário');

            insert into db_syssequencia values(1001007, 'iniciaisunificadas_v45_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010802,1013262,1,1001007);
            insert into db_sysarqcamp values(1010802,1013263,2,0);
            insert into db_sysarqcamp values(1010802,1013264,3,0);
            insert into db_sysarqcamp values(1010802,1013265,4,0);
            insert into db_sysarqcamp values(1010802,1013266,5,0);
            insert into db_sysarqcamp values(1010802,1013267,6,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010802,1013262,1,1013262);

            insert into db_sysforkey values(1010802,1013263,1,108,0);
            insert into db_sysforkey values(1010802,1013264,1,108,0);
            insert into db_sysforkey values(1010802,1013265,1,100,0);
            insert into db_sysforkey values(1010802,1013267,1,109,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228502;
            delete from db_itensmenu where id_item = 228502;

            delete from db_sysarqcamp where codarq = 1010802;
            delete from db_sysprikey where codarq = 1010802;
            delete from db_sysforkey where codarq = 1010802;

            delete from db_syscampo where codcam in (
                1013262,
                1013263,
                1013264,
                1013265,
                1013266,
                1013267
            );

            delete from db_sysarqmod where codarq = 1010802;
            delete from db_sysarquivo where codarq = 1010802;

            delete from db_syssequencia where codsequencia = 1001007;
SQL
        );
    }
}
