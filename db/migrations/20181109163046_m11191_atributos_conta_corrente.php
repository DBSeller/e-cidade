<?php

use Classes\PostgresMigration;

class M11191AtributosContaCorrente extends PostgresMigration
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

        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010074 ,'c121_sql' ,'text' ,'Consulta para descobrir a origem dos dados do atributo atraves dos lancamentos' ,'' ,'Consulta' ,1 ,'true' ,'false' ,'false' ,0 ,'text' ,'Consulta' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010256 ,1010074 ,4 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010075 ,'c121_ajuda' ,'text' ,'texto de ajuda para o preenchimento do atributo' ,'' ,'Ajuda' ,1 ,'true' ,'false' ,'false' ,0 ,'text' ,'Ajuda' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010256 ,1010075 ,5 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010076 ,'c121_nomepropriedade' ,'varchar(40)' ,'Nome da propriedade dentro do conta corrente. Deve ser um nome Unico.' ,'' ,'Nome da Propriedade' ,40 ,'false' ,'false' ,'false' ,0 ,'text' ,'Nome da Propriedade' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010256 ,1010076 ,6 ,0 );");
        $this->execute("insert into db_sysarquivo values (1010337, 'conlancamdepartamento', 'Departamento de origem do lancamento', 'c128', '2018-11-09', 'Departamento de origem do lancamento', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into db_sysarqmod values (32,1010337);");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010080 ,'c128_sequencial' ,'int4' ,'Código Sequencial' ,'' ,'Código Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código Sequencial' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010337 ,1010080 ,1 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010081 ,'c128_conlancam' ,'int4' ,'Código do Lancamento' ,'' ,'Código do Lancamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do Lancamento' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010337 ,1010081 ,2 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010082 ,'c128_departamento' ,'int4' ,'Código do departamento' ,'' ,'Código do departamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do departamento' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010337 ,1010082 ,3 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010088 ,'c121_valorpadrao' ,'varchar(100)' ,'Valor Padrão do Atributo' ,'' ,'Valor Padrão do Atributo' ,100 ,'true' ,'false' ,'false' ,0 ,'text' ,'Valor Padrão do Atributo' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010256 ,1010088 ,7 ,0 );");
        $this->execute("insert into db_syssequencia values(1000781, 'conlancamdepartamento_c128_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update db_sysarqcamp set codsequencia = 1000781 where codarq = 1010337 and codcam = 1010080;");

        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010337,1010080,1,1010080);");
        $this->execute("insert into db_sysforkey values(1010337,1010082,1,154,0);");

        $this->execute("insert into db_sysforkey values(1010337,1010081,1,760,0);");
        $this->execute("insert into db_sysindices values(1008349,'Código do departamento_depto_in',1010337,'0');");
        $this->execute("insert into db_syscadind values(1008349,1010082,1);");
        $this->execute("insert into db_sysindices values(1008350,'Código do departamento_conlancam_in',1010337,'0');");
        $this->execute("insert into db_syscadind values(1008350,1010081,1);");

        $this->execute("alter table conplanoinfocomplementar add c121_sql text null;");
        $this->execute("alter table conplanoinfocomplementar add c121_ajuda text null;");
        $this->execute("alter table conplanoinfocomplementar add c121_nomepropriedade text null;");
        $this->execute("alter table conplanoinfocomplementar add c121_valorpadrao varchar null");

        $this->execute("CREATE SEQUENCE contabilidade.conlancamdepartamento_c128_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE contabilidade.conlancamdepartamento(
c128_sequencial		int  default nextval('contabilidade.conlancamdepartamento_c128_sequencial_seq'),
c128_departamento int not null,
c128_conlancam int not null,
CONSTRAINT conlancamdepartamento_sequ_pk PRIMARY KEY (c128_sequencial));



ALTER TABLE contabilidade.conlancamdepartamento
ADD CONSTRAINT conlancamdepartamento_conlancam_fk FOREIGN KEY (c128_conlancam)
REFERENCES contabilidade.conlancam;

ALTER TABLE contabilidade.conlancamdepartamento
ADD CONSTRAINT conlancamdepartamento_departamento_fk FOREIGN KEY (c128_departamento)
REFERENCES db_depart;


CREATE  INDEX conlancamdepartamento_depto_in ON contabilidade.conlancamdepartamento(c128_departamento);
CREATE  INDEX conlancamdepartamento_conlancam_in ON contabilidade.conlancamdepartamento(c128_conlancam);");
    }

    public function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam in(1010074, 1010075, 1010076 ,1010080, 1010081, 1010082, 1010088)");
        $this->execute("delete from db_sysforkey where codarq = 1010337");
        $this->execute("delete from db_sysprikey where codarq = 1010337;");
        $this->execute("delete from db_syscadind where codind in(1008349,1008350 )");
        $this->execute("delete from db_sysindices where codind in(1008349,1008350 )");
        $this->execute("delete from db_syssequencia where codsequencia = 1000781");
        $this->execute("delete from db_syscampo where codcam in(1010074, 1010075, 1010076, 1010080, 1010081, 1010082, 1010088)");
        $this->execute("delete from db_sysarqmod where codarq = 1010337");
        $this->execute("delete from db_sysarquivo where codarq = 1010337");

        $this->execute("alter table conplanoinfocomplementar drop c121_sql ;");
        $this->execute("alter table conplanoinfocomplementar drop c121_ajuda ;");
        $this->execute("alter table conplanoinfocomplementar drop c121_nomepropriedade;");
        $this->execute("alter table conplanoinfocomplementar drop c121_valorpadrao;");

        $this->execute("Drop table contabilidade.conlancamdepartamento");
        $this->execute("Drop sequence contabilidade.conlancamdepartamento_c128_sequencial_seq");
    }
}
