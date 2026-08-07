<?php

use Classes\PostgresMigration;

class M13734RecalculoIptu extends PostgresMigration
{
    public function up()
    {
        $sql = <<<STRING
        CREATE OR REPLACE FUNCTION fc_iptu_gerafinanceiro(INTEGER, INTEGER, INTEGER, INTEGER, BOOLEAN, BOOLEAN, BOOLEAN,
                                                  BOOLEAN, BOOLEAN)
  RETURNS BOOLEAN AS $$
DECLARE
  iMatricula ALIAS FOR $1;
  iAnousu ALIAS FOR $2;
  iParcelaini ALIAS FOR $3;
  iParcelafim ALIAS FOR $4;
  lCalculogeral ALIAS FOR $5;
  lPossuiPagamento ALIAS FOR $6;
  lNovoNumpre ALIAS FOR $7;
  lDemonstrativo ALIAS FOR $8;
  lRaise ALIAS FOR $9;

  lExisteAbatimento BOOLEAN DEFAULT FALSE;
  lRetorno BOOLEAN DEFAULT FALSE;

BEGIN
  RETURN (SELECT fc_iptu_gerafinanceiro(iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, lPossuiPagamento,
                                        lNovoNumpre, lDemonstrativo, lRaise, 0));
END;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION fc_iptu_gerafinanceiro(INTEGER, INTEGER, INTEGER, INTEGER, BOOLEAN, BOOLEAN, BOOLEAN,
                                                  BOOLEAN, BOOLEAN,
                                                  INTEGER)
  RETURNS BOOLEAN
LANGUAGE plpgsql
AS $$
DECLARE

  iMatricula ALIAS FOR $1;
  iAnousu ALIAS FOR $2;
  iParcelaini ALIAS FOR $3;
  iParcelafim ALIAS FOR $4;
  lCalculogeral ALIAS FOR $5;
  lPossuiPagamento ALIAS FOR $6;
  lNovoNumpre ALIAS FOR $7;
  lDemonstrativo ALIAS FOR $8;
  lRaise ALIAS FOR $9;
  iDiasVcto ALIAS FOR $10;

  nValorParcela                   NUMERIC(15, 2) DEFAULT 0;
  nValorIncrementalReceitaParcela NUMERIC(15, 2) DEFAULT 0;
  nValorTotalCalculo              NUMERIC(15, 2) DEFAULT 0;
  nValorParcelaMinima             NUMERIC(15, 2) DEFAULT 0;
  nPercentualParcela              NUMERIC(15, 2) DEFAULT 0;
  nValorTotalAberto               NUMERIC(15, 2) DEFAULT 0;
  nTotalGeradoReceita             NUMERIC(15, 2) DEFAULT 0;
  nTotalCalculoAnterior           NUMERIC(15, 2) DEFAULT 0;
  nTotalCalculoNovo               NUMERIC(15, 2) DEFAULT 0;
  nTotalCreditoReceita            NUMERIC(15, 2) DEFAULT 0;
  nTotalComplementarReceita      NUMERIC(15, 2) DEFAULT 0;
  nValorteste      NUMERIC(15, 2) DEFAULT 0;

  iDigito                        INTEGER DEFAULT 0;
  iNumpre                        INTEGER DEFAULT 0;
  iParcelas                      INTEGER DEFAULT 0;
  iCgm                           INTEGER DEFAULT 0;
  iNumpreArrematric              INTEGER DEFAULT 0;
  iNumeroParcelasPagasCanceladas INTEGER DEFAULT 0;
  iUltimaParcelaGerada           INTEGER DEFAULT 0;
  iDiaPadraoVencimento           INTEGER DEFAULT 0;
  iMesInicial                    INTEGER DEFAULT 0;
  iParcelasPadrao            INTEGER DEFAULT 0;
  iParcelasProcessadas       INTEGER DEFAULT 0;
  iNumpreIptunump            INTEGER DEFAULT 0;
  iNumpreComplementar        INTEGER DEFAULT 0;

  lExisteNumpre              BOOLEAN DEFAULT FALSE;
  lUtilizandoMinima          BOOLEAN DEFAULT FALSE;
  lExisteFinanceiroGerado    BOOLEAN;
  lProcessaParcela           BOOLEAN;
  lProcessaVencimentoForcado BOOLEAN DEFAULT FALSE;
  lExisteAbatimento          BOOLEAN DEFAULT FALSE;
  lRetorno          BOOLEAN DEFAULT FALSE;
  lCreditoRecalculo BOOLEAN DEFAULT FALSE;
  lDebitoRecalculo  BOOLEAN DEFAULT FALSE;
  lCalculoQuitado   BOOLEAN DEFAULT FALSE;
  lRecalculoQuitado BOOLEAN DEFAULT FALSE;

  dDataOperacao     DATE;

  tSqlVencimentos   TEXT DEFAULT '';
  tManual           TEXT DEFAULT '';

  rVencimentos      RECORD;
  rArrecad          RECORD;
  rValoresPorReceita   RECORD;
  rDadosIptu           RECORD;
  rRecibosGerados      RECORD;
  rGeraComplementar    RECORD;
  rIptuCalcConfRec     RECORD;
  rGeraCredito         RECORD;

  nPercentualUnica     NUMERIC;
  nTotalCalculoCredito NUMERIC;
  nValorCreditoDebito  NUMERIC DEFAULT 0;

BEGIN
  -- Verifica se existe Pagamento Parcial para o débito informado
  SELECT j20_numpre
      INTO iNumpreIptunump
  FROM iptunump
  WHERE j20_matric = iMatricula
    AND j20_anousu = iAnousu
  LIMIT 1;


  SELECT sum(valor)
      INTO nValorteste
      FROM tmprecval;

  perform fc_debug('================= tmprecval '||nValorteste ,lRaise);

  IF found
  THEN

    SELECT fc_verifica_abatimento(1, iNumpreIptunump) :: BOOLEAN INTO lExisteAbatimento;
    IF lExisteAbatimento
    THEN
      RAISE EXCEPTION '<erro>Operação Cancelada, Débito com Pagamento Parcial!</erro>';
    END IF;
  END IF;


  SELECT sum(valor)
      INTO nValorteste
      FROM tmprecval;

  perform fc_debug('================= tmprecval '||nValorteste ,lRaise);

  -- Validamos para que não seja possível realizar o recálculo de IPTU quando o mesmo possuir Bom pagador e Isenção
  -- e tiver débitos pagos e abertos no numpre do cálculo.
  PERFORM 1
  FROM iptucalcconfrec
         INNER JOIN iptunump ON iptunump.j20_matric = iptucalcconfrec.j23_matric
                                  AND iptunump.j20_anousu = iptucalcconfrec.j23_anousu
         INNER JOIN arrecad ON arrecad.k00_numpre = iptunump.j20_numpre
         INNER JOIN arrepaga ON arrepaga.k00_numpre = iptunump.j20_numpre
         INNER JOIN tmptaxapercisen ON tmptaxapercisen.rectaxaisen = arrecad.k00_receit
  WHERE iptucalcconfrec.j23_matric = iMatricula
    AND iptucalcconfrec.j23_anousu = iAnousu
    AND tmptaxapercisen.percisen > 0;
  IF found
  THEN
    RAISE EXCEPTION '<erro>Operação Cancelada, não é possível recalcular IPTU de bom pagador com isenção. Cálculo possui parcelas pagas e abertas!</erro>';
  END IF;

  IF lRaise IS TRUE
  THEN

    PERFORM fc_debug('', lRaise, FALSE, FALSE);
    PERFORM fc_debug(' <iptu_gerafinanceiro> Gerando financeiro', lRaise, FALSE, FALSE);
  END IF;

  SELECT coalesce((SELECT sum(k00_valor) FROM arrecad WHERE k00_numpre = j20_numpre), 0) AS valor_total
      INTO nValorTotalAberto
  FROM iptunump
  WHERE j20_matric = iMatricula
    AND j20_anousu = iAnousu;

  iMesInicial := iDiasVcto;
  iParcelasPadrao := iParcelaini;
  iDiaPadraoVencimento := iParcelafim;

  IF iMesInicial <> 0 AND iParcelasPadrao <> 0 AND iDiaPadraoVencimento <> 0
  THEN
    lProcessaVencimentoForcado := TRUE;
  END IF;

  SELECT * FROM tmpdadosiptu INTO rDadosIptu;

  SELECT nparc
      INTO iParcelas FROM tmpdadostaxa;

  /**
   * Verifica codigo de arrecadacao
   */
  SELECT j20_numpre
      INTO iNumpre FROM iptunump WHERE j20_anousu = iAnousu
                                   AND j20_matric = iMatricula;

  PERFORM fc_debug(
            ' <iptu_gerafinanceiro> Calculo geral : '||(CASE WHEN lCalculogeral IS TRUE THEN 'Sim' ELSE 'Nao' END),
            lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> Numpre atual  : '||iNumpre, lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> parcelaini    : '||iParcelaini||' Parcelafim : '||iParcelafim, lRaise, FALSE,
                   FALSE);

  IF iNumpre IS NOT NULL
  THEN

    /**
   * Se for calculo parcial e nao for demonstrativo
   */
    IF lCalculogeral = FALSE AND lDemonstrativo IS FALSE
    THEN

      FOR rArrecad IN SELECT DISTINCT k00_numpar FROM arrecad WHERE k00_numpre = iNumpre ORDER BY k00_numpar
      LOOP

        IF iParcelafim = 0
        THEN

          IF rArrecad.k00_numpar >= iParcelaini
          THEN
            DELETE FROM arrecad WHERE k00_numpre = iNumpre
                                  AND k00_numpar = rArrecad.k00_numpar;
          END IF;

        ELSE

          IF rArrecad.k00_numpar >= iParcelaini AND rArrecad.k00_numpar <= iParcelafim
          THEN
            DELETE FROM arrecad WHERE k00_numpre = iNumpre
                                  AND k00_numpar = rArrecad.k00_numpar;
          END IF;

        END IF;

      END LOOP;

    END IF;

    IF lNovoNumpre = FALSE
    THEN

      lExisteNumpre = TRUE;

    ELSE

      IF lPossuiPagamento = FALSE
      THEN

        IF lCalculogeral = FALSE AND lDemonstrativo IS FALSE
        THEN

          IF lRaise IS TRUE
          THEN
            PERFORM fc_debug(' <iptu_gerafinanceiro> Deletando iptunump', lRaise, FALSE, FALSE);
          END IF;

          DELETE FROM iptunump WHERE j20_anousu = iAnousu
                                 AND j20_matric = iMatricula;
        END IF;

        IF lDemonstrativo IS FALSE
        THEN

          SELECT nextval('numpref_k03_numpre_seq') :: INTEGER
              INTO iNumpre;
        END IF;

      END IF;

    END IF;

  ELSE

    IF lDemonstrativo IS FALSE
    THEN
      SELECT nextval('numpref_k03_numpre_seq') :: INTEGER INTO iNumpre;
    END IF;

  END IF;

  /**
   * Verifica imune
   */
  IF NOT rDadosIptu.tipoisen IS NULL
  THEN

    IF rDadosIptu.tipoisen = 1 AND NOT exists(SELECT 1 FROM tmpipturecalculo WHERE matricula = iMatricula
                                                                               AND anousu = iAnousu)
    THEN
      RETURN TRUE;
    END IF;
  END IF;

  /**
   * Verifica taxas
   */
  IF lRaise IS TRUE
  THEN
    PERFORM fc_debug(' <iptu_gerafinanceiro> Processando vencimentos', lRaise, FALSE, FALSE);
  END IF;

  /**
   * Esta funcao retorna um select com a consulta para gerar os vencimentos
   * lendo os parametros iMesInicial,iParcelasPadrao,iDiaPadraoVencimento... se os parametros forem diferente de 0 a funcao
   * ira criar uma tabela temporaria com a estrutura do select do cadastro de vencimentos e retornara a string do select
   */
  tSqlVencimentos := (SELECT fc_iptu_getselectvencimentos(iMatricula, iAnousu, rDadosIptu.codvenc, iMesInicial,
                                                          iParcelasPadrao, iDiaPadraoVencimento, nValorTotalAberto,
                                                          lRaise));

  EXECUTE 'select count(*) from (' || tSqlVencimentos || ') as x'
  INTO iParcelas;


  SELECT sum(valor)
      INTO nValorteste
      FROM tmprecval;

  perform fc_debug('================= tmprecval '||nValorteste ,lRaise);

  lProcessaParcela = TRUE;

  PERFORM fc_debug(' <iptu_gerafinanceiro> Sql retornado dos vencimentos: ' || tSqlVencimentos, lRaise, FALSE, FALSE);

  /**
   * Cgm que sera gravado no arrecad e arrenumcgm
   */
  SELECT fc_iptu_getcgmiptu(iMatricula) INTO iCgm;


  SELECT sum(valor)
      INTO nValorteste
      FROM tmprecval;

  perform fc_debug('================= tmprecval '||nValorteste ,lRaise);
  /**
   * Data de operacao do cfiptu
   */
  SELECT j18_dtoper
      INTO dDataOperacao FROM cfiptu WHERE j18_anousu = iAnousu;

  /**
   * Quantidade de receitas e valores gerados pelo calculo
   */
  SELECT sum(valor)
      INTO nValorTotalCalculo FROM tmprecval;

  PERFORM fc_debug(' <iptu_gerafinanceiro> Total retornado da tmprecval: '||nValorTotalCalculo, lRaise, FALSE, FALSE);

  /**
   * Valor de minimo da parcela
   */
  SELECT q92_vlrminimo
      INTO nValorParcelaMinima FROM cadvencdesc WHERE q92_codigo = rDadosIptu.codvenc;

  /**
   * Quantidade de parcelas que já foram
   * pagas e ou canceladas do iptu sendo gerado
   */
  SELECT coalesce(count(DISTINCT k00_numpar), 0)
      INTO iNumeroParcelasPagasCanceladas
  FROM (SELECT DISTINCT k00_numpar FROM arrecant WHERE arrecant.k00_numpre = iNumpre) AS x;

  PERFORM fc_debug(' <iptu_gerafinanceiro> TOTAL: '||nValorTotalCalculo||' - nValorParcelaMinima: '||nValorParcelaMinima||' - iParcelas: '||iParcelas||' - Divisao (nValorTotalCalculo / iParcelas): '||(nValorTotalCalculo / iParcelas),
                   lRaise, FALSE, FALSE);

  /**
   * Validamos se este é um recalculo de IPTU quitado
   * Se for, faremos as devidadas validações para determinar se devemos gerar débito ou crédito
   */
  PERFORM * FROM tmpipturecalculo WHERE matricula = iMatricula
                                    AND anousu = iAnousu;

  IF found
  THEN

    lRecalculoQuitado := TRUE;

    PERFORM fc_debug(' <iptu_gerafinanceiro> Recalculo de IPTU quitado detectado ', lRaise, FALSE, FALSE);

    SELECT sum(valor - valor_isencao)
        INTO nTotalCalculoAnterior
    FROM tmpipturecalculo
    WHERE matricula = iMatricula
      AND anousu = iAnousu
      AND valor > 0;

    SELECT sum(j21_valor)
        INTO nTotalCalculoNovo
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu;

    PERFORM fc_debug(' <iptu_gerafinanceiro> Valor total do calculo anterior '||nTotalCalculoAnterior, lRaise, FALSE,
                     FALSE);
    PERFORM fc_debug(' <iptu_gerafinanceiro> Valor total do calculo novo '||nTotalCalculoNovo, lRaise, FALSE, FALSE);

    --     Busca desconto no cálculo anterior caso haja valor da L.I.C.
    nTotalCalculoAnterior := fc_iptu_deducao_lic(iMatricula, iAnousu) + nTotalCalculoAnterior;

    IF nTotalCalculoAnterior < nTotalCalculoNovo
    THEN
      lDebitoRecalculo := TRUE;
    END IF;

    IF nTotalCalculoAnterior > nTotalCalculoNovo
    THEN
      lCreditoRecalculo := TRUE;
    END IF;

  END IF;

  PERFORM fc_debug(' <iptu_gerafinanceiro> lDebitoRecalculo '||lDebitoRecalculo, lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> lCreditoRecalculo '||lCreditoRecalculo, lRaise, FALSE, FALSE);

  -- Verifica se mudou alguma das receitas comparado com o cálculo anterior
  PERFORM *
  FROM tmpipturecalculo
  WHERE NOT exists(SELECT * FROM iptucalv WHERE j21_matric = matricula
                                            AND j21_anousu = anousu
                                            AND j21_receit = receita);

  IF found
  THEN

    FOR rIptuCalcConfRec IN

    SELECT j23_recorg, j23_recdst
    FROM iptucalcconfrec
    WHERE iptucalcconfrec.j23_matric = iMatricula
      AND iptucalcconfrec.j23_anousu = iAnousu

    LOOP

      UPDATE tmpipturecalculo
      SET receita = rIptuCalcConfRec.j23_recdst
      WHERE receita = rIptuCalcConfRec.j23_recorg
        AND matricula = iMatricula
        AND anousu = iAnousu;

    END LOOP;

    UPDATE tmpipturecalculo
    SET receita = (SELECT j21_receit
                   FROM iptucalv
                   WHERE j21_matric = iMatricula
                     AND j21_anousu = iAnousu
                     AND NOT exists(SELECT * FROM tmpipturecalculo WHERE matricula = j21_matric
                                                                     AND anousu = j21_anousu
                                                                     AND j21_receit = receita)
                   LIMIT 1)
    WHERE NOT exists(SELECT * FROM iptucalv WHERE j21_matric = matricula
                                              AND j21_anousu = anousu
                                              AND j21_receit = receita);

  END IF;

  IF lDebitoRecalculo IS TRUE
  THEN

    FOR rGeraComplementar IN

    SELECT j21_receit, sum(j21_valor) AS j21_valor
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu
    GROUP BY j21_receit

    LOOP

      SELECT rGeraComplementar.j21_valor - sum(valor - valor_isencao)
          INTO nTotalComplementarReceita
      FROM tmpipturecalculo
      WHERE matricula = iMatricula
        AND anousu = iAnousu
        AND receita = rGeraComplementar.j21_receit
        AND valor > 0;

      --       Deduz o valor do desconto da L.I.C. do calculo anterior caso haja
      nTotalComplementarReceita := nTotalComplementarReceita -
                                   fc_iptu_deducao_lic(iMatricula, iAnousu, rGeraComplementar.j21_receit);

      IF nTotalComplementarReceita > 0
      THEN

        SELECT fc_iptu_complementar(iMatricula, iAnousu, nTotalComplementarReceita, rGeraComplementar.j21_receit,
                                    lRaise)
            INTO iNumpreComplementar;

        IF (SELECT count(DISTINCT k00_dtpaga) FROM arrepaga WHERE k00_numpre = iNumpre) = 1
        THEN

          -- SELECT k00_percdes
          --     INTO nPercentualUnica
          -- FROM recibounica
          -- WHERE k00_numpre = iNumpre
          --   AND k00_dtvenc >= (SELECT coalesce(disbanco.dtpago, arrepaga.k00_dtpaga)
          --                      FROM arrepaga
          --                             LEFT JOIN arreidret ON arreidret.k00_numpre = arrepaga.k00_numpre
          --                                                      AND arreidret.k00_numpar = arrepaga.k00_numpar
          --                             LEFT JOIN disbanco ON disbanco.idret = arreidret.idret
          --                      WHERE arrepaga.k00_numpre = iNumpre
          --                      LIMIT 1)
          -- ORDER BY k00_dtvenc
          -- LIMIT 1;

          -- IF found AND nPercentualUnica > 0
          -- THEN

          --   PERFORM fc_debug(' <iptu_gerafinanceiro> nTotalComplementarReceita '||nTotalComplementarReceita, lRaise, FALSE, FALSE);
          --   PERFORM fc_debug(' <iptu_gerafinanceiro> nPercentualUnica '||nPercentualUnica, lRaise, FALSE, FALSE);

          --   nTotalComplementarReceita := nTotalComplementarReceita * (nPercentualUnica / 100);
          --   PERFORM fc_debug(' <iptu_gerafinanceiro> Desconto da unica '||nTotalComplementarReceita, lRaise, FALSE, FALSE);

          --   PERFORM fc_debug(' <iptu_gerafinanceiro> Executar compensação', lRaise, FALSE, FALSE);

          --   PERFORM fc_iptu_compensacao_automatica(nTotalComplementarReceita, rGeraComplementar.j21_receit,
          --                                          iNumpreComplementar, iMatricula, iAnousu, lRaise);

          -- END IF;

        END IF;

      END IF;

    END LOOP;

    -- Verifica se alguma das receitas deve gerar crédito
    FOR rGeraCredito IN

    SELECT j21_receit, sum(j21_valor) AS j21_valor
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu
    GROUP BY j21_receit

    LOOP

      SELECT rGeraCredito.j21_valor - sum(valor - valor_isencao)
          INTO nTotalCreditoReceita
      FROM tmpipturecalculo
      WHERE matricula = iMatricula
        AND anousu = iAnousu
        AND receita = rGeraCredito.j21_receit;

      IF nTotalCreditoReceita < 0
      THEN

        lCreditoRecalculo := TRUE;
        nTotalCalculoNovo := nTotalCalculoAnterior - abs(nTotalCreditoReceita);
      END IF;

    END LOOP;

  END IF;

  IF lCreditoRecalculo IS TRUE
  THEN

    nTotalCalculoCredito := nTotalCalculoAnterior - nTotalCalculoNovo;

    IF (SELECT count(DISTINCT k00_dtpaga) FROM arrepaga WHERE k00_numpre = iNumpre) = 1
    THEN

      SELECT k00_percdes
          INTO nPercentualUnica
      FROM recibounica
      WHERE k00_numpre = iNumpre
        AND k00_dtvenc >= (SELECT coalesce(disbanco.dtpago, arrepaga.k00_dtpaga)
                           FROM arrepaga
                                  LEFT JOIN arreidret ON arreidret.k00_numpre = arrepaga.k00_numpre
                                                           AND arreidret.k00_numpar = arrepaga.k00_numpar
                                  LEFT JOIN disbanco ON disbanco.idret = arreidret.idret
                           WHERE arrepaga.k00_numpre = iNumpre
                           LIMIT 1)
      ORDER BY k00_dtvenc
      LIMIT 1;

      IF found AND nPercentualUnica > 0
      THEN
        nTotalCalculoCredito := nTotalCalculoCredito * (1 - (nPercentualUnica / 100));
      END IF;

    END IF;

PERFORM fc_debug(' <iptu_gerafinanceiro> GERANDO CRÉDITO.', lRaise, FALSE,FALSE);

    SELECT fc_iptu_geracreditorecalculo(iMatricula, iAnousu, nTotalCalculoCredito, lRaise)
        INTO lCalculoQuitado;

    IF lCalculoQuitado IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Erro ao gerar crédito no recálculo de IPTU Quitado.', lRaise, FALSE,
                       FALSE);
      RETURN FALSE;
    END IF;
  END IF;

  /**
   * Caso seja recalculo mas o IPTU não esteja quitado porém o novo valor recalculado seja menor do que a soma do valor
   * pago do IPTU até o momento. Neste caso gera um crédito com a diferença do valor
   */
  IF nValorTotalCalculo < 0 AND lRecalculoQuitado IS FALSE
  THEN

  PERFORM fc_debug(' <iptu_gerafinanceiro> GERANDO CRÉDITO.', lRaise, FALSE,FALSE);

    SELECT fc_iptu_geracreditorecalculo(iMatricula, iAnousu, abs(nValorTotalCalculo), lRaise)
        INTO lCalculoQuitado;

    IF lCalculoQuitado IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Erro ao gerar crédito no recálculo de IPTU Quitado.', lRaise, FALSE,
                       FALSE);
      RETURN FALSE;
    END IF;

