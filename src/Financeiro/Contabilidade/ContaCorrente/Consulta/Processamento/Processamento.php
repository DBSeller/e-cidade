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

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Processamento;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\ContaCorrente;

/**
 * Class Processamento
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Processamento
 */
class Processamento
{


    /**
     * @var \DateTime
     */
    private $datainicial;
    /**
     * @var \DateTime
     */
    private $dataFinal;
    /**
     * @var ContaCorrente
     */
    private $contaCorrente;

    /**
     * filtros para pesquisar
     * @var null
     */
    private $filtros = null;

    /**
     * Colunas Visiveis no relatório
     * @var array
     */
    private $colunas = array();

    /**
     * filtros da consulta por atributos
     * @var array
     */
    private $filtroAtributos = array();


    /**
     * Consulta deve agrupar por documento contábil
     * @var bool
     */
    private $agruparPorDocumentoContabil = false;
    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * Processamento constructor.
     * @param \DateTime $datainicial
     * @param \DateTime $dataFinal
     * @param ContaCorrente|null $contaCorrente
     */
    public function __construct(
        $instituicao,
        \DateTime $datainicial,
        \DateTime $dataFinal,
        ContaCorrente $contaCorrente = null
    ) {
        $this->datainicial = $datainicial;
        $this->dataFinal = $dataFinal;
        $this->contaCorrente = $contaCorrente;

        $this->filtros = new \stdClass();
        $this->filtros->contas = array();
        $this->filtros->documentos = array();
        if (!is_array($instituicao)) {
            $instituicao = array($instituicao);
        }
        $this->instituicao = $instituicao;
    }


    /**
     * Monta os filtros dinamicos da consulta
     * @return string
     */
    private function montarFiltros()
    {

        $codigoInstituicoes = array_map(function ($instituicao) {
            return $instituicao->getCodigo();
        }, $this->instituicao);

        $where = array("c61_instit in(".implode(", ", $codigoInstituicoes).")");
        if (count($this->filtros->contas) > 0) {
            $where[] = "c61_reduz in (".implode(", ", $this->filtros->contas).")";
        }
        if (count($this->filtros->documentos) > 0) {
            $where[] = "c71_coddoc in (".implode(',', $this->filtros->documentos).")";
        }

        if (!empty($this->filtros->estrutural)) {
            $where[] = "c60_estrut like '{$this->filtros->estrutural}%'";
        }


        if (!empty($this->filtros->reduzido)) {
            $where[] = "c123_reduzido = {$this->filtros->reduzido} ";
        }


        if (!empty($this->filtros->atributos)) {
            $this->filtroAtributos = array();
            foreach ($this->filtros->atributos as $filtroAtributo) {
                $this->filtroAtributos[] = "(atributos ilike '%{$filtroAtributo->valor}#{$filtroAtributo->atributo}%')";
            }
        }

        if (!empty($this->filtros->conta_contabil_codigo)) {
            $where[] = "    c60_codcon = {$this->filtros->conta_contabil_codigo}
                        and c60_anousu = {$this->filtros->conta_contabil_ano}";
        }

        if (count($where) === 0) {
            return '';
        }

        return " and  ".implode(" and ", $where);
    }



