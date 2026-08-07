<?php
/**
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

use ECidade\Financeiro\Orcamento\Repository\RecursoRepository as RecursoRepositoryAlias;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_contacorrenteatributos.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$clconplano = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig = new cl_conlancamdig;
$clconlancamdoc = new cl_conlancamdoc;
$clconlancam = new cl_conlancam;
$clconplanoexe = new cl_conplanoexe;

$oJson = new services_json();
$oParametros = $parametros = JSON::requestParameters();
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

$c70_data_dia = date("d", db_getsession("DB_datausu"));
$c70_data_mes = date("m", db_getsession("DB_datausu"));
$c70_data_ano = db_getsession('DB_anousu');

function buscarInformacoes($conta, $sinal)
{

    $anoSessao = db_getsession('DB_anousu');
    $instituicaoSessao = db_getsession('DB_instit');

    $campos = implode(',', array(
        'c60_codcon as codigo_conta',
        'c61_reduz as codigo_reduzido',
        'c60_descr as descricao_conta',
        'c60_estrut as estrutural_conta',
        'c60_identificadorfinanceiro as identificador_financeiro',
        'c121_sequencial as codigo_atributo',
        'c121_sigla as sigla_atributo',
        'c61_codigo as codigo_recurso',
        'o15_recurso as fonte_recurso',
        'c121_descricao as descricao_atributo',
        'c120_conplanosistema as codigo_sistema',
        'c122_descricao as descricao_sistema',
        'c60_codsis as codigo_sistema',
        'c120_infocomplementar as atributo',
        "'{$sinal}' as sinal_conta",
        'complementofonterecurso.o200_sequencial as complemento'
    ));

    $sqlBuscaInformacoes = "
        select distinct {$campos}
          from conplanoatributos
               join conplanoinfocomplementar on c121_sequencial = c120_infocomplementar
               join conplanosistema on c122_sequencial = c120_conplanosistema
               join conplano on (c60_codcon, c60_anousu) = (c120_conplano, c120_anousu)
               join conplanoreduz on (c61_codcon, c61_anousu) = (c60_codcon, c60_anousu)
               join orctiporec on orctiporec.o15_codigo = conplanoreduz.c61_codigo
               left join conplanosistemaatributos on c129_conplanoinfocomplementar = c121_sequencial
               left join complementofonterecurso on orctiporec.o15_complemento = complementofonterecurso.o200_sequencial
         where c120_anousu = {$anoSessao}
           and c61_instit = {$instituicaoSessao}
           and c61_reduz in ({$conta})
         order by c120_conplanosistema, c120_infocomplementar ";

    $executaBusca = db_query($sqlBuscaInformacoes);
    return $executaBusca;
}

/**
 * @param $conta
 * @param $sigla
 *
 * @return string
 * @throws Exception
 */
function getValorAtributoMSC($conta, $sigla, $recursoLinha)
{
    switch ($sigla) {
        case 'PO':
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            return $instituicao->getCodigoTribunal();
            break;
        case 'FR':
            return $recursoLinha;
            break;
        case 'ND':
            return '999999999999998';
            break;
        case 'NR':
            return '999999999999999';
            break;
        case 'FS':
            return '04122';
            break;
        case 'ES':
            return '0';
            break;
        case 'AI':
            return (int)db_getsession("DB_anousu") - 1;
            break;
        case 'DC':
            return $conta->codigo_sistema == 9 ? '0' : '1';
            break;
        case 'FP':
            switch ($conta->identificador_financeiro) {
                case 'F':
                    $valor = '1';
                    break;
                case 'P':
                    $valor = '2';
                    break;
                default:
                    $valor = '';
                    break;
            }
            return $valor;
            break;
        case 'CO':
            return $conta->complemento;
            break;

        default:
            return '';
            break;
    }
}


/**
 * @param $conta
 * @param $sigla
 *
 * @return string
 * @throws Exception
 */
