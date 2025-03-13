<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBseller Servicos de Informatica
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
require_once(modification("libs/JSON.php"));

use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilha as InicialPartilhaEntity;
use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas as InicialPartilhaCustasEntity;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;
use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilha as InicialPartilhaRepository;
use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas as InicialPartilhaCustasRepository;
use ECidade\Tributario\Arrecadacao\Repository\Taxa as TaxaRepository;
use ECidade\Tributario\Juridico\ProcessoForo\Repository\ProcessoForo;
use ECidade\Tributario\Juridico\ProcessoForoPartilha\Repository\ProcessoForoPartilha;
use ECidade\Tributario\Arrecadacao\Custas\Enum\TipoLancamento;

$oJson = new services_json();
$oParametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->sMessage = null;

try {
    switch ($oParametros->sExecucao) {
        case "getDadosInicialTaxa":
            $lDebitoPago = false;
            $lReciboEmitido = false;
            $aTaxas = array();

            $oInicialRepository = InicialRepository::getInstance();

            $lDebitoPago = $oInicialRepository->isDebitoPago($oParametros->iInicial);

            if ($lDebitoPago == false) {
                $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
                $oInicialPartilha = $oInicialPartilhaRepository->getUltimaByInicial($oParametros->iInicial);

                $oCollectionInicialPartilhaCustas = array();

                if (!empty($oInicialPartilha)) {
                    $iCodigoInicialPartilha = $oInicialPartilha->getCodigo();

                    $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();
                    $oCollectionInicialPartilhaCustas = $oInicialPartilhaCustasRepository->getByInicialPartilha($iCodigoInicialPartilha);
                }

                $oTaxaRepository = TaxaRepository::getInstance();
                $oCollectionTaxas = $oTaxaRepository->getTodasSemProcesso();

                foreach ($oCollectionTaxas as $oTaxa) {
                    $lDispensaLancamentoRecibo = false;

                    foreach ($oCollectionInicialPartilhaCustas as $oInicialPartilhaCustas) {
                        if ($oInicialPartilhaCustas->isDispensaLancamentoRecibo() == 'f' &&
                            $oInicialPartilhaCustas->getCodigoTaxa() == $oTaxa->getCodigoTaxa()
                        ) {
                            $lDispensaLancamentoRecibo = true;
                        }
                    }

                    $aTaxas[] = array(
                        "iCodigoTaxa" => $oTaxa->getCodigoTaxa(),
                        "sDescricao" => $oTaxa->getDescricao(),
                        "lChecked" => $lDispensaLancamentoRecibo
                    );
                }

                $lReciboEmitido = $oInicialRepository->isReciboEmitidoDebito($oParametros->iInicial);
            }

            $oDados = new stdClass();
            // @todo - feito na correira refazer
            $sJUstificativa = "";
            $sSql = "select v35_justificativa from inicialpartilha where v35_inicial = {$oParametros->iInicial}";
            $rsJUstificativa = db_query($sSql);
            if ($rsJUstificativa and pg_num_rows($rsJUstificativa) > 0) {
                $sJUstificativa = db_utils::fieldsMemory($rsJUstificativa,0)->v35_justificativa;
            }
            $oDados->aTaxas = $aTaxas;
            $oDados->sJustificativa = $sJUstificativa;
            $oDados->lDebitoPago = $lDebitoPago;
            $oDados->lReciboEmitido = $lReciboEmitido;

            $oRetorno->oDados = DBString::utf8_encode_all($oDados);

            break;

        case "processaInicialTaxaIsencao":

            $daoProcessoForoInicial = new cl_processoforoinicial();
            $sqlForoInicial = $daoProcessoForoInicial->sql_query_file(
                null,
                '*',
                null,
                "v71_inicial = {$oParametros->iInicial} and v71_anulado is false"
            );
            $buscaProcessoDoForo = db_query($sqlForoInicial);
            if (!$buscaProcessoDoForo) {
                throw new DBException("Ocorreu um erro ao consultar o processo do foro para a inicial.");
            }

            if (pg_num_rows($buscaProcessoDoForo) > 0) {
                throw new BusinessException("Inicial possui processo do foro.");
            }


            $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
            $oInicialPartilhaEntity = $oInicialPartilhaRepository->getInicialPartilhaIsencao($oParametros->iInicial);

            if (empty($oInicialPartilhaEntity)) {
                $oInicialPartilhaEntity = new InicialPartilhaEntity();
            } else {
                $oInicialPartilhaEntity = $oInicialPartilhaEntity[0];
            }
            $oInicialPartilhaEntity->setCodigoInicial((int)$oParametros->iInicial);

            $oInicialPartilhaEntity->setValorPartilha(0);
            $oInicialPartilhaEntity->setDataPartilha(new DateTime(date('Y-m-d', db_getsession('DB_datausu'))));
            $oInicialPartilhaEntity->setJustificativa($oParametros->sJustificativa);
            $lTipoLancamento = 1;

            $iInicialPartilhaEntityCodigo = $oInicialPartilhaEntity->getCodigo();

            $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();

            foreach ($oParametros->aTaxas as $aTaxa) {
                if (!empty($iInicialPartilhaEntityCodigo)) {
                    $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();

                    $oInicialPartilhaCustas = $oInicialPartilhaCustasRepository->getByCustaInicialPartilha(
                        (integer)$aTaxa->iTaxa,
                        $iInicialPartilhaEntityCodigo
                    );
                }

                if (empty($oInicialPartilhaCustas) || empty($iInicialPartilhaEntityCodigo)) {
                    $oInicialPartilhaCustas = new InicialPartilhaCustasEntity();
                }

                if ((boolean)$aTaxa->lIsencao) {
                    $oInicialPartilhaCustas->setCodigoTaxa((integer)$aTaxa->iTaxa);
                    $oInicialPartilhaCustas->setValor(0);
                    $oInicialPartilhaCustas->setDispensaLancamentoRecibo((boolean)$aTaxa->lIsencao);

                    $lTipoLancamento = 3;

                    $oInicialPartilhaEntity->addCustas($oInicialPartilhaCustas);

                } else {
                    if ($oInicialPartilhaCustas->getCodigo()) {
                        $oInicialPartilhaCustasRepository->delete($oInicialPartilhaCustas);
                    }
                }
            }

            if ($lTipoLancamento == 3) {
                $oInicialPartilhaEntity->setTipoLancamento($lTipoLancamento);
                $oInicialPartilhaRepository->persist($oInicialPartilhaEntity);
            } else {
                if (!empty($iInicialPartilhaEntityCodigo)) {
                    $oInicialPartilhaRepository->delete($oInicialPartilhaEntity);
                }
            }

            break;

        case 'buscarTaxasProcessuais':
            $taxaTepository = TaxaRepository::getInstance();
            $taxas = $taxaTepository->getTaxasProcessuais();
            $oRetorno->taxas = $taxaTepository->toArray($taxas);

            $oRetorno->observacaoIsencao = '';
            $oRetorno->observacaoPagamento = '';
            $oRetorno->dtpagamento = '';

            $statusLancamento = array(1 => '', 2 => 'Pago (Manual)', 3 => 'Isento', 4 => 'Pago Parcialmente');

            $partilhas = ProcessoForoPartilha::getInstance()->getPartilhaByProcessoSemRecibo($oParametros->processo);

            foreach ($oRetorno->taxas as $taxa) {
                $valorHonorario = array();

                $taxa->tipoLancamento = null;
                $taxa->status         = '';
                $taxa->idCusta        = null;
                $taxa->idPartilha     = null;
                $taxa->valorCusta     = null;
                
                if (empty($taxa->valor) && !empty($taxa->valorMinimo)) {
                    $taxa->valor = $taxa->valorMinimo;
                }

                $processoRepository = ProcessoForo::getInstance();
                $processoRepository->setReturnFullItem(true);
                $processo = $processoRepository->getByCodigo($oParametros->processo);

                $taxa->liberaCadastro = true;

                foreach ($partilhas as $partilha) {
                    foreach ($partilha->getCustas() as $custas) {
                        if ($custas->getCodigoTaxa() != $taxa->id) {
                            continue;
                        }

                        $taxa->idPartilha = $partilha->getCodigo();
                        $taxa->tipoLancamento = $partilha->getTipoLancamento();
                        $taxa->idCusta = $custas->getCodigo();
                        $taxa->valor = $custas->getValor();

                        $codigoTaxa = $custas->getCodigoTaxa();

                        if($taxa->aplicaHonorario){
                            $valorHonorario[$taxa->id] = $custas->getValor();
                        }

                        if ($partilha->getTipoLancamento() == 2) {
                            $oRetorno->observacaoPagamento = $partilha->getObservacao();
                            $oRetorno->dtpagamento = $partilha->getDataPagamento()->format('d/m/Y');
                        } else {
                            $oRetorno->observacaoIsencao = $partilha->getObservacao();
                        }

                        if ($taxa->aplicaHonorario && $processo->getParcelasHonorarios() > 1) {
                            continue;
                        }

                        $isencoes = ProcessoForoPartilha::getInstance()->getIsencaoByProcessoForoCodigo($oParametros->processo);
                        
                        foreach ($isencoes as $isencao) {
                            foreach ($isencao->getCustas() as $custas) {
                                if ($taxa->id == $custas->getCodigoTaxa()) {
                                    $taxa->status = $statusLancamento[TipoLancamento::ISENCAO];
                                    $taxa->tipoLancamento = $partilha->getTipoLancamento();
                                }
                            }
                        }
                    }
                }

                if($taxa->aplicaHonorario AND !empty($valorHonorario) AND $codigoTaxa == $taxa->id){
                     $taxa->valor = $valorHonorario[$codigoTaxa];
                }

                if ($taxa->aplicaHonorario && $processo->getParcelasHonorarios() > 1) {
                    $partilhaRepository = ProcessoForoPartilha::getInstance();
                    $taxaEntity = $taxaTepository->getByCodigo($taxa->id);
                    $parcelasPagas = $partilhaRepository->getParcelasPaga($taxaEntity, $processo);

                    if (count($parcelasPagas) == $processo->getParcelasHonorarios()) {
                        $taxa->status = $statusLancamento[1];
                    } else {
                        if (count($parcelasPagas) > 0) {
                            $taxa->status = $statusLancamento[4];
                        }
                    }

                    if ($partilhaRepository->hasPagamentoInicial($taxaEntity, $processo)) {
                        $taxa->status = $statusLancamento[1];
                    }
                }
            }
            break;

        /**
         * busca as ultimas taxas do processo
         * as pagas, isentas e sempre a ultima em aberto
         */
        case 'buscaTaxasProcesso':
            $campos = array(
                'v76_sequencial as partilha',
                'v76_tipolancamento',
                'v76_dtpagamento',
                'v76_datapartilha',
                'v76_obs',
                'v77_valor',
                'v77_taxa',
                'ar36_descricao as taxa',
            );

            $dao = new \cl_processoforopartilha();
            $sqlPartilhas = $dao->sql_partilhas_processo($oParametros->processo, implode(', ', $campos));
            $rs = db_query($sqlPartilhas);

            if (!$rs) {
                throw new \Exception("Erro ao buscar as custas e taxas judiciais.");
            }

            $partilhas = array();
            db_utils::makeCollectionFromRecord($rs, function($dado) use (&$partilhas) {
                if (!array_key_exists($dado->partilha, $partilhas)) {
                    $partilha = new stdClass();
                    $partilha->tipo = $dado->v76_tipolancamento;
                    $partilha->observacao = $dado->v76_obs;

                    $data = $dado->v76_dtpagamento;
                    if (empty($data)) {
                        $data = $dado->v76_datapartilha;
                    }
                    $partilha->data = db_formatar($data, 'd');

                    $partilha->status = retornaStatus($dado->v76_tipolancamento, $dado->v76_dtpagamento);
                    $partilha->taxas = array();
                    $partilhas[$dado->partilha] = $partilha;
                }

                $taxa = new stdClass();
                $taxa->id = $dado->v77_taxa;
                $taxa->descricao = $dado->taxa;
                $taxa->valor = db_formatar($dado->v77_valor, 'f');

                $partilhas[$dado->partilha]->taxas[] = $taxa;
            });

            $partilhas = array_values($partilhas);
            $oRetorno->partilhas = $partilhas;

            break;

        case 'removerCustaPartilha':
            if (empty($oParametros->partilha->idPartilha) || empty($oParametros->partilha->idCusta)) {
                throw new \Exception("Informe a custa que deseja remover da partilha.");
            }

            db_inicio_transacao();

            try {
                $dao = new \cl_processoforopartilhacusta();
                $rs = $dao->excluir($oParametros->partilha->idCusta);

                if (!$rs) {
                    $mensagem = "Erro ao apagar a custas da taxa {$oParametros->partilha->descricao} da partilha de ";
                    $mensagem .= "processo do foro {$oParametros->processo}.";
                    throw new \DBException($mensagem);
                }

                $sql = $dao->sql_query(
                    null,
                    '*',
                    null,
                    'v77_processoforopartilha = ' . $oParametros->partilha->idPartilha
                );

                $resource = db_query($sql);

                if (!$resource) {
                    throw new Exception('Erro ao consultar a quantidade de custas.');
                }

                if (pg_num_rows($resource) > 0) {
                    $sql  = " update processoforopartilha ";
                    $sql .= "    set v76_valorpartilha = (select sum(v77_valor) ";
                    $sql .= "                               from processoforopartilhacusta ";
                    $sql .= "                              where v77_processoforopartilha = v76_sequencial)";
                    $sql .= "  where v76_sequencial = {$oParametros->partilha->idPartilha}";
                } else {
                    $processoForoPartilha = new \cl_processoforopartilha();
                    $processoForoPartilha->excluir($oParametros->partilha->idPartilha);
                }

                $rs = db_query($sql);
                if (!$rs) {
                    throw new \Exception("Erro ao atualizar valor da partilha.");
                }
            } catch (\Exception $e) {
                db_fim_transacao(true);
                throw new Exception($e->getMessage());
            }
            db_fim_transacao();

            $oRetorno->sMensagem = "Custa removida da partilha.";
            break;
        case 'manutencaoCustasProcessuais':
            if (empty($oParametros->processo)) {
                throw new \Exception("Processo não informado.");
            }

            if (empty($oParametros->tipoLancamento)) {
                throw new Exception("Informe o tipo de lancamento.");
            }

            $tipo = $oParametros->tipoLancamento == 2 ? "pagas" : "isentas";

            db_inicio_transacao();
            try {
                $where = array(
                    "v76_processoforo = {$oParametros->processo}",
                    "v76_tipolancamento = {$oParametros->tipoLancamento}"
                );

                $where[2] = "exists (select 1 from processoforopartilhacusta where ";
                $where[2] .= "v77_processoforopartilha = v76_sequencial and v77_numnov = 0)";

                $dao = new cl_processoforopartilha();
                $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $where));
                $rs = db_query($sql);
                if (!$rs) {
                    throw new \Exception("Erro ao buscar partilha para o processo.");
                }

                $dao->v76_sequencial = null;
                $dao->v76_processoforo = $oParametros->processo;
                $dao->v76_tipolancamento = $oParametros->tipoLancamento;
                $dao->v76_dtpagamento = $oParametros->dtPagamento;
                $dao->v76_obs = $oParametros->observacao;
                $dao->v76_valorpartilha = 0;
                $dao->v76_datapartilha = date('Y-m-d');

                if (pg_num_rows($rs) > 0) {
                    $dadosPartilha = db_utils::fieldsMemory($rs, 0);
                    $dao->v76_sequencial = $dadosPartilha->v76_sequencial;

                    if (empty($oParametros->custas)) {
                        excluirCustasPartilha($dao->v76_sequencial);
                        $dao->excluir($dao->v76_sequencial);

                        if ($dao->erro_status == 0) {
                            throw new Exception('Erro ao excluir a partilha.');
                        }
                    }
                }

                if (!empty($oParametros->custas)) {

                    foreach ($oParametros->custas as $custa) {
                        $dao->v76_valorpartilha += $custa->valor;
                    }

                    $acao = "alterar";
                    if (empty($dao->v76_sequencial) && !empty($oParametros->custas)) {
                        $dao->incluir(null);
                        $acao = "incluir";
                    } else {
                        $dao->alterar($dao->v76_sequencial);
                    }

                    if ($dao->erro_status == 0) {
                        throw new Exception("Erro ao {$acao} partilha.");
                    }

                    vincularCustas($dao->v76_sequencial, $oParametros->custas);
                }

                $oRetorno->sMensagem = "Manutenção de Taxas/Custas do Processo realizada com sucesso!";
            } catch (\Exception $e) {
                db_fim_transacao(true);
                throw new \Exception($e->getMessage());
            }

            db_fim_transacao();
            break;
    }
} catch (Exception $oErro) {
    $oRetorno->erro = true;
    $oRetorno->sMensagem = $oErro->getMessage();
}