   /**
     * Monta os dados da Consulta
     * @return string
     */
    public function getQueryMsc()
    {
        $campoDocumento = $this->agrupaPorDocumentoContabil() ? "documento," : "";
        $oFiltros = $this->getFiltros();
        $iConplano = $oFiltros->conta_contabil_codigo;
        $iReduzido = $oFiltros->reduzido;
        $iAno = $oFiltros->conta_contabil_ano;

        $aFiltros = array();
        if ($iConplano != null) {
            $aFiltros[] = "c120_conplano = {$iConplano}";
        }

        if ($iReduzido != null && count($oFiltros->contas) <= 0) {
            $aFiltros[] = "c123_reduzido = {$iReduzido}";
        }

        if (count($oFiltros->contas) > 0) {
            $sReduzidos = implode(", ", $oFiltros->contas);
            $aFiltros[] = "c123_reduzido in ($sReduzidos)";
        }

        if (count($oFiltros->documentos) > 0) {
            $sDoc = implode(", ", $oFiltros->documentos);
            $aFiltros[] = "c71_coddoc in ($sDoc)";
        }


        /**
         * precisamos saber as colunas que não estao nos filtros
         */
        $aColunas = $this->getColunas();
        $aAtributosFiltros = array();
        $aSiglas = array();
        $aColunasFiltrar = array();

        foreach ($oFiltros->atributos as $oFiltro) {
            $aAtributosFiltros[] = $oFiltro->atributo;
        }

        $aColunasFiltrar = array_diff($aColunas, $aAtributosFiltros);

        foreach ($aColunasFiltrar as $colunaSemFiltro) {
            $aSiglas[] = "'$colunaSemFiltro'";
        }

        $sSiglas = implode(", ", $aSiglas);

        if ($sSiglas != "") {
            $sFiltroColunas = " where ( sigla_atributo in ($sSiglas) ) ";
        }


        /**
         * logica para filtrar os atributos necessário pois na CC esta gravado o o15_codigo e o
         * usuario buscaria pelo o15_recurso
         */
        $sFiltroAtributos = "";

        if (count($oFiltros->atributos) > 0) {
            $aPares = array();
            foreach ($oFiltros->atributos as $oFiltro) {
                if (in_array($oFiltro->atributo, $aColunas)) {
                    $aPares[] = "(sigla_atributo = '{$oFiltro->atributo}' and valor_atributo = '{$oFiltro->valor}')";
                }
            }

            $sOr = "OR";
            if ($sFiltroColunas == "") {
                $sOr = " where";
            }

            if (count($aPares) > 0) {
                $sPares = implode(" or ", $aPares);
                $sFiltroAtributos = "$sOr {$sPares}";
            }
        }


        if ($iAno != null) {
            $aFiltros[] = "c120_anousu = {$iAno}";
        }
        $sFiltros = implode(" and ", $aFiltros);

        $FiltroMacro = array();
        $sFiltroMacro = "";
        if (count($oFiltros->atributos) > 0) {
            foreach ($oFiltros->atributos as $oAtributos) {
                $sFiltroAtributo = " atributos ilike '%{$oAtributos->valor}#{$oAtributos->atributo}%' ";
                $FiltroMacro[] = $sFiltroAtributo;
            }
            $sFiltroMacro = " where " . implode(" and ", $FiltroMacro);
        }

        $dataInicial = $this->datainicial->format('Y-m-d');
        $dataFinal   = $this->dataFinal->format('Y-m-d');
        $queryInicial = "with lancamentos as (

            select * from (

              select c124_sequencial as codigo,
                     c124_data as data,
                     c124_natureza as natureza,
                     c124_valor as valor,
                     c124_lancamento as codigo_lancamento,
                     c71_coddoc as documento,
                     c123_reduzido as reduzido,
                     c60_estrut as estrutural,
                     c60_descr as nome_conta,
                     case
                       when c121_sigla = 'FR'
                         then ( select o15_recurso from orctiporec where o15_codigo =  c123_valor::int)
                       else c123_valor
                     end as valor_atributo,
                     c121_sigla as sigla_atributo,
                     case
                       when c121_sigla = 'CF' then 1
                       when c121_sigla = 'FR' then 2
                       when c121_sigla = 'FS' then 3
                       when c121_sigla = 'PO' then 4
                       when c121_sigla = 'ND' then 5
                     end as ordenacao,
                     c129_ordem as ordem,
                     c124_tipo as tipo
              from conplanoinfocomplementar
              join conplanoatributos on c120_infocomplementar = c121_sequencial
              join infocomplementarvalor on c123_infocomplementar = c121_sequencial
              join conplanosistema on c123_conplanosistema = c122_sequencial
              left join conplanosistemaatributos on c129_conplanoinfocomplementar = c121_sequencial
              INNER JOIN conplanoatributolancamentos ON c124_sequencial = c123_conplanoatributolancamentos

                 INNER JOIN conplanoreduz ON c61_reduz = c123_reduzido
                                         AND EXTRACT (YEAR FROM c124_data)::int = c61_anousu

                 INNER JOIN conplano ON c61_codcon = c60_codcon
                                    AND c60_anousu = c61_anousu

                 LEFT JOIN conlancam ON c70_codlan = c124_lancamento
                 LEFT JOIN conlancamdoc ON c71_codlan = c70_codlan

              where c124_data between '{$dataInicial}' and '{$dataFinal}'
                and c123_conplanosistema = 1
                and {$sFiltros}

                order by c124_sequencial,
                         c71_coddoc,
                         c124_data,
                         c123_reduzido,
                         c129_ordem,
                         c124_lancamento,
                         c60_estrut
              ) as core
              $sFiltroColunas
              $sFiltroAtributos
              order by sigla_atributo

             ),

             conta_corrente as (

               select  codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural,
                       nome_conta,
                       array_to_string( array_agg( valor_atributo ||
                                                   '#' ||
                                                   sigla_atributo order by ordenacao), '|') as atributos,
                       tipo
                 from lancamentos
                 group by codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural,
                       nome_conta,
                       tipo

                 order by codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural

             )

