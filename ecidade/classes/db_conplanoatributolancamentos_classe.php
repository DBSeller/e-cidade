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

/**
 * Class cl_conplanoatributolancamentos
 *
 * @property integer c124_sequencial
 * @property integer c124_lancamento
 * @property string  c124_natureza
 * @property string  c124_tipo
 * @property float   c124_valor
 * @property string  c124_data
 * @property integer c124_conplanosistema
 */
class cl_conplanoatributolancamentos extends DAOBasica
{

    public function __construct()
    {

        parent::__construct("contabilidade.conplanoatributolancamentos");
    }

    /**
     * Retorna todos os lancamentos da matriz
     *
     * @todo modificar join com as tabelas conplanoatributos. Realizar o join com a tabela infocomplementarvalor com os campos novos.
     * @param $mes
     * @param $ano
     * @param $instituicoes
     * @return string
     */
    public function sql_query_lancamentos($mes, $ano, $instituicoes, $encerramento = null)
    {

        $ultimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $where = "";

        if ($encerramento === false) {
            $where = "   AND (c53_tipo <> 1000 or c53_tipo is null)";
        }

        if ($mes == 12 && $encerramento === true) {
            $where = ' and c53_tipo = 1000 ';
        }


        $sql = " SELECT distinct conplanoatributolancamentos.c124_sequencial AS sequencial, ";
        $sql .= "        conplano.c60_estrut AS estrutura, ";
        $sql .= "        pcasp.conta AS estrutura_pcasp, ";
        $sql .= "        conplano.c60_codcon AS conta, ";
        $sql .= "        conlancamval.c69_anousu AS anousu, ";
        $sql .= "        infocomplementarvalor.c123_valor AS valor, ";
        $sql .= "        infocomplementarvalor.c123_conplanosistema AS sistema, ";
        $sql .= "        conplanoinfocomplementar.c121_sequencial AS codigo_infocomplementar, ";
        $sql .= "        conplanoinfocomplementar.c121_sigla AS sigla, ";
        $sql .= "        conplanoinfocomplementar.c121_descricao AS descricao, ";
        $sql .= "        conplanoatributolancamentos.c124_lancamento AS codigo_lancamento, ";
        $sql .= "        conplanoatributolancamentos.c124_valor AS valor_lancamento, ";
        $sql .= "        conplanoatributolancamentos.c124_tipo AS tipo, ";
        $sql .= "        conplanoatributolancamentos.c124_natureza AS natureza, ";
        $sql .= "        conplanoatributolancamentos.c124_data AS data_lancamento, ";
        $sql .= "        infocomplementarvalor.c123_reduzido as conta_reduzida ";
        $sql .= "   FROM infocomplementarvalor ";
        $sql .= "   join conplanoreduz on c61_reduz = c123_reduzido and c61_anousu = {$ano}";
        $sql .= "   join conplano on c61_codcon = c60_codcon and c61_anousu = c60_anousu ";
        $sql .= "   join contabilidade.pcaspconplano on conplano_codigo = conplano.c60_codigo ";
        $sql .= "   join contabilidade.pcasp on pcasp.id = pcaspconplano.pcasp_id and pcasp.uniao is true";
        $sql .= "   JOIN conplanoinfocomplementar ON conplanoinfocomplementar.c121_sequencial = infocomplementarvalor.c123_infocomplementar ";
        $sql .= "   JOIN conplanoatributolancamentos ON conplanoatributolancamentos.c124_sequencial = infocomplementarvalor.c123_conplanoatributolancamentos ";
        $sql .= "   JOIN conlancamval ON conlancamval.c69_codlan = conplanoatributolancamentos.c124_lancamento ";
        $sql .= "   LEFT JOIN contabilidade.conlancamdoc ON c71_codlan = c69_codlan ";
        $sql .= "   LEFT JOIN contabilidade.conhistdoc ON c71_coddoc = c53_coddoc ";
        $sql .= "   JOIN conlancaminstit ON conlancaminstit.c02_codlan = conlancamval.c69_codlan ";
        $sql .= " WHERE conlancaminstit.c02_instit IN({$instituicoes}) ";
        $sql .= "   AND c69_data between '{$ano}-{$mes}-01' and '{$ano}-{$mes}-{$ultimoDiaMes}' and c124_conplanosistema = 1";
        $sql .= $where;

        $sql .= " union all ";

        $sql .= " SELECT DISTINCT conplanoatributolancamentos.c124_sequencial AS sequencial, ";
        $sql .= "        conplano.c60_estrut AS estrutura, ";
        $sql .= "        pcasp.conta AS estrutura_pcasp, ";
        $sql .= "        conplano.c60_codcon AS conta, ";
        $sql .= "        conplano.c60_anousu AS anousu, ";
        $sql .= "        infocomplementarvalor.c123_valor AS valor, ";
        $sql .= "        infocomplementarvalor.c123_conplanosistema AS sistema, ";
        $sql .= "        conplanoinfocomplementar.c121_sequencial AS codigo_infocomplementar, ";
        $sql .= "        conplanoinfocomplementar.c121_sigla AS sigla, ";
        $sql .= "        conplanoinfocomplementar.c121_descricao AS descricao, ";
        $sql .= "        conplanoatributolancamentos.c124_lancamento AS codigo_lancamento, ";
        $sql .= "        conplanoatributolancamentos.c124_valor AS valor_lancamento, ";
        $sql .= "        conplanoatributolancamentos.c124_tipo AS tipo, ";
        $sql .= "        conplanoatributolancamentos.c124_natureza AS natureza, ";
        $sql .= "        conplanoatributolancamentos.c124_data AS data_lancamento, ";
        $sql .= "        infocomplementarvalor.c123_reduzido as conta_reduzida ";
        $sql .= "   FROM infocomplementarvalor ";
        $sql .= "   join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial ";
        $sql .= "   LEFT JOIN contabilidade.conlancamdoc ON c71_codlan = c124_lancamento ";
        $sql .= "   LEFT JOIN contabilidade.conhistdoc ON c71_coddoc = c53_coddoc ";
        $sql .= "   join conplanoreduz on c61_reduz = c123_reduzido and c61_anousu = {$ano}";
        $sql .= "   join conplano on c61_codcon = c60_codcon and c61_anousu = c60_anousu ";
        $sql .= "   join contabilidade.pcaspconplano on conplano_codigo = conplano.c60_codigo ";
        $sql .= "   join contabilidade.pcasp on pcasp.id = pcaspconplano.pcasp_id and pcasp.uniao is true";
        $sql .= "   join conplanoinfocomplementar on c123_infocomplementar = c121_sequencial ";
        $sql .= " where c124_tipo = '1' ";
        $sql .= "   and extract(month from c124_data) = {$mes} ";
        $sql .= "   and extract(year from c124_data) = {$ano} ";
        $sql .= "   and c61_instit in ({$instituicoes}) ";
        $sql .= "   and c124_conplanosistema = 1";
        $sql .= $where;
        $sql .= " order by sistema, estrutura,sequencial,codigo_lancamento,codigo_infocomplementar ";

       // DEBUG com conta especifica codigo_infocomplementar = 3 eh o FR
       // $sql = "select * from ($sql) as dd where estrutura ilike '111111912%' and codigo_infocomplementar = 3  ";
       // $sql = "select * from ($sql) as dd where codigo_infocomplementar = 3  and valor = '0001' ";


        return $sql;
    }

