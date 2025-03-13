<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Sagres\Repository\V2022;

use db_utils;
use Exception;

/**
 * Class SagresRepository
 * @package ECidade\Financeiro\Contabilidade\Sagres\Repository\V2022
 */
class SagresRepository
{
    // 4.1. UnidadeOrcamentaria
    public static function getUnidadeOrcamentaria($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT DISTINCT
                        tribinst AS codunidadegestora,
                        lpad(o41_orgao::VARCHAR, 2, '0') || lpad(o41_unidade::VARCHAR, 3, '0') AS codigo,
                        o41_descr AS descricao,
                        z01_nome AS nomesecretario,
                        z01_cgccpf AS cpfsecretario,
                        c140_tipoatojuridico AS atoadministrativo,
                        db21_codtipo AS tiponaturezajuridica,
                        '000000' AS reservado
                 FROM orcamento.orcunidade
                      INNER JOIN orcamento.orcdotacao
                              ON (o58_anousu, o58_orgao, o58_unidade) = (o41_anousu, o41_orgao, o41_unidade)
                      INNER JOIN contabilidade.sagresresponsavelunidadeorcamentaria
                              ON (c140_anousu, c140_orgao, c140_unidade) = (o41_anousu, o41_orgao, o41_unidade)
                      INNER JOIN protocolo.cgm ON z01_numcgm = c140_cgm
                      INNER JOIN configuracoes.db_config ON o41_instit = codigo
                      INNER JOIN configuracoes.db_tipoinstit ON db_config.db21_tipoinstit = db_tipoinstit.db21_codtipo
                 WHERE orcunidade.o41_anousu = {$params->data_ano}
                   AND orcunidade.o41_instit = {$iInstit}
                   AND sagresresponsavelunidadeorcamentaria.c140_ativo = 't'";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as Unidades Orçamentárias.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo' => str_pad($oResultado->codigo, 5, 0, STR_PAD_LEFT),
                'descricao' => str_pad(utf8_decode($oResultado->descricao), 50, ' ', STR_PAD_RIGHT),
                'nomeSecretario' => str_pad(utf8_decode($oResultado->nomesecretario), 60, ' ', STR_PAD_RIGHT),
                'cpfSecretario' => str_pad($oResultado->cpfsecretario, 11, 0),
                'atoAdministrativo' => str_pad($oResultado->atoadministrativo, 1, 0, STR_PAD_LEFT),
                'tipoNaturezaJuridica' => str_pad($oResultado->tiponaturezajuridica, 1, 0, STR_PAD_LEFT),
                'reservado' => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.2. Programas
    public static function getProgramas($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT DISTINCT
                        lpad(tribinst::varchar,6,0) AS codunidadegestora,
                        lpad(o54_programa::varchar, 4, '0') AS codigo,
                        rpad(o54_descr, 70, ' ') AS descricao,
                        rpad(o54_finali, 150, ' ') AS descobjetivo,
                        CASE
                            WHEN o143_sequencial IS NULL THEN '09'
                            ELSE lpad(o143_sequencial::varchar, 2, 0)
                        END AS tipoobjetivomilenio,
                        '000000' AS reservado
                 FROM orcamento.orcprograma
                      JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = orcprograma.o54_anousu
                                               AND orcdotacao.o58_programa = orcprograma.o54_programa
                      JOIN configuracoes.db_config ON orcdotacao.o58_instit = codigo
                      LEFT JOIN orcamento.orcprogramavinculoobjetivo
                             ON (o144_orcprogramaanousu,o144_orcprogramaprograma) = (o54_anousu, o54_programa)
                      LEFT JOIN orcamento.orcobjetivo ON o144_orcobjetivo = o143_sequencial
                 WHERE orcdotacao.o58_anousu = {$params->data_ano}
                   AND orcdotacao.o58_instit = {$iInstit}";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar os Programas.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'   => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo'              => str_pad($oResultado->codigo, 4, 0, STR_PAD_LEFT),
                'descricao'           => utf8_decode(str_pad($oResultado->descricao, 70, ' ', STR_PAD_RIGHT)),
                'descObjetivo'        => utf8_decode(str_pad($oResultado->descobjetivo, 150, ' ', STR_PAD_RIGHT)),
                'tipoObjetivoMilenio' => str_pad($oResultado->tipoobjetivomilenio, 2, 0, STR_PAD_LEFT),
                'reservado'           => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.3. Acao
    public static function getAcao($params)
    {
        $iInstit = db_getsession('DB_instit');

        $sSql = "SELECT DISTINCT
                        lpad(tribinst::varchar,6,0) AS codunidadegestora,
                        lpad(o55_projativ::varchar, 4, '0') AS codigo,
                        rpad(o55_descr, 70, ' ') AS descricao,
                        o55_tipo AS tipo,
                        rpad(o55_finali, 150, ' ')  AS descmeta,
                        RPAD('', 50, ' ') AS unidademedida,
                        '000000' AS reservado
                  FROM orcamento.orcprojativ
                       JOIN orcamento.orcdotacao ON o58_anousu = o55_anousu
                        AND o58_projativ = o55_projativ
                       JOIN configuracoes.db_config ON o58_instit = codigo
                 WHERE o58_anousu = {$params->data_ano}
                   AND o58_instit = {$iInstit}";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as Ações.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo'            => str_pad($oResultado->codigo, 4, 0, STR_PAD_LEFT),
                'descricao'         => utf8_encode(str_pad($oResultado->descricao, 70, ' ', STR_PAD_RIGHT)),
                'tipo'              => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'descMeta'          => utf8_encode(str_pad($oResultado->descmeta, 150, ' ', STR_PAD_RIGHT)),
                'unidadeMedida'     => utf8_encode(str_pad($oResultado->unidademedida, 50, ' ', STR_PAD_RIGHT)),
                'reservado'         => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.4. Dotacao
    public static function getDotacao($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT lpad(tribinst::varchar,6,0) AS codunidadegestora,
                        dotacao.o58_anousu AS competencia,
                        lpad(dotacao.o58_orgao::TEXT, 2, '0') || lpad(dotacao.o58_unidade::TEXT, 3, '0')
                            AS codunidadeorcamentaria,
                        dotacao.o58_funcao AS codfuncao,
                        dotacao.o58_subfuncao AS codsubfuncao,
                        dotacao.o58_programa AS codprograma,
                        dotacao.o58_projativ AS codacao,
                        000000 AS reservado1,
                        SUBSTRING(elemento.o56_elemento, 2, 1) AS codcategoriaeconomica,
                        SUBSTRING(elemento.o56_elemento, 3, 1) AS codnaturezadespesa,
                        SUBSTRING(elemento.o56_elemento, 4, 2) AS codmodalidadedespesa,
                        SUBSTRING(elemento.o56_elemento, 6, 2) AS codelementodespesa,
                        '1' AS exerciciofonterecurso,
                        fonte.o15_recurso AS codfonterecurso,
                        round(dotacao.o58_valor, 2) AS valor,
                        000000 AS reservado2
                 FROM orcamento.orcdotacao dotacao
                      JOIN orcamento.orcelemento elemento ON dotacao.o58_anousu = elemento.o56_anousu
                                                         AND dotacao.o58_codele = elemento.o56_codele
                      JOIN orcamento.orctiporec fonte ON dotacao.o58_codigo = fonte.o15_codigo
                      JOIN configuracoes.db_config ON o58_instit = codigo
                 WHERE dotacao.o58_anousu = {$params->data_ano}
                   AND o58_instit = {$iInstit}";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as Dotações.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'codFuncao'              => str_pad($oResultado->codfuncao, 2, 0, STR_PAD_LEFT),
                'codSubfuncao'           => str_pad($oResultado->codsubfuncao, 3, 0, STR_PAD_LEFT),
                'codPrograma'            => str_pad($oResultado->codprograma, 4, 0, STR_PAD_LEFT),
                'codAcao'                => str_pad($oResultado->codacao, 4, 0, STR_PAD_LEFT),
                'reservado1'             => str_pad($oResultado->reservado1, 6, 0, STR_PAD_LEFT),
                'codCategoriaEconomica'  => str_pad($oResultado->codcategoriaeconomica, 1, 0, STR_PAD_LEFT),
                'codNaturezaDespesa'     => str_pad($oResultado->codnaturezadespesa, 1, 0, STR_PAD_LEFT),
                'codModalidadeDespesa'   => str_pad($oResultado->codmodalidadedespesa, 2, 0, STR_PAD_LEFT),
                'codElementoDespesa'     => str_pad($oResultado->codelementodespesa, 2, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => $oResultado->exerciciofonterecurso,
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado2'             => str_pad($oResultado->reservado2, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.5. AtualizacaoOrcamentaria
    public static function getAtualizacaoOrcamentaria($params)
    {
        if ($params->periodo == 'diario') {
            $sWhere = " o49_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
            $sWhere .= "and c53_tipo = 50";
        } else {
            $sWhere = " to_char(o49_data, 'yyyy/mm') = '{$params->folder}' ";
            $sWhere .= "and c53_tipo <> 50";
        }

        $sSql = "select tribinst as codunidadegestora,
                        o39_anousu as competencia,
                        lpad(orcdotacao.o58_orgao::varchar, 2, '0') || lpad(orcdotacao.o58_unidade::varchar, 3, '0')
                            as codunidadeorcamentaria,
                        o58_funcao as codfuncao,
                        o58_subfuncao as codsubfuncao,
                        o58_programa as codprograma,
                        o58_projativ as codacao,
                        '000000' as reservado1,
                        o39_numero||o39_anousu::varchar as numdecretooficio,
                        1 as tipodecretooficio,
                        case when o48_tiposup = 1002 then 1
                             when o48_tiposup = 1003 then 2
                             when o48_tiposup = 1004 then 3
                             when o48_tiposup = 1001 then 4
                             when o48_tiposup = 1007 then 6
                             when o48_tiposup = 1008 then 7
                             when o48_tiposup = 1006 then 8
                             when o48_tiposup = 1009 then 9
                             when o48_tiposup = 1011 then 10
                             when o48_tiposup in (1014,1015,1016) then 13
                             else 0 end as tipoalteracao,
                        substr(o56_elemento,2,1) as codCategoriaEconomica,
                        substr(o56_elemento,3,1) as codNaturezaDespesa,
                        substr(o56_elemento,4,2) as codModalidadeDespesa,
                        substr(o56_elemento,6,2) as codElementoDespesa,
                        1 as exerciciofonterecurso,
                        substr(o15_recurso, 2, 3) as codfonterecurso,
                        lpad(round(abs(o47_valor), 2)::varchar, 16, 0) AS valor,
                        '000000' as reservado2
                 from orcsuplemlan
                      join orcsuplem on o49_codsup = o46_codsup
                      join orcsuplemval on o47_codsup = o46_codsup
                      join orcsuplemtipo on o46_tiposup = o48_tiposup
                      join conhistdoc on c53_coddoc = o48_coddocsup
                      join orcprojeto on o46_codlei = o39_codproj
                      join orclei on o39_codlei = o45_codlei
                      join orcdotacao on o47_anousu = o58_anousu and o47_coddot = o58_coddot
                      join orcelemento on o58_anousu = o56_anousu and o58_codele = o56_codele
                      join orctiporec on o58_codigo = o15_codigo
                      join db_config on o58_instit = codigo
                 where {$sWhere} ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as Atualizações Orçamentárias.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'codFuncao'              => str_pad($oResultado->codfuncao, 2, 0, STR_PAD_LEFT),
                'codSubfuncao'           => str_pad($oResultado->codsubfuncao, 3, 0, STR_PAD_LEFT),
                'codPrograma'            => str_pad($oResultado->codprograma, 4, 0, STR_PAD_LEFT),
                'codAcao'                => str_pad($oResultado->codacao, 4, 0, STR_PAD_LEFT),
                'reservado1'             => str_pad($oResultado->reservado1, 6, 0, STR_PAD_LEFT),
                'numDecretoOficio'       => str_pad($oResultado->numdecretooficio, 8, 0, STR_PAD_LEFT),
                'tipoDecretoOficio'      => str_pad($oResultado->tipodecretooficio, 1, 0, STR_PAD_LEFT),
                'tipoAlteracao'          => str_pad($oResultado->tipoalteracao, 2, 0, STR_PAD_LEFT),
                'codCategoriaEconomica'  => str_pad($oResultado->codcategoriaeconomica, 1, 0, STR_PAD_LEFT),
                'codNaturezaDespesa'     => str_pad($oResultado->codnaturezadespesa, 1, 0, STR_PAD_LEFT),
                'codModalidadeDespesa'   => str_pad($oResultado->codmodalidadedespesa, 2, 0, STR_PAD_LEFT),
                'codElementoDespesa'     => str_pad($oResultado->codelementodespesa, 2, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado2'             => str_pad($oResultado->reservado2, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.6. DecretoseOficios
    public static function getDecretoseOficios($params)
    {
        if ($params->periodo == 'diario') {
            $sWhere = " o39_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
            $sWhere .= "and c53_tipo = 50";
        } else {
            $sWhere = " to_char(o39_data, 'yyyy/mm') = '{$params->folder}' ";
            $sWhere .= "and c53_tipo <> 50";
        }

        $sSql = "select distinct tribinst as codunidadegestora,
                                 o47_anousu as competencia,
                                 o39_numero||o39_anousu::varchar as numero,
                                 regexp_replace(o45_numlei, '[^0-9]', '', 'g') as numlei,
                                 to_char(o39_data, 'DDMMYYYY') as data,
                                 1 as tipo,
                                 '000000'as reservado
                 from orcprojeto
                     join orclei on o45_codlei = o39_codlei
                     join orcsuplem on o46_codlei = o39_codproj
                     join orcsuplemval on o47_codsup = o46_codsup
                     join orcsuplemtipo on o48_tiposup = o46_tiposup
                     join conhistdoc on c53_coddoc = o48_coddocsup
                     join orcdotacao on o58_anousu = o47_anousu and o58_coddot = o47_coddot
                     join orcelemento on o58_anousu = o56_anousu and o58_codele = o56_codele
                     join orctiporec on o58_codigo = o15_codigo
                     join db_config on o46_instit = codigo
                 where {$sWhere} ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar os Decretos e Ofícios.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'       => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'numero'            => str_pad($oResultado->numero, 8, 0, STR_PAD_LEFT),
                'numLei'            => str_pad(rmSpecial($oResultado->numlei), 8, 0, STR_PAD_LEFT),
                'data'              => str_pad(rmSpecial($oResultado->data), 8, 0, STR_PAD_LEFT),
                'tipo'              => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'reservado'         => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.7. ReceitaPrevista
    public static function getReceitaPrevista($params)
    {
        $iInstit = db_getsession('DB_instit');

        $sSql = "SELECT lpad(o70_instit::varchar, 6, 0) AS codunidadegestora,
                        o70_anousu AS competencia,
                        CASE
                            WHEN substr(o57_fonte, 1, 1) = '4' THEN substr(o57_fonte, 2, 8)
                            ELSE substr(o57_fonte, 1, 8)
                        END AS codreceitaorcamentaria,
                        1 AS exerciciofonterecurso,
                        substr(o15_recurso, 2, 3) AS codfonterecurso,
                        CASE
                            WHEN substr(o57_fonte, 1, 3) = '917' THEN 3
                            WHEN substr(o57_fonte, 1, 5) = '91321' THEN 4
                            WHEN substr(o57_fonte, 1, 1) = '9'
                                 AND substr(o57_fonte, 1, 3) <> '917'
                                 AND substr(o57_fonte, 1, 5) <> '91321' THEN 1
                            ELSE 1
                        END AS tiporeceita,
                        lpad(round(o70_valor, 2)::varchar, 16, 0) AS valor,
                        0 AS reservado
                 FROM orcamento.orcreceita
                      JOIN orcamento.orcfontes ON o70_codfon = o57_codfon
                                              AND o70_anousu = o57_anousu
                      JOIN orcamento.orctiporec ON o15_codigo = o70_codigo
                 WHERE o70_anousu = {$params->ano}
                   AND o70_instit = {$iInstit}";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as Receitas Previstas.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'codReceitaOrcamentaria' => str_pad($oResultado->codreceitaorcamentaria, 8, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'tipoReceita'            => str_pad($oResultado->tiporeceita, 1, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.8. Empenhos
    public static function getEmpenhos($params)
    {
        $iInstit = db_getsession('DB_instit');
        $e171_dadosdefault = '{"geo_obra":"00000000"}';
        $sSql = "SELECT tribinst   AS codunidadegestora,
                  e60_anousu AS anoemissao,
                  lpad(Lpad(o58_orgao::         varchar,2,0)||
                  lpad(o58_unidade::varchar,3,0),5,0) AS codunidadeorcamentaria,
                  lpad(o58_funcao::   varchar,2,0)    AS codfuncao,
                  lpad(o58_subfuncao::varchar,3,0)    AS codsubfuncao,
                  lpad(o58_programa:: varchar,4,0)    AS codprograma,
                  lpad(o58_projativ:: varchar,4,0)    AS codacao,
                  lpad('0',6, '0')                    AS reservado,
                  substr(eledes.o56_elemento,2,1)     AS codcategoriaeconomica,
                  substr(eledes.o56_elemento,3,1)     AS codnaturezadespesa,
                  substr(eledes.o56_elemento,4,2)     AS codmodalidadedespesa,
                  substr(eledes.o56_elemento,6,2)     AS codelementodespesa,
                  substr(eledes.o56_elemento,8,2)     AS codsubelementodespesa,
                  lpad(e60_codcom::varchar,2,0)       AS modalidadelicitacao,
                  split_part(e60_numerol, '/',1)||
                  split_part(e60_numerol, '/',2)      AS numlicitacao,
                  lpad(e60_codemp::varchar,7,0)                               AS numempenho,
                  1                                                           AS tipo,
                  to_char(e60_emiss, 'DDMMYYYY')                              AS data,
                  round(e60_vlremp,2)                                         AS valor,
                  rpad(substr(regexp_replace(e60_resumo, e'[\n\r]+', ' ', 'g' ),1,255),255) AS historico,
                  rpad(substr(regexp_replace(e60_resumo, e'[\n\r]+', ' ', 'g' ),256,255),255)
                           AS complementacaohistorico,
                  cgmemp.z01_cgccpf                                           AS cpfcnpjfornecedor,
                  e40_codhist                                                 AS tipometa,
                  (select coalesce(e171_dados, '{$e171_dadosdefault}')
                    from empempenhooutrosdados
                   where e171_numemp = e60_numemp) AS numobra,
                  substr(o15_recurso,1,1)                                     AS exerciciofonterecurso,
                  substr(o15_recurso,2,3)                                     AS codfonterecurso,
                  cgmord.z01_cgccpf                                           AS cpfordenador,

		  (select case when o206_complementorecurso = 1030
                               then '0000'
                               else lpad(o206_complementorecurso,4,0)
                          end
                     from origemcomplementorecurso
                    where o206_origem = 1
                      and o206_numero = e60_numemp) as co

           FROM empempenho
                JOIN empelemento ON e60_numemp = e64_numemp
                JOIN orcdotacao ON e60_anousu = o58_anousu
                               AND e60_coddot = o58_coddot
                JOIN orcelemento eledot ON o58_anousu = eledot.o56_anousu
                                       AND o58_codele = eledot.o56_codele
                JOIN orcelemento eledes ON e64_codele = eledes.o56_codele
                                       AND eledes.o56_anousu = {$params->data_ano}
                JOIN db_config ON e60_instit = codigo
                JOIN cgm cgmemp ON e60_numcgm = cgmemp.z01_numcgm
                JOIN orcunidade ON o58_anousu = o41_anousu
                               AND o58_orgao = o41_orgao
                               AND o58_unidade = o41_unidade
                JOIN orcfuncao ON o58_funcao = o52_funcao
                JOIN orcsubfuncao ON o58_subfuncao = o53_subfuncao
                JOIN orcprograma ON o58_anousu = o54_anousu
                                AND o58_programa = o54_programa
                JOIN orcprojativ ON o58_anousu = o55_anousu
                                AND o58_projativ = o55_projativ
                JOIN orctiporec ON o58_codigo = o15_codigo
                JOIN sagresordenadordespesa ON db_config.codigo = c139_instit
                JOIN cgm cgmord ON cgmord.z01_numcgm = c139_cgm
                LEFT JOIN empemphist ON e60_numemp = e63_numemp
                LEFT JOIN emphist ON e63_codhist = e40_codhist
           WHERE e60_emiss = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
             AND e60_instit = {$iInstit}
             AND c139_ativo = 't'";
        $result = db_query($sSql);
        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Empenhos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            $numObra = json_decode($oResultado->numobra);
            if (!isset($numObra->geo_obra)) {
                $numObra->geo_obra = 0;
            }
            return array(
                'codUnidadeGestora'       => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissao'              => str_pad($oResultado->anoemissao, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'  => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'codFuncao'               => str_pad($oResultado->codfuncao, 2, 0, STR_PAD_LEFT),
                'codSubfuncao'            => str_pad($oResultado->codsubfuncao, 3, 0, STR_PAD_LEFT),
                'codPrograma'             => str_pad($oResultado->codprograma, 4, 0, STR_PAD_LEFT),
                'codAcao'                 => str_pad($oResultado->codacao, 4, 0, STR_PAD_LEFT),
                'reservado'               => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
                'codCategoriaEconomica'   => str_pad($oResultado->codcategoriaeconomica, 1, 0, STR_PAD_LEFT),
                'codNaturezaDespesa'      => str_pad($oResultado->codnaturezadespesa, 1, 0, STR_PAD_LEFT),
                'codModalidadeDespesa'    => str_pad($oResultado->codmodalidadedespesa, 2, 0, STR_PAD_LEFT),
                'codElementoDespesa'      => str_pad($oResultado->codelementodespesa, 2, 0, STR_PAD_LEFT),
                'codSubelementoDespesa'   => str_pad($oResultado->codsubelementodespesa, 3, 0, STR_PAD_LEFT),
                'modalidadeLicitacao'     => str_pad($oResultado->modalidadelicitacao, 2, 0, STR_PAD_LEFT),
                'numLicitacao'            => str_pad($oResultado->numlicitacao, 9, 0, STR_PAD_LEFT),
                'numEmpenho'              => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'tipo'                    => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'data'                    => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                   => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'historico'               => str_pad($oResultado->historico, 255, ' ', STR_PAD_RIGHT),
                'complementacaoHistorico' =>
                    str_pad($oResultado->complementacaohistorico, 255, ' ', STR_PAD_RIGHT),
                'cpfCnpjFornecedor'       => str_pad($oResultado->cpfcnpjfornecedor, 14, 0, STR_PAD_LEFT),
                'tipoMeta'                => str_pad($oResultado->tipometa, 1, 0, STR_PAD_LEFT),
                'numObra'                 => str_pad($numObra->geo_obra, 8, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'   => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'         => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'cpfOrdenador'            => str_pad($oResultado->cpfordenador, 11, 0, STR_PAD_LEFT),
                'co'                      => str_pad($oResultado->co, 4, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.9. Estornos
    public static function getEstorno($params)
    {
        $iInstit = db_getsession("DB_instit");
        $sWhere = " conlancam.c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";

        $sSql = "WITH estornos_depara AS (
                        SELECT codlan AS c70_codlan,
                               codanulacao AS e94_codanu,
                               e60_anousu,
                               e60_codemp,
                               e60_instit,
                               e60_coddot
                        FROM empanulado
                             JOIN empempenho ON e60_numemp = e94_numemp
                             JOIN empenhoanulacaodepara ON codanulacao = e94_codanu
                        WHERE e94_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                          AND e60_instit = {$iInstit}
                      ),

                      estorno_depara_codlan AS (
                        SELECT estornos_depara.c70_codlan,
                               e94_codanu,
                               e60_anousu,
                               e60_codemp,
                               e60_instit,
                               e60_coddot,
                               c70_data,
                               c70_valor,
                               c53_descr
                        FROM estornos_depara
                             JOIN contabilidade.conlancam ON conlancam.c70_codlan = estornos_depara.c70_codlan
                             JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                             JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                        UNION
                        SELECT conlancam.c70_codlan,
                               empanulado.e94_codanu,
                               empempenho.e60_anousu,
                               empempenho.e60_codemp,
                               empempenho.e60_instit,
                               empempenho.e60_coddot,
                               conlancam.c70_data,
                               conlancam.c70_valor,
                               conhistdoc.c53_descr
                        FROM contabilidade.conlancam
                             JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                             JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                                                    AND empempenho.e60_anousu = {$params->data_ano}
                             JOIN empenho.empanulado ON empanulado.e94_numemp = empempenho.e60_numemp
                                                    AND empanulado.e94_data = conlancam.c70_data
                             JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                             JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                             LEFT JOIN estornos_depara ON estornos_depara.c70_codlan = conlancam.c70_codlan
                        WHERE {$sWhere}
                          AND empempenho.e60_instit = {$iInstit}
                          AND conhistdoc.c53_tipo = 11
                          AND estornos_depara.c70_codlan is null
                      ),

                      estornos AS (
                        SELECT codunidadegestora,
                               anoemissaoempenho,
                               codunidadeorcamentaria,
                               numempenho,
                               numero,
                               data,
                               data_lancto,
                               valor,
                               trim(motivo) AS motivo,
                               despesaliquidada,
                               descricao,
                               ('Lancamento contabil correspondente ao evento ' || descricao ||
                                ', empenho '|| numempenho || '/' || anoemissaoempenho ||' em '|| data_lancto)
                                      AS motivobasico,
                               reservado
                        FROM (
                              SELECT lpad(tribinst::VARCHAR, 6, 0) AS codunidadegestora,
                                     e60_anousu AS anoemissaoempenho,
                                     lpad(orcdotacao.o58_orgao::varchar, 2, '0')||
                                     lpad(orcdotacao.o58_unidade::varchar, 3, '0') AS codunidadeorcamentaria,
                                     lpad(e60_codemp::varchar, 7, 0) AS numempenho,
                                     e94_codanu AS numero,
                                     to_char(c70_data, 'DDMMYYYY') AS data,
                                     to_char(c70_data, 'DD/MM/YYYY') AS data_lancto,
                                     round(c70_valor, 2) AS valor,
                                     coalesce(rpad(regexp_replace(conlancamcompl.c72_complem,
                                                                  E'[\n\r]+', ' ', 'g'), 120), '')
                                                AS motivo,
                                    'N' AS despesaliquidada,
                                     c53_descr AS descricao,
                                     0 AS reservado
                              FROM estorno_depara_codlan
                                   JOIN orcamento.orcdotacao
                                     ON orcdotacao.o58_coddot = estorno_depara_codlan.e60_coddot
                                    AND orcdotacao.o58_anousu = estorno_depara_codlan.e60_anousu
                                    AND orcdotacao.o58_instit = estorno_depara_codlan.e60_instit
                                   JOIN configuracoes.db_config ON db_config.codigo = estorno_depara_codlan.e60_instit
                                   JOIN contabilidade.conlancamcompl
                                     ON conlancamcompl.c72_codlan = estorno_depara_codlan.c70_codlan
                             ) AS x
                      )

                      SELECT codunidadegestora,
                             anoemissaoempenho,
                             codunidadeorcamentaria,
                             numempenho,
                             numero,
                             data,
                             valor,
                             despesaliquidada,
                             reservado,
                             CASE WHEN length(motivo) < 50
                                       THEN substr((motivo||' - '||motivobasico), 1, 120)
                                  WHEN length(motivo) > 120
                                       THEN substr(motivo, 1, 120)
                                  ELSE motivo
                             END AS motivo
                      FROM estornos
                      ORDER BY numempenho, numero ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informaes dos Estornos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'despesaLiquidada'       => str_pad(utf8_decode($oResultado->despesaliquidada), 1, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.10. Liquidacao
    public static function getLiquidacao($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $e171_dadosdefault = '{"codigo_agrupamento": " "}';
        $sSql = "SELECT lpad(db_config.tribinst::varchar,6,0) AS codunidadegestora,
                        e69_outrosdados,
                        coalesce(e171_dados, '{$e171_dadosdefault}') as e171_dados,
                        e60_anousu AS anoemissaoempenho,
                        lpad(orcdotacao.o58_orgao::varchar, 2, '0') || lpad(orcdotacao.o58_unidade::varchar, 3, '0')
                            AS codunidadeorcamentaria,
                        e60_codemp AS numempenho,
                        (select min(e82_codmov)
                          from empord
                               join conlancamord on e82_codord = c80_codord
                               join conlancamdoc on c80_codlan = c71_codlan
                               join conhistdoc on c71_coddoc = c53_coddoc
                         where c80_codlan = c70_codlan
                           and c53_tipo = 20) AS numero,
                        TO_CHAR(c70_data, 'DDMMYYYY') AS data,
                        e69_numero AS numnotafiscal,
                        TO_CHAR(e69_dtnota, 'DDMMYYYY') AS datanotafiscal,
                        round(e70_valor,2) AS valornotafiscal,
                        round(c70_valor,2) AS valor,
                        0 AS reservado
                FROM empenho.empempenho
                     JOIN contabilidade.conlancamemp ON c75_numemp = empempenho.e60_numemp
                     JOIN contabilidade.conlancam ON c70_codlan = c75_codlan
                     JOIN contabilidade.conlancamnota ON c66_codlan = c70_codlan
                     JOIN empenho.empnota ON c66_codnota = e69_codnota
                     JOIN empenho.pagordemnota ON e71_codnota = c66_codnota
                     JOIN empenho.empnotaele ON e69_codnota = e70_codnota
                     JOIN contabilidade.conlancamdoc ON c71_codlan = c70_codlan
                     JOIN contabilidade.conhistdoc ON c53_coddoc = c71_coddoc
                     JOIN protocolo.cgm ON cgm.z01_numcgm = empempenho.e60_numcgm
                     JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                     JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                                              AND orcdotacao.o58_coddot = empempenho.e60_coddot
                                              AND orcdotacao.o58_instit = empempenho.e60_instit
                     JOIN orcamento.orcorgao ON orcorgao.o40_anousu = orcdotacao.o58_anousu
                                            AND orcorgao.o40_orgao = orcdotacao.o58_orgao
                     JOIN orcamento.orcunidade ON orcunidade.o41_anousu = orcdotacao.o58_anousu
                                              AND orcunidade.o41_orgao = orcdotacao.o58_orgao
                                              AND orcunidade.o41_unidade = orcdotacao.o58_unidade
                     LEFT JOIN empenho.empempenhooutrosdados ON e171_numemp = e60_numemp
                WHERE e60_instit = {$iInstit}
                  AND e60_anousu = {$params->data_ano}
                  AND {$sWhere}
                  AND c53_tipo = 20
                 ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações das Liquidações.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            $e69_outrosdados = json_decode($oResultado->e69_outrosdados);
            $e171_dados = json_decode($oResultado->e171_dados);

            if (!isset($e171_dados->codigo_agrupamento)) {
                $e171_dados->codigo_agrupamento = " ";
            }

            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'tipoNotaFiscal'         => str_pad($e69_outrosdados->tipo_nota, 2, 0, STR_PAD_LEFT),
                'numChaveNotaFiscal'     => str_pad($e69_outrosdados->chave_nota, 44, 0, STR_PAD_LEFT),
                'numNotaFiscal'          =>
                    str_pad(utf8_decode(substr($oResultado->numnotafiscal, 0, 15)), 15, ' ', STR_PAD_RIGHT),
                'serieNotaFiscal'        =>
                    str_pad(utf8_decode(substr($e69_outrosdados->serie_nota, 0, 12)), 12, ' ', STR_PAD_RIGHT),
                'dataNotaFiscal'         => str_pad(rmSpecial($oResultado->datanotafiscal), 8, 0, STR_PAD_LEFT),
                'valorNotaFiscal'        =>
                    str_pad(str_replace(".", ",", $oResultado->valornotafiscal), 16, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'codAgrupamentoFolha'    =>
                    str_pad(utf8_decode($e171_dados->codigo_agrupamento), 10, ' ', STR_PAD_RIGHT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.11. EstornoLiquidacao
    public static function getEstornoLiquidacao($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "
          WITH liquidacoes_sagres AS (
               select e60_instit,
                      tribinst,
                      e50_data,
                      e50_numemp,
                      e50_codord,
                      split_part(e50_obs, ':', 2)::int as numero
               from pagordem
                    join empempenho on e60_numemp = e50_numemp
                    join db_config on codigo = e60_instit
               where to_char(e50_data, 'yyyy/mm') between '2022/01' and '2022/04'
                 and trim(split_part(e50_obs, ':', 2)) <> ''
               order by e50_numemp, e50_data, e50_codord
               ),

               estornos AS (
               SELECT codunidadegestora,
                      anoemissaoempenho,
                      codunidadeorcamentaria,
                      numempenho,
                      numliquidacao,
                      numero,
                      data,
                      data_lancto,
                      valor,
                      trim(motivo) AS motivo,
                      descricao,
                      ('Lancamento contabil correspondente ao evento ' || descricao ||
                       ', empenho '|| numempenho || '/' || anoemissaoempenho ||' em '|| data_lancto) as motivobasico,
                      e71_codord,
                      reservado
               FROM (
                     SELECT lpad(tribinst::VARCHAR, 6, 0) AS codunidadegestora,
                            e60_anousu AS anoemissaoempenho,
                            lpad(orcdotacao.o58_orgao::varchar, 2, '0') ||
                            lpad(orcdotacao.o58_unidade::varchar, 3, '0') AS codunidadeorcamentaria,
                            lpad(e60_codemp::varchar, 7, 0) AS numempenho,
                           (select min(e82_codmov)
                            from empord
                                 join conlancamord on e82_codord = c80_codord
                                 join conlancamdoc on c80_codlan = c71_codlan
                                 join conhistdoc on c71_coddoc = c53_coddoc
                            where c80_codlan = c70_codlan
                              and c53_tipo = 21) AS numliquidacao,
                            '' AS numero,
                            to_char(c70_data, 'DDMMYYYY') AS data,
                            to_char(c70_data, 'DD/MM/YYYY') AS data_lancto,
                            round(c70_valor, 2) AS valor,
                            coalesce(rpad(regexp_replace(conlancamcompl.c72_complem, E'[\n\r ]+', ' ', 'g'), 120), '')
                                       AS motivo,
                            conhistdoc.c53_descr AS descricao,
                            e71_codord,
                            0 AS reservado
                     FROM contabilidade.conlancam
                          JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                          JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                                                 AND empempenho.e60_anousu = {$params->data_ano}
                          JOIN contabilidade.conlancamnota ON c66_codlan = c70_codlan
                          JOIN empenho.empnota ON empnota.e69_codnota = conlancamnota.c66_codnota
                          JOIN empenho.pagordemnota ON e71_codnota = c66_codnota
                          JOIN empenho.empord ON e82_codord = e71_codord
                          JOIN orcamento.orcdotacao ON orcdotacao.o58_coddot = empempenho.e60_coddot
                                                   AND orcdotacao.o58_anousu = empempenho.e60_anousu
                                                   AND orcdotacao.o58_instit = empempenho.e60_instit
                          JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                          JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                          JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                          LEFT JOIN contabilidade.conlancamcompl ON conlancamcompl.c72_codlan = conlancam.c70_codlan
                     WHERE {$sWhere}
                       AND empempenho.e60_instit = {$iInstit}
                       AND conhistdoc.c53_tipo = 21
                    ) AS x
               )

              SELECT distinct codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     numempenho,
                     case when e50_codord is not null then liquidacoes_sagres.numero
                          else numliquidacao
                     end as numliquidacao,
                     estornos.numero,
                     data,
                     valor,
                     reservado,
                     CASE WHEN length(motivo) < 50
                               THEN substr((motivo||' - '||motivobasico), 1, 120)
                          WHEN length(motivo) > 120
                               THEN substr(motivo, 1, 120)
                          ELSE motivo
                     END AS motivo
              FROM estornos left join liquidacoes_sagres on e50_codord = e71_codord
          ";

          $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Estornos de Liquidação.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numLiquidacao'          => str_pad($oResultado->numliquidacao, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad(sprintf('%06d', $oResultado->reservado), 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.11. EstornoLiquidacaoRestos
    public static function getEstornoLiquidacaoRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "
          WITH liquidacoes_sagres AS (
               select e60_instit,
                      tribinst,
                      e50_data,
                      e50_numemp,
                      e50_codord,
                      split_part(e50_obs, ':', 2)::int as numero
               from pagordem
                    join empempenho on e60_numemp = e50_numemp
	    	        join empresto on e60_numemp = e91_numemp
                    join db_config on codigo = e60_instit
               where to_char(e50_data, 'yyyy/mm') between '2022/01' and '2022/04'
                 and trim(split_part(e50_obs, ':', 2)) <> ''
               order by e50_numemp, e50_data, e50_codord
               ),

               estornos AS (
               SELECT codunidadegestora,
                      anoemissaoempenho,
                      codunidadeorcamentaria,
                      numempenho,
                      numliquidacao,
                      numero,
                      data,
                      data_lancto,
                      valor,
                      trim(motivo) AS motivo,
                      descricao,
                      ('Lancamento contabil correspondente ao evento ' || descricao ||
                       ', empenho '|| numempenho || '/' || anoemissaoempenho ||' em '|| data_lancto) as motivobasico,
                      e71_codord,
                      reservado
               FROM (
                     SELECT lpad(tribinst::VARCHAR, 6, 0) AS codunidadegestora,
                            e60_anousu AS anoemissaoempenho,
                            lpad(orcdotacao.o58_orgao::varchar, 2, '0') ||
                            lpad(orcdotacao.o58_unidade::varchar, 3, '0') AS codunidadeorcamentaria,
                            lpad(e60_codemp::varchar, 7, 0) AS numempenho,
                           (select min(e82_codmov)
                            from empord
                                 join conlancamord on e82_codord = c80_codord
                                 join conlancamdoc on c80_codlan = c71_codlan
                                 join conhistdoc on c71_coddoc = c53_coddoc
                            where c80_codlan = c70_codlan
                              and c53_tipo = 21) AS numliquidacao,
                            '' AS numero,
                            to_char(c70_data, 'DDMMYYYY') AS data,
                            to_char(c70_data, 'DD/MM/YYYY') AS data_lancto,
                            round(c70_valor, 2) AS valor,
                            coalesce(rpad(regexp_replace(conlancamcompl.c72_complem, E'[\n\r ]+', ' ', 'g'), 120), '')
                                       AS motivo,
                            conhistdoc.c53_descr AS descricao,
                            e71_codord,
                            0 AS reservado
                     FROM contabilidade.conlancam
                          JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                          JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
	    	          JOIN empresto on e60_numemp = e91_numemp
                          JOIN contabilidade.conlancamnota ON c66_codlan = c70_codlan
                          JOIN empenho.empnota ON empnota.e69_codnota = conlancamnota.c66_codnota
                          JOIN empenho.pagordemnota ON e71_codnota = c66_codnota
                          JOIN empenho.empord ON e82_codord = e71_codord
                          JOIN orcamento.orcdotacao ON orcdotacao.o58_coddot = empempenho.e60_coddot
                                                   AND orcdotacao.o58_anousu = empempenho.e60_anousu
                                                   AND orcdotacao.o58_instit = empempenho.e60_instit
                          JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                          JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                          JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                          LEFT JOIN contabilidade.conlancamcompl ON conlancamcompl.c72_codlan = conlancam.c70_codlan
                     WHERE {$sWhere}
                       AND empempenho.e60_instit = {$iInstit}
                       AND e91_anousu = {$params->ano}
                       AND conhistdoc.c53_tipo = 21
                    ) AS x
               )

              SELECT distinct codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     numempenho,
                     case when e50_codord is not null then liquidacoes_sagres.numero
                          else numliquidacao
                     end as numliquidacao,
                     estornos.numero,
                     data,
                     valor,
                     reservado,
                     CASE WHEN length(motivo) < 50
                               THEN substr((motivo||' - '||motivobasico), 1, 120)
                          WHEN length(motivo) > 120
                               THEN substr(motivo, 1, 120)
                          ELSE motivo
                     END AS motivo
              FROM estornos left join liquidacoes_sagres on e50_codord = e71_codord
          ";

          $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Estornos de Liquidação de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numLiquidacao'          => str_pad($oResultado->numliquidacao, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad(sprintf('%06d', $oResultado->reservado), 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.12. Pagamentos
    public static function getPagamentos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c75_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c75_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "WITH pagamentos AS (
                      SELECT lpad(max(tribinst)::varchar, 6, 0) AS codunidadegestora,
                             e60_anousu AS anoemissaoempenho,
                             lpad(lpad(max(o58_orgao)::varchar, 2, 0)||lpad(max(o58_unidade)::varchar, 3, 0), 5, 0)
                                       AS codunidadeorcamentaria,
                             lpad(e60_codemp, 7, 0) AS numempenho,
                             e60_numemp,
                             k12_codmov,
                             corempagemovpagamento.k12_codmov AS numero,
                             TO_CHAR(max(c75_data), 'DDMMYYYY') AS data,
                             lpad(round(sum(k12_valor), 2)::varchar, 16, '0') AS valor,
                             lpad((trim(max(db83_conta))||trim(max(db83_dvconta))),13, '0') AS numcontabancaria,
                             lpad(trim(max(db89_codagencia))||trim(max(db89_digito)),6, '0') AS numagencia,
                             max(db90_codban) AS codbanco,
                             substr(max(o15_recurso), 2, 3) AS codfonterecurso,
                             CASE WHEN max(db83_tipoconta) = 1 THEN 1
                                  WHEN max(db83_tipoconta) = 2 THEN 4
                                  WHEN max(db83_tipoconta) = 3 THEN 7
                                  ELSE 0
                             END AS tipocontabancaria,
                             regexp_replace(max(db83_identificador), '[^0-9]', '', 'g') AS cnpjgerenciacontabancaria
                      FROM contabilidade.conlancamemp
                           JOIN contabilidade.conlancam ON conlancam.c70_codlan = conlancamemp.c75_codlan
                           JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                           JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancamemp.c75_codlan
                           JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                           JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                           JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancamemp.c75_codlan
                           JOIN contabilidade.conlancamcorgrupocorrente
                             ON conlancamcorgrupocorrente.c23_conlancam = conlancamemp.c75_codlan
                           JOIN caixa.corgrupocorrente
                             ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                           JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                              AND corrente.k12_data = corgrupocorrente.k105_data
                                              AND corrente.k12_autent = corgrupocorrente.k105_autent
                                              AND corrente.k12_estorn = FALSE
                           JOIN caixa.corempagemovpagamento ON corempagemovpagamento.k12_id = corrente.k12_id
                                                           AND corempagemovpagamento.k12_data = corrente.k12_data
                                                           AND corempagemovpagamento.k12_autent = corrente.k12_autent
                           JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                                                    AND orcdotacao.o58_coddot = empempenho.e60_coddot
                           JOIN orcamento.orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
                           LEFT JOIN contabilidade.conlancampag ON conlancampag.c82_codlan = conlancamemp.c75_codlan
                           LEFT JOIN contabilidade.conplanocontabancaria
                                  ON conplanocontabancaria.c56_reduz = conlancampag.c82_reduz
                                 AND conplanocontabancaria.c56_anousu = conlancampag.c82_anousu
                           LEFT JOIN configuracoes.contabancaria
                                  ON contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria
                           LEFT JOIN configuracoes.bancoagencia
                                  ON bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                           LEFT JOIN configuracoes.db_bancos ON db_bancos.db90_codban = bancoagencia.db89_db_bancos
                      WHERE {$sWhere}
                        AND e60_instit = {$iInstit}
                        AND e60_anousu = {$params->data_ano}
                        AND c53_tipo = 30
                      GROUP BY empempenho.e60_anousu, empempenho.e60_codemp,
                               empempenho.e60_numemp, corempagemovpagamento.k12_codmov
                 )

                 SELECT codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        CASE WHEN numemp IS NOT NULL AND codmov_novo > 0
                             THEN codmov_novo
                             ELSE ((numero::VARCHAR)||(seq::VARCHAR))::INT
                        END AS numero,
                        data,
                        valor,
                        numcontabancaria,
                        numagencia,
                        codbanco,
                        codfonterecurso,
                        tipocontabancaria,
                        numcheque,
                        numdocdebito,
                        codbancocred,
                        numagenciacred,
                        numcontabancariacred,
                        exerciciofonterecurso,
                        cnpjgerenciacontabancaria
                 FROM (
                       SELECT (row_number() over(PARTITION BY e60_numemp, k12_codmov
                                                 ORDER BY e60_numemp, k12_codmov))::int AS seq,
                              codunidadegestora,
                              anoemissaoempenho,
                              codunidadeorcamentaria,
                              numempenho,
                              numero,
                              data,
                              valor,
                              numcontabancaria,
                              numagencia,
                              codbanco,
                              codfonterecurso,
                              tipocontabancaria,
                              e60_numemp,
                              lpad('0', 6) AS numcheque,
                              lpad(' ', 11) AS numdocdebito,
                              lpad(' ', 3) AS codbancocred,
                              lpad(' ', 6) AS numagenciacred,
                              lpad(' ', 13) AS numcontabancariacred,
                              CASE WHEN EXISTS
                                         (SELECT 1
                                          FROM empresto
                                          WHERE e91_numemp = e60_numemp
                                            AND e91_anousu = {$params->data_ano}) THEN 2
                                   ELSE 1
                              END AS exerciciofonterecurso,
                              cnpjgerenciacontabancaria
                       FROM pagamentos
                      ) AS x LEFT JOIN empenho.empenhocodmovdepara
                                    ON numemp = e60_numemp
                                   AND codmov_atual = (numero::varchar||seq::varchar)::int
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Pagamentos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'         => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'    => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'                => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                    => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                      => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                     => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'numContaBancaria'          =>
                    str_pad(utf8_decode(trim($oResultado->numcontabancaria)), 13, '0', STR_PAD_LEFT),
                'numAgencia'                =>
                    str_pad(utf8_decode(trim($oResultado->numagencia)), 6, '0', STR_PAD_LEFT),
                'codBanco'                  =>
                    str_pad(utf8_decode(trim($oResultado->codbanco)), 3, '0', STR_PAD_LEFT),
                'numCheque'                 =>
                    str_pad(utf8_decode(trim($oResultado->numcheque)), 6, '0', STR_PAD_LEFT),
                'numDocDebito'              =>
                    str_pad(utf8_decode(trim($oResultado->numdocdebito)), 11, ' ', STR_PAD_LEFT),
                'codBancoCred'              =>
                    str_pad(utf8_decode(trim($oResultado->codbancocred)), 3, ' ', STR_PAD_LEFT),
                'numAgenciaCred'            =>
                    str_pad(utf8_decode(trim($oResultado->numagenciacred)), 6, ' ', STR_PAD_LEFT),
                'numContaBancariaCred'      =>
                    str_pad(utf8_decode(trim($oResultado->numcontabancariacred)), 13, ' ', STR_PAD_LEFT),
                'exercicioFonteRecurso'     => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'           => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'tipoContaBancaria'         => str_pad($oResultado->tipocontabancaria, 1, 0, STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpjgerenciacontabancaria, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.13. EstornoPagamento
    public static function getEstornoPagamento($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c75_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c75_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "
          WITH estornos AS (
              SELECT codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     e60_numemp,
                     numempenho,
                     numpagamento,
                     numero,
                     data,
                     data_lancto,
                     despesaliquidada,
                     valor,
                     trim(motivo) AS motivo,
                     descricao,
                     ('Lancamento contabil correspondente ao evento ' || descricao ||
                      ', empenho '|| numempenho || '/' || anoemissaoempenho ||' em '|| data_lancto) as motivobasico
              FROM (
                    SELECT lpad(max(tribinst)::VARCHAR, 6, 0) AS codunidadegestora,
                           e60_anousu AS anoemissaoempenho,
                           lpad(max(orcdotacao.o58_orgao)::varchar, 2, '0') ||
                           lpad(max(orcdotacao.o58_unidade)::varchar, 3, '0')
                                                   AS codunidadeorcamentaria,
                           lpad(e60_codemp::varchar, 7, 0) AS numempenho,
                           k12_codmov AS numpagamento,
                           max(e60_numemp) as e60_numemp,
                           to_char(max(c70_data), 'DDMMYYYY') AS DATA,
                           to_char(max(c70_data), 'DD/MM/YYYY') AS data_lancto,
                           sum(round(abs(k12_valor), 2)) AS valor,
                           coalesce(rpad(regexp_replace(max(conlancamcompl.c72_complem),
                                                        E'[ ]+', ' ', 'g'), 120), '') AS motivo,
                           max(conhistdoc.c53_descr) AS descricao,
                           max('S') AS despesaliquidada,
                           lpad(max(c70_codlan), 7, 0) AS numero
                    FROM contabilidade.conlancam
                         JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                         JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                                                AND empempenho.e60_anousu = {$params->data_ano}
                         JOIN contabilidade.conlancamcorgrupocorrente
                           ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                         JOIN caixa.corgrupocorrente
                           ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                         JOIN caixa.corempagemovestorno
                           ON corempagemovestorno.k12_id = corgrupocorrente.k105_id
                          AND corempagemovestorno.k12_data = corgrupocorrente.k105_data
                          AND corempagemovestorno.k12_autent = corgrupocorrente.k105_autent
                         JOIN corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                      AND corrente.k12_data = corgrupocorrente.k105_data
                                      AND corrente.k12_autent = corgrupocorrente.k105_autent
                         JOIN empenho.empagemov ON empagemov.e81_codmov = corempagemovestorno.k12_codmov
                         JOIN orcamento.orcdotacao ON orcdotacao.o58_coddot = empempenho.e60_coddot
                                                  AND orcdotacao.o58_anousu = empempenho.e60_anousu
                                                  AND orcdotacao.o58_instit = empempenho.e60_instit
                         JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                         JOIN contabilidade.conlancamcompl ON conlancamcompl.c72_codlan = conlancam.c70_codlan
                    WHERE {$sWhere}
                      AND empempenho.e60_instit = {$iInstit}
                      AND empempenho.e60_anousu = {$params->data_ano}
                      AND conhistdoc.c53_tipo = 31
                    GROUP BY empempenho.e60_anousu, empempenho.e60_codemp,
                             empempenho.e60_numemp, corempagemovestorno.k12_codmov
                   ) AS x
              ),

              estorno_pagamento as (
                SELECT (row_number() over(partition by numempenho, numpagamento
                                          order by numempenho, numpagamento, numero))::int as seq,
                       codunidadegestora,
                       anoemissaoempenho,
                       codunidadeorcamentaria,
                       e60_numemp,
                       numempenho,
                       numpagamento,
                       numero,
                       data,
                       valor,
                       despesaliquidada,
                       CASE WHEN length(motivo) < 50
                                 THEN substr((motivo||' - '||motivobasico), 1, 120)
                            WHEN length(motivo) > 120
                                 THEN substr(motivo, 1, 120)
                            ELSE motivo
                       END AS motivo
                FROM estornos
              )

              select seq,
                     codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     numempenho,
                     numpagamento,
                     numero,
                     data,
                     valor,
                     despesaliquidada,
                     motivo
              from (
                    select seq,
                           codunidadegestora,
                           anoemissaoempenho,
                           codunidadeorcamentaria,
                           numempenho,
                           case when codmov_novo > 0
                                then codmov_novo
                                else (numpagamento::varchar||seq::varchar)::int
                           end as numpagamento,
                           numero,
                           data,
                           valor,
                           despesaliquidada,
                           motivo
                    from estorno_pagamento
                         LEFT JOIN empenho.empenhocodmovdepara
                                ON empenhocodmovdepara.numemp = e60_numemp
                               AND empenhocodmovdepara.codmov_atual = (numpagamento::varchar||seq::varchar)::int
                   ) as x
              order by numempenho, numpagamento
            ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Estornos de Pagamento.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'despesaLiquidada'       => str_pad($oResultado->despesaliquidada, 1, ' ', STR_PAD_RIGHT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.14. Retencao
    public static function getRetencao($params)
    {

        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "SELECT (row_number() over(PARTITION BY numempenho, numpagamento
                                           ORDER BY numempenho, numpagamento, c70_codlan))::int AS seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        numpagamento,
                        valor,
                        tipo,
                        reservado
                 FROM (SELECT DISTINCT lpad(tribinst::varchar, 6, 0) AS codunidadegestora,
                                       e60_anousu AS anoemissaoempenho,
                                       lpad(lpad(o58_orgao::varchar, 2, 0)||lpad(o58_unidade::varchar, 3, 0), 5, 0)
                                                AS codunidadeorcamentaria,
                                       lpad(trim(e60_codemp), 7, 0) AS numempenho,
                                       corempagemovpagamento.k12_codmov AS numpagamento,
                                       lpad(round(corrente.k12_valor, 2)::varchar, 16, 0) AS valor,
                                       CASE WHEN e21_retencaotipocalc in (1,2) THEN 2
                                            WHEN e21_retencaotipocalc in (7,3,4) THEN 3
                                            WHEN e21_retencaotipocalc in (5) THEN 1
                                            WHEN e21_retencaotipocalc in (6) THEN 5
                                       END AS tipo,
                                       lpad(0, 6) AS reservado,
                                       conlancam.c70_codlan
                       FROM contabilidade.conlancam
                            JOIN contabilidade.conlancamretencao
                              ON conlancamretencao.c127_conlancam = conlancam.c70_codlan
                            JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                                                         AND conhistdoc.c53_tipo = 30
                            JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                            JOIN empenho.retencaotiporec
                              ON retencaotiporec.e21_sequencial = conlancamretencao.c127_retencaotiporec
                            JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                            JOIN empenho.pagordem ON c80_codord = e50_codord
                            JOIN empenho.retencaopagordem ON retencaopagordem.e20_pagordem = pagordem.e50_codord
                            JOIN empenho.retencaoreceitas
                              ON retencaoreceitas.e23_retencaopagordem = retencaopagordem.e20_sequencial
                             AND retencaoreceitas.e23_retencaotiporec = retencaotiporec.e21_sequencial
                            JOIN empenho.pagordemnota ON pagordemnota.e71_codord = pagordem.e50_codord
                            JOIN empenho.empagemov ON empagemov.e81_numemp = pagordem.e50_numemp
                            JOIN empenho.retencaoempagemov
                              ON retencaoempagemov.e27_retencaoreceitas = retencaoreceitas.e23_sequencial
                             AND retencaoempagemov.e27_empagemov = empagemov.e81_codmov
                             AND retencaoempagemov.e27_principal = true
                             JOIN empenho.empord ON empord.e82_codord = conlancamord.c80_codord
                                               AND empord.e82_codmov = empagemov.e81_codmov
                            JOIN empenho.empageconf ON empageconf.e86_codmov = empagemov.e81_codmov
                            JOIN contabilidade.conlancamcorgrupocorrente
                              ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                            JOIN caixa.corgrupocorrente
                              ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                             AND corgrupocorrente.k105_corgrupotipo = 2
                            JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                               AND corrente.k12_data = corgrupocorrente.k105_data
                                               AND corrente.k12_autent = corgrupocorrente.k105_autent
                                               AND corrente.k12_estorn = FALSE
                                               AND corrente.k12_instit = empempenho.e60_instit
                            JOIN corempagemovpagamento
                              ON corempagemovpagamento.k12_id = corgrupocorrente.k105_id
                             AND corempagemovpagamento.k12_data = corgrupocorrente.k105_data
                             AND corempagemovpagamento.k12_autent = corgrupocorrente.k105_autent
                            JOIN orcamento.orcdotacao ON e60_anousu = o58_anousu
                                                     AND e60_coddot = o58_coddot
                            JOIN configuracoes.db_config ON codigo = e60_instit
                       WHERE {$sWhere}
                         AND e60_instit = {$iInstit}
                         AND c71_coddoc in (6002,6004)
                 ) as x ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Retenções.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento.$oResultado->seq, 7, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'tipo'                   => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.15. EstornoRetencao
    public static function getEstornoRetencao($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "WITH estorno_retencao as (
                 SELECT (row_number() over(partition by numempenho, numpagamento
                                           order by numempenho, numpagamento, c70_codlan))::int as seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        e60_numemp,
                        numempenho,
                        numpagamento,
                        tiporetencao,
                        numero,
                        valor,
                        reservado
                 FROM (SELECT DISTINCT lpad(tribinst::varchar,6,0) AS codunidadegestora,
                              e60_anousu AS anoemissaoempenho,
                              lpad(lpad(o58_orgao::varchar,2,0)||lpad(o58_unidade::varchar,3,0),5,0)
                                         AS codunidadeorcamentaria,
                              e60_numemp,
                              lpad(e60_codemp,7,0) AS numempenho,
                              corempagemovestorno.k12_codmov AS numpagamento,
                              CASE WHEN e21_retencaotipocalc IN (1,2) THEN 2
                                   WHEN e21_retencaotipocalc IN (7,3,4) THEN 3
                                   WHEN e21_retencaotipocalc IN (5) THEN 1
                                   WHEN e21_retencaotipocalc IN (6) THEN 5
                              END  AS tiporetencao,
                              lpad(e71_codnota,7,0) AS numero,
                              round(abs(corrente.k12_valor), 2) AS valor,
                              lpad('',6) AS reservado,
                              c70_codlan
                       FROM contabilidade.conlancam
                            JOIN contabilidade.conlancamretencao
                              ON conlancamretencao.c127_conlancam = conlancam.c70_codlan
                            JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                                                         AND conhistdoc.c53_tipo = 31
                            JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conlancamcorgrupocorrente
                              ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                            JOIN empenho.retencaotiporec
                              ON retencaotiporec.e21_sequencial = conlancamretencao.c127_retencaotiporec
                            JOIN empenho.pagordem ON pagordem.e50_codord = conlancamord.c80_codord
                            JOIN empenho.retencaopagordem ON retencaopagordem.e20_pagordem = pagordem.e50_codord
                            JOIN empenho.retencaoreceitas
                              ON retencaoreceitas.e23_retencaopagordem = retencaopagordem.e20_sequencial
                             AND retencaoreceitas.e23_retencaotiporec = retencaotiporec.e21_sequencial
                            JOIN empenho.empempenho ON empempenho.e60_numemp = pagordem.e50_numemp
                            JOIN empenho.pagordemnota ON pagordemnota.e71_codord = pagordem.e50_codord
                            JOIN empenho.empagemov ON empagemov.e81_numemp = pagordem.e50_numemp
                            JOIN empenho.retencaoempagemov
                              ON retencaoempagemov.e27_retencaoreceitas = retencaoreceitas.e23_sequencial
                             AND retencaoempagemov.e27_empagemov = empagemov.e81_codmov
                             AND retencaoempagemov.e27_principal = true
                            JOIN empenho.empord ON empord.e82_codord = conlancamord.c80_codord
                                               AND empord.e82_codmov = empagemov.e81_codmov
                            JOIN empenho.empageconf ON empageconf.e86_codmov = empagemov.e81_codmov
                            JOIN caixa.corgrupocorrente
                              ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                             AND corgrupocorrente.k105_corgrupotipo = 5
                            JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                               AND corrente.k12_data = corgrupocorrente.k105_data
                                               AND corrente.k12_autent = corgrupocorrente.k105_autent
                                               AND corrente.k12_estorn = true
                                               AND corrente.k12_instit = empempenho.e60_instit
                            JOIN corempagemovestorno ON corempagemovestorno.k12_id = corgrupocorrente.k105_id
                                                      AND corempagemovestorno.k12_data = corgrupocorrente.k105_data
                                                      AND corempagemovestorno.k12_autent = corgrupocorrente.k105_autent
                            JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                            AND orcdotacao.o58_coddot = empempenho.e60_coddot
                            JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                       WHERE {$sWhere}
                         AND e60_instit = {$iInstit}
                         AND c71_coddoc in (6003, 6005)
                      ) as x
                 )

                 select min(seq) as seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        numpagamento,
                        tiporetencao,
                        numero,
                        lpad(sum(valor)::varchar, 16, 0) as valor,
                        reservado
                 from (
                       select seq,
                              codunidadegestora,
                              anoemissaoempenho,
                              codunidadeorcamentaria,
                              numempenho,
                              numpagamento,
                              case when numemp is not null and tpretencao <> tiporetencao then tpretencao
                                   else tiporetencao
                              end as tiporetencao,
                              numero,
                              valor,
                              reservado
                       from estorno_retencao
                            left join empenhoretencaodepara on numemp = e60_numemp
                                                           and codmov = numpagamento
                      ) as x
                 group by 2,3,4,5,6,7,8,10
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Estorno de Retenções.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento.$oResultado->seq, 7, 0, STR_PAD_LEFT),
                'tipoRetencao'           => str_pad($oResultado->tiporetencao, 1, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad(sprintf('%06d', $oResultado->reservado), 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.16. ReceitaOrcamentaria
    public static function getReceitaOrcamentaria($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT codunidadegestora,
                        codreceitaorcamentaria,
                        tipolancamento,
                        exerciciofonterecurso,
                        codfonterecurso,
                        tiporeceita,
                        sum(valor) AS valor,
                        competencia,
                        co
        FROM
             (SELECT db_config.tribinst AS codunidadegestora,
                     substr(o57_fonte, 2, 8) AS codreceitaorcamentaria,
		     case when substr(o57_fonte, 1, 1) = '9' 
		       then
		         case when c53_tipo = 100 then 2 else 1 end
		       else
		         case when c53_tipo = 100 then 1 else 2 end
                       end
		     as tipolancamento,
                     1 AS exerciciofonterecurso,
                     substr(o15_recurso, 2, 3) AS codfonterecurso,
		     case when o70_concarpeculiar in ( '0', '000' ) then '1' else o70_concarpeculiar end as tiporeceita,
                     round(c70_valor, 2) AS valor,
                     lpad(extract(MONTH FROM c70_data)::varchar||
                          extract(YEAR FROM c70_data)::varchar, 6, 0) AS competencia,
                     lpad(o15_complemento, 4, 0) AS co
              FROM contabilidade.conlancam
                   JOIN contabilidade.conlancamdoc ON c70_codlan = c71_codlan
                   JOIN contabilidade.conhistdoc ON c71_coddoc = c53_coddoc
                   JOIN contabilidade.conlancamrec ON c70_codlan = c74_codlan
                   JOIN orcamento.orcreceita ON c74_anousu = o70_anousu
                                            AND c74_codrec = o70_codrec
                   JOIN orcamento.orcfontes ON o70_anousu = o57_anousu
                                           AND o70_codfon = o57_codfon
                   JOIN orcamento.orctiporec ON o70_codigo = o15_codigo
                   JOIN configuracoes.db_config ON db_config.codigo = orcreceita.o70_instit
              WHERE to_char(c70_data, 'yyyy/mm') = '{$params->folder}'
                AND o70_instit = {$iInstit}
                AND c53_tipo IN (100, 101)
             ) AS x
        GROUP BY codunidadegestora,
                 codreceitaorcamentaria,
                 tipolancamento,
                 exerciciofonterecurso,
                 codfonterecurso,
                 tiporeceita,
                 competencia,
                 co";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações da Receita Orçamentária.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codReceitaOrcamentaria' => str_pad($oResultado->codreceitaorcamentaria, 8, 0, STR_PAD_LEFT),
                'tipoLancamento'         => str_pad($oResultado->tipolancamento, 1, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'tipoReceita'            => str_pad($oResultado->tiporeceita, 1, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 6, 0, STR_PAD_LEFT),
                'co'                     => str_pad($oResultado->co, 4, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.17. TransfRecebida
    public static function getTransfRecebida($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT institrecebe.tribinst AS codunidadegestora,
                        db_config.tribinst AS codunidadegestoratransferencia,
                        CASE WHEN db_tipoinstit.db21_codtipo = 2 THEN 1
                             WHEN db_tipoinstit.db21_codtipo IN (7,9,10,12,8,5,6) THEN 9
                             ELSE 8
                        END AS tipotransferencia,
                        CASE WHEN c53_coddoc = 130 THEN 1
                             ELSE 2
                        END AS tipolancamento,
                        round(sum(slip.k17_valor), 2) AS valor,
                        0 AS reservado
                 FROM caixa.transferenciafinanceirarecebimento
                      JOIN caixa.slip sliprecebe
                        ON sliprecebe.k17_codigo = transferenciafinanceirarecebimento.k151_slip
                      JOIN configuracoes.db_config institrecebe ON institrecebe.codigo = sliprecebe.k17_instit
                      JOIN configuracoes.db_tipoinstit ON db_tipoinstit.db21_codtipo = institrecebe.db21_tipoinstit
                      JOIN caixa.sliptipooperacaovinculo ON sliptipooperacaovinculo.k153_slip = sliprecebe.k17_codigo
                      JOIN caixa.sliptipooperacao
                        ON sliptipooperacao.k152_sequencial = sliptipooperacaovinculo.k153_slipoperacaotipo
                      JOIN contabilidade.conlancamslip ON conlancamslip.c84_slip = sliprecebe.k17_codigo
                      JOIN contabilidade.conlancam ON conlancam.c70_codlan = conlancamslip.c84_conlancam
                      JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                      JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                      JOIN caixa.transferenciafinanceira
                        ON transferenciafinanceira.k150_sequencial =
                           transferenciafinanceirarecebimento.k151_transferenciafinanceira
                      JOIN caixa.slip ON slip.k17_codigo = transferenciafinanceira.k150_slip
                      JOIN configuracoes.db_config ON db_config.codigo = slip.k17_instit
                 WHERE sliprecebe.k17_instit = {$iInstit}
                   AND sliptipooperacao.k152_sequencial IN (3,4)
                   AND to_char(c70_data, 'yyyy/mm') = '{$params->folder}'
		 group by 1,2,3,4
		 order by 1,2,3,4";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações das Transferências Recebidas.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'              => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codUnidadeGestoraTransferencia' =>
                    str_pad($oResultado->codunidadegestoratransferencia, 6, 0, STR_PAD_LEFT),
                'tipoTransferencia'              => str_pad($oResultado->tipotransferencia, 1, 0, STR_PAD_LEFT),
                'tipoLancamento'                 => str_pad($oResultado->tipolancamento, 1, 0, STR_PAD_LEFT),
                'valor'                          =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'                      =>
                    str_pad(sprintf('%06d', $oResultado->reservado), 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.18. TransfConcedida
    public static function getTransfConcedida($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT db_config.tribinst AS codunidadegestora,
                        institrecebe.tribinst AS codunidadegestoratransferencia,
                        CASE WHEN db_tipoinstit.db21_codtipo = 2 THEN 1
                             WHEN db_tipoinstit.db21_codtipo IN (7,9,10,12,8,5,6) THEN 9
                             ELSE 8
                        END AS tipotransferencia,
                        CASE WHEN c53_coddoc = 120 THEN 1
                             ELSE 2
                        END AS tipolancamento,
                        round(sum(slip.k17_valor), 2) AS valor,
                        0 AS reservado
                 FROM caixa.slip
                      JOIN configuracoes.db_config ON db_config.codigo = slip.k17_instit
                      JOIN caixa.slipnum ON slip.k17_codigo = slipnum.k17_codigo
                      JOIN caixa.transferenciafinanceira ON slip.k17_codigo = transferenciafinanceira.k150_slip
                      JOIN caixa.sliptipooperacaovinculo ON slip.k17_codigo = sliptipooperacaovinculo.k153_slip
                      JOIN caixa.sliptipooperacao
                        ON sliptipooperacaovinculo.k153_slipoperacaotipo = sliptipooperacao.k152_sequencial
                      JOIN contabilidade.conlancamslip ON slip.k17_codigo = conlancamslip.c84_slip
                      JOIN contabilidade.conlancam ON conlancamslip.c84_conlancam = conlancam.c70_codlan
                      JOIN contabilidade.conlancamdoc ON conlancam.c70_codlan = conlancamdoc.c71_codlan
                      JOIN contabilidade.conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                      JOIN configuracoes.db_config institrecebe
                        ON transferenciafinanceira.k150_instituicao = institrecebe.codigo
                      JOIN configuracoes.db_tipoinstit ON institrecebe.db21_tipoinstit = db_tipoinstit.db21_codtipo
                 WHERE sliptipooperacao.k152_sequencial IN (1,2)
                   AND to_char(c70_data, 'yyyy/mm') = '{$params->folder}'
		   AND k17_instit = {$iInstit}
		 group by 1,2,3,4
		 order by 1,2,3,4";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações das Transferências Concedidas.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'              => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codUnidadeGestoraTransferencia' =>
                    str_pad($oResultado->codunidadegestoratransferencia, 6, 0, STR_PAD_LEFT),
                'tipoTransferencia'              => str_pad($oResultado->tipotransferencia, 1, 0, STR_PAD_LEFT),
                'tipoLancamento'                 => str_pad($oResultado->tipolancamento, 1, 0, STR_PAD_LEFT),
                'valor'                          =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'                      => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.19. ReceitaExtra
    public static function getReceitaExtra($params)
    {
        $iInstit = db_getsession("DB_instit");

        /* parte 1 - apropriação - apropriacaoret
           parte 2 - planilha receita extra - receita_planilha
           parte 3 - slip receita extra - receita_slip
         */

        $sSql = "with busca_corrente as (
                        select (row_number() over(ORDER BY db_config.tribinst))::int AS seq,
                               db_config.tribinst as codunidadegestora,
                                ('3' || lpad(retencaoreceitas.e23_sequencial::varchar,6,0))::integer as numero,
                               substr(tabplan.k02_estpla,1,9) as codcontacontabil,
                               corrente.k12_data as data,
                               cgm.z01_cgccpf as cpfcnpjfornecedor,
                               1 as exerciciofonterecurso,
                               substr(orctiporec.o15_recurso,2,3) as codfonterecurso,
                               round(corrente.k12_valor, 2) AS valor,
                               substr(regexp_replace(corhist.k12_histcor, e'[\n\r]+', ' ', 'g' ),1,500) as historico,
                               case when substr(tabplan.k02_estpla,1,4) = '2188' then '10000014'
                                    else '10000017'
                               end as codreceitaextra,
                               tabplan.k02_anousu as exercicio,
                               lpad(o58_orgao::varchar, 2, '0') || lpad(o58_unidade::varchar, 3, '0')
                                        as codunidadeorcamentariaretencao,
                               empempenho.e60_anousu::varchar as anoemissaoempenho,
                               empempenho.e60_codemp as numempenho,
                               empempenho.e60_numemp,
                               e20_pagordem,
                               o15_codigo,
                               retencaoempagemov.e27_empagemov as numpagamento,
                               corrente.k12_instit,
                               case when e21_retencaotipocalc in (1,2) then 2
                                    when e21_retencaotipocalc in (7,3,4) then 3
                                    when e21_retencaotipocalc in (5) then 1
                                    when e21_retencaotipocalc in (6) then 5
                               end as tiporetencao
                        from corrente
                             join corhist on corhist.k12_data = corrente.k12_data
                                         and corhist.k12_id = corrente.k12_id
                                         and corhist.k12_autent = corrente.k12_autent
                             join cornump on cornump.k12_data = corrente.k12_data
                                         and cornump.k12_id = corrente.k12_id
                                         and cornump.k12_autent = corrente.k12_autent
                             join corgrupocorrente on corgrupocorrente.k105_data = corrente.k12_data
                                                  and corgrupocorrente.k105_autent = corrente.k12_autent
                                                  and corgrupocorrente.k105_id = corrente.k12_id
                             join retencaocorgrupocorrente
                               on retencaocorgrupocorrente.e47_corgrupocorrente = corgrupocorrente.k105_sequencial
                             join retencaoreceitas
                               on retencaoreceitas.e23_sequencial = retencaocorgrupocorrente.e47_retencaoreceita
                              and retencaoreceitas.e23_recolhido is true
                             join retencaotiporec
                               on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                              and retencaotiporec.e21_retencaotiporecgrupo = 1
                              and retencaotiporec.e21_instit = corrente.k12_instit
                             join retencaoempagemov
                               on retencaoempagemov.e27_retencaoreceitas = retencaoreceitas.e23_sequencial
                              and retencaoempagemov.e27_principal is true
                             join retencaotiporeccgm
                               on retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial
                             join tabrec on tabrec.k02_codigo = cornump.k12_receit
                             join tabplan on tabplan.k02_codigo = tabrec.k02_codigo
                                         and tabplan.k02_anousu = {$params->ano}
                             join conplanoreduz on tabplan.k02_reduz = conplanoreduz.c61_reduz
                                               and tabplan.k02_anousu = conplanoreduz.c61_anousu
                             join retencaopagordem
                               on retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem
                             join pagordem on pagordem.e50_codord = retencaopagordem.e20_pagordem
                             join empempenho on empempenho.e60_numemp = pagordem.e50_numemp
                             join orcdotacao on orcdotacao.o58_coddot = empempenho.e60_coddot
                                            and orcdotacao.o58_anousu = empempenho.e60_anousu
                             join orctiporec on orctiporec.o15_codigo = conplanoreduz.c61_codigo
                             join db_config on db_config.codigo = corrente.k12_instit
                             join cgm on cgm.z01_numcgm = db_config.numcgm
                        where corrente.k12_instit = {$iInstit}
                          and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                          and corrente.k12_estorn is false
                      ),

                      conta_pagadora as (
                         select seq,
                                exercicio,
                                (select c82_reduz
                                 from conlancamord
                                      join conlancamdoc on conlancamdoc.c71_codlan = conlancamord.c80_codlan
                                      join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                                                     and conhistdoc.c53_tipo = 30
                                                     and conhistdoc.c53_coddoc < 6000
                                      join conlancampag on conlancampag.c82_codlan = conlancamord.c80_codlan
                                 where conlancamord.c80_codord = busca_corrente.e20_pagordem
                                   and busca_corrente.data between
                                       (conlancamord.c80_data - 30) and (conlancamord.c80_data + 30)
                                 order by conlancamord.c80_data desc limit 1
                                ) as reduzido
                         from busca_corrente
                      ),

                      conta_bancaria as (
                         select distinct seq,
                                lpad((trim(db83_conta)||trim(db83_dvconta)),13, '0') AS numcontabancaria,
                                lpad(trim(db89_codagencia)||trim(db89_digito),6, '0') AS numagencia,
                                db90_codban AS codbanco,
                                CASE WHEN db83_tipoconta = 1 THEN 1
                                     WHEN db83_tipoconta = 2 THEN 4
                                     WHEN db83_tipoconta = 3 THEN 7
                                     ELSE 0
                                END AS tipocontabancaria,
                                regexp_replace(db83_identificador, '[^0-9]', '', 'g') AS cnpjgerenciacontabancaria
                         from conta_pagadora
                              join contabilidade.conplanocontabancaria
                                on conplanocontabancaria.c56_reduz = conta_pagadora.reduzido
                               and conplanocontabancaria.c56_anousu = conta_pagadora.exercicio
                              join configuracoes.contabancaria
                                on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria
                              join configuracoes.bancoagencia
                                on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                              join configuracoes.db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
                      ),

                      arquivoapropriacao as (
                         select codunidadegestora,
                                numero,
                                codcontacontabil,
                                data,
                                cpfcnpjfornecedor,
                                exerciciofonterecurso,
                                codfonterecurso,
                                numcontabancaria,
                                numagencia,
                                codbanco,
                                tipocontabancaria,
                                valor,
                                historico,
                                codreceitaextra,
                                exercicio,
                                codunidadeorcamentariaretencao,
                                anoemissaoempenho::varchar,
                                numempenho,
                                (numpagamento::varchar||seq::varchar)::int as numpagamento,
                                tiporetencao,
                                e60_numemp,
                                cnpjgerenciacontabancaria
                         from (
                               select (row_number() over(PARTITION BY e60_numemp, numpagamento, numero
                                                         ORDER BY e60_numemp, numpagamento, numero))::int AS seq,
                                      codunidadegestora,
                                      numero,
                                      codcontacontabil,
                                      to_char(data, 'DDMMYYYY') as data,
                                      cpfcnpjfornecedor,
                                      exerciciofonterecurso,
                                      codfonterecurso,
                                      numcontabancaria,
                                      numagencia,
                                      codbanco,
                                      tipocontabancaria,
                                      valor,
                                      historico,
                                      codreceitaextra,
                                      exercicio,
                                      codunidadeorcamentariaretencao,
                                      anoemissaoempenho::varchar,
                                      numempenho,
                                      numpagamento,
                                      tiporetencao,
                                      e60_numemp,
                                      cnpjgerenciacontabancaria
                               from busca_corrente
                                    left join conta_bancaria on conta_bancaria.seq = busca_corrente.seq
                              ) as x
                      ),

                      apropriacaoret as (
                         select codunidadegestora,
                                numero,
                                codcontacontabil,
                                data,
                                cpfcnpjfornecedor,
                                exerciciofonterecurso,
                                codfonterecurso,
                                numcontabancaria,
                                numagencia,
                                codbanco,
                                tipocontabancaria,
                                valor,
                                historico,
                                codreceitaextra,
                                exercicio,
                                codunidadeorcamentariaretencao,
                                codunidadegestora::varchar as codunidadegestorart,
                                anoemissaoempenho::varchar,
                                numempenho,
                                case when codmov_novo is not null and codmov_novo > 0 and receita_extra is true
                                     then codmov_novo::varchar
                                     else numpagamento::varchar
                                end as numpagamento,
                                case when tpretencao is not null and tpretencao <> tiporetencao
                                     then tpretencao::varchar
                                     else tiporetencao::varchar
                                end as tiporetencao,
                                cnpjgerenciacontabancaria
                         from arquivoapropriacao
                              left join empenho.empenhoretencaodepara on empenhoretencaodepara.numemp = e60_numemp
                                                                     and empenhoretencaodepara.codmov = numpagamento
                              left join empenho.empenhocodmovdepara
                                on empenhocodmovdepara.numemp = e60_numemp
                               and empenhocodmovdepara.codmov_atual = numpagamento
                      ),

                      receita_planilha as (
                         select tribinst as codunidadegestora,
                                ('2' || lpad(placaixarec.k81_seqpla::varchar,6,0))::integer as numero,
                                substr(tabplan.k02_estpla,1,9) as codcontacontabil,
                                to_char(corrente.k12_data, 'ddmmyyyy') as data,
                                z01_cgccpf as cpfcnpjfornecedor,
                                1 as exerciciofonterecurso,
                                substr(recurso_receita.o15_recurso,2,3) as codfonterecurso,
                                db83_conta||db83_dvconta as numcontabancaria,
                                db89_codagencia||db89_digito as numagencia,
                                db89_db_bancos as codbanco,
                                case when db83_tipoconta = 1 then 1
                                     when db83_tipoconta = 2 then 4
                                     else 7
                                end as tipocontabancaria,
                                round(corrente.k12_valor,2) as valor,
                                substr(regexp_replace(placaixarec.k81_obs, e'[\n\r]+', ' ', 'g' ),1,500) as historico,
                                case when substr(tabplan.k02_estpla,1,4) = '2188' then '10000014'
                                     else '10000017'
                                end as codreceitaextra,
                                extract(year from corrente.k12_data) as exercicio,
                                codtrib as codunidadeorcamentariaretencao,
                                tribinst::varchar as codunidadegestorart,
                                '0' as anoemissaoempenho,
                                ' ' as numempenho,
                                '0' as numpagamento,
                                '0' as tiporetencao,
                                regexp_replace(db83_identificador, '[^0-9]', '', 'g') as cnpjgerenciacontabancaria
                         from corrente
                                join corplacaixa on corrente.k12_id = corplacaixa.k82_id
                                                and corrente.k12_data = corplacaixa.k82_data
                                                and corrente.k12_autent = corplacaixa.k82_autent
                                join placaixarec on k81_seqpla = corplacaixa.k82_seqpla
                                join placaixa on k80_codpla = k81_codpla
                                join db_config on k80_instit = codigo
                                join tabrec on k81_receita = k02_codigo
                                join tabplan on tabrec.k02_codigo = tabplan.k02_codigo
                                            and tabplan.k02_anousu = {$params->ano}
                                join cgm on k81_numcgm = z01_numcgm
                                join conplanoreduz redrec on tabplan.k02_reduz = redrec.c61_reduz
                                                         and tabplan.k02_anousu = redrec.c61_anousu
                                join orctiporec recurso_receita on redrec.c61_codigo = recurso_receita.o15_codigo
                                join conplanoreduz redban on redban.c61_reduz = k81_conta
                                                         and redban.c61_anousu = {$params->ano}
                                join conplano on redban.c61_anousu = c60_anousu and redban.c61_codcon = c60_codcon
                                join conplanocontabancaria on redban.c61_anousu = c56_anousu
                                                          and redban.c61_codcon = c56_codcon
                                                          and redban.c61_reduz = c56_reduz
                                join contabancaria on c56_contabancaria = db83_sequencial
                                join bancoagencia on db83_bancoagencia = db89_sequencial
                         where corrente.k12_instit = {$iInstit}
                           and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                           and corrente.k12_estorn is false
                      ),

                      receita_slip as (
			             select tribinst as codunidadegestora,
                                ('1' || lpad(slip.k17_codigo::varchar,6,0))::integer as numero,
                                substr(conplanoc.c60_estrut,1,9) as codcontacontabil,
                                to_char(corrente.k12_data, 'ddmmyyyy') as data,
                                z01_cgccpf as cpfcnpjfornecedor,
                                1 as exerciciofonterecurso,
                                substr(recurso_extra.o15_recurso,2,3) as codfonterecurso,
                                db83_conta||db83_dvconta as numcontabancaria,
                                db89_codagencia||db89_digito as numagencia,
                                db89_db_bancos as codbanco,
                                case when db83_tipoconta = 1 then 1
                                     when db83_tipoconta = 2 then 4
                                     else 7
                                end as tipocontabancaria,
                                round(corrente.k12_valor,2) as valor,
                                substr(regexp_replace(slip.k17_texto, e'[\n\r]+', ' ', 'g' ),1,500) as historico,
                                case when conplano.c60_estrut like '2188%' then '10000014'
                                     else '10000017'
                                end as codreceitaextra,
                                extract(year from corrente.k12_data) as exercicio,
                                case when k153_slipoperacaotipo in ( 7, 11 ) then ''
                                     else codtrib
                                end as codunidadeorcamentariaretencao,
                                case when k153_slipoperacaotipo in ( 7, 11 ) then ''
                                     else tribinst::varchar
                                end as codunidadegestorart,
                                case when k153_slipoperacaotipo in ( 7, 11 ) then ''
                                     else '0'::varchar
                                end as anoemissaoempenho,
                                ' ' as numempenho,
                                case when k153_slipoperacaotipo in ( 7, 11 ) then '' else '0' end as numpagamento,
                                case when k153_slipoperacaotipo in ( 7, 11 ) then '' else '0' end as tipoRetencao,
                                regexp_replace(db83_identificador, '[^0-9]', '', 'g') as cnpjgerenciacontabancaria
                         from corrente
                              join corlanc on corrente.k12_id = corlanc.k12_id
                                          and corrente.k12_data = corlanc.k12_data
                                          and corrente.k12_autent = corlanc.k12_autent
                              join slip on corlanc.k12_codigo = k17_codigo
                              join slipnum on slip.k17_codigo = slipnum.k17_codigo
                              join sliptipooperacaovinculo on slip.k17_codigo = k153_slip
                              join sliptipooperacao on k153_slipoperacaotipo = k152_sequencial
                              join cgm on slipnum.k17_numcgm = z01_numcgm
                              join db_config on slip.k17_instit = codigo
                              join conplanoreduz credito on credito.c61_reduz = slip.k17_credito
                                                        and credito.c61_anousu = {$params->ano}
                              join conplano conplanoc on credito.c61_anousu = conplanoc.c60_anousu
                                                     and credito.c61_codcon = conplanoc.c60_codcon
                              join orctiporec recurso_extra on recurso_extra.o15_codigo = credito.c61_codigo
                              join conplanoreduz debito on debito.c61_reduz = slip.k17_debito
                                                       and debito.c61_anousu = {$params->ano}
                              join conplano on debito.c61_anousu = conplano.c60_anousu
                                           and debito.c61_codcon = conplano.c60_codcon
                              join conplanocontabancaria on debito.c61_anousu = c56_anousu
                                                        and debito.c61_codcon = c56_codcon
                                                        and debito.c61_reduz = c56_reduz
                              join contabancaria on c56_contabancaria = db83_sequencial
                              join bancoagencia on db83_bancoagencia = db89_sequencial
                         where corrente.k12_instit = {$iInstit}
                           and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                           and corrente.k12_estorn is false
                           and k152_sequencial in (7,11)
                      )

                      select codunidadegestora,
                             numero,
                             codcontacontabil,
                             case when codcontacontabil = '113810800' then true
                                  when codcontacontabil = '218810499' then false
                                  else false
                             end exigere,
                             data,
                             cpfcnpjfornecedor,
                             exerciciofonterecurso,
                             codfonterecurso,
                             numcontabancaria,
                             numagencia,
                             codbanco,
                             tipocontabancaria,
                             valor,
                             historico,
                             codreceitaextra,
                             exercicio,
                             codunidadeorcamentariaretencao,
                             codunidadegestorart,
                             anoemissaoempenho,
                             numempenho,
                             numpagamento,
                             tipoRetencao,
                             cnpjgerenciacontabancaria
                      from (
                            select codunidadegestora,
                                   numero,
                                   case when substr(codcontacontabil,1,7) = '1138108' then '113810800'
                                        when substr(codcontacontabil,1,7) = '1138109' then '113810900'
                                        when substr(codcontacontabil,1,7) = '1138199' then '113819900'
                                        when substr(codcontacontabil,1,7) = '2188103' then '218810499'
                                        else codcontacontabil
                                   end as codcontacontabil,
                                   data,
                                   cpfcnpjfornecedor,
                                   exerciciofonterecurso,
                                   codfonterecurso,
                                   numcontabancaria,
                                   numagencia,
                                   codbanco,
                                   tipocontabancaria,
                                   valor,
                                   historico,
                                   codreceitaextra,
                                   exercicio,
                                   codunidadeorcamentariaretencao,
                                   codunidadegestorart,
                                   anoemissaoempenho,
                                   numempenho,
                                   numpagamento,
                                   tipoRetencao,
                                   cnpjgerenciacontabancaria
                            from (
                                    select * from apropriacaoret
                                    union all
                                    select * from receita_planilha
                                    union all
                                    select * from receita_slip
                                 ) as x
                           ) as y ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações da Receita Extra.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'              => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numero'                         => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'codContaContabil'               => str_pad($oResultado->codcontacontabil, 9, 0, STR_PAD_LEFT),
                'data'                           => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'cpfCnpjFornecedor'              => str_pad($oResultado->cpfcnpjfornecedor, 14, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'          => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'                => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'numContaBancaria'               => str_pad($oResultado->numcontabancaria, 13, 0, STR_PAD_LEFT),
                'numAgencia'                     => str_pad($oResultado->numagencia, 6, 0, STR_PAD_LEFT),
                'codBanco'                       => str_pad($oResultado->codbanco, 3, 0, STR_PAD_LEFT),
                'tipoContaBancaria'              => str_pad($oResultado->tipocontabancaria, 1, 0, STR_PAD_LEFT),
                'valor'                          =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'historico'                      => str_pad($oResultado->historico, 500, ' ', STR_PAD_RIGHT),
                'codReceitaExtra'                => str_pad($oResultado->codreceitaextra, 8, 0, STR_PAD_LEFT),
                'exercicio'                      => str_pad($oResultado->exercicio, 4, 0, STR_PAD_LEFT),
                'codUnidadeGestoraRetencao'      =>
                    str_pad($oResultado->codunidadegestorart, 6, ($oResultado->exigere == 1?0:" "), STR_PAD_LEFT),
                'codUnidadeOrcamentariaRetencao' =>
             str_pad($oResultado->codunidadeorcamentariaretencao, 5, ($oResultado->exigere == 1?0:" "), STR_PAD_RIGHT),
                'anoEmissaoEmpenho'              =>
                    str_pad($oResultado->anoemissaoempenho, 4, ($oResultado->exigere == 1?0:" "), STR_PAD_LEFT),
                'numEmpenho'                     =>
                    str_pad($oResultado->numempenho, 7, ($oResultado->exigere == 1?0:" "), STR_PAD_LEFT),
                'numPagamento'                   =>
                    str_pad($oResultado->numpagamento, 7, ($oResultado->exigere == 1?0:" "), STR_PAD_LEFT),
                'tipoRetencao'                   =>
                    str_pad($oResultado->tiporetencao, 1, ($oResultado->exigere == 1?0:" "), STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria'      =>
                    str_pad($oResultado->cnpjgerenciacontabancaria, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.21. EstornoReceitaExtra
    public static function getEstornoReceitaExtra($params)
    {
        $iInstit = db_getsession("DB_instit");

        /* parte 1 - apropriação
           parte 2 - planilha receita extra
           parte 3 - slip receita extra
         */

         $sSql = "with busca_corrente as (
                          select db_config.tribinst as codunidadegestora,
                                 ('3' || lpad(retencaoreceitas.e23_sequencial::varchar,6,0))::integer
                                      as numeroreceitaextra,
                                 to_char(corrente.k12_data, 'ddmmyyyy') as data,
                                 round(abs(corrente.k12_valor), 2) as valor,
                                 'Correspondente ao estorno de apropriação de retenção referente a receita extra '||
                                 tabplan.k02_estpla as historico,
                                 0 as reservado
                          from corrente
                               join db_config on db_config.codigo = corrente.k12_instit
                               join cornump on cornump.k12_data = corrente.k12_data
                                           and cornump.k12_id = corrente.k12_id
                                           and cornump.k12_autent = corrente.k12_autent
                               join corgrupocorrente on corgrupocorrente.k105_data = corrente.k12_data
                                                    and corgrupocorrente.k105_autent = corrente.k12_autent
                                                    and corgrupocorrente.k105_id = corrente.k12_id
                               join retencaocorgrupocorrente
                                 on retencaocorgrupocorrente.e47_corgrupocorrente = corgrupocorrente.k105_sequencial
                               join retencaoreceitas
                                 on retencaoreceitas.e23_sequencial = retencaocorgrupocorrente.e47_retencaoreceita
                                and retencaoreceitas.e23_ativo is false
                                and retencaoreceitas.e23_recolhido is true
                               join retencaotiporec
                                 on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                                and retencaotiporec.e21_retencaotiporecgrupo = 1
                                and retencaotiporec.e21_instit = corrente.k12_instit
                               join retencaotiporeccgm
                                 on retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial
                               join tabrec on tabrec.k02_codigo = retencaotiporec.e21_receita
                               join tabplan on tabplan.k02_codigo = tabrec.k02_codigo
                                           and tabplan.k02_anousu = {$params->ano}
                          where corrente.k12_instit = {$iInstit}
                            and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                            and corrente.k12_estorn is true
                       ),

                       receita_planilha AS (
                          select tribinst as codunidadegestora,
                                 ('2' || lpad(placaixarec.k81_seqpla::varchar,6,0))::integer as numeroreceitaextra,
                                 to_char(corrente.k12_data, 'ddmmyyyy') as data,
                                 round(abs(corrente.k12_valor), 2) as valor,
                                 'Correspondente ao estorno de planilha referente a receita extra '||
                                 tabplan.k02_estpla as historico,
                                 0 as reservado
                          from corrente
                               join db_config on codigo = k12_instit
                               join corplacaixa on corrente.k12_id = corplacaixa.k82_id
                                               and corrente.k12_data = corplacaixa.k82_data
                                               and corrente.k12_autent = corplacaixa.k82_autent
                               join placaixarec on k81_seqpla = corplacaixa.k82_seqpla
                               join placaixa on k80_codpla = k81_codpla
                               join tabrec on k81_receita = k02_codigo
                               join tabplan on tabrec.k02_codigo = tabplan.k02_codigo
                                           and tabplan.k02_anousu = {$params->ano}
                          where corrente.k12_instit = {$iInstit}
                            and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                            and corrente.k12_estorn is true
                       ),

                       receita_slip as (
                          select tribinst as codunidadegestora,
                                 ('1' || lpad(slip.k17_codigo::varchar,6,0))::integer as numeroreceitaextra,
                                 to_char(corrente.k12_data, 'ddmmyyyy') as data,
                                 round(abs(corrente.k12_valor), 2) as valor,
                                 'Correspondente ao estorno de slip referente a receita extra '||
                                 conplano.c60_estrut as historico,
                                 0 as reservado
                          from corrente
                               join corlanc on corrente.k12_id = corlanc.k12_id
                                           and corrente.k12_data = corlanc.k12_data
                                           and corrente.k12_autent = corlanc.k12_autent
                               join slip on corlanc.k12_codigo = k17_codigo
                               join sliptipooperacaovinculo on slip.k17_codigo = k153_slip
                               join sliptipooperacao on k153_slipoperacaotipo = k152_sequencial
                               join db_config on slip.k17_instit = codigo
                               join conplanoreduz credito on credito.c61_reduz = slip.k17_credito
                                                         and credito.c61_anousu = {$params->ano}
                               join orctiporec recurso_extra on recurso_extra.o15_codigo = credito.c61_codigo
                               join conplanoreduz debito on debito.c61_reduz = slip.k17_debito
                                                        and debito.c61_anousu = {$params->ano}
                               join conplano on debito.c61_anousu = c60_anousu
                                            and debito.c61_codcon = c60_codcon
                          where corrente.k12_instit = {$iInstit}
                            and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                            and corrente.k12_estorn is true
                            and k152_sequencial in (7,8,11,12)
                       )

		       select
                          codunidadegestora,
                          numeroreceitaextra,
                          data,
                          valor,
                          historico,
                          reservado
 			from busca_corrente
                       union all
		       select
                          codunidadegestora,
			  case
				when '$params->folder' = '2022/06' and numeroreceitaextra = '2004720' then '0000006'
				when '$params->folder' = '2022/06' and numeroreceitaextra = '2004719' then '0000005'
				else
				numeroreceitaextra
			  end as numeroreceitaextra,
                          data,
                          valor,
                          historico,
                          reservado
 			from receita_planilha
                       union all
		       select
                          codunidadegestora,
                          numeroreceitaextra,
                          data,
                          valor,
                          historico,
                          reservado
			from receita_slip
			";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Estorno da Receita Extra.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'              => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numReceitaExtra'                => str_pad($oResultado->numeroreceitaextra, 7, 0, STR_PAD_LEFT),
                'numero'                         => str_pad($oResultado->numeroreceitaextra, 7, 0, STR_PAD_LEFT),
                'data'                           => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                          =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'motivo'                         => str_pad($oResultado->historico, 255, ' ', STR_PAD_RIGHT),
                'reservado'                      => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.20. DespesaExtra
    public static function getDespesaExtra($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "with despesasextra as (
                        select lpad(db_config.tribinst::varchar,6,0) as codunidadegestora,
				case
				when slip.k17_codigo = 975  and corlanc.k12_autent = 6   then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 8343 and corlanc.k12_autent = 39  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1170 and corlanc.k12_autent = 57  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1172 and corlanc.k12_autent = 58  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1174 and corlanc.k12_autent = 59  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1176 and corlanc.k12_autent = 60  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1178 and corlanc.k12_autent = 61  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1180 and corlanc.k12_autent = 62  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1182 and corlanc.k12_autent = 63  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1184 and corlanc.k12_autent = 64  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1186 and corlanc.k12_autent = 65  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1188 and corlanc.k12_autent = 66  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1190 and corlanc.k12_autent = 67  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1192 and corlanc.k12_autent = 68  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 1194 and corlanc.k12_autent = 69  then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 3270 and corlanc.k12_autent = 646 then '4' || lpad(slip.k17_codigo::varchar,6,0)
				when slip.k17_codigo = 3284 and corlanc.k12_autent = 647 then '4' || lpad(slip.k17_codigo::varchar,6,0)
			        else
			          '1' || lpad(slip.k17_codigo::varchar,6,0)
			        end as numero,
                               substr(plcre.c60_estrut,1,9) as codcontacontabil,
                               to_char(corrente.k12_data, 'DDMMYYYY') AS data,
                               cgm.z01_cgccpf as cpfcnpjfornecedor,
                               1 as exerciciofonterecurso,
                               869 as codfonterecurso,
                               lpad(contabancaria.db83_conta||contabancaria.db83_dvconta::varchar,13,0)
                                      as numcontabancaria,
                               lpad(bancoagencia.db89_codagencia||bancoagencia.db89_digito::varchar,6,0)
                                      as numagencia,
                               lpad(db_bancos.db90_codban::varchar,3,0) as codbanco,
                               1 as tipocontabancaria,
                               lpad(round(corrente.k12_valor,2)::varchar,16,0) as valor,
                               rpad(trim(substr(regexp_replace(slip.k17_texto, e'[\n\r]+', ' ', 'g' ),1,500)),500)
                                      as historico,
                               case when k153_slipoperacaotipo = 7  then '20000019'
                                    when k153_slipoperacaotipo = 9  then '20000019'
                                    when k153_slipoperacaotipo = 11 then '20000017'
                                    when k153_slipoperacaotipo = 13 then '20000017'
                               end as coddespesaextra,
                               extract(year from corrente.k12_data)::int as exercicio,
                               869 as codfonterecursopagamento,
                               '0000'::varchar as copagamento,
                               case when k153_slipoperacaotipo = 9 and false then ''::varchar
                                    else lpad(db_config.tribinst::varchar,6,0)
                               end as codunidadegestorareceitaextra,
                               case when k153_slipoperacaotipo = 9 then '0'
                                    else extract(year from corrente.k12_data)::varchar
                               end as exercicioreceitaextra,
                               '' as numreceitaextra,
                               regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g')
                                       as cnpjgerenciacontabancaria,
                               case when ( select k206_retencaoreceitas
                                           from slipretencaoreceitas
                                           where k206_slip = slip.k17_codigo ) is not null
                                    then ( select ( case when k206_retencaoreceitas > 17910 and
                                                             ( select count(*)
                                                               from retencaocorgrupocorrente
                                                                    inner join corgrupocorrente
                                                                       on k105_sequencial = e47_corgrupocorrente
                                                                    inner join corrente
                                                                       on k105_data = k12_data and k105_id = k12_id
                                                                      and k105_autent = k12_autent
                                                               where e47_retencaoreceita = k206_retencaoreceitas
                                                                 and k12_instit = 9 and k12_data < '2022-05-31' ) = 0
                                                         then ('3'||lpad(k206_retencaoreceitas::varchar,6,0))::integer
                                                         else k206_retencaoreceitas end )::integer
                                           from slipretencaoreceitas
                                           where k206_slip = slip.k17_codigo )
                                    when ( select k207_placaixarec
                                           from slipplacaixarec
                                           where k207_slip = slip.k17_codigo ) is not null
                                    then ( select ( '2' || lpad(k207_placaixarec,6,0) )::integer
                                           from slipplacaixarec
                                           where k207_slip = slip.k17_codigo )
                                    when ( select k208_recebimento
                                           from slipoperacaoextra
                                           where k208_pagamento = slip.k17_codigo ) is not null
                                    then ( select ( '1' || lpad(k208_recebimento,6,0) )::integer
                                           from slipoperacaoextra
                                           where k208_pagamento = slip.k17_codigo )
                                    else 0
                                end as vinculo_receita
                        from corrente
                             join corlanc on corlanc.k12_id = corrente.k12_id
                                         and corlanc.k12_data = corrente.k12_data
                                         and corlanc.k12_autent = corrente.k12_autent
                             join slip on corlanc.k12_codigo = slip.k17_codigo
                             join sliptipooperacaovinculo on k153_slip = k12_codigo
                             join slipnum on slip.k17_codigo = slipnum.k17_codigo
                             join cgm on slipnum.k17_numcgm = cgm.z01_numcgm
                             join conplanoreduz deb on deb.c61_reduz = corrente.k12_conta
                                                   and deb.c61_anousu = {$params->ano}
                             join conplano pldeb on deb.c61_anousu = pldeb.c60_anousu
                                                and deb.c61_codcon = pldeb.c60_codcon
                             join orctiporec rec_extra on rec_extra.o15_codigo = deb.c61_codigo
                             join conplanoreduz cre on cre.c61_reduz = corlanc.k12_conta
                                                   and cre.c61_anousu = {$params->ano}
                             join conplano plcre on cre.c61_anousu = plcre.c60_anousu
                                                and cre.c61_codcon = plcre.c60_codcon
                             join orctiporec rec_bco on rec_bco.o15_codigo = cre.c61_codigo
                             join conplanocontabancaria on deb.c61_codcon = conplanocontabancaria.c56_codcon
                                                       and deb.c61_anousu = conplanocontabancaria.c56_anousu
                                                       and deb.c61_reduz = conplanocontabancaria.c56_reduz
                             join contabancaria
                               on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
                             join bancoagencia on contabancaria.db83_bancoagencia = bancoagencia.db89_sequencial
                             join db_bancos on bancoagencia.db89_db_bancos = db_bancos.db90_codban
                             join configuracoes.db_config on db_config.codigo = corrente.k12_instit
                        where corrente.k12_instit = {$iInstit}
                          and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                          and k12_estorn is false
                          and k153_slipoperacaotipo in (9,13)
		      ),

			rps as (
			    select lpad(db_config.tribinst::varchar,6,0) as codunidadegestora,
				   case
				   when coremp.k12_empen = 13314 and coremp.k12_autent = 53  then '4' || lpad(e60_codemp::varchar,6,0)
				   when coremp.k12_empen = 12954 and coremp.k12_autent = 13  then '4' || lpad(e60_codemp::varchar,6,0)
				   when coremp.k12_empen = 12862 and coremp.k12_autent = 56  then '4' || lpad(e60_codemp::varchar,6,0)
				   when coremp.k12_empen = 12864 and coremp.k12_autent = 54  then '4' || lpad(e60_codemp::varchar,6,0)
				   when coremp.k12_empen = 12860 and coremp.k12_autent = 17  then '4' || lpad(e60_codemp::varchar,6,0)
				   else
				     '2' || lpad(e60_codemp::varchar,6,0)
				   end as numero,
                                   '213210101' as codcontacontabil,
				   to_char(corrente.k12_data, 'DDMMYYYY') AS data,
				   cgm.z01_cgccpf as cpfcnpjfornecedor,
				   1 as exerciciofonterecurso,
				   869 as codfonterecurso,
				   case when contabancaria.db83_conta is not null then
				     lpad(contabancaria.db83_conta||contabancaria.db83_dvconta::varchar,13,0)
					else
					(
					select lpad(c1.db83_conta||c1.db83_dvconta::varchar,13,0)
					from conlancamord
					join conlancam on c80_codlan = c70_codlan
					join conlancamdoc on c80_codlan = c71_codlan
					join conhistdoc on c71_coddoc = c53_coddoc
					join conlancampag on c80_codlan = c82_codlan
					join conplanoreduz cre1 on cre1.c61_reduz = c82_reduz
						       and cre1.c61_anousu = c82_anousu
					join conplano plcre1 on cre1.c61_anousu = plcre1.c60_anousu
						    and cre1.c61_codcon = plcre1.c60_codcon
					join orctiporec rec_bco1 on rec_bco1.o15_codigo = cre1.c61_codigo
					join conplanocontabancaria cc1 on cre1.c61_codcon = cc1.c56_codcon
							   and cre1.c61_anousu = cc1.c56_anousu
							   and cre1.c61_reduz = cc1.c56_reduz
					join contabancaria c1 on cc1.c56_contabancaria = c1.db83_sequencial
					join bancoagencia ba1 on c1.db83_bancoagencia = ba1.db89_sequencial
					join db_bancos b1 on ba1.db89_db_bancos = b1.db90_codban
					where c80_codord = coremp.k12_codord and c53_tipo = 30 and c70_data = coremp.k12_data
					limit 1
					)
				   end as numcontabancaria,
				   case when bancoagencia.db89_codagencia is not null
				   then
				   lpad(bancoagencia.db89_codagencia||bancoagencia.db89_digito::varchar,6,0)
					else
					(
					select lpad(ba1.db89_codagencia||ba1.db89_digito::varchar,6,0)
					from conlancamord
					join conlancam on c80_codlan = c70_codlan
					join conlancamdoc on c80_codlan = c71_codlan
					join conhistdoc on c71_coddoc = c53_coddoc
					join conlancampag on c80_codlan = c82_codlan
					join conplanoreduz cre1 on cre1.c61_reduz = c82_reduz
						       and cre1.c61_anousu = c82_anousu
					join conplano plcre1 on cre1.c61_anousu = plcre1.c60_anousu
						    and cre1.c61_codcon = plcre1.c60_codcon
					join orctiporec rec_bco1 on rec_bco1.o15_codigo = cre1.c61_codigo
					join conplanocontabancaria cc1 on cre1.c61_codcon = cc1.c56_codcon
							   and cre1.c61_anousu = cc1.c56_anousu
							   and cre1.c61_reduz = cc1.c56_reduz
					join contabancaria c1 on cc1.c56_contabancaria = c1.db83_sequencial
					join bancoagencia ba1 on c1.db83_bancoagencia = ba1.db89_sequencial
					join db_bancos b1 on ba1.db89_db_bancos = b1.db90_codban
					where c80_codord = coremp.k12_codord and c53_tipo = 30 and c70_data = coremp.k12_data
					limit 1
					)
					end as numagencia,
				   case when db_bancos.db90_codban is not null
					then lpad(db_bancos.db90_codban::varchar,3,0)
					else
					(
					select lpad(b1.db90_codban::varchar,3,0)
					from conlancamord
					join conlancam on c80_codlan = c70_codlan
					join conlancamdoc on c80_codlan = c71_codlan
					join conhistdoc on c71_coddoc = c53_coddoc
					join conlancampag on c80_codlan = c82_codlan
					join conplanoreduz cre1 on cre1.c61_reduz = c82_reduz
						       and cre1.c61_anousu = c82_anousu
					join conplano plcre1 on cre1.c61_anousu = plcre1.c60_anousu
						    and cre1.c61_codcon = plcre1.c60_codcon
					join orctiporec rec_bco1 on rec_bco1.o15_codigo = cre1.c61_codigo
					join conplanocontabancaria cc1 on cre1.c61_codcon = cc1.c56_codcon
							   and cre1.c61_anousu = cc1.c56_anousu
							   and cre1.c61_reduz = cc1.c56_reduz
					join contabancaria c1 on cc1.c56_contabancaria = c1.db83_sequencial
					join bancoagencia ba1 on c1.db83_bancoagencia = ba1.db89_sequencial
					join db_bancos b1 on ba1.db89_db_bancos = b1.db90_codban
					where c80_codord = coremp.k12_codord and c53_tipo = 30 and c70_data = coremp.k12_data
					limit 1
					)
					end as codbanco,
				   1 as tipocontabancaria,
				   lpad(round(corrente.k12_valor,2)::varchar,16,0) as valor,
				   rpad(trim(substr(regexp_replace(empempenho.e60_resumo, e'[\n\r]+', ' ', 'g' ),1,500)),500)
					     as historico,
				   '20000010'as coddespesaextra,
				   extract(year from corrente.k12_data)::int as exercicio,
				   869 as codfonterecursopagamento,
				   '0000'::varchar as copagamento,
				   '' as codunidadegestorareceitaextra,
				   '' as exercicioreceitaextra,
				   '' as numreceitaextra,
				   case when contabancaria.db83_identificador is not null then
				   regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g')
				   else
				   (
				   select regexp_replace(c1.db83_identificador,'[^0-9]','','g')
				   from conlancamord
				   join conlancam on c80_codlan = c70_codlan
				   join conlancamdoc on c80_codlan = c71_codlan
				   join conhistdoc on c71_coddoc = c53_coddoc
				   join conlancampag on c80_codlan = c82_codlan
				   join conplanoreduz cre1 on cre1.c61_reduz = c82_reduz
						  and cre1.c61_anousu = c82_anousu
				   join conplano plcre1 on cre1.c61_anousu = plcre1.c60_anousu
					       and cre1.c61_codcon = plcre1.c60_codcon
				   join orctiporec rec_bco1 on rec_bco1.o15_codigo = cre1.c61_codigo
				   join conplanocontabancaria cc1 on cre1.c61_codcon = cc1.c56_codcon
						      and cre1.c61_anousu = cc1.c56_anousu
						      and cre1.c61_reduz = cc1.c56_reduz
				   join contabancaria c1 on cc1.c56_contabancaria = c1.db83_sequencial
				   join bancoagencia ba1 on c1.db83_bancoagencia = ba1.db89_sequencial
				   join db_bancos b1 on ba1.db89_db_bancos = b1.db90_codban
				   where c80_codord = coremp.k12_codord and c53_tipo = 30 and c70_data = coremp.k12_data
				   limit 1
				   )
				   end as cnpjgerenciacontabancaria,
				   0 as vinculo_receita
			    from corrente
				 join db_config on k12_instit = codigo
				 join coremp on corrente.k12_data = coremp.k12_data
						  and corrente.k12_id = coremp.k12_id and corrente.k12_autent = coremp.k12_autent
				 join empempenho on k12_empen = e60_numemp
				 join cgm on z01_numcgm = e60_numcgm
				 join empresto on e91_anousu = 2022 and e91_numemp = e60_numemp

				 left join conplanoreduz cre on cre.c61_reduz = corrente.k12_conta
						       and cre.c61_anousu = {$params->ano}
				 left join conplano plcre on cre.c61_anousu = plcre.c60_anousu
						    and cre.c61_codcon = plcre.c60_codcon
				 left join orctiporec rec_bco on rec_bco.o15_codigo = cre.c61_codigo
				 left join conplanocontabancaria on cre.c61_codcon = conplanocontabancaria.c56_codcon
							   and cre.c61_anousu = conplanocontabancaria.c56_anousu
							   and cre.c61_reduz = conplanocontabancaria.c56_reduz
				 left join contabancaria
				   on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
				 left join bancoagencia on contabancaria.db83_bancoagencia = bancoagencia.db89_sequencial
				 left join db_bancos on bancoagencia.db89_db_bancos = db_bancos.db90_codban

			    where corrente.k12_instit = {$iInstit}
			      and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
			      and k12_estorn is false

			)

                      select codunidadegestora,
			     case
				when numero = '1000975' and codcontacontabil = '218830102' then '4000975'
                             	when numero = '1002690' and numcontabancaria = '0000000430595' then '4002690'
				else numero end
				as numero,
			     codcontacontabil,
                             data,
                             cpfcnpjfornecedor,
                             exerciciofonterecurso,
                             codfonterecurso,
                             numcontabancaria,
                             numagencia,
                             codbanco,
                             tipocontabancaria,
                             valor,
                             historico,
                             coddespesaextra,
                             exercicio,
                             codfonterecursopagamento,
			     copagamento,
			     case
				when codcontacontabil in ( '113819900', '218810801' )
				  then ''
				else
			          codunidadegestorareceitaextra
				end as codunidadegestorareceitaextra,
			     case
				when codcontacontabil in ( '113819900', '218810801' )
				  then ''
				else
				  exercicioreceitaextra
				end as
                             exercicioreceitaextra,
			     case
				when codcontacontabil in ( '113819900', '218810801' )
				  then 0
				else
				  1
			     end as exigert,
			     case
				when substr(codcontacontabil,1,7) in ( '1138199', '2188108' )
				then ''
				else numreceitaextra
			     end as numreceitaextra,
			     cnpjgerenciacontabancaria

			from (

                      select codunidadegestora,
                             numero,
			                 case when substr(codcontacontabil,1,7) = '1138109' then '113810900'
                                  when substr(codcontacontabil,1,7) = '1138199' then '113819900'
                                  when substr(codcontacontabil,1,7) = '1138108' then '113810800'
                                  when substr(codcontacontabil,1,7) = '1138108' then '113810800'
                                  when substr(codcontacontabil,1,7) = '1138115' then '113811500'
                                  when substr(codcontacontabil,1,7) = '2188101' then '218810110'
                                  else codcontacontabil
                             end as codcontacontabil,
                             data,
                             cpfcnpjfornecedor,
                             exerciciofonterecurso,
                             codfonterecurso,
                             numcontabancaria,
                             numagencia,
                             codbanco,
                             tipocontabancaria,
                             valor,
                             historico,
                             coddespesaextra,
                             exercicio,
                             codfonterecursopagamento,
                             copagamento,
                             case when coddespesaextra in ( '20000017', '20000019' ) and
                                       substr(codcontacontabil,1,7) in
                                       ('1138199','1138109','1138108','2188108','1138115') then ''
                                  when vinculo_receita > 0 or true then codunidadegestorareceitaextra
                                  else ''
                             end as codunidadegestorareceitaextra,
                             case when coddespesaextra in ( '20000017', '20000019' ) and
                                       substr(codcontacontabil,1,7) in
                                       ('1138199','1138109','1138108','2188108','1138115') then ''
                                  when vinculo_receita > 0 then exercicioreceitaextra::varchar
                                  else '0'
                             end as exercicioreceitaextra,
                             case when vinculo_receita > 0 and
                                       (substr(codcontacontabil,1,7) not in ('1138109','1138108','2188108'))
                                       then lpad(vinculo_receita,7,0)
                                  else ''
                             end as numreceitaextra,
                             cnpjgerenciacontabancaria
			          from despesasextra
 			     ) as x

			union all

                      select codunidadegestora,
                             numero,
                             codcontacontabil,
                             data,
                             cpfcnpjfornecedor,
                             exerciciofonterecurso,
                             codfonterecurso,
                             numcontabancaria,
                             numagencia,
                             codbanco,
                             tipocontabancaria,
                             valor,
                             historico,
                             coddespesaextra,
                             exercicio,
                             codfonterecursopagamento,
                             copagamento,
                             codunidadegestorareceitaextra,
                             exercicioreceitaextra,
			     1 exigert,
                             numreceitaextra,
			     cnpjgerenciacontabancaria

			from rps

                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações da Despesa Extra.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'             => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numero'                        => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'codContaContabil'              => str_pad($oResultado->codcontacontabil, 9, 0, STR_PAD_LEFT),
                'data'                          => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'cpfCnpjFornecedor'             => str_pad($oResultado->cpfcnpjfornecedor, 14, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'         => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'               => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'numContaBancaria'              => str_pad($oResultado->numcontabancaria, 13, 0, STR_PAD_LEFT),
                'numAgencia'                    => str_pad($oResultado->numagencia, 6, 0, STR_PAD_LEFT),
                'codBanco'                      => str_pad($oResultado->codbanco, 3, 0, STR_PAD_LEFT),
                'tipoContaBancaria'             => str_pad($oResultado->tipocontabancaria, 1, 0, STR_PAD_LEFT),
                'valor'                         =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'historico'                     => str_pad($oResultado->historico, 500, ' ', STR_PAD_RIGHT),
                'codDespesaExtra'               => str_pad($oResultado->coddespesaextra, 8, 0, STR_PAD_LEFT),
                'exercicio'                     => str_pad($oResultado->exercicio, 4, 0, STR_PAD_LEFT),
                'codFonteRecursoPagamento'      => str_pad($oResultado->codfonterecursopagamento, 3, 0, STR_PAD_LEFT),
                'co'                            => str_pad($oResultado->copagamento, 4, 0, STR_PAD_LEFT),
                'codUnidadeGestoraReceitaExtra' =>
                    str_pad($oResultado->codunidadegestorareceitaextra, 6, ($oResultado->coddespesaextra == '20000010'
                            ||
                            $oResultado->coddespesaextra == '20000019'
                            ||
                            $oResultado->exigert == 0?' ':0), STR_PAD_LEFT),
                'exercicioReceitaExtra'         =>
                    str_pad($oResultado->exercicioreceitaextra, 4, ( $oResultado->coddespesaextra == '20000010' ||
                            $oResultado->coddespesaextra == '20000019'?' ':' ' ), STR_PAD_LEFT),
                'numReceitaExtra'               =>
                    str_pad($oResultado->numreceitaextra, 7, ( $oResultado->coddespesaextra == '20000010' ||
                            $oResultado->coddespesaextra == '20000019' ||
                            $oResultado->exigert == 0?' ':0 ), STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria'    => str_pad($oResultado->cnpjgerenciacontabancaria, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.22. EstornoDespesaExtra
    public static function getEstornoDespesaExtra($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "select codunidadegestora,
                        case when numero = '1000975' then '4000975'
                             else numero
                        end as numero,
                        data,
                        valor,
                        historico,
                        reservado
                 from (
    		           select lpad(db_config.tribinst::varchar,6,0) as codunidadegestora,
                              '1' || lpad(slip.k17_codigo::varchar,6,0) as numero,
                              to_char(corrente.k12_data, 'DDMMYYYY') AS data,
                              lpad(round(abs(corrente.k12_valor),2)::varchar,16,0) as valor,
                              rpad(substr(regexp_replace(conlancamcompl.c72_complem, e'[\n\r]+', ' ', 'g' ),1,255),255)
                                    as historico,
                              repeat('0',6) as reservado
                       from corrente
                            join corlanc on corlanc.k12_id = corrente.k12_id
                                        and corlanc.k12_data = corrente.k12_data
                                        and corlanc.k12_autent = corrente.k12_autent
                            join slip on corlanc.k12_codigo = slip.k17_codigo
                            join sliptipooperacaovinculo on k153_slip = k12_codigo
                            join slipnum on slip.k17_codigo = slipnum.k17_codigo
                            join cgm on slipnum.k17_numcgm = cgm.z01_numcgm
                            join configuracoes.db_config on db_config.codigo = corrente.k12_instit
                            join conlancamcorrente on c86_data = corrente.k12_data
                                                  and c86_id = corrente.k12_id
                                                  and c86_autent = corrente.k12_autent
                            join conlancamcompl on conlancamcompl.c72_codlan = c86_conlancam
                       where corrente.k12_instit = {$iInstit}
                         and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
                         and k12_estorn is true
			 and k153_slipoperacaotipo in (9,13)

			union

			    select lpad(db_config.tribinst::varchar,6,0) as codunidadegestora,
				   '2' || lpad(e60_codemp, 6, 0) as numero,
				   to_char(corrente.k12_data, 'DDMMYYYY') AS data,
				   lpad(round(corrente.k12_valor*-1,2)::varchar,16,0) as valor,
				   rpad(trim(substr(regexp_replace(empempenho.e60_resumo, e'[\n\r]+', ' ', 'g' ),1,255)),255)
					     as historico,
 	                           repeat('0',6) as reservado

			    from corrente
				 join db_config on k12_instit = codigo
				 join coremp on corrente.k12_data = coremp.k12_data
						  and corrente.k12_id = coremp.k12_id and corrente.k12_autent = coremp.k12_autent
				 join empempenho on k12_empen = e60_numemp
				 join cgm on z01_numcgm = e60_numcgm
				 join empresto on e91_anousu = 2022 and e91_numemp = e60_numemp
				 join conplanoreduz cre on cre.c61_reduz = corrente.k12_conta
						       and cre.c61_anousu = {$params->ano}
				 join conplano plcre on cre.c61_anousu = plcre.c60_anousu
						    and cre.c61_codcon = plcre.c60_codcon
				 join orctiporec rec_bco on rec_bco.o15_codigo = cre.c61_codigo
				 join conplanocontabancaria on cre.c61_codcon = conplanocontabancaria.c56_codcon
							   and cre.c61_anousu = conplanocontabancaria.c56_anousu
							   and cre.c61_reduz = conplanocontabancaria.c56_reduz
				 join contabancaria
				   on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
				 join bancoagencia on contabancaria.db83_bancoagencia = bancoagencia.db89_sequencial
				 join db_bancos on bancoagencia.db89_db_bancos = db_bancos.db90_codban

			      where corrente.k12_instit = {$iInstit}
			      and to_char(corrente.k12_data, 'yyyy/mm') = '{$params->folder}'
			      and k12_estorn is true

			      ) as x
			      order by 1,2,3,4,5,6
		   	     ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do Estorno da Despesa Extra.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'             => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numDespesaExtra'               => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'numero'                        => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                          => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                         =>
                    str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'motivo'                        =>
                    str_pad(utf8_decode($oResultado->historico), 255, ' ', STR_PAD_RIGHT),
                'reservado'                     => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.23. CadastroContaBancaria
    public static function getCadastroContaBancaria($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT distinct lpad(tribinst::varchar, 6, 0) AS codunidadegestora,
                        lpad((contabancaria.db83_conta||contabancaria.db83_dvconta)::varchar, 13, 0) AS numero,
                        CASE WHEN saltes.k13_outrosdados->>'conta_ativa' = 'false' THEN 0
                             WHEN saltes.k13_outrosdados->>'conta_ativa' = 'true' THEN 1
                        END AS situacao,
                        lpad(db_bancos.db90_codban::varchar, 3, 0) AS codbanco,
                        lpad((bancoagencia.db89_codagencia||bancoagencia.db89_digito)::varchar, 6, 0) AS numagencia,
                        rpad(substr(conplano.c60_descr, 1, 60)::varchar, 60) AS descricao,
                        CASE WHEN contabancaria.db83_tipoconta = 1 THEN 1
                             WHEN contabancaria.db83_tipoconta = 2 THEN 4
                             WHEN contabancaria.db83_tipoconta = 3 THEN 7
                             ELSE 0
                        END AS tipo,
                        regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') AS cnpjgerencia
                 FROM contabilidade.conplano
                      JOIN contabilidade.conplanoreduz ON conplano.c60_anousu = conplanoreduz.c61_anousu
                       AND conplano.c60_codcon = conplanoreduz.c61_codcon
                      JOIN contabilidade.conplanocontabancaria
                        ON conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
                       AND conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon
                       AND conplanoreduz.c61_reduz = conplanocontabancaria.c56_reduz
                      JOIN caixa.saltes ON conplanoreduz.c61_reduz = saltes.k13_conta
                       AND conplanoreduz.c61_anousu = {$params->data_ano}
                      JOIN configuracoes.contabancaria
                        ON conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
                      JOIN configuracoes.bancoagencia ON contabancaria.db83_bancoagencia = bancoagencia.db89_sequencial
                      JOIN configuracoes.db_bancos ON bancoagencia.db89_db_bancos = db_bancos.db90_codban
                      JOIN configuracoes.db_config ON db_config.codigo = conplanoreduz.c61_instit
                 WHERE conplano.c60_anousu = {$params->data_ano}
                   AND c61_instit = {$iInstit}
                   AND saltes.k13_limite IS NULL
                   AND saltes.k13_outrosdados->>'enviada_sagres' = 'false'";


        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do Cadastro de Conta Bancária.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numero'            => str_pad(utf8_decode($oResultado->numero), 13, 0, STR_PAD_LEFT),
                'situacao'          => str_pad($oResultado->situacao, 1, 0, STR_PAD_LEFT),
                'codBanco'          => str_pad($oResultado->codbanco, 3, 0, STR_PAD_LEFT),
                'numAgencia'        => str_pad(utf8_decode($oResultado->numagencia), 6, 0, STR_PAD_LEFT),
                'descricao'         => str_pad(utf8_decode($oResultado->descricao), 60, 0, STR_PAD_LEFT),
                'tipo'              => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'cnpjGerencia'      => str_pad($oResultado->cnpjgerencia, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.24. RelacionamentoCCorrenteFontePagadora
    public static function getRelacionamentoCCorrenteFontePagadora($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT lpad(tribinst::varchar,6,0) AS codunidadegestora,
               lpad((contabancaria.db83_conta||contabancaria.db83_dvconta)::varchar, 13, 0) AS numcontabancaria,
               lpad((bancoagencia.db89_codagencia||bancoagencia.db89_digito)::varchar, 6, 0) AS numagencia,
               lpad(db_bancos.db90_codban::varchar, 3, 0) AS codbanco,
               CASE WHEN saltes.k13_outrosdados->>'conta_ativa' = 'false' THEN 0
                    WHEN saltes.k13_outrosdados->>'conta_ativa' = 'true' THEN 1
               END AS exerciciofonterecurso,
               substr(orctiporec.o15_recurso, 2, 3) AS codfonterecurso,
               CASE
                   WHEN contabancaria.db83_tipoconta = 1 THEN 1
                   WHEN contabancaria.db83_tipoconta = 2 THEN 4
                   WHEN contabancaria.db83_tipoconta = 3 THEN 7
                   ELSE 0
               END AS tipocontabancaria,
               regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') AS cnpjgerenciacontabancaria
        FROM conplano
        JOIN conplanoreduz ON conplano.c60_anousu = conplanoreduz.c61_anousu
         AND conplano.c60_codcon = conplanoreduz.c61_codcon
        JOIN conplanocontabancaria ON conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
         AND conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon
         AND conplanoreduz.c61_reduz = conplanocontabancaria.c56_reduz
        JOIN saltes ON conplanoreduz.c61_reduz = saltes.k13_conta
         AND conplanoreduz.c61_anousu = {$params->data_ano}
        JOIN contabancaria ON conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
        JOIN bancoagencia ON contabancaria.db83_bancoagencia = bancoagencia.db89_sequencial
        JOIN db_bancos ON bancoagencia.db89_db_bancos = db_bancos.db90_codban
        JOIN orctiporec ON conplanoreduz.c61_codigo = orctiporec.o15_codigo
         AND conplanoreduz.c61_anousu = {$params->data_ano}
        JOIN configuracoes.db_config ON db_config.codigo = conplanoreduz.c61_instit
       WHERE conplano.c60_anousu = {$params->data_ano}
         AND c61_instit = {$iInstit}
         AND saltes.k13_limite IS NULL
         AND saltes.k13_outrosdados->>'enviada_sagres' = 'false'";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Relacionamento CCorrente Fonte Pagadora.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numContaBancaria'          => str_pad(utf8_decode($oResultado->numcontabancaria), 13, 0, STR_PAD_LEFT),
                'numAgencia'                => str_pad(utf8_decode($oResultado->numagencia), 6, 0, STR_PAD_LEFT),
                'codBanco'                  => str_pad(utf8_decode($oResultado->codbanco), 3, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'     => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'           => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'tipoContaBancaria'         => str_pad($oResultado->tipocontabancaria, 1, 0, STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpjgerenciacontabancaria, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.28. PagamentosRestos
    public static function getPagamentosRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c75_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c75_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "WITH pagamentos AS (
                      SELECT lpad(max(tribinst)::varchar, 6, 0) AS codunidadegestora,
                             e60_anousu AS anoemissaoempenho,
                             lpad(lpad(max(o58_orgao)::varchar, 2, 0)||lpad(max(o58_unidade)::varchar, 3, 0), 5, 0)
                                       AS codunidadeorcamentaria,
                             lpad(e60_codemp, 7, 0) AS numempenho,
                             e60_numemp,
                             k12_codmov,
                             corempagemovpagamento.k12_codmov AS numero,
                             TO_CHAR(max(c75_data), 'DDMMYYYY') AS data,
                             lpad(round(sum(k12_valor), 2)::varchar, 16, '0') AS valor,
                             lpad((trim(max(db83_conta))||trim(max(db83_dvconta))),13, '0') AS numcontabancaria,
                             lpad(trim(max(db89_codagencia))||trim(max(db89_digito)),6, '0') AS numagencia,
                             max(db90_codban) AS codbanco,
                             substr(max(orctiporecrp.o15_recurso), 2, 3) AS codfonterecurso,
                     	     lpad(max(orctiporecrp.o15_complemento), 4, 0) AS co,
                             CASE WHEN max(db83_tipoconta) = 1 THEN 1
                                  WHEN max(db83_tipoconta) = 2 THEN 4
                                  WHEN max(db83_tipoconta) = 3 THEN 7
                                  ELSE 0
                             END AS tipocontabancaria,
                             regexp_replace(max(db83_identificador), '[^0-9]', '', 'g') AS cnpjgerenciacontabancaria
                      FROM contabilidade.conlancamemp
                           JOIN contabilidade.conlancam ON conlancam.c70_codlan = conlancamemp.c75_codlan
                           JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
			   JOIN empenho.empresto   ON empempenho.e60_numemp = empresto.e91_numemp
                           JOIN orcamento.orctiporec orctiporecrp ON orctiporecrp.o15_codigo = empresto.e91_recurso
                           JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancamemp.c75_codlan
                           JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                           JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                           JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancamemp.c75_codlan
                           JOIN contabilidade.conlancamcorgrupocorrente
                             ON conlancamcorgrupocorrente.c23_conlancam = conlancamemp.c75_codlan
                           JOIN caixa.corgrupocorrente
                             ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                           JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                              AND corrente.k12_data = corgrupocorrente.k105_data
                                              AND corrente.k12_autent = corgrupocorrente.k105_autent
                                              AND corrente.k12_estorn = FALSE
                           JOIN caixa.corempagemovpagamento ON corempagemovpagamento.k12_id = corrente.k12_id
                                                           AND corempagemovpagamento.k12_data = corrente.k12_data
                                                           AND corempagemovpagamento.k12_autent = corrente.k12_autent
                           JOIN empenho.empageconf ON empageconf.e86_codmov = corempagemovpagamento.k12_codmov
                           JOIN empenho.empagemov ON empagemov.e81_codmov = corempagemovpagamento.k12_codmov
                           JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                                                    AND orcdotacao.o58_coddot = empempenho.e60_coddot
                           JOIN orcamento.orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
                           LEFT JOIN contabilidade.conlancampag ON conlancampag.c82_codlan = conlancamemp.c75_codlan
                           LEFT JOIN contabilidade.conplanocontabancaria
                                  ON conplanocontabancaria.c56_reduz = conlancampag.c82_reduz
                                 AND conplanocontabancaria.c56_anousu = conlancampag.c82_anousu
                           LEFT JOIN configuracoes.contabancaria
                                  ON contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria
                           LEFT JOIN configuracoes.bancoagencia
                                  ON bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                           LEFT JOIN configuracoes.db_bancos ON db_bancos.db90_codban = bancoagencia.db89_db_bancos
                      WHERE {$sWhere}
                        AND e60_instit = {$iInstit}
                        AND e91_anousu = {$params->ano}
                        AND c53_tipo = 30
                      GROUP BY empempenho.e60_anousu, empempenho.e60_codemp,
                               empempenho.e60_numemp, corempagemovpagamento.k12_codmov
                 )

                 SELECT (row_number() over(PARTITION BY e60_numemp, k12_codmov
                                           ORDER BY e60_numemp, k12_codmov))::int AS seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        numero,
                        data,
                        valor,
                        numcontabancaria,
                        numagencia,
                        codbanco,
                        codfonterecurso,
			co,
                        tipocontabancaria,
                        lpad('0', 6) AS numcheque,
                        lpad(' ', 11) AS numdocdebito,
                        lpad(' ', 3) AS codbancocred,
                        lpad(' ', 6) AS numagenciacred,
                        lpad(' ', 13) AS numcontabancariacred,
                        CASE WHEN EXISTS
                                   (SELECT 1
                                    FROM empresto
                                    WHERE e91_numemp = e60_numemp
                                      AND e91_anousu = {$params->ano}) THEN 2
                             ELSE 1
                        END AS exerciciofonterecurso,
                        cnpjgerenciacontabancaria
                 FROM pagamentos ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Pagamentos de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'         => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'    => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'                => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                    => str_pad($oResultado->numero.$oResultado->seq, 7, 0, STR_PAD_LEFT),
                'data'                      => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                     => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'numContaBancaria'          =>
                                  str_pad(utf8_decode(trim($oResultado->numcontabancaria)), 13, '0', STR_PAD_LEFT),
                'numAgencia'                =>
                                  str_pad(utf8_decode(trim($oResultado->numagencia)), 6, '0', STR_PAD_LEFT),
                'codBanco'                  => str_pad(utf8_decode(trim($oResultado->codbanco)), 3, '0', STR_PAD_LEFT),
                'numCheque'                 =>
                                  str_pad(utf8_decode(trim($oResultado->numcheque)), 6, '0', STR_PAD_LEFT),
                'numDocDebito'              =>
                                  str_pad(utf8_decode(trim($oResultado->numdocdebito)), 11, ' ', STR_PAD_LEFT),
                'codBancoCred'              =>
                                  str_pad(utf8_decode(trim($oResultado->codbancocred)), 3, ' ', STR_PAD_LEFT),
                'numAgenciaCred'            =>
                                  str_pad(utf8_decode(trim($oResultado->numagenciacred)), 6, ' ', STR_PAD_LEFT),
                'numContaBancariaCred'      =>
                                  str_pad(utf8_decode(trim($oResultado->numcontabancariacred)), 13, ' ', STR_PAD_LEFT),
                'codFonteRecurso'           => str_pad($oResultado->codfonterecurso, 3, 0, STR_PAD_LEFT),
                'tipoContaBancaria'         => str_pad($oResultado->tipocontabancaria, 1, 0, STR_PAD_LEFT),
                'co'                        => str_pad($oResultado->co, 4, 0, STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpjgerenciacontabancaria, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.29. EstornoPagamentoRestos
    public static function getEstornoPagamentoRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c75_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c75_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "
          WITH estornos AS (
              SELECT codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     e60_numemp,
                     numempenho,
                     numpagamento,
                     numero,
                     data,
                     data_lancto,
                     despesaliquidada,
                     valor,
                     trim(motivo) AS motivo,
                     descricao,
                     ('Lancamento contabil correspondente ao evento ' || descricao ||
                      ', empenho '|| numempenho || '/' || anoemissaoempenho ||' em '|| data_lancto) as motivobasico
              FROM (
                    SELECT lpad(max(tribinst)::VARCHAR, 6, 0) AS codunidadegestora,
                           e60_anousu AS anoemissaoempenho,
                           lpad(max(orcdotacao.o58_orgao)::varchar, 2, '0') ||
                           lpad(max(orcdotacao.o58_unidade)::varchar, 3, '0')
                                                   AS codunidadeorcamentaria,
                           lpad(e60_codemp::varchar, 7, 0) AS numempenho,
                           k12_codmov AS numpagamento,
                           max(e60_numemp) as e60_numemp,
                           to_char(max(c70_data), 'DDMMYYYY') AS DATA,
                           to_char(max(c70_data), 'DD/MM/YYYY') AS data_lancto,
                           sum(round(abs(k12_valor), 2)) AS valor,
                           coalesce(rpad(regexp_replace(max(conlancamcompl.c72_complem),
                                                        E'[ ]+', ' ', 'g'), 120), '') AS motivo,
                           max(conhistdoc.c53_descr) AS descricao,
                           max('S') AS despesaliquidada,
                           lpad(max(c70_codlan), 7, 0) AS numero
                    FROM contabilidade.conlancam
                         JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                         JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                         JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                                                AND empempenho.e60_anousu < {$params->ano}
                         JOIN empenho.empresto   ON empempenho.e60_numemp = empresto.e91_numemp
                         JOIN orcamento.orctiporec orctiporecrp ON orctiporecrp.o15_codigo = empresto.e91_recurso
                         JOIN contabilidade.conlancamcorgrupocorrente
                           ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                         JOIN caixa.corgrupocorrente
                           ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                         JOIN caixa.corempagemovestorno
                           ON corempagemovestorno.k12_id = corgrupocorrente.k105_id
                          AND corempagemovestorno.k12_data = corgrupocorrente.k105_data
                          AND corempagemovestorno.k12_autent = corgrupocorrente.k105_autent
                         JOIN corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                      AND corrente.k12_data = corgrupocorrente.k105_data
                                      AND corrente.k12_autent = corgrupocorrente.k105_autent
                         JOIN empenho.empagemov ON empagemov.e81_codmov = corempagemovestorno.k12_codmov
                         JOIN orcamento.orcdotacao ON orcdotacao.o58_coddot = empempenho.e60_coddot
                                                  AND orcdotacao.o58_anousu = empempenho.e60_anousu
                                                  AND orcdotacao.o58_instit = empempenho.e60_instit
                         JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                         JOIN contabilidade.conlancamcompl ON conlancamcompl.c72_codlan = conlancam.c70_codlan
                    WHERE {$sWhere}
                      AND empempenho.e60_instit = {$iInstit}
                      AND empresto.e91_anousu = {$params->ano}
                      AND conhistdoc.c53_tipo = 31
                    GROUP BY empempenho.e60_anousu, empempenho.e60_codemp,
                             empempenho.e60_numemp, corempagemovestorno.k12_codmov
                   ) AS x
              ),

              estorno_pagamento as (
                SELECT (row_number() over(partition by numempenho, numpagamento
                                          order by numempenho, numpagamento, numero))::int as seq,
                       codunidadegestora,
                       anoemissaoempenho,
                       codunidadeorcamentaria,
                       e60_numemp,
                       numempenho,
                       numpagamento,
                       numero,
                       data,
                       valor,
                       despesaliquidada,
                       CASE WHEN length(motivo) < 50
                                 THEN substr((motivo||' - '||motivobasico), 1, 120)
                            WHEN length(motivo) > 120
                                 THEN substr(motivo, 1, 120)
                            ELSE motivo
                       END AS motivo
                FROM estornos
              )

              select seq,
                     codunidadegestora,
                     anoemissaoempenho,
                     codunidadeorcamentaria,
                     numempenho,
                     case when codmov_novo > 0
                          then codmov_novo
                          else (numpagamento::varchar||seq::varchar)::int
                     end as numpagamento,
                     numero,
                     data,
                     valor,
                     despesaliquidada,
                     motivo,
                     0 AS reservado
              from estorno_pagamento
                   LEFT JOIN empenho.empenhocodmovdepara
                          ON empenhocodmovdepara.numemp = e60_numemp
                         AND empenhocodmovdepara.codmov_atual = (numpagamento::varchar||seq::varchar)::int
            ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Estornos de Pagamentos de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'despesaLiquidada'       => str_pad($oResultado->despesaliquidada, 1, ' ', STR_PAD_RIGHT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.30. CancelamentoRestos
    public static function getCancelamentoRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT lpad(e60_instit::varchar, 6, 0) AS codunidadegestora,
                        e60_anousu AS anoemissaoempenho,
                        lpad(lpad(o58_orgao::varchar, 2, 0)||lpad(o58_unidade::varchar, 3, 0), 5, 0)
                                               AS codunidadeorcamentaria,
                        lpad(e60_codemp, 7, 0) AS numempenho,
                        lpad(c69_codlan, 7, 0) AS numero,
                        TO_CHAR(c69_data, 'DDMMYYYY') AS data,
                        lpad(round(c69_valor, 2)::varchar, 16, 0) AS valor,
                        rpad(c72_complem, 120) AS motivo,
                        'S' AS despesaliquidada,
                        0 AS reservado
                 FROM contabilidade.conlancamval
                      JOIN contabilidade.conlancamemp ON c69_codlan = c75_codlan
                      JOIN contabilidade.conlancamdoc ON c69_codlan = c71_codlan
                      JOIN contabilidade.conhistdoc ON c71_coddoc = c53_coddoc
                      JOIN empenho.empempenho ON c75_numemp = e60_numemp
                      JOIN empenho.empresto ON e60_numemp = e91_numemp
                      JOIN orcamento.orcdotacao ON e60_anousu = o58_anousu
                                               AND e60_coddot = o58_coddot
                      LEFT JOIN contabilidade.conlancamcompl ON c69_codlan = c72_codlan
                 WHERE to_char(c69_data, 'yyyy/mm') = '{$params->folder}'
                   AND e60_instit = {$iInstit}
                   AND c53_tipo = 11
                   AND c69_ordem = 1 ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Cancelamentos de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'motivo'                 => str_pad(utf8_decode($oResultado->motivo), 120, ' ', STR_PAD_RIGHT),
                'despesaLiquidada'       => str_pad(utf8_decode($oResultado->despesaliquidada), 1, ' ', STR_PAD_RIGHT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.31. LiquidacaoRestos
    public static function getLiquidacaoRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $e171_dadosdefault = '{"codigo_agrupamento": " "}';
        $sSql = "SELECT lpad(db_config.tribinst::varchar,6,0) AS codunidadegestora,
                        e69_outrosdados,
                        coalesce(e171_dados, '{$e171_dadosdefault}') as e171_dados,
                        e60_anousu AS anoemissaoempenho,
                        lpad(orcdotacao.o58_orgao::varchar, 2, '0') || lpad(orcdotacao.o58_unidade::varchar, 3, '0')
                            AS codunidadeorcamentaria,
                        e60_codemp AS numempenho,
                        (select min(e82_codmov)
                          from empord
                               join conlancamord on e82_codord = c80_codord
                               join conlancamdoc on c80_codlan = c71_codlan
                               join conhistdoc on c71_coddoc = c53_coddoc
                         where c80_codlan = c70_codlan
                           and c53_tipo = 20) AS numero,
                        TO_CHAR(c70_data, 'DDMMYYYY') AS data,
                        e69_numero AS numnotafiscal,
                        TO_CHAR(e69_dtnota, 'DDMMYYYY') AS datanotafiscal,
                        round(e70_valor,2) AS valornotafiscal,
                        round(c70_valor,2) AS valor,
                        0 AS reservado
                FROM empenho.empempenho
		     JOIN empenho.empresto ON e60_numemp = e91_numemp
                     JOIN contabilidade.conlancamemp ON c75_numemp = empempenho.e60_numemp
                     JOIN contabilidade.conlancam ON c70_codlan = c75_codlan
                     JOIN contabilidade.conlancamnota ON c66_codlan = c70_codlan
                     JOIN empenho.empnota ON c66_codnota = e69_codnota
                     JOIN empenho.pagordemnota ON e71_codnota = c66_codnota
                     JOIN empenho.empnotaele ON e69_codnota = e70_codnota
                     JOIN contabilidade.conlancamdoc ON c71_codlan = c70_codlan
                     JOIN contabilidade.conhistdoc ON c53_coddoc = c71_coddoc
                     JOIN protocolo.cgm ON cgm.z01_numcgm = empempenho.e60_numcgm
                     JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                     JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                                              AND orcdotacao.o58_coddot = empempenho.e60_coddot
                                              AND orcdotacao.o58_instit = empempenho.e60_instit
                     JOIN orcamento.orcorgao ON orcorgao.o40_anousu = orcdotacao.o58_anousu
                                            AND orcorgao.o40_orgao = orcdotacao.o58_orgao
                     JOIN orcamento.orcunidade ON orcunidade.o41_anousu = orcdotacao.o58_anousu
                                              AND orcunidade.o41_orgao = orcdotacao.o58_orgao
                                              AND orcunidade.o41_unidade = orcdotacao.o58_unidade
                     LEFT JOIN empenho.empempenhooutrosdados ON e171_numemp = e60_numemp
                WHERE e60_instit = {$iInstit}
                  AND e91_anousu = {$params->ano}
                  AND {$sWhere}
                  AND c53_tipo = 20
                 ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações das Liquidações de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            $e69_outrosdados = json_decode($oResultado->e69_outrosdados);
            $e171_dados = json_decode($oResultado->e171_dados);

            if (!isset($e171_dados->codigo_agrupamento)) {
                $e171_dados->codigo_agrupamento = " ";
            }

            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'data'                   => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'tipoNotaFiscal'         => str_pad($e69_outrosdados->tipo_nota, 2, 0, STR_PAD_LEFT),
                'numChaveNotaFiscal'     => str_pad($e69_outrosdados->chave_nota, 44, 0, STR_PAD_LEFT),
                'numNotaFiscal'          =>
                    str_pad(utf8_decode(substr($oResultado->numnotafiscal, 0, 15)), 15, ' ', STR_PAD_RIGHT),
                'serieNotaFiscal'        =>
                    str_pad(utf8_decode(substr($e69_outrosdados->serie_nota, 0, 12)), 12, ' ', STR_PAD_RIGHT),
                'dataNotaFiscal'         => str_pad(rmSpecial($oResultado->datanotafiscal), 8, 0, STR_PAD_LEFT),
                'valorNotaFiscal'        =>
                    str_pad(str_replace(".", ",", $oResultado->valornotafiscal), 16, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'codAgrupamentoFolha'    =>
                    str_pad(utf8_decode($e171_dados->codigo_agrupamento), 10, ' ', STR_PAD_RIGHT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.33. RetencaoRestos
    public static function getRetencaoRestos($params)
    {

        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "SELECT (row_number() over(PARTITION BY numempenho, numpagamento
                                           ORDER BY numempenho, numpagamento, c70_codlan))::int AS seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        numpagamento,
                        valor,
                        tipo,
                        0 as reservado
                 FROM (SELECT DISTINCT lpad(tribinst::varchar, 6, 0) AS codunidadegestora,
                                       e60_anousu AS anoemissaoempenho,
                                       lpad(lpad(o58_orgao::varchar, 2, 0)||lpad(o58_unidade::varchar, 3, 0), 5, 0)
                                                AS codunidadeorcamentaria,
                                       lpad(trim(e60_codemp), 7, 0) AS numempenho,
                                       corempagemovpagamento.k12_codmov AS numpagamento,
                                       lpad(round(corrente.k12_valor, 2)::varchar, 16, 0) AS valor,
                                       CASE WHEN e21_retencaotipocalc in (1,2) THEN 2
                                            WHEN e21_retencaotipocalc in (7,3,4) THEN 3
                                            WHEN e21_retencaotipocalc in (5) THEN 1
                                            WHEN e21_retencaotipocalc in (6) THEN 5
                                       END AS tipo,
                                       conlancam.c70_codlan
                       FROM contabilidade.conlancam
                            JOIN contabilidade.conlancamretencao
                              ON conlancamretencao.c127_conlancam = conlancam.c70_codlan
                            JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                                                         AND conhistdoc.c53_tipo = 30
                            JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                            JOIN empenho.retencaotiporec
                              ON retencaotiporec.e21_sequencial = conlancamretencao.c127_retencaotiporec
                            JOIN empenho.empempenho ON empempenho.e60_numemp = conlancamemp.c75_numemp
                            JOIN empenho.empresto ON empempenho.e60_numemp = empresto.e91_numemp
                            JOIN empenho.pagordem ON c80_codord = e50_codord
                            JOIN empenho.empord ON e82_codord = c80_codord
                            JOIN empenho.empageconf ON empageconf.e86_codmov = empord.e82_codmov
                                                   AND empageconf.e86_data = conlancam.c70_data
                            JOIN empenho.empagemov ON empagemov.e81_codmov = empord.e82_codmov
                            JOIN contabilidade.conlancamcorgrupocorrente
                              ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                            JOIN caixa.corgrupocorrente
                              ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                             AND corgrupocorrente.k105_corgrupotipo = 2
                            JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                               AND corrente.k12_data = corgrupocorrente.k105_data
                                               AND corrente.k12_autent = corgrupocorrente.k105_autent
                                               AND corrente.k12_estorn = FALSE
                                               AND corrente.k12_instit = empempenho.e60_instit
                            JOIN corempagemovpagamento
                              ON corempagemovpagamento.k12_id = corgrupocorrente.k105_id
                             AND corempagemovpagamento.k12_data = corgrupocorrente.k105_data
                             AND corempagemovpagamento.k12_autent = corgrupocorrente.k105_autent
                            JOIN orcamento.orcdotacao ON e60_anousu = o58_anousu
                                                     AND e60_coddot = o58_coddot
                            JOIN configuracoes.db_config ON codigo = e60_instit
                       WHERE {$sWhere}
			 AND e60_instit = {$iInstit}
			 AND e91_anousu = {$params->ano}
                         AND c71_coddoc in (6008,6010)
                 ) as x ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Retenções de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento.$oResultado->seq, 7, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'tipoRetencao'           => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.34. EstornoRetencaoRestos
    public static function getEstornoRetencaoRestos($params)
    {
        $iInstit = db_getsession("DB_instit");

        if ($params->periodo == 'diario') {
            $sWhere = " c70_data = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}' ";
        } else {
            $sWhere = " to_char(c70_data, 'yyyy/mm') = '{$params->folder}' ";
        }

        $sSql = "SELECT (row_number() over(partition by numempenho, numpagamento
                                           order by numempenho, numpagamento, c70_codlan))::int as seq,
                        codunidadegestora,
                        anoemissaoempenho,
                        codunidadeorcamentaria,
                        numempenho,
                        numpagamento,
                        tiporetencao,
                        numero,
                        valor,
                        reservado
                 FROM (SELECT DISTINCT lpad(tribinst::varchar,6,0) AS codunidadegestora,
                              e60_anousu AS anoemissaoempenho,
                              lpad(lpad(o58_orgao::varchar,2,0)||lpad(o58_unidade::varchar,3,0),5,0)
                                         AS codunidadeorcamentaria,
                              lpad(e60_codemp,7,0) AS numempenho,
                              corempagemovestorno.k12_codmov AS numpagamento,
                              CASE WHEN e21_retencaotipocalc IN (1,2) THEN 2
                                   WHEN e21_retencaotipocalc IN (7,3,4) THEN 3
                                   WHEN e21_retencaotipocalc IN (5) THEN 1
                                   WHEN e21_retencaotipocalc IN (6) THEN 5
                              END  AS tiporetencao,
                              lpad(e71_codnota,7,0) AS numero,
                              lpad(round(abs(corrente.k12_valor), 2)::varchar, 16, 0) AS valor,
                              lpad('',6) AS reservado,
                              c70_codlan
                       FROM contabilidade.conlancam
                            JOIN contabilidade.conlancamretencao
                              ON conlancamretencao.c127_conlancam = conlancam.c70_codlan
                            JOIN contabilidade.conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                                                         AND conhistdoc.c53_tipo = 31
                            JOIN contabilidade.conlancamord ON conlancamord.c80_codlan = conlancam.c70_codlan
                            JOIN contabilidade.conlancamcorgrupocorrente
                              ON conlancamcorgrupocorrente.c23_conlancam = conlancam.c70_codlan
                            JOIN empenho.retencaotiporec
                              ON retencaotiporec.e21_sequencial = conlancamretencao.c127_retencaotiporec
                            JOIN empenho.pagordem ON pagordem.e50_codord = conlancamord.c80_codord
                            JOIN empenho.pagordemnota ON pagordemnota.e71_codord = pagordem.e50_codord
                            JOIN empenho.empord ON empord.e82_codord = conlancamord.c80_codord
                            JOIN empenho.empageconf ON empageconf.e86_codmov = empord.e82_codmov
                                                   AND empageconf.e86_data = conlancam.c70_data
                            JOIN empenho.empagemov ON empagemov.e81_codmov = empord.e82_codmov
                            JOIN empenho.empempenho ON empempenho.e60_numemp = pagordem.e50_numemp
                            JOIN empenho.empresto ON empresto.e91_numemp = empempenho.e60_numemp
                                                 AND empresto.e91_anousu = {$params->ano}
                            JOIN caixa.corgrupocorrente
                              ON corgrupocorrente.k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente
                             AND corgrupocorrente.k105_corgrupotipo = 5
                            JOIN caixa.corrente ON corrente.k12_id = corgrupocorrente.k105_id
                                               AND corrente.k12_data = corgrupocorrente.k105_data
                                               AND corrente.k12_autent = corgrupocorrente.k105_autent
                                               AND corrente.k12_estorn = true
                                               AND corrente.k12_instit = empempenho.e60_instit
                            JOIN corempagemovestorno ON corempagemovestorno.k12_id = corgrupocorrente.k105_id
                                                      AND corempagemovestorno.k12_data = corgrupocorrente.k105_data
                                                      AND corempagemovestorno.k12_autent = corgrupocorrente.k105_autent
                            JOIN orcamento.orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                                                     AND orcdotacao.o58_coddot = empempenho.e60_coddot
                            JOIN configuracoes.db_config ON db_config.codigo = empempenho.e60_instit
                       WHERE {$sWhere}
                         AND e60_instit = {$iInstit}
                         AND c71_coddoc in (6009, 6011)
                      ) as x ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Estorno de Retenções de Restos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'anoEmissaoEmpenho'      => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numPagamento'           => str_pad($oResultado->numpagamento.$oResultado->seq, 7, 0, STR_PAD_LEFT),
                'tipoRetencao'           => str_pad($oResultado->tiporetencao, 1, 0, STR_PAD_LEFT),
                'numero'                 => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'valor'                  => str_pad(str_replace(".", ",", $oResultado->valor), 16, 0, STR_PAD_LEFT),
                'reservado'              => str_pad(sprintf('%06d', $oResultado->reservado), 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.35. Fornecedores
    public static function getFornecedores($params)
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "
                 SELECT DISTINCT ON (cpfcnpj) *
                 FROM (
                       SELECT DISTINCT
                             (SELECT lpad(tribinst::varchar, 6, 0)
                              FROM configuracoes.db_config
                              WHERE codigo = {$iInstit}) AS codunidadegestora,
                              z01_cgccpf AS cpfcnpj,
                              z01_nome AS nome,
                              CASE WHEN length(trim(z01_cgccpf)) = 14 THEN 2
                                   ELSE 1
                              END AS tipo,
                              lpad(0, 6) AS reservado
                       FROM protocolo.cgm
                            JOIN empenho.empempenho ON empempenho.e60_numcgm = cgm.z01_numcgm
                       WHERE empempenho.e60_instit = {$iInstit}
                         AND empempenho.e60_emiss = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                       UNION
                       SELECT DISTINCT
                             (SELECT lpad(tribinst::varchar, 6, 0)
                              FROM configuracoes.db_config
                              WHERE codigo = {$iInstit}) AS codunidadegestora,
                              z01_cgccpf AS cpfcnpj,
                              z01_nome AS nome,
                              CASE WHEN length(trim(z01_cgccpf)) = 14 THEN 2
                                   ELSE 1
                              END AS tipo,
                              lpad(0, 6) AS reservado
                       FROM protocolo.cgm
                            JOIN caixa.placaixarec ON placaixarec.k81_numcgm = cgm.z01_numcgm
                            JOIN caixa.placaixa ON placaixa.k80_codpla = placaixarec.k81_codpla
                       WHERE placaixa.k80_instit = {$iInstit}
                         AND ( cgm.z01_cadast = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                               OR
                               cgm.z01_ultalt = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                             )
                       UNION
                       SELECT DISTINCT
                             (SELECT lpad(tribinst::varchar, 6, 0)
                              FROM configuracoes.db_config
                              WHERE codigo = {$iInstit}) AS codunidadegestora,
                              z01_cgccpf AS cpfcnpj,
                              z01_nome AS nome,
                              CASE WHEN length(trim(z01_cgccpf)) = 14 THEN 2
                                   ELSE 1
                              END AS tipo,
                              lpad(0, 6) AS reservado
                       FROM protocolo.cgm
                            JOIN caixa.slipnum ON slipnum.k17_numcgm = cgm.z01_numcgm
                            JOIN caixa.slip ON slip.k17_codigo = slipnum.k17_codigo
                            JOIN caixa.sliptipooperacaovinculo
                              ON sliptipooperacaovinculo.k153_slip = slipnum.k17_codigo
                             AND sliptipooperacaovinculo.k153_slipoperacaotipo < 500
                       WHERE slip.k17_instit = {$iInstit}
                         AND ( cgm.z01_cadast = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                               OR
                               cgm.z01_ultalt = '{$params->data_ano}-{$params->data_mes}-{$params->data_dia}'
                             )
                        ) AS x
                 ORDER BY 2 ;
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Fornecedores.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'cpfCnpj'           => str_pad($oResultado->cpfcnpj, 14, 0, STR_PAD_LEFT),
                'nome'              => str_pad(utf8_decode($oResultado->nome), 80, ' ', STR_PAD_RIGHT),
                'tipo'              => $oResultado->tipo,
                'reservado'         => sprintf('%06d', $oResultado->reservado),
            );
        });
    }

    // 4.36. Ordenador
    public static function getOrdenador()
    {
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT lpad(tribinst::varchar,6,0) AS codUnidadeGestora,
                        z01_cgccpf AS cpfcnpj,
                        rpad(z01_nome::varchar,50,' '::varchar) AS nome,
                        0 AS reservado
                 FROM contabilidade.sagresordenadordespesa
                      JOIN protocolo.cgm ON z01_numcgm = c139_cgm
                      JOIN configuracoes.db_config ON codigo = c139_instit
                 WHERE c139_instit = {$iInstit}
                   AND c139_ativo = 't'
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do Ordenador.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora' => $oResultado->codunidadegestora,
                'cpfCnpj'           => $oResultado->cpfcnpj,
                'nome'              => utf8_decode($oResultado->nome),
                'reservado'         => sprintf('%06d', $oResultado->reservado),
            );
        });
    }

    // 4.40. RestosInscritos
    public static function getRestosInscritos($params)
    {

        $sWhereZerado = '';
        if ($params->mes <> '01') {
            $sWhereZerado = ' AND 1 = 2';
        }
        $iInstit = db_getsession("DB_instit");

        $sSql = "SELECT tribinst AS codunidadegestora,
                        rpad(nomeinst, 250) AS descunidadegestora,
                        e60_anousu AS anoemissaoempenho,
                        rpad(lpad(o58_orgao::varchar, 2, 0)||lpad(o58_unidade::varchar, 3, 0), 5, '0')
                                             AS codunidadeorcamentaria,
                        rpad(o41_descr, 250) AS descunidadeorcamentaria,
                        lpad(o58_funcao::varchar, 2, 0)AS codfuncao,
                        rpad(o52_descr, 100) AS descfuncao,
                        lpad(o58_subfuncao::varchar, 3, 0) AS codsubfuncao,
                        rpad(o53_descr, 100) AS descsubfuncao,
                        lpad(o58_programa::varchar, 4, 0) AS codprograma,
                        rpad(o54_descr, 300) AS descprograma,
                        lpad(o58_projativ::varchar, 4, 0) AS codacao,
                        rpad(o55_descr, 500) AS descacao,
                        substr(des.o56_elemento, 2, 6) AS codclassificacao,
                        substr(des.o56_elemento, 2, 1) AS codcategoriaeconomica,

                       (SELECT x.o56_descr
                        FROM orcamento.orcelemento x
                        WHERE x.o56_anousu = des.o56_anousu
                          AND x.o56_elemento = rpad(substr(des.o56_elemento, 1, 2), 13, '0')
                        LIMIT 1) AS desccategoriaeconomica,
                        substr(des.o56_elemento, 3, 1) AS codnaturezadespesa,

                       (SELECT x.o56_descr
                        FROM orcamento.orcelemento x
                        WHERE x.o56_anousu = des.o56_anousu
                          AND x.o56_elemento = rpad(substr(des.o56_elemento, 1, 3), 13, '0')
                        LIMIT 1) AS descnaturezadespesa,
                        substr(des.o56_elemento, 4, 2) AS codmodalidadedespesa,

                       (SELECT x.o56_descr
                        FROM orcamento.orcelemento x
                        WHERE x.o56_anousu = des.o56_anousu
                          AND x.o56_elemento = rpad(substr(des.o56_elemento, 1, 5), 13, '0')
                        LIMIT 1) AS descmodalidadedespesa,
                        substr(des.o56_elemento, 6, 2) AS codelementodespesa,

                       (SELECT x.o56_descr
                        FROM orcamento.orcelemento x
                        WHERE x.o56_anousu = des.o56_anousu
                          AND x.o56_elemento = rpad(substr(des.o56_elemento, 1, 7), 13, '0')
                        LIMIT 1) AS descelementodespesa,

                        substr(des.o56_elemento, 8, 3) AS codsubelementodespesa,
                        rpad(des.o56_descr, 100) AS descsubelementodespesa,
                        lpad(e60_codemp::varchar, 7, 0)AS numempenho,
                        to_char(e60_emiss, 'DDMMYYYY') AS dataempenho,
                        z01_cgccpf AS cpfcnpjfornecedor,
                        rpad(z01_nome, 150) AS descfornecedor,
                        o15_recurso AS tipofonterecurso,
                        1 AS descfonterecurso,
                        rpad(substr(e60_resumo,1,1000), 1000) AS historicoempenho,
                        lpad(round((e91_vlremp-e91_vlranu-e91_vlrpag),2)::varchar, 16, 0) AS valorinscrito,
                        lpad(round((e91_vlrliq-e91_vlrpag),2)::varchar, 16, 0) AS valorprocessado,
                        lpad(round((e91_vlremp-e91_vlranu-e91_vlrliq),2)::varchar, 16, 0) AS valornaoprocessado,
                        lpad(e60_instit::varchar, 6, 0) AS codunidadegestoraorigem,
                        substr(o15_recurso, 2, 3) AS codfonterecursoatual,
                        lpad(o15_complemento, 4, 0) AS coatual
                 FROM empenho.empresto
                      JOIN empenho.empempenho ON e91_numemp = e60_numemp
                      JOIN empenho.empelemento ON e60_numemp = e64_numemp
                      JOIN orcamento.orcdotacao ON e60_anousu = o58_anousu
                                               AND e60_coddot = o58_coddot
                      JOIN orcamento.orcelemento ele ON o58_anousu = ele.o56_anousu
                                                    AND o58_codele = ele.o56_codele
                      JOIN orcamento.orcelemento des ON des.o56_codele = e64_codele
                                                    AND des.o56_anousu = e60_anousu
                      JOIN configuracoes.db_config ON e60_instit = codigo
                      JOIN protocolo.cgm ON e60_numcgm = z01_numcgm
                      JOIN orcamento.orcunidade ON o58_anousu = o41_anousu
                                               AND o58_orgao = o41_orgao
                                               AND o58_unidade = o41_unidade
                      JOIN orcamento.orcfuncao ON o58_funcao = o52_funcao
                      JOIN orcamento.orcsubfuncao ON o58_subfuncao = o53_subfuncao
                      JOIN orcamento.orcprograma ON o58_anousu = o54_anousu
                                                AND o58_programa = o54_programa
                      JOIN orcamento.orcprojativ ON o58_anousu = o55_anousu
                                                AND o58_projativ = o55_projativ
                      JOIN orcamento.orctiporec ON o58_codigo = o15_codigo
                 WHERE e91_anousu = {$params->ano}
                   AND e60_instit = {$iInstit} {$sWhereZerado}";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Restos Inscritos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'       => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'descUnidadeGestora'      =>
                          str_pad(utf8_decode($oResultado->descunidadegestora), 250, ' ', STR_PAD_RIGHT),
                'anoEmissaoEmpenho'       => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'  => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'descUnidadeOrcamentaria' =>
                          str_pad(utf8_decode($oResultado->descunidadeorcamentaria), 250, ' ', STR_PAD_RIGHT),
                'codFuncao'               => str_pad($oResultado->codfuncao, 2, 0, STR_PAD_LEFT),
                'descFuncao'              => str_pad(utf8_decode($oResultado->descfuncao), 100, ' ', STR_PAD_RIGHT),
                'codSubfuncao'            => str_pad($oResultado->codsubfuncao, 3, 0, STR_PAD_LEFT),
                'descSubfuncao'           => str_pad(utf8_decode($oResultado->descsubfuncao), 100, ' ', STR_PAD_RIGHT),
                'codPrograma'             => str_pad($oResultado->codprograma, 4, 0, STR_PAD_LEFT),
                'descPrograma'            => str_pad(utf8_decode($oResultado->descprograma), 300, ' ', STR_PAD_RIGHT),
                'codAcao'                 => str_pad($oResultado->codacao, 4, 0, STR_PAD_LEFT),
                'descAcao'                => str_pad(utf8_decode($oResultado->descacao), 500, ' ', STR_PAD_RIGHT),
                'codClassificacao'        => str_pad($oResultado->codclassificacao, 6, 0, STR_PAD_LEFT),
                'codCategoriaEconomica'   => str_pad($oResultado->codcategoriaeconomica, 1, 0, STR_PAD_LEFT),
                'descCategoriaEconomica'  =>
                           str_pad(utf8_decode($oResultado->desccategoriaeconomica), 50, ' ', STR_PAD_RIGHT),
                'codNaturezaDespesa'      => str_pad($oResultado->codnaturezadespesa, 1, 0, STR_PAD_LEFT),
                'descNaturezaDespesa'     =>
                           str_pad(utf8_decode($oResultado->descnaturezadespesa), 50, ' ', STR_PAD_RIGHT),
                'codModalidadeDespesa'    => str_pad($oResultado->codmodalidadedespesa, 2, 0, STR_PAD_LEFT),
                'descModalidadeDespesa'   =>
                           str_pad(utf8_decode($oResultado->descmodalidadedespesa), 100, ' ', STR_PAD_RIGHT),
                'codElementoDespesa'      => str_pad($oResultado->codelementodespesa, 2, 0, STR_PAD_LEFT),
                'descElementoDespesa'     =>
                           str_pad(utf8_decode($oResultado->descelementodespesa), 100, ' ', STR_PAD_RIGHT),
                'codSubelementoDespesa'   => str_pad($oResultado->codsubelementodespesa, 3, 0, STR_PAD_LEFT),
                'descSubelementoDespesa'  =>
                           str_pad(utf8_decode($oResultado->descsubelementodespesa), 100, ' ', STR_PAD_RIGHT),
                'numEmpenho'              => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'dataEmpenho'             => str_pad($oResultado->dataempenho, 8, 0, STR_PAD_LEFT),
                'cpfCnpjFornecedor'       => str_pad($oResultado->cpfcnpjfornecedor, 14, 0, STR_PAD_LEFT),
                'descFornecedor'          =>
                               str_pad(utf8_decode($oResultado->descfornecedor), 150, ' ', STR_PAD_RIGHT),
                'tipoFonteRecurso'        => str_pad($oResultado->tipofonterecurso, 4, 0, STR_PAD_LEFT),
                'descFonteRecurso'        =>
                               str_pad(utf8_decode($oResultado->descfonterecurso), 150, ' ', STR_PAD_RIGHT),
                'historicoEmpenho'        =>
                               str_pad(utf8_decode($oResultado->historicoempenho), 1000, ' ', STR_PAD_RIGHT),
                'valorInscrito'           =>
                               str_pad(str_replace(".", ",", $oResultado->valorinscrito), 16, 0, STR_PAD_LEFT),
                'valorProcessado'         =>
                               str_pad(str_replace(".", ",", $oResultado->valorprocessado), 16, 0, STR_PAD_LEFT),
                'valorNaoProcessado'      =>
                               str_pad(str_replace(".", ",", $oResultado->valornaoprocessado), 16, 0, STR_PAD_LEFT),
                'codUnidadeGestoraOrigem' => str_pad($oResultado->codunidadegestoraorigem, 6, 0, STR_PAD_LEFT),
                'codFonteRecursoAtual'    => str_pad($oResultado->codfonterecursoatual, 3, 0, STR_PAD_LEFT),
                'coAtual'                 => str_pad($oResultado->coatual, 4, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.37 - RelacionamentoEmpenhoObra
    public static function getRelacionamentoEmpenhoObra($params)
    {
        $iInstit = db_getsession('DB_instit');
        $e171_dadosdefault = '{"geo_obra": " "}';
        $sSql = "with empenhos as (

                     SELECT tribinst AS codunidadegestora,
                            e60_anousu AS anoemissao,
                            coalesce(e171_dados, '{$e171_dadosdefault}') as e171_dados,
                            Lpad(Lpad(o58_orgao:: varchar,2,0)|| Lpad(o58_unidade::varchar,3,0),5,0)
                                   AS codunidadeorcamentaria,
                            Lpad('0',6, '0') AS reservado,
                            Lpad(e60_codemp::varchar,7,0) AS numempenho,
                            1 AS numobra
                     FROM empempenho
                          JOIN empelemento ON e60_numemp = e64_numemp
                          JOIN orcdotacao ON e60_anousu = o58_anousu
                                         AND e60_coddot = o58_coddot
                          JOIN orcelemento eledes ON eledes.o56_codele = e64_codele
                                                 AND eledes.o56_anousu = e60_anousu
                          JOIN sagresordenadordespesa ON c139_instit = e60_instit
                          JOIN configuracoes.db_config ON codigo = e60_instit
                          LEFT JOIN empenho.empempenhooutrosdados ON e171_numemp = e60_numemp
                     WHERE to_char(e60_emiss, 'yyyy/mm') = '{$params->folder}'
                       AND e60_instit = {$iInstit}
                       AND c139_ativo = 't'
                       AND Substr(eledes.o56_elemento,6,2) = '51'
                 )

                 select empenhos.codunidadegestora,
                        empenhos.codunidadeorcamentaria,
                        empenhos.numempenho,
                        empenhos.e171_dados,
                        empenhos.reservado
                 from empenhos ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Empenhos de Obras.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            $e171_dados = json_decode($oResultado->e171_dados);

            if (!isset($e171_dados->geo_obra)) {
                $e171_dados->geo_obra = " ";
            }

            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'codUnidadeGestoraObra'  => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numObra'                => str_pad(utf8_decode($e171_dados->geo_obra), 8, ' ', STR_PAD_RIGHT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.38 - RelacionamentoEmpenhoLicitacao
    public static function getRelacionamentoEmpenhoLicitacao($params)
    {
        $iInstit = db_getsession('DB_instit');
        $sSql = "

            with empenhos as (

                SELECT tribinst AS codunidadegestora,
                       e60_anousu AS anoemissao,
                       lpad(Lpad(o58_orgao:: varchar,2,0)|| Lpad(o58_unidade::varchar,3,0),5,0)
                                 AS codunidadeorcamentaria,
                       lpad('0',6, '0') AS reservado,
                       case when lpad(e60_codcom::varchar,2,0) = '29' then '13'
                        else lpad(e60_codcom::varchar,2,0) end AS modalidadelicitacao,
                       lpad(trim(replace(e60_numerol, '/','')), 9, '0') AS numlicitacao,
		       lpad(e60_codemp::varchar,7,0) AS numempenho,
		       case
			when ( ( e60_instit = 7 and substr(e60_numerol,1,2) <> '16' ) or
		  	       ( e60_instit = 9 and substr(e60_numerol,1,2) <> '25' )
			     )
			then ( select a.tribinst from db_config a where a.codigo = 1 )
			else tribinst
			end as codunidadegestoralic
                FROM empempenho
                     JOIN empelemento ON e60_numemp = e64_numemp
                     JOIN orcdotacao ON e60_anousu = o58_anousu
                                    AND e60_coddot = o58_coddot
                     JOIN db_config ON e60_instit = codigo
                     JOIN sagresordenadordespesa ON db_config.codigo = c139_instit
                     LEFT JOIN empenho.empempenhooutrosdados ON e171_numemp = e60_numemp
                WHERE to_char(e60_emiss, 'yyyy/mm') = '{$params->folder}'
                 AND e60_instit = {$iInstit}
                 AND c139_ativo = 't'
                 AND Lpad(e60_codcom::varchar,2,0) <> '09'

            )

	   select empenhos.codunidadegestora,
		  empenhos.codunidadegestoralic,
                  empenhos.codunidadeorcamentaria,
                  empenhos.numempenho,
                  empenhos.numlicitacao,
                  empenhos.modalidadelicitacao,
                  empenhos.reservado as reservado
             from empenhos ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Empenhos de Licitações.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'           => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'      => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_LEFT),
                'numEmpenho'                  => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'codUnidadeGestoraLicitacao'  => str_pad($oResultado->codunidadegestoralic, 6, 0, STR_PAD_LEFT),
                'numLicitacao'                => str_pad($oResultado->numlicitacao, 9, ' ', STR_PAD_RIGHT),
                'modalidadeLicitacao'         => str_pad($oResultado->modalidadelicitacao, 2, 0, STR_PAD_LEFT),
                'reservado'                   => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.39 - RelacionamentoLiquidacaoCodigoAgrupamentoFolhaPagamento
    public static function getRelacionamentoLiquidacaoCodigoAgrupamentoFolhaPagamento($params)
    {
        $iInstit = db_getsession('DB_instit');
        $e172_dadosdefault = '{"codigo_agrupamento": ""}';

        $sSql = "
	    with liquidacoes as (

		    select
			   lpad(db_config.tribinst::varchar,6,0) as codunidadegestora,
                           coalesce(e172_dados, '{$e172_dadosdefault}') as e172_dados,
                           '{$params->mes}' as mes,
                           e60_anousu as anoemissaoempenho,
                           lpad(orcdotacao.o58_orgao::varchar, 2, '0')||lpad(orcdotacao.o58_unidade::varchar, 3, '0')
                                  as codunidadeorcamentaria,
			   e60_codemp as numempenho,
			   e82_codord,
			   (select min(e82_codmov)
			     from empord a
				  join conlancamord b on a.e82_codord = b.c80_codord
				  join conlancamdoc c on b.c80_codlan = c.c71_codlan
				  join conhistdoc d on c.c71_coddoc = d.c53_coddoc
			    where b.c80_codlan = c70_codlan
			      and d.c53_tipo = 20) AS numero,
			   0 as reservado
                    from empenho.empempenho
                         join configuracoes.db_config on db_config.codigo = empempenho.e60_instit
                         join conlancamemp on c75_numemp = e60_numemp
                         join contabilidade.conlancam on c70_codlan = c75_codlan
                         join contabilidade.conlancamdoc on c71_codlan = c70_codlan
                         join contabilidade.conhistdoc on c53_coddoc = c71_coddoc
                         join conlancamord on c80_codlan = c70_codlan
                         join empenho.pagordem on e50_codord = c80_codord
                         join empord on e82_codord = e50_codord
                         join empagemov on e81_codmov = e82_codmov and e81_cancelado is null
                         join contabilidade.conlancamnota on c66_codlan = c70_codlan
                         join empenho.empnota on c66_codnota = e69_codnota
                         join empenho.pagordemnota on e71_codnota = c66_codnota
                         join empenho.empnotaele on e69_codnota = e70_codnota
                         join orcamento.orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
                                                  and orcdotacao.o58_coddot = empempenho.e60_coddot
                                                  and orcdotacao.o58_instit = empempenho.e60_instit
                         join orcamento.orcelemento on orcdotacao.o58_anousu = orcelemento.o56_anousu
                                                   and orcdotacao.o58_codele = orcelemento.o56_codele
                         left join empenho.pagordemoutrosdados on e172_pagordem = e50_codord
                    where e60_instit = {$iInstit}
                      and to_char(c70_data, 'yyyy/mm') = '{$params->folder}'
                      and c53_tipo = 20
                      and substr(orcelemento.o56_elemento,3,1) = '1'
		      and substr(orcelemento.o56_elemento,6,2) in ( '01', '03', '04', '05', '11', '16', '34' )

                 )

                 select distinct liquidacoes.codunidadegestora,
                        liquidacoes.codunidadeorcamentaria,
                        liquidacoes.numempenho,
                        liquidacoes.numero,
                        liquidacoes.mes,
                        liquidacoes.e172_dados,
                        liquidacoes.reservado
                 from liquidacoes
                 order by numempenho, numero ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos Empenhos de Agrupamento da Folha de Pagamento.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            $e172_dados = json_decode($oResultado->e172_dados);

            if (!isset($e172_dados->codigo_agrupamento)) {
                $e172_dados->codigo_agrupamento = "";
            }

            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_RIGHT),
                'numEmpenho'             => str_pad($oResultado->numempenho, 7, 0, STR_PAD_LEFT),
                'numLiquidacao'          => str_pad($oResultado->numero, 7, 0, STR_PAD_LEFT),
                'codAgrupamentoFolha'    => str_pad($oResultado->mes, 2, 0, STR_PAD_LEFT).
                               str_pad(utf8_decode($e172_dados->codigo_agrupamento), 8, 0, STR_PAD_LEFT),
                'reservado'              => str_pad($oResultado->reservado, 6, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.47 - SaldoMensalCoConciliado
    public static function getSaldoMensalCoConciliado($params)
    {
        $iInstit = db_getsession('DB_instit');
        $aCompetencia = explode('/', $params->folder);
        $sCompetencia = $aCompetencia[1].$aCompetencia[0];
        $sDataIni = str_replace('/', '-', $params->folder).'-01';
        $dDataIni = date("Y-m-d", strtotime($sDataIni));
        $dDataFinal = date("Y-m-t", strtotime($sDataIni));

        $sSql = "select ug as codunidadegestora,
                        {$sCompetencia} as competencia,
                        banco,
                        agencia,
                        conta,
			tipo,
                        fonterecurso,
                        co,
                        round(sum(substr(fc_saltessaldo,41,13)::float8), 2) as valor,
			cnpj_conta
                 from (select tribinst as ug,
                              db90_codban as banco,
                              db89_codagencia||db89_digito as agencia,
                              db83_conta||db83_dvconta as conta,
			      case
				when db83_sequencial = 39328 then 5
				when db83_tipoconta = 1 then 1
				when db83_tipoconta = 2 then 4
				when db83_tipoconta = 3 then 7
			      end as tipo,
                              regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') as cnpj_conta,
                              substr(o15_recurso,2,3) as fonterecurso,
			      case
				when o15_complemento = '1030' then '0000'
				else o15_complemento
			      end as co,
                              c61_reduz,
                              fc_saltessaldo(c61_reduz,'{$dDataIni}','{$dDataFinal}',null,{$iInstit}) as fc_saltessaldo
                         from conplanoreduz
                              join conplanocontabancaria on c61_anousu = c56_anousu
                                                        and c61_codcon = c56_codcon
                                                        and c61_reduz = c56_reduz
                              join contabancaria on c56_contabancaria = db83_sequencial
                              join bancoagencia on db83_bancoagencia = db89_sequencial
                              join db_bancos on db89_db_bancos = db90_codban
                              join db_config on codigo = c61_instit
                              join orctiporec on c61_codigo = o15_codigo
                        where c61_anousu = {$params->ano}
			  and c61_instit = {$iInstit}
                          and db90_codban::int > 0
			  and ( select count(*)
                    from saltes
                    where k13_reduz = c61_reduz
                      and case when k13_dtimplantacao is null then true
                               else k13_dtimplantacao <= '{$dDataFinal}'
                          end ) > 0
                      ) as x
			group by 1,3,4,5,6,7,8,10
                 union
                 select ug as codunidadegestora,
                        {$sCompetencia} as competencia,
                        banco,
                        agencia,
                        conta,
                        tipo,
                        fonterecurso,
                        co,
                        round(sum(substr(fc_saltessaldo,41,13)::float8), 2) as valor,
                        cnpj_conta
                 from (select tribinst as ug,
                              '000' as banco,
                              lpad('0',6,'0') as agencia,
                              lpad('0',13,'0') as conta,
                              1 as tipo,
                              substr(o15_recurso,2,3) as fonterecurso,
                              o15_complemento as co,
                              cgc as cnpj_conta,
                              fc_saltessaldo(c61_reduz,'{$dDataIni}','{$dDataFinal}',null,{$iInstit}) as fc_saltessaldo
                       from conplano
                            join conplanoreduz on c61_codcon = c60_codcon
                                              and c61_anousu = c60_anousu
                            join db_config on codigo = c61_instit
                            join orctiporec on c61_codigo = o15_codigo
                       where c60_estrut = '111110100000000'
                         and c60_anousu = {$params->ano}
			 and c61_instit = {$iInstit}
                      ) as y
			group by 1,3,4,5,6,7,8,10
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações de Saldo Mensal CO Conciliado.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'               => str_pad($oResultado->competencia, 6, 0, STR_PAD_LEFT),
                'codBanco'                  => str_pad($oResultado->banco, 3, 0, STR_PAD_LEFT),
                'numAgencia'                => str_pad($oResultado->agencia, 6, 0, STR_PAD_LEFT),
                'numero'                    => str_pad($oResultado->conta, 13, 0, STR_PAD_LEFT),
                'tipo'                      => str_pad($oResultado->tipo, 1, ' ', STR_PAD_RIGHT),
                'codFonteRecurso'           => str_pad($oResultado->fonterecurso, 3, ' ', STR_PAD_LEFT),
                'co'                        => str_pad($oResultado->co, 4, 0, STR_PAD_LEFT),
                'valor'                     => str_pad($oResultado->valor, 16, 0, STR_PAD_LEFT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpj_conta, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.26 - SaldoMensal
    public static function getSaldoMensal($params)
    {
        $iInstit = db_getsession('DB_instit');
        $aCompetencia = explode('/', $params->folder);
        $sCompetencia = $aCompetencia[1].$aCompetencia[0];
        $sDataIni = str_replace('/', '-', $params->folder).'-01';
        $dDataIni = date("Y-m-d", strtotime($sDataIni));
        $dDataFinal = date("Y-m-t", strtotime($sDataIni));

        $sSql = "select ug as codunidadegestora,
                        {$sCompetencia} as competencia,
                        banco,
                        agencia,
                        conta,
                        tipo,
                        cnpj_conta,
                        sum(round(substr(fc_saltessaldo,41,13)::float8, 2)) as valor
                 from (select tribinst as ug,
                              db90_codban as banco,
                              db89_codagencia||db89_digito as agencia,
                              db83_conta||db83_dvconta as conta,
			      case
				when db83_sequencial = 39328 then 5
				when db83_tipoconta = 1 then 1
				when db83_tipoconta = 2 then 4
				when db83_tipoconta = 3 then 7
			      end as tipo,
                              regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') as cnpj_conta,
                              substr(o15_recurso,2,3) as fonterecurso,
                              o15_complemento as co,
                              c61_reduz,
                              fc_saltessaldo(c61_reduz,'{$dDataIni}','{$dDataFinal}',null,{$iInstit}) as fc_saltessaldo
                         from conplanoreduz
                              join conplanocontabancaria on c61_anousu = c56_anousu
                                                        and c61_codcon = c56_codcon
                                                        and c61_reduz = c56_reduz
                              join contabancaria on c56_contabancaria = db83_sequencial
                              join bancoagencia on db83_bancoagencia = db89_sequencial
                              join db_bancos on db89_db_bancos = db90_codban
                              join db_config on codigo = c61_instit
                              join orctiporec on c61_codigo = o15_codigo
                        where c61_anousu = {$params->ano}
			  and c61_instit = {$iInstit}
			  and db90_codban::int > 0
			  and ( select count(*)
                    from saltes
                    where k13_reduz = c61_reduz
                      and case when k13_dtimplantacao is null then true
                               else k13_dtimplantacao <= '{$dDataFinal}'
                          end
                      and case when k13_limite is null then true
                                else k13_limite >= '{$dDataIni}' end) > 0
		              ) as x
                 group by 1,2,3,4,5,6,7
                 union
                 select ug as codunidadegestora,
                        {$sCompetencia} as competencia,
                        banco,
                        agencia,
                        conta,
                        tipo,
                        cnpj_conta,
                        round(substr(fc_saltessaldo,41,13)::float8, 2) as valor
                 from (select tribinst as ug,
                              '000' as banco,
                              lpad('0',6,'0') as agencia,
                              lpad('0',13,'0') as conta,
                              1 as tipo,
                              substr(o15_recurso,2,3) as fonterecurso,
                              o15_complemento as co,
                              cgc as cnpj_conta,
                              fc_saltessaldo(c61_reduz,'{$dDataIni}','{$dDataFinal}',null,{$iInstit}) as fc_saltessaldo
                       from conplano
                            join conplanoreduz on c61_codcon = c60_codcon
                                              and c61_anousu = c60_anousu
                            join db_config on codigo = c61_instit
                            join orctiporec on c61_codigo = o15_codigo
                       where c60_estrut = '111110100000000'
                         and c60_anousu = {$params->ano}
			 and c61_instit = {$iInstit}
                      ) as y
                ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do Saldo Mensal.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numContaBancaria'          => str_pad($oResultado->conta, 13, 0, STR_PAD_LEFT),
                'numAgencia'                => str_pad($oResultado->agencia, 6, 0, STR_PAD_LEFT),
                'codBanco'                  => str_pad($oResultado->banco, 3, 0, STR_PAD_LEFT),
                'valor'                     => str_pad($oResultado->valor, 16, 0, STR_PAD_LEFT),
                'tipoContaBancaria'         => str_pad($oResultado->tipo, 1, ' ', STR_PAD_RIGHT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpj_conta, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.27 - ConciliacaoBancaria
    public static function getConciliacaoBancaria($params)
    {
        $iInstit = db_getsession('DB_instit');
        $sDataIni = str_replace('/', '-', $params->folder).'-01';
        $dDataIni = date("Y-m-d", strtotime($sDataIni));
        $dDataFinal = date("Y-m-t", strtotime($sDataIni));

        $sSql = "select k68_contabancaria as iconta,
                        tribinst as codunidadegestora,
                        db83_conta||db83_dvconta as conta,
                        db89_codagencia||db89_digito as agencia,
                        db90_codban as banco,
                        k68_sequencial as numero,
                        case when (case when rnvalordebito is not null and
                 	                        rnvalordebito <> 0
                                        then 'D'
                 					   else 'C'
                                   end) = 'C'
                             then 5
                 			else 2
                        end as tipo,
                        substr(rtdetalhe, 1, 150) as detalhe,
                        to_char(ridata, 'DDMMYYYY') as data,
                        richeque as cheque,
                        '0' as numdocdebito,
                        case when rnvalordebito is not null and rnvalordebito <> 0
                 	         then round(rnvalordebito,2)
                 			 else round(rivalorcredito,2)
                        end as valor,
                        case when db83_tipoconta = 1 then 1
                 			when db83_tipoconta = 2 then 4
                 			when db83_tipoconta = 3 then 7
                        end as tipocontabancaria,
                        regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') as cnpj_conta
                 from concilia
                      join contabancaria on db83_sequencial = k68_contabancaria
                      join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                      join db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
                      join conciliapendcorrente on k89_concilia = k68_sequencial
                      join corrente on k12_id = k89_id and k12_data = k89_data and k12_autent = k89_autent
                      join db_config on codigo = k12_instit
                      join fc_extratocaixa(k12_instit, k12_conta, '{$dDataIni}', '{$dDataFinal}', false)
                 	    on ricaixa = k89_id and riautent = k89_autent and ridata = k89_data
                 where k68_contabancaria::varchar||k68_data in ( select k68_contabancaria::varchar||max(k68_data)
                                                                 from concilia
                 												where k68_data <= '{$dDataFinal}'
                 												group by k68_contabancaria )
                   and k12_instit = {$iInstit}
                   and ( select count(*)
                         from conplanocontabancaria join conplanoreduz
                 		                             on c61_anousu = c56_anousu
                                                     and c61_reduz = c56_reduz
                                                     and c61_codcon = c56_codcon
                         where c56_contabancaria = db83_sequencial and c56_anousu = {$params->ano} ) > 0

                   and not exists ( SELECT 1
                                    FROM corgrupocorrente
                 				   WHERE k105_autent = k89_autent AND k105_id = k89_id
                                     AND k105_data = k89_data AND ( ( k105_corgrupotipo in (2, 3, 5, 6)
                 					 AND extract(YEAR FROM k105_data) <= 2012) OR (k105_corgrupotipo in (2, 3) ) ) )

                 union

                 select k68_contabancaria as iconta,
                        tribinst as codunidadegestora,
                        db83_conta||db83_dvconta as conta,
                        db89_codagencia||db89_digito as agencia,
                        db90_codban as banco,
                        k68_sequencial as numero,
                        case when k86_tipo = 'D' then 3 when k86_tipo = 'C' then 4 end as tipo,
                        substr(k86_observacao, 1, 150) as detalhe,
                        to_char(k86_data, 'DDMMYYYY') as data,
                        0 as cheque,
                        k86_documento as numdocdebito,
                        round(k86_valor,2) as k86_valor,
                        case when db83_tipoconta = 1 then 1
                 	        when db83_tipoconta = 2 then 4
                 			when db83_tipoconta = 3 then 7
                        end as tipocontabancaria,
                        regexp_replace(contabancaria.db83_identificador,'[^0-9]','','g') as cnpj_conta
                 from concilia
                      join contabancaria on db83_sequencial = k68_contabancaria
                      join conplanocontabancaria on c56_contabancaria = db83_sequencial and c56_anousu = {$params->ano}
                      join conplanoreduz on c61_anousu = c56_anousu
                                        and c61_reduz = c56_reduz and c61_codcon = c56_codcon
                      join db_config on codigo = c61_instit
                      join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                      join db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
                      join conciliapendextrato on k68_sequencial = k88_concilia
                      join extratolinha on k86_sequencial = k88_extratolinha
                      join extrato on k85_sequencial = k86_extrato
                 where k68_contabancaria::varchar||k68_data in ( select k68_contabancaria::varchar||max(k68_data)
                                                                 from concilia
                 												where k68_data <= '{$dDataFinal}'
                 												group by k68_contabancaria )
                   and ( select count(*)
                         from conplanocontabancaria join conplanoreduz on c61_anousu = c56_anousu
                 		                                             and c61_reduz = c56_reduz
                 													 and c61_codcon = c56_codcon
                         where c56_contabancaria = db83_sequencial and c56_anousu = {$params->ano} ) > 0
                   and k86_bancohistmov = 1
                   and c61_instit = {$iInstit}

                 union

		 select 0 as iconta,
                        tribinst as codunidadegestora,
                        db83_conta||db83_dvconta as conta,
                        db89_codagencia||db89_digito as agencia,
                        db90_codban as banco,
                        0 as numero,
                        1 as tipo,
                        '' as detalhe,
                        to_char('{$dDataFinal}'::date, 'DDMMYYYY') as data,
                        0 as cheque,
                        '' as documento,
                        round(sum(coalesce ( ( select case when k97_situacao = 'D' then (k97_saldofinal*-1)
                                                                               else k97_saldofinal
                                                  end as saldoextrato
                                           from extratosaldo
                                           where k97_contabancaria = db83_sequencial
                                             and k97_dtsaldofinal = ( select k97_dtsaldofinal
                                                                      from extratosaldo a
                                                                      where a.k97_contabancaria = db83_sequencial
                                                                        and '{$dDataFinal}' >= a.k97_dtsaldofinal
                                                                      order by a.k97_dtsaldofinal desc,
                                                                               a.k97_extrato desc limit 1 )
					   order by k97_dtsaldofinal desc, k97_extrato desc limit 1 ),0)),2) as valor,
			case
				when db83_sequencial = 39328 then 5
                        	when db83_tipoconta = 1 then 1
                             	when db83_tipoconta = 2 then 4
                             	when db83_tipoconta = 3 then 7
                        end as tipo,
                        regexp_replace(db83_identificador,'[^0-9]','','g') as cnpj_conta
                 from contabancaria
                      join conplanocontabancaria on c56_contabancaria = db83_sequencial
                      join conplanoreduz on c61_anousu = c56_anousu and c61_reduz = c56_reduz
                                                                    and c61_codcon = c56_codcon
								    and c61_instit = {$iInstit}
								    and c61_anousu = {$params->ano}
                      join db_config on codigo = c61_instit
                      join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                      join db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
                 where ( select count(*)
                         from conplanocontabancaria
                 		     join conplanoreduz on c61_anousu = c56_anousu
                 			                   and c61_reduz = c56_reduz
                 							   and c61_codcon = c56_codcon
			 where c56_contabancaria = db83_sequencial and c56_anousu = {$params->ano} ) > 0
			and ( select count(*)
                         from conplanocontabancaria
               		     join conplanoreduz on c61_anousu = c56_anousu
                          and c61_reduz = c56_reduz and c61_codcon = c56_codcon
			     join saltes on k13_reduz = c61_reduz
			 where c56_contabancaria = db83_sequencial and c56_anousu = {$params->ano}
			   	and case when k13_limite is null then true else k13_limite >= '{$dDataIni}' end
				and case when k13_dtimplantacao is null then true else k13_dtimplantacao <= '{$dDataFinal}' end ) > 0
		 group by 2,3,4,5,9,13,14";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações da Conciliação Bancária.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'numContaBancaria'          => str_pad($oResultado->conta, 13, 0, STR_PAD_LEFT),
                'numAgencia'                => str_pad($oResultado->agencia, 6, 0, STR_PAD_LEFT),
                'codBanco'                  => str_pad($oResultado->banco, 3, 0, STR_PAD_LEFT),
                'numero'                    => str_pad($oResultado->numero, 8, 0, STR_PAD_LEFT),
                'tipoConciliacao'           => str_pad($oResultado->tipo, 1, 0, STR_PAD_LEFT),
                'descricao'                 => str_pad($oResultado->detalhe, 150, 0, STR_PAD_RIGHT),
                'data'                      => str_pad($oResultado->data, 8, 0, STR_PAD_LEFT),
                'numCheque'                 => str_pad($oResultado->cheque, 6, 0, STR_PAD_LEFT),
                'numDocDebito'              => str_pad($oResultado->numdocdebito, 11, 0, STR_PAD_LEFT),
                'valor'                     => str_pad($oResultado->valor, 16, 0, STR_PAD_LEFT),
                'tipoContaBancaria'         => str_pad($oResultado->tipocontabancaria, 1, ' ', STR_PAD_RIGHT),
                'cnpjGerenciaContaBancaria' => str_pad($oResultado->cnpj_conta, 14, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.41 - PLOAAcao
    public static function getPloaAcao($params)
    {

        $iInstit = db_getsession('DB_instit');

        $sSql = "
	select distinct lpad(tribinst::varchar,6,0) as codUnidadeGestora,
                lpad(o55_projativ::varchar, 4, '0') as codigo,
                rpad(o55_descr, 70, ' ') as descricao,
                case when o55_tipo = 3 then 0 else o55_tipo end AS tipoAcao,
                rpad(o55_finali, 150, ' ')  AS descMeta,
                RPAD('', 50, ' ') AS descUnidade
           from orcprojativ
                join orcdotacao on o58_anousu = o55_anousu and o58_projativ = o55_projativ
                join db_config on o58_instit = codigo
	  where o58_anousu = {$params->ano} + 1
		";
        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do PloaAcao.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'     => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo'            => str_pad($oResultado->codigo, 4, 0, STR_PAD_RIGHT),
                'descricao'             => str_pad(utf8_decode($oResultado->descricao), 70, ' ', STR_PAD_RIGHT),
                'tipoAcao'              => str_pad($oResultado->tipoacao, 1, ' ', STR_PAD_RIGHT),
                'descMeta'              => str_pad(utf8_decode($oResultado->descmeta), 150, ' ', STR_PAD_RIGHT),
                'descUnidade'           => str_pad(utf8_decode($oResultado->descunidade), 50, ' ', STR_PAD_RIGHT),
            );
        });
    }

    // 4.42 - PLoaDotacao
    public static function getPloaDotacao($params)
    {

        $iInstit = db_getsession('DB_instit');

        $sSql = "

		 select lpad(tribinst::varchar,6,0) as codUnidadeGestora,
			o58_anousu as competencia,
                        lpad(lpad(o58_orgao::varchar,2,0)||lpad(o58_unidade::varchar,3,0),5,0)
                            as codUnidadeOrcamentaria,
			o58_funcao as codFuncao,
			o58_subfuncao as codSubfuncao,
			o58_programa as codPrograma,
			o58_projativ as codAcao,
			substr(orcelemento.o56_elemento, 2, 1) as codCategoriaEconomica,
			substr(orcelemento.o56_elemento, 3, 1) as codNaturezaDespesa,
			substr(orcelemento.o56_elemento, 4, 2) as codModalidadeDespesa,
			substr(orcelemento.o56_elemento, 6, 2) as codElementoDespesa,
			'1' as exercicioFonteRecurso,
			substr(o15_recurso,2,3) as codFonteRecurso,
			sum(o58_valor) as valor
		   from orcdotacao
			join orcelemento on o56_anousu = o58_anousu and o56_codele = o58_codele
			join orctiporec on o15_codigo = o58_codigo
			join db_config on codigo = o58_instit
		  where o58_anousu = {$params->ano} + 1
			group by 1,2,3,4,5,6,7,8,9,10,11,12,13
			order by 1,2,3,4,5,6,7,8,9,10,11,12,13

		";
        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dPLoaDotacao [$sSql]");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria' => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_RIGHT),
                'codFuncao'              => str_pad($oResultado->codfuncao, 2, 0, STR_PAD_LEFT),
                'codSubfuncao'           => str_pad($oResultado->codsubfuncao, 3, 0, STR_PAD_LEFT),
                'codPrograma'            => str_pad($oResultado->codprograma, 4, 0, STR_PAD_LEFT),
                'codAcao'                => str_pad($oResultado->codacao, 4, 0, STR_PAD_LEFT),
                'codCategoriaEconomica'  => str_pad($oResultado->codcategoriaeconomica, 1, 0, STR_PAD_LEFT),
                'codNaturezaDespesa'     => str_pad($oResultado->codnaturezadespesa, 1, 0, STR_PAD_LEFT),
                'codModalidadeDespesa'   => str_pad($oResultado->codmodalidadedespesa, 2, 0, STR_PAD_LEFT),
                'codElementoDespesa'     => str_pad($oResultado->codelementodespesa, 2, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, ' ', STR_PAD_LEFT),
                'valor'                  => str_pad($oResultado->valor, 16, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.43 - PloaPrograma
    public static function getPloaPrograma($params)
    {

        $iInstit = db_getsession('DB_instit');

        $sSql = "
		 select distinct lpad(tribinst::varchar,6,0) as codUnidadeGestora,
				     lpad(o54_programa::varchar, 4, '0') as codigo,
				     rpad(o54_descr, 70, ' ') as descricao,
				     case when o54_finali = '' then rpad(o54_descr, 150, ' ')
				         else rpad(o54_finali, 150, ' ') end as descObjetivo,
				     case when o143_sequencial is null then '09'
				         else lpad(o143_sequencial::varchar,2,0) end as tipoObjetivoMilenio
			    from orcprograma
				 left join orcprogramavinculoobjetivo on
				     (o144_orcprogramaanousu, o144_orcprogramaprograma) = (o54_anousu, o54_programa)
				 left join orcobjetivo on o144_orcobjetivo = o143_sequencial
				 join orcdotacao on o54_anousu = o58_anousu and o54_programa = o58_programa
				 join db_config on codigo = o58_instit
			   where o58_anousu = {$params->ano} + 1
		";
        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do PloaAcao.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'     => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo'            => str_pad($oResultado->codigo, 4, 0, STR_PAD_RIGHT),
                'descricao'             => str_pad(utf8_decode($oResultado->descricao), 70, ' ', STR_PAD_RIGHT),
                'descObjetivo'          => str_pad(utf8_decode($oResultado->descobjetivo), 150, ' ', STR_PAD_RIGHT),
                'tipoObjetivoMilenio'    => str_pad($oResultado->tipoobjetivomilenio, 2, '0', STR_PAD_RIGHT),
            );
        });
    }

    // 4.44 - PloaReceitaPrevista
    public static function getPloaReceitaPrevista($params)
    {

        $iInstit = db_getsession('DB_instit');

        $sSql = "
		 select lpad(tribinst::varchar,6,0) as codUnidadeGestora,
			o70_anousu as competencia,
--			case when substr(o57_fonte, 1, 1) = '4' then substr(o57_fonte, 2, 8)
--			else substr(o57_fonte, 1, 8) end  AS codReceitaOrcamentaria,
			substr(o57_fonte, 2, 8) AS codReceitaOrcamentaria,
			1 as exercicioFonteRecurso,
			substr(o15_recurso,2,3) as codFonteRecurso,
		        case when o70_concarpeculiar in ( '0', '000' ) then '1' else o70_concarpeculiar end as tiporeceita,
                        lpad(sum(round(abs(o70_valor), 2))::varchar, 16, 0) AS valor
		   from orcreceita
			join orcfontes on o70_codfon = o57_codfon and o70_anousu = o57_anousu
			join orctiporec on o15_codigo = o70_codigo
			join db_config on codigo = o70_instit
		  where o70_anousu = {$params->ano} + 1
		    and o70_valor <> 0
			group by 1,2,3,4,5,6
			order by 1,2,3,4,5,6
		";
        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do PloaAcao.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'      => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'competencia'            => str_pad($oResultado->competencia, 4, 0, STR_PAD_LEFT),
                'codReceitaOrcamentaria' => str_pad($oResultado->codreceitaorcamentaria, 8, 0, STR_PAD_LEFT),
                'exercicioFonteRecurso'  => str_pad($oResultado->exerciciofonterecurso, 1, 0, STR_PAD_LEFT),
                'codFonteRecurso'        => str_pad($oResultado->codfonterecurso, 3, ' ', STR_PAD_LEFT),
                'tipoReceita'            => str_pad($oResultado->tiporeceita, 1, 0, STR_PAD_LEFT),
                'valor'                  => str_replace(".", ",", $oResultado->valor)
            );
        });
    }


    // 4.45 - PloaUnidadeOrcamentaria
    public static function getPloaUnidadeOrcamentaria($params)
    {

        $iInstit = db_getsession('DB_instit');

        $sSql = "
		 select distinct
			 tribinst as codUnidadeGestora,
			 lpad(o41_orgao::TEXT, 2, '0') || lpad(o41_unidade::TEXT, 3, '0') as codigo,
			 o41_descr as descricao,
			 z01_nome as nomeSecretario,
			 z01_cgccpf as cpfSecretario,
			 c140_tipoatojuridico as tipoAtoJuridico,
			 case
				when db21_codtipo = 1 then 2
				when db21_codtipo = 2 then 1
				when db21_codtipo = 5 then 9
				when db21_codtipo = 6 then 8
				when db21_codtipo = 7 then 3
				when db21_codtipo = 9 then 7

			 end
			 as tipoNaturezaJuridica
		 from    orcamento.orcunidade
			 join orcdotacao on (o58_anousu, o58_orgao, o58_unidade) = (o41_anousu, o41_orgao, o41_unidade)
			 left join sagresresponsavelunidadeorcamentaria on
			     (c140_anousu, c140_orgao, c140_unidade) = (o41_anousu, o41_orgao, o41_unidade)
			 left join cgm on z01_numcgm = c140_cgm
			 join db_config on o41_instit = codigo
			 join db_tipoinstit on db_config.db21_tipoinstit = db_tipoinstit.db21_codtipo
		 where
		     o41_anousu = {$params->ano} + 1
		  and
		     c140_ativo = 't'
		";
        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do PloaAcao.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'     => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codigo'               => str_pad($oResultado->codigo, 5, ' ', STR_PAD_RIGHT),
                'descricao'            => str_pad(utf8_decode($oResultado->descricao), 50, ' ', STR_PAD_RIGHT),
                'nomeSecretario'       => str_pad(utf8_decode($oResultado->nomesecretario), 60, ' ', STR_PAD_RIGHT),
                'cpfSecretario'        => str_pad($oResultado->cpfsecretario, 11, 0),
                'tipoAtoJuridico'      => str_pad($oResultado->tipoatojuridico, 1, 0, STR_PAD_LEFT),
                'tipoNaturezaJuridica' => str_pad($oResultado->tiponaturezajuridica, 1, 0, STR_PAD_LEFT),
            );
        });
    }

    // 4.46 - RelacionamentoEmpenhoTipoMeta
    public static function getRelacionamentoEmpenhoTipoMeta($params)
    {
        $iInstit = db_getsession('DB_instit');

        $sSql = "select tribinst as codunidadegestora,
                        (o58_orgao::varchar||o58_unidade::varchar) as codunidadeorcamentaria,
                        e60_anousu as anoemissaoempenho,
                        e60_codemp as numempenho,
                        e63_codhist as tipometa
                 from empempenho
                      join orcdotacao on e60_anousu = o58_anousu
                                     and e60_coddot = o58_coddot
                      join empemphist on e60_numemp = e63_numemp
                      join db_config on codigo = e60_instit
                where e60_anousu = {$params->ano}
                  and e60_instit = {$iInstit} ";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do Relacionamento Empenho Tipo Meta.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'         => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'codUnidadeOrcamentaria'    => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_RIGHT),
                'anoEmissaoEmpenho'         => str_pad($oResultado->anoemissaoempenho, 4, 0, STR_PAD_RIGHT),
                'numEmpenho'                => str_pad($oResultado->numempenho, 7, ' ', STR_PAD_RIGHT),
                'tipoMeta'                  => str_pad($oResultado->tipometa, 1, ' ', STR_PAD_LEFT),
            );
        });
    }
}

function rmSpecial($str)
{
    $res = preg_replace('/[\@\.\;\" "-\/]+/', '', $str);
    return $res;
}
