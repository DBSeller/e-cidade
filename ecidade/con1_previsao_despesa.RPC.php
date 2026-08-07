<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once(modification("libs/JSON.php"));

use ECidade\File\Csv\Dumper\Dumper;

$parametros = JSON::requestParameters();
$oJson   = new Services_JSON();


try {
    db_inicio_transacao();
    $retorno = new stdClass();
    $retorno->erro = false;


    switch ($parametros->exec) {
        case 'salvar':
            if ($parametros->esferaOrcamentaria === '') {
                throw new ParameterException('É necessário preencher o campo "Esfera Orçamentária".');
            }
            if ($parametros->unidadeOrcamentaria === '') {
                throw new ParameterException('É necessário preencher o campo "Unidade Orçamentária".');
            }
            if ($parametros->funcao === '') {
                throw new ParameterException('É necessário preencher o campo "Função".');
            }
            if ($parametros->subfuncao === '') {
                throw new ParameterException('É necessário preencher o campo "Subfunção".');
            }
            if ($parametros->programa === '') {
                throw new ParameterException('É necessário preencher o campo "Programa".');
            }
            if ($parametros->acao === '') {
                throw new ParameterException('É necessário preencher o campo "Ação".');
            }
            if ($parametros->subtitulo === '') {
                throw new ParameterException('É necessário preencher o campo "Subtítulo".');
            }
            if ($parametros->naturezaDespesa === '') {
                throw new ParameterException('É necessário preencher o campo "Natureza de Despesa".');
            }
            if ($parametros->identificadorUso === '') {
                throw new ParameterException('É necessário preencher o campo "Identificador de Uso".');
            }
            if ($parametros->tipoDetalhamento === '') {
                throw new ParameterException('É necessário preencher o campo "Tipo de Detalhamento".');
            }
            if ($parametros->grupoFonteRecurso === '') {
                throw new ParameterException('É necessário preencher o campo "Grupo de Fonte de Recurso".');
            }
            if ($parametros->especificacaoFonte === '') {
                throw new ParameterException('É necessário preencher o campo "Especificação da Fonte de Recurso".');
            }
            if ($parametros->identificadorResultadoPrimario === '') {
                throw new ParameterException('É necessário preencher o campo "Identificador de Resultado Primário".');
            }
            if ($parametros->previsao2019 === '') {
                throw new ParameterException('É necessário preencher o campo "Previsão 2019"');
            }

            $dao = new cl_previsaodespesa();

            $where = " c333_ano = {$parametros->ano}";
            $where .= " and c333_esferaorcamentaria = {$parametros->esferaOrcamentaria}::integer";
            $where .= " and c333_orcorgao = substr({$parametros->unidadeOrcamentaria}, 0, 2)::integer";
            $where .= " and c333_orcunidade = substr({$parametros->unidadeOrcamentaria}, 2, 2)::integer";
            $where .= " and c333_orcfuncao = {$parametros->funcao}";
            $where .= " and c333_orcsubfuncao = {$parametros->subfuncao}";
            $where .= " and c333_orcprograma = {$parametros->programa}";
            $where .= " and c333_orcprojativ = {$parametros->acao}";
            $where .= " and c333_ppasubtitulolocalizadorgasto = {$parametros->subtitulo}";
            $where .= " and c333_conplanoorcamento = {$parametros->naturezaDespesa}";
            $where .= " and c333_identificadoruso = {$parametros->identificadorUso}";
            $where .= " and c333_tipodetalhamento = '{$parametros->tipoDetalhamento}'";
            $where .= " and c333_grupofonterecursos = '{$parametros->grupoFonteRecurso}'";
            $where .= " and c333_especificacaofonte = '{$parametros->especificacaoFonte}'";
            $where .= " and c333_identificadorresultadoprimario = '{$parametros->identificadorResultadoPrimario}'";

            $sql = $dao->sql_query_file(null, "c333_sequencial", null, $where);
            $rsPrevisao = db_query($sql);

            if (empty($rsPrevisao)) {
                throw new DBException("Erro ao consultar previsões de despesas cadastradas.");
            }

            if (pg_num_rows($rsPrevisao) > 0) {
                $previsao = db_utils::fieldsMemory($rsPrevisao, 0);

                if (!isset($parametros->codigo) || $parametros->codigo != $previsao->c333_sequencial) {
                    $erro = "Já há uma previsão de despesa com estes dados. ";
                    $erro .= "Altere-a em 'Previsão de Despesa > Alteração' utilizando o código {$previsao->c333_sequencial}.";
                    throw new BusinessException($erro);
                }
            }

            $where = array(
                "c40_ano = {$parametros->ano}",
                'c40_orgao = ' . substr($parametros->unidadeOrcamentaria, 0, 2),
                'c40_unidade = ' . substr($parametros->unidadeOrcamentaria, 2, 2),
                "c40_identificador_uso = {$parametros->identificadorUso}",
                "c40_tipo_detalhamento = '{$parametros->tipoDetalhamento}'",
                "c40_grupo_fonte_recursos = '{$parametros->grupoFonteRecurso}'",
                "c40_especificacao_fonte = '{$parametros->especificacaoFonte}'",
                'c40_grupo_natureza_despesa = ' . substr($parametros->estrutural, 2, 1)
            );

            $daoTetoOrcamentario = new cl_teto_orcamentario();
            $sql = $daoTetoOrcamentario->sql_query_file(null, '*', null, implode(' AND ', $where));

            $resultado = \db_query($sql);

            if (!$resultado) {
                throw new Exception('Não foi possível consultar o valor disponível do teto.');
            }

            if (pg_num_rows($resultado) > 0) {
                $teto = db_utils::fieldsMemory($resultado, 0);

                $valorAlterarTeto = $parametros->previsao2019;

                if (!empty($parametros->codigo)) {
                    $sqlAnterior = $dao->sql_query_file($parametros->codigo);
                    $rsAnterior = db_query($sqlAnterior);

                    if (empty($rsAnterior)) {
                        throw new DBException("Erro ao buscar dados anteriores da despesa.");
                    }

                    $depesaAnterior = db_utils::fieldsMemory($rsAnterior, 0);

                    $valorAlterarTeto = $parametros->previsao2019 - $depesaAnterior->c333_previsao;
                }

                if ($valorAlterarTeto > $teto->c40_valor_disponivel) {
                    throw new Exception(
                        "O valor da previsão da despesa não pode exceder o valor do teto.\nValor do teto: R$ " .
                        number_format($teto->c40_valor_teto, 2, ',', '.') . "\nValor disponível: R$ " .
                        number_format($teto->c40_valor_disponivel, 2, ',', '.')
                    );
                }

                $sqlDisponivel = $daoTetoOrcamentario->update_valor_disponivel($teto->c40_sequencial, $teto->c40_valor_disponivel - $valorAlterarTeto);
                $rsDisponivel = db_query($sqlDisponivel);

                if (empty($rsDisponivel)) {
                    throw new DBException("Erro ao dimunuir o valor disponível do teto.");
                }
            } else {
                throw new DBException("Não existe um Teto Orçamentário cadastrado para a despesa informada.\n\nCadastre no menu Contabilidade > Cadastros > Teto Orçamentário > Inclusão");
            }

            $dao->c333_ano = $parametros->ano;
            $dao->c333_esferaorcamentaria = $parametros->esferaOrcamentaria;
            $dao->c333_orcorgao = substr($parametros->unidadeOrcamentaria, 0, 2);
            $dao->c333_orcunidade = substr($parametros->unidadeOrcamentaria, 2, 2);
            $dao->c333_orcfuncao = $parametros->funcao;
            $dao->c333_orcsubfuncao = $parametros->subfuncao;
            $dao->c333_orcprograma = $parametros->programa;
            $dao->c333_orcprojativ = $parametros->acao;
            $dao->c333_ppasubtitulolocalizadorgasto = $parametros->subtitulo;
            $dao->c333_conplanoorcamento = $parametros->naturezaDespesa;
            $dao->c333_identificadoruso = $parametros->identificadorUso;
            $dao->c333_tipodetalhamento = $parametros->tipoDetalhamento;
            $dao->c333_grupofonterecursos = $parametros->grupoFonteRecurso;
            $dao->c333_especificacaofonte = $parametros->especificacaoFonte;
            $dao->c333_identificadorresultadoprimario = $parametros->identificadorResultadoPrimario;
            $dao->c333_previsao = $parametros->previsao2019;
            $dao->c333_planoorcamentario = JSON::create()->stringify(array('campo' => 'desativado'));

            if (isset($parametros->codigo)) {

                if ($parametros->previsao2019alterada != $parametros->previsao2019) {

                    $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();

                    $whereExclusaoVinculo = " c41_previsaodespesa = {$parametros->codigo} ";
                    $daoLinhasPactoVinculo->excluir(null, $whereExclusaoVinculo);

                    if ($daoLinhasPactoVinculo->erro_status == "0") {
                        throw new Exception("Não foi possível descinvular as linhas de pacto do plano orçamentário informado.\n\nContate o suporte.");
                    }

                    $daoPrevisaoPlanos = new cl_previsaodespesaplano();
                    $wherePlanos = " c55_previsaodespesa = {$parametros->codigo} ";
                    $daoPrevisaoPlanos->c55_previsaodespesa = $parametros->codigo;
                    $daoPrevisaoPlanos->excluir(null, $wherePlanos);

                    if ($daoPrevisaoPlanos->erro_status == "0") {
                        $msgErro = "Não foi possível incluir o plano orçamentário.\n";
                        $msgErro .= "Contate o suporte.";
                        throw new Exception($msgErro);
                    }

                    $retorno->planoPadrao =  cadastraPlanoPadrao($parametros, $parametros->codigo);
                }

                $dao->c333_sequencial = $parametros->codigo;
                $dao->alterar($parametros->codigo);
            } else {

                $dao->incluir(null);

                if ($dao->erro_status != 0) {
                    $retorno->planoPadrao =  cadastraPlanoPadrao($parametros, $dao->c333_sequencial);
                }
            }

            if ($dao->erro_status == 0) {
                throw new Exception("Não foi possível salvar a previsão de despesa. \n{$dao->erro_msg}");
            }

            $retorno->mensagem = 'Previsão de despesa salva com sucesso!';
            $retorno->codigo = $dao->c333_sequencial;
            $retorno->previsao2019 = $parametros->previsao2019;

            break;
        case 'pesquisar':

            if (empty($parametros->sequencial)) {
                throw new ParameterException("Informe o código da previsão de despesa.");
            }

            $campos = "c333_sequencial,
                        c333_ano, 
                        c333_esferaorcamentaria,
                        c333_orcorgao,
                        c333_orcunidade,
                        o40_descr || ' / ' || o41_descr as unidade_orcamentaria,
                        c333_orcfuncao, o52_descr,
                        c333_orcsubfuncao,
                        o53_descr,
                        c333_orcprograma,
                        o54_descr,
                        c333_orcprojativ,
                        o55_descr,
                        c333_ppasubtitulolocalizadorgasto,
                        o11_descricao,
                        c333_conplanoorcamento,
                        c60_descr,
                        c333_identificadoruso,
                        c333_tipodetalhamento,
                        c333_grupofonterecursos,
                        c333_especificacaofonte,
                        c333_identificadorresultadoprimario,
                        c333_previsao,
                        c333_planoorcamentario,
                        c60_estrut AS estrutural";

            $dao = new cl_previsaodespesa();
            $rs = db_query($dao->sql_previsao_despesa($parametros->sequencial, $campos));

            if (!$rs) {
                throw new DBException("Erro ao pesquisar previsão de despesa.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new DBException("Sequencial informado não é de uma previsão de despesa.");
            }

            $retorno->previsao = db_utils::fieldsMemory($rs, 0);
            $retorno->previsao->c333_planoorcamentario = JSON::create()->stringify(array('campo' => 'desativado'));
            $retorno->previsao->planos = array();

            $daoPrevisaoPlanos = new cl_previsaodespesaplano();
            $camposPlanos = " c55_sequencial , c55_codigo as codigo, c55_titulo as descricao, c55_valor as valor";
            $wherePlanos = " c55_previsaodespesa = {$retorno->previsao->c333_sequencial} ";
            $sqlPrevisaoPlanos = $daoPrevisaoPlanos->sql_query(null, $camposPlanos, null, $wherePlanos);

            $rsPrevisaoPlanos = db_query($sqlPrevisaoPlanos);
            if (!$rsPrevisaoPlanos) {
                throw new DBException("Ocorreu um erro ao pesquisar os planos orçamentários vinculados à despesa.\n\nContate o suporte.");
            }

            $previsaodespesa = $retorno->previsao->c333_sequencial;

            $retorno->previsao->planos = db_utils::makeCollectionFromRecord($rsPrevisaoPlanos, function($item) use ($previsaodespesa) {

                $sSqlLinha = " select c07_sequencial as codigo, c41_valorlinha as valor, c07_titulo as descricao  from linhaspacto 
                           inner join  previsaodespesalinhaspacto on  c07_sequencial = c41_linhaspacto 
                          where  c41_previsaodespesaplano =". $item->c55_sequencial . " and  c41_previsaodespesa=".$previsaodespesa;


                $rsLinhasPac = db_query($sSqlLinha);

                if (!$rsLinhasPac) {
                    throw new DBException("Ocorreu um erro ao pesquisar as Linhas de Pacto .");
                }

                $aLinhaPactos = pg_fetch_all($rsLinhasPac);

                $item->linhaPacto = (!empty($aLinhaPactos) ? $aLinhaPactos : array());
                $item->sId = $item->c55_sequencial;
                return $item;
            });


            break;

        case 'emitirPrevisaoDespesa':
            $campos = "c333_sequencial, c333_ano, c333_esferaorcamentaria, c333_orcorgao, c333_orcunidade,
            o40_descr || ' / ' || o41_descr as unidade_orcamentaria, c333_orcfuncao, o52_descr, c333_orcsubfuncao, 
            o53_descr, c333_orcprograma, o54_descr,  c60_estrut,
            c333_orcprojativ, o55_descr, c333_ppasubtitulolocalizadorgasto, o11_descricao, c333_conplanoorcamento,  
            c60_descr, c333_identificadoruso, c333_tipodetalhamento, c333_grupofonterecursos, c333_especificacaofonte,  
            c333_identificadorresultadoprimario, c333_previsao, c333_planoorcamentario";

            $where = montarFiltrosEmissao($parametros);
            $dao = new cl_previsaodespesa();
            $sql = $dao->sql_previsao_despesa(null, $campos, '1', implode(' and ', $where));
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Erro ao pesquisar previsão de despesa.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new DBException("Não foi encontrado nenhuma previsão de despesa para os filtros informados.");
            }

            $cabecalho = array(
                    'Código da Dotação',
                    'Esfera Orçamentária',
                    'Unidade Orçamentária',
                    'Função',
                    'Subfunção',
                    'Programa',
                    'Ação',
                    'Subtítulo',
                    'Natureza de Despesa',
                    'Identificador de Uso',
                    'Tipo de Detalhamento',
                    'Grupo de Fonte de Recurso',
                    'Especificação da Fonte de Recurso',
                    'Identificador de Resultado Primário',
                    'Previsão 2019',

                    'Plano Orçamentário',
                    'Valor plano',
                    'Linhas Pacto',
                    'Valor Pacto'
            );


            $arquivo = 'tmp/previsao_despesa' . time() . '.csv';
            $arquivosalvar = fopen($arquivo, 'w+');

            fputs($arquivosalvar, implode(';', $cabecalho)."\n");

            $registros = db_utils::makeCollectionFromRecord($rs, function ($registro) use ($arquivosalvar) {



                $sSqlBuscaPlano = " select  c55_titulo, c55_valor,
                                               json_agg(
                                                    concat(
                                                    '{', 
                                                         '\"linhaspacto\" : \"' || c07_titulo || '\",'
                                                          '\"valor\":'        || c41_valorlinha 
                                                         , 
                                                    '}')) as jsonteste from  previsaodespesaplano 
                                    inner join  previsaodespesalinhaspacto on c41_previsaodespesaplano = c55_sequencial
                                    inner join  linhaspacto  on  c07_sequencial  = c41_linhaspacto
                                    where c55_previsaodespesa = {$registro->c333_sequencial}  group by  c55_sequencial ";


                $rsBuscaPlano   = db_query($sSqlBuscaPlano);
                $array = pg_fetch_all($rsBuscaPlano);

                if (empty($array)) {
                    return;
                }

                $linhaspacots = array();

                foreach ($array as $value) {

                    $json =  stripcslashes( str_replace(array('"{', '}"'), array('{', '}'), $value['jsonteste']));
                    $linhaspacots[$value['c55_titulo']]['linhas'] = json_decode(utf8_encode($json));
                    $linhaspacots[$value['c55_titulo']]['valor']  = $value['c55_valor'];
                }

                $dado = array(
                    $registro->c333_sequencial,
                    esferaOrcamentaria($registro->c333_esferaorcamentaria),
                    str_pad($registro->c333_orcorgao, 2, '0') . str_pad($registro->c333_orcunidade, 2, '0') . ' - ' . $registro->unidade_orcamentaria,
                    str_pad($registro->c333_orcfuncao, 2, '0', STR_PAD_LEFT) . " - {$registro->o52_descr}",
                    str_pad($registro->c333_orcsubfuncao, 3, '0', STR_PAD_LEFT) . " - {$registro->o53_descr}",
                    str_pad($registro->c333_orcprograma, 4, '0', STR_PAD_LEFT) . " - {$registro->o54_descr}",
                    str_pad($registro->c333_orcprojativ, 4, '0', STR_PAD_LEFT) . " - {$registro->o55_descr}",
                    str_pad($registro->c333_ppasubtitulolocalizadorgasto, 4, '0', STR_PAD_LEFT) . " - {$registro->o11_descricao}",
                    substr($registro->c60_estrut, 0, 13) . " - {$registro->c60_descr}",
                    identificadorUso($registro->c333_identificadoruso),
                    tipoDetalhamento($registro->c333_tipodetalhamento),
                    grupoFonteRecursos($registro->c333_grupofonterecursos),
                    especificacaoFonte($registro->c333_especificacaofonte),
                    identificadorResultadoPrimario($registro->c333_identificadorresultadoprimario),
                    db_formatar($registro->c333_previsao, 'f')
                );

                $count = 0;

                $tmp = array();
                foreach ($linhaspacots as $nomeplano =>  $linhaspa) {

                    if ($count == 0) {
                        $dado[] = utf8_decode($nomeplano);
                        $dado[] = db_formatar($linhaspa['valor'], 'f');
                    } else {

                        $tmp = array_fill(0, 15, ' ');

                        $tmp[] = utf8_decode($nomeplano);
                        $tmp[] = $linhaspa['valor'];
                        $linep = implode(';', $tmp);
                        fputs($arquivosalvar, $linep."\n");
                    }

                    foreach ($linhaspa['linhas'] as $linhas) {

                        $tmp2 = array();
                        if ($count == 0) {

                            $dado[] = utf8_decode($linhas->linhaspacto);
                            $dado[] = db_formatar($linhas->valor, 'f');
                            $line = implode(';', $dado);
                            fputs($arquivosalvar, $line."\n");
                        } else {
                            $tmp2[] = array_fill(0, 17, ' ');
                            $tmp2[0][] = utf8_decode($linhas->linhaspacto);
                            $tmp2[0][] = db_formatar($linhas->valor, 'f');
                            $line2 = implode(';', $tmp2[0]);
                            fputs($arquivosalvar, $line2."\n");
                        }

                        $count++;
                    }
                }

                return $dado;
            });

            $retorno->arquivo  = $arquivo;

            break;
        case 'emitirPlanoOrcamentario':
            $campos = "c333_sequencial, c333_orcprojativ, o55_descr, c333_planoorcamentario";
            $where = montarFiltrosEmissao($parametros);
            $dao = new cl_previsaodespesa();
            $sql = $dao->sql_previsao_despesa(null, $campos, '1', implode(' and ', $where));
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Erro ao pesquisar previsão de despesa.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new DBException("Não foi encontrado nenhum plano orçamentário para os filtros informados.");
            }

            $conteudo = array(
                array(
                    'Código da Dotação',
                    'Ação',
                    'Código',
                    'Plano Orçamentário',
                    'Valor'
                ),
            );

            db_utils::makeCollectionFromRecord($rs, function ($registro) use (&$conteudo) {

                $acao = str_pad($registro->c333_orcprojativ, 4, '0', STR_PAD_LEFT) . " - {$registro->o55_descr}";
                $planosOrcamentarios = JSON::create()->stringify(array('campo' => 'desativado'));
            });

            $arquivo = 'tmp/plano_orcamentario' . time() . '.csv';
            $cvs = new Dumper();
            $cvs->setCsvControl(';');
            $retorno->arquivo = $cvs->dumpToFile($conteudo, $arquivo);
            break;


        case 'adicionarPlanoLinhaPacto':

            if (empty($parametros->codigoPrevisao)) {
                throw new ParameterException("Nenhuma despesa foi informada para adicionar o plano orçamentário.");
            }

            $itens = $oJson->decode($parametros->itens);

            deletaPlanoOrcamentario($parametros->codigoPrevisao);

            foreach ($itens as $item) {

                if (empty($item->descricao) || empty($item->valor)) {
                    throw new ParameterException("Os campos Título e Valor são de preenchimento obrigatório.");
                }

                $valorPlano = str_replace(",", ".", $item->valor);

                $daoPrevisaoPlanos = new cl_previsaodespesaplano();
                $wherePlanosInclusos = " c55_previsaodespesa = {$parametros->codigoPrevisao} ";
                $sqlPlanosInclusos = $daoPrevisaoPlanos->sql_query_file(null, "sum(c55_valor) as total", null, $wherePlanosInclusos);
                $rsPlanosInclusos  = db_query($sqlPlanosInclusos);

                if (!$rsPlanosInclusos) {
                    throw new Exception("Não foi possível buscar o valor dos planos inclusos na despesa.\nContate o suporte.");
                }

                $planosInclusos = db_utils::fieldsMemory($rsPlanosInclusos, 0);
                $valorPlanosInclusos = empty($planosInclusos->total) ? 0 : $planosInclusos->total;

                $daoDespesa = new cl_previsaodespesa();
                $sqlDespesa = $daoDespesa->sql_query_file($parametros->codigoPrevisao);
                $rsDespesa  = db_query($sqlDespesa);

                if (!$rsDespesa) {
                    throw new Exception("Não foi possível buscar a despesa para vincular o plano orçamentário.\nContate o suporte.");
                }

                $despesa = db_utils::fieldsMemory($rsDespesa, 0);
                $valorDespesa = $despesa->c333_previsao;

                if ($valorPlano  > $valorDespesa) {
                   throw new Exception("Não foi possível inserir o Plano Orçamentário '{$item->descricao}', pois o valor total dos planos inclusos ultrapassam o valor da despesa.");
                }

                $daoPrevisaoPlanos->c55_previsaodespesa = $parametros->codigoPrevisao;
                $daoPrevisaoPlanos->c55_titulo = $item->descricao;
                $daoPrevisaoPlanos->c55_valor  = db_formatar($valorPlano, "p");
                $daoPrevisaoPlanos->c55_codigo =   $item->codigo ;

                if (!empty($item->c55_sequencial)) {

                    $daoPrevisaoPlanos->c55_sequencial = $item->c55_sequencial;
                    $daoPrevisaoPlanos->alterar($item->c55_sequencial);
                } else {

                    $daoPrevisaoPlanos->incluir(null);
                }

                $codigoPlano = $daoPrevisaoPlanos->c55_sequencial;

                if ($daoPrevisaoPlanos->erro_status == "0") {
                    throw new Exception("Não foi possível incluir um plano orçamentário.\n\nContate o suporte.");
                }

                if (empty($item->linhasPacto)) {
                    throw new Exception("Não foi possível incluir um plano orçamentário.\n\n Linhas de Pacto faltando.");
                }

                foreach ($item->linhasPacto as $linhasPacto) {

                    $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();

                    $whereLinhasInclusas = " c41_previsaodespesaplano = {$codigoPlano} ";
                    $sqlLinhasInclusas = $daoLinhasPactoVinculo->sql_query(null, "sum(c41_valorlinha) as total", null, $whereLinhasInclusas);
                    $rsLinhasInclusas = db_query($sqlLinhasInclusas);

                    if (!$rsLinhasInclusas) {
                        throw new Exception("Não foi possível buscar o valor das linhas de pacto vinculadas ao plano orçamentário.\nContate o suporte.");
                    }

                    $linhasInclusas = db_utils::fieldsMemory($rsLinhasInclusas, 0);
                    $valorLinhasInclusas = empty($linhasInclusas->total) ? 0 : $linhasInclusas->total;


                    $daoLinhasPacto = new cl_linhaspacto();
                    $sqlLinhaPacto = $daoLinhasPacto->sql_query_file($linhasPacto->codigo, 'c07_titulo as titulo, c07_valor as valor');
                    $rsLinhaPacto = db_query($sqlLinhaPacto);

                    if (!$rsLinhaPacto) {
                        throw new Exception("Não foi possível buscar o valor da linha a ser vinculada ao plano.\nContate o suporte.");
                    }

                    $linha = db_utils::fieldsMemory($rsLinhaPacto, 0);
                    $valorLinha = $linhasPacto->valor;


                    if ($valorLinha > $valorPlano) {
                        throw new Exception("Não foi possível inserir a Linha de Pacto '{$linha->titulo}', pois o valor total das linhas vinculadas ultrapassam o valor do plano.");
                    }

                    $daoLinhasPactoVinculo->c41_previsaodespesaplano = $codigoPlano;
                    $daoLinhasPactoVinculo->c41_previsaodespesa = $parametros->codigoPrevisao;
                    $daoLinhasPactoVinculo->c41_linhaspacto = $linhasPacto->codigo;
                    $daoLinhasPactoVinculo->c41_valorlinha = $linhasPacto->valor;

                    $sqlLinhaPactoExist = "select * from previsaodespesalinhaspacto  
                                            where c41_previsaodespesa = {$parametros->codigoPrevisao} and  
                                            c41_linhaspacto = {$linhasPacto->codigo} and c41_previsaodespesaplano = {$codigoPlano}";


                    $rsLinhaPactoExist =  db_query($sqlLinhaPactoExist);

                    if (!$rsLinhaPactoExist) {
                       throw new Exception("Não foi possível buscar o valor da linha a ser vinculada ao plano e ao plano de orcamento .\nContate o suporte.");
                    }

                    $oPrevisaodespesalinhaspacto = pg_fetch_object($rsLinhaPactoExist);

                    if (!empty($oPrevisaodespesalinhaspacto)) {
                        $daoLinhasPactoVinculo->c41_sequencial = $oPrevisaodespesalinhaspacto->c41_sequencial;
                        $daoLinhasPactoVinculo->alterar($oPrevisaodespesalinhaspacto->c41_sequencial);
                    } else {

                        $daoLinhasPactoVinculo->incluir(null);
                    }

                    if ($daoLinhasPactoVinculo->erro_status == "0") {
                        $msgErro = "Não foi possível incluir a linha de pacto.\n";
                        $msgErro .= "Consulte o cadastro em Contabilidade > Cadastros > Linhas de Pacto > Alteração";
                        throw new Exception($msgErro);
                    }
                }
            }

            $retorno->mensagem = "Plano orçamentário e Linhas Pacto salvas com sucesso.";

            break;
        case 'excluirLinhaPacto':
            if (empty($parametros->codigoLinha)) {
                throw new ParameterException("É necessário selecionar uma linha de pacto para excluir.");
            }

            if (empty($parametros->codigoPlano)) {
                throw new ParameterException("É necessário selecionar um Plano orçamentário para excluir a linha de pacto.");
            }

            $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();
            $whereExclusao = " c41_previsaodespesaplano = {$parametros->codigoPlano} AND c41_linhaspacto =  {$parametros->codigoLinha} ";
            $daoLinhasPactoVinculo->excluir(null, $whereExclusao);

            if ($daoLinhasPactoVinculo->erro_status == "0") {
                $msgErro = "Não foi possível excluir a linha de pacto.\n";
                $msgErro .= "Contate o suporte.";
                throw new Exception($msgErro);
            }

            $retorno->mensagem = "Linha de pacto excluída com sucesso.";
            break;
        case 'buscarLinhasPactoPlanoOrcamentario':
            if (empty($parametros->codigoPlanoOrcamentario)) {
                throw new ParameterException("É necessário informar um plano orçamentário para buscas as linhas de pacto.");
            }

            $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();

            $where = " c41_previsaodespesaplano = {$parametros->codigoPlanoOrcamentario} ";
            $sqlLinhasPactoVinculo = $daoLinhasPactoVinculo->sql_query(null, 'linhaspacto.c07_sequencial as sCodigo, linhaspacto.c07_titulo as sDescricao', null, $where);
            $rsLinhasPactoVinculo = db_query($sqlLinhasPactoVinculo);

            if (!$rsLinhasPactoVinculo) {
                throw new Exception("Não foi possível buscar as linhas de pacto vinculadas ao Plano orçamentário.\nContate o suporte.");
            }

            $retorno->linhasPacto = db_utils::getCollectionByRecord($rsLinhasPactoVinculo);
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);



