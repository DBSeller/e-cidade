<?php

use Classes\PostgresMigration;

class M15395ArquivosComplementaresPad extends PostgresMigration
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
        $this->createEstrutura();
        $this->upDDL();
    }

    public function down()
    {
        $this->dropEstrutura();
        $this->downDDL();
    }

    public function createEstrutura()
    {
        $sql = <<<SQL
            update db_layoutcampos set db52_codigo = 171773 where db52_layoutlinha = 132 and db52_nome = 'complemento_recurso';
        
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos )
            values ( 171772, 1015 ,'identificadorfolhapagamento' ,'IDENTIFICADOR FOLHA PAGAMENTO' ,1 ,164 ,'' ,12 ,'f' ,'t' ,'d' ,'' ,0 );
            
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos )
            values ( 171783 ,1015 ,'pagamentoaposvigencia' ,'PAGAMENTO APOS VIGENCIA' ,1 ,176 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 );

            insert into db_sysarquivo values (1010525, 'identificadorfolhapagamento', 'Guarda os identificadores e matrículas enviados no arquivo complementar de folha de pagamento do PAD.', 'rh237', '2020-02-21', 'identificadorfolhapagamento', 0, 'f', 'f', 'f', 'f' );
            
            insert into db_sysarqmod values (28,1010525);
            
            insert into db_syscampo values(1011040,'rh237_sequencial','int4','Sequencial da tabela padpagamentoposterior.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011041,'rh237_identificador','varchar(12)','Número identificador da linha enviada no arquivo de folha de pagamento do PAD.','', 'Identificador',12,'f','t','f',0,'text','Identificador');
            insert into db_syscampo values(1011042,'rh237_matricula','int4','Matrícula referente ao identificador.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1011043,'rh237_instituicao','int4','Instituição de vínculo do identificador/matrícula.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1011044,'rh237_ano','int4','Ano referente ao envio do identificador.','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1011045,'rh237_mes','int4','Mês referente ao envio do identificador.','0', 'Mês',10,'f','f','f',1,'text','Mês');
            insert into db_syscampo values(1011046,'rh237_tipofolha','int4','Tipo de folha a qual se refere o identificador. Valores válidos com base no manual do arquivo TCE_4810: 1 - Folha Normal(Salário) 2 - 13º Salário 3 - Férias 4 - Rescisão 5 - Complementar/Suplementar 6 - Afastamento 9 - Outros','0', 'Tipo de Folha',10,'f','f','f',1,'text','Tipo de Folha');
            
            insert into db_sysarqcamp values(1010525,1011040,1,0);
            insert into db_sysarqcamp values(1010525,1011041,2,0);
            insert into db_sysarqcamp values(1010525,1011042,3,0);
            insert into db_sysarqcamp values(1010525,1011043,4,0);
            insert into db_sysarqcamp values(1010525,1011044,5,0);
            insert into db_sysarqcamp values(1010525,1011045,6,0);
            insert into db_sysarqcamp values(1010525,1011046,7,0);
            
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010525,1011040,1,1011041);
            
            insert into db_sysforkey values(1010525,1011042,1,1153,0);
            insert into db_sysforkey values(1010525,1011043,1,83,0);
            
            insert into db_sysindices values(1008523,'padpagamentoposterior_matricula_in',1010525,'0');
            insert into db_syscadind values(1008523,1011042,1);
            insert into db_sysindices values(1008524,'padpagamentoposterior_instituicao_in',1010525,'0');
            
            insert into db_syssequencia values(1000879, 'padpagamentoposterior_rh237_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);        
            update db_sysarqcamp set codsequencia = 1000879 where codarq = 1010525 and codcam = 1011040;
            
            update db_sysarquivo set nomearq = 'padpagamentoposterior', descricao = 'Guarda os dados a serem utilizados no arquivo de Pagamento Posterior do PAD.', sigla = 'rh237', dataincl = '2020-02-26', rotulo = 'padpagamentoposterior', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010525;
            insert into db_syscampo values(1011070,'rh237_valor','float8','Valor da Vantagem/Desconto/Totalizador layout 4810.','0', 'Valor',20,'f','f','f',4,'text','Valor');
            insert into db_syscampo values(1011071,'rh237_identificacaovalor','char(1)','Identificação da Operação layout 4810. Preencher com: V = Vantagem; D = Desconto; T = Totalizador O = Outros (Especificar no campo Observações)','', 'Identificação do valor',1,'f','t','f',0,'text','Identificação do valor');
            insert into db_syscampo values(1011072,'rh237_banco','varchar(5)','Código do Banco do Depósito da Folha de Pagamento da Entidade.','', 'Banco',5,'f','t','f',0,'text','Banco');
            insert into db_syscampo values(1011073,'rh237_agencia','varchar(5)','Código da Agência do Banco do Depósito da Folha de Pagamento da Entidade.','', 'Agência',5,'f','t','f',0,'text','Agência');
            insert into db_syscampo values(1011074,'rh237_contacorrente','varchar(20)','Código da Conta-Corrente do Banco do Depósito da Folha de Pagamento da Entidade.','', 'Conta Corrente',20,'f','t','f',0,'text','Conta Corrente');
            insert into db_syscampo values(1011075,'rh237_datapagamento','date','Data de Pagamento da folha.','null', 'Data de Pagamento',10,'f','f','f',1,'text','Data de Pagamento');
            delete from db_sysarqcamp where codarq = 1010525;
            insert into db_sysarqcamp values(1010525,1011040,1,1000879);
            insert into db_sysarqcamp values(1010525,1011041,2,0);
            insert into db_sysarqcamp values(1010525,1011042,3,0);
            insert into db_sysarqcamp values(1010525,1011043,4,0);
            insert into db_sysarqcamp values(1010525,1011044,5,0);
            insert into db_sysarqcamp values(1010525,1011045,6,0);
            insert into db_sysarqcamp values(1010525,1011046,7,0);
            insert into db_sysarqcamp values(1010525,1011070,8,0);
            insert into db_sysarqcamp values(1010525,1011071,9,0);
            insert into db_sysarqcamp values(1010525,1011072,10,0);
            insert into db_sysarqcamp values(1010525,1011073,11,0);
            insert into db_sysarqcamp values(1010525,1011074,12,0);
            insert into db_sysarqcamp values(1010525,1011075,13,0);


SQL;
        $this->execute($sql);
    }

    public function dropEstrutura()
    {

        $sql = <<<SQL
            
            delete from db_layoutcampos where db52_codigo in (171772, 171783);

            delete from db_syssequencia where codsequencia = 1000879;
            delete from db_syscadind where codind in(1008523, 1008524, 1008527);
            delete from db_sysindices where codind in(1008523, 1008524, 1008527);
            delete from db_sysforkey where codarq = 1010525;
            delete from db_sysprikey where codarq = 1010525;
            delete from db_sysarqcamp where codarq = 1010525;
            delete from db_syscampo where codcam in(1011040, 1011041, 1011042, 1011043, 1011044, 1011045, 1011046, 1011070, 1011071, 1011072, 1011073, 1011074, 1011075);
            delete from db_sysarqmod where codarq = 1010525;
            delete from db_sysarquivo where codarq = 1010525;
SQL;
        $this->execute($sql);
    }

    private function upDDL()
    {
        $sql = <<<SQL
CREATE SEQUENCE pessoal.padpagamentoposterior_rh237_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE pessoal.padpagamentoposterior(
rh237_sequencial int4 default nextval('pessoal.padpagamentoposterior_rh237_sequencial_seq'::regclass),
rh237_identificador varchar(12) not null,
rh237_matricula int4 not null,
rh237_instituicao int4 not null,
rh237_ano int4 not null,
rh237_mes int4 not null,
rh237_tipofolha int4 not null,
rh237_valor float not null,
rh237_identificacaovalor char not null,
rh237_banco varchar(5) not null,
rh237_agencia varchar(5) not null,
rh237_contacorrente varchar(20) not null,
rh237_datapagamento date not null,
CONSTRAINT padpagamentoposterior_sequ_pk PRIMARY KEY (rh237_sequencial));

ALTER TABLE pessoal.padpagamentoposterior
ADD CONSTRAINT padpagamentoposterior_instituicao_fk FOREIGN KEY (rh237_instituicao)
REFERENCES db_config;

ALTER TABLE pessoal.padpagamentoposterior
ADD CONSTRAINT padpagamentoposterior_matricula_fk FOREIGN KEY (rh237_matricula)
REFERENCES rhpessoal;

CREATE  INDEX padpagamentoposterior_matricula_in ON padpagamentoposterior(rh237_matricula);
CREATE  INDEX padpagamentoposterior_instituicao_in ON padpagamentoposterior(rh237_instituicao);
SQL;

        $this->execute($sql);
    }

    private function downDDL()
    {
        $sql = <<<SQL
DROP TABLE IF EXISTS padpagamentoposterior CASCADE;
DROP SEQUENCE IF EXISTS padpagamentoposterior_rh237_sequencial_seq;
SQL;

        $this->execute($sql);
    }
}
