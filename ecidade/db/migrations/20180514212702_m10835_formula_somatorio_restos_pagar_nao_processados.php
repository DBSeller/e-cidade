<?php

use Classes\PostgresMigration;

class M10835FormulaSomatorioRestosPagarNaoProcessados extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
create table w_10835 as
      SELECT orcparamseqorcparamseqcoluna.*
        FROM orcparamseqorcparamseqcoluna
       WHERE o116_codparamrel = 170
         AND o116_codseq in(53,54,55,57,58,59)
         AND o116_orcparamseqcoluna = 184;

update orcparamseqorcparamseqcoluna
   set o116_formula = '(#e60_anousu < #e91_anousu -1 ? (#e91_vlremp - #e91_vlranu - #e91_vlrliq) : 0) + (#e60_anousu == #e91_anousu - 1 ? (#e91_vlremp - #e91_vlranu - #e91_vlrliq) : 0) - #vlrpagnproc - #vlranuliqnaoproc'
  from w_10835
 where w_10835.o116_sequencial = orcparamseqorcparamseqcoluna.o116_sequencial;
SQL_UP
        );
    }

    public function down()
    {
        $this->execute(
          <<<SQL_DOWN
update orcparamseqorcparamseqcoluna
   set o116_formula = '(#e60_anousu < #e91_anousu -1  ? (#e91_vlremp - #e91_vlranu - #e91_vlrliq) : 0) + (#e60_anousu ==  #e91_anousu - 1 ? (#e91_vlremp - #e91_vlranu - #e91_vlrliq) : 0) - #vlrliq - #vlranuliqnaoproc'
  from w_10835
 where w_10835.o116_sequencial = orcparamseqorcparamseqcoluna.o116_sequencial;
 
drop table w_10835;
SQL_DOWN

        );
    }
}