function deletaPlanoOrcamentario($previsaodespesa)
{

    $sSql = "select * from previsaodespesaplano  where c55_previsaodespesa = {$previsaodespesa} and  c55_codigo = 0;";
    $rsprevisaodespesaplano = db_query($sSql);

    if (!$rsprevisaodespesaplano) {
        throw new Exception("Não foi possível buscar plano orcamentario para excluir.\n\nContate o suporte.");
    }

    $oPrevisaodespesaplano = pg_fetch_object($rsprevisaodespesaplano);


    if (empty($oPrevisaodespesaplano)) {
        return;
    }

    $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();

    $whereExclusaoVinculo = " c41_previsaodespesa = {$previsaodespesa} and c41_previsaodespesaplano =". $oPrevisaodespesaplano->c55_sequencial;
    $daoLinhasPactoVinculo->excluir(null, $whereExclusaoVinculo);

    if ($daoLinhasPactoVinculo->erro_status == "0") {
        throw new Exception("Não foi possível descinvular as linhas de pacto do plano orçamentário informado.\n\nContate o suporte.");
    }

    $daoPrevisaoPlanos = new cl_previsaodespesaplano();
    $wherePlanos = " c55_previsaodespesa = {$previsaodespesa} and c55_sequencial = ".$oPrevisaodespesaplano->c55_sequencial ;
    $daoPrevisaoPlanos->c55_previsaodespesa = $previsaodespesa;
    $daoPrevisaoPlanos->excluir(null, $wherePlanos);

    if ($daoPrevisaoPlanos->erro_status == "0") {
        $msgErro = "Não foi possível incluir o plano orçamentário.\n";
        $msgErro .= "Contate o suporte.";
        throw new Exception($msgErro);
    }
}


