<?php

use Classes\PostgresMigration;

class M10979RelatoriosLegaisCodigoGrupo extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update orcparamrel 
			   set o42_orcparamrelgrupo = 2 
			 where o42_orcparamrelgrupo = 4 
			   and o42_codparrel in (145, 146, 147, 148, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 169, 170, 171, 172, 173, 174, 175, 176, 177, 179);
        ");
    }

    public function down()
    {
        $this->execute("
            update orcparamrel 
			   set o42_orcparamrelgrupo = 4 
			 where o42_orcparamrelgrupo = 2 
			   and o42_codparrel in (145, 146, 147, 148, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 169, 170, 171, 172, 173, 174, 175, 176, 177, 179);
        ");
    }
}
