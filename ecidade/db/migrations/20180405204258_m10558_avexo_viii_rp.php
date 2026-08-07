<?php

use Classes\PostgresMigration;

class M10558AvexoViiiRp extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            update orcparamseqorcparamseqcoluna set o116_formula = '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlranuliq+#vlranuliqnaoproc+#vlrpag+#vlrpagnproc)' where o116_codparamrel = 179 and o116_orcparamseqcoluna = 184 and o116_codseq in (104, 105); 
            update orcparamseqorcparamseqcoluna set o116_formula = '#vlranuliq+#vlranuliqnaoproc' where o116_codparamrel = 179 and o116_orcparamseqcoluna = 183 and o116_codseq in (104, 105); 
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            update orcparamseqorcparamseqcoluna set o116_formula = '(#e91_vlremp-#e91_vlranu-#vlranu)-(#e91_vlrpag+#vlrpag+#vlrpagnproc)' where o116_codparamrel = 179 and o116_orcparamseqcoluna = 184 and o116_codseq in (104, 105); 
            update orcparamseqorcparamseqcoluna set o116_formula = '#vlranu' where o116_codparamrel = 179 and o116_orcparamseqcoluna = 183 and o116_codseq in (104, 105); 
SQL;
        $this->execute($sql);        
    }
}
