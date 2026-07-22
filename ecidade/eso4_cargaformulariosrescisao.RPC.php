<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use ECidade\Configuracao\Formulario\Processamento\CargaRescisao;
use ECidade\Configuracao\Formulario\Repository\Formulario;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\V3\Extension\Registry;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$parametro = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->mensagem = '';
$retorno->erro = false;

$instituicao = InstituicaoRepository::getInstituicaoSessao();
$competencia = DBPessoal::getCompetenciaFolha();
try {

    db_inicio_transacao();

    switch ($parametro->exec) {

        case 'getRescisoes':
            $daoRescisao = new cl_rhpesrescisao();
            $dataInicial = new \DateTime($parametro->dataInicio);
            $dataFinal = new \DateTime($parametro->dataFim);

            $sqlDadosRescisao = $daoRescisao->sql_query_esocial_desligamento($dataInicial->format('Y-m-d'),
                $dataFinal->format('Y-m-d'),
                $instituicao->getCodigo(),
                $competencia
            );

            $rsRescisoes = db_query($sqlDadosRescisao);
            $retorno->rescisoes = db_utils::makeCollectionFromRecord($rsRescisoes, function ($rescisao) {
                return $rescisao;
            });
            break;

        case 'processar':
            $codigoFormularioEsocial = Configuracao::getFormularioDoTipoNaVersaoAtual(14);
            if (empty($codigoFormularioEsocial)) {
              throw new BusinessException('Formulário do desligamento não configurado.');
            }
            $formulario    = Formulario::getById($codigoFormularioEsocial->formulario);

            $carga = new CargaRescisao($formulario);
            $carga->setVinculoEmprego('true');
            $carga->setInstituicao($instituicao);
            $carga->setCompetencia($competencia);
            $carga->setRescisoes($parametro->rescisoes);
            $carga->setCallbackSaveForm(function ($codigoResposta, $oDadosConsulta, $codigoFormulario) {
                $oDaopreenchimentoRescisao = new \cl_avaliacaogruporespostarhpesrescisao();
                $oDaopreenchimentoRescisao->eso15_avaliacaogruporesposta = $codigoResposta;
                $oDaopreenchimentoRescisao->eso15_codigorescisao = $oDadosConsulta->desligamento_codigo_rescisao;
                $oDaopreenchimentoRescisao->eso15_cgmempregador = $oDadosConsulta->cgmempregador;
                $oDaopreenchimentoRescisao->eso15_regist = $oDadosConsulta->matricula;
                $oDaopreenchimentoRescisao->eso15_avaliacao = $codigoFormulario;

                $oDaopreenchimentoRescisao->incluir(null);

                if ($oDaopreenchimentoRescisao->erro_status === '0') {
                    throw new Exception('Não foi possível salvar o vínculo do preenchimento do desligamento do servidor.');
                }
            });
            $carga->executar();

            $retorno->mensagem = 'Matrículas selecionadas foram processadas com sucesso.';

            break;

        case 'validarEnvioServidor':
            if (empty($parametro->matriculas)) {
                throw new Exception('Nenhuma matrícula informada.');
            }

            $retorno->matriculas = array();
            foreach ($parametro->matriculas as $matricula) {
                $body = new stdClass();
                $body->idReferencia = $matricula;
                $body->idEvento = 'S-2200';
                $body->inscricaoEmpregador = $instituicao->getCNPJ();

                $service = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
                $service->setDados($body);

                $response = $service->request('GET');

                if (!$response) {
                    $retorno->matriculas[] = $matricula;
                }
            }

            break;
    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);

    $retorno->mensagem = $e->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);