    public function sql_query_delete_lancamentos_by_competencia($mes, $ano, $instituicoes)
    {

        $sql = " select distinct c124_sequencial AS sequencial from conplanoatributolancamentos ";
        $sql .= " inner join conlancamval    on c69_codlan = c124_lancamento ";
        $sql .= " inner join conlancaminstit on c02_codlan = c124_lancamento ";
        $sql .= " where extract(month from c69_data) = {$mes} ";
        $sql .= " and c69_anousu = {$ano} ";
        $sql .= " and c02_instit in ({$instituicoes}) ";

        return $sql;
    }

    /**
     * Remove os registos da matriz
     *
     * @todo - Remover join com a tabela conplanoatributos
     * @param       $mes
     * @param       $ano
     * @param array $instituicoes
     * @param array $tiposDocumentos
     * @return bool
     * @throws Exception
     */
    public function removerLancametosCompetencia(
        $mes,
        $ano,
        array $instituicoes,
        $SistemaContaCorrente = 1,
        array $listaLancamentos = null,
        array $tiposDocumentos = array()
    ) {

        $codigoInstituicoes = array();
        foreach ($instituicoes as $instituicao) {
            $codigoInstituicoes[] = $instituicao->getCodigo();
        }

        $whereListaLancamentos = '';
        if (is_array($listaLancamentos) && count($listaLancamentos) > 0) {
            $whereListaLancamentos = " and " . implode(",", $listaLancamentos);
        }

        if (!empty($SistemaContaCorrente)) {
            $whereListaLancamentos .= " and c123_conplanosistema = {$SistemaContaCorrente}";
        }

        $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $sDataInicial = "{$ano}-{$mes}-01";
        $sDataFinal = "{$ano}-{$mes}-{$iUltimoDiaMes}";

        $sqlInfo = " delete
                       from infocomplementarvalor
                      using conplanoatributolancamentos,
                            conlancaminstit,
                            conlancamdoc,
                            conhistdoc
                      where c123_conplanoatributolancamentos = c124_sequencial
                            {$whereListaLancamentos}
                        and c124_lancamento = c02_codlan
                        and conlancamdoc.c71_codlan = conplanoatributolancamentos.c124_lancamento
                        and conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                        and c124_data between '" . $sDataInicial . "' and '" . $sDataFinal . "'
                        and c02_instit in (" . implode(", ", $codigoInstituicoes) . ") ";

        $sqlInfo .= !empty($tiposDocumentos) ? " and conhistdoc.c53_tipo in (".implode(', ', $tiposDocumentos).") " : '';

        $rsInfo = db_query($sqlInfo);
        if (!$rsInfo) {
            throw new \Exception("Erro ao excluir as informações complementares dos lançamentos da competência: {$mes}/{$ano}.");
        }

        $sqlDesabilitaTriggers = "
            alter table conplanoatributolancamentos
            disable trigger all;
        ";

        $rsDesabilitaTriggers = db_query($sqlDesabilitaTriggers);

        if (!$rsDesabilitaTriggers) {
            throw new \Exception('Não foi possível desabilitar as triggers da tabela "conplanoatributolancamentos".');
        }

        $sqlAtributos = " delete
                             from conplanoatributolancamentos
                            using conlancaminstit,
                                  conlancamdoc,
                                  conhistdoc
                            where c124_data between '" . $sDataInicial . "' and '" . $sDataFinal . "'
                              and conlancamdoc.c71_codlan = conplanoatributolancamentos.c124_lancamento
                              and conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                              and c124_lancamento = c02_codlan ";

        $sqlAtributos .= !empty($tiposDocumentos) ? " and conhistdoc.c53_tipo in (".implode(', ', $tiposDocumentos).") " : '';

        if (!empty($SistemaContaCorrente)) {
            $sqlAtributos .= " and c124_conplanosistema = {$SistemaContaCorrente}";
        }

        $rsAtributos = db_query($sqlAtributos);
        if (!$rsAtributos) {
            throw new \Exception("Erro ao excluir os atributos dos lançamentos da competência: {$mes}/{$ano}.");
        }


        if ($SistemaContaCorrente == 1) {

            $mes = !empty($tiposDocumentos) ? 13 : $mes;

            $sqlSaldo = " delete from conplanoatributosaldo
                           where c125_anousu = {$ano}
                             and c125_mesusu = {$mes}
                             and c125_conplanosistema = {$SistemaContaCorrente} ";


            $rsSaldo = db_query($sqlSaldo);
            if (!$rsSaldo) {
                throw new \Exception("Erro ao excluir os saldos das contas para a competência: {$mes}/{$ano}.");
            }
            /**
             * Devemos remover todo o saldo inicial calculado para as institicuoes
             *
             * Selecionamos todos os registros de saldo e inicial e removemos os mesmos. devemos manter os registros apenas no processamento
             */
            $sqlRemoverSaldoInicialAno  = "create temp table w_apagar_valores_atributo as ";
            $sqlRemoverSaldoInicialAno .= "select distinct c124_sequencial ";
            $sqlRemoverSaldoInicialAno .= "  from infocomplementarvalor ";
            $sqlRemoverSaldoInicialAno .= "       inner join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial ";
            $sqlRemoverSaldoInicialAno .= " where c124_lancamento is null ";
            $sqlRemoverSaldoInicialAno .= "   and extract(year from c124_data) = {$ano} ";
            $sqlRemoverSaldoInicialAno .= "  and c124_tipo = '1' ";
            $sqlRemoverSaldoInicialAno .= "  and c124_conplanosistema = 1 ";

            $sqlRemoverSaldoInicialAno .= ";create index w_apagar_valores_atributo_sequencial_in on w_apagar_valores_atributo(c124_sequencial); ";
            $rsTabelaAuxiliar = db_query($sqlRemoverSaldoInicialAno);
            if (!$rsTabelaAuxiliar) {
                throw new \Exception("Erro ao gerar dados para remocao dos registros de saldo inicial do {$ano}.");
            }

            $sqlRemoverAtributosSaldoInicial  = "delete from infocomplementarvalor ";
            $sqlRemoverAtributosSaldoInicial .= " where c123_conplanoatributolancamentos in(select c124_sequencial from w_apagar_valores_atributo)";
            $rsRemoverAtributosSaldoInicial  = db_query($sqlRemoverAtributosSaldoInicial);
            if (!$rsRemoverAtributosSaldoInicial) {
                throw new \Exception("Erro ao remover dados de atributos gerados do saldo inicial do {$ano}.");
            }

            $sqlRemoverAtributosSaldoInicial  = "delete from conplanoatributolancamentos ";
            $sqlRemoverAtributosSaldoInicial .= " where c124_sequencial in(select c124_sequencial from w_apagar_valores_atributo)";
            $rsRemoverAtributosSaldoInicial  = db_query($sqlRemoverAtributosSaldoInicial);
            if (!$rsRemoverAtributosSaldoInicial) {
                throw new \Exception("Erro ao remover dados de lançamentos  gerados na MSC do saldo inicial do {$ano}.");
            }
        }

        $sqlHabilitaTriggers = "alter table conplanoatributolancamentos enable trigger all;";
        $rsHabilitaTriggers = db_query($sqlHabilitaTriggers);
        if (!$rsHabilitaTriggers) {
            throw new \Exception('Não foi possível habilitar as triggers da tabela "conplanoatributolancamentos".');
        }


        $rsHabilitaTriggers = db_query($sqlHabilitaTriggers);

        if (!$rsHabilitaTriggers) {
            throw new \Exception('Não foi possível habilitar as triggers da tabela "conplanoatributolancamentos".');
        }
        return true;
    }

