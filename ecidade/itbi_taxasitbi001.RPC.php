<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\Tributario\ITBI\Repository\TaxasitbiRepository;
use ECidade\Tributario\ITBI\Model\Taxasitbi;
use ECidade\Tributario\ITBI\Repository\TaxasitbitaxaRepository;
use ECidade\Tributario\ITBI\Model\Taxasitbitaxa;
use ECidade\Tributario\Arrecadacao\Repository\TaxasLancadasRepository;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case "salvarTipo":
                $taxasitbiRepository = TaxasitbiRepository::getInstance();
                $taxasitbi = new Taxasitbi();

                $taxasitbi->setSequencial($parametro->it36_sequencial);
                $taxasitbi->setDescricao($parametro->it36_descricao);
                $taxasitbi->setImovelurbano(($parametro->it36_imovelurbano ? "t" : "f"));
                $taxasitbi->setImovelrural(($parametro->it36_imovelrural ? "t" : "f"));
                $taxasitbi->setImovelurbanopleno(($parametro->it36_imovelurbanopleno ? "t" : "f"));

                $retorno->it36_sequencial = $taxasitbiRepository->persist($taxasitbi);

                $retorno->mensagem = "Tipo salvo com sucesso.";
            break;

        case "buscarTipo":
                $taxasitbiRepository = TaxasitbiRepository::getInstance();

                $oTipo = $taxasitbiRepository->getTipoById($parametro->it36_sequencial);

                $taxasitbitaxaRepository = TaxasitbitaxaRepository::getInstance();
                $taxasitbitaxa = new Taxasitbitaxa();

                $taxasitbitaxa->setTaxasitbi($parametro->it36_sequencial);
                $aTaxas = $taxasitbitaxaRepository->getTaxas($taxasitbitaxa);

                $aTaxas = array_map(function ($oTaxa){
                    return (object) array(
                        "codigo" => $oTaxa->it37_taxaslancadas,
                        "descricao" => $oTaxa->ar44_descricao,
                        "calculaSobre" => $oTaxa->it37_calculasobre,
                        "tipo" => $oTaxa->ar44_tipo,
                        "inicioFaixa" => number_format($oTaxa->it37_iniciofaixa, 2, ",", "."),
                        "fimFaixa" => number_format($oTaxa->it37_fimfaixa, 2, ",", "."),
                        "faixa" => (object) array(
                            "valorInicio" => number_format($oTaxa->it37_iniciofaixa, 2, ",", "."),
                            "valorFim" => number_format($oTaxa->it37_fimfaixa, 2, ",", ".")
                        )
                    );
                }, $aTaxas);

                $retorno->aTaxas = $aTaxas;
                $retorno->oTipo = $oTipo;
            break;

        case "salvarTaxas":
                $taxasitbitaxaRepository = TaxasitbitaxaRepository::getInstance();
                $taxasitbitaxa = new Taxasitbitaxa();

                $taxasitbitaxa->setTaxasitbi($parametro->it36_sequencial);

                $taxasitbitaxaRepository->delete($taxasitbitaxa);

                $oTaxas = JSON::create()->parse($parametro->taxas);

                foreach ($oTaxas as $oTaxa) {
                    $taxasitbitaxa->setTaxaslancadas($oTaxa->codigo);
                    $taxasitbitaxa->setCalculasobre($oTaxa->calculaSobre);

                    if (!empty($oTaxa->faixa)) {
                        $taxasitbitaxa->setIniciofaixa($oTaxa->faixa->valorInicio);
                        $taxasitbitaxa->setFimfaixa($oTaxa->faixa->valorFim);
                    }

                    $taxasitbitaxaRepository->persist($taxasitbitaxa);
                }

                $retorno->mensagem = "Taxa(s) salva(s) com sucesso.";
            break;
        case "listarTipos":
                $taxasitbiRepository = TaxasitbiRepository::getInstance();

                if ($parametro->tipo == "urbano") {
                    $sWhere = " (it36_imovelurbano = 't' OR it36_imovelurbanopleno = 't') ";
                } else {
                    $sWhere = " it36_imovelrural = 't' ";
                }

                $aTipos = $taxasitbiRepository->getAllTipos($sWhere);

                $retorno->aTipos = $aTipos;
            break;
        case "buscarTaxasTipo":
                $valorVenalTerreno = 0;
                $valorVenalConstrucao = 0;

                if ($parametro->tipo == "urbano") {
                    $cl_iptucalc = new cl_iptucalc();
                    $cl_iptucale = new cl_iptucale();
                    $anousu = db_getsession("DB_anousu");

                    if (empty($parametro->matricula)) {
                        throw new Exception("Matricula obrigatória");
                    }

                    $rIptucalc = db_query($cl_iptucalc->sql_query_file($anousu, $parametro->matricula, "j23_vlrter AS it01_valorterreno"));

                    if (!$rIptucalc) {
                        throw new Exception("Erro ao buscar o os dados da tabela iptucalc");
                    }

                    $iptuCalc = \db_utils::fieldsMemory($rIptucalc,0);

                    $valorVenalTerreno = $iptuCalc->it01_valorterreno;

                    $rIptucale = db_query($cl_iptucale->sql_query($anousu, $parametro->matricula, null));

                    if (!$rIptucale) {
                        throw new Exception("Erro ao buscar o os dados da tabela iptucale");
                    }

                    $aIptuCale = \db_utils::getCollectionByRecord($rIptucale);

                    if (count($aIptuCale)) {
                        $aValorConstrucao = array_map(function ($oIptucale) {
                            return floatval($oIptucale->j22_valor);
                        }, $aIptuCale);

                        $valorVenalConstrucao = array_sum($aValorConstrucao);
                    }
                }

                $taxasitbitaxaRepository = TaxasitbitaxaRepository::getInstance();
                $taxasitbitaxa = new Taxasitbitaxa();

                $taxasitbitaxa->setTaxasitbi($parametro->it36_sequencial);
                $aTaxas = $taxasitbitaxaRepository->getTaxas($taxasitbitaxa);

                $taxasLancadasRepository = TaxasLancadasRepository::getInstance();
                $aTaxaLancada = array();

                foreach ($aTaxas as $oTaxa) {
                    $oTaxaLancada = $taxasLancadasRepository->getTaxa($oTaxa->ar44_sequencial);
                    $oTaxaLancada->it37_calculasobre = $oTaxa->it37_calculasobre;
                    $oTaxaLancada->it37_iniciofaixa = $oTaxa->it37_iniciofaixa;
                    $oTaxaLancada->it37_fimfaixa = $oTaxa->it37_fimfaixa;
                    $oTaxaLancada->bMostra = true;
                    $oTaxaLancada->aliquota = $oTaxaLancada->ar44_valorinflator;

                    if ($oTaxaLancada->ar44_tipo == 2) {
                        if ($oTaxaLancada->it37_calculasobre == 1) {
                            $valorCalculado = ($oTaxaLancada->ar44_valorinflator / 100) * $valorVenalTerreno;
                        } else if ($oTaxaLancada->it37_calculasobre == 2) {
                            $valorCalculado = ($oTaxaLancada->ar44_valorinflator / 100) * $valorVenalConstrucao;
                        } else if ($oTaxaLancada->it37_calculasobre == 3) {
                            $valorCalculado = ($oTaxaLancada->ar44_valorinflator / 100) * ($valorVenalTerreno + $valorVenalConstrucao);
                        }

                        $oTaxaLancada->i02_valor = $valorCalculado;
                    }

                    $aTaxaLancada[] = $oTaxaLancada;
                }

                $retorno->aTaxas = $aTaxaLancada;
            break;

        case "buscaCgmParametro":
            $rDadosCgmDoParametro = db_query("select * from parreciboitbi inner join cgm on z01_numcgm =  it17_numcgm");

            if (!$rDadosCgmDoParametro) {
                throw new Exception("Erro ao buscar o os dados referente ao cmg do parâmetro");
            }
            $dadosCgmDoParametro = \db_utils::fieldsMemory($rDadosCgmDoParametro,0);

            $dadosCgm = array();
            $dadosCgm = $dadosCgmDoParametro;

            $retorno->dadosDoCgm = $dadosCgm;

            break;
        default:
            throw new Exception('Nenhuma ação encontrada.');
            break;
    }

    db_fim_transacao(false);
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);
