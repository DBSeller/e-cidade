<?php

use Classes\PostgresMigration;

class M12259AtributosSiconfi extends PostgresMigration
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
        $this->execute("select setval('conplanoinfocomplementar_c121_sequencial_seq', 10000)");
        $this->execute("insert into conplanoinfocomplementar 
                            values 
                            (50,
                            'ES',
                            'Despesas com MDE e ASPS',
                            'select case when c75_codlan is not null then finalidade_empenho.c119_tipo
           when c73_codlan is not null then finalidade_dotacao.c119_tipo end as tipo
from conlancam
       left join conlancamemp on c75_codlan = c70_codlan
       left join empempenho on c75_numemp  = e60_numemp
       left join siconfidotacaofinalidade AS finalidade_empenho on finalidade_empenho.c119_coddot = e60_coddot
                                         and finalidade_empenho.c119_anousu = e60_anousu
       left join conlancamdot on c75_codlan = c73_codlan
       left join siconfidotacaofinalidade  finalidade_dotacao on finalidade_empenho.c119_coddot = c73_coddot
                                               and finalidade_empenho.c119_anousu = c73_anousu
where c70_codlan = codigo_lancamento limit 1
',
                            'Despesas com MDE e ASPS  - 1 para MDE e 2 para ASPS',
                            'atributo_es',
                            ''
                            )");

        $this->execute("insert into conplanoinfocomplementar 
                            values ( 
                            51,
                            'AI',
                            'Ano de inscrição de restos a pagar',
                            'select min(e91_anousu) as ano from conlancamemp inner join empresto on c75_numemp = e91_numemp where c75_codlan = codigo_lancamento limit 1',
                            'Primeira inscrição do Empenho em resto a pagar',
                            'atributo_ai',
                            ''
                            )");

    }

    public function down()
    {
        $this->execute("delete from conplanoinfocomplementar where c121_nomepropriedade in('atributo_ai', 'atributo_es')");
    }
}
