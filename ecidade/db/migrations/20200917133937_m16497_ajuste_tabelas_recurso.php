<?php

use Classes\PostgresMigration;

class M16497AjusteTabelasRecurso extends PostgresMigration
{
    public function up()
    {
        $this->execute(
            "create table orcamento.bkp_recursoespecificacao as select * from orcamento.recursoespecificacao"
        );

        $this->execute("update orcamento.recursoespecificacao set o205_estado = '' where o205_estado is null");

        $stmt = $this->query("select munic from db_config limit 1");
        $dado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dado['munic'] == 'NITEROI') {
            $this->execute("delete from orcamento.recursoespecificacao where o205_estado is null");
        } else {
            // deleta os registros exclusivos de niterói
            $this->execute("
                delete from orcamento.recursodetalhamento where o203_estado = 'RJ';
                delete from orcamento.recursoidentificador where o202_estado = 'RJ';
                delete from orcamento.recursogrupo where o204_estado = 'RJ';
                delete from orcamento.recursoespecificacao where o205_estado = 'RJ';
            ");

            $this->execute("update orcamento.recursoespecificacao set o205_codigo = lpad(o205_codigo, 4, 0);");
            $this->execute("update orcamento.orctiporec set o15_loaespecificacao = lpad(o15_loaespecificacao, 4, 0);");
        }
    }

    public function down()
    {
        $this->execute("
            insert into orcamento.recursoespecificacao
            select * from orcamento.bkp_recursoespecificacao
            where bkp_recursoespecificacao.o205_sequencial not in (
                select o205_sequencial from orcamento.recursoespecificacao
                )
        ");

        $this->execute("drop table if exists orcamento.bkp_recursoespecificacao;");
    }
}
