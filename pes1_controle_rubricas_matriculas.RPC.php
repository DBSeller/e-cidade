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
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

use ECidade\Core\Helpers\HourHelper;
use ECidade\RecursosHumanos\Pessoal\Builders\ControleRubricasMatriculasBuilder;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasMatriculas;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasMatriculasRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRepository;
use ECidade\RecursosHumanos\Pessoal\Service\ControleRubricasMatriculasService;
use ECidade\RecursosHumanos\Pessoal\Service\ControleRubricasParametrosService;

$parametros = JSON::requestParameters();
$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

try {
    db_inicio_transacao();
    $ano = DBPessoal::getAnoFolha();
    $mes = DBPessoal::getMesFolha();
    $instituicao = InstituicaoRepository::getInstituicaoSessao();
    $dao = new cl_controlehorasextrasmatriculas();
    $repository = new ControleRubricasMatriculasRepository($dao);
    $builder = new ControleRubricasMatriculasBuilder();
    $service = new ControleRubricasMatriculasService($repository, $builder);
    $hourHelper = new HourHelper();
    $repositoryParametros = new ControleRubricasParametrosRepository(new cl_controlehorasextras());
    $serviceParametros = new ControleRubricasParametrosService($repositoryParametros);

    $competencia = new DBCompetencia($ano, $mes);
    $configuracoes = $serviceParametros->buscarPorInstituicaoECompetencia($instituicao, $competencia);

    switch ($parametros->acao) {
        case 'buscaDadosMatricula':
            if (empty($parametros->matricula)) {
                throw new Exception('É necessário informar a matrícula.');
            }

            $retorno->matricula = $service->buscaDadosMatricula($instituicao, $hourHelper, $configuracoes->getSelecao(), $parametros->matricula, $ano, $mes)->toArray();

            break;
        case 'buscaMatriculasConfiguradas':
            $matriculasConfiguradas = $service->buscaMatriculasConfiguradas($instituicao, $ano, $mes);

            $retorno->matriculas = array_map(function (ControleRubricasMatriculas $controleHorasExtrasMatriculas) {
                return $controleHorasExtrasMatriculas->toArray();
            }, $matriculasConfiguradas);

            break;
        case 'salvarMatriculas':
            if (empty($parametros->matricula)) {
                throw new Exception('É necessário informar a matricula.');
            }
            if (empty($parametros->ano)) {
                throw new Exception('É necessário informar o ano.');
            }
            if (empty($parametros->mes)) {
                throw new Exception('É necessário informar o mes.');
            }
            if (empty($parametros->horasLiberadas)) {
                throw new Exception('É necessário informar a quantidade de horas liberadas.');
            }

            $retorno->matricula = $service->salvaPropagaCompetencia(
                $instituicao,
                $hourHelper,
                $configuracoes->getSelecao(),
                $parametros->matricula,
                $parametros->horasLiberadas,
                $ano,
                $mes,
                $parametros->ano,
                $parametros->mes
            )->toArray();
            break;
        case 'removerControleHorasExtrasMatricula':
            if (empty($parametros->matricula)) {
                throw new Exception('É necessário informar a matrícula para exclusão.');
            }

            $retorno->itensExcluidos = $service->removerControleHorasExtrasMatricula(
                $instituicao,
                $parametros->matricula,
                $ano,
                $mes
            );
            break;
    }

} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
