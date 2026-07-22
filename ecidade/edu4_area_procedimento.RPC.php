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

use ECidade\Educacao\Escola\Repository\AreaProcedimentoAvaliacaoRepository;
use ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao\AreaProcedimentoAvaliacaoResource;
use ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao\AreaProcedimentoResource;
use ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao\ProcedimentoResource;
use ECidade\Educacao\Escola\Service\AreaProcedimentoService;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta" . ".php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));


$parametros = JSON::requestParameters();
$retorno = (object)array('erro' => false, 'mensagem' => '');

$codigoEscola = db_getsession('DB_coddepto');
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'buscarProcedimentos':
            $retorno->procedimentos = ProcedimentoResource::toArray(
                ProcedimentoAvaliacaoRepository::getProcedimentoEscola(new Escola($codigoEscola))
            );
            break;
        case 'buscarProcedimentoArea':
            if (empty($parametros->codigoProcedimento)) {
                throw new Exception("Informe o procedimento de avaliação.");
            }
            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($parametros->codigoProcedimento);

            $service = new AreaProcedimentoService();
            $procedimentoArea = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);
            $retorno->procedimentoArea = null;
            if (!is_null($procedimentoArea)) {
                $turmasEncerradas = $service->hasTurmasEncerradas($procedimentoArea);
                $retorno->procedimentoArea = AreaProcedimentoResource::toObject($procedimentoArea);
                $retorno->turmasEncerradas = $turmasEncerradas;
            }

            break;

        case 'adicionarElementoAvaliacaoProcedimentoArea':
            break;

        case 'configurarResultadoProcedimentoArea':
            break;
        case 'salvarAvaliacao':
            if (empty($parametros->procedimentoVinculado)) {
                throw new Exception("Informe o procedimento de avaliação.");
            }
            if (empty($parametros->codigoPeriodoAvaliacao)) {
                throw new Exception("Informe o período de avaliação.");
            }

            if (empty($parametros->codigoFormaAvaliacao)) {
                throw new Exception("Informe a Forma de Avaliação.");
            }

            if (empty($parametros->ordemElementoProcedimento)) {
                throw new Exception("Informe o Elemento base para Cálculo.");
            }

            if (empty($parametros->formaObtencao)) {
                throw new Exception("Informe a Forma de Obtenção.");
            }

            $service = new AreaProcedimentoService();

            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                $parametros->procedimentoVinculado
            );
            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);

            if (is_null($areaProcedimento)) {
                $areaProcedimento = $service->criarAreaProcedimento($procedimento);
            }

            $service->salvarAvaliacoes($areaProcedimento, $parametros);

            $retorno->procedimentoArea = AreaProcedimentoResource::toObject($areaProcedimento);
            $retorno->mensagem = "Elemento de Avaliação salvo com sucesso.";
            break;

        case 'excluirElementoAvaliacao':
            if (empty($parametros->codigoElemento)) {
                throw new Exception("Informe um elemento para excluir.");
            }

            if (empty($parametros->codigoElemento)) {
                throw new Exception("Informe um Procedimento de Avaliação.");
            }

            $service = new AreaProcedimentoService();

            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                $parametros->procedimentoVinculado
            );
            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);

            $areaProcedimento = $service->excluirAvaliacao($areaProcedimento, $parametros->codigoElemento);

            $retorno->procedimentoArea = AreaProcedimentoResource::toObject($areaProcedimento);
            $retorno->mensagem = "Elemento do Procedimento de Avaliação excluido com sucesso.";
            break;

        case 'salvarResultado':
            if (empty($parametros->procedimentoVinculado)) {
                throw new Exception("Informe o procedimento de avaliação.");
            }
            if (empty($parametros->codigoTipoResultado)) {
                throw new Exception("Informe o Tipo de resultado.");
            }
            if (empty($parametros->formaObtencaoResultado)) {
                throw new Exception("Informe a Forma de Obtenção da Nota.");
            }
            if (empty($parametros->codigoFormaAvaliacaoResultado)) {
                throw new Exception("Informe o Forma de avaliação.");
            }

            $service = new AreaProcedimentoService();

            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                $parametros->procedimentoVinculado
            );
            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);

            if (is_null($areaProcedimento)) {
                throw new Exception("Adicione elementos ante de Configurar um resultado.");
            }

            $procedimentoArea = $service->salvarResultado($areaProcedimento, $parametros);
            $retorno->procedimentoArea = null;
            if (!is_null($procedimentoArea)) {
                $retorno->procedimentoArea = AreaProcedimentoResource::toObject($procedimentoArea);
            }
            $retorno->mensagem = "Resultado do Procedimento da Area Salvo com sucesso.";
            break;
        case 'salvarOrdemElementos':
            $service = new AreaProcedimentoService();
            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                $parametros->procedimentoVinculado
            );
            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);
            $areaProcedimentoAvaliacoes = $areaProcedimento->getAvaliacoes();

            $ordens = json_decode($parametros->ordens);
            foreach ($ordens as $ordem) {
                foreach ($areaProcedimentoAvaliacoes as $areaProcedimentoAvaliacao) {
                    if ($areaProcedimentoAvaliacao->getCodigo() == $ordem->codigo) {
                        $areaProcedimentoAvaliacao->setOrdem($novaOrdem = $ordem->novaOrdem);
                    }
                }
            }

            $service->salvarOrdemElementos($areaProcedimento);

            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);

            $retorno->procedimentoArea = AreaProcedimentoResource::toObject($areaProcedimento);
            $retorno->mensagem = "Ordem de Elementos salva com sucesso.";
            break;
        case 'excluirProcedimentoArea':
            $service = new AreaProcedimentoService();
            $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                $parametros->procedimentoVinculado
            );
            $areaProcedimento = $service->getAreaProcedimentoPorProcedimentoAvaliacao($procedimento);

            if (is_null($areaProcedimento)) {
                throw new Exception("Não há procedimento de avaliação por Área de conhecimento configurado para este procedimento.");
            }

            $areaProcedimento = $service->excluirAreaProcedimento($areaProcedimento);
            $retorno->mensagem = "Procedimento de avaliação por Área de conhecimento excluido com sucesso.";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
