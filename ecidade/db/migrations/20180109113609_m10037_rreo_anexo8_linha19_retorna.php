<?php

use Classes\PostgresMigration;

class M10037RreoAnexo8Linha19Retorna extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            UPDATE orcparamseq
               SET o69_codparamrel = 165 ,
                   o69_codseq = 19 ,
                   o69_totalizador = 'f' ,
                   o69_descr = '2.1.3- Parcela referente à CF, art. 159, I, alínea e' ,
                   o69_grupo = 1 ,
                   o69_grupoexclusao = 0 ,
                   o69_nivel = 1 ,
                   o69_libnivel = 'f' ,
                   o69_librec = 'f' ,
                   o69_libsubfunc = 'f' ,
                   o69_origem = 1 ,
                   o69_libfunc = 'f' ,
                   o69_verificaano = 'f' ,
                   o69_labelrel = '2.1.3- Parcela referente à CF, art. 159, I, alínea e' ,
                   o69_manual = 't'
               WHERE o69_codparamrel = 165
                 AND o69_codseq = 19;
        ");
    }

    public function down()
    {
    }
}
