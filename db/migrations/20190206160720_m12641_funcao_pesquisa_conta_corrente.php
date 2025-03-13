<?php

use Classes\PostgresMigration;

class M12641FuncaoPesquisaContaCorrente extends PostgresMigration
{

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {



        $sql = "create or replace function fc_atributo_conta_corrente(lancamento integer, conta integer, natureza char(1))
              returns json
            as
            $$
            declare
                resultset  record;
            begin
            
              select x.*
                into resultset
              from (
              select c122_descricao as descricao,
                     array_accum(c121_sigla :: text||':'||c123_valor order by c129_ordem) as atributos
              from infocomplementarvalor
                     inner join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial
                     inner join conplanosistemaatributos
                       on c129_conplanosistema = c124_conplanosistema and c129_conplanoinfocomplementar = c123_infocomplementar
                     inner join conplanosistema on c124_conplanosistema = c122_sequencial
                     inner join conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
              where c124_lancamento = lancamento
                and c123_reduzido = conta
                and c124_natureza = natureza
              group by c122_descricao) as x;
              
              return row_to_json(resultset);
            end;
            $$
            language 'plpgsql';";
        
        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("drop function fc_atributo_conta_corrente(lancamento integer, conta integer, natureza char(1))");
    }
}
