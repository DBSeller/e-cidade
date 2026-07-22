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

class cl_conlancaminfocomplementarvalor extends DAOBasica {


    const SICONF = 1;
    const CONTA_CORRENTE = 2;

    public function __construct()
    {
        parent::__construct("contabilidade.conlancaminfocomplementarvalor");
    }

    /**
     * @param $sCampos
     * @param $sWhere
     * @return string
     */
    public function sql_query_informacao_complementar_valor($sCampos, $sWhere)
    {
        $sql = " select {$sCampos} from conlancaminfocomplementarvalor inner join conplanoinfocomplementar on c126_infocomplementar = c121_sequencial ";

        if (!empty($sWhere)) {
            $sql .= " where {$sWhere} ";
        }

        return $sql;
    }


    /**
     * Ajusta os valores das informações complementares dos lançamentos caso haja configurações para eles
     * @param array $aCodigosLancamentos
     * @return bool
     * @throws Exception
     */
    public function ajustarValorInformacaoComplementar($aCodigosLancamentos)
    {
        if (empty($aCodigosLancamentos)) {
            return;
        }

        $codigosLancamentos = implode(",", $aCodigosLancamentos);

        $sql  = " UPDATE infocomplementarvalor a ";
        $sql .= " SET    c123_valor = c.c126_valor ";
        $sql .= " FROM   conplanoatributolancamentos  b ";
        $sql .= " JOIN   conlancaminfocomplementarvalor c ON c.c126_codlan = b.c124_lancamento ";
        $sql .= " WHERE  c.c126_codlan in ({$codigosLancamentos}) ";
        $sql .= "   AND c.c126_reduz = a.c123_reduzido ";
        $sql .= "   AND c.c126_infocomplementar = a.c123_infocomplementar ";
        $sql .= "   AND c.c126_tiposistema = a.c123_conplanosistema ";
        $sql .= "   AND a.c123_conplanoatributolancamentos = b.c124_sequencial ";

        $rsLancamentos = db_query($sql);
        if (!$rsLancamentos) {
            throw new \Exception("Erro ao ajustar o valor das informações complementares de acordo com a configurações do lançamento.");
        }

        return true;
    }

    /**
     * Altera valores das informações complementares que já existem na estrutura conlancaminfocomplementarvalor
     * @param $valor
     * @param $sWhere
     * @return bool
     * @throws DBException
     */
    public function alterarValorInfoComplementarPorCondicao($valor, $sWhere)
    {
        $sql = "update conlancaminfocomplementarvalor set c126_valor = '{$valor}' where {$sWhere}";
        $rs = db_query($sql);

        if (!$rs) {
            throw new \DBException("Erro ao alterar o valor da informação complementar.");
        }

        return true;
    }

    /**
     * Exclui os valores das informações complementares a partir do código do lançamento e reduzido da conta
     * @param int $iCodLancamento
     * @param int $iReduzidoConta
     * @throws Exception
     */
    public function excluirInformacaoComplementarLancamento($iCodLancamento, $iReduzidoConta)
    {
        $sql = "DELETE FROM conlancaminfocomplementarvalor WHERE c126_codlan = $iCodLancamento AND c126_reduz = $iReduzidoConta";

        $rsQueryResult = db_query($sql);

        if (!$rsQueryResult) {
            throw new \Exception("Erro ao excluir valores das informações complementares");
        }
    }

