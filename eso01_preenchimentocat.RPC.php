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

use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;

$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {
    db_inicio_transacao();
    switch ($oParam->exec) {
        case 'buscarAvaliacao':
            if (empty($oParam->iCGM)) {
                throw new BusinessException("Cgm não informado.");
            }
            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::CAT);

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($formularioId);
            $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
            if (!empty($oParam->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($oParam->preenchimento);
                $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
                $avaliacaoEsocial->setCodigoGrupoResposta($oParam->preenchimento);
            }

            $avaliacaoEsocial->setCgm(CgmFactory::getInstanceByCgm($oParam->iCGM));
            $avaliacaoEsocial->setCat(true);
            $oRetorno->oFormulario = $avaliacaoEsocial->getObject();
            break;

        case 'salvarAvaliacao':
            if (empty($oParam->iCGM)) {
                throw new BusinessException("Cgm não informado.");
            }
            $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->iCodigoAvaliacao);
            $oAvaliacao->setAvaliacaoGrupo();

            $iCodigoGrupoPerguntas = null;

            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }

            if (empty($oParam->cpf)) {
                throw new BusinessException("CPF não informado.");
            }

            if (empty($oParam->dataAcidente)) {
                $msg = "Data do Acidente não informada.\n\rPor favor informe ela no grupo 2 - Comunicação de Acidente de Trabalho - CAT";
                throw new BusinessException($msg);
            }

            $aParametros = ['matricula' => $oParam->matricula, 'data' => $oParam->dataAcidente, 'cpf' => $oParam->cpf];

            if (!empty($oParam->iCodigoPreenchimento)) {
                $aParametros["iCodigoPreenchimento"] = $oParam->iCodigoPreenchimento;
            }

            $oAvaliacaoESocial = new AvaliacaoESocial();
            $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
            $oAvaliacaoESocial->setCgm(CgmFactory::getInstanceByCgm($oParam->iCGM));
            $oAvaliacaoESocial->setPerguntasRespostas($oParam->aPerguntasRespostas);
            $oAvaliacaoESocial->salvar($iCodigoGrupoPerguntas, Tipo::CAT, $aParametros);

            // Pega a Avaliacao salva
            $oAvaliacao  = $oAvaliacaoESocial->getAvaliacao();
            // Seta o preenchimento
            $oRetorno->preenchimento = $oAvaliacao->getAvaliacaoGrupo();
            $oRetorno->mensagem      = "Avaliação salva com sucesso.";
            break;
        case 'remover':
            $formulario = \ECidade\Configuracao\Formulario\Repository\Formulario::getById((int)$oParam->formulario);
            if (empty($formulario)) {
                throw new BusinessException("Formulário de código ({$oParam->formulario}) não encontrado no sistema. Verifique.");
            }

            $resposta = Resposta::getBydId($formulario, (int)$oParam->codigo_resposta);
            if (empty($resposta)) {
                throw new BusinessException('Resposta não encontrada no sistema. Verifique.');
            }
            $oDaoAvaliacaoCat = new cl_esoacidentetrabalho();
            $oDaoAvaliacaoCat->excluir(null, "eso36_avaliacaogruporesposta={$oParam->codigo_resposta}");
            if ($oDaoAvaliacaoCat->erro_status == 0) {
                throw new BusinessException('Não é possível remover o CAT. Verifique.');
            }
            Resposta::remover($resposta);
            $oRetorno->mensagem = "CAT removido com sucesso.";
            break;
    }
    if (db_utils::inTransaction()) {
        db_fim_transacao();
    }
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}

echo JSON::create()->stringify($oRetorno);
