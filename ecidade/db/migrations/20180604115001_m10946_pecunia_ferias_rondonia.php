<?php

use Classes\PostgresMigration;

class M10946PecuniaFeriasRondonia extends PostgresMigration
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

        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
      $this->downDDL();
      $this->downDicionario();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009749 ,'rh168_tipoassentamentopecunia' ,'int4' ,'Assentamento Para Abono em Pecúnia' ,'' ,'Assentamento Para Abono em Pecúnia' ,1 ,'true' ,'false' ,'false' ,1 ,'text' ,'Assentamento Para Pecunia' );
        delete from db_syscampodef where codcam = 1009749;
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3872 ,1009749 ,5 ,0 );
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009752 ,'r11_rubricaabonopecuniario' ,'varchar(10)' ,'Rubrica para Abono em Pecúnia' ,'' ,'Rubrica para Abono em Pecúnia' ,10 ,'true' ,'true' ,'false' ,0 ,'text' ,'Rubrica para Abono em Pecúnia' );
        delete from db_syscampodef where codcam = 1009752;
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 536 ,1009752 ,99 ,0 );
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009759 ,'rh110_diaspecunia' ,'int4' ,'Dias em Pecúnia' ,'0' ,'Dias em Pecúnia' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Dias em Pecúnia' );
        delete from db_syscampodef where codcam = 1009759;
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3374 ,1009759 ,17 ,0 );
SQL
);
    }


    private function upDDL()
    {
        $this->execute(<<<SQL
         alter table rhferiasperiodo add rh110_diaspecunia integer default 0;
         alter table rhferiasconfiguracao add rh168_tipoassentamentopecunia integer default 0;
         alter table cfpess add r11_rubricaabonopecuniario varchar;

SQL
        );

    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
       
        delete from db_syscampodef where codcam in(1009749, 1009752, 1009759);
        delete from db_sysarqcamp where codcam in(1009749, 1009752, 1009759);
        delete from db_syscampo where codcam in(1009749, 1009752, 1009759);    
SQL
        );

    }

    private function downDDL()
    {
        $this->execute(<<<SQL
         alter table rhferiasperiodo drop rh110_diaspecunia;
         alter table rhferiasconfiguracao drop rh168_tipoassentamentopecunia;
         alter table cfpess drop r11_rubricaabonopecuniario;

SQL
        );
    }

}
