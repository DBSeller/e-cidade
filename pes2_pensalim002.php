<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("libs/db_sql.php");
include("classes/db_selecao_classe.php");
$clselecao = new cl_selecao();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$head2 = "Resumo de Pensões Alimentícias";
$head4 = "Período : ".$mes." / ".$ano;

$sWhere = " ";

if (trim($selecao) != "") {

  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao,db_getsession("DB_instit")));

  if ($clselecao->numrows > 0) {

    db_fieldsmemory($result_selecao, 0);
    $sWhere = " and ".$r44_where;
    $head8 = "Seleção: ".$selecao." - ".$r44_descr;
  }
}

if ($tipo == 's') {

  $head6  = "Salário ";
  $valor = " r52_valor + r52_valfer "; 
} else if($tipo == 'c') {

  $head6 = "Complementar ";
  $valor = " r52_valcom"; 
} else if($tipo == '3') {

  $head6 = "13º.  Salário ";
  $valor = " r52_val13 "; 
} else if($tipo == 'r') {

  $head6 = "Rescisão ";
  $valor = " r52_valres "; 
}

if ($ordem == 'n') {
  $ordem = " order by rh01_regist ";
} else {

  if ($func == 's') {
    $ordem = " order by z01_nome, codigo_banco, codigo_agencia ";
  } else {
    $ordem = " order by  codigo_banco, codigo_agencia, nome_beneficiario";
  }
}

$sSql = "
select * from 
(
       select case when trim(r52_codbco) = '' or r52_codbco is null 
                   then '000' 
		   else r52_codbco 
	      end as codigo_banco,
              case when db90_descr is not null 
	           then db90_descr 
		   else 'SEM BANCO' 
	      end as descricao_banco,
              to_char(to_number(case when trim(r52_codage) = '' 
	                     then '0' 
			     else r52_codage 
			end,'99999'),'99999') as codigo_agencia,
	      case when r52_dvagencia is null 
	           then '' 
		   else r52_dvagencia 
	      end as r52_dvagencia,
		   r52_conta as conta,
	      case when r52_dvconta is null 
	           then '' 
		   else r52_dvconta 
	      end as r52_dvconta,
	      r52_numcgm as cgm_beneficiario,
	      cgm.z01_nome as nome_beneficiario,
	      a.z01_nome,
	      rh01_regist,
	      $valor as w01_work05 
       from pensao
            inner join cgm       on r52_numcgm = z01_numcgm
      	    inner join rhpessoal on rh01_regist = r52_regist
						inner join rhpessoalmov on rh01_regist = rh02_regist 
						                       and rh02_anousu = ".db_anofolha()." 
						                       and rh02_mesusu = ".db_mesfolha()." 
                                   and rh02_instit = ".db_getsession("DB_instit")."
            inner join rhlota       on r70_codigo  = rh02_lota
                                   and r70_instit  = rh02_instit
	          inner join cgm a     on a.z01_numcgm = rh01_numcgm
	          left  join db_bancos on r52_codbco::varchar(10) = db90_codban
       where r52_anousu = $ano 
         and r52_mesusu = $mes 
         $sWhere

	 and $valor > 0
) as x
group by descricao_banco,codigo_banco,codigo_agencia,r52_dvagencia,conta, r52_dvconta, cgm_beneficiario, nome_beneficiario,rh01_regist, x.z01_nome,x.w01_work05
$ordem
       ";
