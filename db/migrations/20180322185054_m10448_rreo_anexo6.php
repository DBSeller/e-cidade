<?php

use Classes\PostgresMigration;

class M10448RreoAnexo6 extends PostgresMigration
{
   public function up() {
         $this->execute( <<<SQL
           update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_anterior' where o116_codseq = 62 and o116_codparamrel = 177 and o116_orcparamseqcoluna= 56;
           update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_final' where o116_codseq = 62 and o116_codparamrel = 177 and o116_orcparamseqcoluna= 57;
SQL
        );
   }

   public function down() {
        $this->execute( <<<SQL
            update orcparamseqorcparamseqcoluna set o116_formula = '' where o116_codseq = 62 and o116_codparamrel = 177 and o116_orcparamseqcoluna= 56;
           update orcparamseqorcparamseqcoluna set o116_formula = '' where o116_codseq = 62 and o116_codparamrel = 177 and o116_orcparamseqcoluna= 57;
SQL
        );
   }
}