function getAtributosPadraoContaCorrente($conta, $sigla, $recursoLinha)
{
    switch ($sigla) {
        case "CTE":
        case "GND":
            $valor = "9";
            break;
        case "MOD":
            $valor = "90";
            break;
        case "ELE":
        case "SBELE":
            $valor = "99";
            break;
        case "BCO":
        case "AGE":
        case "CTA":
            $valor = "999";
            break;
        case "CNPJ":
        case "IDE":
            $valor = "99999999999999";
            break;
        case "NREC":
            $valor = "999999999999999";
            break;
        case "CTR":
        case "CRE":
        case "GEN":
        case "EO":
            $valor = "NI";
            break;
        case "FUN":
            $valor = "4";
            break;
        case "SUBF":
            $valor = "122";
            break;
        case "PROG":
        case "AC":
            $valor = "9999";
            break;
        case "SLG":
            $valor = "0";
            break;
        case "FR":
            $valor = $recursoLinha;
            break;
        case "UG":
            $departamentoSessao = db_getsession("DB_coddepto");
            $where = "k171_departamento = {$departamentoSessao} or k180_depart = {$departamentoSessao}";
            $daoUnidadeGestora = new cl_unidadegestora();
            $sqlUnidadeGestora = $daoUnidadeGestora->sql_query_ug_departamentos('k171_sequencial', $where);
            $resUnidadeGestora = db_query($sqlUnidadeGestora);
            if (!$resUnidadeGestora) {
                throw new Exception("Ocorreu um erro ao consultar a unidade gestora.");
            }
            $valor = "0";
            if (pg_num_rows($resUnidadeGestora) > 0) {
                $valor = db_utils::fieldsMemory($resUnidadeGestora, 0)->k171_sequencial;
            }
            break;
        case "ORG":
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            $valor = substr($instituicao->getCodigoTribunal(), 0, 2);
            break;
        case "UO":
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            $valor = substr($instituicao->getCodigoTribunal(), 2, 2);
            break;
        case "IRP":
            $valor = "F";
            break;
        case "IUFR":
            $recurso = RecursoRepository::getRecursoPorCodigo($conta->codigo_recurso);
            $valor = $recurso->getIdentificadorUsoLOA();
            break;
        case "TDFR":
            $recurso = RecursoRepository::getRecursoPorCodigo($conta->codigo_recurso);
            $valor = $recurso->getTipoDetalhamentoLOA();
            break;
        case "GFR":
            $recurso = RecursoRepository::getRecursoPorCodigo($conta->codigo_recurso);
            $valor = $recurso->getGrupoLOA();
            break;
        case "EFR":
            $recurso = RecursoRepository::getRecursoPorCodigo($conta->codigo_recurso);
            $valor = $recurso->getEspecificacaoLOA();
            break;
        case "DFR":
            $valor = "000";
            break;
        case 'RV':
            $valor = $recursoLinha;
            break;
        default:
            $valor = '';
    }

    return $valor;
}

function getAtributosPorSinal($lista, $sinal)
{
    $registros = [];
    foreach ($lista as $linha) {
        if ($linha->sinal_conta == $sinal) {
            $registros[] = $linha;
        }
    }

    return $registros;
}

