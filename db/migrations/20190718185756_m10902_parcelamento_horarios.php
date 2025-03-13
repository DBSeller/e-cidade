<?php

use Classes\PostgresMigration;

class M10902ParcelamentoHorarios extends PostgresMigration
{
    public function up()
    {

        $this->createDicionarioDados();
        $this->createTableHonorariosParcelamento();
        $this->createTableCustasParcela();
        $this->alterTaxa();
    }

    public function down()
    {

        $this->dropDicionarioDados();
        $this->dropTableHonorariosParcelamento();
        $this->dropTableCustasParcela();
        $this->dropTaxa();
    }

    public function createDicionarioDados()
    {

        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228150 ,'Liberação do Parcelamento de Honorário' ,'Liberação do Parcelamento de Honorário' ,'arr4_honorarioparcelamento001.php', '1' ,'1' ,'Liberação do Parcelamento de Honorário' ,'false' );
            delete from db_menu where id_item_filho = 228150 AND modulo = 313;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10469 ,228150 ,2 ,313 );
            insert into db_syscampo values(1010594,'ar36_honorario','bool','Campo logico de honorario','f', 'Campo honorario',1,'f','f','f',5,'text','Campo honorario');
            delete from db_sysarqcamp where codarq = 3221;
            insert into db_sysarqcamp values(3221,18215,1,2123);
            insert into db_sysarqcamp values(3221,18216,2,0);
            insert into db_sysarqcamp values(3221,18272,3,0);
            insert into db_sysarqcamp values(3221,18217,4,0);
            insert into db_sysarqcamp values(3221,18218,5,0);
            insert into db_sysarqcamp values(3221,18219,6,0);
            insert into db_sysarqcamp values(3221,18220,7,0);
            insert into db_sysarqcamp values(3221,18221,8,0);
            insert into db_sysarqcamp values(3221,1009487,9,0);
            insert into db_sysarqcamp values(3221,1009488,10,0);
            insert into db_sysarqcamp values(3221,1009597,11,0);
            insert into db_sysarqcamp values(3221,1010594,12,0);
            insert into db_sysarquivo values (1010456, 'honorariosparcelamento', 'Tabela responsável por armazenar as informações de parcelamento honorario', 'ar43', '2019-07-18', 'honorariosparcelamento', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (54,1010456);
            insert into db_syscampo values(1010595,'ar43_sequencial','int8','Campo responsavel por armazenar o numero sequencial dos registros da tabela','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1010596,'ar43_processoforo','int4','Campo responsavel por armazenar o numero do processo do foro','0', 'processoforo',10,'t','f','f',1,'text','processoforo');
            insert into db_syscampo values(1010597,'ar43_inicial','int4','Campo responsavel por armazenar o numero da inicial','0', 'inicial',10,'t','f','f',1,'text','inicial');
            insert into db_syscampo values(1010598,'ar43_numeroparcelas','int4','Campo responsavel por armazenar a quantidade de parcelas','0', 'numeroparcelas',10,'f','f','f',1,'text','numeroparcelas');
            delete from db_sysarqcamp where codarq = 1010456;
            insert into db_sysarqcamp values(1010456,1010595,1,0);
            insert into db_sysarqcamp values(1010456,1010596,2,0);
            insert into db_sysarqcamp values(1010456,1010597,3,0);
            insert into db_sysarqcamp values(1010456,1010598,4,0);
            delete from db_sysprikey where codarq = 1010456;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010456,1010595,1,1010595);
            delete from db_sysforkey where codarq = 1010456 and referen = 0;
            insert into db_sysforkey values(1010456,1010596,1,3069,0);
            delete from db_sysforkey where codarq = 1010456 and referen = 0;
            insert into db_sysforkey values(1010456,1010597,1,108,0);
            insert into db_sysindices values(1008479,'honorariosparcelamento_sequencial_in',1010456,'0');
            insert into db_syscadind values(1008479,1010595,1);
            insert into db_sysindices values(1008480,'honorariosparcelamento_processoforo_in',1010456,'0');
            insert into db_syscadind values(1008480,1010596,1);
            insert into db_sysindices values(1008481,'honorariosparcelamento_inicial_in',1010456,'0');
            insert into db_syscadind values(1008481,1010597,1);
            insert into db_syssequencia values(1000843, 'honorariosparcelamento_ar43_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000843 where codarq = 1010456 and codcam = 1010595;
            delete from db_sysarqcamp where codarq = 3230;
            insert into db_sysarqcamp values(3230,18256,1,2135);
            insert into db_sysarqcamp values(3230,18263,2,0);
            insert into db_sysarqcamp values(3230,18264,3,0);
            insert into db_sysarqcamp values(3230,18265,4,0);
            insert into db_sysarqcamp values(3230,18266,5,0);
            insert into db_sysarqcamp values(3230,20752,6,0);
            insert into db_syscampo values(1010644,'v44_sequencial','int4','Guarda o numero sequencial dos dados da tabela','0', 'Squencial',10,'f','f','f',1,'text','Squencial');
            insert into db_syscampo values(1010645,'v44_processoforopartilhacusta','int4','Código sequencial do cadastro da partilha gerada para o processo do foro','0', 'Processo foro partilha custa',10,'t','f','f',1,'text','Processo foro partilha custa');
            insert into db_syscampo values(1010646,'v44_inicialpartilhacustas','int4','Armazena a inicial','0', 'Inicial partilha custas',10,'t','f','f',1,'text','Inicial partilha custas');
            insert into db_syscampo values(1010647,'v44_numpre','int4','Armazena o numpre','0', 'Numpre',10,'f','f','f',1,'text','Numpre');
            insert into db_syscampo values(1010648,'v44_numpar','int4','Campo responsavel por armazenar o numero da parcela','0', 'Numero parcelas',10,'f','f','f',1,'text','Numero parcelas');
            insert into db_syscampo values(1010649,'v44_receit','int4','Armazena dados da receita','0', 'Receit',10,'f','f','f',1,'text','Receit');
            insert into db_sysarquivo values (1010461, 'custaparcela', 'Custa Parcela', 'v44', '2019-07-29', 'custaparcela', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (21,1010461);
            delete from db_sysarqcamp where codarq = 1010461;
            insert into db_sysarqcamp values(1010461,1010644,1,0);
            insert into db_sysarqcamp values(1010461,1010645,2,0);
            insert into db_sysarqcamp values(1010461,1010646,3,0);
            insert into db_sysarqcamp values(1010461,1010647,4,0);
            insert into db_sysarqcamp values(1010461,1010648,5,0);
            insert into db_sysarqcamp values(1010461,1010649,6,0);
            delete from db_sysprikey where codarq = 1010461;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010461,1010644,1,1010644);
            delete from db_sysforkey where codarq = 1010461 and referen = 0;
            insert into db_sysforkey values(1010461,1010645,1,3230,0);
            delete from db_sysforkey where codarq = 1010461 and referen = 0;
            insert into db_sysforkey values(1010461,1010646,1,1010235,0);
            insert into db_sysindices values(1008484,'custaparcela_sequencial_in',1010461,'0');
            insert into db_syscadind values(1008484,1010644,1);
            insert into db_sysindices values(1008485,'custaparcela_processoforopartilhacusta_in',1010461,'0');
            insert into db_syscadind values(1008485,1010645,1);
            insert into db_sysindices values(1008486,'custaparcela_inicialpartilhacustas_in',1010461,'0');
            insert into db_syscadind values(1008486,1010646,1);
            insert into db_syssequencia values(1000846, 'custaparcela_v44_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000846 where codarq = 1010461 and codcam = 1010644;

SQL;
        $this->execute($sql);
    }

    public function createTableHonorariosParcelamento()
    {

        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE arrecadacao.honorariosparcelamento_ar43_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            -- Módulo: arrecadacao
            CREATE TABLE arrecadacao.honorariosparcelamento(
                ar43_sequencial		int4 not null default 0,
                ar43_processoforo   int4,                                      
                ar43_inicial        int4,                                    
                ar43_numeroparcelas int4 not null,
                CONSTRAINT honorariosparcelamento_sequ_pk PRIMARY KEY (ar43_sequencial)
            );
            
            -- CHAVE ESTRANGEIRA
            ALTER TABLE honorariosparcelamento
            ADD CONSTRAINT honorariosparcelamento_processoforo_fk FOREIGN KEY (ar43_processoforo)
            REFERENCES processoforo;
            
            ALTER TABLE honorariosparcelamento
            ADD CONSTRAINT honorariosparcelamento_inicial_fk FOREIGN KEY (ar43_inicial)
            REFERENCES inicial;
            
            -- INDICES
            CREATE  INDEX honorariosparcelamento_sequencial_in ON honorariosparcelamento(ar43_sequencial);
            
            CREATE  INDEX honorariosparcelamento_processoforo_in ON honorariosparcelamento(ar43_processoforo);
            
            CREATE  INDEX honorariosparcelamento_inicial_in ON honorariosparcelamento(ar43_inicial);            
SQL;
        $this->execute($sql);
    }

    public function createTableCustasParcela()
    {

        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE custaparcela_v44_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
                
            -- TABELAS E ESTRUTURA
            -- Módulo: juridico
            CREATE TABLE juridico.custaparcela(
            v44_sequencial		int4 not null default 0,
            v44_processoforopartilhacusta int4,
            v44_inicialpartilhacustas int4,
            v44_numpre int4 not null,
            v44_numpar int4 not null,
            v44_receit int4 not null,
            CONSTRAINT custaparcela_sequ_pk PRIMARY KEY (v44_sequencial));
            
            -- CHAVE ESTRANGEIRA
            ALTER TABLE custaparcela
            ADD CONSTRAINT custaparcela_processoforopartilhacusta_fk FOREIGN KEY (v44_processoforopartilhacusta)
            REFERENCES processoforopartilhacusta;
            
            ALTER TABLE custaparcela
            ADD CONSTRAINT custaparcela_inicialpartilhacustas_fk FOREIGN KEY (v44_inicialpartilhacustas)
            REFERENCES inicialpartilhacustas;

            -- INDICES
            CREATE  INDEX custaparcela_sequencial_in ON custaparcela(v44_sequencial);
            CREATE  INDEX custaparcela_processoforopartilhacusta_in ON custaparcela(v44_processoforopartilhacusta);
            CREATE  INDEX custaparcela_inicialpartilhacustas_in ON custaparcela(v44_inicialpartilhacustas);
SQL;
        $this->execute($sql);
    }

    public function alterTaxa()
    {

        $sql = <<<SQL
            alter table arrecadacao.taxa add column ar36_honorario boolean default false;
SQL;
        $this->execute($sql);
    }

    public function dropDicionarioDados()
    {

        $sql = <<<SQL
            delete from db_itensmenu where id_item = 228150;
            delete from db_menu where id_item = 10469;
            delete from db_sysarqcamp where codcam = 1010594;
            delete from db_syscampo where codcam = 1010594;
            delete from db_sysarqcamp where codarq = 3221;
            delete from db_sysforkey where codarq = 1010456;
            delete from db_sysarqmod where codarq = 1010456;
            delete from db_sysarqcamp where codarq = 1010456;
            delete from db_sysarquivo where codarq = 1010456;
            delete from db_syscampo where codcam in (1010595, 1010596, 1010597, 1010598);
            delete from db_sysprikey where codarq = 1010456;
            delete from db_sysindices where codind in (1008479, 1008480, 1008481);
            delete from db_syscadind where codind in (1008479, 1008480, 1008481);
            delete from db_syssequencia where codsequencia = 1000843;
            delete from db_sysarqcamp where codarq = 3230;
            delete from db_sysarqmod where codarq = 1010461;
            delete from db_sysarqcamp where codarq = 1010461;
            delete from db_sysforkey where codarq = 1010461;
            delete from db_sysarquivo where codarq = 1010461;
            delete from db_syscampo where codcam in (1010644, 1010645,1010646, 1010647, 1010648,1010649);
            delete from db_sysindices where codind in (1008484, 1008485, 1008486);
            delete from db_syscadind where codind in (1008484, 1008485, 1008486);
            delete from db_syssequencia where codsequencia = 1000846;
SQL;
        $this->execute($sql);
    }

    public function dropTableHonorariosParcelamento()
    {

        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE arrecadacao.honorariosparcelamento CASCADE;
            --Criando drop sequences
            DROP SEQUENCE honorariosparcelamento_ar43_sequencial_seq;         
SQL;
        $this->execute($sql);
    }

    public function dropTableCustasParcela()
    {

        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE juridico.custaparcela CASCADE;
            --Criando drop sequences
            DROP SEQUENCE custaparcela_v44_sequencial_seq;
SQL;
        $this->execute($sql);
    }

    public function dropTaxa()
    {

        $sql = <<<SQL
            alter table arrecadacao.taxa drop column ar36_honorario;
SQL;
        $this->execute($sql);
    }
}
