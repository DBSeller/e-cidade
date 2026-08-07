<?php

use Classes\PostgresMigration;

class M12588SisobraCadastroDeObras extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->createDicionarioDados();
        $this->createEstrutura();
    }

    public function down()
    {
        $this->dropDicionarioDados();
        $this->dropEstrutura();
    }
    
    public function createDicionarioDados()
    {
        $sql = <<<SQL

            -- tabela nova 'obrasconstrareacomplementar'
            insert into db_sysarquivo values (1010455, 'obrasconstrareacomplementar', 'Tabela para armazenar as áreas complementares', 'ob27', '2019-06-26', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (46,1010455);
            insert into db_syscampo values(1010559,'ob27_sequencial','int4','Campo responsável para armazenar o sequencial','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1010560,'ob27_construcao','int4','Campo responsável para armazenar a construção','0', 'construcao',10,'f','f','f',1,'text','construcao');
            insert into db_syscampo values(1010561,'ob27_descricao','varchar(100)','Campo responsável por armazenar a descrição da construção','', 'descricao',100,'f','t','f',0,'text','descricao');
            insert into db_syscampo values(1010562,'ob27_medida_area_coberta','float8','Campo responsável por armazenar a medida da área coberta','0', 'medida area coberta',15,'f','f','f',4,'text','medida area coberta');
            insert into db_syscampo values(1010563,'ob27_medida_area_descoberta','float8','Campo responsável por armazenar a medida área descoberta','0', 'medida área descoberta',15,'f','f','f',4,'text','medida área descoberta');
            insert into db_syscampo values(1010564,'ob27_ocupacao','int4','Armazena a ocupação','0', 'ocupacao',10,'f','f','f',1,'text','ocupacao');
            insert into db_syscampo values(1010565,'ob27_tipoconstrucao','int4','Armazena o tipo da construcao','0', 'tipo construcao',10,'f','f','f',1,'text','tipo construcao');
            insert into db_syscampo values(1010566,'ob27_tipolancamento','int4','Armazena o tipo lancamento','0', 'tipo lancamento',10,'f','f','f',1,'text','tipo lancamento');
            insert into db_syscampo values(1010567,'ob27_tipo','int4','Armazena o tipo da construção complementar','0', 'tipo',2,'f','f','f',1,'text','tipo');
            update db_syscampo set nomecam = 'ob27_medidaareacoberta', conteudo = 'float8', descricao = 'Campo responsável por armazenar a medida da área coberta', valorinicial = '0', rotulo = 'medida area coberta', nulo = 'f', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'medida area coberta' where codcam = 1010562;
            update db_syscampo set nomecam = 'ob27_medidaareadescoberta', conteudo = 'float8', descricao = 'Campo responsável por armazenar a medida área descoberta', valorinicial = '0', rotulo = 'medida área descoberta', nulo = 'f', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'medida área descoberta' where codcam = 1010563;
            insert into db_sysarqcamp values(1010455,1010559,1,0);
            insert into db_sysarqcamp values(1010455,1010560,2,0);
            insert into db_sysarqcamp values(1010455,1010561,3,0);
            insert into db_sysarqcamp values(1010455,1010562,4,0);
            insert into db_sysarqcamp values(1010455,1010563,5,0);
            insert into db_sysarqcamp values(1010455,1010564,6,0);
            insert into db_sysarqcamp values(1010455,1010565,7,0);
            insert into db_sysarqcamp values(1010455,1010566,8,0);
            insert into db_sysarqcamp values(1010455,1010567,9,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010455,1010559,1,1010559);
            insert into db_sysindices values(1008477,'obrasconstrareacomplementar_sequencial_in',1010455,'0');
            insert into db_syscadind values(1008477,1010559,1);
            update db_sysarquivo set nomearq = 'obrasconstrareacomplementar', descricao = 'Tabela para armazenar as áreas complementares', sigla = 'ob27', dataincl = '2019-06-26', rotulo = '', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010455;
            insert into db_sysarqarq values(0,1010455);
            insert into db_sysforkey values(1010455,1010560,1,953,0);
            insert into db_syssequencia values(1000842, 'obrasconstrareacomplementar_ob27_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000842 where codarq = 1010455 and codcam = 1010559;

            -- campos novos da tabela 'obras'
            insert into db_syscampo values(1010570,'ob01_responsavelprojeto','int4','Armazena responsável do projeto','0', 'Responsável pelo Projeto',10,'f','f','f',1,'text','Responsável pelo Projeto');
            insert into db_syscampo values(1010571,'ob01_arquitetoobra','int4','Armazena arquiteto da obra','0', 'Arquiteto da Obra',10,'f','f','f',1,'text','Arquiteto da Obra');
            insert into db_sysarqcamp values(946,1010571,1,0);
            insert into db_sysarqcamp values(946,1010570,2,0);
            insert into db_sysforkey values(946,1010570,1,1001,0);
            insert into db_sysforkey values(946,1010571,1,1001,0);

            insert into db_syscampo values(1010578,'ob07_areacoberta','float4','Informação referente a área coberta da área complementar.','0', 'Área Coberta',10,'f','f','f',4,'text','Área Coberta');
            insert into db_syscampo values(1010579,'ob07_areadescoberta','float4','Informação referente a área descoberta da área complementar.','0', 'Área Descoberta',10,'f','f','f',4,'text','Área Descoberta');
            insert into db_sysarqcamp values(952,1010579,12,0);
            insert into db_sysarqcamp values(952,1010578,13,0);

            insert into db_syscampo values(1010581,'ob09_ativo','bool','Controla se o habite-se está ativou ou cancelado.','true', 'Ativo',1,'f','f','f',5,'text','Ativo');
            insert into db_syscampo values(1010582,'ob09_datacancelamentoreativacao','date','Data em que foi feito o último cancelamento/reativação do habite-se.','null', 'Data de Cancelamento/Reativação',10,'t','f','f',1,'text','Data de Cancelamento/Reativação');
            insert into db_syscampo values(1010583,'ob09_datafinalobra','date','Data Final da Obra.','null', 'Data Final da Obra',10,'t','f','f',1,'text','Data Final da Obra');
            insert into db_sysarqcamp values(954,1010583,16,0);
            insert into db_sysarqcamp values(954,1010582,17,0);
            insert into db_sysarqcamp values(954,1010581,18,0);
            insert into db_sysindices values(1008478,'obrashabite_obrasalvara_in',954,'0');

            update db_syscampo set descricao = 'Data Início', rotulo = 'Data Início', rotulorel = 'Data Início' where codcam = 5934;
            update db_syscampo set descricao = 'Data Final', rotulo = 'Data Final', rotulorel = 'Data Final' where codcam = 5935;
SQL;
        $this->execute($sql);
    }

    public function createEstrutura()
    {
        $sql = <<<SQL

            -- tabela nova 'obrasconstrareacomplementar'
            -- Criando  sequences
            CREATE SEQUENCE projetos.obrasconstrareacomplementar_ob27_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA

            -- Módulo: projetos
            CREATE TABLE projetos.obrasconstrareacomplementar(
                ob27_sequencial	int4 not null default nextval('obrasconstrareacomplementar_ob27_sequencial_seq'),
                ob27_construcao int4 not null,
                ob27_descricao varchar(100) not null,
                ob27_medidaareacoberta float8 not null,
                ob27_medidaareadescoberta float8 not null,
                ob27_ocupacao int4 not null,
                ob27_tipoconstrucao int4 not null,
                ob27_tipolancamento int4 not null,
                ob27_tipo int4 not null,
            CONSTRAINT obrasconstrareacomplementar_sequ_pk PRIMARY KEY (ob27_sequencial));
            
            -- CHAVE ESTRANGEIRA
            
            ALTER TABLE obrasconstrareacomplementar
            ADD CONSTRAINT obrasconstrareacomplementar_construcao_fk FOREIGN KEY (ob27_construcao)
            REFERENCES obrasconstr;
            
            -- INDICES

            CREATE  INDEX obrasconstrareacomplementar_sequencial_in ON obrasconstrareacomplementar(ob27_sequencial);
            
            -- campos novos da tabela 'obras'      
            alter table projetos.obras add column ob01_responsavelprojeto int4;
            alter table projetos.obras add column ob01_arquitetoobra int4;
            
            alter table projetos.obras
            add constraint obras_responsavelprojeto_fk foreign key (ob01_responsavelprojeto)
            references obrastec(ob15_sequencial);

            alter table projetos.obras
            add constraint obras_arquitetoobra_fk foreign key (ob01_arquitetoobra)
            references obrastec(ob15_sequencial);           

            alter table obrasender add column ob07_areacoberta float8 default 0;
            alter table obrasender add column ob07_areadescoberta float8 default 0;

            alter table obrashabite add column ob09_ativo boolean default true;
            alter table obrashabite add column ob09_datacancelamentoreativacao date;
            alter table obrashabite add column ob09_datafinalobra date;
SQL;
        $this->execute($sql);

    }

    public function dropDicionarioDados()
    {
        $sql = <<<SQL

            -- drop tabela nova 'obrasconstrareacomplementar'
            delete from db_syscampodep where codcam = 1010562;
            delete from db_syscampodef where codcam = 1010562;
            delete from db_syscampodep where codcam = 1010563;
            delete from db_syscampodef where codcam = 1010563;
            delete from db_sysarqmod where codarq = 1010455;
            delete from db_sysforkey where codarq = 1010455;
            delete from db_sysarqcamp where codarq = 1010455;
            delete from db_sysarquivo where codarq = 1010455;
            delete from db_sysprikey where codarq = 1010455;
            delete from db_sysarqarq where codarq = 1010455;
            delete from db_sysforkey where codarq = 1010455 and referen = 0;
            delete from db_syscampo where codcam in (1010559, 1010560, 1010561, 1010562, 1010563, 1010564, 1010565, 1010566, 1010567);
            delete from db_sysindices where codind = 1008477;
            delete from db_syscadind where codind = 1008477;
            delete from db_syssequencia where codsequencia = 1000842;

             -- drop campos novos da tabela 'obras'
            delete from db_sysforkey where codcam in (1010570, 1010571);
            delete from db_sysarqcamp where codcam in (1010570, 1010571);
            delete from db_syscampo where codcam in (1010570, 1010571);

            delete from db_sysarqcamp where codcam in(1010578, 1010579);
            delete from db_syscampo where codcam in(1010578, 1010579);

            delete from db_sysindices where codind = 1008478;
            delete from db_sysarqcamp where codcam in(1010583, 1010582, 1010581);
            delete from db_syscampo where codcam in(1010583, 1010582, 1010581);
SQL;
        $this->execute($sql);
    }

    public function dropEstrutura()
    {
        $sql = <<<SQL

        -- drop tabela nova 'obrasconstrareacomplementar'
        drop table projetos.obrasconstrareacomplementar;
        drop sequence obrasconstrareacomplementar_ob27_sequencial_seq;
         
         -- drop campos novos da tabela 'obras'
        alter table projetos.obras drop column ob01_responsavelprojeto;        
        alter table projetos.obras drop column ob01_arquitetoobra;
        alter table projetos.obrasender drop column ob07_areacoberta;
        alter table projetos.obrasender drop column ob07_areadescoberta;

        alter table obrashabite drop column ob09_ativo;
        alter table obrashabite drop column ob09_datacancelamentoreativacao;
        alter table obrashabite drop column ob09_datafinalobra;
SQL;
        $this->execute($sql);
    }
}