function montarFiltrosEmissao($parametros)
{
    $where = array();

    if ($parametros->codigoDotacao !== '') {
        $where[] = "c333_sequencial = {$parametros->codigoDotacao}";
    }
    if ($parametros->esferaOrcamentaria !== '') {
        $where[] = "c333_esferaorcamentaria = {$parametros->esferaOrcamentaria}";
    }
    if ($parametros->unidadeOrcamentaria !== '') {
        $where[] = " c333_orcorgao = " . substr($parametros->unidadeOrcamentaria, 0, 2);
        $where[] = " c333_orcunidade = " . substr($parametros->unidadeOrcamentaria, 2, 2);
    }

    if ($parametros->funcao !== '') {
        $where[] = " c333_orcfuncao = {$parametros->funcao}";
    }
    if ($parametros->subfuncao !== '') {
        $where[] = " c333_orcsubfuncao = {$parametros->subfuncao}";
    }
    if ($parametros->programa !== '') {
        $where[] = "c333_orcprograma = " . (int)$parametros->programa;
    }
    if ($parametros->acao !== '') {
        $where[] = " c333_orcprojativ = {$parametros->acao}";
    }
    if ($parametros->subtitulo !== '') {
        $where[] = "c333_ppasubtitulolocalizadorgasto = {$parametros->subtitulo}";
    }
    if ($parametros->naturezaDespesa !== '') {
        $where[] = "c333_conplanoorcamento = {$parametros->naturezaDespesa}";
    }
    if ($parametros->identificadorUso !== '') {
        $where[] = "c333_identificadoruso = {$parametros->identificadorUso}";
    }
    if ($parametros->tipoDetalhamento !== '') {
        $where[] = "c333_tipodetalhamento = '{$parametros->tipoDetalhamento}'";
    }
    if ($parametros->grupoFonteRecurso !== '') {
        $where[] = "c333_grupofonterecursos = '{$parametros->grupoFonteRecurso}'";
    }
    if ($parametros->especificacaoFonte !== '') {
        $where[] = "c333_especificacaofonte = '{$parametros->especificacaoFonte}'";
    }
    if ($parametros->identificadorResultadoPrimario) {
        $where[] = "c333_identificadorresultadoprimario = '{$parametros->identificadorResultadoPrimario}'";
    }

    return $where;
}

