<?php

use Classes\PostgresMigration;

class M13814CorrecaoAnexo1Rgf extends PostgresMigration
{
    public function up()
    {
        $sql = "update orcparamseqcoluna set o115_descricao = 'Mês/Ano', o115_tipo = 2 where o115_relatorio = 197 and o115_nomecoluna = 'despliq'";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "update orcparamseqcoluna set o115_descricao = 'Despesas Liquidadas', o115_tipo = 1 where o115_relatorio = 197 and o115_nomecoluna = 'despliq'";
        $this->execute($sql);
    }
}
