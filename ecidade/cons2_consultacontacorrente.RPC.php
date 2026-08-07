<?php
/**
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

use ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter\Csv;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter\Json;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Processamento\Processamento as ProcessamentoConsulta;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Repository\ContaCorrente as ContaCorrenteRepository;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Repository\Visao;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
ini_set('memory_limit', '-1');

$parametros = \JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno = new stdClass();
$retorno->message = '';
$retorno->erro = false;
define("MENSAGENS", "con4_matrizsaldocontabil.RPC.json");

$instituicao = InstituicaoRepository::getInstituicaoSessao();
try {
    db_inicio_transacao();
    switch ($parametros->exec) {
        case 'gerarRelatorio':


            //dd($parametros->filtros->colunas,$parametros->filtros->atributos);

            $datainicial = new DateTime($parametros->filtros->data_inicial);
            $dataFinal   = new DateTime($parametros->filtros->data_final);
            $param       = new stdClass();
            $param->filtros = new stdClass();
            $param->filtros->estrutural = $parametros->filtros->estrutural_inicial;
            $param->filtros->contas     = $parametros->filtros->contas;
            $param->filtros->atributos  = $parametros->filtros->atributos;
            $param->filtros->conta_contabil_codigo = $parametros->filtros->conta_contabil;
            $param->filtros->conta_contabil_ano    = db_getsession('DB_anousu');
            $param->filtros->documentos            = $parametros->filtros->documentos;
            $param->filtros->reduzido = $parametros->filtros->reduzido;
            $contaCorrente = ContaCorrenteRepository::getByCodigo($parametros->filtros->conta_corrente);
            $colunas = $parametros->filtros->colunas;
            $formatter = $parametros->tipo == 1 ? new Json() : new Csv();
            $processamento = new ProcessamentoConsulta($instituicao, $datainicial, $dataFinal, $contaCorrente);
            $processamento->setAgruparPorDocumentoContabil(count($param->filtros->documentos) > 0);
            $consulta = new ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Consulta(
                $formatter,
                $processamento
            );
            $consulta->setMsc(false);

            if ($parametros->fonte_de_dados == 2) {

                $consulta->setMsc(true);
            }

            $consulta->setFiltros($param->filtros);
            $consulta->setColunas($colunas);

            $retorno->configuracaoVisao = $consulta->getConfiguracaoPadrao();
            if ( ! empty($parametros->codigoVisao) ) {

                $visao = Visao::getPorCodigo($parametros->codigoVisao);
                $consulta->setVisao($visao);
                $retorno->configuracaoVisao = \JSON::create()->parse($visao->getFiltrosJson());

            }
            $dados          = $consulta->emitir();

            //dd($dados);


            $retorno->tipo  = $parametros->tipo;
            $retorno->dados = $dados;
            $retorno->mostrarColunaDocumentos = $processamento->agrupaPorDocumentoContabil();
            break;
        case 'getAtributos':

            $iFonteDeDados = $parametros->fonte_de_dados;
            $contaCorrente = ContaCorrenteRepository::getByCodigo($parametros->conta_corrente);
            $iReduzido = $parametros->reduzido;

            switch($iFonteDeDados){

                case "1":
                    $atributos = $contaCorrente->getAtributos();
                    break;

                case "2":
                    $atributos = $contaCorrente->getAtributosMSC($parametros->codigo_conta, $iReduzido);
                    break;
            }

            $retorno->atributos = array();
            foreach ($atributos as $atributo) {
                $dadosAtributo = new stdClass();
                $dadosAtributo->codigo = $atributo->getCodigo();
                $dadosAtributo->sigla = $atributo->getSigla();
                $dadosAtributo->descricao = $atributo->getNome();
                $dadosAtributo->ajuda = $atributo->getAjuda();
                $retorno->atributos[] = $dadosAtributo;
            }

            break;


        case 'getContaCorrentePorContaContabil':
            if (empty($parametros->codigo_conta)) {
                throw new ParameterException("Código da conta não informado.");
            }

            $contaPlano = ContaPlanoPCASPRepository::getContaByCodigo(
                $parametros->codigo_conta,
                db_getsession('DB_anousu')
            );
            $dadosContaCorrente = $contaPlano->getDadosSistemaContaCorrente();
            if (empty($dadosContaCorrente)) {
                throw new BusinessException("A conta {$contaPlano->getCodigoConta()} - {$contaPlano->getDescricao()} não possui nenhum conta corrente vinculado.");
            }
            $retorno->codigo_conta_corrente = $dadosContaCorrente->codigo;
            $retorno->descricao_conta_corrente = $dadosContaCorrente->descricao;

            break;
        case 'getLancamentos':

            $lista_lancamentos = $parametros->lista_lancamentos;
            $reduzido          = $parametros->reduzidos;

            if (empty($lista_lancamentos)) {
                throw new ParameterException("Lançamentos não encontrados para o conta corrente selecionado.");
            }

            if (empty($reduzido)) {
                throw new ParameterException("Reduzido do conta corrente não encontrado.");
            }

            $daoLancamentos = new cl_conlancamval();
            $sqlLancamentos = $daoLancamentos->sql_query_lancamentos_documento($reduzido, $lista_lancamentos);
            $rsLancamentos  = db_query($sqlLancamentos);
            if (!$rsLancamentos){
                throw new Exception("Ocorreu algo inesperado ao buscar lancamentos do conta corrente.");
            }

            $lancamentos    = db_utils::getCollectionByRecord($rsLancamentos);
            $retorno->lancamentos = $lancamentos;
            break;
    }
    db_fim_transacao(false);
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $retorno->erro = true;
    $retorno->message = $oErro->getMessage();
}

echo \JSON::create()->stringify($retorno);
