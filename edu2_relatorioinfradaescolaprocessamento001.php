<?php

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_' . 'conecta.php'));

$perguntas = getPerguntas();

$cabecalho = [
    'CODIGO ESCOLA',
    'COD REF',    
    'UNIDADE ESCOLAR'
];

foreach ($perguntas as $grupo) {
    $cabecalho[] = '(' . $grupo['codigo_pergunta'] . ') ' . $grupo['pergunta'];

    foreach ($grupo['opcao_pergunta'] as $opcao) {
        if (!empty($opcao['descricao'])) {
            $cabecalho[] = $opcao['descricao'];
        }
    }
}

$escolas = getEscolas();

$arquivo = 'tmp/arquivo_infra_escola_'.time().'.csv';
$file = fopen($arquivo, 'w');

fputcsv($file, $cabecalho);

foreach ($escolas as $escola) {

    $linha = [
        $escola['codigo'],
        $escola['codigoref'],
        $escola['nome']
    ];

    foreach ($perguntas as $grupo => $opcoes) {
        
        if (count($opcoes['opcao_pergunta']) > 1 && !empty($opcoes['opcao_pergunta'][0]['descricao'])) {
            $linha[] = '';
        }
        
        foreach ($opcoes['opcao_pergunta'] as $opcao) {
            $resposta = resultadoPergunta($escola['codigo'], $opcao['codigo']);
            $linha[] = $resposta;
        }
    }

    fputcsv($file, $linha);
}

fclose($file);

$retorno = new stdClass();
$retorno->erro = false;
$retorno->arquivo = $arquivo;
$retorno->nomeArquivo = 'Relatório de Infraestrutura Escolar';

echo JSON::create()->stringify($retorno);


function getPerguntas()
{
    $sql = <<<SQL
    select
        db103_avaliacaogrupopergunta as grupo,
        db103_sequencial as codigo_pergunta,
        db103_descricao as pergunta,
        db104_descricao as opcao_pergunta,
        db104_sequencial as codigo_opcao_pergunta
    from
        avaliacaogrupopergunta
    join avaliacaopergunta on
        db102_sequencial = db103_avaliacaogrupopergunta
    join avaliacaoperguntaopcao on
        db104_avaliacaopergunta = db103_sequencial
    where
        db102_avaliacao = 3000000
SQL;
    $result = db_query($sql);

    $retorno = [];

    while ($pergunta = pg_fetch_object($result)) {

        if (array_key_exists($pergunta->codigo_pergunta, $retorno)) {
            array_push(
                $retorno[$pergunta->codigo_pergunta]['opcao_pergunta'],
                [
                    'descricao' => $pergunta->opcao_pergunta,
                    'codigo' => $pergunta->codigo_opcao_pergunta
                ]
            );
        } else {

            $retorno[$pergunta->codigo_pergunta] = [
                'codigo_pergunta' => $pergunta->codigo_pergunta,
                'pergunta' => $pergunta->pergunta,
                'opcao_pergunta' => []
            ];

            array_push(
                $retorno[$pergunta->codigo_pergunta]['opcao_pergunta'],
                [
                    'descricao' => $pergunta->opcao_pergunta,
                    'codigo' => $pergunta->codigo_opcao_pergunta

                ]
            );
        }
    }

    return $retorno;
}

function getEscolas()
{
    $sqlEscola = <<<SQL
    select ed18_i_codigo as codigo,
           ed18_codigoreferencia as codigoref,
           ed18_c_nome as nome
    from escoladadoscenso,
                escola
    where escola.ed18_i_codigo = escoladadoscenso.ed308_escola
SQL;

    $escolas = db_query($sqlEscola);

    return pg_fetch_all($escolas);
}

function resultadoPergunta($escola, $opcaoPergunta)
{
    $sqlRespostas = <<<SQL
    select
        case
            when avaliacaoperguntaopcao.db104_aceitatexto = true
                            then avaliacaoresposta.db106_resposta
            else 'X'
        end as resposta
    from
        avaliacaogrupoperguntaresposta
    join avaliacaoresposta on
        avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
    join avaliacaoperguntaopcao on
        avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
    join avaliacaopergunta on
        avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta
    join avaliacaogrupopergunta on
        avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta
    join avaliacaogruporesposta on
        avaliacaogruporesposta.db107_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta
    join escoladadoscenso on
        escoladadoscenso.ed308_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial
    join escola on
        escola.ed18_i_codigo = escoladadoscenso.ed308_escola
    where
        db102_avaliacao = 3000000
        and ed18_i_codigo = {$escola}
        and db106_avaliacaoperguntaopcao = {$opcaoPergunta}
SQL;

    $opRespostas = db_query($sqlRespostas);

    return pg_fetch_object($opRespostas, 0)->resposta;
}
