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

namespace ECidade\RecursosHumanos\Pessoal\Repository;

use Exception;
use db_utils;
use DBDate;

/**
 * Class SagresRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 * @author Lucas Jarrier de Aquino Cavalcanti
 */
class SagresRepository
{
    public static function getCodigoAgrupamentoFolhaPagamento($params)
    {
        $iInstit = db_getsession('DB_instit');
        $ano = $params->ano;
        $mes = $params->mes;

        /**
         * Trás todos os agrupamentos independente se são utilizados ou não.
         */

        $sql = "
        SELECT DISTINCT ON (r70_descr) r70_descr,
        lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
        CASE
            WHEN rh23_codele IS NOT NULL THEN 
                concat(lpad(r70_codigo, 3, 0), lpad(rh23_codele, 5, 0))
            ELSE 
                concat(lpad(r70_codigo, 3, 0), 'FALTA')
        END AS codigo,
        r14_regist,
        {$mes} as mesComp
        FROM gerfsal
        INNER JOIN rhlota ON r70_instit = r14_instit
        INNER JOIN db_config ON db_config.codigo = r14_instit
        INNER JOIN rhrubelemento ON rh23_rubric = r14_rubric
        INNER JOIN rhrubricas ON rh27_rubric = r14_rubric
        AND rh27_instit = r14_instit
        WHERE r14_mesusu = {$mes}
        AND r14_anousu = {$ano}
        AND (r14_instit = {$iInstit} or (r14_instit = 1 and r14_lotac in ('4',
        '47',
        '46',
        '49')))
        AND r14_pd <> 3
        AND rh27_ativo IS TRUE";

        /**
         * Trás apenas os agrupamentos que são utilizados no arquivo folha de pagamento.
         */
        
        $sql = "
        WITH folha_normal AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r14_valor AS valor,
                r14_rubric AS rubrica,
                r14_pd AS vantdesc,
                'norm' AS folha,
                rh01_ponto AS matant
         FROM gerfsal
         INNER JOIN rhpessoal ON r14_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r14_anousu = {$ano}
           AND r14_mesusu = {$mes}
           AND (r14_instit = 7
                OR (r14_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r14_instit = 1)
                OR r14_rubric = '0850')
           AND r14_pd <> 3 ),
           folha_13 AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r35_valor AS valor,
                r35_rubric AS rubrica,
                r35_pd AS vantdesc,
                '13sa' AS folha,
                rh01_ponto AS matant
         FROM gerfs13
         INNER JOIN rhpessoal ON r35_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r35_anousu = {$ano}
           AND r35_mesusu = {$mes}
           AND (r35_instit = 7
                OR (r35_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r35_instit = 1)
                OR r35_rubric = '0850')
           AND r35_pd <> 3 ),
           folha_comp AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r48_valor AS valor,
                r48_rubric AS rubrica,
                r48_pd AS vantdesc,
                'comp' AS folha,
                rh01_ponto AS matant
         FROM gerfcom
         INNER JOIN rhpessoal ON r48_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r48_anousu = {$ano}
           AND r48_mesusu = {$mes}
           AND (r48_instit = 7
                OR (r48_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r48_instit = 1)
                OR r48_rubric = '0850')
           AND r48_pd <> 3
         ORDER BY 1),
           folha_rescis AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r20_valor AS valor,
                r20_rubric AS rubrica,
                r20_pd AS vantdesc,
                'resc' AS folha,
                rh01_ponto AS matant
         FROM gerfres
         INNER JOIN rhpessoal ON r20_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r20_anousu = {$ano}
           AND r20_mesusu = {$mes}
           AND (r20_instit = 7
                OR (r20_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r20_instit = 1)
                OR r20_rubric = '0850')
           AND r20_pd <> 3 ),
           folha_pagamento AS
        (SELECT *
         FROM folha_normal
         UNION SELECT *
         FROM folha_13
         UNION SELECT *
         FROM folha_comp
         UNION SELECT *
         FROM folha_rescis)
      SELECT 
          distinct on (r70_codigo, r70_descr) r70_codigo,
          lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
             concat({$mes}, {$ano}) AS mesano,
             CASE
                 WHEN rh23_codele IS NOT NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('097', 5, 0))
                 WHEN rh23_codele IS NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('17037', 5, 0))
                 ELSE concat(lpad(r70_codigo, 3, 0), 'FALTA')
             END AS codigo,
             rh02_instit AS instit,
             r70_descr,
             {$mes} as mesComp
      FROM folha_pagamento
      INNER JOIN rhpessoalmov ON numero = rh02_regist
      AND rh02_mesusu = {$mes}
      AND rh02_anousu = {$ano}
      INNER JOIN rhlota ON r70_codigo = rh02_lota
      AND r70_instit = rh02_instit
      LEFT JOIN rhrubelemento ON rh23_rubric = rubrica
      AND rh23_instit = rh02_instit
      INNER JOIN db_config ON db_config.codigo = rh02_instit
      WHERE folha_pagamento.numero not in
          (SELECT numero
           FROM folha_pagamento
           WHERE rubrica = 'R928') and rh02_instit = {$iInstit}";

        $result = db_query($sql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do arquivo Agrupamento da Folha.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'             => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'codigo'                        => str_pad($oResultado->mescomp
                    . dePara($oResultado->codigo), 10, 0, STR_PAD_LEFT),
                'descricao'                     => str_pad($oResultado->r70_descr, 80, ' '),
                'reservado'                     => sprintf('%06d', ''),
            );
        });
    }

    public static function getServidores($params)
    {
        $concatCondicao = "";
        $iInstit = db_getsession('DB_instit');

        if ($iInstit == 7) {
            // $concatCondicao = " or (rh02_lota in (4, 47, 46, 49) and rh01_instit = 1)";
        }

        $ano = $params->ano;
        $mes = $params->mes;

        // Filtra apenas os servidores admitidos na competência atual
        $admitidosCompetencia = isset($params->admiss);

        $sSql = "
        SELECT 
            distinct z01_cgccpf AS cpf,
            rh01_regist,
            z01_ident AS rg,
            z01_identorgao AS orgaoemissor,
            z01_nome AS nome,
            to_char(rh01_nasc, 'DDMMYYYY') AS datanascimento,
            rh01_sexo AS sexo,
            CASE
                WHEN rh02_deficientefisico IS TRUE THEN 'S'
                ELSE 'N'
            END AS possuideficiencia,
            lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora
        FROM rhpessoal
        INNER JOIN rhpessoalmov ON rh02_regist = rh01_regist
        AND rh02_instit = rh01_instit
        INNER JOIN rhregime ON rh02_codreg = rh30_codreg
        AND rh30_instit = rh02_instit
        INNER JOIN rhlota ON r70_codigo = rh02_lota
        AND r70_instit = rh02_instit
        INNER JOIN cgm ON z01_numcgm = rh01_numcgm
        INNER JOIN db_config ON db_config.codigo = rh01_instit
        WHERE rh02_anousu = {$ano}
        AND rh02_mesusu = {$mes}
        AND (rh01_instit = {$iInstit} {$concatCondicao})";

        if ($admitidosCompetencia) {
            $dadaInicio = "{$ano}-{$mes}-01";
            $quantidadeDias = DBDate::getQuantidadeDiasMes($mes, $ano);
            $dadaFim = "{$ano}-{$mes}-{$quantidadeDias}";
            $sSql .= " and rh01_admiss between '{$dadaInicio}' and '{$dadaFim}'";
        }

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos servidores.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'   => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'cpf'                 => str_pad($oResultado->cpf, 11, 0, STR_PAD_LEFT),
                'rg'                  => str_pad(str_replace('.', '', $oResultado->rg), 15, 0, STR_PAD_LEFT),
                'orgaoEmissor'        => str_pad($oResultado->orgaoemissor, 15, ' '),
                'nome'                => str_pad($oResultado->nome, 60, ' '),
                'dataNascimento'      => str_pad($oResultado->datanascimento, 8, 0),
                'sexo'                => str_pad($oResultado->sexo, 1, 0),
                'possuiDeficiencia'   => str_pad($oResultado->possuideficiencia, 1, 0),
                'reservado'           => sprintf('%06d', ''),
            );
        });
    }

    public static function getMatricula($params)
    {
        $concatCondicao = "";
        $iInstit = db_getsession('DB_instit');

        if ($iInstit == 7) {
            // $concatCondicao = " or (rh02_lota in (4, 47, 46, 49) and rh01_instit = 1)";
        }

        $ano = $params->ano;
        $mes = $params->mes;

        // Filtra apenas os servidores admitidos na competência atual.
        $admitidosCompetencia = isset($params->admiss);

        $sql = "
        SELECT DISTINCT
                CASE
                    WHEN rh01_instit = 7
                        AND rh01_ponto != 0 THEN rh01_ponto::integer + 500000000
                    ELSE rh01_regist
                END as numero,
                z01_cgccpf as cpf,
                CASE
                    WHEN rh02_instit = 7 THEN rh02_funcao::integer + 5000000
                    ELSE rh02_funcao
                END AS codcargo,
                to_char(rh01_admiss, 'DDMMYYYY') AS dataadmissao,
                lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora
        FROM rhpessoal
        INNER JOIN rhpessoalmov
        on rh02_regist = rh01_regist
        AND rh02_instit = rh01_instit
        INNER JOIN rhregime ON rh02_codreg = rh30_codreg
        AND rh30_instit = rh02_instit
        INNER JOIN rhlota ON r70_codigo = rh02_lota
        AND r70_instit = rh02_instit
        INNER JOIN cgm ON z01_numcgm = rh01_numcgm
        INNER JOIN db_config ON db_config.codigo = rh01_instit
        WHERE rh02_anousu = {$ano}
        AND rh02_mesusu = {$mes}
        AND (rh01_instit = {$iInstit} {$concatCondicao})";

        if ($admitidosCompetencia) {
            $dadaInicio = "{$ano}-{$mes}-01";
            $quantidadeDias = DBDate::getQuantidadeDiasMes($mes, $ano);
            $dadaFim = "{$ano}-{$mes}-{$quantidadeDias}";
            $sql .= " and rh01_admiss between '{$dadaInicio}' and '{$dadaFim}'";
        }

        $result = db_query($sql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do arquivo Matriculas.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {

            return array(
                'codUnidadeGestora'   => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'cpfServidor'         => str_pad($oResultado->cpf, 11, 0, STR_PAD_LEFT),
                'codCargo'            => str_pad($oResultado->codcargo, 8, 0, STR_PAD_LEFT),
                'numero'              => str_pad($oResultado->numero, 15, 0, STR_PAD_LEFT),
                'dataAdmissao'        => str_pad($oResultado->dataadmissao, 8, ' '),
                'reservado'           => sprintf('%06d', ''),
            );
        });
    }

    public static function getCargos($params)
    {
        $iInstit = db_getsession('DB_instit');

        $dadaInicio = "{$params->ano}-{$params->mes}-01";
        $quantidadeDias = DBDate::getQuantidadeDiasMes($params->mes, $params->ano);
        $dadaFim = "{$params->ano}-{$params->mes}-{$quantidadeDias}";

        $sSql = "
        SELECT DISTINCT 
                CASE WHEN rh37_instit = 7 then rh37_funcao::integer + 5000000 
                     ELSE rh37_funcao
                end AS codcargo,
                rh37_descr AS descricao,
                rh37_cbo AS cbo,
                CASE WHEN rh37_rhinstrucao in (2,3,4,5) then 2
                     WHEN rh37_rhinstrucao in (6, 7) then 3
                     WHEN rh37_rhinstrucao in (10, 11) then 3
                     WHEN rh37_rhinstrucao = 8 then 4
                     WHEN rh37_rhinstrucao = 9 then 5
                ELSE rh37_rhinstrucao
                END AS escolaridade,
                lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
                rh267_dados->>'tipoCargo' as tipocargo,
                rh267_dados->>'competenciaCriacao' as competenciaCriacao
        FROM RHFUNCAO
        INNER JOIN db_config ON db_config.codigo = rh37_instit
        INNER JOIN rhfuncaooutrosdados on rh267_rhfuncao = rh37_funcao
        WHERE rh37_instit = {$iInstit}
        AND rh267_dados->>'competenciaCriacao' BETWEEN '{$dadaInicio}' AND '{$dadaFim}'";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do arquivo Cargos.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'   => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'codigo'              => str_pad($oResultado->codcargo, 8, 0, STR_PAD_LEFT),
                'descricao'           => str_pad($oResultado->descricao, 50, ' '),
                'tipo'                => str_pad($oResultado->tipocargo, 1, 0, STR_PAD_LEFT),
                'escolaridadeMinima'  => str_pad($oResultado->escolaridade, 1, 0),
                'cbo'                 => str_pad($oResultado->cbo, 6, 0, STR_PAD_LEFT),
                'reservado'           => sprintf('%06d', ''),
            );
        });
    }

    public static function getHistoricoFuncionalSagres($params)
    {
        $iInstit = db_getsession('DB_instit');
        $ano = $params->ano;
        $mes = $params->mes;

        // Filtra apenas os servidores admitidos na competência atual.
        $admitidosCompetencia = isset($params->admiss);

        if ($admitidosCompetencia) {
            $dadaInicio = "{$ano}-{$mes}-01";
            $quantidadeDias = DBDate::getQuantidadeDiasMes($mes, $ano);
            $dadaFim = "{$ano}-{$mes}-{$quantidadeDias}";
        }

        $mesAnterior = $mes;
        $anoAnterir = $ano;

        if ($mes == 1) {
            $mesAnterior = 12;
            $anoAnterir -= 1;
        } else {
            $mesAnterior -= 1;
        }

        $sSql = "
            WITH tabela_admiss AS
            (SELECT rh01_regist AS matricula,
                    rh01_admiss AS dataadmissao,
                    NULL::date AS datamovimentacao,
                    NULL::date AS rh05_rescis,
                    0 AS r45_situac,
                    0 AS cargo,
                    0 AS lotacao,
                    NULL AS tipoAfasta
            FROM rhpessoal
            WHERE rh01_admiss BETWEEN '{$dadaInicio}' AND '{$dadaFim}'
            AND rh01_instit = {$iInstit}),
            tabela_afasta AS
            (SELECT rh01_regist AS matricula,
                    NULL::date AS dataadmissao,
                    CASE
                        WHEN r45_dtafas IS NOT NULL
                            AND r45_dtafas BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN r45_dtafas
                        WHEN r45_dtreto IS NOT NULL
                            AND r45_dtreto BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN r45_dtreto
                        ELSE NULL
                    END AS datamovimentacao,
                    NULL::date AS rh05_rescis,
                    r45_situac,
                    0 AS cargo,
                    0 AS lotacao,
                    CASE
                        WHEN r45_dtafas IS NOT NULL
                            AND r45_dtafas BETWEEN '{$dadaInicio}' AND '{$dadaFim}'
                            AND r45_codafa in ('Q1',
                                                'P1') THEN '17'
                        WHEN r45_dtreto IS NOT NULL
                            AND r45_dtreto BETWEEN '{$dadaInicio}' AND '{$dadaFim}'
                            AND r45_codafa in ('Q1',
                                                'P1') THEN '21'
                        ELSE NULL
                    END AS tipoAfasta
            FROM rhpessoal
            INNER JOIN afasta ON rh01_regist = r45_regist
            WHERE rh01_instit = {$iInstit}
            AND r45_anousu = {$ano}
            AND r45_mesusu = {$mes}
            AND ((r45_dtafas BETWEEN '{$dadaInicio}' AND '{$dadaFim}')
                    OR (r45_dtreto BETWEEN '{$dadaInicio}' AND '{$dadaFim}'))) ,
            tabela_rescis AS
            (SELECT rh01_regist AS matricula,
                    NULL::date AS dataadmissao,
                    NULL::date AS datamovimentacao,
                    rh05_recis,
                    0 AS r45_situac,
                    0 AS cargo,
                    0 AS lotacao,
                    NULL AS tipoAfasta
            FROM rhpessoal
            INNER JOIN rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
            AND rhpessoalmov.rh02_anousu = {$ano}
            AND rhpessoalmov.rh02_mesusu = {$mes}
            INNER JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
            WHERE rh01_instit = {$iInstit}
            AND rh05_recis BETWEEN '{$dadaInicio}' AND '{$dadaFim}') ,

            tabela_troca_cargo AS
            (
                SELECT
                    rhpessoalmov.rh02_regist AS matricula,
                    NULL::date AS dataadmissao,
                    NULL::date AS datamovimentacao,
                    NULL::date AS rh05_recis,
                    0 AS r45_situac,
                    rhpessoalmov.rh02_funcao AS cargo,
                    0 AS lotacao,
                    NULL AS tipoAfasta
            FROM rhpessoalmov
            INNER JOIN rhpessoalmov AS rhpessoalmovant ON rhpessoalmov.rh02_instit = rhpessoalmovant.rh02_instit
            AND rhpessoalmov.rh02_regist = rhpessoalmovant.rh02_regist
            AND rhpessoalmovant.rh02_anousu = {$anoAnterir}
            AND rhpessoalmovant.rh02_mesusu = {$mesAnterior}
            WHERE rhpessoalmov.rh02_anousu = {$ano}
            AND rhpessoalmov.rh02_mesusu = {$mes}
            AND rhpessoalmov.rh02_instit = {$iInstit}
            AND rhpessoalmov.rh02_funcao <> rhpessoalmovant.rh02_funcao ),
            tabela_troca_unidadeorc AS
            (SELECT rhpessoalmov.rh02_regist AS matricula,
                    NULL::date AS dataadmissao,
                    NULL::date AS datamovimentacao,
                    NULL::date AS rh05_recis,
                    0 AS r45_situac,
                    0 AS cargo,
                    rhpessoalmov.rh02_lota AS lotacao,
                    NULL AS tipoAfasta
            FROM rhpessoalmov
            INNER JOIN rhpessoalmov AS rhpessoalmovant ON rhpessoalmov.rh02_instit = rhpessoalmovant.rh02_instit
            AND rhpessoalmov.rh02_regist = rhpessoalmovant.rh02_regist
            AND rhpessoalmovant.rh02_anousu = {$anoAnterir}
            AND rhpessoalmovant.rh02_mesusu = {$mesAnterior}
            WHERE rhpessoalmov.rh02_anousu = {$ano}
            AND rhpessoalmov.rh02_mesusu = {$mes}
            AND rhpessoalmov.rh02_instit = {$iInstit}
            AND rhpessoalmov.rh02_lota <> rhpessoalmovant.rh02_lota ),

            historico_funcional AS
            (SELECT *
            FROM tabela_admiss
            UNION SELECT *
            FROM tabela_afasta
            UNION SELECT *
            FROM tabela_rescis
            UNION SELECT *
            FROM tabela_troca_cargo
            UNION SELECT *
            FROM tabela_troca_unidadeorc)

        SELECT CASE
                    WHEN rh02_instit = 7
                        AND rh01_ponto != 0 THEN rh01_ponto::integer + 500000000
                    ELSE matricula
                END,
                lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
                CASE
                    WHEN dataadmissao IS NOT NULL
                        AND dataadmissao BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN to_char(dataadmissao, 'DDMMYYYY')
                    WHEN datamovimentacao IS NOT NULL
                        AND datamovimentacao BETWEEN '{$dadaInicio}' AND '{$dadaFim}' 
                            THEN to_char(datamovimentacao, 'DDMMYYYY')
                    WHEN rh05_rescis IS NOT NULL
                        AND rh05_rescis BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN to_char(rh05_rescis, 'DDMMYYYY') 
                    WHEN cargo > 0 THEN to_char('{$dadaInicio}'::date, 'DDMMYYYY')
                    WHEN lotacao > 0 THEN to_char('{$dadaInicio}'::date, 'DDMMYYYY')
                    ELSE NULL
                END AS datamov,
                CASE
                    WHEN rh02_instit = 7 THEN rh02_funcao::integer + 5000000
                    ELSE rh02_funcao
                END AS codcargo,
                CASE
                    WHEN rh02_codreg in ('25',
                                        '23') THEN '1'
                    ELSE '0'
                END AS regprev,
                CASE
                    WHEN rh02_tbprev = 1 THEN '2'
                    ELSE '0'
                END AS regtrab,
                concat(o40_codtri, o41_codtri) AS codunidadeorcamentaria,
                concat({$mes}, {$ano}) AS mesano,
                z01_cgccpf AS cpf,
                CASE
                    WHEN rh30_vinculo = 'A' THEN '0'
                    WHEN rh30_vinculo = 'I' THEN '1'
                    WHEN rh30_vinculo = 'P' THEN '2'
                    ELSE NULL
                END AS situacao,
                CASE
                    WHEN dataadmissao IS NOT NULL
                        AND dataadmissao BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN '8'
                    WHEN tipoafasta IS NOT NULL THEN tipoafasta
                    WHEN rh05_rescis IS NOT NULL
                        AND rh05_rescis BETWEEN '{$dadaInicio}' AND '{$dadaFim}' THEN '22'
                    WHEN cargo > 0 THEN '23'
                    WHEN lotacao > 0 THEN '18'
                    ELSE NULL
                END AS tipoato,
                NULL AS cpfsegurado
        FROM historico_funcional
        INNER JOIN rhpessoalmov ON rh02_anousu = {$ano}
        AND rh02_mesusu = {$mes}
        AND rh02_regist = matricula
        AND rh02_instit = {$iInstit}
        INNER JOIN rhpessoal ON rh01_regist = rh02_regist
        INNER JOIN cgm ON rh01_numcgm = z01_numcgm
        INNER JOIN rhlota ON r70_codigo = rh02_lota
        INNER JOIN rhlotaexe ON rh26_codigo = rh02_lota
        AND rh02_anousu = rh26_anousu
        INNER JOIN rhregime ON rh02_codreg = rh30_codreg
        AND rh30_instit = rh02_instit
        INNER JOIN db_config ON db_config.codigo = rh02_instit
        INNER JOIN orcunidade ON rh02_instit = o41_instit
        AND rh26_unidade = o41_unidade
        AND o41_anousu = rh02_anousu
        INNER JOIN orcorgao ON o40_instit = o41_instit
        AND rh26_orgao = o40_orgao
        AND o40_anousu = rh02_anousu";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do arquivo Histórico Funcional.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'          => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'cpfServidor'                => str_pad($oResultado->cpf, 11, 0, STR_PAD_LEFT),
                'codCargo'                   => str_pad($oResultado->codcargo, 8, 0, STR_PAD_LEFT),
                'dataMovimentacao'           => str_pad($oResultado->datamov, 8, 0, STR_PAD_LEFT),
                'numMatricula'               => str_pad($oResultado->matricula, 15, 0, STR_PAD_LEFT),
                'mesAno'                     => str_pad($oResultado->mesano, 6, 0, STR_PAD_LEFT),
                'cpfSegurado'                => str_pad($oResultado->cpfsegurado, 11, 0, STR_PAD_LEFT),
                'tipoAto'                    => str_pad($oResultado->tipoato, 2, 0, STR_PAD_LEFT),
                'situacao'                   => str_pad($oResultado->situacao, 1, 0, STR_PAD_RIGHT),
                'tipoRegimePrevidenciario'   => str_pad($oResultado->regprev, 1, 0, STR_PAD_RIGHT),
                'tipoRegimeTrabalho'         => str_pad($oResultado->regtrab, 1, 0, STR_PAD_RIGHT),
                'codUnidadeOrcamentaria'     => str_pad($oResultado->codunidadeorcamentaria, 5, 0, STR_PAD_RIGHT),
                'reservado'                  => sprintf('%06d', ''),
            );
        });
    }

    public static function getCodigoVantagensDescontos($params)
    {
        $iInstit = db_getsession('DB_instit');

        $dadaInicio = "{$params->ano}-{$params->mes}-01";
        $quantidadeDias = DBDate::getQuantidadeDiasMes($params->mes, $params->ano);
        $dadaFim = "{$params->ano}-{$params->mes}-{$quantidadeDias}";

        $sSql = "
        SELECT distinct rh27_rubric AS codigo,
        lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
        CASE
            WHEN rh27_pd = 1 THEN '0'
            WHEN rh27_pd = 2 THEN '1'
            ELSE rh27_pd
        END AS tipolancamento,
        rh27_descr AS descricao,
        CASE
            WHEN r09_base in ('B001',
                              'B018') THEN 'S'
            ELSE 'N'
        END AS regprev,
        CASE
            WHEN rh75_retencaotiporec IS NOT NULL THEN '0'
            ELSE '1'
        END AS tipocontabilizacao,
        r09_anousu as ano,
        r09_mesusu as mes
        FROM RHRUBRICAS
        INNER JOIN db_config ON db_config.codigo = rh27_instit
        INNER JOIN basesr ON r09_rubric = rh27_rubric
        INNER JOIN esocialrubricas on eso26_rubrica = rh27_rubric
        LEFT JOIN rhrubretencao ON rh27_rubric = rh75_rubric
        LEFT JOIN rhrubelemento ON rh23_rubric = rh27_rubric
        WHERE rh27_instit = {$iInstit} AND rh27_ativo IS TRUE AND rh27_pd <> 3
        AND eso26_datainicial BETWEEN '{$dadaInicio}' AND '{$dadaFim}'";

        $result = db_query($sSql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações dos servidores.");
        }

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'             => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_RIGHT),
                'tipoLancamento'                => str_pad($oResultado->tipolancamento, 1, 0),
                'codigo'                        => str_pad($oResultado->codigo, 6, 0, STR_PAD_LEFT),
                'descricao'                     => str_pad(substr($oResultado->descricao, 0, 40), 40, ' '),
                'tipo'                          => str_pad($oResultado->tipocontabilizacao, 1, ' '),
                'baseCalculoPrevidenciario'     => str_pad($oResultado->regprev, 1, ' '),
            );
        });
    }

    public static function getFolhaPagamento($params)
    {

        $iInstit = db_getsession('DB_instit');
        $ano = $params->ano;
        $mes = $params->mes;

        /**
         * Condição Temporária, adicionada pois nenhuma matricula que vai no sagres possui a rúbrica em questão.
         */

        if ($iInstit == 7) {
            $condicaoExclusao = "WHERE folha_pagamento.numero not in 
            (select numero from folha_pagamento where rubrica = 'R928')";
        }

        $sql =  " 
        WITH folha_normal AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r14_valor AS valor,
                r14_rubric AS rubrica,
                r14_pd AS vantdesc,
                'norm' AS folha,
                rh01_ponto AS matant
         FROM gerfsal
         INNER JOIN rhpessoal ON r14_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r14_anousu = {$ano}
           AND r14_mesusu = {$mes}
           AND (r14_instit = {$iInstit}
                OR (r14_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r14_instit = 1)
                OR r14_rubric = '0850')
           AND r14_pd <> 3 ),
           folha_13 AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r35_valor AS valor,
                r35_rubric AS rubrica,
                r35_pd AS vantdesc,
                '13sa' AS folha,
                rh01_ponto AS matant
         FROM gerfs13
         INNER JOIN rhpessoal ON r35_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r35_anousu = {$ano}
           AND r35_mesusu = {$mes}
           AND (r35_instit = {$iInstit}
                OR (r35_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r35_instit = 1)
                OR r35_rubric = '0850')
           AND r35_pd <> 3 ),
           folha_comp AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r48_valor AS valor,
                r48_rubric AS rubrica,
                r48_pd AS vantdesc,
                'comp' AS folha,
                rh01_ponto AS matant
         FROM gerfcom
         INNER JOIN rhpessoal ON r48_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r48_anousu = {$ano}
           AND r48_mesusu = {$mes}
           AND (r48_instit = {$iInstit}
                OR (r48_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r48_instit = 1)
                OR r48_rubric = '0850')
           AND r48_pd <> 3
         ORDER BY 1),
           folha_rescis AS
        (SELECT rh01_regist AS numero,
                z01_cgccpf AS cpf,
                r20_valor AS valor,
                r20_rubric AS rubrica,
                r20_pd AS vantdesc,
                'resc' AS folha,
                rh01_ponto AS matant
         FROM gerfres
         INNER JOIN rhpessoal ON r20_regist = rh01_regist
         INNER JOIN cgm ON z01_numcgm = rh01_numcgm
         WHERE r20_anousu = {$ano}
           AND r20_mesusu = {$mes}
           AND (r20_instit = {$iInstit}
                OR (r20_lotac in ('4',
                                  '47',
                                  '46',
                                  '49')
                    AND r20_instit = 1)
                OR r20_rubric = '0850')
           AND r20_pd <> 3 ),
           folha_pagamento AS
        (SELECT *
         FROM folha_normal
         UNION SELECT *
         FROM folha_13
         UNION SELECT *
         FROM folha_comp
         UNION SELECT *
         FROM folha_rescis)
        SELECT lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
                cpf,
                CASE
                    WHEN rh02_instit = 7
                        AND matant != 0 THEN matant::integer + 500000000
                    ELSE folha_pagamento.numero
                END,
                concat({$mes}, {$ano}) AS mesano,
                CASE
                    WHEN vantdesc = 1 THEN '0'
                    WHEN vantdesc = 2 THEN '1'
                    ELSE vantdesc
                END,
                rubrica,
                CASE
                    WHEN folha = 'norm' THEN '0'
                    WHEN folha = 'comp' THEN '3'
                    WHEN folha = '13sa' THEN '1'
                    WHEN folha = 'resc' THEN '0'
                    ELSE NULL
                END AS tipo,
                valor,
                CASE
                    WHEN rh23_codele IS NOT NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('097', 5, 0))
                    WHEN rh23_codele IS NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('17037', 5, 0))
                    ELSE concat(lpad(r70_codigo, 3, 0), 'FALTA')
                END AS codagrup,
                CASE
                    WHEN rh02_instit = 7 THEN rh02_funcao::integer + 5000000
                    ELSE rh02_funcao
                END AS codcargo,
                rh02_instit AS instit,
                r70_descr AS descricao,
                {$mes} as mesComp
        FROM folha_pagamento
        INNER JOIN rhpessoalmov ON numero = rh02_regist
        AND rh02_mesusu = {$mes}
        AND rh02_anousu = {$ano}
        INNER JOIN rhlota ON r70_codigo = rh02_lota
        AND r70_instit = rh02_instit
        LEFT JOIN rhrubelemento ON rh23_rubric = rubrica
        AND rh23_instit = rh02_instit
        INNER JOIN db_config ON db_config.codigo = rh02_instit
        {$condicaoExclusao} and rh02_instit = {$iInstit}
        ORDER BY instit DESC, codagrup, tipo";

        /**
        * Folha Comp
        */

    //     $sql = "
    //     WITH folha_comp AS
    //     (SELECT rh01_regist AS numero,
    //             z01_cgccpf AS cpf,
    //             r48_valor AS valor,
    //             r48_rubric AS rubrica,
    //             r48_pd AS vantdesc,
    //             'comp' AS folha,
    //             rh01_ponto AS matant
    //      FROM gerfcom
    //      INNER JOIN rhpessoal ON r48_regist = rh01_regist
    //      INNER JOIN cgm ON z01_numcgm = rh01_numcgm
    //      WHERE r48_anousu = {$ano}
    //        AND r48_mesusu = {$mes}
    //        AND (r48_instit = {$iInstit}
    //             OR (r48_lotac in ('4',
    //                               '47',
    //                               '46',
    //                               '49')
    //                 AND r48_instit = 1)
    //             OR r48_rubric = '0850')
    //        AND r48_pd <> 3
    //      ORDER BY 1),
    //        folha_pagamento AS
    //     (SELECT *
    //      FROM folha_comp)
    //   SELECT lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
    //          cpf,
    //          CASE
    //              WHEN rh02_instit = 7
    //                   AND matant != 0 THEN matant::integer + 500000000
    //              ELSE folha_pagamento.numero
    //          END,
    //          concat({$mes}, {$ano}) AS mesano,
    //          CASE
    //              WHEN vantdesc = 1 THEN '0'
    //              WHEN vantdesc = 2 THEN '1'
    //              ELSE vantdesc
    //          END,
    //          rubrica,
    //          CASE
    //              WHEN folha = 'norm' THEN '0'
    //              WHEN folha = 'comp' THEN '3'
    //              WHEN folha = '13sa' THEN '1'
    //              WHEN folha = 'resc' THEN '0'
    //              ELSE NULL
    //          END AS tipo,
    //          valor,
    //          CASE
    //              WHEN rh23_codele IS NOT NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('097', 5, 0))
    //              WHEN rh23_codele IS NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('17037', 5, 0))
    //              ELSE concat(lpad(r70_codigo, 3, 0), 'FALTA')
    //          END AS codagrup,
    //          CASE
    //              WHEN rh02_instit = 7 THEN rh02_funcao::integer + 5000000
    //              ELSE rh02_funcao
    //          END AS codcargo,
    //          rh02_instit AS instit,
    //          r70_descr AS descricao,
    //          {$mes} as mesComp
    //   FROM folha_pagamento
    //   INNER JOIN rhpessoalmov ON numero = rh02_regist
    //   AND rh02_mesusu = {$mes}
    //   AND rh02_anousu = {$ano}
    //   INNER JOIN rhlota ON r70_codigo = rh02_lota
    //   AND r70_instit = rh02_instit
    //   LEFT JOIN rhrubelemento ON rh23_rubric = rubrica
    //   AND rh23_instit = rh02_instit
    //   INNER JOIN db_config ON db_config.codigo = rh02_instit
    //   WHERE folha_pagamento.numero not in
    //       (SELECT numero
    //        FROM folha_pagamento
    //        WHERE rubrica = 'R928')
      
    //     and rh02_instit = 7
    //     ORDER BY instit DESC,
    //             codagrup,
    //             tipo";

    // Folha Salário, tem um where la embaixo!

    //     $sql = "
    //     WITH folha_normal AS
    //     (SELECT rh01_regist AS numero,
    //             z01_cgccpf AS cpf,
    //             r14_valor AS valor,
    //             r14_rubric AS rubrica,
    //             r14_pd AS vantdesc,
    //             'norm' AS folha,
    //             rh01_ponto AS matant
    //      FROM gerfsal
    //      INNER JOIN rhpessoal ON r14_regist = rh01_regist
    //      INNER JOIN cgm ON z01_numcgm = rh01_numcgm
    //      WHERE r14_anousu = {$ano}
    //        AND r14_mesusu = {$mes}
    //        AND (r14_instit = {$iInstit}
    //             OR (r14_lotac in ('4',
    //                               '47',
    //                               '46',
    //                               '49')
    //                 AND r14_instit = 1)
    //             OR r14_rubric = '0850')
    //        AND r14_pd <> 3
    //      ORDER BY 1),
    //        folha_pagamento AS
    //     (SELECT *
    //      FROM folha_normal)
    //   SELECT lpad(db_config.tribinst::varchar, 6, 0) AS codunidadegestora,
    //          cpf,
    //          CASE
    //              WHEN rh02_instit = 7
    //                   AND matant != 0 THEN matant::integer + 500000000
    //              ELSE folha_pagamento.numero
    //          END,
    //          concat({$mes}, {$ano}) AS mesano,
    //          CASE
    //              WHEN vantdesc = 1 THEN '0'
    //              WHEN vantdesc = 2 THEN '1'
    //              ELSE vantdesc
    //          END,
    //          rubrica,
    //          CASE
    //              WHEN folha = 'norm' THEN '0'
    //              WHEN folha = 'comp' THEN '3'
    //              WHEN folha = '13sa' THEN '1'
    //              WHEN folha = 'resc' THEN '0'
    //              ELSE NULL
    //          END AS tipo,
    //          valor,
    //          CASE
    //              WHEN rh23_codele IS NOT NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('097', 5, 0))
    //              WHEN rh23_codele IS NULL THEN concat(lpad(r70_codigo, 3, 0), lpad('17037', 5, 0))
    //              ELSE concat(lpad(r70_codigo, 3, 0), 'FALTA')
    //          END AS codagrup,
    //          CASE
    //              WHEN rh02_instit = 7 THEN rh02_funcao::integer + 5000000
    //              ELSE rh02_funcao
    //          END AS codcargo,
    //          rh02_instit AS instit,
    //          r70_descr AS descricao,
    //          {$mes} as mesComp,
    //          r70_codigo
    //   FROM folha_pagamento
    //   INNER JOIN rhpessoalmov ON numero = rh02_regist
    //   AND rh02_mesusu = {$mes}
    //   AND rh02_anousu = {$ano}
    //   INNER JOIN rhlota ON r70_codigo = rh02_lota
    //   AND r70_instit = rh02_instit
    //   LEFT JOIN rhrubelemento ON rh23_rubric = rubrica
    //   AND rh23_instit = rh02_instit
    //   INNER JOIN db_config ON db_config.codigo = rh02_instit
    //   WHERE folha_pagamento.numero not in
    //       (SELECT numero
    //        FROM folha_pagamento
    //        WHERE rubrica = 'R928')
    //     and rh02_instit = 7
    //     and r70_codigo = 478
    //     ORDER BY instit DESC,
    //             codagrup,
    //             tipo";

        $result = db_query($sql);

        if (!$result) {
            throw new Exception("Erro ao buscar as informações do arquivo Folha de Pagamento.");
        }

        // $oResultado->codunidadegestora
        // Deixando unidade gestora mocada

        return db_utils::makeCollectionFromRecord($result, function ($oResultado) {
            return array(
                'codUnidadeGestora'             => str_pad($oResultado->codunidadegestora, 6, 0, STR_PAD_LEFT),
                'cpfServidor'                   => str_pad($oResultado->cpf, 11, 0, STR_PAD_LEFT),
                'codCargo'                      => str_pad($oResultado->codcargo, 8, 0, STR_PAD_LEFT),
                'numMatricula'                  => str_pad($oResultado->numero, 15, 0, STR_PAD_LEFT),
                'mesAno'                        => str_pad($oResultado->mesano, 6, 0, STR_PAD_LEFT),
                'codOperacao'                   => str_pad($oResultado->vantdesc, 1, 0),
                'codVantagemDesconto'           => str_pad($oResultado->rubrica, 6, 0, STR_PAD_LEFT),
                'tipo'                          => str_pad($oResultado->tipo, 1, 0),
                'valor'                         => str_pad(str_replace(
                    '.',
                    ',',
                    str_contains($oResultado->valor, '.')
                    ? $oResultado->valor : $oResultado->valor . '.00'
                ), 16, 0, STR_PAD_LEFT),
                'codAgrupamento'                => str_pad($oResultado->mescomp .
                    dePara($oResultado->codagrup), 10, 0, STR_PAD_LEFT),
                'reservado'                     => sprintf('%06d', ''),
            );
        });
    }
}