    public function sql_query_contas_inconsistentes($ano)
    {

        $sSql = " SELECT COUNT(*) AS quantidade ";
        $sSql .= " FROM ";
        $sSql .= " ( SELECT c61_codcon AS codigo_conta ";
        $sSql .= " FROM conplanoreduz ";
        $sSql .= " WHERE c61_anousu = {$ano} ";
        $sSql .= " AND c61_reduz IN ";
        $sSql .= " ( SELECT DISTINCT c69_credito ";
        $sSql .= " FROM ";
        $sSql .= " (SELECT c69_credito ";
        $sSql .= "    FROM conlancamval ";
        $sSql .= "    WHERE c69_anousu = {$ano} ";
        $sSql .= "    UNION ALL SELECT c69_debito ";
        $sSql .= "    FROM conlancamval ";
        $sSql .= "    WHERE c69_anousu = {$ano} ) AS x ) ) AS y ";
        $sSql .= " WHERE codigo_conta NOT IN ";
        $sSql .= " (SELECT c120_conplano ";
        $sSql .= " FROM conplanoatributos ";
        $sSql .= " WHERE c120_anousu = {$ano}); ";

        return $sSql;
    }

    public function inserirSaldoContaAtributo($mes, $ano, $hashContaAtributos, $valor, $natureza, $tipo, $sistema = 1, $tipoSaldo)
    {

        $sql = " insert into conplanoatributosaldo (c125_sequencial, c125_anousu, c125_mesusu, c125_hashcontaatributos, c125_valor, c125_natureza, c125_tipo, c125_conplanosistema, c125_tiposaldo) ";
        $sql .= " values (nextval('conplanoatributosaldo_c125_sequencial_seq'), {$ano}, {$mes}, '{$hashContaAtributos}', {$valor}, '{$natureza}', {$tipo}, {$sistema}, {$tipoSaldo}) ";

        $rsSql = db_query($sql);
        if (!$rsSql) {
            throw new \Exception("Erro ao inserir o saldo para a conta e atributos {$hashContaAtributos} para a competência: {$mes}/{$ano}.");
        }

        return true;
    }

