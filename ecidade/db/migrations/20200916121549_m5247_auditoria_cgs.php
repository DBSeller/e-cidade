<?php

use Classes\PostgresMigration;

class M5247AuditoriaCgs extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionarioCgsAuditoria();
        $this->criaEstruturaCgsAuditoria();

    }

    public function down()
    {
        $this->deletaDicionarioCgsAuditoria();
        $this->deletaEstruturaCgsAuditoria();

    }

    public function criaDicionarioCgsAuditoria ()
    {
        $this->execute(<<<SQL
            -- Dicionario
            --
            insert into db_sysarquivo values (1010617, 'cgsauditoria', 'Auditoria do CGS', 'z18', '2020-09-16', 'Auditoria do CGS', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (1000004,1010617);
            insert into db_syscampo values(1011807,'z18_sequencial','int4','sequencial da tabela cgsauditoria','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011808,'z18_cgs','int4','Campo que referencia o sequencial da tabela CGS','0', 'CGS',10,'f','f','f',1,'text','CGS');
            insert into db_syscampo values(1011809,'z18_usuario','varchar(255)','usuário do sistema','0', 'usuário do sistema',255,'f','t','f',1,'text','usuário do sistema');
            -- Vinculando campos na tabela cgsauditoria
            insert into db_sysarqcamp values(1010617,1011807,1,0);
            insert into db_sysarqcamp values(1010617,1011808,2,0);
            insert into db_sysarqcamp values(1010617,1011809,3,0);
            -- Cria PK
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010617,1011807,1,1011807);
            -- Cria SEQ
            insert into db_syssequencia values(1000969, 'cgsauditoria_z18_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000969 where codarq = 1010617 and codcam = 1011807;
            -- Cria FK
            insert into db_sysforkey values(1010617,1011808,1,1010142,0);
SQL
        ); 
    }

    public function criaEstruturaCgsAuditoria ()
    {
        $this->execute(<<<SQL
            -- Estrutura
            --
            CREATE table ambulatorial.cgsauditoria (

                z18_sequencial SERIAL,
                z18_cgs int, 
                z18_usuario varchar(255)
                                
            );

            -- Referencia a FK á outra tabela
            ALTER TABLE ambulatorial.cgsauditoria add CONSTRAINT "cgsauditoria_z18_cgs_fk" FOREIGN KEY ("z18_cgs") REFERENCES "cgs"("z01_i_numcgs");
SQL
        ); 
    }

    public function deletaDicionarioCgsAuditoria ()
    {
        $this->execute(<<<SQL
            -- Deleta FK
            delete from db_sysforkey where codarq = 1010617;
            -- Deleta PK
            delete from db_sysprikey where codarq = 1010617;
            -- Deleta SEQ
            delete from db_syssequencia where codsequencia = 1000969;

            -- desvincula os campos da tabela
            delete from db_sysarqmod where codmod = 1000004;
            
            -- Deleta arq campos
            delete from db_sysarqcamp where codarq = 1010617;

            -- Deleta campos
            delete from db_syscampo where codcam in (
                1011807,
                1011808,
                1011809
            );

            -- Deleta o arq
            delete from db_sysarquivo where codarq = 1010617;
SQL
        );
    }

    public function deletaEstruturaCgsAuditoria ()
    {
        $this->execute(<<<SQL
            -- Deleta a tabela
            DROP TABLE ambulatorial.cgsauditoria;
SQL
        );
    }
}
