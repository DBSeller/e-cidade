<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\ESocial\Entity\RemuneracaoRGPS;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Service\RemuneracaoRGPSService;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOutrosVinculos;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaude;

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {
    switch ($oParam->executa) {
        case 'buscarDadosCGM':

            if (empty($oParam->cgm)) {
                throw new ParameterException('CGM não informado.');
            }

            if (empty($oParam->ano)) {
                throw new ParameterException('Ano da competência não informado.');
            }

            if (empty($oParam->mes)) {
                throw new ParameterException('Mês da competência não informado.');
            }

            $remuneracaoRGPSService = new RemuneracaoRGPSService();
            $remuneracaoRGPSService->setAnoCompetencia($oParam->ano);
            $remuneracaoRGPSService->setMesCompetencia($oParam->mes);
            $remuneracoesRGPS = $remuneracaoRGPSService->buscarPorCGM(CgmRepository::getByCodigo($oParam->cgm));
            $oRetorno->matriculas = array();

            foreach ($remuneracoesRGPS as $remuneracaoRGPS) {
                $dadosMatricula = new stdClass();
                $dadosMatricula->matricula = $remuneracaoRGPS->getServidor()->getMatricula();
                $dadosMatricula->outrosVinculos = array();
                $dadosMatricula->planosSaude = array();
                $dadosMatricula->pagamentos = $remuneracaoRGPS->getPagamentos();
                $dadosMatricula->dadosTrabalhador = $remuneracaoRGPS->getDadosTrabalhador();

                if(count($remuneracaoRGPS->getServidorOutrosVinculos()) > 0) {
                    $dadosMatricula->outrosVinculos[] = array_map(function (ServidorOutrosVinculos $servidorOutrosVinculos) {
                        return $servidorOutrosVinculos->toArray();
                    }, $remuneracaoRGPS->getServidorOutrosVinculos());
                }

                if(count($remuneracaoRGPS->getPlanoSaude()) > 0) {
                    $dadosMatricula->planosSaude[] = array_map(function (ServidorOperadoraSaude $servidorOperadoraSaude) {
                        return $servidorOperadoraSaude->toArray();
                    }, $remuneracaoRGPS->getPlanoSaude());
                }

                $oRetorno->matriculas[] = $dadosMatricula;
            }

            break;

        case 'salvarAvaliacao':
            if(empty($oParam->cgm)) {
                throw new ParameterException('CGM não informado.');
            }

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->avaliacao);
            $avaliacao->setAvaliacaoGrupo($oParam->avaliacaogruporesposta);

            $avaliacaoESocial = new AvaliacaoESocial();
            $avaliacaoESocial->setAvaliacao($avaliacao);
            $avaliacaoESocial->setCgm(CgmFactory::getInstanceByCgm($oParam->cgm));
            $avaliacaoESocial->setPerguntasRespostas($oParam->perguntasRespostas);
            $avaliacaoESocial->salvar(null, Tipo::REMUNERACAO_RGPS, (array) $oParam);

            $oRetorno->mensagem = "Avaliação salva com sucesso.";

            break;

        case 'buscar':
            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(RemuneracaoRGPS::AVALIACAO);

            $avaliacaoAdapter = new AvaliacaoEsocialAdapter($avaliacao);
            $avaliacaoAdapter->setRemuneracaoRGPS(true);

            if (!empty($parametros->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($parametros->preenchimento);
                $avaliacaoAdapter->setCodigoGrupoResposta($parametros->preenchimento);
            }

            $retorno->formulario = $avaliacaoAdapter->getObject();

            break;
    }
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}

echo $oJson->stringify($oRetorno);