function esferaOrcamentaria($codigo)
{
    $esferas = array(
        10 => '10 - F - Orçamento Fiscal',
        20 => '20 - S - Orçamento da Seguridade Social',
        30 => '30 - I - Orçamento de Investimento',
    );

    return $esferas[$codigo];
}

function identificadorUso($codigo)
{
    $identificadorUso = array(
        0 => '0 - Recursos não destinados à contrapartida ou à identificação de despesas destinadas ao mínimo da Saúde ou ao mínimo da Educação',
        1 => '1 - Contrapartida de empréstimos do BIRD',
        2 => '2 - Contrapartida de empréstimos do BID',
        3 => '3 - Contrapartida de empréstimos do CAF',
        4 => '4 - Contrapartida de outros empréstimos',
        5 => '5 - Contrapartida de doações',
        6 => '6 - Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Saúde',
        7 => '7 - Recursos de Contrapartida de Convênio',
        8 => '8 - Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Educação',
    );

    return $identificadorUso[$codigo];
}

function tipoDetalhamento($codigo)
{
    $tipoDetalhamento = array(
        0 => '0 - Sem Detalhamento',
        1 => '1 - Cadastro',
        2 => '2 - Operação de Crédito',
        3 => '3 - Convênio',
    );

    return $tipoDetalhamento[$codigo];
}

