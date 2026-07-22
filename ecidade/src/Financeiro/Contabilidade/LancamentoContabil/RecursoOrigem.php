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
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil;

use ILancamentoAuxiliar;

/**
 * Class RecursoOrigem
 *
 * ESSA CLASSE É UTILIZADA QUANDO ATIVO O PARÂMETRO DOMICÍLIO BANCÁRIO
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class RecursoOrigem extends RecursoAbstract
{

    /**
     * Processa os dados
     * @param                          $codigoLancamnento
     * @param ILancamentoAuxiliar|null $lancamentoAuxiliar
     * @throws \ReflectionException
     */
    public function processar($codigoLancamnento, ILancamentoAuxiliar $lancamentoAuxiliar = null)
    {

        $daoConlancam = new \cl_conlancam();
        $sqlRecurso = $daoConlancam->sql_consulta_origem_recursos($codigoLancamnento);
        $rsRecursos = db_query($sqlRecurso);
        if (pg_num_rows($rsRecursos) == 0) {
            return;
        }

        $recurso = null;
        $dadosLancamento = \db_utils::fieldsMemory($rsRecursos, 0);

        if ($dadosLancamento->recurso_receita !== '') {
            $this->salvarRecurso($codigoLancamnento, $dadosLancamento->recurso_receita);
            return;
        }

        if ($dadosLancamento->recurso_resto != '') {
            $this->salvarRecurso($codigoLancamnento, $dadosLancamento->recurso_resto);
            return;
        }

        if ($dadosLancamento->recurso_empenho !== '') {
            $this->salvarRecurso($codigoLancamnento, $dadosLancamento->recurso_empenho);
            return;
        }

        if ($dadosLancamento->recurso_dotacao_lancamento != '') {
            $this->salvarRecurso($codigoLancamnento, $dadosLancamento->recurso_dotacao_lancamento);
            return;
        }

        /**
         * caso o slip seja uma transferencia bancária.
         */
        if (!empty($dadosLancamento->slip) && Documento::isTransferenciaDecendio($dadosLancamento->documento)) {
            $daoSlipRecursos = new \cl_sliprecursocontas();
            $sqlRecursos = $daoSlipRecursos->sql_query(
                null,
                "k17_credito as conta_credito, k181_recursocredito as recurso_credito,
                         k17_debito as conta_debito, k181_recursodebito as recurso_debito",
                null,
                "k17_codigo = {$dadosLancamento->slip}"
            );
            $rsRecursos = db_query($sqlRecursos);
            if (pg_num_rows($rsRecursos) == 0) {
                return;
            }
            $dadosRecurso = \db_utils::fieldsMemory($rsRecursos, 0);
            $recursoConta = array(
                $dadosRecurso->conta_credito => $dadosRecurso->recurso_credito,
                $dadosRecurso->conta_debito  => $dadosRecurso->recurso_debito
            );
            $this->recursosTransferenciasBancarias(
                $dadosLancamento,
                $recursoConta,
                $dadosRecurso->recurso_debito,
                $dadosRecurso->recurso_credito
            );

            return;
        }

        if (!empty($dadosLancamento->slip) &&
            (Documento::isPagamentoExtra($dadosLancamento->documento) ||
                Documento::isRecebimentoExtra($dadosLancamento->documento))) {
            $daoSlipRecursos = new \cl_sliprecursocontas();
            $sqlRecursos = $daoSlipRecursos->sql_query_plano(
                null,
                "k17_credito as conta_credito, k181_recursocredito as recurso_credito,
                         k17_debito as conta_debito, k181_recursodebito as recurso_debito,
                          reduzcredito.c61_codigo as recurso_conta_credito,
                          reduzdebito.c61_codigo as recurso_conta_debito",
                null,
                "k17_codigo = {$dadosLancamento->slip}"
            );
            $rsRecursos = db_query($sqlRecursos);
            if (pg_num_rows($rsRecursos) == 0) {
                return;
            }
            $dadosRecurso = \db_utils::fieldsMemory($rsRecursos, 0);
            $recurso = $dadosRecurso->recurso_conta_debito;
            if (Documento::isRecebimentoExtra($dadosLancamento->documento)) {
                $recurso = $dadosRecurso->recurso_conta_credito;
            }
            $this->salvarRecurso($codigoLancamnento, $recurso);
            return;
        }

        /**
         * Tratamento para o recurso extra extra orcamentario
         */
        if (!empty($dadosLancamento->recurso_extraorcamentario) &&
            in_array($dadosLancamento->documento, array(160, 162))) {
            $this->salvarRecurso($codigoLancamnento, $dadosLancamento->recurso_extraorcamentario);
            return;
        }

        if (!empty($lancamentoAuxiliar)) {
            $reflection = new \ReflectionClass($lancamentoAuxiliar);
            if ($reflection->hasMethod('getRecurso')) {
                $codigoRecurso = $lancamentoAuxiliar->getRecurso();
                if (!empty($codigoRecurso) && $codigoRecurso instanceof \Recurso) {
                    $recurso = $codigoRecurso->getCodigo();
                }
            }
            $this->salvarRecurso($codigoLancamnento, $recurso);
            return;
        }
    }

    /**
     *
     * Processa os lancamentos  de transferencia bancária e retorna o recurso da conta conforme a regra:
     * 1º -  lancamento  usa os recursos de debito e credito informados no slip
     * 2º -  lancamento  usa os recursos a credito informados no slip
     * 3º -  lancamento  usa os recursos a debito informados no slip
     * salva os recursos da conta
     *
     * @param $dadosLancamento
     * @param $recursos
     * @param $recursoDebito
     * @param $recursoCredito
     * @throws \Exception
     */
    protected function recursosTransferenciasBancarias($dadosLancamento, $recursos, $recursoDebito, $recursoCredito)
    {

        $codigoLancamento = $dadosLancamento->lancamento;
        $daoConlancamRecurso = new \cl_conlancamrecurso();
        $sqlLancamentos = "select * from conlancamval where c69_codlan = {$codigoLancamento} order by c69_sequen";
        $rsLancamentos = db_query($sqlLancamentos);
        $lancamentos = \db_utils::getCollectionByRecord($rsLancamentos);
        foreach ($lancamentos as $i => $lancamento) {
            $contas = array("D" => $lancamento->c69_debito, "C" => $lancamento->c69_credito);
            foreach ($contas as $sinal => $conta) {
                $recursoConta = $recursoDebito;
                /**
                 * Validamos o segundo lancamento
                 */
                if ($i + 1 == 3) {
                    $recursoConta = $recursoCredito;
                }
                /**
                 * Validamos o primeiro lancamento
                 */
                if (!empty($recursos[$conta])) {
                    $recursoConta = $recursos[$conta];
                }

                if (empty($recursoConta)) {
                    continue;
                }
                $daoConlancamRecurso->c130_orctiporec = $recursoConta;
                $daoConlancamRecurso->c130_anousu = $lancamento->c69_anousu;
                $daoConlancamRecurso->c130_conlancam = $lancamento->c69_codlan;
                $daoConlancamRecurso->c130_conta = $conta;
                $daoConlancamRecurso->c130_natureza = $sinal;
                $daoConlancamRecurso->c130_sequencial = null;
                $daoConlancamRecurso->incluir(null);
                if ($daoConlancamRecurso->erro_status == 0) {
                    throw new \Exception(sprintf(
                        "Erro ao salvar dados do recurso do lançamento\n %s",
                        $daoConlancamRecurso->erro_status
                    ));
                }
            }
        };
    }
}
