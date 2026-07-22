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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';
try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case 'buscar':
            $daoTetoOrcamentario = new cl_teto_orcamentario();

            $campos = "*, orcorgao.o40_descr || ' / ' || orcunidade.o41_descr AS unidade_orcamentaria";
            $codigo = !empty($parametros->codigo) ? $parametros->codigo : null;

            $sql = $daoTetoOrcamentario->sql_query($codigo, $campos);
            $rs = db_query($sql);
            $retorno->tetosOrcamentarios = array();

            if (!$rs) {
                throw new DBException("Erro ao buscar os tetos orçamentários.");
            }

            if ($codigo) {
                $retorno->tetoOrcamentario = pg_fetch_object($rs);
            } else {
                $retorno->tetosOrcamentarios = db_utils::getCollectionByRecord($rs, false, false, true);
            }

            break;

        case 'salvar':
            if ($parametros->ano === '') {
                throw new ParameterException('É necessário preencher o campo "Ano".');
            }
            if ($parametros->unidade === '') {
                throw new ParameterException('É necessário preencher o campo "Unidade Orçamentária".');
            }
            if ($parametros->grupoNaturezaDespesa === '') {
                throw new ParameterException('É necessário preencher o campo "Natureza da despesa".');
            }
            if ($parametros->identificadorUso === '') {
                throw new ParameterException('É necessário preencher o campo "Identificador de uso".');
            }
            if ($parametros->tipoDetalhamento === '') {
                throw new ParameterException('É necessário preencher o campo "Tipo de detalhamento".');
            }
            if ($parametros->grupoFonteRecursos === '') {
                throw new ParameterException('É necessário preencher o campo "Grupo de fonte de recursos".');
            }
            if ($parametros->especificacaoFonte === '') {
                throw new ParameterException('É necessário preencher o campo "Especificação da Fonte de Recurso".');
            }
            if ($parametros->valorTeto === '') {
                throw new ParameterException('É necessário preencher o campo "Valor do teto orçamentário".');
            }

            $daoTetoOrcamentario = new cl_teto_orcamentario();

            $orgao = substr($parametros->unidade, 0, 2);
            $unidade = substr($parametros->unidade, 2, 2);

            $where  = " c40_ano                        = {$parametros->ano}";
            $where .= " AND c40_orgao                  = {$orgao}";
            $where .= " AND c40_unidade                = {$unidade}";
            $where .= " AND c40_grupo_natureza_despesa = {$parametros->grupoNaturezaDespesa}";
            $where .= " AND c40_identificador_uso      = {$parametros->identificadorUso}";
            $where .= " AND c40_tipo_detalhamento      = '{$parametros->tipoDetalhamento}'";
            $where .= " AND c40_grupo_fonte_recursos   = '{$parametros->grupoFonteRecursos}'";
            $where .= " AND c40_especificacao_fonte    = '{$parametros->especificacaoFonte}'";

            $sqlTeto = $daoTetoOrcamentario->sql_query_file(null, "c40_sequencial", null, $where);
            $rsTeto = db_query($sqlTeto);

            if (empty($rsTeto)) {
                throw new DBException("Erro ao buscar tetos orçamentários existentes.");
            }

            if (pg_num_rows($rsTeto) > 0) {
                $teto = db_utils::fieldsMemory($rsTeto, 0);

                if (!isset($parametros->codigo) || $parametros->codigo != $teto->c40_sequencial) {
                    $erro  = "Já há um Teto Orçamentário com estes dados. ";
                    $erro .= "Altere-o em 'Teto Orçamentário > Alteração' utilizando o código {$teto->c40_sequencial}.";
                    throw new BusinessException($erro);
                }
            }

            $daoTetoOrcamentario->c40_ano = $parametros->ano;
            $daoTetoOrcamentario->c40_orgao = $orgao;
            $daoTetoOrcamentario->c40_unidade = $unidade;
            $daoTetoOrcamentario->c40_grupo_natureza_despesa = $parametros->grupoNaturezaDespesa;
            $daoTetoOrcamentario->c40_identificador_uso = $parametros->identificadorUso;
            $daoTetoOrcamentario->c40_tipo_detalhamento = $parametros->tipoDetalhamento;
            $daoTetoOrcamentario->c40_grupo_fonte_recursos = $parametros->grupoFonteRecursos;
            $daoTetoOrcamentario->c40_especificacao_fonte = $parametros->especificacaoFonte;
            $daoTetoOrcamentario->c40_valor_teto = $parametros->valorTeto;

            if (!empty($parametros->codigo)) {
                $sql = $daoTetoOrcamentario->sql_query_file($parametros->codigo, "c40_valor_teto, c40_valor_disponivel");
                $rs = db_query($sql);

                if (!$rs || pg_num_rows($rs) == 0) {
                    throw new DBException("Erro ao buscar o teto orçamentário.");
                }

                $dados = db_utils::fieldsMemory($rs, 0);
                $daoTetoOrcamentario->c40_valor_disponivel = $dados->c40_valor_disponivel + ($parametros->valorTeto - $dados->c40_valor_teto);

                if ($daoTetoOrcamentario->c40_valor_disponivel < 0) {
                    $where  = " c333_ano = {$parametros->ano}";
                    $where .= " and c333_orcorgao = {$orgao}::integer";
                    $where .= " and c333_orcunidade = {$unidade}::integer";
                    $where .= " and c60_estrut ilike '__{$parametros->grupoNaturezaDespesa}%'";
                    $where .= " and c333_identificadoruso = {$parametros->identificadorUso}";
                    $where .= " and c333_tipodetalhamento = '{$parametros->tipoDetalhamento}'";
                    $where .= " and c333_grupofonterecursos = '{$parametros->grupoFonteRecursos}'";
                    $where .= " and c333_especificacaofonte = '{$parametros->especificacaoFonte}'";
                    
                    $daoPrevisaoDespesa = new cl_previsaodespesa();
                    $sqlPrevisaoDespesa = $daoPrevisaoDespesa->sql_previsao_despesa(null, "c333_sequencial", "c333_sequencial", $where);
                    $rsPrevisaoDespesa = db_query($sqlPrevisaoDespesa);
                    
                    if (!$rsPrevisaoDespesa || pg_num_rows($rsPrevisaoDespesa) == 0) {
                        throw new DBException("Erro ao buscar a previsão de despesa.");   
                    }

                    $linhasPrevisaoDespesa = pg_num_rows($rsPrevisaoDespesa);
                    $codigosPrevisaoDespesa = array();
                    for ($i = 0; $i < $linhasPrevisaoDespesa; $i++) {
                        $codigosPrevisaoDespesa[] = db_utils::fieldsMemory($rsPrevisaoDespesa, $i)->c333_sequencial;
                    }

                    $mensagem  = "Não é possível alterar o valor de teto, pois ficará menor que o valor já utilizado.\n";
                    $mensagem .= "Códigos das previsões de despesas que estão utilizando este saldo: " . implode(", ", $codigosPrevisaoDespesa) . ".";
                    throw new BusinessException($mensagem);
                }

                $daoTetoOrcamentario->c40_valor_disponivel = $daoTetoOrcamentario->c40_valor_disponivel == 0 ? '0' : $daoTetoOrcamentario->c40_valor_disponivel;

                $daoTetoOrcamentario->c40_sequencial = $parametros->codigo;
                $daoTetoOrcamentario->alterar($parametros->codigo);
            } else {
                $daoTetoOrcamentario->c40_valor_disponivel = $parametros->valorTeto;
                $daoTetoOrcamentario->c40_sequencial = null;
                $daoTetoOrcamentario->incluir(null);
            }

            if ($daoTetoOrcamentario->erro_status == 0) {
                throw new DBException("Erro ao salvar o teto orçamentário.");
            }

            $retorno->mensagem = 'Teto orçamentário salvo com sucesso!';
            $retorno->sequencial = $daoTetoOrcamentario->c40_sequencial;
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $eErro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);
