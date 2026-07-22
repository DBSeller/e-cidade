<?php

use Classes\PostgresMigration;

class M12820ExportacaoBb extends PostgresMigration
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
        $this->createMenus();
        $this->createTableDicionarioDados();
        $this->createTableEmissao();
        $this->insertsTableEmissao();
        $this->createTipoModelo();
    }

    public function down()
    {
        $this->deleteMenus();
        $this->dropTablesDicionarioDados();
        $this->dropTables();
        $this->dropTipoModelo();
    }

    private function createTipoModelo()
    {
        $this->execute("insert into cadtipomod values(27, 'AUTOATENDIMENTO');");
        $this->execute("select setval('cadtipomod_k46_sequencial_seq', max(k46_sequencial)) from cadtipomod;");
    }

    private function dropTipoModelo()
    {
        $this->execute("delete from cadtipomod where k46_sequencial = 27;");
        $this->execute("select setval('cadtipomod_k46_sequencial_seq', max(k46_sequencial)) from cadtipomod;");
    }

    public function createTableDicionarioDados()
    {
        $sql = "        
            insert into db_sysarquivo values (1010428, 'arquivoautoatendimentoregistros', 'Tabela responsável por guardar os débitos do arquivo.', '183', '2019-04-02', 'arquivoautoatendimentoregistros', 0, 't', 't', 'f', 't' );
            insert into db_sysarqmod values (54,1010428);
            insert into db_sysarqarq values(0,1010428);
            insert into db_syscampo values(1010377,'k183_codigo','int8','Campo que armazena o sequencial da chave primária.','0', 'Código',2000000,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010378,'k183_autoatendimento','int8','Armazena a chave estrangeira do campo k182_codigo da tabela arquivoautoatendimento.','0', 'Código',2000000,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010379,'k183_tipodebito','int8','Tipo de Débito','0', 'Tipo de Débito',2000000,'f','f','f',1,'text','Tipo de Débito');
            insert into db_syscampo values(1010380,'k183_referencia','int8','Ano de Refência a ser informada no aquivo.','0', 'Ano',2000000,'t','f','f',1,'text','Ano');
            insert into db_syscampo values(1010381,'k183_situacao','char(2)','Situação do débito informada pelo banco, ao enviar campo fica nulo.','', 'Situação',2,'t','f','f',2,'text','Situação');
            insert into db_syscampo values(1010382,'k183_numnov','int8','Numpre gerado na emissão do Recibo.','0', 'Numpre Recibo',2000000,'t','f','f',1,'text','Numpre Recibo');
            insert into db_sysarqcamp values(1010428,1010377,1,0);
            insert into db_sysarqcamp values(1010428,1010378,2,0);
            insert into db_sysarqcamp values(1010428,1010379,3,0);
            insert into db_sysarqcamp values(1010428,1010381,4,0);
            insert into db_sysarqcamp values(1010428,1010382,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010428,1010377,1,1010377);
            insert into db_sysindices values(1008438,'k183_autoatendimento_in',1010428,'0');
            insert into db_sysindices values(1008439,'k183_tipodebito_in',1010428,'0');
            insert into db_sysarquivo values (1010433, 'arquivoautoatendimentotipo', 'Tabela responsável por guardar os tipos de débitos do arquivo.', 'k184', '2019-04-03', 'arquivoautoatendimentotipo', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010433);
            insert into db_syscampo values(1010388,'k184_codigo','int8','Campo que armazena o sequencial da chave primária.','0', 'Campo código',2000000,'f','f','f',1,'text','Campo código');
            insert into db_syscampo values(1010389,'k184_descricao','varchar(10)','Campo que armazena a descrição do tipos de débito.','', 'Campos descrição',10,'f','t','f',0,'text','Campos descrição');
            insert into db_sysarqcamp values(1010433,1010388,1,0);
            insert into db_sysarqcamp values(1010433,1010389,2,0);
            insert into db_sysarquivo values (1010434, 'arquivoautoatendimentotipocadtipo', 'Tabela responsável por guardar os tipos de débitos.', 'K185', '2019-04-03', 'arquivoautoatendimentotipocadtipo', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010434);
            insert into db_syscampo values(1010390,'k185_codigo','int8','Campo que armazena o sequencial da chave primária.','0', 'Campo código',2000000,'f','f','f',1,'text','Campo código');
            insert into db_syscampo values(1010391,'k185_arquivoautoatendimentotipo','int8','Armazena a chave estrangeira do campo k184_codigo da tabela arquivoautoatendimentotipo.','0', 'Campo tipo cadastro',2000000,'f','f','f',1,'text','Campo tipo cadastro');
            insert into db_syscampo values(1010392,'k185_cadtipo','int8','Armazena a chave estrangeira do campo k03_tipo da tabela cadtipo.','0', 'Campo tipo cadastro',2000000,'f','f','f',1,'text','Campo tipo cadastro');
            insert into db_sysarqcamp values(1010434,1010390,1,0);
            insert into db_sysarqcamp values(1010434,1010391,2,0);
            insert into db_sysarqcamp values(1010434,1010392,3,0);
            insert into db_sysforkey values(1010434,1010391,1,1010434,0);
            insert into db_sysindices values(1008440,'k185_arquivoautoatendimentotipo_in',1010434,'0');
            insert into db_sysindices values(1008441,'k185_cadtipo_in',1010434,'0');
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010434,1010390,1,1010390);               
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010433,1010388,1,1010388);
            insert into db_syssequencia values(1000824, 'arquivoautoatendimentotipocadtipo_k185_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysarquivo values (1010435, 'arquivoautoatendimento', 'Tabela que armazena os dados da geração do arquivo.', 'k182', '2019-04-03', 'arquivoautoatendimento', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010435);
            insert into db_sysarqarq values(0,1010435);
            insert into db_syscampo values(1010393,'k182_codigo','int8','Campo que armazena o sequencial da chave primária.','0', 'Campo código',2000000,'f','f','f',1,'text','Campo código');
            insert into db_syscampo values(1010394,'k182_ano','int8','Campo que armazena o ano da geração do arquivo.','0', 'Campo ano',2000000,'f','f','f',1,'text','Campo ano');
            insert into db_syscampo values(1010395,'k182_numero','int8','Campo que armazena o numero da geração de arquivo.','0', 'Campo numero',2000000,'f','f','f',1,'text','Campo numero');
            insert into db_syscampo values(1010396,'k182_tipo','char(1)','Campo que armazena o tipo da geração de arquivo.','', 'Campo tipo',1,'f','t','f',0,'text','Campo tipo');
            insert into db_syscampo values(1010397,'k182_dataemissao','date','Campo que armazena a data da geração do arquivo. ','null', 'Campo data',10,'f','f','f',1,'text','Campo data');
            insert into db_syscampo values(1010398,'k182_datavigenciainicial','date','Campo que armazena a vigência inicial do arquivo.','null', 'Campo data',10,'f','f','f',1,'text','Campo data');
            insert into db_syscampo values(1010399,'k182_datavigenciafinal','date','Campo que armazena a data da vigência final do arquivo.','null', 'Campo data',10,'f','f','f',1,'text','Campo data');
            insert into db_sysarqcamp values(1010435,1010393,1,0);
            insert into db_sysarqcamp values(1010435,1010394,2,0);
            insert into db_sysarqcamp values(1010435,1010395,3,0);
            insert into db_sysarqcamp values(1010435,1010396,4,0);
            insert into db_sysarqcamp values(1010435,1010397,5,0);
            insert into db_sysarqcamp values(1010435,1010398,6,0);
            insert into db_sysarqcamp values(1010435,1010399,7,0);
            insert into db_syssequencia values(1000825, 'arquivoautoatendimento_k182_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysarquivo values (1010436, 'arquivoautoatendimentoregistrosmatricula', 'Matricula do débito enviado no arquivo de autoatendimento. ', 'k186', '2019-04-03', 'arquivoautoatendimentoregistrosmatricula', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010436);
            insert into db_syscampo values(1010400,'k186_autoatendimentoregistros','int8','Campo que armazena os registros. ','0', 'Campo registros',2000000,'f','f','f',1,'text','Campo registros');
            insert into db_syscampo values(1010401,'k186_matricula','int8','Campo que armazena a matricula ','0', 'Campo matricula',2000000,'f','f','f',1,'text','Campo matricula');
            insert into db_sysarqcamp values(1010436,1010400,1,0);
            insert into db_sysarqcamp values(1010436,1010401,2,0);
            insert into db_sysforkey values(1010436,1010400,1,1010428,0);
            insert into db_sysarquivo values (1010437, 'arquivoautoatendimentoregistrosinscricao', 'Inscrição Municipal do débito enviado no arquivo de autoatendimento.', 'k187', '2019-04-03', 'arquivoautoatendimentoregistrosinscricao', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010437);
            insert into db_syscampo values(1010402,'k187_autoatendimentoregistros','int8','Campo que armazena os registros.','0', 'Campo registros',2000000,'f','f','f',1,'text','Campo registros');
            insert into db_syscampo values(1010403,'k187_inscricao','int8','Campo que armazena as inscriç&#7869;os','0', 'Campo instrição',2000000,'f','f','f',1,'text','Campo instrição');
            insert into db_sysarqcamp values(1010437,1010402,1,0);
            insert into db_sysarqcamp values(1010437,1010403,2,0);
            insert into db_sysforkey values(1010437,1010402,1,1010428,0);
            insert into db_sysindices values(1008444,'arquivoautoatendimentoregistrosinscricao_autoatendimentoregistros_in',1010437,'0');
            insert into db_syscadind values(1008444,1010402,1);
            insert into db_sysarquivo values (1010438, 'arquivoautoatendimentoregistroscgm', 'Cgm do débito enviado no arquivo de autoatendimento', 'k188', '2019-04-03', 'arquivoautoatendimentoregistroscgm', 0, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (54,1010438);
            insert into db_syscampo values(1010404,'k188_autoatendimentoregistros','int8','Campo que armazena os registros.','0', 'Campo registros',2000000,'f','f','f',1,'text','Campo registros');
            insert into db_syscampo values(1010405,'k188_cgm','int8','Campo que armazena o cgm.','0', 'Campo cgm',2000000,'f','f','f',1,'text','Campo cgm');
            insert into db_sysarqcamp values(1010438,1010404,1,0);
            insert into db_sysarqcamp values(1010438,1010405,2,0);
            insert into db_sysindices values(1008445,'arquivoautoatendimentoregistroscgm_autoatendimentoregistros_in',1010438,'0');
            insert into db_syscadind values(1008445,1010404,1);
            insert into db_syssequencia values(1000826, 'arquivoautoatendimentotipo_k184_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010435,1010393,1,1010393);
            insert into db_sysforkey values(1010428,1010378,1,1010435,0);
            insert into db_sysforkey values(1010428,1010379,1,1010433,0);
            insert into db_syscadind values(1008438,1010378,1);
            insert into db_syscadind values(1008439,1010379,1);
            insert into db_syssequencia values(1000827, 'arquivoautoatendimentoregistros_k183_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            delete from db_sysforkey where codarq = 1010434;                        
            insert into db_sysforkey values(1010434,1010391,1,1010433,0);
            insert into db_sysforkey values(1010434,1010392,1,410,0);
            insert into db_sysforkey values(1010438,1010404,1,1010428,0);

            update db_sysarquivo set nomearq = 'arquivoautoatendimentoregistros', descricao = 'Tabela responsável por guardar os débitos do arquivo.', sigla = '183', dataincl = '2019-04-02', rotulo = 'arquivoautoatendimentoregistros', tipotabela = 0, naolibclass = 'f', naolibfunc = 't', naolibprog = 't', naolibform = 't' where codarq = 1010428;
            update db_syscampo set nomecam = 'k183_tipodebito', conteudo = 'int8', descricao = 'Armazena a chave estrangeira do campo k184_codigo da tabela arquivoautoatendimentotipo. ', valorinicial = '0', rotulo = 'Tipo de Débito', nulo = 'f', tamanho = 2000000, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Débito' where codcam = 1010379;
            update db_sysindices set nomeind = 'arquivoautoatendimentotipo_in',campounico = '0' where codind = 1008440;
            update db_sysindices set nomeind = 'cadtipo_in',campounico = '0' where codind = 1008441;
            update db_sysarqcamp set codsequencia = 1000824 where codarq = 1010434 and codcam = 1010390;
            update db_syssequencia set nomesequencia = 'arquivoautoatendimentotipocadtipo_k185_codigo_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1000824;
            update db_sysarqcamp set codsequencia = 1000824 where codarq = 1010434 and codcam = 1010390;
            update db_sysarquivo set nomearq = 'arquivoautoatendimento', descricao = 'Tabela que armazena os lotes de envios dos arquivos gerados.', sigla = 'k182', dataincl = '2019-04-03', rotulo = 'arquivoautoatendimento', tipotabela = 0, naolibclass = 'f', naolibfunc = 't', naolibprog = 't', naolibform = 't' where codarq = 1010435;
            update db_sysarqcamp set codsequencia = 1000825 where codarq = 1010435 and codcam = 1010393;
            update db_sysarqcamp set codsequencia = 1000826 where codarq = 1010433 and codcam = 1010388;
            update db_syscampo set nomecam = 'k184_descricao', conteudo = 'varchar(50)', descricao = 'Campo que armazena a descrição do tipos de débito.', valorinicial = '', rotulo = 'Campos descrição', nulo = 'f', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Campos descrição' where codcam = 1010389;
            update db_sysindices set nomeind = 'arquivoautoatendimentoregistros_autoatendimento_in',campounico = '0' where codind = 1008438;
            update db_sysindices set nomeind = 'arquivoautoatendimentoregistros_tipodebito_in',campounico = '0' where codind = 1008439;
            update db_sysarqcamp set codsequencia = 1000827 where codarq = 1010428 and codcam = 1010377;            
            update db_sysindices set nomeind = 'arquivoautoatendimentotipocadtipo_arquivoautoatendimentotipo_in',campounico = '0' where codind = 1008440;
            update db_sysindices set nomeind = 'arquivoautoatendimentotipocadtipo_cadtipo_in',campounico = '0' where codind = 1008441;
            update db_syscampo set nomecam = 'k182_ano', conteudo = 'int8', descricao = 'Campo que armazena o ano da geração do arquivo.', valorinicial = '0', rotulo = 'Campo ano', nulo = 't', tamanho = 2000000, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Campo ano' where codcam = 1010394;
            update db_sysarquivo set nomearq = 'arquivoautoatendimentoregistros', descricao = 'Tabela responsável por guardar os débitos do arquivo.', sigla = 'k183', dataincl = '2019-04-08', rotulo = 'arquivoautoatendimentoregistros', tipotabela = 0, naolibclass = 'f', naolibfunc = 't', naolibprog = 't', naolibform = 't' where codarq = 1010428;
        ";
        $this->execute($sql);
    }

    public function createTableEmissao()
    {
        $sql = "CREATE SEQUENCE arrecadacao.arquivoautoatendimentotipocadtipo_k185_codigo_seq
                                INCREMENT 1
                                MINVALUE 1
                                MAXVALUE 9223372036854775807
                                START 1
                                CACHE 1;";
        $sql .= "CREATE SEQUENCE arrecadacao.arquivoautoatendimento_k182_codigo_seq
                                INCREMENT 1
                                MINVALUE 1
                                MAXVALUE 9223372036854775807
                                START 1
                                CACHE 1;";
        $sql .= "CREATE SEQUENCE arrecadacao.arquivoautoatendimentoregistros_k183_codigo_seq
                                INCREMENT 1
                                MINVALUE 1
                                MAXVALUE 9223372036854775807
                                START 1
                                CACHE 1;";                                

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentotipo (
                                      k184_codigo int8 PRIMARY KEY,
                                      k184_descricao varchar(50) NOT NULL
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentotipocadtipo (
                                      k185_codigo int8 PRIMARY KEY DEFAULT nextval('arrecadacao.arquivoautoatendimentotipocadtipo_k185_codigo_seq'),
                                      k185_arquivoautoatendimentotipo int8 REFERENCES arquivoautoatendimentotipo(k184_codigo) NOT NULL,
                                      k185_cadtipo int8 REFERENCES cadtipo (k03_tipo) NOT NULL
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimento (
                                      k182_codigo int8 PRIMARY KEY DEFAULT nextval('arrecadacao.arquivoautoatendimento_k182_codigo_seq'),
                                      k182_ano int8,
                                      k182_numero int8 NOT NULL,
                                      k182_tipo char(1) NOT NULL,
                                      k182_dataemissao date NOT NULL,
                                      k182_datavigenciainicial date NOT NULL,
                                      k182_datavigenciafinal date NOT NULL
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentoregistros (
                                      k183_codigo int8 PRIMARY KEY DEFAULT nextval('arrecadacao.arquivoautoatendimentoregistros_k183_codigo_seq'),
                                      k183_autoatendimento int8 REFERENCES arquivoautoatendimento(k182_codigo) NOT NULL,
                                      k183_tipodebito int8 REFERENCES arquivoautoatendimentotipo(k184_codigo) NOT NULL,
                                      k183_situacao CHAR(2),
                                      k183_numnov int8
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentoregistrosmatricula (
                                      k186_autoatendimentoregistros int8 REFERENCES arquivoautoatendimentoregistros(k183_codigo) NOT NULL,
                                      k186_matricula int8 NOT NULL
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentoregistrosinscricao (
                                      k187_autoatendimentoregistros int8 REFERENCES arquivoautoatendimentoregistros(k183_codigo) NOT NULL,
                                      k187_inscricao int8 NOT NULL
                              );";

        $sql .= "CREATE TABLE arrecadacao.arquivoautoatendimentoregistroscgm (
                                      k188_autoatendimentoregistros int8 REFERENCES arquivoautoatendimentoregistros(k183_codigo) NOT NULL,
                                      k188_cgm int8 NOT NULL
                              );";

        $this->execute($sql);
    }

    public function insertsTableEmissao()
    {
        $sqls = array();

        $sqls[] = "INSERT INTO arquivoautoatendimentotipo VALUES 
                       (1,  'IPVA')
                      ,(2,  'Seguro DPVAT')
                      ,(3,  'Licenciamento')
                      ,(4,  'Multa Municipal')
                      ,(5,  'Multa Estadual')
                      ,(6,  'Multa Federal')
                      ,(7,  'Multa Detran')
                      ,(8,  'ICMS')
                      ,(9,  'IPTU')
                      ,(10, 'ISS')
                      ,(11, 'Energia Elétrica')
                      ,(12, 'Gás')
                      ,(13, 'Telefone/Internet/ TV')
                      ,(14, 'Taxa Veículo')
                      ,(15, 'Taxa Imóvel')
                      ,(16, 'Dívida Ativa')
                      ,(17, 'Água/ Esgoto')
                      ,(18, 'Internet')
                      ,(19, 'Telefone')
                      ,(20, 'TV Paga')
                      ,(21, 'TFF')
                      ,(22, 'Taxa Licença Empresas')
        ";

        $sqls[] = "INSERT INTO arquivoautoatendimentotipocadtipo (k185_arquivoautoatendimentotipo, k185_cadtipo) VALUES
                       (9,  1)
                      ,(10, 2)
                      ,(10, 3)
                      ,(15, 8)
                      ,(16, 15)
                      ,(16, 5)
                      ,(16, 12)
                      ,(16, 18)
                      ,(16, 13)
                      ,(16, 6)
                      ,(22, 9)
                      ,(22, 11)
                      ,(22, 19)
        ";

        foreach ($sqls as $sql) {
            $this->execute($sql);
        }
    }

    public function createMenus()
    {
        $sql = "
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228099 ,'Arquivo Autoatendimento' ,'Arquivo Autoatendimento' ,'' ,'1' ,'1' ,'Arquivo Autoatendimento' ,'false' );
        delete from db_menu where id_item_filho = 228099 AND modulo = 1985522;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228099 ,510 ,1985522 );

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228100 ,'Emissão' ,'Emissão' ,'arr4_emissaoautoatendimento001.php' ,'1' ,'1' ,'Emissão' ,'true' );
        delete from db_menu where id_item_filho = 228100 AND modulo = 1985522;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228099 ,228100 ,1 ,1985522 );

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228101 ,'Importação' ,'Importação' ,'arr4_autoatendimentoretorno001.php' ,'1' ,'1' ,'Importação' ,'true' );
        delete from db_menu where id_item_filho = 228101 AND modulo = 1985522;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228099 ,228101 ,2 ,1985522 );

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228102 ,'Monitor' ,'Monitor' ,'arr4_monitorbb001.php' ,'1' ,'1' ,'Monitor' ,'true' );
        delete from db_menu where id_item_filho = 228102 AND modulo = 1985522;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228099 ,228102 ,3 ,1985522 );
        ";
        $this->execute($sql);
    }

    public function dropTables()
    {
        $sqls = array(
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentoregistrosmatricula;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentoregistrosinscricao;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentoregistroscgm;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentoregistros;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimento;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentotipocadtipo;",
            "DROP TABLE IF EXISTS arrecadacao.arquivoautoatendimentotipo;",

            "DROP SEQUENCE IF EXISTS arrecadacao.arquivoautoatendimentoregistros_k183_codigo_seq;",
            "DROP SEQUENCE IF EXISTS arrecadacao.arquivoautoatendimento_k182_codigo_seq;",
            "DROP SEQUENCE IF EXISTS arrecadacao.arquivoautoatendimentotipocadtipo_k185_codigo_seq;",
        );
     
        foreach($sqls as $sql) {
            $this->execute($sql);
        }
    }

    public function dropTablesDicionarioDados()
    {
   
        $sql = "
            delete from db_sysarqarq where codarq = 1010428;
            delete from db_sysarqcamp where codarq = 1010428;
            delete from db_sysprikey where codarq = 1010428;
            delete from db_syscampodef where codcam = 1010380;
            delete from db_syscampodep where codcam = 1010380;
            delete from db_syscampo where codcam = 1010380;
            delete from db_syscampodef where codcam = 1010386;
            delete from db_syscampodep where codcam = 1010386;
            delete from db_syscampo where codcam = 1010386;
            delete from db_sysarqcamp where codarq = 1010433;
            delete from db_syscampodep where codcam = 1010379;
            delete from db_syscampodef where codcam = 1010379;
            delete from db_sysarqarq where codarq = 1010431;
            delete from db_sysarqmod where codarq = 1010431;
            delete from db_sysarquivo where codarq = 1010431;
            delete from db_sysarqcamp where codarq = 1010434;
            delete from db_sysprikey where codarq = 1010434;
            delete from db_sysforkey where codarq = 1010434 and referen = 0;            
            delete from db_sysforkey where codarq = 1010434 and referen = 1010434;
            delete from db_sysprikey where codarq = 1010433;
            delete from db_sysarqarq where codarq = 1010435;
            delete from db_sysprikey where codarq = 1010435;
            delete from db_sysarqcamp where codarq = 1010435;
            delete from db_sysprikey where codarq = 1010436;
            delete from db_sysforkey where codarq = 1010436 and referen = 0;
            delete from db_sysarqcamp where codarq = 1010437;
            delete from db_sysforkey where codarq = 1010437 and referen = 0;
            delete from db_sysarqcamp where codarq = 1010438;
            delete from db_sysforkey where codarq = 1010438 and referen = 0;
            delete from db_syscampodep where codcam = 1010389;
            delete from db_syscampodef where codcam = 1010389;
            delete from db_sysforkey where codarq = 1010428 and referen = 0;
            delete from db_syscadind where codind = 1008438;
            delete from db_syscadind where codind = 1008439;
            delete from db_syscadind where codind = 1008442;
            delete from db_syscadind where codind = 1008440;
            delete from db_syscadind where codind = 1008441;
            delete from db_sysindices where codind = 1008438;
            delete from db_sysindices where codind = 1008439;
            delete from db_syscampodep where codcam = 1010394;
            delete from db_syscampodef where codcam = 1010394;
            delete from db_sysforkey where codarq = 1010428;
            delete from db_sysarqmod where codarq = 1010428;
            delete from db_sysarquivo where codarq = 1010428;
            delete from db_syscampo where codcam = 1010377;
            delete from db_syscampo where codcam = 1010378;
            delete from db_syscampo where codcam = 1010379;
            delete from db_syscampo where codcam = 1010381;
            delete from db_syscampo where codcam = 1010382;
            delete from db_sysarqmod where codarq = 1010433;
            delete from db_sysarquivo where codarq = 1010433;
            delete from db_syscampo where codcam = 1010388;
            delete from db_syscampo where codcam = 1010389;
            delete from db_sysarqmod where codarq = 1010434;
            delete from db_sysforkey where codarq = 1010434;
            delete from db_sysarquivo where codarq = 1010434;
            delete from db_syscampo where codcam = 1010390;
            delete from db_syscampo where codcam = 1010391;
            delete from db_syscampo where codcam = 1010392;
            delete from db_sysindices where codind = 1008440;
            delete from db_sysindices where codind = 1008441;
            delete from db_syssequencia where codsequencia = 1000824;
            delete from db_sysarqmod where codarq = 1010435;
            delete from db_sysarquivo where codarq = 1010435;
            delete from db_syscampo where codcam = 1010393;
            delete from db_syscampo where codcam = 1010394;
            delete from db_syscampo where codcam = 1010395;
            delete from db_syscampo where codcam = 1010396;
            delete from db_syscampo where codcam = 1010397;
            delete from db_syscampo where codcam = 1010398;
            delete from db_syscampo where codcam = 1010399;
            delete from db_syssequencia where codsequencia = 1000825;
            delete from db_sysarqcamp where codarq = 1010436;
            delete from db_sysarqmod where codarq = 1010436;
            delete from db_sysforkey where codarq = 1010436;
            delete from db_sysarquivo where codarq = 1010436;
            delete from db_syscampo where codcam = 1010400;
            delete from db_syscampo where codcam = 1010401;
            delete from db_sysarqmod where codarq = 1010437;
            delete from db_sysforkey where codarq = 1010437;
            delete from db_sysarquivo where codarq = 1010437;
            delete from db_syscampo where codcam =1010402;
            delete from db_syscampo where codcam =1010403;
            delete from db_sysindices where codind =1008444;
            delete from db_syscadind where codind = 1008444;
            delete from db_sysarqcamp where codarq =1010438;            
            delete from db_sysforkey where codarq =1010438;
            delete from db_sysarqmod where codarq =1010438;
            delete from db_sysarquivo where codarq =1010438;
            delete from db_syscampo where codcam =1010404;
            delete from db_syscampo where codcam =1010405;
            delete from db_sysindices where codind =1008445;
            delete from db_syscadind where codind = 1008445;
            delete from db_syssequencia where codsequencia =1000826;
            delete from db_syssequencia where codsequencia =1000827;
        ";
        $this->execute($sql);

    }

    public function deleteMenus()
    {
        $sql = "
            DELETE FROM db_menu where id_item_filho = 228099 and modulo = 1985522;
            DELETE FROM db_menu where id_item_filho = 228100 and modulo = 1985522;
            DELETE FROM db_menu where id_item_filho = 228101 and modulo = 1985522;
            DELETE FROM db_menu where id_item_filho = 228102 and modulo = 1985522;
            DELETE FROM db_itensmenu where id_item  = 228099;
            DELETE FROM db_itensmenu where id_item  = 228100;
            DELETE FROM db_itensmenu where id_item  = 228101;
            DELETE FROM db_itensmenu where id_item  = 228102;
        ";
        $this->execute($sql);
    }
}
