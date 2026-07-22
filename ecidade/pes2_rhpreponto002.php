<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oLoteRegistroPonto = new cl_loteregistroponto;

$sCampos  = "loteregistroponto.rh155_ano as ano,";;
$sCampos .= "lpad(trim(to_char(loteregistroponto.rh155_mes,'99')),2,'0') as mes,";
$sCampos .= "loteregistroponto.rh155_descricao,";
$sCampos .= "db_usuarios.nome,";
$sCampos .= "db_usuarios.login,";
$sCampos .= "case rh155_situacao ";
$sCampos .= "    when 'A' then 'ABERTO'";
$sCampos .= "    when 'C' then 'CONFIRMADO'";
$sCampos .= "    when 'F' then 'FECHADO'";
$sCampos .= "    when 'C' then 'CONFIRMADO' ";
$sCampos .= "end as situacao";

$rsPrincipal = $oLoteRegistroPonto->sql_record($oLoteRegistroPonto->sql_query($lote,$sCampos));

$oPrincipal = db_utils::fieldsMemory($rsPrincipal,0);

$head2 = "LOTE: ".$oPrincipal->rh155_descricao;
$head4 = "PERIODO : ".$oPrincipal->ano.'/'.$oPrincipal->mes.' - SITUACAO : '.$oPrincipal->situacao ;
$head5 = "USUARIO : ".$oPrincipal->login." - ".$oPrincipal->nome;

$sSqlRhPrePontoLoteRegistro = "
        select  rhpessoal.rh01_regist,
                cgm.z01_nome,
                rhpreponto.rh149_rubric,
                rhpreponto.rh149_quantidade,
                rhpreponto.rh149_valor,
                rhpreponto.rh149_competencia 
        from rhprepontoloteregistroponto 
             inner join loteregistroponto on loteregistroponto.rh155_sequencial = rhprepontoloteregistroponto.rh156_loteregistroponto 
             inner join rhpreponto        on rhpreponto.rh149_sequencial        = rhprepontoloteregistroponto.rh156_rhpreponto 
             inner join rhpessoal         on rhpessoal.rh01_regist      = rhpreponto.rh149_regist 
             inner join cgm               on rhpessoal.rh01_numcgm      = cgm.z01_numcgm 
        where loteregistroponto.rh155_sequencial = $lote
        order by rhpreponto.rh149_sequencial
       ";

$rsRhPrePontoLoteRegistro = db_query($sSqlRhPrePontoLoteRegistro);
$xxnum = pg_numrows($rsRhPrePontoLoteRegistro);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Erro na pesquisa do lote - '.$lote);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$iTotal     = 0;
$iTotQuant = 0;
$iTotVal   = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($rsRhPrePontoLoteRegistro);$x++){
   
   $oRhPrePontoLoteRegistro = db_utils::fieldsMemory($rsRhPrePontoLoteRegistro,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(30,$alt,'QUANTIDADE',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR',1,0,"C",1);
      $pdf->cell(30,$alt,'DATA LIMITE',1,1,"C",1);
       
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }

   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$oRhPrePontoLoteRegistro->rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$oRhPrePontoLoteRegistro->z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,$oRhPrePontoLoteRegistro->rh149_rubric,0,0,"C",$pre);
   $pdf->cell(30,$alt,db_formatar($oRhPrePontoLoteRegistro->rh149_quantidade,'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($oRhPrePontoLoteRegistro->rh149_valor, 'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,$oRhPrePontoLoteRegistro->rh149_competencia,0,1,"C",$pre);
   
   $iTotal += 1;
   $iTotQuant += $oRhPrePontoLoteRegistro->rh149_quantidade;
   $iTotVal   += $oRhPrePontoLoteRegistro->rh149_valor;
}

$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL DE REGISTROS :  '.$iTotal,"T",0,"C",0);
$pdf->cell(30,$alt,db_formatar($iTotQuant,'f'),"T",0,"R",0);
$pdf->cell(30,$alt,db_formatar($iTotVal,'f'),"T",0,"R",0);
$pdf->cell(30,$alt,'',"T",1,"R",0);

$pdf->Output();
   

