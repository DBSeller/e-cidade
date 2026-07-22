<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

require_once modification ("interfaces/iPadArquivoTxtBase.interface.php");
require_once modification ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");
//
/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Pagamento
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoPagamento extends SigfisArquivoBase implements iPadArquivoTXTBase {

    protected $iCodigoLayout     = 206;
    protected $sNomeArquivo      = 'PagEmp';
    protected $aMovimentoContabil = array();


    /**
     * Busca os dados para gerar o Arquivo do Pagamento
     */
    public function gerarDados() {

        /**
         * Busca os dados da db_config
         */

        $iInstituicaoSessao = db_getsession('DB_instit');

        $oDadoConfig    = db_stdClass::getDadosInstit();
        $clConLanCamEmp = db_utils::getDao('conlancamemp');

        $this->setCodigoLayout(206);
        $iAnoSessao = db_getsession("DB_anousu");
        if( $iAnoSessao < 2013 ){
            $this->setCodigoLayout(129);
        }



        $sCampos  = " empempenho.e60_codemp, empempenho.e60_anousu, conlancam.c70_data, orcdotacao.o58_orgao, ";
//    $sCampos  = " array_to_string(array_accum(distinct c60_estrut||'-'||c60_codcon),',') as c60_estrut, ";

        $sCampos .= " array_to_string(array_accum(distinct c60_estrut||'-'||c60_codcon),',') as c60_estrut, ";

        $sCampos .= " array_to_string(array_accum(distinct c60_codcon),',') as c60_codcon, ";
        $sCampos .= " array_to_string(array_accum(distinct c61_reduz||'-'||c60_codcon),',') as c61_reduz, ";
        $sCampos .= " array_to_string(array_accum(distinct c70_codlan),',') as c70_codlan, ";

        $sCampos .= " array_to_string(array_accum(distinct pagordem.e50_codord),',') as codordem, ";
        $sCampos .= " to_char(max(conlancam.c70_data),'YYYYmm') as competencia, orcdotacao.o58_unidade, ";
//    $sCampos .= " k105_corgrupo, ";
//    $sCampos .= "k105_data, k105_id, k105_autent ";
        $sCampos .= " round(sum(case  when c53_tipo = 30 and c53_coddoc not in (6002, 6003, 6004, 6005, 6008, 6009, 6010, 6011) then conlancam.c70_valor when c53_tipo = 31 and c53_coddoc not in (6002, 6003, 6004, 6005, 6008, 6009, 6010, 6011) then (conlancam.c70_valor * -1)  else 0 end),2) as valor_pago ";

        $sWhere   = " conlancam.c70_anousu = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao}                    ";
        $sWhere  .= " and empempenho.e60_anousu = {$this->iAnoUso}                                                                 ";
        $sWhere  .= " and conhistdoc.c53_tipo in (30,31)                                                                           ";
//    $sWhere  .= " and e60_codemp = '306' ";
        $sWhere  .= " and conlancam.c70_data between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
        $sWhere  .= " group by empempenho.e60_codemp, empempenho.e60_anousu, c70_data, orcdotacao.o58_orgao, empempenho.e60_numemp, ";
//    $sWhere  .= " k105_corgrupo, ";
//    $sWhere  .= " k105_data, k105_id, k105_autent ";
        $sWhere  .= " orcdotacao.o58_unidade";

        $sOrdem   = "empempenho.e60_codemp, conlancam.c70_data";

        $sSqlConLanCamEmp = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);

// die($sSqlConLanCamEmp);

        /*  GERA SQL PARA O LOG  */
        //$sErroLog  = "$sSqlConLanCamEmp ";
        //$this->addLog($sErroLog);
        /* */

        // = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);
        $rsConLanCamEmp    = $clConLanCamEmp->sql_record($sSqlConLanCamEmp);

        $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");

        if ($clConLanCamEmp->numrows > 0) {

            if (empty($this->sCodigoTribunal)) {
                throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
            }

            system("echo > /tmp/pagemp.txt");

            for ($i = 0; $i < $clConLanCamEmp->numrows; $i++) {

                $oDadosQuery = new stdClass();
                $oDadosQuery = db_utils::fieldsMemory($rsConLanCamEmp, $i);

                if ($oDadosQuery->valor_pago == 0 && $oDadosQuery->c61_reduz != ''){
                    continue;
                }

                $codordem = $oDadosQuery->codordem;

//select *, ( select count(*) from conlancamcorgrupocorrente inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial where c23_conlancam = c80_codlan ) from conlancamord inner join conlancam on c70_codlan = c80_codlan where c80_codord = 61749 order by c70_data;

                $sSqlTotalRetencao = "SELECT COALESCE(sum(e23_valorretencao),0) as valorretido ";
                $sSqlTotalRetencao .= "FROM   retencaoreceitas ";
                $sSqlTotalRetencao .= "       INNER JOIN retencaotiporec ";
                $sSqlTotalRetencao .= "               ON retencaotiporec.e21_sequencial = ";
                $sSqlTotalRetencao .= "             retencaoreceitas.e23_retencaotiporec ";
                $sSqlTotalRetencao .= "     INNER JOIN retencaopagordem ";
                $sSqlTotalRetencao .= "               ON retencaopagordem.e20_sequencial = ";
                $sSqlTotalRetencao .= "                  retencaoreceitas.e23_retencaopagordem ";
                $sSqlTotalRetencao .= "       INNER JOIN tabrec ";
                $sSqlTotalRetencao .= "               ON tabrec.k02_codigo = retencaotiporec.e21_receita ";
                $sSqlTotalRetencao .= "       INNER JOIN retencaotipocalc ";
                $sSqlTotalRetencao .= "               ON retencaotipocalc.e32_sequencial = ";
                $sSqlTotalRetencao .= "                  retencaotiporec.e21_retencaotipocalc ";
                $sSqlTotalRetencao .= "       INNER JOIN pagordem ";
                $sSqlTotalRetencao .= "               ON pagordem.e50_codord = retencaopagordem.e20_pagordem ";
                $sSqlTotalRetencao .= "       INNER JOIN pagordemnota ";
                $sSqlTotalRetencao .= "               ON pagordem.e50_codord = pagordemnota.e71_codord ";
                $sSqlTotalRetencao .= "       INNER JOIN empnota ";
                $sSqlTotalRetencao .= "               ON pagordemnota.e71_codnota = empnota.e69_codnota ";
                $sSqlTotalRetencao .= "       INNER JOIN retencaoempagemov ";
                $sSqlTotalRetencao .= "               ON e23_sequencial = e27_retencaoreceitas ";
                $sSqlTotalRetencao .= "       LEFT JOIN empagemovslips ";
                $sSqlTotalRetencao .= "              ON e27_empagemov = k107_empagemov ";
                $sSqlTotalRetencao .= "       LEFT JOIN slipempagemovslips ";
                $sSqlTotalRetencao .= "              ON k107_sequencial = k108_empagemovslips ";
                $sSqlTotalRetencao .= "WHERE  e20_pagordem IN ({$codordem})";
                $sSqlTotalRetencao .= "       AND e23_ativo = true ";
                $sSqlTotalRetencao .= "       AND e71_anulado = false ";
                $sSqlTotalRetencao .= "       AND e27_principal IS TRUE ";


                $sSqlTotalRetencao  = " select round(sum(coalesce(case when k105_corgrupotipo = 2 then c70_valor else c70_valor*-1 end,0)),2) as valorretido from conlancamcorgrupocorrente ";
                $sSqlTotalRetencao .= " inner join conlancamdoc on c71_codlan = c23_conlancam ";
                $sSqlTotalRetencao .= " inner join conlancam on c70_codlan = c23_conlancam ";
                $sSqlTotalRetencao .= " inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial ";
                $sSqlTotalRetencao .= " where c23_conlancam in ( $oDadosQuery->c70_codlan ) and k105_corgrupotipo in (2,5)";
//die($sSqlTotalRetencao);


//    $sErroLog  = "$sSqlTotalRetencao --------------------------------";
//    $this->addLog($sErroLog);

                // $rsretencao    = $retencao->sql_record($sSqlTotalRetencao);
                // $rsretencao    = db_utils::db_query($sSqlTotalRetencao);
//die($sSqlTotalRetencao);

                $rsretencao    = db_query($sSqlTotalRetencao);
                if(pg_num_rows($rsretencao) > 0){
//    $vlrretencao = $rsretencao;
                    $vlrretencao = round(pg_result($rsretencao,0),2);
                } else {
                    $vlrretencao = 0.00;
                }

                $vlrpag_liquido = $oDadosQuery->valor_pago - $vlrretencao;

                if ($oDadosQuery->valor_pago == 0 && $vlrretencao > 0) {
                    $vlrpag_liquido = 0;
                }
//echo "pago: $oDadosQuery->valor_pago - ret: $vlrretencao - liq: $vlrpag_liquido\n";

                /**
                 * Verifica se a Conta retornada possui vinculo com a conta do Sigfis
                 */
//        if ($oVinculo = SigfisVinculoConta::getVinculoConta($oDadosQuery->c61_codcon)) {

                $oDados                = new stdClass();
                $sUnidadeOrcamentaria = str_pad($oDadosQuery->o58_unidade,4, ' ', STR_PAD_LEFT);
                $dtPagamento           = $this->formataData($oDadosQuery->c70_data);

                $oDados->cd_Unidade             = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
                $oDados->cd_UnidadeOrcamentaria = str_pad($sUnidadeOrcamentaria,     4, ' ', STR_PAD_LEFT);
                $oDados->nu_Empenho             = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
                $oDados->dt_PagamentoEmpenho    = $dtPagamento;
                $oDados->dt_Ano                 = $oDadosQuery->e60_anousu;
                $oDados->vl_Pagamento           = str_pad($vlrpag_liquido * 100 , 16, ' ', STR_PAD_LEFT);
//$oDados->vl_Pagamento           = str_pad(round($vlrpag_liquido,2), 16, ' ', STR_PAD_LEFT);
//          $oDados->vl_Pagamento           = str_pad($this->formataValor($oDadosQuery->valor_pago), 16, ' ', STR_PAD_LEFT);


                system("echo '" . $oDadosQuery->e60_codemp . ";" . $vlrpag_liquido . ";" . $oDadosQuery->valor_pago . ";" . $vlrretencao . "' >> /tmp/pagemp.txt");


                $vlrpag_liquido = $oDadosQuery->valor_pago - $vlrretencao;

                $aContas = array_unique((explode(',',$oDadosQuery->c60_estrut)));

                $aContasNovas = array();
                foreach ($aContas as $a) {
                    if (!empty($a)) {
                        $contaS = explode('-',$a);
                        $estrut = $contaS[0];
                        $codcon = $contaS[1];
                        $aContasNovas[] = $estrut;
                    }
                }
                $aContas = $aContasNovas;

                $aContasCodcon = array_unique((explode(',',$oDadosQuery->c61_reduz)));

                $aContasCodconNovas = array();
                foreach ($aContasCodcon as $a) {
                    if (!empty($a)) {
                        $contaJ = explode('-',$a);
                        $estrut = $contaJ[0];
                        $codcon = $contaJ[1];

                        $sContaCorrente = "   select ( CASE WHEN $iAnoSessao < 2016 THEN c56_sequencial ELSE c56_contabancaria END) as conta_bancaria from contabilidade.conplano 
             a left join conplanocontabancaria b on a.c60_codcon = b.c56_codcon 
             and a.c60_anousu = b.c56_anousu 
             left join configuracoes.contabancaria c on c.db83_sequencial = b.c56_contabancaria where a.c60_anousu = $iAnoSessao and a.c60_codcon = $codcon and b.c56_reduz = {$estrut}";
                        $rsContaCorrente = db_query($sContaCorrente);
                        $oDadosContaCorrente = db_utils::fieldsMemory($rsContaCorrente, 0);

                        $aContasCodconNovas[] = $oDadosContaCorrente->conta_bancaria;

                    }
                }
                $aContasCodcon = $aContasCodconNovas;

                $oDados->cd_ContaContabil1      = str_pad($aContas[0], 34, ' ', STR_PAD_RIGHT);
                $oDados->cd_ContaContabil2      = str_pad($aContas[1], 34, ' ', STR_PAD_RIGHT); // str_repeat(' ', 34); // Não usado no e-cidada
                $oDados->cd_ContaContabil3      = str_pad($aContas[2], 34, ' ', STR_PAD_RIGHT); // str_repeat(' ', 34); // Não usado no e-cidada



                $oDados->dt_AnoMes              = $oDadosQuery->competencia;
                $oDados->cd_Orgao               = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
                $oDados->nu_EmpenhoSup          = str_pad(str_repeat(' ', 10), 10, ' ', STR_PAD_LEFT);
                $oDados->Reservado_tce          = str_repeat(' ', 41);

                if( $iAnoSessao < 2013 ){
                    $oDados->codigolinha            = 416;
                }else{
                    $oDados->Reservado_tce1         = str_repeat(' ', 10);
                    $oDados->Cd_ContaCorrente1      = str_pad($aContasCodcon[0],  30, ' ', STR_PAD_RIGHT);
                    $oDados->Cd_ContaCorrente2      = str_pad($aContasCodcon[1],  30, ' ', STR_PAD_RIGHT);
                    $oDados->Cd_ContaCorrente3      = str_pad($aContasCodcon[2],  30, ' ', STR_PAD_RIGHT);
                    $oDados->codigolinha            = 671;
                }

                $this->aDados[] = $oDados;
                /*
                        } else {
                          $sErroLog  = "Estrutural {$oDadosQuery->c60_estrut} - Conta{$oDadosQuery->e50_codord} -> ";
          $sErroLog .= "sem Vinculo com plano do SIGFIS - Conta *NÃO* Adicionada ao Arquivo.\n";
                          $this->addLog($sErroLog);
                        } */
            }
        }

        $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
    }
}
?>

