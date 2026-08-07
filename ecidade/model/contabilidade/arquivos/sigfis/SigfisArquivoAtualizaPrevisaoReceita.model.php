<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once  modification("interfaces/iPadArquivoTxtBase.interface.php");
require_once  modification("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 * Classe que processa as informações para serem inseridas no
 * arquivo APrevRec.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */
class SigfisArquivoAtualizaPrevisaoReceita extends SigfisArquivoBase implements iPadArquivoTXTBase {

    protected $iCodigoLayout = 123;
    protected $sNomeArquivo  = 'APrevRec';

    public function gerarDados() {

        $oDaoOrcreceita     = db_utils::getDao('orcreceita');
        $iAnoSessao         = db_getsession('DB_anousu');
        $iInstituicaoSessao = db_getsession('DB_instit');

        /**
         * Efetuamos a busca no banco de dados para retornar as atualizações de
         * previsão de receita
         */
        $sCampos                  = " orcreceita.o70_anousu,                ";
        $sCampos                 .= " db_config.codtrib,                    ";
        $sCampos                 .= " orcfontes.o57_fonte,                  ";
        $sCampos                 .= " (SELECT SUM(orcsuplemrec.o85_valor)
                                           FROM orcreceita
                                           INNER JOIN db_config ON db_config.codigo = orcreceita.o70_instit
                                           INNER JOIN orcfontes ON orcfontes.o57_codfon = orcreceita.o70_codfon
                                           AND orcfontes.o57_anousu = orcreceita.o70_anousu
                                           INNER JOIN orcsuplemrec ON orcsuplemrec.o85_anousu = orcreceita.o70_anousu
                                           AND orcsuplemrec.o85_codrec = orcreceita.o70_codrec
                                           INNER JOIN orcsuplem ON orcsuplem.o46_codsup = orcsuplemrec.o85_codsup
                                           INNER JOIN orcsuplemlan ON orcsuplemlan.o49_codsup = orcsuplem.o46_codsup
                                           WHERE orcreceita.o70_anousu = ".$iAnoSessao."
                                             AND orcsuplemlan.o49_data BETWEEN '{$this->dtDataInicial}' AND '{$this->dtDataFinal}'
                                             AND orcreceita.o70_instit IN (".$iInstituicaoSessao.") LIMIT 1) AS o85_valor, ";
        $sCampos                 .= " orcsuplemlan.o49_data                 ";
        $sWhereBuscaAtualizacoes  = "     orcreceita.o70_anousu = {$iAnoSessao}                                             ";
        $sWhereBuscaAtualizacoes .= " and orcsuplemlan.o49_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}' ";
        $sWhereBuscaAtualizacoes .= " and orcreceita.o70_instit = {$iInstituicaoSessao}                          ";
        $sSqlBuscaAtualizacoes    = $oDaoOrcreceita->sql_query_atualizacoesprevisao(null, null, $sCampos, null, $sWhereBuscaAtualizacoes);

        $rsSqlBuscaAtualizacoes   = $oDaoOrcreceita->sql_record($sSqlBuscaAtualizacoes);
        $oAtualizacoes            = db_utils::getCollectionByRecord($rsSqlBuscaAtualizacoes);

        if ($oAtualizacoes > 0) {

            if (empty($this->sCodigoTribunal)) {
                throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
            }

            foreach ($oAtualizacoes as $oAtualizacao) {

                $oDaoOrcfontes = db_utils::getDao('orcfontes');
                $sWhereFontes  = " o57_anousu = {$this->iAnoUso} ";
                $sWhereFontes .= " and o57_fonte = '{$oAtualizacao->o57_fonte}'";
                $sSqlOrcFontes = $oDaoOrcfontes->sql_query_file(null, null, "*", null, $sWhereFontes);
                $rsOrcFontes   = $oDaoOrcfontes->sql_record($sSqlOrcFontes);

                /**
                 * Testando os dados de vinculo de receita no arquivo XML de vinculação
                 */
                $aReceitaSoma = array();
                if ($oDaoOrcfontes->numrows == 1) {

                    $iCodigoConta = db_utils::fieldsMemory($rsOrcFontes, 0)->o57_codfon;
                    $oVinculo = SigfisVinculoReceita::getVinculoReceita($iCodigoConta);
                    if (empty($oVinculo)) {

                        $sErroLog  = "Receita {$oAtualizacao->o57_fonte} do ano de {$this->iAnoUso} ";
                        $sErroLog .= "não tem vinculo com Receita Sigfis.\n";
                        $this->addLog($sErroLog);
                        continue;
                    }


                    $sEstrut = db_utils::fieldsMemory($rsOrcFontes, 0)->o57_fonte;
//                    $sEstrut = $oVinculo->receitatce;
                    if(substr($sEstrut, 0,  1) == '9' ) {
                        $sEstrut = '9' . substr($sEstrut, 2, 12);
                    }else {
                        $sEstrut = substr($sEstrut, 1, 13);
                    }

                    if (!isset( $aReceitaSoma[$sEstrut] )) {
                        $aReceitaSoma[$sEstrut] = $oAtualizacao->o85_valor;
                    } else {
                        $aReceitaSoma[$sEstrut] += $oAtualizacao->o85_valor;
                    }

                } else {
                    $sErroLog  = "Receita {$oAtualizacao->o57_fonte} do ano de {$this->iAnoUso} retornou mais de um registro.\n";
                    $this->addLog($sErroLog);
                }

                if (count($aReceitaSoma) > 0) {

                    foreach ($aReceitaSoma as $sFonte => $nValor) {

                        /**
                         * recuperando ano e mes
                         */
                        $aDadosData     = explode('-', $oAtualizacao->o49_data);
                        $sDataFormatada = $aDadosData[0].$aDadosData[1];

                        $oDadosLinha = new stdClass();
                        $oDadosLinha->dt_Ano           = $oAtualizacao->o70_anousu;
                        $oDadosLinha->cd_Unidade       = str_pad($this->sCodigoTribunal,             4, ' ', STR_PAD_LEFT);
                        $oDadosLinha->cd_ItemReceita   = str_pad(substr($sFonte, 0, 13), 13, ' ', STR_PAD_RIGHT);
                        $oDadosLinha->tp_Atual_Receita = 1;
                        $oDadosLinha->vl_Receita       = str_pad(number_format($nValor, 2, '', ''), 16, ' ', STR_PAD_LEFT);
                        $oDadosLinha->dt_AnoMes        = $sDataFormatada;
                        $oDadosLinha->codigolinha      = 410;
                        $this->aDados[]                = $oDadosLinha;
                    }
                }
            }
        }

        return $this->aDados;
    }
}