/**
 * De para criado para os agrupamentos da unidade 7
 */

function dePara($chave)
{
    $deParaAgrupamentoSaude = [
        $chave => $chave,
        '06000097' => '20241404', '06017037' => '20241404', // UPA II ( MEDICOS PLANTONISTAS )
        '06300097' => '20321404', '06317037' => '20321404', // UPA II (PESSOAL DE APOIO)
        '06400097' => '20021404', '06417037' => '20021404', // SAUDE MENTAL ( CAPS )
        '06500097' => '20031404', '06517037' => '20031404', // ISEA ( PESSOAL DE APOIO )
        '06700097' => '20051404', '06717037' => '20051404', // SAMU ( PESSOAL DE APOIO )
        '06800097' => '20061404', '06817037' => '20061404', // UPA ( PESSOAL DE APOIO )
        '06900097' => '20071404', '06917037' => '20071404', // HOSPITAL DA CRIANCA ( PESSOAL DE APOIO )
        '07000097' => '20081404', '07017037' => '20081404', // CEO
        '07100097' => '20091404', '07117037' => '20091404', // CEREST
        '07200097' => '20101404', '07217037' => '20101404', // PSF
        '07300097' => '20111404', '07317037' => '20111404', // SEDE
        '07400097' => '20121404', '07417037' => '20121404', // POSTOS
        '07500097' => '20131404', '07517037' => '20131404', // VIGILANCIA EM SAUDE
        '08000097' => '20181404', '08017037' => '20181404', // AVA
        '08300097' => '20211404', '08317037' => '20211404', // CONSULTORIO NA RUA
        '08700097' => '20281404', '08717037' => '20281404', // HOSP DR EDGLEY ( PESSOAL DE APOIO )
        '08800097' => '20291404', '08817037' => '20291404', // HOSP DR EDGLEY ( PLANTONISTAS )
        '09100097' => '20301404', '09117037' => '20301404', // CENTRO ESPECIALIZADO EM REABILITACAO - CER
        '10100097' => '20271604', '10117037' => '20271604', // HOSPITAL PEDRO I ( PESSOAL DE APOIO )
        '10300097' => '20311404', '10317037' => '20311404', // CERAST
        '35800097' => '20331404', '35817037' => '20331404', // PSF (MEDICOS)
        '37800097' => '20341404', '37817037' => '20341404', // D.A.S.
        '41800097' => '20361404', '41817037' => '20361404', // PNAISP
        '41900097' => '20351404', '41917037' => '20351404', // EMAD
        '45800097' => '20371404', '45817037' => '20371404', // COVID-19
        '47800097' => '20381404', '47817037' => '20381404', // PROGRAMA SAUDE NA HORA
        '53800097' => '20391404', '53817037' => '20391404'  // CENTRAL DE AMBULANCIA
        ];
    return $deParaAgrupamentoSaude[$chave];
}
