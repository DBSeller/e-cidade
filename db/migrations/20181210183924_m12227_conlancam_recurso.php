<?php

use Classes\PostgresMigration;

class M12227ConlancamRecurso extends PostgresMigration
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
        $this->execute("insert into db_sysarquivo values (1010355, 'conlancamrecurso', 'Recursos do lançamento contabil', 'c130', '2018-12-12', 'Recursos do lançamento contabil', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into db_sysarqmod values (32,1010355);");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010186 ,'c130_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010186 ,1 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010187 ,'c130_conlancam' ,'int4' ,'Código do lançamento' ,'' ,'Código do lançamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do lançamento' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010187 ,2 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010188 ,'c130_orctiporec' ,'int4' ,'Fonte de Recurso' ,'' ,'Fonte de Recurso' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Fonte de Recurso' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010188 ,3 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010189 ,'c130_conta' ,'int4' ,'Fonte de Recurso' ,'' ,'Conta' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Conta' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010189 ,4 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010190 ,'c130_anousu' ,'int4' ,'Ano da Conta' ,'' ,'Ano da Conta' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ano da Conta' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010190 ,5 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010191 ,'c130_natureza' ,'char(1)' ,'Natureza da Conta' ,'' ,'Natureza da Conta' ,1 ,'false' ,'true' ,'false' ,0 ,'text' ,'Natureza da Conta' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010355 ,1010191 ,6 ,0 );");


        $this->execute("insert into db_syssequencia values(1000797, 'conlancamrecurso_c130_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update db_sysarqcamp set codsequencia = 1000797 where codarq = 1010355 and codcam = 1010186;");
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010355,1010186,1,1010186);");
        $this->execute("insert into db_sysforkey values(1010355,1010187,1,760,0);");
        $this->execute("insert into db_sysforkey values(1010355,1010188,1,749,0);");
        $this->execute("insert into db_sysforkey values(1010355,1010189,1,773,0);");
        $this->execute("insert into db_sysforkey values(1010355,1010190,2,773,0);");

        $this->execute("insert into db_sysindices values(1008392,'conlancamrecurso_lancan_in',1010355,'0');");
        $this->execute("insert into db_syscadind values(1008392,1010187,1);");
        $this->execute("insert into db_sysindices values(1008393,'conlancamrecurso_recurso_in',1010355,'0');");
        $this->execute("insert into db_syscadind values(1008393,1010188,1);");
        $this->execute("insert into db_sysindices values(1008394,'conlancamrecurso_conta_in',1010355,'0');");
        $this->execute("insert into db_syscadind values(1008394,1010189,1);");
        $this->execute("insert into db_syscadind values(1008394,1010190,2);");

        $this->execute("CREATE SEQUENCE contabilidade.conlancamrecurso_c130_sequencial_seq");
        $this->execute("CREATE TABLE contabilidade.conlancamrecurso(
                              c130_sequencial   int4  default nextval('contabilidade.conlancamrecurso_c130_sequencial_seq'),
                              c130_conlancam    int4  not null,
                              c130_orctiporec   int4  not null,
                              c130_conta        int4  not null,
                              c130_anousu       int4  not null,
                              c130_natureza     char(1)  not null,
                  CONSTRAINT conlancamrecurso_sequ_pk PRIMARY KEY (c130_sequencial));"
        );

        $this->execute("ALTER TABLE contabilidade.conlancamrecurso
                                ADD CONSTRAINT conlancamrecurso_orctiporec_fk FOREIGN KEY (c130_orctiporec)
                              REFERENCES orctiporec;");

        $this->execute("ALTER TABLE contabilidade.conlancamrecurso
ADD CONSTRAINT conlancamrecurso_conlancam_fk FOREIGN KEY (c130_conlancam)
REFERENCES contabilidade.conlancam;");

        $this->execute("ALTER TABLE contabilidade.conlancamrecurso ADD CONSTRAINT conlancamrecurso_conta_ae_fk FOREIGN KEY (c130_conta,c130_anousu) REFERENCES contabilidade.conplanoreduz;");


        $this->execute("CREATE  INDEX conlancamrecurso_lancan_in ON contabilidade.conlancamrecurso(c130_conlancam);");
        $this->execute("CREATE  INDEX conlancamrecurso_recurso_in ON contabilidade.conlancamrecurso(c130_orctiporec);");
        $this->execute("CREATE  INDEX conlancamrecurso_conta_in ON contabilidade.conlancamrecurso(c130_conta,c130_anousu);");
    }

    public function down()
    {

        $this->execute("delete from db_syscadind where codind in(1008392, 1008393, 1008394)");
        $this->execute("delete from db_sysindices where codind in(1008392, 1008393, 1008394)");
        $this->execute("delete from db_sysforkey  where codarq in(1010355)");
        $this->execute("delete from db_sysprikey  where codarq in(1010355)");

        $this->execute("delete from db_syssequencia where codsequencia = 1000797");
        $this->execute("delete from db_sysarqcamp   where codarq = 1010355");
        $this->execute("delete from db_syscampo     where codcam in(1010186, 1010187, 1010188, 1010189, 1010190, 1010191)");
        $this->execute("delete from db_sysarqmod     where codarq in(1010355)");
        $this->execute("delete from db_sysarquivo  where codarq in(1010355)");

        $this->execute("drop table if exists contabilidade.conlancamrecurso");
        $this->execute("drop sequence if exists contabilidade.conlancamrecurso_c130_sequencial_seq");

    }
}
