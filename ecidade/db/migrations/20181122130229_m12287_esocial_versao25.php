<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25 extends PostgresMigration
{
    public function up()
    {
        $this->execute("INSERT INTO esocialversao (rh210_versao) VALUES (2.5)");
        $this->execute("
            INSERT INTO esocialversaoformulario 
                (rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) 
                SELECT '2.5', rh211_avaliacao, rh211_esocialformulariotipo FROM esocialversaoformulario
                WHERE rh211_versao = '2.4'
        ");
    }

    public function down()
    {
        $this->execute("DELETE FROM esocialversaoformulario WHERE rh211_versao = '2.5' ");
        $this->execute("DELETE FROM esocialversao WHERE rh210_versao = '2.5' ");
    }
}
