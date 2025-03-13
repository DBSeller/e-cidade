<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');

$parametros = JSON::requestParameters();

try {
    $retorno = new stdClass();
    $retorno->erro = false;

    switch ($parametros->acao) {
        case 'emitir':
            $sql = "
                SELECT DISTINCT
                  avaliacaogruporespostaconta.c06_sequencial,
                  conplanoorcamento.c60_estrut || ' - ' || conplanoorcamento.c60_descr                                                                       AS conta,
                  lpad(orcunidade.o41_orgao, 2, '0') || lpad(orcunidade.o41_unidade, 2, '0') || ' - ' || orcunidade.o41_descr || ' / ' || orcorgao.o40_descr AS unidade_orcamentaria,
                  avaliacaopergunta.db103_identificadorcampo                                                                                                 AS identificador,
                  avaliacaopergunta.db103_descricao                                                                                                          AS pergunta,
                  CASE WHEN avaliacaoresposta.db106_resposta <> ''
                    THEN avaliacaoresposta.db106_resposta
                  ELSE avaliacaoperguntaopcao.db104_descricao END                                                                                            AS resposta
                FROM avaliacaogruporespostaconta
                  JOIN avaliacaogruporesposta
                    ON avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaconta.c06_avaliacaogruporesposta
                  JOIN avaliacaogrupoperguntaresposta
                    ON avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial
                  JOIN avaliacaoresposta
                    ON avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
                  JOIN avaliacaoperguntaopcao
                    ON avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
                  JOIN avaliacaopergunta ON avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta
                  JOIN conplanoorcamento ON conplanoorcamento.c60_codcon = avaliacaogruporespostaconta.c06_conta AND
                                            conplanoorcamento.c60_anousu = avaliacaogruporespostaconta.c06_ano
                  LEFT JOIN orcunidade ON lpad(orcunidade.o41_orgao, 2, '0') = substr(avaliacaoresposta.db106_resposta, 0, 3) AND
                                          lpad(orcunidade.o41_unidade, 2, '0') = substr(avaliacaoresposta.db106_resposta, 3, 2)
                                          AND orcunidade.o41_anousu = conplanoorcamento.c60_anousu
                  LEFT JOIN orcorgao ON orcorgao.o40_anousu = conplanoorcamento.c60_anousu AND orcorgao.o40_orgao = orcunidade.o41_orgao
                WHERE conplanoorcamento.c60_anousu = 2019
                ORDER BY avaliacaogruporespostaconta.c06_sequencial;
            ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception('Não foi possível buscar os dados do relatório.');
            }

            if (pg_num_rows($rs) === 0) {
                throw new Exception('Não foi encontrado nenhum registro usando o filtro informado.');
            }

            $unidadesOrcamentarias = array();
            $unidadeOrcamentariaAuxiliar = '';

            while ($linha = pg_fetch_array($rs)) {
                if ($linha['unidade_orcamentaria']) {
                    $unidadeOrcamentariaAuxiliar = $linha['unidade_orcamentaria'];
                }

                if ($parametros->unidade && substr($unidadeOrcamentariaAuxiliar, 0, 4) != $parametros->unidade) {
                    continue;
                }

                $unidadesOrcamentarias[$unidadeOrcamentariaAuxiliar][$linha['conta']][$linha['identificador']] = $linha['resposta'];
            }

            unset($unidadeOrcamentariaAuxiliar);

            if (empty($unidadesOrcamentarias)) {
                throw new Exception('Não foi encontrado nenhum registro usando o filtro informado.');
            }

            $linhas = array(
                array(
                    'Unidade Orçamentária',
                    'Natureza de Receita',
                    'Esfera',
                    'Ind. Result. Primário',
                    'IDUSO',
                    'Tipo Detalhamento',
                    'Grupo de Fonte',
                    'Espec. Fonte',
                    'Real 2017',
                    'Provável 2018',
                    'Previsão 2019',
                )
            );

            $caminho = 'tmp/conferencia_previsao_receita_LOA_2019.csv';
            $arquivo = fopen($caminho, 'w');

            foreach ($unidadesOrcamentarias as $unidadeOrcamentaria => $contas) {
                foreach ($contas as $descricao => $preenchimento) {
                    $linhas[] = array(
                        $unidadeOrcamentaria,
                        $descricao,
                        valor($preenchimento, 'esferaOrcamentaria'),
                        valor($preenchimento, 'indicadorResultadoPrimario'),
                        valor($preenchimento, 'id_uso', true),
                        valor($preenchimento, 'previsaoTipoDetalhamento', true),
                        valor($preenchimento, 'grupo_fonte_recurso', true),
                        valor($preenchimento, 'especificacao_fonte', true),
                        array_key_exists('previsaoReal2017', $preenchimento) ? moeda($preenchimento['previsaoReal2017']) : '',
                        array_key_exists('previsaoProvavel2018', $preenchimento) ? moeda($preenchimento['previsaoProvavel2018']) : '',
                        array_key_exists('previsaoPrevisao2019', $preenchimento) ? moeda($preenchimento['previsaoPrevisao2019']) : '',
                    );
                }
            }

            foreach ($linhas as $linha) {
                fputcsv($arquivo, $linha, ';');
            }

            fclose($arquivo);

            $retorno->arquivo = $caminho;
            $retorno->nomeArquivo = 'Conferência Previsão Receita LOA 2019';

            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);

function moeda($numero)
{
    return number_format((float)$numero, 2, ',', '.');
}

function valor(array $lista, $chave, $inteiro = false)
{
    $valor = array_key_exists($chave, $lista) ? $lista[$chave] : '';

    return $inteiro ? (int)$valor : $valor;
}
