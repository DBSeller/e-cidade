<?php

use Classes\PostgresMigration;

class M13576SlipCustaClassificacao extends PostgresMigration
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

        $this->execute("insert into configuracoes.db_sysarquivo values (1010451, 'slipcustasclassificacao', 'slips de transferência das custas oriundas de uma classificação da baixa de banca', 'k190', '2019-05-28', 'slipcustasclassificacao', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into configuracoes.db_sysarqmod values (5,1010451);");
        $this->execute("insert into configuracoes.db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010534 ,'k190_sequencial' ,'int4' ,'Código Sequencial' ,'' ,'Código Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código Sequencial' );");
        $this->execute("insert into configuracoes.db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010451 ,1010534 ,1 ,0 );");
        $this->execute("insert into configuracoes.db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010535 ,'k190_codcla' ,'int4' ,'Código da Classificação' ,'' ,'Código da Classificação' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código da Classificação' );");
        $this->execute("insert into configuracoes.db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010451 ,1010535 ,2 ,0 );");
        $this->execute("insert into configuracoes.db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010536 ,'k190_slip' ,'int4' ,'Código do Slip' ,'' ,'Código do Slip' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do Slip' );");
        $this->execute("insert into configuracoes.db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010451 ,1010536 ,3 ,0 );");
        $this->execute("insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010451,1010534,1,1010535);");
        $this->execute("insert into configuracoes.db_sysforkey values(1010451,1010536,1,196,0);");
        $this->execute("insert into configuracoes.db_sysforkey values(1010451,1010535,1,215,0);");
        $this->execute("insert into configuracoes.db_sysindices values(1008471,'slipcustasclassificacao_codcla_in',1010451,'0');");
        $this->execute("insert into configuracoes.db_syscadind values(1008471,1010535,1);");
        $this->execute("insert into configuracoes.db_sysindices values(1008472,'slipcustasclassificacao_slip_in',1010451,'0');");
        $this->execute("insert into configuracoes.db_syscadind values(1008472,1010536,1);");
        $this->execute("insert into configuracoes.db_syssequencia values(1000840, 'slipcustasclassificacao_k190_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update configuracoes.db_sysarqcamp set codsequencia = 1000840 where codarq = 1010451 and codcam = 1010534;");


        $this->execute(<<<SQL
        CREATE SEQUENCE caixa.slipcustasclassificacao_k190_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE caixa.slipcustasclassificacao(
k190_sequencial         int4  default nextval('caixa.slipcustasclassificacao_k190_sequencial_seq'),
k190_codcla integer not null,
k190_slip integer not null,
CONSTRAINT slipcustasclassificacao_sequ_pk PRIMARY KEY (k190_sequencial));




-- CHAVE ESTRANGEIRA


ALTER TABLE caixa.slipcustasclassificacao
ADD CONSTRAINT slipcustasclassificacao_slip_fk FOREIGN KEY (k190_slip)
REFERENCES slip;

ALTER TABLE caixa.slipcustasclassificacao
ADD CONSTRAINT slipcustasclassificacao_codcla_fk FOREIGN KEY (k190_codcla)
REFERENCES caixa.discla;




-- INDICES


CREATE  INDEX slipcustasclassificacao_codcla_in ON caixa.slipcustasclassificacao(k190_codcla);

CREATE  INDEX slipcustasclassificacao_slip_in ON caixa.slipcustasclassificacao(k190_slip);

        
SQL
        );

    }
    

    public function down()
    {

        $this->execute("delete from configuracoes.db_syssequencia where  codsequencia= 1000840");
        $this->execute("delete from configuracoes.db_sysprikey where codarq = 1010451;");
        $this->execute("delete from configuracoes.db_sysforkey where codarq = 1010451");
        $this->execute("delete from configuracoes.db_syscadind where codcam in(1010535, 1010536)");
        $this->execute("delete from configuracoes.db_sysindices where codind in(1010535, 1010536)");

        $this->execute("delete from configuracoes.db_sysarqcamp where codcam in(1010535, 1010536, 1010534)");
        $this->execute("delete from configuracoes.db_syscampo where codcam in(1010535, 1010536, 1010534)");
        $this->execute("delete from configuracoes.db_sysarqmod where codarq in(1010451)");
        $this->execute("delete from configuracoes.db_sysarquivo where codarq in(1010451)");


        $this->execute(<<<SQL
DROP TABLE IF EXISTS caixa.slipcustasclassificacao CASCADE;
DROP SEQUENCE IF EXISTS caixa.slipcustasclassificacao_k190_sequencial_seq;
SQL
        );

    }
    
    
}