$result = db_query($sSql);
$iNumeroLinhas = pg_numrows($result);
if ($iNumeroLinhas == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem lancamentos no periodo de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$alt = 5;
$total = 0;
$total_g = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

db_fieldsmemory($result,0);

if($func != 's'){
  
  if($tipoquebra == 'a'){
    $quebra = substr($codigo_banco,0,3).$codigo_agencia;
  }else{  
    $quebra = substr($codigo_banco,0,3);
  }
  $troca = 0;

  for($x = 0; $x < pg_numrows($result);$x++){
     
     db_fieldsmemory($result,$x);

     if ($quebra != substr($codigo_banco,0,3).$codigo_agencia && $tipoquebra == 'a') {

        $pdf->setfont('arial','b',8);
        $pdf->cell(122,$alt,'Total da Agência',"T",0,"C",0);
        $pdf->cell(40,$alt,'',"T",0,"C",0);
        $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $pdf->sety(300);
        $total = 0;
        $quebra = substr($codigo_banco,0,3).$codigo_agencia;
     }

     if ($quebra != substr($codigo_banco,0,3) && $tipoquebra != 'a') {

        $pdf->setfont('arial','b',8);
        $pdf->cell(122,$alt,'Total do Banco',"T",0,"C",0);
        $pdf->cell(40,$alt,'',"T",0,"C",0);
        $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $pdf->sety(300);
        $total = 0;
        $quebra = substr($codigo_banco,0,3);
     }

     if ($pdf->gety() > $pdf->h - 30 || $troca == 0) {

        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        if ($tipoquebra == 'a') {
          $pdf->cell(80,$alt,$descricao_banco.' - Agência: '.$codigo_agencia,0,1,"L",0);
        } else {
          $pdf->cell(80,$alt,$descricao_banco,0,1,"L",0);
        }
        $pdf->ln(3);
        $pdf->cell(122,$alt,'Nome do Beneficiário',1,0,"C",1);
        $pdf->cell(20,$alt,'Agência',1,0,"C",1);
        $pdf->cell(20,$alt,'Conta',1,0,"C",1);
        $pdf->cell(30,$alt,'Valor',1,1,"C",1);
        $troca = 1;
     }

     $pdf->setfont('arial','',7);
     $pdf->cell(122, $alt, $nome_beneficiario,0,0,"l",0);
     $pdf->cell(20, $alt, $codigo_agencia.$r52_dvagencia, 0, 0, "R", 0);
     $pdf->cell(20, $alt, $conta.$r52_dvconta, 0, 0, "R", 0);
     $pdf->cell(30, $alt, db_formatar($w01_work05,'f'), 0, 1, "R", 0);
     $total   += $w01_work05;
     $total_g += $w01_work05;
  }

  $pdf->setfont('arial','b',8);
  if ($tipoquebra == 'a') {
    $pdf->cell(122,$alt,'Total da Agência',"T",0,"C",0);
  } else {
    $pdf->cell(122,$alt,'Total do Banco',"T",0,"C",0);
  }
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

  $pdf->ln(5);
  $pdf->cell(122,$alt,'Total do Geral',"T",0,"C",0);
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}else{

  $troca = 0;

  for ($x = 0; $x < pg_numrows($result);$x++) {
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca == 0) {

        $pdf->addpage('L');
        $pdf->setfont('arial','b',8);
        $pdf->ln(3);
        $pdf->cell(15,$alt,'Matr',1,0,"C",1);
        $pdf->cell(80,$alt,'Nome do Funcionário',1,0,"C",1);
        $pdf->cell(15,$alt,'CGM',1,0,"C",1);
        $pdf->cell(80,$alt,'Nome do Beneficiário',1,0,"C",1);
        $pdf->cell(10,$alt,'Banco',1,0,"C",1);
        $pdf->cell(20,$alt,'Agência',1,0,"C",1);
        $pdf->cell(20,$alt,'Conta',1,0,"C",1);
        $pdf->cell(30,$alt,'Valor',1,1,"C",1);
        $troca = 1;
     }

     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$rh01_regist,0,0,"l",0);
     $pdf->cell(80,$alt,$z01_nome,0,0,"l",0);
     $pdf->cell(15,$alt,$cgm_beneficiario,0,0,"l",0);
     $pdf->cell(80,$alt,$nome_beneficiario,0,0,"l",0);
     $pdf->cell(10,$alt,$codigo_banco,0,0,"l",0);
     $pdf->cell(20,$alt,$codigo_agencia.$r52_dvagencia,0,0,"R",0);
     $pdf->cell(20,$alt,$conta.$r52_dvconta,0,0,"R",0);
     $pdf->cell(30,$alt,db_formatar($w01_work05,'f'),0,1,"R",0);
     $total += $w01_work05;
     $total_g += $w01_work05;
  }

  $pdf->ln(5);
  $pdf->cell(200,$alt,'TOTAL GERAL',"T",0,"C",0);
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}

$sName = 'tmp/pensaoAlimenticia' . date('YmdHms') . '.pdf';
$pdf->Output($sName, false);
