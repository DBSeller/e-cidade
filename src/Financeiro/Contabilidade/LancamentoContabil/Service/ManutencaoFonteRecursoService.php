<?php

namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Service;

use cl_conlancamcomplementorecurso;
use cl_conlancamrecurso;
use cl_origemcomplementorecurso;
use cl_placaixarec;
use ECidade\Financeiro\Orcamento\Recurso\Origem;
use ECidade\Financeiro\Orcamento\Recurso\Recurso;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\ComplementoRecurso;
use Exception;

/**
 * Class ManutencaoFonteRecursoService
 */
class ManutencaoFonteRecursoService
{
    /**
     * @param $codigoEmpenho
     * @param $codigoRecurso
     * @throws Exception
     */
    public function atualizarRecursoEmpenho($codigoEmpenho, $codigoRecurso, $ano)
    {
        $recurso = new Recurso($codigoRecurso);

        $empenho = new \EmpenhoFinanceiro($codigoEmpenho);
        if ($empenho->isRP($ano)) {
            Origem::setEmpenhoRP($codigoEmpenho, $recurso->getCodigo(), $recurso->getComplemento());

            $update = "update empresto set e91_recurso = {$recurso->getCodigo()} where e91_numemp = {$codigoEmpenho}";
            db_query($update);
        } else {
            Origem::setEmpenho($codigoEmpenho, $recurso->getCodigo(), $recurso->getComplemento(), $ano);
        }

        $lancamentos = $this->getLancamentosEmpenho($codigoEmpenho, $ano);

        foreach ($lancamentos as $codigo) {
            $this->alterarLancamentoComplementoRecurso($codigo, $recurso->getComplemento(), $recurso->getCodigo());
            $this->alterarLancamentoRecurso($codigo, $recurso);
        }
    }

    /**
     * @param $lancamento
     * @param $codigoRecurso
     * @throws Exception
     */
    public function atualizarRecursoReceita($lancamento, $codigoRecurso)
    {
        $recurso = new Recurso($codigoRecurso);
        Origem::setLancamentoContabil($lancamento, $recurso->getCodigo(), $recurso->getComplemento());
        $this->alterarLancamentoComplementoRecurso($lancamento, $recurso->getComplemento(), $recurso->getCodigo());
        $this->alterarLancamentoRecurso($lancamento, $recurso);
        $this->alterarOrigemComplementoRecursoPlanilha($lancamento, $recurso);
    }

    /**
     * @param $codigoEmpenho
     * @return array
     * @throws Exception
     */
    private function getLancamentosEmpenho($codigoEmpenho, $ano)
    {
        $dao = new \cl_conlancamemp();
        $where = [
            "c75_numemp = {$codigoEmpenho} and c70_anousu >= {$ano}",
            "conlancam.c70_data > (select max(c99_data) from condataconf where c99_instit = c02_instit)"
        ];

        $rs = db_query($dao->sql_query_dados(null, 'c75_codlan', null, implode(' and ', $where)));

        if (!$rs) {
            throw new Exception("Erro ao buscar lançamentos do empenho.");
        }

        $lancamentos = [];
        while ($state = pg_fetch_array($rs)) {
            $lancamentos[] = $state['c75_codlan'];
        }

        return $lancamentos;
    }

    /**
     * @param integer $codigo
     * @param integer $codigoComplemento
     * @param integer $codigoRecurso
     * @throws Exception
     */
    private function alterarLancamentoComplementoRecurso($codigo, $codigoComplemento, $codigoRecurso)
    {
        $dao = new cl_conlancamcomplementorecurso();
        $where = "o201_codlan = {$codigo}";
        $rs = db_query($dao->sql_query_file(null, '*', null, $where));

        if (!$rs) {
            throw new Exception("Erro ao buscar Complementos.");
        }

        if (pg_num_rows($rs) === 0) {
            $dao = new cl_conlancamcomplementorecurso();
            $dao->o201_sequencial = null;
            $dao->o201_codlan = $codigo;
            $dao->o201_complemento = $codigoComplemento;
            $dao->o201_orctiporec = $codigoRecurso;
            $dao->incluir();
            if ($dao->erro_status == '0') {
                throw new Exception("Erro ao incluir o complemento de recurso do lançamento: {$codigo}.");
            }
        } else {
            while ($state = pg_fetch_array($rs)) {
                $dao = new cl_conlancamcomplementorecurso();
                $dao->o201_sequencial = $state['o201_sequencial'];
                $dao->o201_codlan = $codigo;
                $dao->o201_complemento = $codigoComplemento;
                $dao->o201_orctiporec = $codigoRecurso;
                $dao->alterar($state['o201_sequencial']);
                if ($dao->erro_status == '0') {
                    throw new Exception("Erro ao atualizar o complemento de recurso do lançamento: {$codigo}.");
                }
            }
        }
    }

    /**
     * @param $codigo
     * @param Recurso $recurso
     * @throws Exception
     */
    private function alterarLancamentoRecurso($codigo, Recurso $recurso)
    {
        $dao = new cl_conlancamrecurso();
        $rs = db_query($dao->sql_query_file(null, '*', null, "c130_conlancam = {$codigo}"));
        if (!$rs) {
            throw new Exception("Erro ao buscar recurso do lançamento.");
        }

        while ($state = pg_fetch_array($rs)) {
            $dao = new cl_conlancamrecurso();
            $dao->c130_sequencial = $state['c130_sequencial'];
            $dao->c130_conta = $state['c130_conta'];
            $dao->c130_anousu = $state['c130_anousu'];
            $dao->c130_natureza = $state['c130_natureza'];
            $dao->c130_conlancam = $codigo;
            $dao->c130_orctiporec = $recurso->getCodigo();
            $dao->alterar($state['c130_sequencial']);
            if ($dao->erro_status == '0') {
                throw new Exception("Erro ao atualizar o recurso do lançamento: {$codigo}.");
            }
        }
    }

    /**
     * @param $lancamento
     * @param Recurso $recurso
     * @throws Exception
     */
    private function alterarOrigemComplementoRecursoPlanilha($lancamento, Recurso $recurso)
    {
        $daoOrigemComplemento = new cl_origemcomplementorecurso();
        $daoPlanilha = new cl_placaixarec();

        $where = "conlancamcorrente.c86_conlancam = {$lancamento}";
        $sqlPlanilha = $daoPlanilha->sql_query_planilha_autenticadaOrigem("*", null, $where);
        $resPlanilha = db_query($sqlPlanilha);

        if (pg_num_rows($resPlanilha) > 0) {
            $oDadosComplemento = \db_utils::fieldsMemory($resPlanilha, 0);
            $iSequencialPlanilha = $oDadosComplemento->k81_seqpla;
            $iSequencialOrigem = $oDadosComplemento->o206_sequencial;
            $codigoRecurso = $recurso->getCodigo();
            $complementoNovo = $recurso->getComplemento();

            $daoOrigemComplemento->o206_sequencial = $iSequencialOrigem;
            $daoOrigemComplemento->o206_origem = 200;
            $daoOrigemComplemento->o206_numero = $iSequencialPlanilha;
            $daoOrigemComplemento->o206_recurso = $codigoRecurso;
            $daoOrigemComplemento->o206_complementorecurso = $complementoNovo;
            $daoOrigemComplemento->alterar($iSequencialOrigem);
            if ($daoOrigemComplemento->erro_status == "0") {
                throw new Exception("Erro ao atualizar o recurso na origem do lançamento: {$lancamento}.");
            }
        }
    }
}