             ";

              $queryFinal = " select estrutural,";
              $queryFinal .= "       {$campoDocumento} ";
              $queryFinal .= "       nome_conta, ";
              $queryFinal .= "       reduzido, ";
              $queryFinal .= "       array_to_string(array_accum(codigo_lancamento),',') as codigos_lancamentos, ";
              $queryFinal .= "       atributos, ";
              $queryFinal .= "       coalesce(sum(case when (data < '{$dataInicial}' or  tipo = '1')";
              $queryFinal .= "         and natureza = 'D' then valor";
              $queryFinal .= "               when (data < '{$dataInicial}' or tipo ='1') and natureza = 'C' then";
              $queryFinal .= "                  valor * -1 end), 0) as valor_movimentacao_anterior,";
              $queryFinal .= "       coalesce(sum(case when data >= '{$dataInicial}' and tipo = '2' and natureza = 'D'";
              $queryFinal .= "               then valor end ), 0) as valor_debito,";
              $queryFinal .= "       coalesce(sum(case when data >= '{$dataInicial}' and tipo = '2' and natureza = 'C'";
              $queryFinal .= "               then valor end ), 0) as valor_credito";
              $queryFinal .= "  from conta_corrente {$sFiltroMacro}";
              $queryFinal .= " group by estrutural,";
              $queryFinal .= "         {$campoDocumento}";
              $queryFinal .= "          atributos,";
              $queryFinal .= "          reduzido,";
              $queryFinal .= "          nome_conta";
              $queryFinal .= " order by estrutural";

