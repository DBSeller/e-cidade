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

use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaInformacaoComplementar;
use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Servico\LinhaColunaServico;
use ECidade\Configuracao\RelatorioLegal\Servico\LinhaServico;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

db_inicio_transacao();

try {
    $resposta = new stdClass();
    $resposta->mensagem = '';
    $resposta->erro = false;
    $resposta->code = 200;
    $parametros = JSON::requestParameters();

    $parametros->anoSessao = db_getsession("DB_anousu");
    $linhaServico = new LinhaServico($parametros);

    switch ($parametros->acao) {
        case 'salvarConfiguracao':
            $padrao = !isset($parametros->customizada);
            $lancamentos = array();

            $informacoesComplementares = JSON::create()->parse($parametros->informacoesComplementares);

            if (!empty($informacoesComplementares)) {
                $informacoesComplementaresSalvas = $linhaServico->salvarLinhaInformacaoComplementar($padrao);
            }

            foreach ($informacoesComplementaresSalvas as $informacaoComplementar) {
                $sequencial = $informacaoComplementar->getInformacaoComplementarLancamento()->getSequencial();
                $sigla = $informacaoComplementar->getSigla();

                $codigoLancamento = $informacaoComplementar->getInformacaoComplementarLancamento()->getSequencial();

                if (empty($lancamentos[$codigoLancamento])) {
                    $lancamentos[$codigoLancamento] = array();
                }

                if (empty($lancamentos[$codigoLancamento]['lancamento'])) {
                    $lancamentos[$codigoLancamento]['lancamento'] = $sequencial;
                    $exclusao = $informacaoComplementar->getInformacaoComplementarLancamento()->isExclusao();
                    $lancamentos[$codigoLancamento]['exclusao'] = $exclusao ? 'SIM' : 'NÃO';
                }

                $lancamentos[$codigoLancamento][$sigla] = $informacaoComplementar->getValor();
            }

            $resposta->lancamentos = $lancamentos;
            $resposta->mensagem = 'Configuração salva com sucesso!';
            break;
        case 'buscarConfiguracao':
            $padrao = !isset($parametros->customizada);
            $lancamentos = array();

            $linhaInformacoesComplementares = $linhaServico->buscarLinhaInformacaoComplementar($padrao);

            $informacoes = array_map(function (LinhaInformacaoComplementar $linhaInformacaoComplementar) use (
                &$lancamentos
            ) {
                $sequencial = $linhaInformacaoComplementar->getInformacaoComplementarLancamento()
                    ->getSequencial();
                $sigla = $linhaInformacaoComplementar->getSigla();

                if (empty($lancamentos[$sequencial]['lancamento'])) {
                    $lancamentos[$sequencial]['lancamento'] = $sequencial;
                    $exclusao = $linhaInformacaoComplementar->getInformacaoComplementarLancamento()->isExclusao();
                    $lancamentos[$sequencial]['exclusao'] = $exclusao ? 'SIM' : 'NÃO';
                }

                $lancamentos[$sequencial][$sigla] = $linhaInformacaoComplementar->getValor();

                return $linhaInformacaoComplementar->toArray();
            }, $linhaInformacoesComplementares);


            $resposta->informacoes = $informacoes;
            $resposta->lancamentos = array_values($lancamentos);
            break;
        case 'reordenarLinhas':
            $codigoLinhas = $parametros->linhas;
            $relatorio = RelatorioRegistry::get($parametros->relatorio);
            LinhaColunaRepositorio::atualizaFormulaLinhas($relatorio, $codigoLinhas);
            $resposta->mensagem = 'Linhas reordenadas com sucesso.';
            break;
        case 'decomporValorLinha':
            $resposta->decomposicao = $linhaServico->decomporValor();
            break;
        case 'salvarLinhaColuna':
            $colunas = JSON::create()->parse($parametros->colunas);
            $periodos = JSON::create()->parse($parametros->periodos);

            foreach ($colunas as $coluna) {
                $parametros->coluna = $coluna->codigo;
                $parametros->ordem = $coluna->ordem;
                $linhaColunaServico = new LinhaColunaServico($parametros);
                $linhaColunaServico->excluirPorColuna();

                foreach ($periodos as $codigoPeriodos) {
                    $parametros->periodo = $codigoPeriodos;
                    $linhaColunaServico->setParametros($parametros);
                    $linhaColunaServico->salvar();
                }
            }
            $resposta->mensagem = "Vínculo da(s) coluna(s) com a linha salvo com sucesso.";
            break;
        case 'buscarLinhaColunas':
            $linhaColunaServico = new LinhaColunaServico($parametros);
            $linhaColunas = $linhaColunaServico->buscarPorRelatorioLinha();

            $resposta->linhaColunas = array();
            foreach ($linhaColunas as $linhaColuna) {
                $codigoColuna = $linhaColuna->getColuna()->getSequencial();
                $periodo = PeriodoRegistry::get($linhaColuna->getPeriodo());
                if (!array_key_exists($codigoColuna, $resposta->linhaColunas)) {
                    $stdLinhaColuna = new stdClass();
                    $stdLinhaColuna->codigoColuna = $codigoColuna;
                    $stdLinhaColuna->ordem = $linhaColuna->getOrdem();
                    $stdLinhaColuna->descricao = $linhaColuna->getColuna()->getDescricao();
                    $stdLinhaColuna->formula = $linhaColuna->getFormula();
                    $stdLinhaColuna->campo = $linhaColuna->getColuna()->getNome();
                    $stdLinhaColuna->periodos = array();
                    $stdLinhaColuna->periodos[$periodo->getSequencial()] = $periodo->getSigla();
                    $resposta->linhaColunas[$codigoColuna] = $stdLinhaColuna;
                    continue;
                }
                $resposta->linhaColunas[$codigoColuna]->periodos[$periodo->getSequencial()] = $periodo->getSigla();
            }

            foreach ($resposta->linhaColunas as $linhaColuna) {
                asort($linhaColuna->periodos);
                $linhaColuna->periodosDescricao = implode(", ", $linhaColuna->periodos);
            }

            break;
        case 'excluirLinhaColunas':
            $linhaColunaServico = new LinhaColunaServico($parametros);
            $linhaColunaServico->excluirPorColuna();
            $resposta->mensagem = "Vínculo da coluna com a linha excluído com sucesso.";
            break;
        case 'excluirInformacaoComplementarLancamento':
            $padrao = !isset($parametros->customizada);

            $linhaServico->excluirLinhaInformacaoComplementar($padrao);
            $resposta->mensagem = 'Lançamento excluído com sucesso!';
            break;
    }
} catch (Exception $exception) {
    $resposta->erro = true;
    $resposta->mensagem = $exception->getMessage();
    $resposta->code = $exception->getCode();
}

db_fim_transacao($resposta->erro);

echo JSON::create()->stringify($resposta);

exit($resposta->code);
