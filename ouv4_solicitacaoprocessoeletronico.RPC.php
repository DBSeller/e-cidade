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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\V3\Extension\Registry;
use ECidade\Tributario\Issqn\Model\ProcessoEletronicoGrauRisco;
use ECidade\Configuracao\Workflow\Filter\Transicao as FiltroTransicao;
use ECidade\Configuracao\Workflow\Repository\Workflow as WorkflowRepository;
use ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Entity\AlvaraOnline as EntidadeAlvaraOnline;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Repository\ConsultaProcesso as RepositoryConsultaProcesso;
use ECidade\Patrimonial\Protocolo\Servicos\InclusaoProcesso;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Helper\ProcessoEletronicoHelper;
use ECidade\Lib\File\FileEstorage;
use ECidade\Tributario\Issqn\Acao\Transicao\Factory\AcaoFactory;

$parametro = JSON::requestParameters();
$retorno = new stdClass();
$retorno->status = false;
$retorno->mensagem = '';

$containerTributario   = Registry::get('app.container')->get('tributario.container');
$containerPatrimonial  = Registry::get('app.container')->get('patrimonial.container');
$containerConfiguracao = Registry::get('app.container')->get('configuracao.container');

$serviceProcessosAlvaraOnline = $containerTributario->get('Inscricao\Service\AlvaraOnline');
$parameterBagProcessoEletronico = $containerTributario->get('ProcessoEletronicoParameterBag');

$serviceProcessoEletronico    = $containerPatrimonial->get('Processo\ProcessoEletronico\Service\ConsultaProcessos');
$consultaProcessosRepository  = $containerPatrimonial->get('Processo\ProcessoEletronico\Repository\ConsultaProcessos');
$inclusaoCgmService           = $containerPatrimonial->get('Servicos\InclusaoCgmLegacy');
$inclusaoProcessoService      = $containerPatrimonial->get('Servicos\InclusaoProcesso');

$processoEletronicoGrauRiscoRepository = $containerTributario->get('ProcessoEletronicoGrauRiscoRepository');

$transicaoService = $containerConfiguracao->get('Workflow\Service\Transicao');

$filtroProcesso   = new FiltroListagemProcessos();
$filtroAtividades = new FiltroListagemAtividades();

try {
    db_inicio_transacao();

    switch ($parametro->exec) {

        case "buscarProcessosAlvara":

            $filtroProcesso->setCodigoInstituicao(db_getsession("DB_instit"));
            $filtroProcesso->setCodigoDepartamento(db_getsession("DB_coddepto"));
            $filtroProcesso->setSituacaoOuvidoriaAtendimento(1); // Ativo

            if(!empty($parametro->dataInicio) && !empty($parametro->dataFim)) {

                $dataInicio = new DBDate($parametro->dataInicio);
                $dataFim    = new DBDate($parametro->dataFim);

                $filtroProcesso->setDataInicio($dataInicio);
                $filtroProcesso->setDataFim($dataFim);
            }

            $retorno->processos = $serviceProcessoEletronico->listarProcessos($filtroProcesso);
            break;

        case "solicitacaoProcessoAlvara":

            if(empty($parametro->numeroProcesso)) {
                throw new DBException("Informa um número de processo.");
            }

            if(empty($parametro->anoProcesso)) {
                throw new DBException("Informe o ano de processo.");
            }

            $filtroProcesso->setNumeroProcesso($parametro->numeroProcesso);
            $filtroProcesso->setAnoProcesso($parametro->anoProcesso);
            $filtroProcesso->setCodigoTipoProcesso($parametro->tipoProcesso);

            if (isset($parametro->codigoProcessoProtocolo) &&
                !is_null($parametro->codigoProcessoProtocolo)
            ) {
                $filtroProcesso->setCodigoProcessoProtocolo($parametro->codigoProcessoProtocolo);
            }

            $retorno->solicitacaoProcessoAlvara = $serviceProcessosAlvaraOnline->retornarProcessoAlvara($filtroProcesso, $filtroAtividades);
            break;

        case 'aprovar':
            /*
            * Parâmetros:
            *   - grauRisco
            *   - processo
            *   - ano
            */
            $filtroProcesso->setNumeroProcesso($parametro->processo);
            $filtroProcesso->setAnoProcesso($parametro->ano);
            $filtroProcesso->setCodigoTipoProcesso($parametro->tipoProcesso);

            $dados = getDadosProcesso($serviceProcessosAlvaraOnline, $filtroProcesso, $filtroAtividades);


            $processoOuvidoria = getProcessoOuvidoria($consultaProcessosRepository, $filtroProcesso);
            $acao = $parameterBagProcessoEletronico->getAcaoByTipoProcesso($filtroProcesso->getCodigoTipoProcesso());
            $arrCgms = incluirCgms($inclusaoCgmService, $dados, $acao);

            $nomeRequerente = null;
            if (!empty($arrCgms['cgmRequerente']) && $arrCgms['cgmRequerente']->getNome()) {
                $nomeRequerente = $arrCgms['cgmRequerente']->getNome();
            }

            $processo = $inclusaoProcessoService->aprovarProcesso(
                $arrCgms['cgmProcesso'],
                $processoOuvidoria->sequencial,
                $filtroProcesso->getCodigoTipoProcesso(),
                $nomeRequerente
            );
            anexarDocumentosProcesso($processo, $dados->documentos);

            $state = array(
                "q151_processo" => $processo->getCodProcesso(),
                "q151_graurisco" => $parametro->grauRisco
            );

            $processoEletronicoGrauRisco = new ProcessoEletronicoGrauRisco();
            $processoEletronicoGrauRisco->fromState($state);
            $processoEletronicoGrauRiscoRepository->save($processoEletronicoGrauRisco);

            $depto = getDeptoProcessoByAndamento($processo->getCodigoAndamento());

            // $idWorkflow = InclusaoProcesso::TIPO_PROCESSO;

            // $workflowRepository = new WorkflowRepository();''
            // $workflow           = $workflowRepository->getById($idWorkflow);
            // $atividadesWorkflow = $workflowRepository->getAtividadesDoWorkflow($workflow);
            // reset($atividadesWorkflow);

            // $atividadeOrigem  = current($atividadesWorkflow);
            // $atividadeDestino = end($atividadesWorkflow);

            // $filtroTransicao = new FiltroTransicao();
            // $filtroTransicao->setProcesso($processo);
            // $filtroTransicao->setAtividadeOrigem($atividadeOrigem);
            // $filtroTransicao->setAtividadeDestino($atividadeDestino);

            // $transicaoService->run($filtroTransicao);
            // $despacho = $transicaoService->getResultado($filtroTransicao);
            // $inclusaoProcessoService->andamentoProcesso($processo, $despacho);

            $acao = AcaoFactory::factory(AcaoFactory::ACAO_GERAR_INSCRICAO, $processo);
            $acao->validate();
            $acao->run();

            ProcessoEletronicoHelper::andamentoProcesso(
                 $processo
                ,"Gerada inscrição ".$acao->getInscricao()."."
                ,$depto
                ,$depto
            );

            $retorno->mensagem  = "Processo ".$processo->getNumeroProcesso() . "/" . $processo->getAnoProcesso() . " Inserido com sucesso.\n";
            $retorno->mensagem .= "Gerada inscrição ".$acao->getInscricao().".";
            break;

        case 'rejeitar':
            /*
            *   - exec: salvarRejeicao
            *   - motivo
            *   - processo
            *   - ano
            */

            $filtroProcesso->setNumeroProcesso($parametro->processo);
            $filtroProcesso->setAnoProcesso($parametro->ano);
            $filtroProcesso->setCodigoTipoProcesso($parametro->tipoProcesso);

            $dados = getDadosProcesso($serviceProcessosAlvaraOnline, $filtroProcesso, $filtroAtividades);
            $processoOuvidoria = getProcessoOuvidoria($consultaProcessosRepository, $filtroProcesso);
            $arrCgms = incluirCgms($inclusaoCgmService, $dados, 'REJEITAR');

            $processo = $inclusaoProcessoService->rejeitarProcesso($arrCgms['cgmProcesso'], $processoOuvidoria->sequencial, $filtroProcesso->getCodigoTipoProcesso(), $parametro->motivo);
            anexarDocumentosProcesso($processo, $dados->documentos);

            $retorno->mensagem = 'Processo rejeitado com sucesso!';

            break;

        case 'downloadArquivo':

            $fileEstorage       = new FileEstorage();
            $retorno->fileName = $fileEstorage->getPath($parametro->id);
            break;

        default:
            return;
    }

    db_fim_transacao(false);

} catch (Exception $erro) {

    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);

