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

use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\V3\Extension\Registry;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';
$instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();

try {
    switch ($oParam->exec) {
        case 'buscarAvaliacao':
            if (empty($oParam->cgm)) {
                throw new BusinessException("Cgm não informado.");
            }
            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::TERMINO_TRABALHADOR_SEM_VINCULO);

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($formularioId);
            $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);

            if (!empty($oParam->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($oParam->preenchimento);
                $avaliacaoEsocial = new AvaliacaoEsocialAdapter($avaliacao);
                $avaliacaoEsocial->setCodigoGrupoResposta($oParam->preenchimento);
                $oRetorno->preenchimento = $oParam->preenchimento;
            } else {
                // busca preenchimento pela matricula e empregador
                $dao = new cl_avaliacaogruporespostatertrabasemvinc();
                $aCampos = array("distinct  eso24_avaliacaogruporesposta as preenchimento");
                $aWhere  = array("eso24_rhpessoal = {$oParam->iMatricula} ", "eso24_cgmempregador = {$oParam->cgm}");
                $aOrder  = array(" eso24_avaliacaogruporesposta desc limit 1");
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

            $oServidor  = ServidorRepository::getInstanciaByCodigo($oParam->iMatricula);
            $avaliacaoEsocial->trazerSugestoes(false);
            $avaliacaoEsocial->setServidorSemVinculoTermino($oServidor);
            $avaliacaoEsocial->setCgm(CgmFactory::getInstanceByCgm($oParam->cgm));

            $oRetorno->oFormulario = $avaliacaoEsocial->getObject();

            $body = new stdClass();
            $body->idReferencia = $oParam->iMatricula;
            $body->idEvento = 'S-2300';
            $body->inscricaoEmpregador = $instituicaoSessao->getCNPJ();

            $service = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
            $service->setDados($body);

            $response = $service->request('GET');

            if (!$response) {
                throw new Exception("Deve ser enviado O Inicial do trabalhador sem ao eSocial antes de enviar o formulário de Termino .\nMenu: DB:RECURSOSHUMANOS > eSocial > Procedimentos > Preenchimento > Trabalhador sem Vínculo > Inicio ");
            }

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
            if (empty($oParam->iMatricula)) {
                throw new BusinessException("Matrícula não informada.");
            }

            $oAvaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($oParam->iCodigoAvaliacao);
            $oAvaliacao->setAvaliacaoGrupo();

            $iCodigoGrupoPerguntas = null;

            if (!empty($oParam->iCodigoGrupoPerguntas)) {
                $iCodigoGrupoPerguntas = $oParam->iCodigoGrupoPerguntas;
            }

            $aParametros = array('matricula' => $oParam->iMatricula);

            if (!empty($oParam->iCodigoPreenchimento)) {
                $aParametros["iCodigoPreenchimento"] = $oParam->iCodigoPreenchimento;
            }

            $cgmEmpregador = CgmFactory::getInstanceByCgm($oParam->cgmEmpregador);

            $oDaoRhPessoal = new cl_rhpessoal();
            $rsSituacao = db_query($oDaoRhPessoal->sql_query_rescisao(
                null,
                "rh05_recis",
                null,
                "rhpessoal.rh01_regist = {$oParam->iMatricula}"
            ));
            
            $oRescisao = db_utils::fieldsMemory($rsSituacao, 0);
            $sDataRescisao = str_replace('-', '', $oRescisao->rh05_recis);
            $ano = substr($sDataRescisao, 0, 4);
            $mes = substr($sDataRescisao, 4, 2);
            
            $oServidor = ServidorRepository::getInstanciaByCodigo($oParam->iMatricula);
            $oAvaliacaoESocial = new AvaliacaoESocial();
            $oAvaliacaoESocial->setAvaliacao($oAvaliacao);
            $oAvaliacaoESocial->setServidor($oServidor);
            $oAvaliacaoESocial->setCgm($cgmEmpregador);
            $oAvaliacaoESocial->setPerguntasRespostas($oParam->aPerguntasRespostas);
            $oAvaliacaoESocial->salvar($iCodigoGrupoPerguntas, Tipo::TERMINO_TRABALHADOR_SEM_VINCULO);

            $oRetorno->preenchimento = $oAvaliacao->getAvaliacaoGrupo();
            $oRetorno->mensagem = "Avaliação salva com sucesso.";
            break;

        case 'getMatriculas':
            $oCgm = UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario"))->getCGM();
            if (empty($oCgm)) {
                throw  new BusinessException("Usuário sem CGM vinculado.");
            }
            if (!$oCgm instanceof CgmFisico) {
                throw  new BusinessException("Cgm do Usuário está cadastradado como Pessoa Jurídica");
            }

            $aMatriculas = ServidorRepository::getServidoresByCgm($oCgm);

            if (count($aMatriculas) == 0) {
                throw  new BusinessException("Cgm não é servidor da instituição");
            }

            $oRetorno->matriculas = array_map(function (Servidor $oServidor) {

                $oStdMatricula = new \stdClass();
                $oStdMatricula->matricula = $oServidor->getMatricula();
                $oStdMatricula->nome = $oServidor->getCgm()->getNome();
                return $oStdMatricula;
            }, $aMatriculas);

            usort($oRetorno->matriculas, function ($oMatricula, $oProximaMatricula) {
                return ($oMatricula->matricula > $oProximaMatricula->matricula);
            });
            break;
        case 'limparRespostas':
            if (empty($oParam->pergunta)) {
                throw new ParameterException("Informe a pergunta para limpar as respostas.");
            }

            db_inicio_transacao();

            $oDaoAvaliacaoResposta = new cl_avaliacaoresposta();
            $rsAvaliacaoResposta = db_query($sqlAvaliacaoResposta = $oDaoAvaliacaoResposta->sql_query(null, ' db106_sequencial AS respostas', null, "db103_sequencial = {$oParam->pergunta}"));

            if (!$rsAvaliacaoResposta) {
                throw new BusinessException("Ocorreu um erro ao excluir as respostas da pergunta.");
            }

            if (pg_num_rows($rsAvaliacaoResposta) == 0) {
                throw new DBException("Não há respostas para esta pergunta.");
                break;
            }

            $respostasApagar = db_utils::makeCollectionFromRecord($rsAvaliacaoResposta, function ($retorno) {
                return $retorno->respostas;
            });

            $oDaoAvaliacaoGrupoPerguntaResposta = new cl_avaliacaogrupoperguntaresposta();

            if ($oDaoAvaliacaoGrupoPerguntaResposta->excluir(null, "db108_avaliacaoresposta in (" . implode(',', $respostasApagar) . ")") == false) {
                throw new DBException($oDaoAvaliacaoGrupoPerguntaResposta->erro_msg);
            }

            if ($oDaoAvaliacaoResposta->excluir(null, "db106_sequencial in (" . implode(',', $respostasApagar) . ")") == false) {
                throw new DBException($oDaoAvaliacaoResposta->erro_msg);
            }

            db_fim_transacao(false);
            $oRetorno->mensagem = 'Respostas excluídas com sucesso.';

            break;

        case 'remover':

            db_inicio_transacao();
            $formulario = \ECidade\Configuracao\Formulario\Repository\Formulario::getById((int)$oParam->formulario);
            if (empty($formulario)) {
                throw new BusinessException("Formulário de código ({$oParam->formulario}) não encontrado no sistema. Verifique.");
            }

            $resposta = Resposta::getBydId($formulario, (int)$oParam->codigo_resposta);
            if (empty($resposta)) {
                throw new BusinessException('Resposta não encontrada no sistema. Verifique.');
            }

            $oDaoAvaliacaoRespostaCGM = new \cl_avaliacaogruporespostacgm();
            $oDaoAvaliacaoRespostaCGM->excluir(null, "eso03_avaliacaogruporesposta={$oParam->codigo_resposta}");
            if ($oDaoAvaliacaoRespostaCGM->erro_status == 0) {
                throw new BusinessException('Não foi possível remover o vínculo com o CGM.');
            }

            $oDaoAvaliacaoRespostaRescisao = new \cl_avaliacaogruporespostatertrabasemvinc();
            $oDaoAvaliacaoRespostaRescisao->excluir(null, "eso24_avaliacaogruporesposta={$oParam->codigo_resposta}");
            if ($oDaoAvaliacaoRespostaRescisao->erro_status == 0) {
                throw new BusinessException('Não foi possível remover desligamento.');
            }
            Resposta::remover($resposta);
            db_fim_transacao(false);
            $oRetorno->mensagem = "Desligamento removido com sucesso.";
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