try {
    db_inicio_transacao();

    switch ($oParametros->exec) {
        case "salvarLancamentos":
            /* Pegando as contas */
            $anoSessao = db_getsession("DB_anousu");
            $instituicao = db_getsession("DB_instit");
            $sWherePadraoDeb = "c60_estrut like '72111%' and c62_anousu = {$anoSessao} and c61_instit = {$instituicao}";
            $contaDebSql = $clconplanoexe->sql_descr("", "", "c60_estrut, c62_reduz", null, $sWherePadraoDeb);
            $rsDeb = db_query($contaDebSql);

            $sWherePadraoCred = "
            c60_estrut like '82111%' and c62_anousu = {$anoSessao} and c61_instit = {$instituicao}
            ";
            $contaCredSql = $clconplanoexe->sql_descr("", "", "c60_estrut, c62_reduz", null, $sWherePadraoCred);
            $rsCred = db_query($contaCredSql);

            if (pg_num_rows($rsDeb) == 0 || pg_num_rows($rsCred) == 0) {
                throw  new \Exception("Não foi possível localizar a(s)
                    conta(s) de crédido/débito. Procedimento abortado.");
            }

            $contaDebitoDados = pg_fetch_array($rsDeb);
            $conta72111 = $contaDebitoDados['c62_reduz'];

            $contaCreditoDados = pg_fetch_array($rsCred);
            $conta82111 = $contaCreditoDados['c62_reduz'];
            //
            /* Processamento */
            foreach ($oParametros->linhasRecursos as $dados) {
                $dadosArray = json_decode($dados, true);
                $dadosArray['diferenca'] = str_replace('.', '', $dadosArray['diferenca']);
                $dadosArray['diferenca'] = str_replace(',', '.', $dadosArray['diferenca']);

                // Valor sem sinal negativo (validando valores)
                if ($dadosArray['diferenca'] < 0) {
                    $diferencaValor = (-1) * $dadosArray['diferenca'];
                } elseif ($dadosArray['diferenca'] > 0) {
                    $diferencaValor = $dadosArray['diferenca'];
                } else {
                    // Ignorando valor 0,00
                    continue;
                }

                // Preparação dos atributos
                $arrayAtributos = array();
                $dadosContaDebito = buscarInformacoes($conta72111, 'D');
                $dadosContaCredito = buscarInformacoes($conta82111, 'C');

                $contaDebito = db_utils::getCollectionByRecord($dadosContaDebito);

                foreach ($contaDebito as &$conta) {
                    if ($conta->codigo_sistema == "1") {
                        $conta->valor = getValorAtributoMSC($conta, $conta->sigla_atributo, $dadosArray['recurso']);
                    } else {
                        $conta->valor = getAtributosPadraoContaCorrente($conta, $conta->sigla_atributo, $dadosArray['recurso']);
                    }
                }

                $contaCredito = db_utils::getCollectionByRecord($dadosContaCredito);
                foreach ($contaCredito as &$conta) {
                    if ($conta->codigo_sistema == "1") {
                        $conta->valor = getValorAtributoMSC($conta, $conta->sigla_atributo, $dadosArray['recurso']);
                    } else {
                        $conta->valor = getAtributosPadraoContaCorrente($conta, $conta->sigla_atributo, $dadosArray['recurso']);
                    }
                }

                // Iniciando a adequação ao formato esperado
                $novoArray = array_merge($contaDebito, $contaCredito);
                $atributosDebito = getAtributosPorSinal($novoArray, 'D');
                $atributosCredito = getAtributosPorSinal($novoArray, 'C');
                $lista = array_merge($atributosDebito, $atributosCredito);

                // Adequação ao formato esperado
                $i = 0;
                $controleCodigoConta = array();

                foreach ($lista as $atrib) {
                    if (!isset($controleCodigoConta[$atrib->codigo_conta])) {
                        $i = 0;

                        $objConta = new stdClass();
                        $objConta->sinal = $atrib->sinal_conta;
                        $objConta->conta_contabil = (object)array(
                            'codigo' => $atrib->codigo_conta,
                            'reduzido' => $atrib->codigo_reduzido,
                            'estrutural' => $atrib->estrutural_conta,
                            'descricao' => $atrib->descricao_conta
                        );

                        $arrayAtributos[][$i] = $objConta;
                        $controleCodigoConta[$atrib->codigo_conta] = 'ok';
                    }

                    if (!isset($objConta->conta_corrente[$atrib->codigo_sistema])) {
                        $dadosConta = new stdClass();
                        $dadosConta->codigo = $atrib->codigo_sistema;
                        $dadosConta->descricao = $atrib->codigo_sistema == 1
                            ? 'MSC'
                            : $atrib->descricao_sistema;

                        $objConta->conta_corrente[$atrib->codigo_sistema] = $dadosConta;
                        $objConta->conta_corrente[$atrib->codigo_sistema]->atributos = [];
                    }

                    $atributos = new stdClass();
                    $atributos->codigo = $atrib->codigo_atributo;
                    $atributos->descricao = $atrib->descricao_atributo;
                    $atributos->sigla = $atrib->sigla_atributo;
                    $atributos->valor = $atrib->valor;

                    $objConta->conta_corrente[$atrib->codigo_sistema]->atributos[] = $atributos;
                    $i++;
                }


                //
                // Início dos lançamentos
                $clconlancam->c70_anousu = db_getsession('DB_anousu');
                $clconlancam->c70_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
                $clconlancam->c70_valor = $diferencaValor;
                $clconlancam->incluir(null);
                if ($clconlancam->erro_status == "0") {
                  throw new Exception("Erro ao incluir dados iniciais do lançamento:" . $clconlancam->erro_msg);
                }
                $codlan = $clconlancam->c70_codlan; //pega o codigo gerado

                $erro = !EventoContabil::vincularLancamentoNaInstituicao($codlan, db_getsession('DB_instit'));

                // Texto complementar
                $clconlancamcompl->c72_complem = "Ajuste da DDR por fonte de Recursos.";
                $clconlancamcompl->incluir($codlan);
                if ($clconlancamcompl->erro_status == "0") {
                    throw new Exception("Erro ao incluir complemento do lançamento:" . $clconlancamcompl->erro_msg);
                }

                // Documento
                $clconlancamdoc->c71_codlan = $codlan;
                $clconlancamdoc->c71_coddoc = '3000'; // Lançamento genérico
                $clconlancamdoc->c71_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
                $clconlancamdoc->incluir($codlan);
                if ($clconlancamdoc->erro_status == "0") {
                    throw  new \Exception("Não foi possível vincular o
                        documento ao lançamento. Procedimento abortado.");
                }

                /*
                * Se a diferença for positiva vai ser débito da 72111 e crédito da 82111
                * Se a diferença for negativa vai ser débito da 82111 e crédito da 72111
                */

                // $conta72111 - 72111%
                // $conta82111 - 82111%
                $naturezaRecurso = 'C';
                $natureza72111 = 'D';
                $natureza82111 = 'C';

                // Lançamento
                $sErroConlancam = "Erro ao Incluir Valores do Lançamento. ";
                if ($dadosArray['diferenca'] > 0) {
                    $clconlancamval->c69_anousu = db_getsession('DB_anousu');
                    $clconlancamval->c69_codlan = $codlan;
                    $clconlancamval->c69_codhist = 8000; // DISPONIBILIDADE POR DESTINAÇÃO DE RECURSOS
                    $clconlancamval->c69_debito = $conta72111;
                    $clconlancamval->c69_credito = $conta82111;
                    $clconlancamval->c69_valor = $diferencaValor;
                    $clconlancamval->c69_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
                    $clconlancamval->incluir(null);
                    if ($clconlancamval->erro_status == "0") {
                      throw new Exception( $sErroConlancam . $clconlancamval->erro_msg );
                    }

                }

                if ($dadosArray['diferenca'] < 0) {
                    $clconlancamval->c69_anousu = db_getsession('DB_anousu');
                    $clconlancamval->c69_codlan = $codlan;
                    $clconlancamval->c69_codhist = 8000; // DISPONIBILIDADE POR DESTINAÇÃO DE RECURSOS
                    $clconlancamval->c69_debito = $conta82111;
                    $clconlancamval->c69_credito =  $conta72111;
                    $clconlancamval->c69_valor = $diferencaValor;
                    $clconlancamval->c69_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
                    $clconlancamval->incluir(null);
                    if ($clconlancamval->erro_status == "0") {
                        throw new Exception($sErroConlancam . $clconlancamval->erro_msg );
                    }

                    $natureza72111 = 'C';
                    $natureza82111 = 'D';
                    $naturezaRecurso = 'D';
                }

                // Tratamento de atributos
                $recurso = null;
                foreach ($arrayAtributos as $indice => $atributo) {
                    if (strpos($atributo[0]->conta_contabil->estrutural, '72111') === 0) {
                        $atributoDebito = $natureza72111 === 'D';
                    }

                    if (strpos($atributo[0]->conta_contabil->estrutural, '82111') === 0) {
                        $atributoDebito = $natureza82111 !== 'C';
                    }

                    if (!empty($atributo)) {
                        foreach ($atributo as $stdDadosAtributo) {
                            foreach ($stdDadosAtributo->conta_corrente as $dadosContaCorrente) {
                                if (empty($dadosContaCorrente)) {
                                    continue;
                                }
                                $atributosDebitoIndexado = array();
                                foreach ($dadosContaCorrente->atributos as $dadosAtributos) {
                                    $atributosDebitoIndexado[$dadosAtributos->sigla] = $dadosAtributos->valor;
                                    if ($dadosAtributos->sigla === "FR") {
                                        $recurso = $dadosAtributos->valor;

                                        $atributosDebitoIndexado[$dadosAtributos->sigla] =
                                            obterCodigoRecursoPorFonte($dadosAtributos->valor);
                                    }

                                    if ($dadosAtributos->sigla === "RV") {
                                        $atributosDebitoIndexado[$dadosAtributos->sigla] =
                                            obterCodigoRecursoPorFonte($dadosAtributos->valor);
                                    }
                                }

                                DBContaCorrenteAtributos::salvarAtributos(
                                    $clconlancamval,
                                    $dadosContaCorrente->codigo,
                                    $atributosDebitoIndexado,
                                    $atributoDebito
                                );
                            }
                        }

                        if (!empty($recurso)) {
                            $recurso = obterCodigoRecursoPorFonte($recurso);

                            $naturezas = ['C', 'D'];
                            foreach ($naturezas as $natureza) {
                                $debito = $natureza == 'D';

                                DBContaCorrenteAtributos::salvarRecursoLancamento(
                                    $clconlancamval,
                                    $recurso,
                                    $debito
                                );
                            }
                        }
                    }
                }
            }

            $oRetorno->mensagem = "Lançamento(s) feito(s) com sucesso.";
            break;
    }

    db_fim_transacao(false);
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $oRetorno->erro = true;
    $oRetorno->mensagem = urlencode($oErro->getMessage());
}
echo Json::create()->stringify($oRetorno);
