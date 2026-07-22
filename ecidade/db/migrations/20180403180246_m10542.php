<?php

use Classes\PostgresMigration;

class M10542 extends PostgresMigration
{
    public function up()
    {

        $this->execute("UPDATE   db_itensmenu
           SET descricao = 'Consulta situação da importação do recadastramento' ,
               desctec = 'Consulta situação da importação do recadastramento' , 
                help = 'Consulta situação da importação do recadastramento' 
                WHERE id_item = 10508 
               ");

    }

    public function down()
    {

       $this->execute("
            UPDATE   db_itensmenu
           SET descricao = 'Consulta situação da Importação do Civitas' ,
               desctec = 'Consulta situação da Importação do Civitas' , 
                help = 'Consulta situação da Importação do Civitas' 
                WHERE id_item = 10508 
       
       ");

    }
}