function grupoFonteRecursos($codigo)
{
    $grupoFonteRecursos = array(
        1 => '1 - Recursos do Tesouro - Exercício Corrente',
        2 => '2 - Recursos de Outras Fontes - Exercício Corrente',
    );

    return $grupoFonteRecursos[$codigo];
}

function especificacaoFonte($codigo)
{
    $especificacaoFonte = array(
        '00' => '00 - Ordinários Não Provenientes de Impostos',
        '01' => '01 - Operações de Crédito',
        '02' => '02 - Recursos de Convênios',
        '03' => '03 - Recursos Próprios Não Financeiros',
        '05' => '05 - Contribuição do Salário-Educação',
        '06' => '06 - Recursos Destinados à Alimentação Escolar',
        '07' => '07 - Recursos do Sistema Único de Saúde',
        '08' => '08 - Recursos do Fundo Nacional de Assistência Social',
        '10' => '10 - Recursos Vinculados ao Fundo de Mobilidade',
        '12' => '12 - Outorga Onerosa do Direito de Construir',
        '13' => '13 - Ordinários Provenientes de Impostos',
        '14' => '14 - Transferências Constitucionais Provenientes de Impostos',
        '15' => '15 - Recursos do Fundeb',
        '17' => '17 - Outras Transferências da União',
        '18' => '18 - Recursos Vinculados à Previdência Municipal',
        '36' => '36 - Recursos de Multas de Trânsito',
        '37' => '37 - Contribuição sobre a Iluminação Pública',
        '38' => '38 - Compensação Financeira pela Exploração e Produção de Petróleo',
        '53' => '53 - Taxas e Multas pelo Exercício do Poder de Polícia',
        '80' => '80 - Remuneração das Disponibilidades do Tesouro',
        '82' => '82 - Recursos Próprios Financeiros',
        '83' => '83 - Recursos de Alienação de Bens e Direitos do Patrimônio Público',
        '90' => '90 - Recursos do Tesouro - a Definir',
        '99' => '99 - Recursos Extraorçamentários',
    );

    return $especificacaoFonte[$codigo];
}

