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

use ECidade\Financeiro\Orcamento\Repository\RecursoRepository;
use ILancamentoAuxiliar;

abstract class RecursoAbstract
{

    /**
     * Dados do recurso
     *
     * @param $recursoGera
     */
    protected function salvarRecurso($codigoLancamnento, $recurso, $recursosEspecificos = null)
    {

        if (!empty($recurso) || !empty($recursosEspecificos)) {
            $daoConlancamRecurso = new \cl_conlancamrecurso();
            $sqlLancamentos = "select * from conlancamval where c69_codlan = {$codigoLancamnento} order by c69_sequen";
            $rsLancamentos = db_query($sqlLancamentos);
            $codigoLancamento = $codigoLancamnento;
            \db_utils::makeCollectionFromRecord(
                $rsLancamentos,
                function ($lancamento) use ($recurso, $codigoLancamento, $daoConlancamRecurso, $recursosEspecificos) {

                    $contas = array("D" => $lancamento->c69_debito, "C" => $lancamento->c69_credito);
                    foreach ($contas as $sinal => $conta) {
                        $recursoConta = $recurso;
                        if (!empty($recursosEspecificos[$conta])) {
                            $recursoConta = $recursosEspecificos[$conta];
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
                            $msg = "Erro ao salvar dados do recurso do lançamento\n{$daoConlancamRecurso->erro_status}";
                            throw new \Exception($msg);
                        }
                    }
                }
            );
        }
    }


    abstract public function processar($codigoLancamnento, ILancamentoAuxiliar $lancamentoAuxiliar = null);
}
