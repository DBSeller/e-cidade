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

use ECidade\RecursosHumanos\ESocial\Factory\SugestaoPreenchimento;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\V3\Extension\Registry;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;

$json = JSON::create();
$parametros = $json->parse(str_replace("\\", "", $_POST["json"]));

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

$instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();
db_inicio_transacao();

try {
    switch ($parametros->exec) {
        case 'buscarAvaliacao':
            if (empty($parametros->matricula)) {
                throw new BusinessException("Matrícula não informada.");
            }

            if (empty($parametros->formularioTipo)) {
                throw new BusinessException("É necessário informar um tipo de formulário para preenchimento.\nContate o suporte.");
            }

            if (empty($parametros->cgmEmpregador)) {
                throw new BusinessException("É necessário selecionar um empregador.");
            }

            $configuracao = new Configuracao();
            $codigoFormulario = $configuracao->getFormulario($parametros->formularioTipo);

            $trazerSugestoes = false;
            if (!empty($parametros->trazerSugestoes)) {
                $trazerSugestoes = $parametros->trazerSugestoes;
            }

            $cgmEmpregador = CgmFactory::getInstanceByCgm($parametros->cgmEmpregador);

            $servidor = ServidorRepository::getInstanciaByCodigo($parametros->matricula);
            $avaliacao = new AvaliacaoEsocialAdapter(AvaliacaoRepository::getAvaliacaoByCodigo($codigoFormulario));
            $avaliacao->setServidor($servidor);
            $avaliacao->setCgm($cgmEmpregador);
            $avaliacao->trazerSugestoes($trazerSugestoes);

            $codigoGrupoResposta = null;

            switch ($parametros->formularioTipo) {
                case Tipo::ALTERACAO_CONTRATUAL:

                    $avaliacao->setAlteracaoContratual(true);
                    $daoAlteracaoContratual = new cl_avaliacaogruporespostaaltercontratual();
                    $where = " eso20_cgm = {$cgmEmpregador->getCodigo()} AND eso20_rhpessoal = {$servidor->getMatricula()} ";
                    $sqlAlteracaoContratual = $daoAlteracaoContratual->sql_query_file(null, "*", null, $where);
                    $rsAlteracaoContratual = db_query($sqlAlteracaoContratual);

                    if (!$rsAlteracaoContratual) {
                        throw new Exception("Não foi possível buscar os preenchimentos anteriores da matrícula {$parametros->matricula}.\nContate o suporte.");
                    }

                    $codigoGrupoResposta = db_utils::fieldsMemory($rsAlteracaoContratual,
                        0)->eso20_avaliacaogruporesposta;
                    break;

                case Tipo::ALTERACAO_TRABALHADOR_SEM_VINCULO:

                    $body = new stdClass();
                    $body->idReferencia = $parametros->matricula;
                    $body->idEvento = 'S-2300';
                    $body->inscricaoEmpregador = $instituicaoSessao->getCNPJ();

                    $eSocial = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
                    $eSocial->setDados($body);
                    $response = $eSocial->request('GET');
                    if (!$response) {

                        $mensagem = "Deve ser enviado o evento inicial do trabalhador sem vínculo ao eSocial antes de enviar o formulário de alteração, e possuir o recibo de confirmação do eSocial.";
                        $mensagem .= "\n\nAcesse:";
                        $mensagem .= "\neSocial > Procedimentos > Preenchimento > Trabalhador sem Vínculo > Inicial";
                        $mensagem .= "\neSocial > Procedimentos > Envio de eventos para o eSocial";
                        $mensagem .= "\neSocial > Consulta > Situação de Eventos";
                        throw new Exception($mensagem);
                    }

                    $avaliacao->setAlteracaoTSVE(true);

                    $daoAlteracaoTSVE = new cl_avaliacaogruporespostatsvealteracao();
                    $where = " eso23_rhpessoal = {$servidor->getMatricula()} ";
                    $sqlAlteracaoTSVE = $daoAlteracaoTSVE->sql_query_file(null, "*",
                        "eso23_avaliacaogruporesposta desc", $where);
                    $rsAlteracaoTSVE = db_query($sqlAlteracaoTSVE);

                    if (!$rsAlteracaoTSVE) {
                        throw new Exception("Não foi possível buscar os preenchimentos anteriores da matrícula {$parametros->matricula}.\nContate o suporte.");
                    }

                    $codigoGrupoResposta = db_utils::fieldsMemory($rsAlteracaoTSVE, 0)->eso23_avaliacaogruporesposta;

                    break;
            }

            $avaliacao->setCodigoGrupoResposta($codigoGrupoResposta);

            $retorno->formulario = $avaliacao->getObject();

            $factory = new SugestaoPreenchimento();
            $factory->setMatricula($parametros->matricula);
            $factory->setCgmResponsavel($cgmEmpregador);
            $sugestao = $factory->porTipo($parametros->formularioTipo);
            if (!empty($sugestao)) {
                $retorno->sugestao = $sugestao->parse();
            }

            break;

        case 'salvarAvaliacao':
            if (empty($parametros->matricula)) {
                throw new BusinessException("Matrícula não informada.");
            }

            if (empty($parametros->formularioTipo)) {
                throw new BusinessException("É necessário informar um tipo de formulário para preenchimento.\nContate o suporte.");
            }

            if (empty($parametros->cgmEmpregador)) {
                throw new BusinessException("É necessário selecionar um empregador.");
            }

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($parametros->codigoAvaliacao);
            $avaliacao->setAvaliacaoGrupo();

            $codigoGrupoPerguntas = null;

            if (!empty($parametros->codigoGrupoPerguntas)) {
                $codigoGrupoPerguntas = $parametros->codigoGrupoPerguntas;
            }

            $cgmEmpregador = CgmFactory::getInstanceByCgm($parametros->cgmEmpregador);

            $servidor = ServidorRepository::getInstanciaByCodigo($parametros->matricula);
            $avaliacaoESocial = new AvaliacaoESocial();
            $avaliacaoESocial->setAvaliacao($avaliacao);
            $avaliacaoESocial->setServidor($servidor);
            $avaliacaoESocial->setCgm($cgmEmpregador);
            $avaliacaoESocial->setPerguntasRespostas($parametros->perguntasRespostas);
            $avaliacaoESocial->salvar($codigoGrupoPerguntas, $parametros->formularioTipo, (array)$parametros);

            $retorno->mensagem = "Avaliação salva com sucesso.";
            break;
    }
} catch (Exception $e) {
    db_fim_transacao(true);
    $retorno->erro = true;
    $retorno->mensagem = $e->getMessage();
}

db_fim_transacao(false);
echo $json->stringify($retorno);