  /**
   * Se não tiver que gerar débito de diversos ou crédito por ser um recalculo de IPTU quitado,
   * Realizamos a geração de débito normal, usando as receitas de IPTU e Taxas(caso tenha) e suas
   * devidas parcelas
   */
  ELSIF nValorTotalCalculo > 0 AND lCreditoRecalculo IS FALSE AND lDebitoRecalculo IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Inicia rateio de valor por parcela', lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> Parcelas: '||iParcelas||' nValorTotalCalculo: '||nValorTotalCalculo,
                       lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> Verifica se ('||nValorTotalCalculo||' / '||iParcelas||') eh menor que o valor de parcela minimo '||nValorParcelaMinima,
                       lRaise, FALSE, FALSE);
      IF (nValorTotalCalculo / iParcelas) < nValorParcelaMinima
      THEN

        IF floor((nValorTotalCalculo / nValorParcelaMinima) :: NUMERIC) :: INTEGER = 0
        THEN
          iParcelas := 1;
        ELSE
          iParcelas := floor((nValorTotalCalculo / nValorParcelaMinima) :: NUMERIC) :: INTEGER;
        END IF;

        lUtilizandoMinima := TRUE;
        PERFORM fc_debug(' <iptu_gerafinanceiro> Entrou em parcela minima... ', lRaise, FALSE, FALSE);
        PERFORM fc_debug(' <iptu_gerafinanceiro> Quantidade de Parcelas: '||iParcelas, lRaise, FALSE, FALSE);
      END IF;

      PERFORM fc_debug('', lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> NUMPRE DO CALCULO: '||iNumpre, lRaise, FALSE, FALSE);
      PERFORM fc_debug('', lRaise, FALSE, FALSE);

      PERFORM fc_debug(' <iptu_gerafinanceiro> Percorrendo valores a serem gerados agrupado por receita '||iNumpre,
                       lRaise, FALSE, FALSE);


      /**
       * Agrupa por receita
       */
      FOR rValoresPorReceita IN SELECT receita,
                                       (SELECT count(DISTINCT receita) FROM tmprecval) AS qtdreceitas,
                                       sum(valor)                                      AS valor
                                FROM tmprecval
                                GROUP BY receita
                                ORDER BY receita
      LOOP

        nValorIncrementalReceitaParcela := 0;
        iParcelasProcessadas := 1;

        PERFORM fc_debug(
                  ' <iptu_gerafinanceiro> iParcelasProcessadas: '||iParcelasProcessadas||' iParcelas: '||iParcelas,
                  lRaise, FALSE, FALSE);

        /**
         * Percorre o record de vencimentos rateando o valor que fora agrupado por receita
         */
        FOR rVencimentos IN EXECUTE tSqlVencimentos
        LOOP

          IF lUtilizandoMinima IS FALSE
          THEN
            nPercentualParcela := cast(rVencimentos.q82_perc AS NUMERIC(15, 2));
          ELSE
            nPercentualParcela := 100 :: NUMERIC / iParcelas;
          END IF;

          PERFORM fc_debug(' <iptu_gerafinanceiro> Percentual da parcela ' || nPercentualParcela, lRaise, FALSE, FALSE);

          IF iParcelas < iParcelasProcessadas AND lProcessaVencimentoForcado IS FALSE
          THEN

            PERFORM fc_debug(' <iptu_gerafinanceiro> PARCELA '||rVencimentos.q82_parc||' NAO SERA CALCULADA', lRaise,
                             FALSE, FALSE);
            PERFORM fc_debug('', lRaise, FALSE, FALSE);
            CONTINUE;
          END IF;

          IF iParcelaini = 0
          THEN

            PERFORM fc_debug(' <iptu_gerafinanceiro> lProcessaParcela = true | iParcelaini = 0', lRaise, FALSE, FALSE);
            lProcessaParcela = TRUE;
          ELSE

            IF rVencimentos.q82_parc >= iParcelaini AND rVencimentos.q82_parc <= iParcelafim
            THEN
              lProcessaParcela = TRUE;
            ELSE
              lProcessaParcela = FALSE;
            END IF;

          END IF;

          IF lProcessaVencimentoForcado
          THEN
            lProcessaParcela = TRUE;
          END IF;

          PERFORM fc_debug(
                    ' <iptu_gerafinanceiro> Processando parcela = '||(CASE WHEN lProcessaParcela IS TRUE THEN 'Sim' ELSE 'Nao' END),
                    lRaise, FALSE, FALSE);

          IF lProcessaParcela IS TRUE
          THEN

            PERFORM *
            FROM fc_statusdebitos(iNumpre, rVencimentos.q82_parc)
            WHERE rtstatus = 'PAGO'
               OR rtstatus = 'CANCELADO'
            LIMIT 1;

            IF found
            THEN

              PERFORM fc_debug(' <iptu_gerafinanceiro> Ignorando parcela '||rVencimentos.q82_parc||' por estar paga ou cancelada',
                               lRaise, FALSE, FALSE);
              PERFORM fc_debug('', lRaise, FALSE, FALSE);
              CONTINUE;
            END IF;

            IF rValoresPorReceita.valor > 0
            THEN

              IF iParcelas = iParcelasProcessadas AND iNumeroParcelasPagasCanceladas = 0
              THEN
                nValorParcela := rValoresPorReceita.valor - nValorIncrementalReceitaParcela;
              ELSE

                nValorParcela := trunc(rValoresPorReceita.valor * (nPercentualParcela / 100 :: NUMERIC) :: NUMERIC, 2);
                nValorIncrementalReceitaParcela := nValorIncrementalReceitaParcela + nValorParcela;
              END IF;

              lExisteFinanceiroGerado := TRUE;
              iDigito := fc_digito(iNumpre, rVencimentos.q82_parc, iParcelas);

              PERFORM fc_debug('', lRaise, FALSE, FALSE);
              PERFORM fc_debug(
                        ' <iptu_gerafinanceiro> Parcela: '||rVencimentos.q82_parc||' Receita: '||rValoresPorReceita.receita||' Valor: '||nValorParcela,
                        lRaise, FALSE, FALSE);

              IF lDemonstrativo IS FALSE
              THEN

                iParcelasProcessadas = (iParcelasProcessadas + 1);

                IF round(nValorParcela, 2) = 0
                THEN

                  PERFORM fc_debug(' <iptu_gerafinanceiro> Valor de parcela zerado, continue...', lRaise);
                  CONTINUE;
                END IF;

                PERFORM fc_debug(' <iptu_gerafinanceiro> GERANDO ARRECAD ', lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> ', lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Numpre .......: '||iNumpre, lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Numpar .......: '||rVencimentos.q82_parc, lRaise, FALSE,
                                 FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Receita ......: '||rValoresPorReceita.receita, lRaise, FALSE,
                                 FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Valor ........: '||nValorParcela, lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Vencimento ...: '||rVencimentos.q82_parc, lRaise, FALSE,
                                 FALSE);

                DELETE
                FROM arrecad
                WHERE k00_numpre = iNumpre
                  AND k00_numpar = rVencimentos.q82_parc
                  AND k00_receit = rValoresPorReceita.receita;

                INSERT INTO arrecad (k00_numcgm,
                                     k00_dtoper,
                                     k00_receit,
                                     k00_hist,
                                     k00_valor,
                                     k00_dtvenc,
                                     k00_numpre,
                                     k00_numpar,
                                     k00_numtot,
                                     k00_numdig,
                                     k00_tipo)
                VALUES (iCgm,
                        dDataOperacao,
                        rValoresPorReceita.receita,
                        rVencimentos.q82_hist,
                        nValorParcela,
                        rVencimentos.q82_venc,
                        iNumpre,
                        rVencimentos.q82_parc,
                        iParcelas,
                        iDigito,
                        rVencimentos.q92_tipo);
              END IF;

            END IF;

          END IF;

          PERFORM fc_debug(' <iptu_gerafinanceiro> nValorParcela.: '||nValorParcela, lRaise, FALSE, FALSE);
          PERFORM fc_debug(
                    ' <iptu_gerafinanceiro> nValorIncrementalReceitaParcela: ' || nValorIncrementalReceitaParcela,
                    lRaise);

        END LOOP;

        /*
         * Lancando a diferenca na ultima parcela
         */
        SELECT max(k00_numpar)
            INTO iUltimaParcelaGerada FROM arrecad WHERE k00_numpre = iNumpre;

        SELECT sum(k00_valor)
            INTO nTotalGeradoReceita
        FROM arrecad
        WHERE k00_numpre = iNumpre
          AND k00_receit = rValoresPorReceita.receita;

        UPDATE arrecad
        SET k00_valor = (k00_valor + (rValoresPorReceita.valor - nTotalGeradoReceita))
        WHERE k00_numpre = iNumpre
          AND k00_numpar = iUltimaParcelaGerada
          AND k00_receit = rValoresPorReceita.receita;

      END LOOP;

      IF lRaise IS TRUE
      THEN

        PERFORM fc_debug('', lRaise, FALSE, FALSE);
        PERFORM fc_debug(' <iptu_gerafinanceiro> Verificando e gerando arrematric, iptunump e iptucalc', lRaise, FALSE,
                         FALSE);
      END IF;

      IF lExisteFinanceiroGerado = TRUE
      THEN

        IF lDemonstrativo IS FALSE
        THEN

          SELECT k00_numpre
              INTO iNumpreArrematric FROM arrematric WHERE k00_numpre = iNumpre
                                                       AND k00_matric = iMatricula;

          IF iNumpreArrematric IS NULL
          THEN
            INSERT INTO arrematric (k00_numpre, k00_matric) VALUES (iNumpre, iMatricula);
          END IF;

          FOR rRecibosGerados IN SELECT DISTINCT recibopaga.k00_numnov, recibopaga.k00_dtoper, recibopaga.k00_dtpaga
                                 FROM arrecad
                                        INNER JOIN recibopaga ON recibopaga.k00_numpre = arrecad.k00_numpre
                                                                   AND recibopaga.k00_numpar = arrecad.k00_numpar
                                 WHERE arrecad.k00_numpre = iNumpre
          LOOP

            DELETE FROM recibopaga WHERE k00_numnov = rRecibosGerados.k00_numnov;
            PERFORM fc_recibo(rRecibosGerados.k00_numnov, rRecibosGerados.k00_dtoper, rRecibosGerados.k00_dtpaga,
                              extract(YEAR FROM rRecibosGerados.k00_dtpaga) :: INTEGER);
          END LOOP;

        END IF;

        IF lExisteNumpre = FALSE AND lDemonstrativo IS FALSE
        THEN
          INSERT INTO iptunump (j20_anousu, j20_matric, j20_numpre) VALUES (iAnousu, iMatricula, iNumpre);
        END IF;

      END IF;

  END IF;

  IF lDemonstrativo IS FALSE
  THEN
    UPDATE iptucalc SET j23_manual = tManual WHERE j23_matric = iMatricula
                                               AND j23_anousu = iAnousu;
  END IF;

  IF lRaise IS TRUE
  THEN
    PERFORM fc_debug(' <iptu_gerafinanceiro> Fim do processamento da funcao iptu_gerafinanceiro', lRaise, FALSE, TRUE);
  END IF;

  RETURN TRUE;

END;
$$;

STRING;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<STRING
        CREATE OR REPLACE FUNCTION fc_iptu_gerafinanceiro(INTEGER, INTEGER, INTEGER, INTEGER, BOOLEAN, BOOLEAN, BOOLEAN,
                                                  BOOLEAN, BOOLEAN)
  RETURNS BOOLEAN AS $$
DECLARE
  iMatricula ALIAS FOR $1;
  iAnousu ALIAS FOR $2;
  iParcelaini ALIAS FOR $3;
  iParcelafim ALIAS FOR $4;
  lCalculogeral ALIAS FOR $5;
  lPossuiPagamento ALIAS FOR $6;
  lNovoNumpre ALIAS FOR $7;
  lDemonstrativo ALIAS FOR $8;
  lRaise ALIAS FOR $9;

  lExisteAbatimento BOOLEAN DEFAULT FALSE;
  lRetorno BOOLEAN DEFAULT FALSE;

BEGIN
  RETURN (SELECT fc_iptu_gerafinanceiro(iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, lPossuiPagamento,
                                        lNovoNumpre, lDemonstrativo, lRaise, 0));
END;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION fc_iptu_gerafinanceiro(INTEGER, INTEGER, INTEGER, INTEGER, BOOLEAN, BOOLEAN, BOOLEAN,
                                                  BOOLEAN, BOOLEAN,
                                                  INTEGER)
  RETURNS BOOLEAN
LANGUAGE plpgsql
AS $$
DECLARE

  iMatricula ALIAS FOR $1;
  iAnousu ALIAS FOR $2;
  iParcelaini ALIAS FOR $3;
  iParcelafim ALIAS FOR $4;
  lCalculogeral ALIAS FOR $5;
  lPossuiPagamento ALIAS FOR $6;
  lNovoNumpre ALIAS FOR $7;
  lDemonstrativo ALIAS FOR $8;
  lRaise ALIAS FOR $9;
  iDiasVcto ALIAS FOR $10;

  nValorParcela                   NUMERIC(15, 2) DEFAULT 0;
  nValorIncrementalReceitaParcela NUMERIC(15, 2) DEFAULT 0;
  nValorTotalCalculo              NUMERIC(15, 2) DEFAULT 0;
  nValorParcelaMinima             NUMERIC(15, 2) DEFAULT 0;
  nPercentualParcela              NUMERIC(15, 2) DEFAULT 0;
  nValorTotalAberto               NUMERIC(15, 2) DEFAULT 0;
  nTotalGeradoReceita             NUMERIC(15, 2) DEFAULT 0;
  nTotalCalculoAnterior           NUMERIC(15, 2) DEFAULT 0;
  nTotalCalculoNovo               NUMERIC(15, 2) DEFAULT 0;
  nTotalCreditoReceita            NUMERIC(15, 2) DEFAULT 0;
  nTotalComplementarReceita      NUMERIC(15, 2) DEFAULT 0;

  iDigito                        INTEGER DEFAULT 0;
  iNumpre                        INTEGER DEFAULT 0;
  iParcelas                      INTEGER DEFAULT 0;
  iCgm                           INTEGER DEFAULT 0;
  iNumpreArrematric              INTEGER DEFAULT 0;
  iNumeroParcelasPagasCanceladas INTEGER DEFAULT 0;
  iUltimaParcelaGerada           INTEGER DEFAULT 0;
  iDiaPadraoVencimento           INTEGER DEFAULT 0;
  iMesInicial                    INTEGER DEFAULT 0;
  iParcelasPadrao            INTEGER DEFAULT 0;
  iParcelasProcessadas       INTEGER DEFAULT 0;
  iNumpreIptunump            INTEGER DEFAULT 0;
  iNumpreComplementar        INTEGER DEFAULT 0;

  lExisteNumpre              BOOLEAN DEFAULT FALSE;
  lUtilizandoMinima          BOOLEAN DEFAULT FALSE;
  lExisteFinanceiroGerado    BOOLEAN;
  lProcessaParcela           BOOLEAN;
  lProcessaVencimentoForcado BOOLEAN DEFAULT FALSE;
  lExisteAbatimento          BOOLEAN DEFAULT FALSE;
  lRetorno          BOOLEAN DEFAULT FALSE;
  lCreditoRecalculo BOOLEAN DEFAULT FALSE;
  lDebitoRecalculo  BOOLEAN DEFAULT FALSE;
  lCalculoQuitado   BOOLEAN DEFAULT FALSE;
  lRecalculoQuitado BOOLEAN DEFAULT FALSE;

  dDataOperacao     DATE;

  tSqlVencimentos   TEXT DEFAULT '';
  tManual           TEXT DEFAULT '';

  rVencimentos      RECORD;
  rArrecad          RECORD;
  rValoresPorReceita   RECORD;
  rDadosIptu           RECORD;
  rRecibosGerados      RECORD;
  rGeraComplementar    RECORD;
  rIptuCalcConfRec     RECORD;
  rGeraCredito         RECORD;

  nPercentualUnica     NUMERIC;
  nTotalCalculoCredito NUMERIC;
  nValorCreditoDebito  NUMERIC DEFAULT 0;

BEGIN
  -- Verifica se existe Pagamento Parcial para o débito informado
  SELECT j20_numpre
      INTO iNumpreIptunump
  FROM iptunump
  WHERE j20_matric = iMatricula
    AND j20_anousu = iAnousu
  LIMIT 1;

  IF found
  THEN

    SELECT fc_verifica_abatimento(1, iNumpreIptunump) :: BOOLEAN INTO lExisteAbatimento;
    IF lExisteAbatimento
    THEN
      RAISE EXCEPTION '<erro>Operação Cancelada, Débito com Pagamento Parcial!</erro>';
    END IF;
  END IF;

  -- Validamos para que não seja possível realizar o recálculo de IPTU quando o mesmo possuir Bom pagador e Isenção
  -- e tiver débitos pagos e abertos no numpre do cálculo.
  PERFORM 1
  FROM iptucalcconfrec
         INNER JOIN iptunump ON iptunump.j20_matric = iptucalcconfrec.j23_matric
                                  AND iptunump.j20_anousu = iptucalcconfrec.j23_anousu
         INNER JOIN arrecad ON arrecad.k00_numpre = iptunump.j20_numpre
         INNER JOIN arrepaga ON arrepaga.k00_numpre = iptunump.j20_numpre
         INNER JOIN tmptaxapercisen ON tmptaxapercisen.rectaxaisen = arrecad.k00_receit
  WHERE iptucalcconfrec.j23_matric = iMatricula
    AND iptucalcconfrec.j23_anousu = iAnousu
    AND tmptaxapercisen.percisen > 0;
  IF found
  THEN
    RAISE EXCEPTION '<erro>Operação Cancelada, não é possível recalcular IPTU de bom pagador com isenção. Cálculo possui parcelas pagas e abertas!</erro>';
  END IF;

  IF lRaise IS TRUE
  THEN

    PERFORM fc_debug('', lRaise, FALSE, FALSE);
    PERFORM fc_debug(' <iptu_gerafinanceiro> Gerando financeiro', lRaise, FALSE, FALSE);
  END IF;

  SELECT coalesce((SELECT sum(k00_valor) FROM arrecad WHERE k00_numpre = j20_numpre), 0) AS valor_total
      INTO nValorTotalAberto
  FROM iptunump
  WHERE j20_matric = iMatricula
    AND j20_anousu = iAnousu;

  iMesInicial := iDiasVcto;
  iParcelasPadrao := iParcelaini;
  iDiaPadraoVencimento := iParcelafim;

  IF iMesInicial <> 0 AND iParcelasPadrao <> 0 AND iDiaPadraoVencimento <> 0
  THEN
    lProcessaVencimentoForcado := TRUE;
  END IF;

  SELECT * FROM tmpdadosiptu INTO rDadosIptu;

  SELECT nparc
      INTO iParcelas FROM tmpdadostaxa;

  /**
   * Verifica codigo de arrecadacao
   */
  SELECT j20_numpre
      INTO iNumpre FROM iptunump WHERE j20_anousu = iAnousu
                                   AND j20_matric = iMatricula;

  PERFORM fc_debug(
            ' <iptu_gerafinanceiro> Calculo geral : '||(CASE WHEN lCalculogeral IS TRUE THEN 'Sim' ELSE 'Nao' END),
            lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> Numpre atual  : '||iNumpre, lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> parcelaini    : '||iParcelaini||' Parcelafim : '||iParcelafim, lRaise, FALSE,
                   FALSE);

  IF iNumpre IS NOT NULL
  THEN

    /**
   * Se for calculo parcial e nao for demonstrativo
   */
    IF lCalculogeral = FALSE AND lDemonstrativo IS FALSE
    THEN

      FOR rArrecad IN SELECT DISTINCT k00_numpar FROM arrecad WHERE k00_numpre = iNumpre ORDER BY k00_numpar
      LOOP

        IF iParcelafim = 0
        THEN

          IF rArrecad.k00_numpar >= iParcelaini
          THEN
            DELETE FROM arrecad WHERE k00_numpre = iNumpre
                                  AND k00_numpar = rArrecad.k00_numpar;
          END IF;

        ELSE

          IF rArrecad.k00_numpar >= iParcelaini AND rArrecad.k00_numpar <= iParcelafim
          THEN
            DELETE FROM arrecad WHERE k00_numpre = iNumpre
                                  AND k00_numpar = rArrecad.k00_numpar;
          END IF;

        END IF;

      END LOOP;

    END IF;

    IF lNovoNumpre = FALSE
    THEN

      lExisteNumpre = TRUE;

    ELSE

      IF lPossuiPagamento = FALSE
      THEN

        IF lCalculogeral = FALSE AND lDemonstrativo IS FALSE
        THEN

          IF lRaise IS TRUE
          THEN
            PERFORM fc_debug(' <iptu_gerafinanceiro> Deletando iptunump', lRaise, FALSE, FALSE);
          END IF;

          DELETE FROM iptunump WHERE j20_anousu = iAnousu
                                 AND j20_matric = iMatricula;
        END IF;

        IF lDemonstrativo IS FALSE
        THEN

          SELECT nextval('numpref_k03_numpre_seq') :: INTEGER
              INTO iNumpre;
        END IF;

      END IF;

    END IF;

  ELSE

    IF lDemonstrativo IS FALSE
    THEN
      SELECT nextval('numpref_k03_numpre_seq') :: INTEGER INTO iNumpre;
    END IF;

  END IF;

  /**
   * Verifica imune
   */
  IF NOT rDadosIptu.tipoisen IS NULL
  THEN

    IF rDadosIptu.tipoisen = 1 AND NOT exists(SELECT 1 FROM tmpipturecalculo WHERE matricula = iMatricula
                                                                               AND anousu = iAnousu)
    THEN
      RETURN TRUE;
    END IF;
  END IF;

  /**
   * Verifica taxas
   */
  IF lRaise IS TRUE
  THEN
    PERFORM fc_debug(' <iptu_gerafinanceiro> Processando vencimentos', lRaise, FALSE, FALSE);
  END IF;

  /**
   * Esta funcao retorna um select com a consulta para gerar os vencimentos
   * lendo os parametros iMesInicial,iParcelasPadrao,iDiaPadraoVencimento... se os parametros forem diferente de 0 a funcao
   * ira criar uma tabela temporaria com a estrutura do select do cadastro de vencimentos e retornara a string do select
   */
  tSqlVencimentos := (SELECT fc_iptu_getselectvencimentos(iMatricula, iAnousu, rDadosIptu.codvenc, iMesInicial,
                                                          iParcelasPadrao, iDiaPadraoVencimento, nValorTotalAberto,
                                                          lRaise));

  EXECUTE 'select count(*) from (' || tSqlVencimentos || ') as x'
  INTO iParcelas;

  lProcessaParcela = TRUE;

  PERFORM fc_debug(' <iptu_gerafinanceiro> Sql retornado dos vencimentos: ' || tSqlVencimentos, lRaise, FALSE, FALSE);

  /**
   * Cgm que sera gravado no arrecad e arrenumcgm
   */
  SELECT fc_iptu_getcgmiptu(iMatricula) INTO iCgm;

  /**
   * Data de operacao do cfiptu
   */
  SELECT j18_dtoper
      INTO dDataOperacao FROM cfiptu WHERE j18_anousu = iAnousu;

  /**
   * Quantidade de receitas e valores gerados pelo calculo
   */
  SELECT sum(valor)
      INTO nValorTotalCalculo FROM tmprecval;

  PERFORM fc_debug(' <iptu_gerafinanceiro> Total retornado da tmprecval: '||nValorTotalCalculo, lRaise, FALSE, FALSE);

  /**
   * Valor de minimo da parcela
   */
  SELECT q92_vlrminimo
      INTO nValorParcelaMinima FROM cadvencdesc WHERE q92_codigo = rDadosIptu.codvenc;

  /**
   * Quantidade de parcelas que já foram
   * pagas e ou canceladas do iptu sendo gerado
   */
  SELECT coalesce(count(DISTINCT k00_numpar), 0)
      INTO iNumeroParcelasPagasCanceladas
  FROM (SELECT DISTINCT k00_numpar FROM arrecant WHERE arrecant.k00_numpre = iNumpre) AS x;

  PERFORM fc_debug(' <iptu_gerafinanceiro> TOTAL: '||nValorTotalCalculo||' - nValorParcelaMinima: '||nValorParcelaMinima||' - iParcelas: '||iParcelas||' - Divisao (nValorTotalCalculo / iParcelas): '||(nValorTotalCalculo / iParcelas),
                   lRaise, FALSE, FALSE);

  /**
   * Validamos se este é um recalculo de IPTU quitado
   * Se for, faremos as devidadas validações para determinar se devemos gerar débito ou crédito
   */
  PERFORM * FROM tmpipturecalculo WHERE matricula = iMatricula
                                    AND anousu = iAnousu;

  IF found
  THEN

    lRecalculoQuitado := TRUE;

    PERFORM fc_debug(' <iptu_gerafinanceiro> Recalculo de IPTU quitado detectado ', lRaise, FALSE, FALSE);

    SELECT sum(valor - valor_isencao)
        INTO nTotalCalculoAnterior
    FROM tmpipturecalculo
    WHERE matricula = iMatricula
      AND anousu = iAnousu
      AND valor > 0;

    SELECT sum(j21_valor)
        INTO nTotalCalculoNovo
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu;

    PERFORM fc_debug(' <iptu_gerafinanceiro> Valor total do calculo anterior '||nTotalCalculoAnterior, lRaise, FALSE,
                     FALSE);
    PERFORM fc_debug(' <iptu_gerafinanceiro> Valor total do calculo novo '||nTotalCalculoNovo, lRaise, FALSE, FALSE);

    --     Busca desconto no cálculo anterior caso haja valor da L.I.C.
    nTotalCalculoAnterior := fc_iptu_deducao_lic(iMatricula, iAnousu) + nTotalCalculoAnterior;

    IF nTotalCalculoAnterior < nTotalCalculoNovo
    THEN
      lDebitoRecalculo := TRUE;
    END IF;

    IF nTotalCalculoAnterior > nTotalCalculoNovo
    THEN
      lCreditoRecalculo := TRUE;
    END IF;

  END IF;

  PERFORM fc_debug(' <iptu_gerafinanceiro> lDebitoRecalculo '||lDebitoRecalculo, lRaise, FALSE, FALSE);
  PERFORM fc_debug(' <iptu_gerafinanceiro> lCreditoRecalculo '||lCreditoRecalculo, lRaise, FALSE, FALSE);

  -- Verifica se mudou alguma das receitas comparado com o cálculo anterior
  PERFORM *
  FROM tmpipturecalculo
  WHERE NOT exists(SELECT * FROM iptucalv WHERE j21_matric = matricula
                                            AND j21_anousu = anousu
                                            AND j21_receit = receita);

  IF found
  THEN

    FOR rIptuCalcConfRec IN

    SELECT j23_recorg, j23_recdst
    FROM iptucalcconfrec
    WHERE iptucalcconfrec.j23_matric = iMatricula
      AND iptucalcconfrec.j23_anousu = iAnousu

    LOOP

      UPDATE tmpipturecalculo
      SET receita = rIptuCalcConfRec.j23_recdst
      WHERE receita = rIptuCalcConfRec.j23_recorg
        AND matricula = iMatricula
        AND anousu = iAnousu;

    END LOOP;

    UPDATE tmpipturecalculo
    SET receita = (SELECT j21_receit
                   FROM iptucalv
                   WHERE j21_matric = iMatricula
                     AND j21_anousu = iAnousu
                     AND NOT exists(SELECT * FROM tmpipturecalculo WHERE matricula = j21_matric
                                                                     AND anousu = j21_anousu
                                                                     AND j21_receit = receita)
                   LIMIT 1)
    WHERE NOT exists(SELECT * FROM iptucalv WHERE j21_matric = matricula
                                              AND j21_anousu = anousu
                                              AND j21_receit = receita);

  END IF;

  IF lDebitoRecalculo IS TRUE
  THEN

    FOR rGeraComplementar IN

    SELECT j21_receit, sum(j21_valor) AS j21_valor
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu
    GROUP BY j21_receit

    LOOP

      SELECT rGeraComplementar.j21_valor - sum(valor - valor_isencao)
          INTO nTotalComplementarReceita
      FROM tmpipturecalculo
      WHERE matricula = iMatricula
        AND anousu = iAnousu
        AND receita = rGeraComplementar.j21_receit
        AND valor > 0;

      --       Deduz o valor do desconto da L.I.C. do calculo anterior caso haja
      nTotalComplementarReceita := nTotalComplementarReceita -
                                   fc_iptu_deducao_lic(iMatricula, iAnousu, rGeraComplementar.j21_receit);

      IF nTotalComplementarReceita > 0
      THEN

        SELECT fc_iptu_complementar(iMatricula, iAnousu, nTotalComplementarReceita, rGeraComplementar.j21_receit,
                                    lRaise)
            INTO iNumpreComplementar;

        IF (SELECT count(DISTINCT k00_dtpaga) FROM arrepaga WHERE k00_numpre = iNumpre) = 1
        THEN

          SELECT k00_percdes
              INTO nPercentualUnica
          FROM recibounica
          WHERE k00_numpre = iNumpre
            AND k00_dtvenc >= (SELECT coalesce(disbanco.dtpago, arrepaga.k00_dtpaga)
                               FROM arrepaga
                                      LEFT JOIN arreidret ON arreidret.k00_numpre = arrepaga.k00_numpre
                                                               AND arreidret.k00_numpar = arrepaga.k00_numpar
                                      LEFT JOIN disbanco ON disbanco.idret = arreidret.idret
                               WHERE arrepaga.k00_numpre = iNumpre
                               LIMIT 1)
          ORDER BY k00_dtvenc
          LIMIT 1;

          IF found AND nPercentualUnica > 0
          THEN

            nTotalComplementarReceita := nTotalComplementarReceita * (nPercentualUnica / 100);

            PERFORM fc_iptu_compensacao_automatica(nTotalComplementarReceita, rGeraComplementar.j21_receit,
                                                   iNumpreComplementar, iMatricula, iAnousu, lRaise);

          END IF;

        END IF;

      END IF;

    END LOOP;

    -- Verifica se alguma das receitas deve gerar crédito
    FOR rGeraCredito IN

    SELECT j21_receit, sum(j21_valor) AS j21_valor
    FROM iptucalv
    WHERE j21_matric = iMatricula
      AND j21_anousu = iAnousu
    GROUP BY j21_receit

    LOOP

      SELECT rGeraCredito.j21_valor - sum(valor - valor_isencao)
          INTO nTotalCreditoReceita
      FROM tmpipturecalculo
      WHERE matricula = iMatricula
        AND anousu = iAnousu
        AND receita = rGeraCredito.j21_receit;

      IF nTotalCreditoReceita < 0
      THEN

        lCreditoRecalculo := TRUE;
        nTotalCalculoNovo := nTotalCalculoAnterior - abs(nTotalCreditoReceita);
      END IF;

    END LOOP;

  END IF;

  IF lCreditoRecalculo IS TRUE
  THEN

    nTotalCalculoCredito := nTotalCalculoAnterior - nTotalCalculoNovo;

    IF (SELECT count(DISTINCT k00_dtpaga) FROM arrepaga WHERE k00_numpre = iNumpre) = 1
    THEN

      SELECT k00_percdes
          INTO nPercentualUnica
      FROM recibounica
      WHERE k00_numpre = iNumpre
        AND k00_dtvenc >= (SELECT coalesce(disbanco.dtpago, arrepaga.k00_dtpaga)
                           FROM arrepaga
                                  LEFT JOIN arreidret ON arreidret.k00_numpre = arrepaga.k00_numpre
                                                           AND arreidret.k00_numpar = arrepaga.k00_numpar
                                  LEFT JOIN disbanco ON disbanco.idret = arreidret.idret
                           WHERE arrepaga.k00_numpre = iNumpre
                           LIMIT 1)
      ORDER BY k00_dtvenc
      LIMIT 1;

      IF found AND nPercentualUnica > 0
      THEN
        nTotalCalculoCredito := nTotalCalculoCredito * (1 - (nPercentualUnica / 100));
      END IF;

    END IF;

    SELECT fc_iptu_geracreditorecalculo(iMatricula, iAnousu, nTotalCalculoCredito, lRaise)
        INTO lCalculoQuitado;

    IF lCalculoQuitado IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Erro ao gerar crédito no recálculo de IPTU Quitado.', lRaise, FALSE,
                       FALSE);
      RETURN FALSE;
    END IF;
  END IF;

  /**
   * Caso seja recalculo mas o IPTU não esteja quitado porém o novo valor recalculado seja menor do que a soma do valor
   * pago do IPTU até o momento. Neste caso gera um crédito com a diferença do valor
   */
  IF nValorTotalCalculo < 0 AND lRecalculoQuitado IS FALSE
  THEN

    SELECT fc_iptu_geracreditorecalculo(iMatricula, iAnousu, abs(nValorTotalCalculo), lRaise)
        INTO lCalculoQuitado;

    IF lCalculoQuitado IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Erro ao gerar crédito no recálculo de IPTU Quitado.', lRaise, FALSE,
                       FALSE);
      RETURN FALSE;
    END IF;

  /**
   * Se não tiver que gerar débito de diversos ou crédito por ser um recalculo de IPTU quitado,
   * Realizamos a geração de débito normal, usando as receitas de IPTU e Taxas(caso tenha) e suas
   * devidas parcelas
   */
  ELSIF nValorTotalCalculo > 0 AND lCreditoRecalculo IS FALSE AND lDebitoRecalculo IS FALSE
    THEN

      PERFORM fc_debug(' <iptu_gerafinanceiro> Inicia rateio de valor por parcela', lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> Parcelas: '||iParcelas||' nValorTotalCalculo: '||nValorTotalCalculo,
                       lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> Verifica se ('||nValorTotalCalculo||' / '||iParcelas||') eh menor que o valor de parcela minimo '||nValorParcelaMinima,
                       lRaise, FALSE, FALSE);
      IF (nValorTotalCalculo / iParcelas) < nValorParcelaMinima
      THEN

        IF floor((nValorTotalCalculo / nValorParcelaMinima) :: NUMERIC) :: INTEGER = 0
        THEN
          iParcelas := 1;
        ELSE
          iParcelas := floor((nValorTotalCalculo / nValorParcelaMinima) :: NUMERIC) :: INTEGER;
        END IF;

        lUtilizandoMinima := TRUE;
        PERFORM fc_debug(' <iptu_gerafinanceiro> Entrou em parcela minima... ', lRaise, FALSE, FALSE);
        PERFORM fc_debug(' <iptu_gerafinanceiro> Quantidade de Parcelas: '||iParcelas, lRaise, FALSE, FALSE);
      END IF;

      PERFORM fc_debug('', lRaise, FALSE, FALSE);
      PERFORM fc_debug(' <iptu_gerafinanceiro> NUMPRE DO CALCULO: '||iNumpre, lRaise, FALSE, FALSE);
      PERFORM fc_debug('', lRaise, FALSE, FALSE);

      PERFORM fc_debug(' <iptu_gerafinanceiro> Percorrendo valores a serem gerados agrupado por receita '||iNumpre,
                       lRaise, FALSE, FALSE);


      /**
       * Agrupa por receita
       */
      FOR rValoresPorReceita IN SELECT receita,
                                       (SELECT count(DISTINCT receita) FROM tmprecval) AS qtdreceitas,
                                       sum(valor)                                      AS valor
                                FROM tmprecval
                                GROUP BY receita
                                ORDER BY receita
      LOOP

        nValorIncrementalReceitaParcela := 0;
        iParcelasProcessadas := 1;

        PERFORM fc_debug(
                  ' <iptu_gerafinanceiro> iParcelasProcessadas: '||iParcelasProcessadas||' iParcelas: '||iParcelas,
                  lRaise, FALSE, FALSE);

        /**
         * Percorre o record de vencimentos rateando o valor que fora agrupado por receita
         */
        FOR rVencimentos IN EXECUTE tSqlVencimentos
        LOOP

          IF lUtilizandoMinima IS FALSE
          THEN
            nPercentualParcela := cast(rVencimentos.q82_perc AS NUMERIC(15, 2));
          ELSE
            nPercentualParcela := 100 :: NUMERIC / iParcelas;
          END IF;

          PERFORM fc_debug(' <iptu_gerafinanceiro> Percentual da parcela ' || nPercentualParcela, lRaise, FALSE, FALSE);

          IF iParcelas < iParcelasProcessadas AND lProcessaVencimentoForcado IS FALSE
          THEN

            PERFORM fc_debug(' <iptu_gerafinanceiro> PARCELA '||rVencimentos.q82_parc||' NAO SERA CALCULADA', lRaise,
                             FALSE, FALSE);
            PERFORM fc_debug('', lRaise, FALSE, FALSE);
            CONTINUE;
          END IF;

          IF iParcelaini = 0
          THEN

            PERFORM fc_debug(' <iptu_gerafinanceiro> lProcessaParcela = true | iParcelaini = 0', lRaise, FALSE, FALSE);
            lProcessaParcela = TRUE;
          ELSE

            IF rVencimentos.q82_parc >= iParcelaini AND rVencimentos.q82_parc <= iParcelafim
            THEN
              lProcessaParcela = TRUE;
            ELSE
              lProcessaParcela = FALSE;
            END IF;

          END IF;

          IF lProcessaVencimentoForcado
          THEN
            lProcessaParcela = TRUE;
          END IF;

          PERFORM fc_debug(
                    ' <iptu_gerafinanceiro> Processando parcela = '||(CASE WHEN lProcessaParcela IS TRUE THEN 'Sim' ELSE 'Nao' END),
                    lRaise, FALSE, FALSE);

          IF lProcessaParcela IS TRUE
          THEN

            PERFORM *
            FROM fc_statusdebitos(iNumpre, rVencimentos.q82_parc)
            WHERE rtstatus = 'PAGO'
               OR rtstatus = 'CANCELADO'
            LIMIT 1;

            IF found
            THEN

              PERFORM fc_debug(' <iptu_gerafinanceiro> Ignorando parcela '||rVencimentos.q82_parc||' por estar paga ou cancelada',
                               lRaise, FALSE, FALSE);
              PERFORM fc_debug('', lRaise, FALSE, FALSE);
              CONTINUE;
            END IF;

            IF rValoresPorReceita.valor > 0
            THEN

              IF iParcelas = iParcelasProcessadas AND iNumeroParcelasPagasCanceladas = 0
              THEN
                nValorParcela := rValoresPorReceita.valor - nValorIncrementalReceitaParcela;
              ELSE

                nValorParcela := trunc(rValoresPorReceita.valor * (nPercentualParcela / 100 :: NUMERIC) :: NUMERIC, 2);
                nValorIncrementalReceitaParcela := nValorIncrementalReceitaParcela + nValorParcela;
              END IF;

              lExisteFinanceiroGerado := TRUE;
              iDigito := fc_digito(iNumpre, rVencimentos.q82_parc, iParcelas);

              PERFORM fc_debug('', lRaise, FALSE, FALSE);
              PERFORM fc_debug(
                        ' <iptu_gerafinanceiro> Parcela: '||rVencimentos.q82_parc||' Receita: '||rValoresPorReceita.receita||' Valor: '||nValorParcela,
                        lRaise, FALSE, FALSE);

              IF lDemonstrativo IS FALSE
              THEN

                iParcelasProcessadas = (iParcelasProcessadas + 1);

                IF round(nValorParcela, 2) = 0
                THEN

                  PERFORM fc_debug(' <iptu_gerafinanceiro> Valor de parcela zerado, continue...', lRaise);
                  CONTINUE;
                END IF;

                PERFORM fc_debug(' <iptu_gerafinanceiro> GERANDO ARRECAD ', lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> ', lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Numpre .......: '||iNumpre, lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Numpar .......: '||rVencimentos.q82_parc, lRaise, FALSE,
                                 FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Receita ......: '||rValoresPorReceita.receita, lRaise, FALSE,
                                 FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Valor ........: '||nValorParcela, lRaise, FALSE, FALSE);
                PERFORM fc_debug(' <iptu_gerafinanceiro> Vencimento ...: '||rVencimentos.q82_parc, lRaise, FALSE,
                                 FALSE);

                DELETE
                FROM arrecad
                WHERE k00_numpre = iNumpre
                  AND k00_numpar = rVencimentos.q82_parc
                  AND k00_receit = rValoresPorReceita.receita;

                INSERT INTO arrecad (k00_numcgm,
                                     k00_dtoper,
                                     k00_receit,
                                     k00_hist,
                                     k00_valor,
                                     k00_dtvenc,
                                     k00_numpre,
                                     k00_numpar,
                                     k00_numtot,
                                     k00_numdig,
                                     k00_tipo)
                VALUES (iCgm,
                        dDataOperacao,
                        rValoresPorReceita.receita,
                        rVencimentos.q82_hist,
                        nValorParcela,
                        rVencimentos.q82_venc,
                        iNumpre,
                        rVencimentos.q82_parc,
                        iParcelas,
                        iDigito,
                        rVencimentos.q92_tipo);
              END IF;

            END IF;

          END IF;

          PERFORM fc_debug(' <iptu_gerafinanceiro> nValorParcela.: '||nValorParcela, lRaise, FALSE, FALSE);
          PERFORM fc_debug(
                    ' <iptu_gerafinanceiro> nValorIncrementalReceitaParcela: ' || nValorIncrementalReceitaParcela,
                    lRaise);

        END LOOP;

        /*
         * Lancando a diferenca na ultima parcela
         */
        SELECT max(k00_numpar)
            INTO iUltimaParcelaGerada FROM arrecad WHERE k00_numpre = iNumpre;

        SELECT sum(k00_valor)
            INTO nTotalGeradoReceita
        FROM arrecad
        WHERE k00_numpre = iNumpre
          AND k00_receit = rValoresPorReceita.receita;

        UPDATE arrecad
        SET k00_valor = (k00_valor + (rValoresPorReceita.valor - nTotalGeradoReceita))
        WHERE k00_numpre = iNumpre
          AND k00_numpar = iUltimaParcelaGerada
          AND k00_receit = rValoresPorReceita.receita;

      END LOOP;

      IF lRaise IS TRUE
      THEN

        PERFORM fc_debug('', lRaise, FALSE, FALSE);
        PERFORM fc_debug(' <iptu_gerafinanceiro> Verificando e gerando arrematric, iptunump e iptucalc', lRaise, FALSE,
                         FALSE);
      END IF;

      IF lExisteFinanceiroGerado = TRUE
      THEN

        IF lDemonstrativo IS FALSE
        THEN

          SELECT k00_numpre
              INTO iNumpreArrematric FROM arrematric WHERE k00_numpre = iNumpre
                                                       AND k00_matric = iMatricula;

          IF iNumpreArrematric IS NULL
          THEN
            INSERT INTO arrematric (k00_numpre, k00_matric) VALUES (iNumpre, iMatricula);
          END IF;

          FOR rRecibosGerados IN SELECT DISTINCT recibopaga.k00_numnov, recibopaga.k00_dtoper, recibopaga.k00_dtpaga
                                 FROM arrecad
                                        INNER JOIN recibopaga ON recibopaga.k00_numpre = arrecad.k00_numpre
                                                                   AND recibopaga.k00_numpar = arrecad.k00_numpar
                                 WHERE arrecad.k00_numpre = iNumpre
          LOOP

            DELETE FROM recibopaga WHERE k00_numnov = rRecibosGerados.k00_numnov;
            PERFORM fc_recibo(rRecibosGerados.k00_numnov, rRecibosGerados.k00_dtoper, rRecibosGerados.k00_dtpaga,
                              extract(YEAR FROM rRecibosGerados.k00_dtpaga) :: INTEGER);
          END LOOP;

        END IF;

        IF lExisteNumpre = FALSE AND lDemonstrativo IS FALSE
        THEN
          INSERT INTO iptunump (j20_anousu, j20_matric, j20_numpre) VALUES (iAnousu, iMatricula, iNumpre);
        END IF;

      END IF;

  END IF;

  IF lDemonstrativo IS FALSE
  THEN
    UPDATE iptucalc SET j23_manual = tManual WHERE j23_matric = iMatricula
                                               AND j23_anousu = iAnousu;
  END IF;

  IF lRaise IS TRUE
  THEN
    PERFORM fc_debug(' <iptu_gerafinanceiro> Fim do processamento da funcao iptu_gerafinanceiro', lRaise, FALSE, TRUE);
  END IF;

  RETURN TRUE;

END;
$$;
STRING;
        $this->execute($sql);
    }
}
