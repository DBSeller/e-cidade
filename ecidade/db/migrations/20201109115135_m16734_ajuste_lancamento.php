<?php

use Classes\PostgresMigration;

class M16734AjusteLancamento extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into contabilidade.conlancamcomplementorecurso
            select nextval('conlancamcomplementorecurso_o201_sequencial_seq'), c130_conlancam, o15_complemento
              from contabilidade.conlancamrecurso
              join orcamento.orctiporec on orctiporec.o15_codigo = conlancamrecurso.c130_orctiporec
              left join contabilidade.conlancamcomplementorecurso
                  on conlancamcomplementorecurso.o201_codlan = conlancamrecurso.c130_conlancam
             where o201_sequencial is null;
        ");
    }

    public function down() {}
}
