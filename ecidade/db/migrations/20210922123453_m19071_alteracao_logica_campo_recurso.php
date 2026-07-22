<?php

use Classes\PostgresMigration;

class M19071AlteracaoLogicaCampoRecurso extends PostgresMigration
{


    public function up()
    {

        $sql = <<<SQL



update conplanoinfocomplementar set c121_sql = '
select
   case when fc_getsession(\'DB_anousu\')::int <= 2018 then
    (SELECT distinct (
                         CASE
                              WHEN c74_codlan IS NOT NULL AND c53_tipo in(100, 101) THEN orcreceita.o70_codigo
                              WHEN c75_codlan IS NOT NULL AND c71_coddoc not in (6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) AND c53_tipo in(30, 31) THEN c61_codigo
                              WHEN c71_coddoc in ( 6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) THEN dotemp.o58_codigo
                              WHEN c75_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN dotemp.o58_codigo
                              WHEN c73_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN dotlan.o58_codigo
                              WHEN c74_codrec IS NOT NULL and dotrec.o58_codigo is not null THEN dotrec.o58_codigo
                              WHEN c74_codrec IS NOT NULL THEN o70_codigo
                              WHEN recursopagdebito.c61_reduz IS NOT NULL THEN c61_codigo
                              ELSE (SELECT c61_codigo
                                    FROM conplanoreduz
                                    WHERE c61_reduz = conta_reduzida
                                    AND c61_anousu = anousu)
                         END
                     ) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho empemp1 ON c75_numemp = empemp1.e60_numemp
                     LEFT JOIN orcdotacao dotemp ON empemp1.e60_coddot = dotemp.o58_coddot
                                                AND empemp1.e60_anousu = dotemp.o58_anousu
                     LEFT JOIN conlancamdot ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot
                                                 AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN conlancamrec ON c74_codlan = c70_codlan
                     LEFT JOIN orcreceita ON c74_codrec = o70_codrec
                                         AND c74_anousu = o70_anousu
                     LEFT JOIN conlancampag ON c82_codlan = c70_codlan
                     LEFT JOIN conplanoreduz AS recursopagdebito ON c82_reduz = recursopagdebito.c61_reduz
                                                                AND c82_anousu = recursopagdebito.c61_anousu
                     LEFT JOIN conlancamcorrente conlancorr1 ON conlancorr1.c86_conlancam =  c70_codlan
                     LEFT JOIN corgrupocorrente corgrpcor1 ON corgrpcor1.k105_data = conlancorr1.c86_data
                                                          AND corgrpcor1.k105_autent = conlancorr1.c86_autent
                                                          AND corgrpcor1.k105_id = conlancorr1.c86_id
                                                          AND corgrpcor1.k105_corgrupotipo = 3
                     LEFT JOIN corgrupocorrente corgrpcor2 ON corgrpcor2.k105_corgrupo = corgrpcor1.k105_corgrupo
                                                          AND corgrpcor2.k105_corgrupotipo = 1
                     LEFT JOIN coremp ON k12_id = corgrpcor2.k105_id
                                     AND k12_data = corgrpcor2.k105_data
                                     AND k12_autent = corgrpcor2.k105_autent
                     LEFT JOIN empempenho empemp2 ON  k12_empen = empemp2.e60_numemp
                     LEFT JOIN orcdotacao dotrec ON empemp2.e60_coddot = dotrec.o58_coddot
                                                AND empemp2.e60_anousu = dotrec.o58_anousu
                     WHERE c70_codlan = codigo_lancamento

               )


               WHEN fc_getsession(\'DB_anousu\')::int > 2018
                THEN
                    (

                        SELECT o15_codigo
                          FROM conlancamrecurso
                          JOIN orctiporec ON orctiporec.o15_codigo = conlancamrecurso.c130_orctiporec
                          WHERE c130_conlancam = codigo_lancamento
                            AND c130_conta = conta_reduzida
                            AND c130_natureza = natureza

                      LIMIT 1

                    )
             END AS infocomplementar_valor
'
where c121_sequencial = 3;



SQL;

        $this->execute($sql);

    }



    public function down()
    {

        $sql = <<<SQL


update conplanoinfocomplementar set c121_sql = '
select
   case when fc_getsession(\'DB_anousu\')::int <= 2018 then
    (SELECT distinct (
                         CASE
                              WHEN c74_codlan IS NOT NULL AND c53_tipo in(100, 101) THEN orcreceita.o70_codigo
                              WHEN c75_codlan IS NOT NULL AND c71_coddoc not in (6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) AND c53_tipo in(30, 31) THEN c61_codigo
                              WHEN c71_coddoc in ( 6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) THEN dotemp.o58_codigo
                              WHEN c75_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN dotemp.o58_codigo
                              WHEN c73_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN dotlan.o58_codigo
                              WHEN c74_codrec IS NOT NULL and dotrec.o58_codigo is not null THEN dotrec.o58_codigo
                              WHEN c74_codrec IS NOT NULL THEN o70_codigo
                              WHEN recursopagdebito.c61_reduz IS NOT NULL THEN c61_codigo
                              ELSE (SELECT c61_codigo
                                    FROM conplanoreduz
                                    WHERE c61_reduz = conta_reduzida
                                    AND c61_anousu = anousu)
                         END
                     ) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho empemp1 ON c75_numemp = empemp1.e60_numemp
                     LEFT JOIN orcdotacao dotemp ON empemp1.e60_coddot = dotemp.o58_coddot
                                                AND empemp1.e60_anousu = dotemp.o58_anousu
                     LEFT JOIN conlancamdot ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot
                                                 AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN conlancamrec ON c74_codlan = c70_codlan
                     LEFT JOIN orcreceita ON c74_codrec = o70_codrec
                                         AND c74_anousu = o70_anousu
                     LEFT JOIN conlancampag ON c82_codlan = c70_codlan
                     LEFT JOIN conplanoreduz AS recursopagdebito ON c82_reduz = recursopagdebito.c61_reduz
                                                                AND c82_anousu = recursopagdebito.c61_anousu
                     LEFT JOIN conlancamcorrente conlancorr1 ON conlancorr1.c86_conlancam =  c70_codlan
                     LEFT JOIN corgrupocorrente corgrpcor1 ON corgrpcor1.k105_data = conlancorr1.c86_data
                                                          AND corgrpcor1.k105_autent = conlancorr1.c86_autent
                                                          AND corgrpcor1.k105_id = conlancorr1.c86_id
                                                          AND corgrpcor1.k105_corgrupotipo = 3
                     LEFT JOIN corgrupocorrente corgrpcor2 ON corgrpcor2.k105_corgrupo = corgrpcor1.k105_corgrupo
                                                          AND corgrpcor2.k105_corgrupotipo = 1
                     LEFT JOIN coremp ON k12_id = corgrpcor2.k105_id
                                     AND k12_data = corgrpcor2.k105_data
                                     AND k12_autent = corgrpcor2.k105_autent
                     LEFT JOIN empempenho empemp2 ON  k12_empen = empemp2.e60_numemp
                     LEFT JOIN orcdotacao dotrec ON empemp2.e60_coddot = dotrec.o58_coddot
                                                AND empemp2.e60_anousu = dotrec.o58_anousu

               WHERE c70_codlan = codigo_lancamento

               )




               WHEN fc_getsession(\'DB_anousu\')::int > 2018
                THEN
                    (
                      SELECT o15_codigo
                          FROM conlancamrecurso
                          JOIN orctiporec ON orctiporec.o15_codigo = conlancamrecurso.c130_orctiporec
                          WHERE c130_conlancam = codigo_lancamento
                            AND c130_conta = conta_reduzida
                            AND c130_natureza = natureza

                       union all

                      select o58_codigo
                        from conlancamemp
                        inner join empempenho on e60_numemp = c75_numemp
                        inner join orcdotacao on o58_coddot = e60_coddot
                                            and e60_anousu = o58_anousu
                        inner join conlancamdoc on c75_codlan = c71_codlan

                        inner join conhistdoc on c71_coddoc = c53_coddoc
                        inner join conlancamrecurso on c71_codlan = c130_conlancam
                        where c75_numemp = (
                                select c75_numemp
                                  from conlancamemp
                            inner join conlancamdoc on c75_codlan = c71_codlan
                            inner join conhistdoc on c71_coddoc = c53_coddoc
                                 where c75_codlan = codigo_lancamento

                        )
                      LIMIT 1)
             END AS infocomplementar_valor


'
where c121_sequencial = 3;



SQL;

        $this->execute($sql);

    }



}
