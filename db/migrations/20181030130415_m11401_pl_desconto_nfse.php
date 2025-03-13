<?php

use Classes\PostgresMigration;

class M11401PlDescontoNfse extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
          CREATE OR REPLACE FUNCTION fc_desconto_iptu_nfse(
            cgm INT, 
            dataOperacao DATE, 
            receita INT, 
            dataVencimento DATE, 
            numpre INT, 
            numpar INT, 
            numtot INT, 
            numdig INT, 
            dataPaga DATE, 
            numnov INT
          ) RETURNS VOID AS $$
          DECLARE

            nDescontoNFSE numeric;
            nDescontoNFSEParcela numeric;
            nDescontoNFSEParcelaResto numeric;

          BEGIN
            
            IF NOT EXISTS(
              SELECT TRUE 
                FROM db_plugin
               WHERE db_plugin.db145_nome = 'iptu-descontos-niteroi'
                 AND db_plugin.db145_situacao IS TRUE
            ) THEN
              RETURN;
            END IF;

            SELECT valor 
              INTO nDescontoNFSE
              FROM plugins.iptudescontonfse
             WHERE matricula = (SELECT iptunump.j20_matric
                                  FROM iptunump
                                 WHERE iptunump.j20_numpre = numpre
                                 LIMIT 1)
               AND exercicio = (SELECT j20_anousu FROM iptunump WHERE iptunump.j20_numpre = numpre LIMIT 1);

            PERFORM 1
               FROM cfiptu
              WHERE cfiptu.j18_anousu = (SELECT j20_anousu FROM iptunump WHERE iptunump.j20_numpre = numpre LIMIT 1)
                AND (cfiptu.j18_rterri = receita OR cfiptu.j18_rpredi = receita);

            IF (found AND nDescontoNFSE > 0) THEN

              PERFORM 1
              FROM arreckey
              INNER JOIN abatimentoarreckey ON abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial
              INNER JOIN abatimento ON abatimento.k125_sequencial = abatimentoarreckey.k128_abatimento
              WHERE k125_tipoabatimento = 1
                AND arreckey.k00_numpre = numpre
                AND arreckey.k00_numpar = numpar
                AND arreckey.k00_receit = receita;

              IF (found) THEN 
                RETURN;
              END IF;

              nDescontoNFSEParcela := (round(nDescontoNFSE / numtot, 2) *- 1);

              if (numtot = numpar) then 
                nDescontoNFSEParcelaResto := ((nDescontoNFSE / numtot)::numeric(15, 2) * numtot) - nDescontoNFSE;

                nDescontoNFSEParcela := round(nDescontoNFSEParcela + nDescontoNFSEParcelaResto, 2);

              end if;

              if (nDescontoNFSE < (numtot / 100.00)) then
                if (numpar = 1) then
                  nDescontoNFSEParcela := nDescontoNFSE * -1;
                else
                  return;
                end if;
              end if;

              IF EXISTS (
                SELECT TRUE
                  FROM recibopaga
                 WHERE recibopaga.k00_receit = receita
                   AND recibopaga.k00_hist = 918
                   AND recibopaga.k00_numpre = numpre
                   AND recibopaga.k00_numpar = numpar
                   AND recibopaga.k00_numnov = numnov
              ) THEN

                UPDATE recibopaga
                   SET k00_valor = (k00_valor + nDescontoNFSEParcela)
                 WHERE k00_receit = receita
                   AND k00_hist = 918
                   AND k00_numpre = numpre
                   AND k00_numpar = numpar
                   AND k00_numnov = numnov;

              ELSE

                INSERT INTO recibopaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist,
                  k00_valor,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta,
                  k00_dtpaga,
                  k00_numnov
                ) VALUES (
                  cgm,
                  dataOperacao,
                  receita,
                  918,
                  nDescontoNFSEParcela,
                  dataVencimento,
                  numpre,
                  numpar,
                  numtot,
                  numdig,
                  0,
                  dataPaga,
                  numnov
                );

              END IF;

            END IF;

          END;
          $$ LANGUAGE 'plpgsql';
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
        DROP FUNCTION fc_desconto_iptu_nfse(
          cgm INT, 
          dataOperacao DATE, 
          receita INT, 
          dataVencimento DATE, 
          numpre INT, 
          numpar INT, 
          numtot INT, 
          numdig INT, 
          dataPaga DATE, 
          numnov INT);
SQL;

        $this->execute($sql);
    }
}
