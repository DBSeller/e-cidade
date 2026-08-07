<?php

use Classes\PostgresMigration;

class PreProcesso extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    private function upDicionario()
    {
        $sql = <<<SQL
insert into db_sysarquivo values (1010464, 'preprocesso', 'Tabela que armazenará as informações de ouvidoria a serem lançadas no processo.', 'p106', '2019-08-05', 'Pré Processo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010464);
insert into db_syscampo values(1010655,'p106_sequencial','int4','Sequencial da tabela.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1010656,'p106_data','date','Data da ouvidoria.','null', 'Data',10,'f','f','f',1,'text','Data');
insert into db_syscampo values(1010657,'p106_usuario','int4','Usuário responsável pelo lançamento da ouvidoria.','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(1010658,'p106_cgm','int4','CGM titular da ouvidoria.','0', 'CGM',10,'f','f','f',1,'text','CGM');
insert into db_syscampo values(1010659,'p106_requerente','varchar(80)','Descrição do requerente.','', 'Requerente',80,'t','t','f',0,'text','Requerente');
insert into db_syscampo values(1010660,'p106_departamento','int4','Departamento inicial da ouvidoria.','0', 'Departamento',10,'f','f','f',1,'text','Departamento');
insert into db_syscampo values(1010661,'p106_observacao','text','Observação da ouvidoria.','', 'Observação',1,'t','t','f',0,'text','Observação');
insert into db_syscampo values(1010662,'p106_despacho','text','Informações do despacho.','', 'Despacho',1,'t','t','f',0,'text','Despacho');
insert into db_syscampo values(1010663,'p106_hora','varchar(5)','Hora do lançamento da ouvidoria.','', 'Hora',5,'f','t','f',0,'text','Hora');
insert into db_syscampo values(1010664,'p106_interno','bool','Controla se é interna ou não.','false', 'Interno',1,'f','f','f',5,'text','Interno');
insert into db_syscampo values(1010665,'p106_publico','bool','Controla se é um despacho público.','false', 'Despacho Público',1,'f','f','f',5,'text','Despacho Público');
insert into db_syscampo values(1010666,'p106_instituicao','int4','Instituição responsável pela ouvidoria.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
insert into db_syscampo values(1010667,'p106_ano','int4','Ano da ouvidoria.','0', 'Ano',10,'f','f','f',1,'text','Ano');
insert into db_syscampo values(1010668,'p106_metadados','text','Metadados vindos da ouvidoria externa.','', 'Metadados',1,'f','t','f',0,'text','Metadados');
insert into db_syscampo values(1010693,'p106_tipoprocesso','int4','Tipo de processo referente a ouvidoria aberta.','0', 'Tipo de Processo',10,'f','f','f',1,'text','Tipo de Processo');

insert into db_sysarqcamp values(1010464,1010655,1,0);
insert into db_sysarqcamp values(1010464,1010656,2,0);
insert into db_sysarqcamp values(1010464,1010657,3,0);
insert into db_sysarqcamp values(1010464,1010658,4,0);
insert into db_sysarqcamp values(1010464,1010659,5,0);
insert into db_sysarqcamp values(1010464,1010660,6,0);
insert into db_sysarqcamp values(1010464,1010661,7,0);
insert into db_sysarqcamp values(1010464,1010662,8,0);
insert into db_sysarqcamp values(1010464,1010663,9,0);
insert into db_sysarqcamp values(1010464,1010664,10,0);
insert into db_sysarqcamp values(1010464,1010665,11,0);
insert into db_sysarqcamp values(1010464,1010666,12,0);
insert into db_sysarqcamp values(1010464,1010667,13,0);
insert into db_sysarqcamp values(1010464,1010668,14,0);
insert into db_sysarqcamp values(1010464,1010693,15,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010464,1010655,1,1010659);

insert into db_sysforkey values(1010464,1010657,1,109,0);
insert into db_sysforkey values(1010464,1010658,1,42,0);
insert into db_sysforkey values(1010464,1010660,1,154,0);
insert into db_sysforkey values(1010464,1010666,1,83,0);
insert into db_sysforkey values(1010464,1010693,1,393,0);

insert into db_sysindices values(1008487,'preprocesso_usuario_in',1010464,'0');
insert into db_syscadind values(1008487,1010657,1);
insert into db_sysindices values(1008488,'preprocesso_cgm_in',1010464,'0');
insert into db_syscadind values(1008488,1010658,1);
insert into db_sysindices values(1008489,'preprocesso_departamento_in',1010464,'0');
insert into db_syscadind values(1008489,1010660,1);
insert into db_sysindices values(1008490,'preprocesso_instituicao_in',1010464,'0');
insert into db_syscadind values(1008490,1010666,1);
insert into db_sysindices values(1008492,'preprocesso_tipoprocesso_in',1010464,'0');
insert into db_syscadind values(1008492,1010693,1);
insert into db_syssequencia values(1000847, 'preprocesso_p106_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000847 where codarq = 1010464 and codcam = 1010655;


insert into db_sysarquivo values (1010465, 'preprocessoprotprocesso', 'Vínculo de um pré processo, com um processo criado.', 'p107', '2019-08-05', 'Pré Processo / Processo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010465);
insert into db_syscampo values(1010669,'p107_sequencial','int4','Sequencial da tabela.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1010670,'p107_preprocesso','int4','Sequencial da tabela preprocesso.','0', 'Pré Processo',10,'f','f','f',1,'text','Pré Processo');
insert into db_syscampo values(1010671,'p107_protprocesso','int4','Vínculo da tabela protprocesso.','0', 'Processo',10,'f','f','f',1,'text','Processo');

insert into db_sysarqcamp values(1010465,1010669,1,0);
insert into db_sysarqcamp values(1010465,1010670,2,0);
insert into db_sysarqcamp values(1010465,1010671,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010465,1010669,1,1010670);

insert into db_sysforkey values(1010465,1010670,1,1010464,0);
insert into db_sysforkey values(1010465,1010671,1,403,0);

insert into db_sysindices values(1008491,'preprocessoprotprocesso_preprocesso_protprocesso_in',1010465,'1');
insert into db_syscadind values(1008491,1010670,1);
insert into db_syscadind values(1008491,1010671,2);
insert into db_syssequencia values(1000848, 'preprocessoprotprocesso_p107_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000848 where codarq = 1010465 and codcam = 1010669;

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228156 ,'Gerar Processo' ,'Gerar Processo' ,'ouv4_gerarprocesso001.php' ,'1' ,'1' ,'Geração de um processo a partir de um pré processo da ouvidoria externa.' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7862 ,228156 ,4 ,7837 );
SQL;

        $this->execute($sql);
    }

    private function upDDL()
    {
        $sql = <<<SQL
            CREATE SEQUENCE protocolo.preprocesso_p106_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE protocolo.preprocesso(
            p106_sequencial int4 default nextval('protocolo.preprocesso_p106_sequencial_seq'),
            p106_data date not null,
            p106_usuario int4 not null,
            p106_cgm int4 not null,
            p106_requerente varchar(80),
            p106_departamento int4 not null,
            p106_observacao text,
            p106_despacho text,
            p106_hora varchar(5),
            p106_interno boolean default false,
            p106_publico boolean default false,
            p106_instituicao int4 not null,
            p106_ano int4 not null,
            p106_metadados text,
            p106_tipoprocesso int4,
            CONSTRAINT preprocesso_sequ_pk PRIMARY KEY (p106_sequencial));
            
            ALTER TABLE protocolo.preprocesso
            ADD CONSTRAINT preprocesso_cgm_fk FOREIGN KEY (p106_cgm)
            REFERENCES cgm;
            
            ALTER TABLE protocolo.preprocesso
            ADD CONSTRAINT preprocesso_instituicao_fk FOREIGN KEY (p106_instituicao)
            REFERENCES db_config;
            
            ALTER TABLE protocolo.preprocesso
            ADD CONSTRAINT preprocesso_departamento_fk FOREIGN KEY (p106_departamento)
            REFERENCES db_depart;
            
            ALTER TABLE protocolo.preprocesso
            ADD CONSTRAINT preprocesso_usuario_fk FOREIGN KEY (p106_usuario)
            REFERENCES db_usuarios;
            
            ALTER TABLE protocolo.preprocesso
            ADD CONSTRAINT preprocesso_tipoprocesso_fk FOREIGN KEY (p106_tipoprocesso)
            REFERENCES tipoproc;
            
            CREATE  INDEX preprocesso_cgm_in ON preprocesso(p106_cgm);
            
            CREATE  INDEX preprocesso_usuario_in ON preprocesso(p106_usuario);
            
            CREATE  INDEX preprocesso_departamento_in ON preprocesso(p106_departamento);
            
            CREATE  INDEX preprocesso_instituicao_in ON preprocesso(p106_instituicao);
            
            CREATE  INDEX preprocesso_tipoprocesso_in ON preprocesso(p106_tipoprocesso);
            
            
            CREATE SEQUENCE protocolo.preprocessoprotprocesso_p107_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE protocolo.preprocessoprotprocesso(
            p107_sequencial int4 default nextval('protocolo.preprocessoprotprocesso_p107_sequencial_seq'),
            p107_preprocesso int4 not null,
            p107_protprocesso int4 not null,
            CONSTRAINT preprocessoprotprocesso_sequ_pk PRIMARY KEY (p107_sequencial));
            
            ALTER TABLE protocolo.preprocessoprotprocesso
            ADD CONSTRAINT preprocessoprotprocesso_preprocesso_fk FOREIGN KEY (p107_preprocesso)
            REFERENCES preprocesso;
            
            ALTER TABLE protocolo.preprocessoprotprocesso
            ADD CONSTRAINT preprocessoprotprocesso_protprocesso_fk FOREIGN KEY (p107_protprocesso)
            REFERENCES protprocesso;
            
            CREATE UNIQUE INDEX preprocessoprotprocesso_preprocesso_protprocesso_in ON preprocessoprotprocesso(p107_preprocesso,p107_protprocesso);
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    private function downDicionario()
    {
        $sql = <<<SQL
delete from db_syssequencia where codsequencia = 1000848;
delete from db_syscadind where codind = 1008491;
delete from db_sysindices where codind = 1008491;
delete from db_sysforkey where codarq = 1010465;
delete from db_sysprikey where codarq = 1010465;
delete from db_sysarqcamp where codarq = 1010465;
delete from db_syscampo where codcam in(1010669, 1010670, 1010671);
delete from db_sysarqmod where codarq = 1010465;
delete from db_sysarquivo where codarq = 1010465;


delete from db_syssequencia where codsequencia = 1000847;
delete from db_syscadind where codind in(1008487, 1008488, 1008489, 1008490, 1008492);
delete from db_sysindices where codind in(1008487, 1008488, 1008489, 1008490, 1008492);
delete from db_sysforkey where codarq = 1010464;
delete from db_sysprikey where codarq = 1010464;
delete from db_sysarqcamp where codarq = 1010464;
delete from db_syscampo where codcam in(1010655, 1010656, 1010657, 1010658, 1010659, 1010660, 1010661, 1010662, 1010663);
delete from db_syscampo where codcam in(1010664, 1010665, 1010666, 1010667, 1010668, 1010693);
delete from db_sysarqmod where codarq = 1010464;
delete from db_sysarquivo where codarq = 1010464;

delete from db_menu where id_item_filho = 228156;
delete from db_itensmenu where id_item = 228156;
SQL;

        $this->execute($sql);
    }

    private function downDDL()
    {
        $sql = <<<SQL
            DROP TABLE IF EXISTS preprocesso CASCADE;
            DROP SEQUENCE IF EXISTS preprocesso_p106_sequencial_seq;
            
            DROP TABLE IF EXISTS preprocessoprotprocesso CASCADE;
            DROP SEQUENCE IF EXISTS preprocessoprotprocesso_p107_sequencial_seq;
SQL;

        $this->execute($sql);
    }
}
