<?php

use Classes\PostgresMigration;

class M11291 extends PostgresMigration
{

    private  $documento = 300;
    private  $paragrafo = 800;


    public function up()
    {
        $aSql = array();

        $title = ("Aviso de Férias");

        $aSql [] = "INSERT INTO db_tipodoc(db08_codigo, db08_descr) VALUES(92000, 'AVISO DE FÉRIAS');";
        $aSql [] = "INSERT INTO  db_documento(db03_docum,db03_descr, db03_tipodoc, db03_instit) VALUES ({$this->documento}, '$title', 92000, 1);";

        $paragrafo = ("Tendo v.Sa. direito a ferias relatiro ao periodo aquisitivo  #periodoAquisitivo#,com o presente levamos ao seu 
conhecimento que resolvemos concedê-las de #periodoGozo#,inclusive pelo que deve V.Sa no dia #dataPagamento# 
comparecer a Seção Pessoal munido de Carteira de Trabalho,a fim de receber o valor das mesmas,devendo 
retornar ao trabalho em #dataVoltarTrabalho#.
        ");

        $aSql [] = "INSERT INTO
                     db_paragrafo
                     (db02_idparag, db02_descr, db02_texto, db02_alinha , db02_inicia ,
                      db02_espaca,  db02_altura, db02_largura, db02_alinhamento, db02_tipo, db02_instit)
                     VALUES ({$this->paragrafo}, '$title', '{$paragrafo}' ,0,10,0,100, 100,'C', 1,1);";

        $aSql[] = " INSERT INTO db_docparag(db04_docum, db04_idparag, db04_ordem) VALUES ({$this->documento}, {$this->paragrafo}, 1);";

        $aSql [] = "insert
                       into db_itensmenu (id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                            values (10554 ,'$title' ,'$title' ,'pes2_feriasaviso001.php', '1', '1','$title', 'true');";
        $aSql [] = "insert into db_menu (id_item ,id_item_filho ,menusequencia ,modulo ) values (5703 , 10554, 7, 952);";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }
    }


    public function down()
    {
        $aSql = array();

        $aSql [] = "DELETE FROM db_docparag   WHERE   db04_docum    ={$this->documento}  AND db04_idparag = {$this->paragrafo};";
        $aSql [] = "DELETE FROM db_tipodoc    WHERE   db08_codigo    = 92000;";

        $aSql [] = "DELETE FROM db_paragrafo  WHERE   db02_idparag  ={$this->paragrafo};";
        $aSql [] = "DELETE FROM db_documento  WHERE   db03_docum    ={$this->documento};";

        $aSql [] = "DELETE FROM db_menu  WHERE  id_item = 5703  AND id_item_filho = 10554;";
        $aSql [] = "DELETE FROM db_itensmenu  WHERE id_item= 10554";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }

    }
}
