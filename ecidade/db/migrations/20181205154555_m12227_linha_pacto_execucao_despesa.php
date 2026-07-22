<?php

use Classes\PostgresMigration;

class M12227LinhaPactoExecucaoDespesa extends PostgresMigration
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
        /**
         * pcdotaco
         */
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010135 ,'pc13_planoorcamentariolinhapacto' ,'int4' ,'Linha de Pacto' ,'null' ,'Linha de Pacto' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Linha de Pacto' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 159 ,1010135 ,9 ,0 )");
        $this->execute("insert into db_sysforkey values(159,1010135,1,1010346,0);");
        $this->execute("insert into db_sysindices values(1008370,'pcdotac_linhapacto_in',159,'0');");
        $this->execute("insert into db_syscadind values(1008370,1010135,1);");


        $this->execute("alter table pcdotac add pc13_planoorcamentariolinhapacto int");
        $this->execute("alter table pcdotac add constraint pcdotac_planoorcamentariolinhapacto_fk foreign key (pc13_planoorcamentariolinhapacto) references planoorcamentariolinhapacto(o156_sequencial)");
        $this->execute("create index pcdotac_olanoorcamentariolinhapacto_in on pcdotac (pc13_planoorcamentariolinhapacto)");

        /**
         * empauditod
         */
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010136 ,'e56_planoorcamentariolinhapacto' ,'int4' ,'LInha de Pacto' ,'null' ,'LInha de Pacto' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'LInha de Pacto' );");
        $this->execute("delete from db_syscampodef where codcam = 1010136;");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 812 ,1010136 ,5 ,0 );");
        $this->execute("insert into db_sysforkey values(812,1010136,1,1010346,0);");
        $this->execute("insert into db_sysindices values(1008371,'empautidot_linhapacto_in',812,'0');");
        $this->execute("insert into db_syscadind values(1008371,1010136,1);");

        $this->execute("alter table empautidot add e56_planoorcamentariolinhapacto int");
        $this->execute("alter table empautidot add constraint empautidot_planoorcamentariolinhapacto_fk foreign key (e56_planoorcamentariolinhapacto) references planoorcamentariolinhapacto(o156_sequencial)");
        $this->execute("create index empautidot_polanoorcamentariolinhapacto_in on empautidot (e56_planoorcamentariolinhapacto)");

    }


    public function down()
    {
        $this->execute("delete from db_syscadind where codind in(1008370,1008371)");
        $this->execute("delete from db_sysindices where codind in(1008370, 1008371)");
        $this->execute("delete from db_sysforkey where codcam in(1010135, 1010136);");
        $this->execute("delete from db_sysarqcamp where codcam in(1010135, 1010136)");
        $this->execute("delete from db_syscampo where codcam in(1010135, 1010136)");


        $this->execute("alter table pcdotac drop pc13_planoorcamentariolinhapacto");
        $this->execute("alter table empautidot drop e56_planoorcamentariolinhapacto");
    }
}
