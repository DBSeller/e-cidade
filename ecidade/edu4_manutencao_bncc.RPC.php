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

use ECidade\Educacao\Secretaria\BNCC\Registry\HabilidadeReferencialCurricularEstadualRegistry;
use ECidade\Educacao\Secretaria\BNCC\Resource\BnccOriginalEducacaoInfantilResource;
use ECidade\Educacao\Secretaria\BNCC\Resource\BnccOriginalEnsinoFundamentalResource;
use ECidade\Educacao\Secretaria\BNCC\Repository\HabilidadeEnsinoFundamentalRepository;
use ECidade\Educacao\Secretaria\BNCC\Resource\HabilidadeEnsinoFundamentalResource;
use ECidade\Educacao\Secretaria\BNCC\Resource\HabilidadeReferencialCurricularResource;
use ECidade\Educacao\Secretaria\BNCC\Service\BnccService;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta" . ".php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));


/**
 * @todo Manter a ideia de buscar sempre os filtros da BNCC original
 * @todo Quando for buscar as habilidades, começar a buscar da BNCC Original e depois buscar das tabelas
 *       bncceducacaoinfantil e bnccensinofundamental verificando o que tem ou não para marcar na grade.
 * @todo
 */
$parametros = JSON::requestParameters();
$retorno = (object)array('erro' => false, 'mensagem' => '');

$codigoEscola = db_getsession('DB_coddepto');
$service = new BnccService();
try {
    db_inicio_transacao();
    $configuracao = ParametrosGlobaisService::get();
    switch ($parametros->acao) {
        case 'buscarConfiguracao':
            $retorno->configuracao = $configuracao->getTipoBaseCurricular()->toJson();
            for ($i = 2020; $i <= date('Y'); $i++) {
                $retorno->anos[] = $i;
            }
            break;
        case 'buscarFiltrosEnsinoInfantil':
            $retorno->filtros = BnccOriginalEducacaoInfantilResource::toArrayFiltros(
                $service->getFiltrosEducacaoInfantil()
            );
            break;
        case 'buscarFiltrosEnsinoFundamental':
            $parametros->opcao = $configuracao->getTipoBaseCurricular()->toJson()['value'];
            $retorno->filtros = BnccOriginalEnsinoFundamentalResource::toArrayFiltros(
                $service->getFiltrosEnsinoFundamental($parametros->opcao, $parametros->ano)
            );
            break;
        case 'buscarHabilidadesEI':
            $habilidades = $service->buscaHabilidadesInfatilManutencao($parametros);
            $retorno->habilidades = BnccOriginalEducacaoInfantilResource::toArray($habilidades);
            break;
        case 'buscarHabilidadesEF':
            $parametros->opcao = $configuracao->getTipoBaseCurricular()->toJson()['value'];
            $habilidades = $service->buscaHabilidadesFundamentalManutencao($parametros);
            if ($parametros->opcao == 3) {
                $retorno->habilidades = HabilidadeEnsinoFundamentalResource::toArray($habilidades);
                break;
            }
            $retorno->habilidades = BnccOriginalEnsinoFundamentalResource::toArray($habilidades);
            break;
        case 'salvarHabilidadesEI':
            $habilidades = [];
            foreach ($parametros->habilidades as $habilidade) {
                $habilidades[] = JSON::create()->parse($habilidade);
            }
            $service->salvarHabilidadesEducacaoInfantil($parametros->ano, $habilidades);
            $retorno->mensagem = "As Habilidades foram salvas com sucesso!";
            break;
        case 'salvarHabilidadesEF':
            $habilidades = [];
            foreach ($parametros->habilidades as $habilidade) {
                $habilidades[] = JSON::create()->parse($habilidade);
            }
            $service->salvarHabilidadesEnsinoFundamental($parametros->ano, $habilidades);

            $retorno->mensagem = "As Habilidades foram salvas com sucesso!";
            break;
        case 'salvarHabilidadeEF':

            $novaHabilidade = isset($parametros->novaHabilidade) ? $parametros->novaHabilidade : false;
            $habilidades = [];
            foreach ($parametros->habilidades as $habilidade) {
                $habilidades[] = JSON::create()->parse($habilidade);
            }
            $service->salvarHabilidadeEnsinoFundamental($parametros->ano, $habilidades, $novaHabilidade);

            $retorno->mensagem = "As Habilidades foram salvas com sucesso!";
            break;
        case 'removerReferencial':
            if (empty($parametros->codigo)) {
                throw new Exception("Informe o Código sequencial da Habilidade");
            }

            $habilidadeReferencial = HabilidadeReferencialCurricularEstadualRegistry::get($parametros->codigo);
            if (is_null($habilidadeReferencial)) {
                throw new Exception("Não foi possível buscar Habilidade do Referencial com esse Código");
            }

            $service->excluirHabilidadeReferencial($habilidadeReferencial);
            $retorno->mensagem = "Habilidade removida com sucesso!";
            break;
        case 'adicionarReferencial':
            if (empty($parametros->novaHabilidade)) {
                throw new Exception("Nova Habilidade do Referencial não informada.");
            }
            $novaHabilidade = JSON::create()->parse($parametros->novaHabilidade);
            if (empty($novaHabilidade->etapa) && $novaHabilidade->ensino != "EI") {
                throw new Exception("Deve ser informado no mínimo uma Etapa.");
            }
            $habilidadeReferencial = $service->adicionarReferencial($novaHabilidade);
            $retorno->habilidadeReferencial = HabilidadeReferencialCurricularResource::toArray(
                [$habilidadeReferencial]
            );
            $retorno->mensagem = "Habilidade Adicionada com Sucesso!";
            break;
        case 'editarObjetoConhecimento':
            if (empty($parametros->ed148_disciplina)) {
                throw new Exception("Disciplina não informada.");
            }
            if (empty($parametros->ed148_unidade_tematica)) {
                throw new Exception("Unidade Temática não informada.");
            }
            if (empty($parametros->ed148_objeto_conhecimento)) {
                throw new Exception("Objeto de Conhecimento não informado.");
            }
            $service->editarObjetoConhecimento($parametros->novoNome, $parametros);
            $retorno->mensagem = "Objeto de Conhecimento Alterado com Sucesso!";
            break;
        case 'excluirObjetoConhecimento':
            if (empty($parametros->objeto)) {
                throw new Exception("Objeto não informado.");
            }
            $service->excluirObjetoConhecimento($parametros->objeto);
            $retorno->mensagem = "Objeto de Conhecimento Excluído com Sucesso!";
            break;
        case 'excluirHabilidadeEF':
            if (empty($parametros->codigo)) {
                throw new Exception("Codigo não informado.");
            }

            $service->excluirHabilidadeEF($parametros->codigo, $parametros->objetoConhecimento, $ano);
            $retorno->mensagem = "Habilidade Excluída com Sucesso!";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
