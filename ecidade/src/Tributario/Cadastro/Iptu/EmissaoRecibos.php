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

namespace ECidade\Tributario\Cadastro\Iptu;

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\Repository as RegistroRepository;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\Factory as RegistroFactory;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\Padrao as RegistroPadrao;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\ParcelaUnica;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\ParcelaUnicaRepository;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\Repository as EmissaoGeralRepository;
use ECidade\Tributario\Arrecadacao\Model\TaxaEspecifica as TaxaEspecificaModel;
use ECidade\Tributario\Caixa\Repository\RecibopagaRepository;
use ECidade\Tributario\Library\DataBase;

class EmissaoRecibos
{
    public static function prepareQuery()
    {
        /**
         * $sqlnaogera = $sSqlNaoGera
         * @param $1 = $j34_setor   text
         * @param $2 = $j34_quadra  text
         */
        $sSqlNaoGera = ' select *                                                                      ';
        $sSqlNaoGera .= '   from iptunaogeracarne                                                       ';
        $sSqlNaoGera .= '        inner join iptunaogeracarnesetqua on j66_sequencial = j67_naogeracarne ';
        $sSqlNaoGera .= '  where j67_setor  = $1                                                        ';
        $sSqlNaoGera .= '    and j67_quadra = $2                                                        ';
        \db_query("PREPARE sSqlNaoGera (text, text) AS {$sSqlNaoGera}");

        /**
         * $sqlnaogeracgm = $sSqlNaoGeraCgm
         * @param $1 = $z01_cgmpri integer
         */
        $sSqlNaoGeraCgm = ' select *                                                                   ';
        $sSqlNaoGeraCgm .= '   from iptunaogeracarne                                                    ';
        $sSqlNaoGeraCgm .= '        inner join iptunaogeracarnecgm on j66_sequencial = j68_naogeracarne ';
        $sSqlNaoGeraCgm .= '  where j68_numcgm = $1                                                     ';
        \db_query("PREPARE sSqlNaoGeraCgm (integer) AS {$sSqlNaoGeraCgm}");

        /**
         * $sqlnaogeramatric = $sSqlNaoGeraMatric
         * @param $1 = $j23_matric  integer
         */
        $sSqlNaoGeraMatric = 'select *                                                                       ';
        $sSqlNaoGeraMatric .= '  from iptunaogeracarne                                                        ';
        $sSqlNaoGeraMatric .= '       inner join iptunaogeracarnematric on j66_sequencial = j131_naogeracarne ';
        $sSqlNaoGeraMatric .= ' where j131_matric = $1                                                        ';
        \db_query("PREPARE sSqlNaoGeraMatric (integer) AS {$sSqlNaoGeraMatric}");

        /**
         * $sqvalorMax = $sSqlValorMax
         * @param $1 = $j20_numpre  integer
         */
        $sSqlValorMax = ' select sum(k00_valor)  as viptu,        ';
        $sSqlValorMax .= '        max(k00_numpar) as parcelamaxima ';
        $sSqlValorMax .= '   from arrecad                          ';
        $sSqlValorMax .= '  where k00_numpre = $1                  ';
        \db_query("PREPARE sSqlValorMax (integer) AS {$sSqlValorMax}");

        /**
         * $sqlarrecad = $sSqlArrecad
         * @param $1 = $j20_numpre  integer
         */
        $sSqlArrecad = ' select k00_tipo  , a.k00_numpre,                           ';
        $sSqlArrecad .= '        k00_numpar, k00_numtot,                             ';
        $sSqlArrecad .= '        k00_numdig, k00_dtvenc,                             ';
        $sSqlArrecad .= '        sum(k00_valor)::float8 as k00_valor                 ';
        $sSqlArrecad .= '   from arrematric m                                        ';
        $sSqlArrecad .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre ';
        $sSqlArrecad .= '  where m.k00_numpre = $1                                   ';
        $sSqlArrecad .= '  group by a.k00_numpre, k00_numpar,                        ';
        $sSqlArrecad .= '           k00_numtot, k00_numdig,                          ';
        $sSqlArrecad .= '           k00_dtvenc, k00_tipo                             ';
        $sSqlArrecad .= '  order by k00_numpar                                       ';
        \db_query("PREPARE sSqlArrecad (integer) AS {$sSqlArrecad}");

        /**
         * $sql = $sSqlArretipo
         * @param $1 = $k00_tipo  integer
         */
        $sSqlArreTipoTxBanco = ' select k00_tipo, k00_codbco, k00_codage, k00_descr, ';
        $sSqlArreTipoTxBanco .= '        k00_hist1, k00_hist2, k00_hist3,  k00_hist4, ';
        $sSqlArreTipoTxBanco .= '        k00_hist5, k00_hist6, k00_hist7, k00_hist8,  ';
        $sSqlArreTipoTxBanco .= '        k03_tipo, k00_txban as tx_banc               ';
        $sSqlArreTipoTxBanco .= '   from arretipo                                     ';
        $sSqlArreTipoTxBanco .= '  where k00_tipo = $1                                ';
        \db_query("PREPARE sSqlArreTipoTxBanco (integer) AS {$sSqlArreTipoTxBanco}");

        /**
         * $sqlfin2 = $sSqlFin02
         * @param $1 = $j20_numpre  integer
         */
        $sSqlFin02 = 'select k00_tipo, k00_dtvenc,       ';
        $sSqlFin02 .= '       k00_numpre, k00_numpar,     ';
        $sSqlFin02 .= '       sum(k00_valor) as k00_valor ';
        $sSqlFin02 .= '  from arrecad                     ';
        $sSqlFin02 .= ' where k00_numpre = $1             ';
        $sSqlFin02 .= ' group by k00_dtvenc, k00_numpre,  ';
        $sSqlFin02 .= '          k00_numpar, k00_tipo     ';
        $sSqlFin02 .= ' order by k00_numpre, k00_numpar   ';
        \db_query("PREPARE sSqlFin02 (integer) AS {$sSqlFin02}");

        /**
         * $sqlfinpripaga = $sSqlFinPriPagaComPgto
         * @param $1 = $j20_numpre                                 integer
         * @param $2 = date("Y-m-d", db_getsession("DB_datausu"))  date
         */
        $sSqlFinPriPagaComPgto = ' select distinct a.k00_numpar                               ';
        $sSqlFinPriPagaComPgto .= '   from arrematric m                                        ';
        $sSqlFinPriPagaComPgto .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre ';
        $sSqlFinPriPagaComPgto .= '  where m.k00_numpre = $1                                   ';
        $sSqlFinPriPagaComPgto .= '    and a.k00_dtvenc < $2                                   ';
        \db_query("PREPARE sSqlFinPriPagaComPgto (integer, date) AS {$sSqlFinPriPagaComPgto}");

        /**
         * $sqlfinpripaga = $sSqlFinPriPagaSemPgto
         * @param $1 = $j20_numpre  integer
         */
        $sSqlFinPriPagaSemPgto = ' select *                                                    ';
        $sSqlFinPriPagaSemPgto .= '   from arrematric m                                         ';
        $sSqlFinPriPagaSemPgto .= '        inner join arrepaga a on m.k00_numpre = a.k00_numpre ';
        $sSqlFinPriPagaSemPgto .= '        inner join arrecant t on m.k00_numpre = t.k00_numpre ';
        $sSqlFinPriPagaSemPgto .= '  where m.k00_numpre = $1                                    ';
        $sSqlFinPriPagaSemPgto .= '  limit 1                                                    ';
        \db_query("PREPARE sSqlFinPriPagaSemPgto (integer) AS {$sSqlFinPriPagaSemPgto}");

        /**
         * $sqlfinvcto = $sSqlFinVencimento
         * @param $1 = $j20_numpre   integer
         */
        $sSqlFinVencimento = ' select min(k00_dtvenc) as vctorecibo                       ';
        $sSqlFinVencimento .= '   from arrematric m                                        ';
        $sSqlFinVencimento .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre ';
        $sSqlFinVencimento .= '  where m.k00_numpre = $1                                   ';
        $sSqlFinVencimento .= '    and a.k00_dtvenc > current_date                         ';
        \db_query("PREPARE sSqlFinVencimento (integer) AS {$sSqlFinVencimento}");

        /**
         * $sqlfinrecibo = $sSqlFinRecibo
         * @param $1 = $j20_numpre  integer
         */
        $sSqlFinRecibo = ' select distinct                                            ';
        $sSqlFinRecibo .= '        a.k00_numpre, a.k00_numpar, k00_dtvenc              ';
        $sSqlFinRecibo .= '   from arrematric m                                        ';
        $sSqlFinRecibo .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre ';
        $sSqlFinRecibo .= '  where m.k00_numpre = $1                                   ';
        $sSqlFinRecibo .= '    and a.k00_dtvenc < current_date                         ';
        \db_query("PREPARE sSqlFinRecibo (integer) AS {$sSqlFinRecibo}");

        /**
         * $sql = $sSqlRecibo
         * @param $1 = $k03_numpre  integer
         */
        $sSqlRecibo = ' select sum(k00_valor) as valorrecibo   ';
        $sSqlRecibo .= '   from recibopaga                      ';
        $sSqlRecibo .= '  where k00_numnov = $1                 ';
        \db_query("PREPARE sSqlRecibo (integer) AS {$sSqlRecibo}");

        $sSqlMovimentos = " select case when pagamentos > 0 then 'SIM' else ";
        $sSqlMovimentos .= "        case when iptunump_anoant = 0 and isencao_anoant = 0 and debitos = 0 ";
        $sSqlMovimentos .= "             then 'SIM' else ";
        $sSqlMovimentos .= "        case when pagamentos = 0 and isencao_anoant = 0 ";
        $sSqlMovimentos .= "             then 'NAO' else 'NAO' end end end as situacao ";
        $sSqlMovimentos .= "   from ( ";
        $sSqlMovimentos .= "    select j20_matric, ";
        $sSqlMovimentos .= "           j20_numpre, ";

        $sSqlMovimentos .= "           coalesce((select case when arrepaga.k00_numpre is null  ";
        $sSqlMovimentos .= "                                 then 0 ";
        $sSqlMovimentos .= "                                 else 1 end ";
        $sSqlMovimentos .= "                       from arrepaga ";
        $sSqlMovimentos .= "                            inner join arrematric a on a.k00_numpre = arrepaga.k00_numpre ";
        $sSqlMovimentos .= "                      where a.k00_matric = iptunump.j20_matric ";
        $sSqlMovimentos .= "                        and extract (year from k00_dtpaga) >= $1 limit 1) ";
        $sSqlMovimentos .= "                   ,0) as pagamentos, ";

        $sSqlMovimentos .= "           coalesce((select case when arrecad.k00_numpre is null then 0 else 1 end ";
        $sSqlMovimentos .= "                       from arrecad ";
        $sSqlMovimentos .= "                            inner join arrematric a on a.k00_numpre = arrecad.k00_numpre ";
        $sSqlMovimentos .= "                      where a.k00_matric = iptunump.j20_matric ";
        $sSqlMovimentos .= "                        and extract (year from k00_dtvenc) between $2 and $3 limit 1)";
        $sSqlMovimentos .= "                   ,0) as debitos, ";

        $sSqlMovimentos .= "           coalesce((select case when iptunump_anoant.j20_numpre is null then 0 else 1 end";
        $sSqlMovimentos .= "                       from iptunump iptunump_anoant ";
        $sSqlMovimentos .= "                      where iptunump_anoant.j20_matric = iptunump.j20_matric ";
        $sSqlMovimentos .= "                        and iptunump_anoant.j20_anousu = $4 limit 1)";
        $sSqlMovimentos .= "                   ,0) as iptunump_anoant, ";

        $sSqlMovimentos .= "           coalesce(( select case when j46_codigo is null then 0 else 1 end ";
        $sSqlMovimentos .= "                        from iptuisen ";
        $sSqlMovimentos .= "                             inner join isenexe on j47_codigo = j46_codigo ";
        $sSqlMovimentos .= "                       where j46_matric = j20_matric and j47_anousu = $5 limit 1)";
        $sSqlMovimentos .= "                   ,0) as isencao_anoant ";

        $sSqlMovimentos .= "      from iptunump ";
        $sSqlMovimentos .= "     where j20_anousu = $6 and j20_matric = $7 ";
        $sSqlMovimentos .= "     order by j20_matric desc ";
        $sSqlMovimentos .= "   ) as x ";
        $sSqlMovimentos .= "   order by j20_matric ";
        $prepare = "PREPARE sSqlMovimentos(integer, integer, integer, integer, integer, integer, integer)";
        $prepare .= "    AS {$sSqlMovimentos}";
        \db_query($prepare);
    }