    /**
     * @param $ano
     * @param $codigoInstituicao
     */
    public function montarEstrutura($ano, $codigoInstituicao, $codigoContaCorrente, $tipoSistema=2, $unidadeGestora)
    {
        $whereConplanoSistemaAtributos     = "";
        $whereUnidadeGestora               = "";
        $innerJoinConplanoSistemaAtributos = "";
        $orderBy  = " c124_data,       ";
        $orderBy .= " c123_reduzido,   ";
        $orderBy .= " c124_lancamento, ";
        $orderBy .= " c121_sequencial  ";

        if ( $tipoSistema == self::CONTA_CORRENTE ) {

            $innerJoinConplanoSistemaAtributos  = " inner join conplanosistemaatributos on c129_conplanoinfocomplementar = c123_infocomplementar ";
            $innerJoinConplanoSistemaAtributos .= "                                    and c129_conplanosistema          = c123_conplanosistema ";
            $innerJoinConplanoSistemaAtributos .= " inner join conplanosistema          on c122_sequencial               = c129_conplanosistema ";
            $leftJoinUnidadeGestora             = " left join conlancamdepartamento     on conlancamdepartamento.c128_conlancam = c124_lancamento ";


            $whereConplanoSistemaAtributos      = " and c129_conplanosistema = {$codigoContaCorrente}";
            $whereConplanoSistemaAtributos     .= " and c122_tipo = 2";
            if ( ! empty($unidadeGestora) ) {
                $whereUnidadeGestora  = " and exists ( select 1 ";
                $whereUnidadeGestora .= "                from unidadegestoradepartamentos ugp ";
                $whereUnidadeGestora .= "               where ugp.k180_unidadegestora = {$unidadeGestora}  ";
                $whereUnidadeGestora .= "                 and ugp.k180_depart = conlancamdepartamento.c128_departamento ) ";
            }
            $orderBy  = " c124_data, ";
            $orderBy .= " c123_reduzido, ";
            $orderBy .= " c124_lancamento,";
            $orderBy .= " c129_ordem";
        }


        $sql  = " create temp table w_movimentacao_conta_corrente as ";
        $sql .= "   with movimentacao_conta_corrente as (            ";
        $sql .= " select c124_sequencial,                                                                      ";
        $sql .= "        c121_sequencial,                                                                      ";
        $sql .= "        c124_data,                                                                            ";
        $sql .= "        c123_reduzido,                                                                        ";
        $sql .= "        o15_codigo,                                                                           ";
        $sql .= "        o15_descr,                                                                            ";
        $sql .= "        c60_estrut,                                                                           ";
        $sql .= "        c60_descr,                                                                            ";
        $sql .= "        c124_lancamento,                                                                      ";
        $sql .= "        0 as c53_coddoc,                                                                      ";
        $sql .= "        ''::text as c53_descr,                                                                ";
        $sql .= "        c121_sigla || '#'::text || c123_valor AS atributo,                                    ";
        $sql .= "        c124_natureza,                                                                        ";
        $sql .= "        c129_ordem,                                                                           ";
        $sql .= "        c124_valor                                                                            ";
        $sql .= "   from contabilidade.infocomplementarvalor ";
        $sql .= "        inner join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial ";
        $sql .= "        inner join conplanoinfocomplementar    on c121_sequencial   = c123_infocomplementar          ";
        $sql .= "        {$innerJoinConplanoSistemaAtributos} ";
        $sql .= "        inner join conlancamdoc                on c71_codlan = c124_lancamento                ";
        $sql .= "        {$leftJoinUnidadeGestora} ";
        $sql .= "        inner join conhistdoc                  on c53_coddoc = c71_coddoc                     ";
        $sql .= "        inner join conplanoreduz               on c61_reduz  = c123_reduzido and c61_anousu = {$ano} ";
        $sql .= "        inner join orctiporec                  on o15_codigo = c61_codigo                            ";
        $sql .= "        inner join conplano                    on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
        $sql .= "   where c61_instit = {$codigoInstituicao} ";
        $sql .= "         {$whereConplanoSistemaAtributos} ";
        $sql .= "         {$whereUnidadeGestora} ";
        $sql .= " ), movimentos_ordenados as ( ";
        $sql .= "         select * ";
        $sql .= "         from movimentacao_conta_corrente ";
        $sql .= "    order by {$orderBy} ";
        $sql .= " ), agrupa_atributos_conta_correntes as ( ";
        $sql .= "  select c124_sequencial as id,                                            ";
        $sql .= "                  c124_data as data,                                       ";
        $sql .= "                  c123_reduzido as reduzido,                               ";
        $sql .= "                  o15_codigo as codigo_recurso,                            ";
        $sql .= "                  o15_descr as descricao_recurso,                          ";
        $sql .= "                  c60_estrut as estrutural,                                ";
        $sql .= "                  c60_descr as descricao_estrutural,                       ";
        $sql .= "                  c124_lancamento as lancamento,                           ";
        $sql .= "                  c53_coddoc as documento,                                 ";
        $sql .= "                  c53_descr as documento_descricao,                        ";
        $sql .= "                  array_to_string(array_accum(atributo), ',') as atributos,";
        $sql .= "                  c124_natureza as natureza,                               ";
        $sql .= "                  c124_valor as valor_lancamento                           ";
        $sql .= "   from movimentos_ordenados ";
        $sql .= "   group by c124_sequencial, ";
        $sql .= "            c124_data, ";
        $sql .= "            c123_reduzido, ";
        $sql .= "            o15_codigo, ";
        $sql .= "            c60_descr, ";
        $sql .= "            o15_descr, ";
        $sql .= "            c60_estrut, ";
        $sql .= "            c124_lancamento, ";
        $sql .= "            c53_coddoc, ";
        $sql .= "            c53_descr, ";
        $sql .= "            c124_natureza, ";
        $sql .= "            c124_valor ";
        $sql .= "            order by atributos, c124_data";
        $sql .= " ) ";

        $sql .= " select * from agrupa_atributos_conta_correntes ";
        db_query($sql);

        $sql  = " create index w_movimentacao_conta_corrente_data on  w_movimentacao_conta_corrente(data); ";
        $sql .= " create index w_movimentacao_conta_corrente_estrutural on  w_movimentacao_conta_corrente(estrutural); ";
        $sql .= " create index w_movimentacao_conta_corrente_reduzido on  w_movimentacao_conta_corrente(reduzido); ";
        $sql .= " create index w_movimentacao_conta_corrente_documento on  w_movimentacao_conta_corrente(documento); ";
        $sql .= " create index w_movimentacao_conta_corrente_atributos on  w_movimentacao_conta_corrente(atributos); ";

        db_query($sql);
    }

    /**
     * @param string $campos
     * @param string $where
     * @param $ano
     * @param $codigoInstituicao
     * @param bool $calculoSaldoAnterior
     * @return string
     */
    public function sqlQueryRazaoContaCorrente($campos = '*', $where = '')
    {
        $sql = "select {$campos} from w_movimentacao_conta_corrente ";
        if (!empty($where)) {
            $sql .= " where {$where}";
        }
        //db_criatabela(db_query($sql));
        //exit;
        return $sql;
    }

}

