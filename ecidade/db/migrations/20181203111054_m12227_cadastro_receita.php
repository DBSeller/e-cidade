<?php

use Classes\PostgresMigration;

class M12227CadastroReceita extends PostgresMigration
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

        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010119 ,'o70_orcorgao' ,'int4' ,'Orgao' ,'null' ,'Orgao' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Orgao' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 780 ,1010119 ,10 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010120 ,'o70_orcunidade' ,'int4' ,'Unidade' ,'null' ,'Unidade' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Unidade' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 780 ,1010120 ,11 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010121 ,'o70_esferaorcamentaria' ,'int4' ,'Esfera Orçamentária' ,'' ,'Esfera Orçamentária' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Esfera Orçamentária' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 780 ,1010121 ,12 ,0 );");

        $this->execute("insert into db_sysforkey values(780,5361,1,757,0);");
        $this->execute("insert into db_sysforkey values(780,1010119,2,757,0);");
        $this->execute("insert into db_sysforkey values(780,1010120,3,757,0);");

        $this->execute("insert into db_sysindices values(1008364,'orcreceita_orgao_unidade_in',780,'0');");
        $this->execute("insert into db_syscadind values(1008364,1010119,1);");
        $this->execute("insert into db_syscadind values(1008364,1010120,2);");

        $this->execute("alter table orcreceita add o70_orcorgao int null");
        $this->execute("alter table orcreceita add o70_orcunidade int null");
        $this->execute("alter table orcreceita add o70_esferaorcamentaria int null");

        $this->execute("create index orcreceita_orgao_unidade_in on orcreceita (o70_orcorgao, o70_orcunidade)");
        $this->execute("alter table orcreceita add constraint orcreceita_orgao_unidade_fk foreign key (o70_anousu, o70_orcorgao, o70_orcunidade)
references orcunidade (o41_anousu, o41_orgao, o41_unidade)");
    }

    public function down()
    {
        $this->execute("delete from db_syscampodef where codcam in(1010119,1010120, 1010121) ");
        $this->execute("delete from db_sysarqcamp where codcam in(1010119,1010120, 1010121) ");
        $this->execute("delete from db_sysforkey where codarq = 780 and referen = 757");
        $this->execute("delete from db_syscadind where  codind = 1008364");
        $this->execute("delete from db_sysindices where  codind = 1008364");
        $this->execute("delete from db_syscampo where codcam in(1010119,1010120, 1010121); ");

        $this->execute("alter table orcreceita drop constraint orcreceita_orgao_unidade_fk ");
        $this->execute("drop index  orcreceita_orgao_unidade_in ");
        $this->execute("alter table orcreceita drop o70_esferaorcamentaria; ");
        $this->execute("alter table orcreceita drop o70_orcunidade; ");
        $this->execute("alter table orcreceita drop o70_orcorgao; ");


    }
}