    /**
     * @param boolean $lProcessaMassaFalida - Se deve processar as matriculas que est\E3o cadastradas como massa falida
     * @param boolean $lSomenteEnderecoEntregaValido
     * @param boolean $lGerarComCidadeEmBranco
     * @param array $aParcelasObrigatorias
     * @param integer $iMaximoParcelasParaGerar - O M\E1ximo de parcelas para poder gerar, incluiindo a parcela unica
     * @param string $listamatrics - Lista de matriculas para gera\E7\E3o
     * @param string $sParcelaUnica - Lista de parcelas unicas utilizadas na gera\E7\E3o
     * @param integer $anousu - Ano para o qual deseja realizar a emiss\E3o
     * @param integer $quantidade - Quantidade de matriculas na query
     * @param integer $iAnosConsiderarMovimento
     * @param string $ordem - Ordem que deve ser gerado os recibos
     * @param string $especie - Tipos de im\F3veis
     * @param string $imobiliaria - V\EDnculo com a imobili\E1ria
     * @param string $loteamento - V\EDnculo com loteamentos
     * @param string $isencao - Tratamento de isencao parcial para emissao
     * @param string $filtroprinc - Filtro principal da consulta
     * @param string $barrasparc
     * @param string $barrasunica
     * @param float $vlrminunica
     * @param string $intervalo
     * @param float $vlrmaxunica
     * @param float $vlrmin
     * @param float $vlrmax
     * @param integer $matriculaInicial
     * @param integer $matriculaFinal
     * @param callback $callbackAtualiza - Callback a ser chamado para atualizar o andamento da rotina
     * @return array
     * @throws \BusinessException
     * @throws \ParameterException
     */
    public static function emitir(
        $lProcessaMassaFalida,
        $lSomenteEnderecoEntregaValido,
        $lGerarComCidadeEmBranco,
        $aParcelasObrigatorias,
        $iMaximoParcelasParaGerar,
        $listamatrics,
        $sParcelaUnica,
        $anousu,
        $quantidade,
        $iAnosConsiderarMovimento,
        $ordem,
        $especie,
        $imobiliaria,
        $loteamento,
        $isencao,
        $filtroprinc,
        $barrasparc,
        $barrasunica,
        $vlrminunica,
        $intervalo,
        $vlrmaxunica,
        $vlrmin,
        $vlrmax,
        $matriculaInicial,
        $matriculaFinal,
        $callbackAtualiza,
        $emissaoGeralPadrao,
        $expressaoIsento
    ) {

        $cliptucalc = new \cl_iptucalc();
        $cliptunump = new \cl_iptunump();
        $clmassamat = new \cl_massamat();

        /**
         * Define os valores padr\E3o dos parametros
         */
        $vlrminunica = !empty($vlrminunica) ? $vlrminunica : 0;
        $vlrmaxunica = !empty($vlrmaxunica) ? $vlrmaxunica : 999999999;
        $vlrmin = !empty($vlrmin) ? $vlrmin : 0;
        $vlrmax = !empty($vlrmax) ? $vlrmax : 999999999;

        $iAnosConsiderarMovimento = (int)$iAnosConsiderarMovimento;

        static::prepareQuery();

        $rsPref = \db_query("select munic, cep, uf, db21_codcli from db_config where prefeitura is true");
        extract((array)\db_utils::fieldsMemory($rsPref, 0));

        $whereimobil = "";
        if ($imobiliaria == "com") {
            $whereimobil = " and j44_matric is not null ";
        } elseif ($imobiliaria == "sem") {
            $whereimobil = " and j44_matric is null ";
        }

        $wherelistamatrics = "";
        if ($listamatrics == "") {
            $wherelistamatrics = " ";
        } else {
            $wherelistamatrics = " and iptucalc.j23_matric in ($listamatrics) ";
            $quantidade = "";
        }

        if ($matriculaInicial != "" && $matriculaFinal != "" && $matriculaInicial <= $matriculaFinal) {
            $wherelistamatrics = "";
            $wherelistamatrics = " and iptucalc.j23_matric between $matriculaInicial and $matriculaFinal ";
            $quantidade = "";
        }

        $whereloteam = "";
        if ($loteamento == "com") {
            $whereloteam = " and loteloteam.j34_idbql is not null ";
        } elseif ($loteamento == "sem") {
            $whereloteam = " and loteloteam.j34_idbql is null ";
        }

        $whereisencao = "";
        if ($isencao == "com") {
            $whereisencao  = " and iptucalc.j23_matric in ";
            $whereisencao .= " (select distinct j21_matric
                                  from iptucalv
                inner join iptunump on j20_anousu = j21_anousu and
                                       j20_matric = j21_matric
                inner join arrecad  on j20_numpre = k00_numpre
                                 where j21_codhis in (select distinct j89_codhis
                                                        from iptucalhconf)
                                                         and j21_anousu = $anousu) ";
        } elseif ($isencao == "sem") {
            $whereisencao  = " and iptucalc.j23_matric in ";
            $whereisencao .= " (select distinct j21_matric
                                  from iptucalv
                inner join iptunump on j20_anousu = j21_anousu and
                                       j20_matric = j21_matric
                inner join arrecad  on j20_numpre = k00_numpre
                                 where j21_matric not in (select distinct j21_matric
                                                            from iptucalv
                                          inner join iptunump on j20_anousu = j21_anousu
                                                             and j20_matric = j21_matric
                                           inner join arrecad on j20_numpre = k00_numpre
                                                           where j21_codhis in (select distinct  j89_codhis
                                                                                  from iptucalhconf)
                                                                                   and j21_anousu = $anousu)
                                                             and j21_anousu = $anousu) ";
        }

        $sOrder = null;

        switch ($ordem) {
            case "endereco":
                $sOrder = "j23_munic, j23_uf, j23_ender, j23_numero, j23_compl";
                break;
            case "bairroender":
                $sOrder = "j23_bairro, j23_munic, j23_uf, j23_ender, j23_numero, j23_compl";
                break;
            case "alfabetica":
                $sOrder = "z01_nome";
                break;
            case "zonaentrega":
                $sOrder = "j86_iptucadzonaentrega";
                break;
            case "refant":
                $sOrder = "j40_refant";
                break;
            case "setorquadralote":
                $sOrder = "j34_setor, j34_quadra, j34_lote";
                break;
            case "bairroalfa":
                $sOrder = " j23_bairro ";
                break;
            default:
                $sOrder = "z01_nome";
                break;
        }

        $sqlprinc = "";
        $sqlprinc .= "select * from ( ";
        $sqlprinc .= "select distinct ";
        $sqlprinc .= "       j23_matric, ";
        $sqlprinc .= "       j23_vlrter, ";
        $sqlprinc .= "       j23_aliq, ";
        $sqlprinc .= "       j23_areafr, ";
        $sqlprinc .= "       j86_iptucadzonaentrega, ";
        $sqlprinc .= "       z01_nome, ";
        $sqlprinc .= "       j20_numpre, ";
        $sqlprinc .= "       j01_idbql, ";
        $sqlprinc .= "       j23_arealo, ";
        $sqlprinc .= "       j23_m2terr, ";
        $sqlprinc .= "       j40_refant, ";
        $sqlprinc .= "       j34_setor, ";
        $sqlprinc .= "       j34_quadra, ";
        $sqlprinc .= "       j34_lote, ";
        $sqlprinc .= "       substr(fc_iptuender,001,40) as j23_ender, ";
        $sqlprinc .= "       substr(fc_iptuender,042,10) as j23_numero, ";
        $sqlprinc .= "       substr(fc_iptuender,053,20) as j23_compl, ";
        $sqlprinc .= "       substr(fc_iptuender,074,40) as j23_bairro, ";
        $sqlprinc .= "       substr(fc_iptuender,115,40) as j23_munic, ";
        $sqlprinc .= "       substr(fc_iptuender,156,02) as j23_uf, ";
        $sqlprinc .= "       substr(fc_iptuender,159,08) as j23_cep, ";
        $sqlprinc .= "       substr(fc_iptuender,168,20) as j23_cxpostal, ";
        $sqlprinc .= "       substr(fc_iptuender,189,40) as j23_dest from  ( ";
        $sqlprinc .= "       select j23_matric, ";
        $sqlprinc .= "              j23_vlrter, ";
        $sqlprinc .= "              j23_aliq, ";
        $sqlprinc .= "              j23_areafr, ";
        $sqlprinc .= "              j20_numpre, ";
        $sqlprinc .= "              j86_iptucadzonaentrega, ";
        $sqlprinc .= "              (select z01_nome ";
        $sqlprinc .= "                 from proprietario_nome where j01_matric = j23_matric limit 1) as z01_nome, ";
        $sqlprinc .= "              j01_idbql, ";
        $sqlprinc .= "              j23_m2terr,";
        $sqlprinc .= "              j23_arealo, ";
        $sqlprinc .= "              j40_refant, ";
        $sqlprinc .= "              j34_setor, ";
        $sqlprinc .= "              j34_quadra, ";
        $sqlprinc .= "              j34_lote, ";
        $sqlprinc .= "              fc_iptuender(j23_matric) ";
        $sqlprinc .= "         from iptucalc ";
        $sqlprinc .= "              inner join iptubase           on iptubase.j01_matric = iptucalc.j23_matric ";
        $sqlprinc .= "              inner join iptunump           on iptunump.j20_matric = iptubase.j01_matric";
        $sqlprinc .= "                                           and iptunump.j20_anousu = $anousu ";
        $sqlprinc .= "              inner join lote               on lote.j34_idbql = iptubase.j01_idbql ";
        $sqlprinc .= "              inner join cgm                on cgm.z01_numcgm = iptubase.j01_numcgm ";
        $sqlprinc .= "              left  join iptumatzonaentrega a on a.j86_matric = iptubase.j01_matric ";
        $sqlprinc .= "              left  join imobil             on imobil.j44_matric = iptubase.j01_matric ";
        $sqlprinc .= "              left  join loteloteam         on loteloteam.j34_idbql = lote.j34_idbql ";
        $sqlprinc .= "              left  join iptuant            on iptuant.j40_matric   = iptubase.j01_matric ";
        $sqlprinc .= "        where iptucalc.j23_anousu = {$anousu} ";
        $sqlprinc .= "        {$whereimobil} {$wherelistamatrics} {$whereloteam} {$whereisencao}";
        $sqlprinc .= "        " . ($quantidade != "" ? " limit {$quantidade}" : "") . " ) as x ";
        $sqlprinc .= "        ) as y ";
        $sqlprinc .= " order by {$sOrder} ";
        $sqlprinc .= !empty($quantidade) ? " limit $quantidade" : "";
        $resultprinc = $cliptucalc->sql_record($sqlprinc);

        if ($resultprinc == false || $cliptucalc->numrows == 0) {
            throw new \Exception("Não existe cálculo efetuado para o ano {$anousu}.");
        }

        $quantescolhida = $quantidade;

        $total_reg = pg_num_rows($resultprinc);


        if ($quantescolhida == "") {
            $quantidade = $total_reg;
        } else {
            $quantidade = $quantescolhida;
            if ($quantidade > $total_reg) {
                $quantidade = $total_reg;
            }
        }

        /**
         * Busca o Tipo de D\E9bito
         */
        $sqlTipo = "select k00_tipo ";
        $sqlTipo .= " from iptunump ";
        $sqlTipo .= "      inner join arrecad on j20_numpre = k00_numpre ";
        $sqlTipo .= "where j20_anousu = {$anousu} limit 1";
        $rsTipo = \db_query($sqlTipo);

        if (pg_num_rows($rsTipo) == 0) {
            throw new \Exception("Erro ao buscar o tipo de d\E9bito.");
        }

        $iTipoDebito = \db_utils::fieldsMemory($rsTipo, 0)->k00_tipo;

        /**
         * Busca a taxa banc\E1ria e as informa\E7\F5es do tipo de d\E9bito
         */
        $sSqlArretipoTxBancoExec = "EXECUTE sSqlArretipoTxBanco({$iTipoDebito})";
        $rsSqlArretipo = \db_query($sSqlArretipoTxBancoExec) or die($sSqlArretipoTxBancoExec);

        if (pg_num_rows($rsSqlArretipo) == 0) {
            throw new \Exception("O c\F3digo do banco n\E3o esta cadastrado no arquivo arretipo para este tipo.");
        }

        extract((array)\db_utils::fieldsMemory($rsSqlArretipo, 0));

        $nTaxaBancaria = !empty($tx_banc) ? $tx_banc : 0;

        /**
         * Instancia a regra de emissão a ser utilizada para emissão dos recibos com base na rotina e tipo de débito
         */
        $oRegraEmissao = new \regraEmissao(
            $iTipoDebito,
            10,
            \db_getsession('DB_instit'),
            date("Y-m-d", \db_getsession("DB_datausu")),
            \db_getsession('DB_ip')
        );

        /**
         * Verifica se o conv\EAnio \E9 um convenio de cobran\E7a v\E1lido
         */
        $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

        /**
         * Cria uma nova emissao geral para essa gera\E7\E3o
         */
        $oParametros = (object)array(
          "processa_massa_falida"       => $lProcessaMassaFalida,
          "somente_com_endereco_valido" => $lSomenteEnderecoEntregaValido,
          "gerar_com_cidade_branco"     => $lGerarComCidadeEmBranco,
          "parcelas_obrigatorias"       => $aParcelasObrigatorias,
          "maximo_parcelas_gerar"       => $iMaximoParcelasParaGerar,
          "matriculas"                  => $listamatrics,
          "ano"                         => $anousu,
          "quantidade"                  => $quantescolhida,
          "anos_considerar_movimento"   => $iAnosConsiderarMovimento,
          "ordem"                       => $ordem,
          "especie"                     => $especie,
          "imobiliaria"                 => $imobiliaria,
          "loteamento"                  => $loteamento,
          "filtro_principal"            => $filtroprinc,
          "nTaxaBancaria"               => $nTaxaBancaria,
          "valor_minimo_parcelado"      => $vlrminunica,
          "intervalo_parcelado"         => $intervalo,
          "valor_maximo_parcelado"      => $vlrmaxunica,
          "barras_parcela"              => $barrasparc,
          "barras_unica"                => $barrasunica,
          "valor_minimo"                => $vlrmin,
          "valor_maximo"                => $vlrmax,
          "expressao_isento"            => $expressaoIsento
        );
        if (empty($codigoEmissao)) {
            $oEmissaoGeral = EmissaoGeral::create(
                EmissaoGeral::TIPO_IPTU,
                $oRegraEmissao->getConvenio(),
                $oParametros,
                $emissaoGeralPadrao
            );
        } else {
            $emissaoGeralRepository = new EmissaoGeralRepository();
            $oEmissaoGeral = $emissaoGeralRepository->getEmissao($codigoEmissao);
        }

        /**
         * Verifica se deve gerar as parcelas unicas
         */
        $aParcelasUnicas = array();

        if (!empty($sParcelaUnica)) {
            $oRepositoryUnica = new ParcelaUnicaRepository();
            $aUnicas = explode('U', $sParcelaUnica);

            foreach ($aUnicas as $sUnica) {
                $aDadosParcelaUnica = explode('=', $sUnica);
                if (!empty($aDadosParcelaUnica) && count($aDadosParcelaUnica) == 3) {
                    $oParcelaUnica = new ParcelaUnica();
                    $oParcelaUnica->setEmissaoGeral($oEmissaoGeral);
                    $oParcelaUnica->setDataVencimento(new \DBDate($aDadosParcelaUnica[0]));
                    $oParcelaUnica->setDataOperacao(new \DBDate($aDadosParcelaUnica[1]));
                    $oParcelaUnica->setPercentual((int)$aDadosParcelaUnica[2]);

                    if (empty($codigoEmissao)) {
                        $oRepositoryUnica->add($oParcelaUnica);
                    }
                    $aParcelasUnicas[] = $oParcelaUnica;
                }
            }
        }

        $oRegistroRepository = new RegistroRepository();
        $cliptubase = new \cl_iptubase();
        $aInconsistencias = array();

        $aRegistrosGerados = array();

        /**
         * Percorre os registros para gerar os recibos
         */
        for ($i = 0; $i < $quantidade; $i++) {
            extract((array)\db_utils::fieldsMemory($resultprinc, $i));
            db_inicio_transacao();

            /**
             * Faz a chamada do callback para atualizar o andamento da gera\E7\E3o dor recibos
             */
            $callbackAtualiza($i, $quantidade);

            /**
             * Come\E7a a executar as valida\E7\F5es com base nos filtros passados
             * Tamb\E9m busca os dados para emiss\E3o dos recibos
             */

            /**
             * verifica massa falida
             */
            if (empty($lProcessaMassaFalida)) {
                $clmassamat->sql_record($clmassamat->sql_query_file(null, $j23_matric));
                if ($clmassamat->numrows > 0) {
                    continue;
                }
            }

            /**
             * Verifica se deve ou n\E3o gerar o recibo quando n\E3o tem cidade e caixa postal com base no parametro
             */
            if (!$lGerarComCidadeEmBranco && empty($j23_munic) && empty($j23_cxpostal)) {
                continue;
            }

            /**
             * Busca as informa\E7\F5es do propriet\E1rio da matricula
             */

            $rsProprietario = $cliptubase->proprietario_record(
                $cliptubase->proprietario_query($j23_matric)
            );

            if ($cliptubase->numrows == 0) {
                continue;
            }

            extract((array)\db_utils::fieldsMemory($rsProprietario, 0));

            $lEspecieInvalido = false;

            /**
             * Verifica se deve gerar somente territorial ou predial ou todos, com base no parametro
             */
            switch ($especie) {
                case "predial":
                    if ($j01_tipoimp == "Territorial") {
                        $lEspecieInvalido = true;
                    }
                    break;

                case "territorial":
                    if ($j01_tipoimp == "Predial") {
                        $lEspecieInvalido = true;
                    }
                    break;
            }

            if ($lEspecieInvalido) {
                continue;
            }

            if ($lConvenioCobrancaValido) {

                /**
                 * Verifica se o CPF ou CNPJ \E9 valido caso seja um convenio de cobran\E7a valido
                 */
                if (!\DBString::isCNPJ($z01_cgccpf) && !\DBString::isCPF($z01_cgccpf)) {
                    $aInconsistencias[$z01_cgmpri] = array(
                      $z01_cgmpri,
                      $z01_nome,
                      "CPF ou CNPJ do contribuinte \E9 inv\E1lido."
                    );
                    continue;
                }
            }

            /**
             * Verifica se o setor e quadra est\E3o na tabela para n\E3o ser processado
             */
            $sSqlNaoGeraExec = "EXECUTE sSqlNaoGera('{$j34_setor}', '{$j34_quadra}')";
            $resultgera = \db_query($sSqlNaoGeraExec) or die($sSqlNaoGeraExec);

            if (pg_num_rows($resultgera) > 0) {
                continue;
            }

            /**
             * Verifica se o CGM esta na tabela para n\E3o ser processado
             */
            $sSqlNaoGeraCgmExec = "EXECUTE sSqlNaoGeraCgm($z01_cgmpri)";
            $resultgeracgm = \db_query($sSqlNaoGeraCgmExec) or die($sSqlNaoGeraCgmExec);

            if (pg_num_rows($resultgeracgm) > 0) {
                continue;
            }

            /**
             * Verifica se a matricula esta na tabela para n\E3o ser processada
             */
            $sSqlNaoGeraMatricExec = "EXECUTE sSqlNaoGeraMatric($j23_matric)";
            $resultgeramatric = \db_query($sSqlNaoGeraMatricExec) or die($sSqlNaoGeraMatricExec);

            if (pg_num_rows($resultgeramatric) > 0) {
                continue;
            }

            /**
             * Verifica se o endere\E7o de entrega \E9 valido com base no parametro passado
             */
            if ($lSomenteEnderecoEntregaValido && empty($j23_ender) && empty($j23_cxpostal)) {
                continue;
            }

            /**
             * Considera as movimenta\E7\F5es da quantidade de anos passada por parametro para a matr\EDcula
             */
            if (!empty($iAnosConsiderarMovimento)) {
                $ano_movimentacao_ini = $anousu - $iAnosConsiderarMovimento;
                $ano_movimentacao_fim = $anousu - 1;

                $sSqlNaoGeraMatricExec = "EXECUTE sSqlMovimentos({$ano_movimentacao_ini}";
                $sSqlNaoGeraMatricExec .= ", {$ano_movimentacao_ini}";
                $sSqlNaoGeraMatricExec .= ", {$ano_movimentacao_fim}";
                $sSqlNaoGeraMatricExec .= ", {$ano_movimentacao_fim}";
                $sSqlNaoGeraMatricExec .= ", {$ano_movimentacao_fim}";
                $sSqlNaoGeraMatricExec .= ", {$anousu}";
                $sSqlNaoGeraMatricExec .= ", {$j23_matric})";
                $resultgeramatric = \db_query($sSqlNaoGeraMatricExec) or die($sSqlNaoGeraMatricExec);

                $imprime = 0;
                if (pg_num_rows($result_movimentacao) > 0) {
                    extract((array)\db_utils::fieldsMemory($result_movimentacao, 0));

                    if ($situacao == "SIM") {
                        $imprime = 1;
                    }
                }

                if ($imprime == 0) {
                    continue;
                }
            }

            /**
             * Verifica se as parcelas obrigat\F3rias passadas est\E3o em aberto
             */
            if (!empty($aParcelasObrigatorias)) {
                $sqlparcobrig = " select k00_matric
                            from arrematric m
                                 inner join arrecad a on m.k00_numpre = a.k00_numpre
                           where m.k00_numpre = {$j20_numpre}
                           group by k00_matric, m.k00_numpre
                          having array_accum(k00_numpar) @> array[" . implode(", ", $aParcelasObrigatorias) . "]
                           limit 1 ";
                $resultfinparcobrig = \db_query($sqlparcobrig) or die($sqlparcobrig);

                if (!$resultfinparcobrig) {
                    throw new \Exception("Erro ao verificar parcelas em aberto para a matricula: {$j23_matric}.");
                }

                if (pg_num_rows($resultfinparcobrig) == 0) {
                    continue;
                }
            }

            /**
             * Verifica se deve emitir conforme filtro principal
             */
            switch ($filtroprinc) {
                case "compgto":
                    $sSqlFinPriPagaComPgtoExec = "EXECUTE sSqlFinPriPagaComPgto({$j20_numpre}, " . date(
                        "Y-m-d",
                        \db_getsession("DB_datausu")
                    ) . ")";
                    $resultfinpripaga = \db_query($sSqlFinPriPagaComPgtoExec) or
                    die($sSqlFinPriPagaComPgtoExec);

                if (pg_num_rows($resultfinpripaga) > 0) {
                    continue;
                }
                    break;

                case "sempgto":
                    $sSqlFinPriPagaSemPgtoExec = "EXECUTE sSqlFinPriPagaSemPgto({$j20_numpre})";
                    $resultfinpripaga = \db_query($sSqlFinPriPagaSemPgtoExec) or
                    die($sSqlFinPriPagaSemPgtoExec);

                if (pg_num_rows($resultfinpripaga) == 1) {
                    continue;
                }
                    break;
            }

            /**
             * Busca o valor total do iptu e a parcela m\E1xima
             */
            $sSqlValorMaxExec = "EXECUTE sSqlValorMax($j20_numpre)";
            $rsValorMax = \db_query($sSqlValorMaxExec) or die($sSqlValorMaxExec);

            $intNumrowsValorMax = pg_num_rows($rsValorMax);

            if ($intNumrowsValorMax > 0) {
                extract((array)\db_utils::fieldsMemory($rsValorMax, 0));
            }

            $iQuantidadeParcelaUnica = 0;

            if (!empty($aParcelasUnicas)) {
                foreach ($aParcelasUnicas as $oUnica) {
                    $sqlfin = "select r.k00_numpre,
                               r.k00_dtvenc,
                               r.k00_dtoper,
                               r.k00_percdes,
                               fc_calcula(r.k00_numpre, 0, 0, r.k00_dtvenc, r.k00_dtvenc, $anousu)
                          from recibounica r
                         where r.k00_numpre = $j20_numpre
                           and r.k00_dtvenc = '{$oUnica->getDataVencimento()->getDate()}'
                           and r.k00_dtoper = '{$oUnica->getDataOperacao()->getDate()}'
                           and k00_percdes  = {$oUnica->getPercentual()}";
                    $resultfin = \db_query($sqlfin) or die($sqlfin);

                    if (pg_num_rows($resultfin) > 0) {
                        $iQuantidadeParcelaUnica = 1;
                    }
                }
            }

            /**
             * Verifica se o máximo de parcelas a gerar não ultrapassa o parametro passado
             */
            if (!empty($iMaximoParcelasParaGerar)) {
                if (($parcelamaxima + $iQuantidadeParcelaUnica) > $iMaximoParcelasParaGerar ||
                ($parcelamaxima + $iQuantidadeParcelaUnica) < $iMaximoParcelasParaGerar) {
                    continue;
                }
            }

            /**
             * Verifica se valor total do iptu for menor que valor minimo ou maior que o valor maximo
             */
            if ($viptu < $vlrmin || $viptu > $vlrmax) {
                continue;
            }

            /**
             * Busca as informa\E7\F5es do arrecad para o numpre
             */
            $sSqlArrecadExec = "EXECUTE sSqlArrecad({$j20_numpre})";
            $resultfinarrecad = \db_query($sSqlArrecadExec) or die($sSqlArrecadExec);

            if (!$resultfinarrecad) {
                $mensagem = "Erro ao buscar informaçeõses dos débitos para a Matrícula:";
                $mensagem .= " {$j23_matric}, Numpre: {$j20_numpre}";

                throw new \Exception($mensagem);
            }

            if (pg_num_rows($resultfinarrecad) == 0) {
                continue;
            }

            /**
             * Gera os recibos para as parcelas unicas
             */
            if (!empty($aParcelasUnicas)) {
                foreach ($aParcelasUnicas as $oUnica) {
                    $sqlfin = "select r.k00_numpre,
                               r.k00_dtvenc,
                               r.k00_dtoper,
                               r.k00_percdes,
                               fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,$anousu)
                          from recibounica r
                         where r.k00_numpre = $j20_numpre
                           and r.k00_dtvenc = '{$oUnica->getDataVencimento()->getDate()}'
                           and r.k00_dtoper = '{$oUnica->getDataOperacao()->getDate()}'
                           and k00_percdes  = {$oUnica->getPercentual()}";

                    $resultfin = \db_query($sqlfin) or die($sqlfin);

                    if (pg_num_rows($resultfin) > 0) {
                        for ($unicont = 0; $unicont < pg_num_rows($resultfin); $unicont++) {
                            extract((array)\db_utils::fieldsMemory($resultfin, $unicont));

                            $uvlrhis = substr($fc_calcula, 1, 13);
                            $uvlrcor = substr($fc_calcula, 14, 13);
                            $uvlrjuros = substr($fc_calcula, 27, 13);
                            $uvlrmulta = substr($fc_calcula, 40, 13);
                            $uvlrdesconto = substr($fc_calcula, 53, 13);

                            $utotal = $uvlrcor + $uvlrjuros + $uvlrmulta - $uvlrdesconto + $nTaxaBancaria;

                            $k00_numpar = 0;

                            $k03_numpreunica = '';

                            try {
                                $oRecibo = new \recibo(2, null, 5);
                                $oRecibo->addNumpre($k00_numpre, 0);
                                $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                                $oRecibo->setDataRecibo($k00_dtvenc);
                                $oRecibo->setDataVencimentoRecibo($k00_dtvenc);
                                $oRecibo->emiteRecibo($lConvenioCobrancaValido, false);
                                $k03_numpreunica = $oRecibo->getNumpreRecibo();

                                $repository = new RecibopagaRepository(DataBase::getInstance(), new \cl_recibopaga());
                                $where = array(
                                  "k00_numnov = {$oRecibo->getNumpreRecibo()}",
                                  "k00_hist = " . TaxaEspecificaModel::CODIGO_HISTORICO
                                );
                                $reciboPaga = $repository->findAll(implode(' AND ', $where))->get(0);

                                $valorExpediente = !empty($reciboPaga->getValor()) ? $reciboPaga->getValor() : 0;
                            } catch (\Exception $eException) {
                                $mensagem = "Erro2 - Matricula: {$j23_matric} - Numpre: {$j20_numpre}";
                                $mensagem .= " - {$eException->getMessage()}";

                                throw new \Exception($mensagem);
                            }
                        }

                        $vlrbar = \db_formatar(str_replace(
                            '.',
                            '',
                            str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)
                        ), 's', '0', 11, 'e');

                        if ($barrasunica == "seis") {
                            $terceiro = "6";
                        } else {
                            $terceiro = "7";
                        }

                        try {
                            $valorConvenio = !empty($valorExpediente) ? $utotal + $valorExpediente : $utotal;
                            $oConvenio = new \convenio(
                                $oRegraEmissao->getConvenio(),
                                $k03_numpreunica,
                                0,
                                $valorConvenio,
                                $vlrbar,
                                $k00_dtvenc,
                                $terceiro
                            );
                        } catch (\Exception $eExeption) {
                            $mensagem = "Erro3 - Matricula: {$j23_matric} - Numpre: {$j20_numpre}";
                            $mensagem .= " - {$eExeption->getMessage()}";

                            throw new \Exception($mensagem);
                        }

                        /**
                         * Adiciona o recibo gerado na lista desta emissao geral
                         */
                        $situacao = RegistroPadrao::SITUACAO_SEM_COBRANCA;

                        if ($lConvenioCobrancaValido) {
                            $situacao = RegistroPadrao::SITUACAO_PENDENTE;
                        }

                        $oRegistro = RegistroFactory::getRegistro($oEmissaoGeral);
                        $oRegistro->setNumpre($oRecibo->getNumpreRecibo());
                        $oRegistro->setParcela($k00_numpar);
                        $oRegistro->setCgm($z01_cgmpri);
                        $oRegistro->setMatricula($j23_matric);
                        $oRegistro->setSituacao($situacao);
                        $oRegistro->setParcelaUnica($oUnica->getCodigo());

                        $oRegistroRepository->add($oRegistro);

                        $aRegistrosGerados[] = $oRecibo->getNumpreRecibo();

                        /**
                         * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na
                         * fila para geração do arquivo de cobrança registrada
                         */
                        if ($lConvenioCobrancaValido) {
                            CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                        }
                    }
                }
            } // Final das parcelas unicas

            /**
             * Gera os recibos das parcelas que encontrou na arrecad
             */
            for ($iParcela = 0; $iParcela < pg_num_rows($resultfinarrecad); $iParcela++) {
                $oDadosParcela = \db_utils::fieldsMemory($resultfinarrecad, $iParcela);

                $data_calc = date("Y-m-d", \db_getsession("DB_datausu"));
                $sql_calcula = "select fc_calcula({$oDadosParcela->k00_numpre}";
                $sql_calcula .= ", {$oDadosParcela->k00_numpar}, 0, '{$data_calc}', '{$data_calc}', $anousu)";
                $rsCalcula = \db_query($sql_calcula);

                if (!$rsCalcula || pg_num_rows($rsCalcula) == 0) {
                    $mensagem = "Erro ao corrigir o valor da parcela {$oDadosParcela->k00_numpar}";
                    $mensagem .= " da Matrícula: {$j23_matric}";

                    throw new \Exception($mensagem);
                }

                $oCalculo = \db_utils::fieldsMemory($rsCalcula, 0);
                $nValorParcelaCorrigido = (float)substr($oCalculo->fc_calcula, 14, 13) +
                  (float)substr($oCalculo->fc_calcula, 27, 13) +
                  (float)substr($oCalculo->fc_calcula, 40, 13) -
                  (float)substr($oCalculo->fc_calcula, 53, 13);

                $nValorParcelaCorrigido += $nTaxaBancaria;

                try {
                    $oRecibo = new \recibo(2, null, 5);
                    $oRecibo->addNumpre($oDadosParcela->k00_numpre, $oDadosParcela->k00_numpar);
                    $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                    $oRecibo->setDataRecibo($oDadosParcela->k00_dtvenc);
                    $oRecibo->setDataVencimentoRecibo($oDadosParcela->k00_dtvenc);
                    $oRecibo->emiteRecibo($lConvenioCobrancaValido, false);

                    $repository = new RecibopagaRepository(DataBase::getInstance(), new \cl_recibopaga());
                    $where = array(
                      "k00_numnov = {$oRecibo->getNumpreRecibo()}",
                      "k00_hist = " . TaxaEspecificaModel::CODIGO_HISTORICO
                    );
                    $reciboPaga = $repository->findAll(implode(' AND ', $where))->get(0);

                    $valorExpediente = !empty($reciboPaga->getValor()) ? $reciboPaga->getValor() : 0;
                } catch (\Exception $eException) {
                    $mensagem = "Erro6 - Matrícula: {$j23_matric} - Numpre: {$j20_numpre}";
                    $mensagem .= " - {$eException->getMessage()}";

                    throw new \Exception($mensagem);
                }

                $vlrbar = \db_formatar(
                    str_replace(
                        '.',
                        '',
                        str_pad(number_format($nValorParcelaCorrigido, 2, "", "."), 11, "0", STR_PAD_LEFT)
                    ),
                    's',
                    '0',
                    11,
                    'e'
                );

                if ($barrasparc == "seis") {
                    $terceiro = "6";
                } else {
                    $terceiro = "7";
                }

                $datavencimento = $oDadosParcela->k00_dtvenc;
                if ($datavencimento == "") {
                    $datavencimento = "0000-00-00";
                }

                try {
                    $valorConvenio = $nValorParcelaCorrigido;

                    if (!empty($valorExpediente)) {
                        $valorConvenio = $nValorParcelaCorrigido + $valorExpediente;
                    }
                    $oConvenio = new \convenio(
                        $oRegraEmissao->getConvenio(),
                        $oRecibo->getNumpreRecibo(),
                        0,
                        $valorConvenio,
                        $vlrbar,
                        $datavencimento,
                        $terceiro
                    );
                } catch (\Exception $eException) {
                    $mensagem = "Erro6 - Matrícula: {$j23_matric} - Numpre: {$j20_numpre}";
                    $mensagem .= " - {$eException->getMessage()}";

                    throw new \Exception($mensagem);
                }

                /**
                 * Adiciona o recibo gerado na lista desta emissao geral
                 */
                $situacao = RegistroPadrao::SITUACAO_SEM_COBRANCA;

                if ($lConvenioCobrancaValido) {
                    $situacao = RegistroPadrao::SITUACAO_PENDENTE;
                }

                $oRegistro = RegistroFactory::getRegistro($oEmissaoGeral);
                $oRegistro->setNumpre($oRecibo->getNumpreRecibo());
                $oRegistro->setParcela($oDadosParcela->k00_numpar);
                $oRegistro->setCgm($z01_cgmpri);
                $oRegistro->setMatricula($j23_matric);
                $oRegistro->setSituacao($situacao);

                $oRegistroRepository->add($oRegistro);

                $aRegistrosGerados[] = $oRecibo->getNumpreRecibo();

                /**
                 * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na fila
                 * para geração do arquivo de cobrança registrada
                 */
                if ($lConvenioCobrancaValido) {
                    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                }
            }
            db_fim_transacao(false);
        }

        if (empty($aRegistrosGerados)) {
            throw new \BusinessException("Nenhum registro encontrado para os filtros selecionados.");
        }

        $aArquivosRetorno = array();

        /**
         * Caso existam inconsistencias, deve ser gerado o relat\F3rio
         */
        if (!empty($aInconsistencias)) {
            $aArquivosRetorno[] = static::gerarRelatorioInconsistencia($aInconsistencias);
        }

        return $aArquivosRetorno;
    }

    /**
     * Fun\E3o que gera o relat\F3rio de inconsist\EAncias da Emiss\E3o geral de IPTU
     * @param $aInconsistencias
     * @return \File
     * @throws \ParameterException
     */
    public static function gerarRelatorioInconsistencia($aInconsistencias)
    {
        $oRelatorio = new \PDFTable();

        $oRelatorio->setPercentWidth(true);
        $oRelatorio->setLineHeigth(5);

        $aCabecalho = array(
          "CGM",
          "Nome / Raz\E3o Social",
          "Inconsist\EAncia"
        );

        $aLargura = array(10, 50, 40);
        $aAlinhamento = array(
          \PDFDocument::ALIGN_CENTER,
          \PDFDocument::ALIGN_LEFT,
          \PDFDocument::ALIGN_LEFT
        );

        $oRelatorio->setHeaders($aCabecalho);
        $oRelatorio->setColumnsWidth($aLargura);
        $oRelatorio->setColumnsAlign($aAlinhamento);

        $aRazaoSocial = array();

        foreach ($aInconsistencias as $iIndice => $aInconsistencia) {
            $aRazaoSocial[$iIndice] = $aInconsistencia[1];
        }

        array_multisort($aRazaoSocial, SORT_ASC, SORT_NATURAL, $aInconsistencias);

        foreach ($aInconsistencias as $iIndice => $aInconsistencia) {
            $oRelatorio->addLineInformation($aInconsistencia);
        }

        $oPdfDocument = new \PDFDocument();
        $oPdfDocument->addHeaderDescription("Relatório de Inconsistências da Exportação de Cobrança Registrada.");

        $oPdfDocument->SetFillColor(235);
        $oPdfDocument->setFontSize(8);
        $oPdfDocument->open();

        $oRelatorio->printOut($oPdfDocument, false);

        /**
         * Criamos o arquivo com o relat\F3rio
         */
        $sNomeArquivo = "cobranca_registrada_relatorio_inconsistencia_" . time();
        $sArquivoRelatorio = $oPdfDocument->savePDF($sNomeArquivo);

        return new \File($sArquivoRelatorio);
    }
}
