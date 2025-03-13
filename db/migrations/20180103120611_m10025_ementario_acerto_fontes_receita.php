<?php

use Classes\PostgresMigration;

class M10025EmentarioAcertoFontesReceita extends PostgresMigration
{

    public function up()
    {

        $this->execute("
          insert into orcfontes 
          select c60_codcon, c60_anousu, c60_estrut, c60_descr, c60_finali 
            from conplanoorcamento
           where c60_anousu >= 2018 
             and substr(c60_estrut, 1, 1)::int in (4,9) 
             and not exists (select 1 from orcfontes where o57_codfon = c60_codcon and o57_anousu = c60_anousu);
        
          update orcfontes
             set o57_fonte  = c60_estrut,
                 o57_descr  = c60_descr,
                 o57_finali = c60_finali
            from conplanoorcamento
           where c60_codcon = o57_codfon
             and c60_anousu = o57_anousu
             and substr(c60_estrut, 1, 1)::int in (4,9)
             and o57_anousu >= 2018;

        ");


    }

    public function down() {}

}
