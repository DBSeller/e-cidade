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

use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';
$instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();
$_SESSION["DB_desativar_account"] = true;
try {

    db_inicio_transacao();
    switch ($oParam->exec) {
        case 'buscarAvaliacao':
            if (empty($oParam->cgm)) {
                throw new BusinessException("Cgm não informado.");
            }
            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::DESLIGAMENTO_SERVIDOR);

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($formularioId);
            $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
            if (!empty($oParam->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($oParam->preenchimento);
                $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
                $avaliacaoEsocial->setCodigoGrupoResposta($oParam->preenchimento);
                $oRetorno->preenchimento = $oParam->preenchimento;
            } else {
                // busca preenchimento pela matricula e empregador
                $dao = new cl_avaliacaogruporespostarhpesrescisao();
                $aCampos = array("distinct  eso15_avaliacaogruporesposta as preenchimento");
                $aWhere  = array("eso15_regist = {$oParam->matricula} ", "eso15_cgmempregador = {$oParam->cgm}");
                $aOrder  = array(" eso15_avaliacaogruporesposta desc limit 1");
                $sql     = $dao->buscaPreenchimento($aCampos, $aWhere, $aOrder, $instituicaoSessao->getCodigo());
                $rs      = db_query($sql);

                if ($rs) {
                    if (pg_num_rows($rs) > 0) {
                        $oStd = db_utils::fieldsMemory($rs, 0);
                        $avaliacaoEsocial->setCodigoGrupoResposta($oStd->preenchimento);
                        $oRetorno->preenchimento = $oStd->preenchimento;
                    }
                }
            }

            $avaliacaoEsocial->trazerSugestoes(false);
            $avaliacaoEsocial->setRescisao(true);
            $avaliacaoEsocial->setCgm(CgmFactory::getInstanceByCgm($oParam->cgm));

            $oRetorno->oFormulario = $avaliacaoEsocial->getObject();
            break;

        case 'buscaCgmEmpregador':
            if (empty($oParam->matricula)) {
                throw new BusinessException("Matrícula não informada");
            }

            $iAno = DBPessoal::getAnoFolha();
            $iMes = DBPessoal::getMesFolha();
            $sql = "SELECT 
                        rhlota.r70_numcgm  as cgm,
                        rh05_recis as data_rescisao
                    FROM 
                        rhpessoal 
                        INNER JOIN rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                            AND rhpessoalmov.rh02_anousu = {$iAno}
                            AND rhpessoalmov.rh02_mesusu = {$iMes}
                        INNER JOIN rhlota ON rhlota.r70_codigo = rhpessoalmov.rh02_lota
                            AND rhlota.r70_ativo = true
                        LEFT JOIN rhpesrescisao ON rh05_seqpes = rh02_seqpes
                    WHERE
                        r70_ativo is true
                        AND rhpessoal.rh01_regist = {$oParam->matricula}
                        AND rhlota.r70_instit = {$oParam->instituicao}";

            $rs = db_query($sql);

            if (!$rs) {
                throw new BusinessException("Não foi possível buscar o empregador.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new BusinessException("Não foi localizado o empregador.");
            }

            $servidor = db_utils::fieldsMemory($rs, 0);
            $oRetorno->cgm = $servidor->cgm;

            if (!$servidor->data_rescisao) {
                throw new Exception('Servidor não possui rescisão/desligamento cadastrada no sistema.');
            }

            break;

        case 'salvarAvaliacao':
            if (empty($oParam->iCGM)) {
                throw new BusinessException("Cgm não informado.");
            }

            if (empty($oParam->matricula)) {
                throw new ParameterException("Matrícula não informada.");
            }

            $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->iCodigoAvaliacao);

            $iCodigoGrupoPerguntas = null;

            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }

            $aParametros = array('matricula' => $oParam->matricula);

            if (!empty($oParam->iCodigoPreenchimento)) {
                $aParametros["iCodigoPreenchimento"] = $oParam->iCodigoPreenchimento;
            }
            $cgmEmpregador = CgmFactory::getInstanceByCgm($oParam->iCGM);

            $oDaoRhPessoal = new cl_rhpessoal();
            $rsSituacao = db_query($oDaoRhPessoal->sql_query_rescisao(
                null,
                "rh05_recis",
                null,
                "rhpessoal.rh01_regist = {$oParam->matricula}"
            ));

            $oRescisao = db_utils::fieldsMemory($rsSituacao, 0);
            $sDataRescisao = str_replace('-', '', $oRescisao->rh05_recis);
            $ano = substr($sDataRescisao, 0, 4);
            $mes = substr($sDataRescisao, 4, 2);

            $oServidor = ServidorRepository::getInstanciaByCodigo($oParam->matricula, $ano, $mes);

            $oAvaliacaoESocial = new AvaliacaoESocial();
            $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
            $oAvaliacaoESocial->setServidor($oServidor);
            $oAvaliacaoESocial->setCgm($cgmEmpregador);
            $oAvaliacaoESocial->setPerguntasRespostas($oParam->aPerguntasRespostas);
            $oAvaliacaoESocial->salvar($iCodigoGrupoPerguntas, Tipo::DESLIGAMENTO_SERVIDOR, $aParametros);

            $oRetorno->preenchimento = $oAvaliacao->getAvaliacaoGrupo();
            $oRetorno->mensagem = "Avaliação salva com sucesso.";
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
            $oDaoAvaliacaoRespostaRescisao = new cl_avaliacaogruporespostarhpesrescisao();
            $oDaoAvaliacaoRespostaRescisao->excluir(null, "eso15_avaliacaogruporesposta={$oParam->codigo_resposta}");
            if ($oDaoAvaliacaoRespostaRescisao->erro_status == 0) {
                throw new BusinessException('Não é possível remover desligamento. Verifique.');
            }
            Resposta::remover($resposta);
            $oRetorno->mensagem = "Desligamento removido com sucesso.";
            break;
    }
    db_fim_transacao(false);
} catch (Exception $e) {
    if (db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->erro = true;
    $oRetorno->mensagem = $e->getMessage();
}
unset($_SESSION["DB_desativar_account"]);
echo JSON::create()->stringify($oRetorno);
