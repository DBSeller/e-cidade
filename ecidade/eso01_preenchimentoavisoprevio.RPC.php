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

$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {
    switch ($oParam->exec) {
        case 'buscarAvaliacao':
            if (empty($oParam->cgm)) {
               throw new BusinessException("Cgm não informado.");
            }
            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::AVISO_PREVIO);

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($formularioId);
            $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
            if (!empty($oParam->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($oParam->preenchimento);
                $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
                $avaliacaoEsocial->setCodigoGrupoResposta($oParam->preenchimento);
                $oRetorno->preenchimento = $oParam->preenchimento;
            } else {
                // busca preenchimento pela matricula e empregador
                $dao = new cl_avaliacaogruporespostaavisoprevio;
                $aCampos = array("distinct eso07_avaliacaogruporesposta as preenchimento");
                $aWhere  = array("eso07_regist = {$oParam->matricula} ", "eso07_empregador = {$oParam->cgm}");
                $aOrder  = array("eso07_avaliacaogruporesposta desc limit 1");
                $sql     = $dao->buscaPreenchimento($aCampos, $aWhere, $aOrder, db_getsession("DB_instit"));
                $rs      = db_query($sql);

                if ($rs) {
                    if (pg_num_rows($rs) > 0) {
                        $oStd = db_utils::fieldsMemory($rs, 0);
                        $avaliacaoEsocial->setCodigoGrupoResposta($oStd->preenchimento);
                        $oRetorno->preenchimento = $oStd->preenchimento;
                    }
                }

            }
            $oServidor  = ServidorRepository::getInstanciaByCodigo($oParam->matricula);
            $avaliacaoEsocial->setServidor($oServidor);
            $avaliacaoEsocial->trazerSugestoes(true);
            $avaliacaoEsocial->setAvisoPrevio(true);
            $avaliacaoEsocial->setCgm(CgmFactory::getInstanceByCgm($oParam->cgm));


            $oRetorno->oFormulario = $avaliacaoEsocial->getObject();
            break;

        case 'buscaCgmEmpregador':
            if (empty($oParam->matricula)) {
                throw new BusinessException("Matrícula não informada");
            }
            $sql = "select
                        rhlota.r70_numcgm  as cgm
                    FROM
                        rhpessoal
                        inner JOIN rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                            and rhpessoalmov.rh02_anousu = fc_anofolha(1) and rhpessoalmov.rh02_mesusu = fc_mesfolha(1)
                        inner join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota
                            and rhlota.r70_ativo = true
                    where
                        r70_ativo is true
                        and rhpessoal.rh01_regist = {$oParam->matricula}
                        and rhlota.r70_instit = {$oParam->instituicao}";

            $rs = db_query($sql);

            if (!$rs) {
                throw new BusinessException("Não foi possível buscar o empregador.");

            }
            if (pg_num_rows($rs) == 0) {
                throw new BusinessException("Não foi localizado o empregador.");

            }
            $oStd = db_utils::fieldsMemory($rs, 0);
            $oRetorno->cgm = $oStd->cgm;
            break;

        case 'salvarAvaliacao':
            if (empty($oParam->iCGM)) {
                throw new BusinessException("Cgm não informado.");
            }

            if (empty($oParam->matricula)) {
                throw new ParameterException("Matrícula não informada.");
            }

            $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->iCodigoAvaliacao);
            $oAvaliacao->setAvaliacaoGrupo();

            $iCodigoGrupoPerguntas = null;

            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }

            $aParametros = array('matricula' => $oParam->matricula);

            if (!empty($oParam->iCodigoPreenchimento)) {
                $aParametros["iCodigoPreenchimento"] = $oParam->iCodigoPreenchimento;
            }

            $oAvaliacaoESocial = new AvaliacaoESocial();
            $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
            $oAvaliacaoESocial->setCgm(CgmFactory::getInstanceByCgm($oParam->iCGM));
            $oAvaliacaoESocial->setPerguntasRespostas($oParam->aPerguntasRespostas);
            $oAvaliacaoESocial->salvar($iCodigoGrupoPerguntas, "avisoprevio", $aParametros);

            $oRetorno->preenchimento = $oAvaliacao->getAvaliacaoGrupo();
            $oRetorno->mensagem = "Avaliação salva com sucesso.";
            break;
    }
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}

echo JSON::create()->stringify($oRetorno);