function getDadosProcesso($serviceProcessosAlvaraOnline, $filtroProcesso, $filtroAtividades)
{
    return $dados = JSON::create()->parse(($serviceProcessosAlvaraOnline->retornarProcessoAlvara($filtroProcesso, $filtroAtividades)));
}

function getProcessoOuvidoria($consultaProcessosRepository, $filtroProcesso)
{
    $consultaProcessosRepository->tipoConsulta(RepositoryConsultaProcesso::CONSULTA_OBJETO_SOLICITACAO);
    $solicitacao = $consultaProcessosRepository->objetoSolicitacao($filtroProcesso);

    if(empty($solicitacao)) {
        throw new ParameterException("Não foi possí­vel identificar o id da solicitação.");
    }

    return $solicitacao;
}

function incluirCgms($inclusaoCgmService, $dados, $acao)
{
    if(empty($dados)) {
        throw new ParameterException("Não foi possí­vel buscar os dados da solicitação.");
    }

    $cgms = ProcessoEletronicoHelper::processaCgmsByDados($inclusaoCgmService, $dados, $acao);

    return $cgms;
}

function anexarDocumentosProcesso($processo, $documentos)
{
    if(empty($documentos)) {
        return null;
    }

    foreach ($documentos as $documento) {

        $daoProtprocessodocumento = new cl_protprocessodocumento();

        $daoProtprocessodocumento->p01_protprocesso  = $processo->getCodProcesso();
        $daoProtprocessodocumento->p01_descricao     = $documento->descricao;
        $daoProtprocessodocumento->p01_nomedocumento = $documento->value;
        $daoProtprocessodocumento->p01_usuario       = 1;
        $daoProtprocessodocumento->p01_data          = null;
        $daoProtprocessodocumento->p01_estorage      = 't';

        if(!$daoProtprocessodocumento->incluir(null)) {
            throw new DBException($daoProtprocessodocumento->erro_msg);
        }
    }


    return true;
}

function getDeptoProcessoByAndamento($codAndam)
{
    $clProcAndam    = new cl_procandam();
    $sql = $clProcAndam->sql_query_file($codAndam);
    $rs = \db_query($sql);

    if (pg_num_rows($rs) > 0) {
        return \db_utils::fieldsMemory($rs, 0)->p61_coddepto;
    }

}