    public function sql_query_valores_conplanoexe($sCampos_1, $sCampos_2, $sWhere, $sWhere2)
    {

        $sql = " SELECT $sCampos_1 ";
        $sql .= "   FROM conplanoexe ";
        $sql .= "        INNER JOIN contabilidade.conplanoreduz ON c61_reduz = c62_reduz ";
        $sql .= "                                              AND c61_anousu = c62_anousu ";
        $sql .= "        INNER JOIN contabilidade.conplanoatributos ON c120_conplano = c61_codcon ";
        $sql .= "                                                  AND c120_anousu = c61_anousu ";
        $sql .= " INNER JOIN contabilidade.conplano ON c60_codcon = c120_conplano ";
        $sql .= " AND c60_anousu = c120_anousu ";

        if (!empty($sWhere)) {
            $sql .= " where {$sWhere} ";
        }

        $sql .= " union all ";
        $sql .= " SELECT $sCampos_2 ";
        $sql .= " FROM conplanoexe ";
        $sql .= " INNER JOIN contabilidade.conplanoreduz ON c61_reduz = c62_reduz ";
        $sql .= " AND c61_anousu = c62_anousu ";
        $sql .= " INNER JOIN contabilidade.conplanoatributos ON c120_conplano = c61_codcon ";
        $sql .= " AND c120_anousu = c61_anousu ";
        $sql .= " INNER JOIN contabilidade.conplano ON c60_codcon = c120_conplano ";
        $sql .= " AND c60_anousu = c120_anousu ";

        if (!empty($sWhere2)) {
            $sql .= " where {$sWhere2} ";
        }

        return $sql;

    }

}
