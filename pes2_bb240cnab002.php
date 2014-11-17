<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcaixa_ze.php");
include("libs/db_libgertxtfolha.php");
include("classes/db_folha_classe.php");
include("classes/db_pensao_classe.php");
include("classes/db_rharqbanco_classe.php");
include("classes/db_orctiporec_classe.php");
require_once("libs/db_utils.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$cllayouts_bb  = new cl_layouts_bb;
$clfolha       = new cl_folha;
$clpensao      = new cl_pensao;
$clrharqbanco  = new cl_rharqbanco;
$clorctiporec  = new cl_orctiporec;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r38_liq");
$clrotulo->label("r38_banco");
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");
$clrotulo->label("db83_tipoconta");
$sqlerro = false;

db_sel_instit();

//die($clrharqbanco->sql_query($rh34_codarq));    
$result_arqbanco = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
/**
 * Soma a quantidade de registros do tipo 
 * 0 HEader do Arquivo
 * if
 *   1 Header do Lote
 *     3 Registro Detalhe(A e B)
 *   5 Trailer do Lote
 * 9 Trailer do Arquivo
 */
$iQuantidadeRegistrosArquivo = 0;

if( $clrharqbanco->numrows > 0 ){

    db_fieldsmemory($result_arqbanco,0);

    include("dbforms/db_layouttxt.php");

    $posicao       = "A";
    $layoutimprime = 2;
    $idcaixa       = "P";
    $idcliente     = "P";
    $db90_codban   = $rh34_codban;
    $agenciaheader = $rh34_agencia;
    $contaheader   = $rh34_conta;
    $contalote     = $rh34_conta;
    $conveniobanco = trim($rh34_convenio); 
    $descrarquivo  = "FOLHA PAGAMENTO"; // Campo somente do layout 3

    $dvagenciaheader         = "0";
    $dvcontaheader           = "0";
    $dvagenciacontaheader    = " ";
    if (trim($rh34_dvagencia) != "") {
      $dvagenciaheader = $rh34_dvagencia[0];
    }
    if (trim($rh34_dvconta)!="") {

      $dvcontaheader  = $rh34_dvconta[0];
      $digitos        = strlen($rh34_dvconta);
      if ($digitos>1) {
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
    }

    $operacaoheader     = substr($contaheader,0,3);
    $contaheader2       = str_pad(trim(substr($contaheader,4,20)),8);
    $dvagencialote      = $dvagenciaheader;
    $dvcontalote        = $dvcontaheader;
    $dvagenciacontalote = $dvagenciacontaheader;

    $datageracao = $datagera; 
    $horageracao = date("H").':'.date("i").':'.date("s");
 
    if (isset($datageracao) && $datageracao!="") {
      $datag = split('-',$datageracao);
      $datag_dia = $datag[2];
      $datag_mes = $datag[1];
      $datag_ano = $datag[0];
    }

    if (isset($datadeposit) && $datadeposit!="") {
      $datad = split('-',$datadeposit);
      $datad_dia = $datad[2];
      $datad_mes = $datad[1];
      $datad_ano = $datad[0];
    }

    $sequencialarq  = $rh34_sequencial;
    $usoprefeitura1 = $rh34_sequencial;

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
 
    $sequenciaarqui = $rh34_sequencial;
    $versaodoarquiv = "030";

    $paramnome = $datag_mes.$datag_ano."_".$horageracao;
    $nomearquivo = "folha_".$db90_codban."_".$paramnome.".txt";
    $db_layouttxt = new db_layouttxt($layoutimprime,"tmp/".$nomearquivo, $posicao);

    $conveniobanco = trim($rh34_convenio); 
    ////// DADOS SOMENTE CNAB240 CEF
    $parametrotransmiss = substr($rh34_convenio,10,2);
    $indicaambcaixa   = "P";
    $indicaambcliente = "P";
    $densidadearquivo = "01600";
    //////

  
    $iQuantidadeRegistrosArquivo += 1; 
    db_setaPropriedadesLayoutTxt(&$db_layouttxt,1);

 ///// }

} else {
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}

if (!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")) {

  $rh34_wherefolha = "";
  $rh34_wherepensa = ""; 
} else {

  $rh34_wherefolha = $rh34_where." and ";
  $rh34_wherepensa = $rh34_where." and " ;
}

$rh34_wherefolha .= " r38_banco = '$rh34_codban' ";

$rh34_wherepensa .= " r52_codbco = '$rh34_codban' and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();

$titrelatorio = "Todos os funcionários";
$titarquivo   = "pagtofuncionarios";
$lPensionista = false;

if ($sqlerro == false) {

  if ($tiparq == 0) {

    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*,
                                               r70_descr, 
                                               case when trim(translate(r38_conta,'0','')) = '' then '02' 
                                                    when (select rh02_fpagto 
                                                            from rhpessoalmov
                                                           where rh02_regist = r38_regist
                                                             and rh02_anousu = ".db_anofolha()."
                                                            and rh02_mesusu     = ".db_mesfolha()."
                                                            and rh02_instit     = ".db_getsession("DB_instit").") = 4 then '02' 
                                                else '01' end as tipo_pagamento,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
                                              "r38_banco,tipo_pagamento, db83_conta",
                                              "$rh34_wherefolha");

    $result  = $clfolha->sql_record($sql);
    $numrows = $clfolha->numrows;
  } else {

      $titarquivo = "pensaojudicial";
      $titrelatorio = "PENSÃO JUDICIAL ";
    if ($qfolha == 1) {
      $campovalor = " r52_valor+r52_valfer ";
      $rh34_wherepensa .= " and (r52_valor > 0 or r52_valfer > 0 ) ";
    } else if ($qfolha == 2) {
      $campovalor = " r52_valcom ";
      $rh34_wherepensa .= " and r52_valcom > 0 ";
    } else if ($qfolha == 3) {
      $campovalor = " r52_val13 ";
      $rh34_wherepensa .= " and r52_val13 > 0 ";
    } else if ($qfolha == 4) {
      $campovalor = " r52_valres ";
      $rh34_wherepensa .= " and r52_valres > 0 ";
    }

    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as r38_liq, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia, contabancaria.*,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
                                               trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                               trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                               cgm.*,func.z01_nome as nomefuncionario,
                                               '01' as tipo_pagamento,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "cgm.z01_nome,r52_codbco",
                                              "$rh34_wherepensa and $campovalor > 0");

    $lPensionista = true;
    $result  = $clpensao->sql_record($sql);
    $numrows = $clpensao->numrows;
  }

  if ($numrows > 0) {
    
    db_fieldsmemory($result,0);

    $idregistroimpressao = $posicao;
    $nomearquivo_impressao = "/tmp/folha_".$db90_codban."_".$paramnome.".pdf";

    if (!is_writable("/tmp/")) {
      $sqlerro= true;
      $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
    }  

    ///// INICIA IMPRESSÃO DO RELATÓRIO
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt   = 4;

    $head3 = "ARQUIVO DE PAGAMENTO DA FOLHA";
    $head4 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head5 = "DATA DA GERAÇÃO  :  ".db_formatar($datagera,"d").' ÀS '.$horageracao.' HS';
    $head6 = "DATA DO PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    $head7 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;


    $finalidadedoc     = "00";
    $codigocompromisso = substr($rh34_convenio,6,4);
    $tipocompromisso   = "02";
    $agencialote       = $agenciaheader;

    
    $tiposervico = "30";

    if ($db90_codban == $r38_banco) {
      $formalancamento = "01";
    } else {
      $formalancamento = "03";
    }

    $sequencialnolote = 0;
  
    $troca_header     = 0;

    $quantidadefuncionarios = 0;
    $valortotal             = 0;
    $quantidaderegistarq    = 0;
   
    /**
     * Monta cabecalho para o PDF
     */
    $pdf->addpage("L");
    $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
    if ($tiparq < 5) {

      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
      $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
    } else {

      $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
      $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
    }

    $pdf->cell(17,$alt,$RLr38_liq,1,0,"C",1);
    $pdf->cell(13,$alt,$RLr38_agenc,1,0,"C",1);
    $pdf->cell(20,$alt,$RLr38_conta,1,0,"C",1);
    $pdf->cell(20,$alt,$RLdb83_tipoconta,1,1,"C",1);

    $pdf->ln(3);


    /**
     * Cria um array associativo com os codigos do tipo de conta do
     * sistema para o codigos definidos pelo FEBRABAN.
     * 1 => 01 -> Conta Corrente
     * 2 => 05 -> Conta Poupança
     * 4 => 04 -> Conta Salário
     * @var array Tipos de conta
     */
    $aTiposConta = array(
      '1' => '01', 
      '2' => '05', 
      '4' => '04',
      '0' => '02'//SEM CONTA/CONTA_ZERADA
    );

    /**
     * Percorre os tipos de conta para 
     * verificar se possui dados na tabela folha, 
     * se existir monta o Lote com os dados do tipo de conta
     */
    $iLote = 0;

    foreach ( $aTiposConta as $iTipoConta => $iCodigoTipoConta ) {

      $valortotallote = 0;

      if ($lPensionista) {

        $sCampos = "r52_codage || r52_dvagencia as r38_agenc, r52_conta || r52_dvconta as r38_conta, $campovalor as r38_liq,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, 
        contabancaria.*, 
        r70_descr, 
        rh01_regist as r38_regist,
        db89_codagencia,
        db89_digito, ";
        $sCampos .= "case when trim(translate(r38_conta,'0','')) = '' then '02'                     ";
        $sCampos .= "     when (select rh02_fpagto                                                  ";
        $sCampos .= "             from rhpessoalmov                                                 ";
        $sCampos .= "            where rh02_regist = r38_regist                                     ";
        $sCampos .= "              and rh02_anousu = " . db_anofolha();
        $sCampos .= "              and rh02_mesusu = " . db_mesfolha();
        $sCampos .= "              and rh02_instit = " . db_getsession("DB_instit").") = 4 then '02'";
        $sCampos .= " else '01' end as tipo_pagamento,                                              ";
        $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                           ";
        $sCampos .= "r38_liq as valorori                                                            ";  
        $sWhere   = $rh34_wherepensa;

        if ( $iTipoConta != "0") {
          $sWhere  .= " and db83_tipoconta = $iTipoConta";      
        }
        $sSql     = $clpensao->sql_query_gerarqbag(null, null, null, null, $sCampos, "cgm.z01_nome,r52_codbco", $sWhere);
      } else {

        $sCampos = "folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*, r70_descr, 
        db89_codagencia,
        db89_digito, ";
        $sCampos .= "case when trim(translate(r38_conta,'0','')) = '' then '02'                                       ";
        $sCampos .= "     when (select rh02_fpagto                                                                    ";
        $sCampos .= "             from rhpessoalmov                                                                   ";
        $sCampos .= "            where rh02_regist = r38_regist                                                       ";
        $sCampos .= "              and rh02_anousu = " . db_anofolha()                                                 ;
        $sCampos .= "              and rh02_mesusu = " . db_mesfolha()                                                 ;
        $sCampos .= "              and rh02_instit = " . db_getsession("DB_instit").") = 4 then '02'                  ";
        $sCampos .= " else '01' end as tipo_pagamento,                                                                ";
        $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                                             ";
        $sCampos .= "r38_liq as valorori                                                                              ";
        $sWhere    = $rh34_wherefolha;

        if ( $iTipoConta != "0") {
          $sWhere   .= " and db83_tipoconta = $iTipoConta";      
        }
        $sSql      = $clfolha->sql_query_gerarqbag(null, $sCampos, "cgm.z01_nome,r38_banco,tipo_pagamento, db83_conta", $sWhere);
      }
      $rsDados   = db_query($sSql);
      if ( pg_num_rows($rsDados) == 0){
        continue;
      }



      /**
       * Percorre os servidores
       */
      $troca_header = 0;

      $formalancamento = $iCodigoTipoConta;
      $iQuantidadeServidoresLote = 0;
      $quantidadetotallote       = 0;
//      db_criatabela($rsDados);
      $iQuantidadeServidoresLote = pg_num_rows($rsDados);
//echo " SQL : $sSql <br>";
      $tipo_pagamento            = $iCodigoTipoConta;

      $lGerarHeaderLote = true;
      $lGerarTraillerLote = false;
      $iTotalizadorLote = 0;
      for ( $iServidor = 0; $iServidor < $iQuantidadeServidoresLote; $iServidor++ ) {

        db_fieldsmemory($rsDados, $iServidor);
         
        if (   ($iTipoConta == "0" && $tipo_pagamento != "02") 
          || ($iTipoConta != "0" && $tipo_pagamento == "02") ) {
          continue;
        }

          $iTotalizadorLote+=2;

        if ($lGerarHeaderLote) {
         
          $iLote++;
          $loteservico = $iLote;
          $iTotalizadorLote++;
          db_setaPropriedadesLayoutTxt(&$db_layouttxt, 2);
          $iQuantidadeRegistrosArquivo += 1; 
          $lGerarHeaderLote = false;
          $lGerarTraillerLote = true;
        }

        if ($tipo_pagamento == "02") {
          $r38_banco = '';
        }
        //////////////////////////////////////////////
        $formalancamento = $tipo_pagamento;
        $agencia         = db_formatar(str_replace('.','',str_replace('-','',$db89_codagencia)),'s','0', 5,'e',0);
        $conta           = trim(str_replace(',','',str_replace('.','',str_replace('-','',$db83_conta))));
        $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1 é do dvconta e -3 do codigooperacao
        $dvconta         = substr($conta,-1);
        $codigooperacao  = substr($conta,0,3);
        $conta           = substr($conta, 3, $qtddigitosconta);
        //////////////////////////////////////////////
        //////////////////////////////////////////////
        $quantidaderegistarq ++;
        if ($troca_header != $tipo_pagamento) {
          
          if ($troca_header != 0) {
            $quantidadetotallote = $sequencialnolote + 2;
            //$valortotallote = $valortotal;
            ///// TRAILLER DE LOTE
            
            ///// FINAL DO TRAILLER DE LOTE
            $sequencialnolote = 0;
            $valortotallote   = 0;
            $quantidaderegistarq ++;
          }

          ///// HEADER DO LOTE
          $loteservico     = $iLote;
          $formalancamento = $tipo_pagamento;
          
          ///// FINAL DO HEADER DO LOTE
          $troca_header       = $tipo_pagamento;
          $sequencialnolote   = 0;
          $quantidaderegistarq ++;
        }
        //////////////////////////////////////////////
        // CAMPOS LAYOUT CNAB240
        $sequencialnolote ++;
        $compensacao = "700";
        if($r38_banco == $db90_codban){
          $compensacao = str_repeat('0',3);
        }

        $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$db89_codagencia)),'s','0', 6,'e',0);
        $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$db83_conta)),'s','0',15,'e',0);

        $agenciapagar = substr($agenciapagarT,0,5);
        $digitoagenci = substr($agenciapagarT,5,1);

        
        $contasapagar = substr($contasapagarT,0,14)+0;
        $digitocontas = substr($contasapagarT,14,1);

        $contasapagar = db_formatar($contasapagar,'s','0',12,'e',0);

        $bancofavorecido     = $r38_banco;
        $agenciafavorecido   = $agenciapagar;
        $dvagenciafavorecido = $digitoagenci;
        $contafavorecido     = $contasapagar;
        $db83_dvconta        = trim($db83_dvconta);
        $dvcontafavorecido   = $db83_dvconta[0];// $digitocontas[0];

        $dvagenciacontafav   = " ";
        if (strlen($digitocontas) == 2){
            $dvagenciacontafav = $db83_dvconta[1];
        }


        $numerocontrolemov   = $r38_regist;
        $sequencialreg       = $iServidor + 1;
        //echo "<br> matricula --> $numerocontrolemov   banco --> $bancofavorecido   agencia --> $agenciafavorecido   dvagencia --> $dvagenciafavorecido   conta --> $contafavorecido  dvconta --> $dvcontafavorecido ";
        //////////////////////////////////////////////
        //////////////////////////////////////////////
        
        $valordebito = $r38_liq;
        $dataprocessamento = $datadedeposito;
  //      $sequencialreg = "      ";
        ///// REGISTRO A
         
        
        $agenciafavorecido            = $db89_codagencia;
        $dvagenciafavorecido          = $db89_digito[0];
        $contafavorecido              = $db83_conta;
        $dvcontafavorecido            = $db83_dvconta[0];
        $dvagenciacontafav            = isset($db83_dvconta[1]) ? $db83_dvconta[1] : " ";
        $iQuantidadeRegistrosArquivo += 1; 
        db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, $posicao);

        ///// FINAL DO REGISTRO A
       /**
        * Grava Registro N
        */
        //db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, 'B');
        if($tam == 11){
          $tipoinscricaofav = "1";
        }else if($tam == 14){
          $tipoinscricaofav = "2";
        }else{
          $tipoinscricaofav = "3";
        }
        $datavencimento = $datadedeposito;
        $valorvencimento = $r38_liq;
      $sTipoConta      = '';
      switch ($db83_tipoconta) {
      
      case '1':
        $sTipoConta = "Conta Corrente";
        break;
      case '2':
        $sTipoConta = "Conta Poupança";
        break;
      case '3':
        $sTipoConta = "Conta Aplicação";
        break;
      case '4':
        $sTipoConta = "Conta Salário";
        break;

      }
        //$db90_codban
        ///// REGISTRO B
        ///
        $sequencialnolote++;
        $iQuantidadeRegistrosArquivo += 1;    
        db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, "");
        ///// FINAL DO REGISTRO B

        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage("L");
          $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
          if ($tiparq < 5) {

            $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
            $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
            $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
            if(strlen($RLr70_descr) > 44) {
              $pdf->cell(65,$alt,substr($RLr70_descr,0,30).'...',1,0,"C",1);
            } else {
            $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
            }
          } else {

            $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
            $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
            $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
            $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          }

          $pdf->cell(17,$alt,$RLr38_liq,1,0,"C",1);
          $pdf->cell(13,$alt,$RLr38_agenc,1,0,"C",1);
          $pdf->cell(20,$alt,$RLr38_conta,1,0,"C",1);
          $pdf->cell(20,$alt,$RLdb83_tipoconta,1,1,"C",1);
          $pdf->ln(3);
        }

        $pdf->setfont('arial','',7);
        $pdf->cell(15,$alt,$r38_regist,1,0,"C",0);
        
        if ($tiparq < 5) {

          $pdf->cell(15, $alt, $z01_numcgm, 1, 0, "C", 0);
          $pdf->cell(20, $alt, $z01_cgccpf, 1, 0, "C", 0);
          $pdf->cell(65, $alt, $z01_nome  , 1, 0, "L", 0);
          if (strlen($r70_descr) > 44){
            $pdf->cell(65, $alt, substr($r70_descr,0,30).'...' , 1, 0, "L", 0);
          } else {
            $pdf->cell(65, $alt, $r70_descr, 1, 0, "L", 0);
          }
        } else {

          $pdf->cell(65, $alt, $z01_nome       , 1, 0, "L", 0);
          $pdf->cell(65, $alt, $nomefuncionario, 1, 0, "L", 0);
          $pdf->cell(15, $alt, $z01_numcgm     , 1, 0, "C", 0);
          $pdf->cell(20, $alt, $z01_cgccpf     , 1, 0, "C", 0);
        }

        $pdf->cell(17,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
        $pdf->cell(13,$alt,$r38_agenc,1,0,"R",0);
        $pdf->cell(20,$alt,$r38_conta,1,0,"R",0);
        $pdf->cell(20,$alt,$sTipoConta,1,1,"L",0);

        $quantidadefuncionarios ++;
        
        $valortotallote += $r38_liq;
     }

     $valortotal         += $valortotallote;
     //echo " final do for $iQuantidadeServidoresLote <br>";
 
     if ($lGerarTraillerLote) {
       $iTotalizadorLote++;
       $quantidadetotallote = $iTotalizadorLote; //$iQuantidadeServidoresLote;
       $iQuantidadeRegistrosArquivo += 1;
       //echo $quantidadetotallote."<br>";
       db_setaPropriedadesLayoutTxt(&$db_layouttxt, 4);
       $lGerarTraillerLote = false;
     }

    }


    $pdf->setfont('arial','b',8);
    $pdf->cell(230,$alt,'TOTAL DE FUNCIONÁRIOS',1,0,"C",1);
    $pdf->cell(20,$alt,$quantidadefuncionarios,1,1,"R",1);

    $pdf->cell(230,$alt,'TOTAL GERAL',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,'f'),1,1,"R",1);

    $quantidadetotallote = $sequencialnolote + 2;
    $quantidaderegistarq ++;
  

    // VARIAVEIS PARA TRAILLER CEF
    $quanttrailler = $quantidadefuncionarios + 2;
    $valortrailler = $valortotal;
    $sequencialreg += 1;
    //////////////////////////////////

    ///// TRAILLER DE ARQUIVO
    $loteservico = '9999';
    
    $iQuantidadeRegistrosArquivo += 1;
    $quantidaderegistarq  = $iQuantidadeRegistrosArquivo;
    $quantidadelotesarq   = $iLote;
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////

    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}

// exit ;

  if($sqlerro == false){
    echo "
    <script>
      parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao');
    </script>
    ";
  }else{
    echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
  }

  db_fim_transacao($sql);

?>
