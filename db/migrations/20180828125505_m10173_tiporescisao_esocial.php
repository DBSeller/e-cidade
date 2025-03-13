<?php

use Classes\PostgresMigration;

class M10173TiporescisaoEsocial extends PostgresMigration
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
        $this->manutencaoTabelas();
        $this->adicionarCampo();
        $this->migrarDados();


    }

    private function adicionarCampo()
    {
        $this->execute("alter table pessoal.rescisao add r59_motivoesocial integer");
    }

    public function manutencaoTabelas()
    {
        $sql = <<<SQL
          insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009921 ,'r59_motivoesocial' ,'int4' ,'Motivo para o eSocial' ,'' ,'Motivo para o eSocial' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Motivo para o eSocial' );
          insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 589 ,1009921 ,30 ,0 );
SQL;
        $this->execute($sql);
      }

      public function down()
      {
          $sql = <<<SQL
          delete from db_sysarqcamp where codcam = 1009921;
          delete from db_syscampo where codcam = 1009921;
SQL;
          $this->execute($sql);
          $this->execute("alter table pessoal.rescisao drop r59_motivoesocial;");
      }

      public function migrarDados()
      {
          $this->execute("update rescisao set r59_motivoesocial = 1 where r59_causa = 10");
          $this->execute("update rescisao set r59_motivoesocial = 2 where r59_causa = 11");
          $this->execute("update rescisao set r59_motivoesocial = 3 where r59_causa = 12 and r59_caub in('01', '02')");
          $this->execute("update rescisao set r59_motivoesocial = 6 where r59_causa = 12 and r59_caub in('06')");
          $this->execute("update rescisao set r59_motivoesocial = 7 where r59_causa = 12 and r59_caub in('03')");
          $this->execute("update rescisao set r59_motivoesocial = 10 where r59_causa in(60,62,64)");
          $this->execute("update rescisao set r59_motivoesocial = 11 where r59_causa in(30,31)");
          $this->execute("update rescisao set r59_motivoesocial = 18 where r59_causa in(75)");
          $this->execute("update rescisao set r59_motivoesocial = 19 where r59_causa in(72)");
          $this->execute("update rescisao set r59_motivoesocial = 20 where r59_causa in(70,71)");
          $this->execute("update rescisao set r59_motivoesocial = 30 where r59_causa in(40)");
      }
}
