<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');

$parametros = JSON::requestParameters();
$ano = isset($parametros->ano) ? $parametros->ano : db_getsession('DB_anousu');
const AVALIACAO = 3000024;

try {
    $retorno = new stdClass();
    $retorno->erro = false;

    switch ($parametros->acao) {
        case 'buscarAvaliacao':
            if (empty($parametros->codigo_conta)) {
                throw new BusinessException('É necessário informar uma conta.');
            }

            $dao = new cl_avaliacaogruporespostaconta();
            $sql = $dao->sql_query_file(null, '*', null,
                "c06_conta = {$parametros->codigo_conta} AND c06_ano = {$ano}");

            $resultado = db_query($sql);

            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(AVALIACAO);

            $avaliacaoAdapter = new AvaliacaoEsocialAdapter($avaliacao);
            $avaliacaoAdapter->setPrevisaoReceita(true);

            if (pg_num_rows($resultado) > 0) {
                $retorno->preenchimento = pg_fetch_object($resultado, 0)->c06_avaliacaogruporesposta;
                $avaliacao->setAvaliacaoGrupo($retorno->preenchimento);
                $avaliacaoAdapter->setCodigoGrupoResposta($retorno->preenchimento);
            }

            $retorno->formulario = $avaliacaoAdapter->getObject();

            break;
        case 'salvar':
            $perguntasRespostas = JSON::create()->parse($parametros->perguntasRespostas);
            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(AVALIACAO);
            $preenchimento = null;

            if (isset($parametros->preenchimento)) {
                $preenchimento = $parametros->preenchimento;
            }

            $avaliacao->setAvaliacaoGrupo($preenchimento);

            $avaliacaoESocial = new AvaliacaoESocial();
            $avaliacaoESocial->setAno($ano);
            $avaliacaoESocial->setAvaliacao($avaliacao);
            $avaliacaoESocial->setPerguntasRespostas($perguntasRespostas);
            $avaliacaoESocial->salvar(null, 'previsao_receita', array(
                'iCodigoPreenchimento' => $avaliacao->getAvaliacaoGrupo(),
                'conta' => $parametros->conta
            ));

            $retorno->mensagem = 'Formulário salvo com sucesso!';
            break;
        case 'buscarUnidadeOrcamentaria':
            $daoOrcUnidade = new cl_orcunidade();
            $sqlUnidades = $daoOrcUnidade->sql_query($ano);
            $unidades = array();
            $resultado = db_query($sqlUnidades);

            if (pg_num_rows($resultado) > 0) {
                $unidades = db_utils::getCollectionByRecord($resultado);
                $unidadesFormatadas = array();

                foreach ($unidades as $unidade) {
                    $value = str_pad($unidade->o41_orgao, 2, 0, STR_PAD_LEFT) . str_pad($unidade->o41_unidade, 2, 0, STR_PAD_LEFT);
                    $label = "{$unidade->o41_descr} / {$unidade->o40_descr}";
                    $stdClass = new stdClass();
                    $stdClass->value = $value;
                    $stdClass->label = $label;
                    $unidadesFormatadas[] = $stdClass;
                }

                $unidades = $unidadesFormatadas;
            }

            $retorno = $unidades;
            break;
        case 'buscarEspecificacao':
            $recursos = array();
            $daoOrcTipoRec = new cl_orctiporec();
            $sqlRecursos = $daoOrcTipoRec->sql_query();
            $resultado = db_query($sqlRecursos);

            if (pg_num_rows($resultado) > 0) {
                $recursosFormatadas = array();

                while ($recurso = pg_fetch_object($resultado)) {
                    $stdClass = new stdClass();
                    $stdClass->value = $recurso->o15_codigo;
                    $stdClass->label = $recurso->o15_descr;
                    $recursos[] = $stdClass;
                }
            }

            $retorno = $recursos;
            break;

    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);