function identificadorResultadoPrimario($codigo)
{
    $identificadorResultadoPrimario = array(
        0 => '0 - Financeira',
        1 => '1 - Primária Obrigatória',
        2 => '2 - Primária Discricionária'
    );

    return $identificadorResultadoPrimario[$codigo];
}

function cadastraPlanoPadrao($parametros, $previsaodespesa)
{
    $daoPrevisaoPlanos = new cl_previsaodespesaplano();
    $daoPrevisaoPlanos->c55_previsaodespesa = $previsaodespesa;
    $daoPrevisaoPlanos->c55_titulo = $parametros->acaoDescricao;
    $daoPrevisaoPlanos->c55_valor = db_formatar($parametros->previsao2019, "p");
    $daoPrevisaoPlanos->c55_codigo = '0';
    $daoPrevisaoPlanos->incluir(null);

    if ($daoPrevisaoPlanos->erro_status == "0") {
        throw new Exception("Não foi possível incluir o Plano orçamentário padrão.\nContate o suporte.");
    }

    $daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();
    $daoLinhasPactoVinculo->c41_valorlinha = db_formatar($parametros->previsao2019, "p");
    $daoLinhasPactoVinculo->c41_previsaodespesaplano = $daoPrevisaoPlanos->c55_sequencial;
    $daoLinhasPactoVinculo->c41_linhaspacto = '0';  // linha padrao ja cadastrada
    $daoLinhasPactoVinculo->c41_previsaodespesa = $previsaodespesa;

    $daoLinhasPactoVinculo->incluir(null);

    if ($daoLinhasPactoVinculo->erro_status == "0") {
        throw new Exception("Não foi possível incluir o Linha de Pacto padrão.\nContate o suporte.". $daoLinhasPactoVinculo->erro_msg);
    }

    $planoOrc = $daoPrevisaoPlanos->c55_sequencial;

    $sqlPlanoPadrao = $daoPrevisaoPlanos->sql_query($daoPrevisaoPlanos->c55_sequencial, "c55_codigo as codigo, c55_valor as valor, c55_titulo as descricao");
    $rsPlanoPadrao = db_query($sqlPlanoPadrao);

    if (!$rsPlanoPadrao) {
        throw new Exception("Não foi possível buscar o  plano orçamentário padrão para a despesa.\nContate o suporte.");
    }

    return db_utils::makeCollectionFromRecord($rsPlanoPadrao, function($item) use ($previsaodespesa, $planoOrc) {

        $sSqlLinha = " select c07_sequencial as codigo, c41_valorlinha as valor, c07_titulo as descricao  from linhaspacto 
                           inner join  previsaodespesalinhaspacto on  c07_sequencial = c41_linhaspacto 
                          where  c41_previsaodespesaplano =". $planoOrc . " and  c41_linhaspacto = 0  and  c41_previsaodespesa=".$previsaodespesa;

        $rsLinhasPac = db_query($sSqlLinha);

        if (!$rsLinhasPac) {
            throw new DBException("Ocorreu um erro ao pesquisar as Linhas de Pacto .");
        }

        $aLinhaPactos = pg_fetch_all($rsLinhasPac);

        $item->linhaPacto = (!empty($aLinhaPactos) ? $aLinhaPactos : array());

        return $item;
    });

}
