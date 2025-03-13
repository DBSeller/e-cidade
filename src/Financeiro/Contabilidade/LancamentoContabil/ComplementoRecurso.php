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

use ECidade\Financeiro\Orcamento\Recurso\Origem;
use Exception;

/**
 * Class Recurso
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class ComplementoRecurso
{
    /**
     * Verifica o documento do
     *
     * @param integer $codigoLancamento
     * @param integer $ano ano da sessao
     * @return bool
     * @throws Exception
     */
    public function processar($codigoLancamento, $ano)
    {
        $complemento = $this->getComplementoPorLancamento($codigoLancamento, $ano);
        /**
         * Adicionamos esse if pois receitas extras-orçamentária não salva conlancamrec
         * portanto não temos como obter o recurso da receita
         */
        if (empty($complemento) || $complemento->o15_codigo === '') {
            return true;
        }

        return $this->salvarComplementoRecurso($codigoLancamento, $complemento);
    }

    /**
     * @param $codigoLancamento
     * @param $ano
     * @return \stdClass|false
     * @throws Exception
     */
    public function getComplementoPorLancamento($codigoLancamento, $ano)
    {

        /**
         * Foi identificado que quando a receita possui desdobramento, devesse salvar o recurso da receita.
         */
        if ($this->isLancamentoReceitaComDesdobramento($codigoLancamento)) {
            return $this->retornaRecursoReceita($codigoLancamento);
        }

        $daoEmpenho = new \cl_conlancamemp();
        $sqlBuscaEmpenho = $daoEmpenho->sql_query_file($codigoLancamento);
        $resBuscaEmpenho = db_query($sqlBuscaEmpenho);

        if (pg_num_rows($resBuscaEmpenho) > 0) {
            $numeroEmpenho = \db_utils::fieldsMemory($resBuscaEmpenho, 0)->c75_numemp;
            return Origem::getEmpenho($numeroEmpenho, $ano);
        }

        /* receita */
        $where = implode(' and ', [
            "conlancamcorrente.c86_conlancam = {$codigoLancamento}"
        ]);
        $daoPlanilha = new \cl_placaixarec();
        $sqlPlanilha = $daoPlanilha->sql_query_planilha_autenticada("placaixarec.*", null, $where);
        $resPlanilha = db_query($sqlPlanilha);
        if (pg_num_rows($resPlanilha) > 0) {
            $numero = \db_utils::fieldsMemory($resPlanilha, 0)->k81_seqpla;
            return Origem::getPlanilhaArrecadacao($numero);
        }

        $where = implode(' and ', [
            "conlancamcorrente.c86_conlancam = {$codigoLancamento}",
            "taborc.k02_anousu = ".db_getsession('DB_anousu'),
            "orcreceita.o70_anousu = ".db_getsession('DB_anousu')
        ]);
        $daoRecibo = new \cl_cornump();
        $sqlRecibo = $daoRecibo->sql_query_recibo_autenticado("cornump.*, k02_complemento, o70_codigo", $where);
        $resRecibo = db_query($sqlRecibo);
        if (pg_num_rows($resRecibo) > 0) {
            $autenticacao = \db_utils::fieldsMemory($resRecibo, 0);
            $complemento = Origem::getRecibo($autenticacao->k12_numpre);

            if (!empty($complemento)) {
                return $complemento;
            }

            if (empty($autenticacao->k12_numpre)) {
                return $this->retornaRecursoReceita($codigoLancamento);
            } else {
                $complemento = Origem::getPadrao($autenticacao->k12_numpre);
                if (!$complemento) {
                    Origem::set(
                        Origem::COMPLEMENTO_PADRAO,
                        $autenticacao->k12_numpre,
                        $autenticacao->o70_codigo,
                        $autenticacao->k02_complemento
                    );
                    $complemento = Origem::getPadrao($autenticacao->k12_numpre);
                }

                return $complemento;
            }
        }

        /* proxima consulta aqui */

        return false;
    }


    /**
     * @param $codigoLancamento
     * @param $complemento
     * @return boolean
     * @throws Exception
     */
    protected function salvarComplementoRecurso($codigoLancamento, $complemento)
    {
        if (!empty($complemento)) {
            $daoRecursoComplemento = new \cl_conlancamcomplementorecurso();
            $daoRecursoComplemento->o201_sequencial = null;
            $daoRecursoComplemento->o201_codlan = $codigoLancamento;
            $daoRecursoComplemento->o201_complemento = $complemento->o200_sequencial;
            $daoRecursoComplemento->o201_orctiporec = $complemento->o15_codigo;
            $daoRecursoComplemento->incluir(null);
            if ($daoRecursoComplemento->erro_status === '0') {
                $msg  = "Erro ao salvar dados do complemento do recurso ";
                $msg .= "do lançamento.\n\n{$daoRecursoComplemento->erro_status} ... Lanc: {$codigoLancamento} " ;
                throw new \Exception($msg);
            }

            $oDaoOrigem = new \cl_origemcomplementorecurso();
            $sWhere = "o206_numero = {$codigoLancamento} and o206_origem = 2";
            $sSqlVerificaVinculo = $oDaoOrigem->sql_query_file(null, "*", null, $sWhere);
            $rs = $oDaoOrigem->sql_record($sSqlVerificaVinculo);
            if ($oDaoOrigem->numrows <= 0) {
                $oDaoOrigem->o206_origem = 2;
                $oDaoOrigem->o206_numero = $codigoLancamento;
                $oDaoOrigem->o206_recurso = $complemento->o15_codigo;
                $oDaoOrigem->o206_complementorecurso = $complemento->o200_sequencial;
                $oDaoOrigem->incluir(null);
                if ($oDaoOrigem->erro_status === '0') {
                    $msg  = "Erro ao salvar dados da origem do complemento do recurso ";
                    $msg .= "do lançamento.\n\n{$oDaoOrigem->erro_status}";
                    throw new \Exception($msg);
                }
            }
            return true;
        }
        return false;
    }

    private function isLancamentoReceitaComDesdobramento($codigoLancamento)
    {
        $dao = new \cl_conlancamrec();
        $sql = "
        select 1
          from conlancamrec
          join orcreceita on (o70_anousu, o70_codrec) = (c74_anousu, c74_codrec)
          join orcfontesdes on  (o60_codfon, o60_anousu) = (o70_codfon, o70_anousu)
         where conlancamrec.c74_codlan = {$codigoLancamento}
        ";

        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $codigoLancamento
     * @return \_db_fields|\stdClass
     * @throws Exception
     */
    private function retornaRecursoReceita($codigoLancamento)
    {
        $sql = "
            select o15_codigo, o200_sequencial, o15_recurso, o15_descr, o200_descricao
                from conlancamrec
                join orcreceita on (o70_anousu, o70_codrec) = (c74_anousu, c74_codrec)
                join orctiporec on orctiporec.o15_codigo = orcreceita.o70_codigo
                join complementofonterecurso on o200_sequencial = o15_complemento
             where c74_codlan = {$codigoLancamento}
            ";
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception('Erro ao buscar recurso da receita.');
        }

        return \db_utils::fieldsMemory($rs, 0);
    }
}
