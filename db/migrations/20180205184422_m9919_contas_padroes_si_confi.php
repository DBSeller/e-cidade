<?php

use Classes\PostgresMigration;

/**
 * Class M9919ContasPadroesSiConfi
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M9919ContasPadroesSiConfi extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( nextval('conplano_c60_codcon_seq') ,2018 ,'999999999999999' ,'NATUREZA DE RECEITA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,2 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2018;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2019 ,'999999999999999' ,'NATUREZA DE RECEITA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,2 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2019;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2020 ,'999999999999999' ,'NATUREZA DE RECEITA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,2 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2020;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2021 ,'999999999999999' ,'NATUREZA DE RECEITA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,2 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2021; 
            
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( nextval('conplano_c60_codcon_seq') ,2018 ,'999999999999998' ,'NATUREZA DE DESPESA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,1 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2018;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2019 ,'999999999999998' ,'NATUREZA DE DESPESA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,1 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2019;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2020 ,'999999999999998' ,'NATUREZA DE DESPESA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,1 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2020;
            INSERT INTO conplano( c60_codcon ,c60_anousu ,c60_estrut ,c60_descr ,c60_finali ,c60_codsis ,c60_codcla ,c60_consistemaconta ,c60_identificadorfinanceiro ,c60_naturezasaldo ,c60_funcao ) VALUES ( currval('conplano_c60_codcon_seq') ,2021 ,'999999999999998' ,'NATUREZA DE DESPESA PADRÃO - SICONFI' ,'' ,0 ,1 ,1 ,'N' ,1 ,'' );
            DELETE FROM conplanocontacorrente WHERE c18_codcon = currval('conplano_c60_codcon_seq') AND c18_anousu = 2021; 
SQL;

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sql = <<<SQL
            DELETE FROM conplano WHERE c60_estrut = '999999999999999' AND c60_descr = 'NATUREZA DE RECEITA PADRÃO - SICONFI';
            DELETE FROM conplano WHERE c60_estrut = '999999999999998' AND c60_descr = 'NATUREZA DE DESPESA PADRÃO - SICONFI';
SQL;

        $this->execute($sql);
    }
}