function excluirCustasPartilha($idPartilha)
{
    $dao = new cl_processoforopartilhacusta();
    $dao->excluir(null, "v77_processoforopartilha = {$idPartilha}");

    if ($dao->erro_status == 0) {
        throw new Exception("Erro ao excluir custas da partilha.");
    }
}

function vincularCustas($idPartilha, $custas)
{
    excluirCustasPartilha($idPartilha);

    $dao = new cl_processoforopartilhacusta();

    foreach ($custas as $custa) {

        $dao->v77_sequencial = null;
        $dao->v77_taxa = $custa->id;
        $dao->v77_processoforopartilha = $idPartilha;
        $dao->v77_valor = $custa->valor;
        $dao->v77_numnov = 0;
        $dao->v77_dispensalancamentorecibo = 'true';
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao atualizar custas da partilha.");
        }
    }
}

function retornaStatus($tipo, $dataPagamento)
{
    if ($tipo == 3 ) {
        return "Isento";
    }

    if ($tipo == 2) {
        return 'Pagamento Manual';
    }

    if ($tipo == 1 && empty($dataPagamento)) {
        return 'Recibo Emitido';
    }

    if ($tipo == 1 && !empty($dataPagamento)) {
        return 'Recibo Pago';
    }
}

echo JSON::create()->stringify($oRetorno);