              $query = $queryInicial."\n".$queryFinal;
        return $query;
    }



    /**
     * Monta os dados da Consulta
     * @return string
     */
    public function getQuery()
    {


        $filtros = $this->montarFiltros();
        $campoDocumento = $this->agrupaPorDocumentoContabil() ? "documento," : "";

        $dataInicial = $this->datainicial->format('Y-m-d');
        $dataFinal   = $this->dataFinal->format('Y-m-d');
        $queryInicial = "with lancamentos as (
                 select c124_sequencial as codigo,
                        c124_data as data,
                        c124_natureza as natureza,
                        c124_valor as valor,
                        c124_lancamento as codigo_lancamento,
                        c71_coddoc as documento,
                        c123_reduzido as reduzido,
                        c60_estrut as estrutural,
                        c60_descr as nome_conta,
                        c123_valor as valor_atributo,
                        c121_sigla as sigla_atributo,
                        c129_ordem as ordem,
                        c124_tipo as tipo
                   from infocomplementarvalor
                        inner join conplanoatributolancamentos on c124_sequencial = c123_conplanoatributolancamentos
                        inner join conplanosistemaatributos    on c129_conplanoinfocomplementar = c123_infocomplementar
                                                         and c129_conplanosistema = c123_conplanosistema
                         inner join conplanoinfocomplementar    on c121_sequencial = c123_infocomplementar
                         inner join conplanoreduz               on c61_reduz = c123_reduzido
                                                         and extract (year from c124_data)::int = c61_anousu
                         inner join conplano                   on c61_codcon = c60_codcon
                                                               and c60_anousu = c61_anousu
                         left  join conlancam    on c70_codlan   = c124_lancamento
                         left  join conlancamdoc on c71_codlan   = c70_codlan

             where c124_data <= '{$dataFinal}'
               and c123_conplanosistema = {$this->contaCorrente->getCodigo()} {$filtros}
               order by c124_sequencial, c71_coddoc, c124_data, c123_reduzido, c129_ordem, c124_lancamento, c60_estrut),

             conta_corrente as (

               select  codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural,
                       nome_conta,
                       array_to_string(array_agg(valor_atributo||'#'||sigla_atributo order by ordem), '|') as atributos,
                       tipo
                 from lancamentos
                 group by codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural,
                       nome_conta,
                       tipo

                 order by codigo,
                       data,
                       natureza,
                       valor,
                       codigo_lancamento,
                       {$campoDocumento}
                       reduzido,
                       estrutural

             )

             ";

              $where = '';
        if (count($this->filtroAtributos) > 0) {
            $where = " where ".implode(" and ", $this->filtroAtributos);
        }
              $queryFinal = " select estrutural,";
              $queryFinal .= "       {$campoDocumento} ";
              $queryFinal .= "       nome_conta, ";
              $queryFinal .= "       reduzido, ";
              $queryFinal .= "       array_to_string(array_accum(codigo_lancamento),',') as codigos_lancamentos, ";
              $queryFinal .= "       atributos, ";
              $queryFinal .= "       coalesce(sum(case when (data < '{$dataInicial}' or  tipo = '1') ";
              $queryFinal .= "             and natureza = 'D' then valor";
              $queryFinal .= "               when (data < '{$dataInicial}' or tipo ='1')";
              $queryFinal .= "             and natureza = 'C' then valor * -1 end), 0) as valor_movimentacao_anterior,";
              $queryFinal .= "       coalesce(sum(case when data >= '{$dataInicial}' and tipo = '2'";
              $queryFinal .= "             and natureza = 'D' then valor end ), 0) as valor_debito,";
              $queryFinal .= "       coalesce(sum(case when data >= '{$dataInicial}' and tipo = '2'";
              $queryFinal .= "             and natureza = 'C' then valor end ), 0) as valor_credito";
              $queryFinal .= "  from conta_corrente {$where}";
              $queryFinal .= " group by estrutural,";
              $queryFinal .= "         {$campoDocumento}";
              $queryFinal .= "          atributos,";
              $queryFinal .= "          reduzido,";
              $queryFinal .= "          nome_conta";
              $queryFinal .= " order by estrutural";

         $query = $queryInicial."\n".$queryFinal;

        return $query;
    }

    /**
     * Retorna os dados do relatorio
     * @return array
     */
    public function getDados()
    {
        $dados = $this->getQuery();
        $rsDados = db_query($dados);

        $totalLinhas = pg_num_rows($rsDados);
        $instancia = $this;
        $retorno = \db_utils::makeCollectionFromRecord($rsDados, function ($dados) use ($instancia) {

            $linha = $dados;
            $linha->saldo_anterior    = abs($dados->valor_movimentacao_anterior);
            $linha->natureza_anterior = $dados->valor_movimentacao_anterior > 0 ? "D" : "C";
            $saldoFinal = $dados->valor_movimentacao_anterior + ($dados->valor_debito - $dados->valor_credito);
            $linha->saldo_final = abs($saldoFinal);
            $linha->natureza_saldo_final = $saldoFinal > 0 ? 'D' : 'C';
            $linha->lista_atributos = $instancia->getListaDeAtributos($linha->atributos);
            return $linha;
        });
        return $retorno;
    }

    /**
     * Retorna os dados do relatorio
     * @return array
     */
    public function getDadosMsc()
    {

        $dados = $this->getQueryMsc();
        $rsDados = db_query($dados);

        $totalLinhas = pg_num_rows($rsDados);
        $instancia = $this;
        $retorno = \db_utils::makeCollectionFromRecord($rsDados, function ($dados) use ($instancia) {

            $linha = $dados;
            $linha->saldo_anterior    = abs($dados->valor_movimentacao_anterior);
            $linha->natureza_anterior = $dados->valor_movimentacao_anterior > 0 ? "D" : "C";
            $saldoFinal = $dados->valor_movimentacao_anterior + ($dados->valor_debito - $dados->valor_credito);
            $linha->saldo_final = abs($saldoFinal);
            $linha->natureza_saldo_final = $saldoFinal > 0 ? 'D' : 'C';
            $linha->lista_atributos = $instancia->getListaDeAtributos($linha->atributos);
            return $linha;
        });
        return $retorno;
    }




    /**
     * @return null
     */
    public function getFiltros()
    {
        return $this->filtros;
    }

    /**
     * @param null $filtros
     */
    public function setFiltros($filtros)
    {
        $this->filtros = $filtros;
    }

    /**
     * @return array
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    /**
     * @return bool
     */
    public function agrupaPorDocumentoContabil()
    {
        return $this->agruparPorDocumentoContabil;
    }

    /**
     * @param bool $agruparPorDocumentoContabil
     */
    public function setAgruparPorDocumentoContabil($agruparPorDocumentoContabil)
    {
        $this->agruparPorDocumentoContabil = $agruparPorDocumentoContabil;
    }



    /**
     * @param array $colunas
     */
    public function setColunas($colunas)
    {
        $this->colunas = $colunas;
    }


    /**
     * Retorna a lsta de atributos como um array
     * @param $listaAtributos
     * @return array
     */
    protected function getListaDeAtributos($listaAtributos)
    {

        $atributosRetorno = array();
        $atributos = explode("|", $listaAtributos);
        foreach ($atributos as $atributo) {
            $campos = explode("#", $atributo);
            $atributosRetorno[$campos[1]] = $campos[0];
        }

        return $atributosRetorno;
    }
}
