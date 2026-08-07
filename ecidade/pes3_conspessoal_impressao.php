<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

use \ECidade\Pdf\Pdf;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));

$cldb_config = new cl_db_config();

$clpessoal = new cl_pessoal;
$clpessoal->rotulo->label();

$clcadferia = new cl_cadferia;
$clcadferia->rotulo->label();

$clcgm = new cl_cgm;
$clcgm->rotulo->label();

$cldepend = new cl_rhdepend;
$cldepend->rotulo->label();

$clrhpesprop = new cl_rhpesprop;
$clrhpesprop->rotulo->label();

$clafasta = new cl_afasta;
$clafasta->rotulo->label();

$clrhpessoal = new cl_rhpessoal;
$clrhpessoal->rotulo->label();

$clrhtipoapos = new cl_rhtipoapos;
$clrhtipoapos->rotulo->label();

$clrhpessoalmov = new cl_rhpessoalmov;
$clrhpessoalmov->rotulo->label();

$clrhcargo = new cl_rhcargo;
$clrhcargo->rotulo->label();

$clrhpesorigem = new cl_rhpesorigem;
$clrhpesorigem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_nomeorigem');
$clrotulo->label('z01_ender');
$clrotulo->label('z01_munic');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_cgccpf');
$clrotulo->label('z01_email');
$clrotulo->label('z01_telcel');
$clrotulo->label('r13_descr');
$clrotulo->label('r37_descr');
$clrotulo->label('rh55_descr');
$clrotulo->label("rh19_propi");
$clrotulo->label("rh01_clas2");

parse_str($_SERVER['QUERY_STRING']);


$isInstituicaoPrevidencia = false;
$sSqlInstPrev = $cldb_config->sql_query_file(null, "codigo", null, "db21_codsiconfi = '10132'");
$rsInstPrev = $cldb_config->sql_record($sSqlInstPrev);
if ($cldb_config->numrows > 0) {
    if (db_getsession("DB_instit") == db_utils::fieldsMemory($rsInstPrev, 0)->codigo) {
      $isInstituicaoPrevidencia = true;
    }
}

if (isset($consulta)) {
    if (isset($locali) && trim($locali) != "" && isset($localf) && trim($localf) != "") {
        $head5 = "INTERVALO: ".$locali." até ".$localf;
        $where = " and rh55_estrut between '$locali'  and '$localf'";
    } elseif (isset($locali) && trim($locali) != "") {
        $head5 = "LOCAIS MAIORES OU IGUAL A ".$locali;
        $where = " and rh55_estrut >= '$locali'";
    } elseif (isset($localf) && trim($localf) != "") {
        $head5 = "LOCAIS MENORES OU IGUAL A ".$localf;
        $where = " and rh55_estrut <= '$localf'";
    } elseif (isset($selloc) && trim($selloc) != "") {
        $head5 = "LOCAIS: ".$selloc;
        $where = " and rh55_estrut in ('".str_replace(",", "','", $selloc)."')";
    }

    if (isset($atinpen)) {
        if ($atinpen == "a") {
            $where = " and rh30_vinculo = 'A' ";
        } elseif ($atinpen == "i") {
            $where = " and rh30_vinculo = 'I' ";
        } elseif ($atinpen == "p") {
            $where = " and rh30_vinculo = 'P' ";
        } elseif ($atinpen == "ip") {
            $where = " and rh30_vinculo <> 'A' ";
        }
    }

    if (trim($anofolha) == "" || trim($mesfolha) == "") {
        $ano = db_anofolha();
        $mes = db_mesfolha();
    } else {
        $ano = $anofolha;
        $mes = $mesfolha;
    }
} else {
    $where = " and rh01_regist = $regist ";
}

$where = "where 1 = 1 ".$where;

$head3 = "CADASTRO DO FUNCIONÁRIO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "select *,
               case rh30_regime
                  when 1 then 'Estatutário'
                  when 2 then 'Celetista'
                  when 3 then 'Extra-Quadro'
               end as descr_regime,
               case rh30_vinculo
                  when 'A' then 'Ativo'
                  when 'I' then 'Inativo'
                  when 'P' then 'Pensionista'
               end as descr_vinculo,
               rh37_cbo as cbo,
               case rh02_vincrais
                  when 10 then 'CLT'
                  when 30 then 'Servidor Público'
                  when 35 then 'Servidor Público Não Efetivo'
                  when 40 then 'Trabalhador Avulso'
                  when 90 then 'Contrato'
               end as descr_vinculorais,
               h13_descr as descr_contrato,
               rh01_observacao as observacao,
               rh01_regist as regist,
               (select r02_descr 
                  from padroes 
                 where r02_codigo  = rh03_padraoprev 
                   and r02_anousu  = rh03_anousu 
                   and r02_mesusu  = rh03_mesusu 
                   and rh03_regime = r02_regime
                   and r02_instit  = rh02_instit) as padraosecundario,
               (select max(rh164_datafim) 
                  from rhcontratoemergencial 
                       inner join rhcontratoemergencialrenovacao on rh163_sequencial = rh164_contratoemergencial
                 where rh163_matricula = rh01_regist) as rh164_datafim
          from rhpessoal
               inner join rhpessoalmov   on rh02_anousu     = $ano
                                        and rh02_mesusu     = $mes
                                        and rh02_regist     = rh01_regist
                                        and rh02_instit     = ".db_getsession("DB_instit")."
               inner join rhregime       on rh30_codreg     = rh02_codreg
                                        and rh30_instit     = rh02_instit
                left join rhtipoapos     on rh88_sequencial = rh02_rhtipoapos
               inner join cgm            on z01_numcgm      = rh01_numcgm
                left join rhfuncao       on rh37_funcao     = rh01_funcao
                                        and rh37_instit     = rh02_instit 
                left join rhlota         on r70_codigo      = rh02_lota
                                        and r70_instit      = rh02_instit
                left join rhpescargo     on rh20_seqpes     = rh02_seqpes
                left join rhcargo        on rh20_cargo      = rh04_codigo
                                        and rh04_instit     = rh02_instit
                left join rhpesbanco      on rh44_seqpes     = rh02_seqpes
                left join rhpespadrao     on rh02_seqpes     = rh03_seqpes
                left join padroes         on  r02_anousu     = rh02_anousu 
                                         and  r02_mesusu     = rh02_mesusu
                                         and  r02_regime     = rh30_regime
                                         and  r02_codigo     = rh03_padrao
                                         and  r02_instit     = rh02_instit
                left join rhpesfgts       on rh01_regist     = rh15_regist
                left join rhpesdoc        on rh01_regist     = rh16_regist
                left join rhpesrescisao   on rh02_seqpes     = rh05_seqpes
                left join tpcontra        on h13_codigo      = rh02_tpcont
                                         and h13_regime      = rh30_regime
                left outer join (select distinct r33_codtab,r33_nome 
                                   from inssirf 
                                  where r33_anousu = {$ano}
                                    and r33_mesusu = {$mes} 
                                    and r33_instit = ".db_getsession("DB_instit").") as x on r33_codtab = rh02_tbprev+2 
        {$where}";
$result = db_query($sql);
$xxnum = pg_num_rows($result);
if ($xxnum == 0 || $xxnum==false) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);
}

$alt="5";
$pdf = new Pdf();
$pdf->init();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 12);

$mostraCabecalhoDadosCadastrais = true;
$mostraCabecalhoOutrosDados     = true;
$mostraCabecalhoDocumentos      = true;

$iContadorColunas = 0;

if (isset($mostraCabecalhoDadosCadastrais)) {
$pdf->cell(180, $alt, "DADOS CADASTRAIS DO FUNCIONÁRIO", 0, 0, "C", 1);
$pdf->ln();
}

$fonte01="arial";
$tam01="9";
$b01="b";
$fonte02="times";
$tam02="9";
$b02="";
$fonte03="times";
$tam03="7";
$b03="";

for ($index=0; $index<$xxnum; $index ++) {

  db_fieldsmemory($result, $index);

  if (isset($mostraRegist)) {

    $pdf->setfont($fonte01, $b01, $tam01);
    $pdf->cell(35, $alt, $RLr01_regist.":", 0, 0, "R", 0);
    $pdf->setfont($fonte02, '', $tam02);
    $pdf->cell(60, $alt, $rh01_regist, 0, 0, "L", 0);

    $iContadorColunas++;
   
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraEndereco)) {

    $pdf->setfont($fonte01, $b01, $tam01);
    $pdf->cell(35, $alt, $RLz01_ender.":", 0, 0, "R", 0);
    $pdf->setfont($fonte02, $b02, $tam02);
    $pdf->cell(60, $alt, $z01_ender.', '.$z01_numero.' '.$z01_compl, 0, 0, "L", 0);

    $iContadorColunas++;
   
  }
  
  if ($iContadorColunas == 2) {
    $iContadorColunas = 0;
    $pdf->ln();
  }

  if (isset($mostraCgm)) {

    $pdf->setfont($fonte01, $b01, $tam01);
    $pdf->cell(35, $alt, $RLz01_numcgm.":", 0, 0, "R", 0);
    $pdf->setfont($fonte02, $b02, $tam02);
    $pdf->cell(60, $alt, $z01_numcgm, 0, 0, "L", 0);
    $iContadorColunas++;
   
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraBairro)) {
      
   $pdf->setfont($fonte01,$b01,$tam01);
   $pdf->cell(35,$alt,$RLz01_bairro.":",0,0,"R",0);
   $pdf->setfont($fonte02,$b02,$tam02);
   $pdf->cell(60,$alt,$z01_bairro,0,0,"L",0);
   
   $iContadorColunas++;
   
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraNome)) {
    
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLz01_nome.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
    
    $iContadorColunas++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraCep)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLz01_cep.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$z01_cep,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraNascimento)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_nasc.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,db_formatar($rh01_nasc,'d'),0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraMunicipio)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLz01_munic.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$z01_munic,0,0,"L",0);
    $iContadorColunas ++;
  }

  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraRegime)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_regime.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh30_regime.'-'.$descr_regime,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraLotacao)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_lotac.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$r70_estrut.'-'.substr($r70_descr,0,25),0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraVinculo)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_tpvinc.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh30_vinculo-$descr_vinculo",0,0,"L",0);
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraPrevidencia)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_tbprev.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh02_tbprev-$r33_nome",0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraCBO)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_cbo.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh37_cbo,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraAdmissao)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_admiss.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraVinculoRais)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_vincul.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh02_vincrais-$descr_vinculorais",0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraFuncao)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_funcao.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh01_funcao-$rh37_descr",0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraPadrao)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_padrao.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh03_padrao.'-'.$r02_descr,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraCargo)) {
      
    $pdf->setfont($fonte01,$b01,$tam02);
    $pdf->cell(35,$alt,$RLrh04_descr.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh04_codigo-$rh04_descr",0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraPadraoSecundario)) {
    
    $pdf->setfont($fonte01,$b01,$tam02);
    $pdf->cell(35,$alt,"Padrão Secundário:",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh03_padraoprev-$padraosecundario",0,0,"L",0);
    
    $iContadorColunas ++;
    
  }

  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraHrsMensais)) {
    
    $pdf->setfont($fonte01,$b01,$tam02);
    $pdf->cell(35,$alt,$RLr01_hrsmen.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh02_hrsmen,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraHrsSemanais)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_hrssem.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh02_hrssem,0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraBanco)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_banco.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,trim($rh44_codban)."/".trim($rh44_agencia)."-".trim($rh44_dvagencia),0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraConta)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_contac.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,trim($rh44_conta)."-".trim($rh44_dvconta),0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraTipoContrato)) {
      
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_tpcont.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,substr($h13_tpcont."-".$descr_contrato,0,45),0,0,"L",0);
    
    $iContadorColunas ++;
    
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraTerminoContrato)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,"Término Contr. Temp. :",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,db_formatar($rh164_datafim,'d'),0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraPai)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,"Pai".":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$z01_pai,0,0,"L",0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraMae)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,'Mãe'.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60, $alt, $z01_mae, 0, 0, "L", 0);
    
    $iContadorColunas ++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraTipoAposentadoria)) {
      
    if ( !empty($rh02_rhtipoapos) ) {
    	
      $pdf->ln();
            
  	  $pdf->setfont($fonte01,$b01,$tam01);
  	  $pdf->cell(35,$alt,$RLrh02_rhtipoapos.":",0,0,"R",0);
  	  $pdf->setfont($fonte02,$b02,$tam02);
  	  $pdf->cell(60,$alt,"$rh02_rhtipoapos-$rh88_descricao",0,0,"L",0);
  	  
  	  if ( !empty($rh02_validadepensao) ) {
  	  	
  	    $pdf->setfont($fonte01,$b01,$tam01);
  	    $pdf->cell(35,$alt,$RLrh02_validadepensao.":",0,0,"R",0);
  	    $pdf->setfont($fonte02,$b02,$tam02);
  	    $pdf->cell(60,$alt,db_formatar($rh02_validadepensao,'d'),0,0,"L",0);
  	  }	  	
    }
    
  }
  
  if ($isInstituicaoPrevidencia) {
      
      $sSqlDadosInstituidor = "select rhpesorigem.rh21_regpri,
                                      cgm.z01_nome
                                 from rhpesorigem
                                      inner join rhpessoal on rhpessoal.rh01_regist = rhpesorigem.rh21_regpri
                                      inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm
                                where rhpesorigem.rh21_regist = {$rh02_regist}";
      $rsRhPesOrigem = db_query($sSqlDadosInstituidor);
      
      if (pg_num_rows($rsRhPesOrigem) > 0) {
          
          $oDadosInstituidor = db_utils::fieldsMemory($rsRhPesOrigem, 0);
          
          $pdf->ln();
          
          $pdf->setfont($fonte01, $b01, $tam01);
          $pdf->cell(35, $alt, "Origem:", 0, 0, "R", 0);
          $pdf->setfont($fonte02, $b02, $tam02);
          $pdf->cell(13, $alt, $oDadosInstituidor->rh21_regpri, 0, 0, "L", 0);
          $pdf->setfont($fonte02, $b02, $tam02);
          $pdf->cell(60, $alt, $oDadosInstituidor->z01_nome, 0, 0, "R", 0);
      }
      
      $rsRhPesProp = $clrhpesprop->sql_record($clrhpesprop->sql_query_file($rh02_regist));
      if ($clrhpesprop->numrows > 0){
        $oDadosRhProp = db_utils::fieldsMemory($rsRhPesProp, 0);
        
        $pdf->ln();
        
        $pdf->setfont($fonte01, $b01, $tam01);
        $pdf->cell(35, $alt, $RLrh19_propi.":", 0, 0, "R", 0);
        $pdf->setfont($fonte02, $b02, $tam02);
        $pdf->cell(60, $alt, $oDadosRhProp->rh19_propi, 0, 0, "L", 0);
      }
      
      $pdf->setfont($fonte01, $b01, $tam01);
      $pdf->cell(35, $alt, $RLrh01_clas2.":", 0, 0, "R", 0);
      $pdf->setfont($fonte02, $b02, $tam02);
      $pdf->cell(60, $alt, db_formatar($rh01_clas2, 'd'), 0, 1, "L", 0);
      
      $pdf->ln();
      
  }

  $pdf->ln();
  
########################################################################$######################################

  $iContadorColunas = 0;
  
  $sql = "
	  select *,
		   case rh02_folha
			when 'M' then 'Mensal'
			when 'Q' then 'Quinzenal'
			when 'S' then 'Semanal'
		   end as descr_folha,
		   case rh02_tipsal
			when 'M' then 'Mensal'
			when 'Q' then 'Quinzenal'
			when 'S' then 'Semanal'
			when 'D' then 'Diário'
			when 'H' then 'Horista'
			when 'E' then 'Extra'
		   end as descr_tipsal,
		   case rh01_instru
			when 1 then 'Analfabeto'
			when 2 then 'Até 4a. Série Incompleta'
			when 3 then '4a. Série Completa'
			when 4 then 'Até 8a. Série Incompleta'
			when 5 then 'Primeiro Grau Completo'
			when 6 then 'Segundo Grau Incompleto'
			when 7 then 'Segundo Grau Completo'
			when 8 then 'Superior Incompleto'
			when 9 then 'Superior Completo'
			when 10 then 'Mestrado Completo'
			when 11 then 'Doutorado Completo'
		   end as descr_instru,
		   case rh01_estciv
			when 1 then 'Solteiro'
			when 2 then 'Casado'
			when 3 then 'Viúvo'
			when 4 then 'Separado Consensual'
			when 5 then 'Divorciado'
			when 6 then 'Outros'
		   end as descr_estciv,
		   case rh01_sexo
			when 'M' then 'Masculino'
			else 'Feminino'
		   end as descr_sexo,
		   case rh01_nacion
			when 10 then 'Brasileiro'
			when 20 then 'Naturalizado'
			when 21 then 'Argentino'
			else 'Outros'
		   end as descr_nacion
																			       
	  from rhpessoal
           inner join rhpessoalmov   on rh02_anousu = $ano
                                    and rh02_mesusu = $mes
                                    and rh02_regist = rh01_regist
                                    and rh02_instit = ".db_getsession("DB_instit")."
           inner join cgm            on z01_numcgm  = rh01_numcgm
           inner join rhfuncao       on rh37_funcao = rh01_funcao
           inner join rhlota         on r70_codigo  = rh02_lota
                                    and r70_instit  = rh02_instit 
            left join rhpescargo     on rh20_seqpes = rh02_seqpes
            left join rhcargo        on rh20_cargo  = rh04_codigo
                                    and rh04_instit = rh02_instit
            left join rhpespadrao    on rh02_seqpes = rh03_seqpes
            left join rhpesrescisao  on rh02_seqpes = rh05_seqpes
            left join rhpesfgts      on rh01_regist = rh15_regist
            left join rhpesdoc       on rh01_regist = rh16_regist
      where rh02_regist = {$regist}";																			    
    $result1 = db_query($sql);
    db_fieldsmemory($result1, 0);

    if (isset($mostraCabecalhoOutrosDados)) {
      $pdf->ln();
      $pdf->setfont('arial', 'b', 12);
      $pdf->cell(180, $alt, "OUTROS DADOS", 0, 0, "C", 1);
  }

    $pdf->ln();
    $pdf->setfont('arial', 'b', 9);

    if (isset($mostraEstadoCivil)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_estciv.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,"$rh01_estciv-".@$descr_estciv,0,0,"L",0);
      
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraNacionalidade)) {
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_nacion.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$rh01_nacion."-".@$descr_nacion,0,0,"L",0);
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }

    if (isset($mostraTipoSalario)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_tipsal.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$rh02_tipsal."-".@$descr_tipsal,0,0,"L",0);
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraNumeroCartaoPonto)) {
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_ponto.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$rh01_ponto,0,0,"L",0);
      
      $iContadorColunas++;
      
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }

    if (isset($mostraTipoFolha)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_folha.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,"$rh02_folha-$descr_folha",0,0,"L",0);
      
      $iContadorColunas++;
      
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
      
    if (isset($mostraGrauInstrucao)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_instru.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,"$rh01_instru-$descr_instru",0,0,"L",0);
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }

    if (isset($mostraPortadorMolestia)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLrh02_portadormolestia.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,($rh02_portadormolestia == 't'?"Sim":"Não"),0,0,"L",0);
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
     
    if (isset($mostraDificienteFisico)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLrh02_deficientefisico.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,($rh02_deficientefisico == 't'?"Sim":"Não"),0,0,"L",0);
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraNaturalidade)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_natura.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$rh01_natura,0,0,"L",0);
      
      $iContadorColunas++;
      
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraSexo)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_sexo.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,@$descr_sexo,0,0,"L",0);
      
      $iContadorColunas++;
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }

    if (isset($mostraDataOpcaoFGTS)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_fgts.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,db_formatar($rh15_data,'d'),0,0,"L",0);
      
      $iContadorColunas++;
      
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraContaFGTS)) {
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLr01_ccfgts.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$rh15_contac,0,0,"L",0);
      
      $iContadorColunas++;
    }

    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }

    if (isset($mostraProgressao)) {
        
     $pdf->setfont($fonte01,$b01,$tam01);
     $pdf->cell(35,$alt,$RLr01_anter.":",0,0,"R",0);
     $pdf->setfont($fonte02,$b02,$tam02);
     $pdf->cell(60,$alt,db_formatar($rh01_progres,'d'),0,0,"L",0);
     $iContadorColunas++;
     
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
     
    if (isset($mostraTelefone)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLz01_telef.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$z01_telef,0,0,"L",0);
      $iContadorColunas++;
      
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
    
    if (isset($mostraCelular)) {
        
        $pdf->setfont($fonte01,$b01,$tam01);
        $pdf->cell(35,$alt,$RLz01_telcel.":",0,0,"R",0);
        $pdf->setfont($fonte02,$b02,$tam02);
        $pdf->cell(60,$alt,$z01_telcel,0,0,"L",0);
        $iContadorColunas++;
        
    }
    
    if ($iContadorColunas == 2) {
        $iContadorColunas = 0;
        $pdf->ln();
    }
  
    if (isset($mostraEmail)) {
        
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(35,$alt,$RLz01_email.":",0,0,"R",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      $pdf->cell(60,$alt,$z01_email,0,0,"L",0);
      
    }
  
  $pdf->ln();
  
  $iContadorColunas = 0;
  
  if (isset($mostraCabecalhoDocumentos)) {
      
    $pdf->ln();
    $pdf->setfont('arial','b',12);
    $pdf->cell(180,$alt,"DOCUMENTOS",0,0,"C",1);
  
  }

  $pdf->setfont('arial','b',9);

  $pdf->ln();

  if (isset($mostraTituloEleitoral)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,"Título/Zona/Seção:",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,"$rh16_titele/$rh16_zonael/$rh16_secaoe",0,0,"L",0);
    
    $iContadorColunas++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraCNH)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_carth.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh16_carth_n,0,0,"L",0);
    $iContadorColunas++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraDataProgressao)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_anter.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,db_formatar($rh01_progres,'d'),0,0,"L",0);
    $iContadorColunas++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraDataBaseTrienio)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_trien.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,db_formatar($rh01_trienio,'d'),0,0,"L",0);
    $iContadorColunas++;
  }

  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraCTPS)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_ctps.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$rh16_ctps_n.'-'.$rh16_ctps_s,0,0,"L",0);
    $iContadorColunas++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraPis)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLr01_pis.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,@$rh16_pis,0,0,"L",0);
    $iContadorColunas++;
  }

  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraReservista)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,"Reservista/Categoria:",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,@$rh16_reserv."/".@$rh16_catres,0,0,"L",0);
    $iContadorColunas++;
  }
  
  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }
  
  if (isset($mostraCNPJCPF)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLz01_cgccpf.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,@$z01_cgccpf,0,0,"L",0);
    $iContadorColunas++;
  }

  if ($iContadorColunas == 2) {
      $iContadorColunas = 0;
      $pdf->ln();
  }

  if (isset($mostraRG)) {
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(35,$alt,$RLz01_ident.":",0,0,"R",0);
    $pdf->setfont($fonte02,$b02,$tam02);
    $pdf->cell(60,$alt,$z01_ident,0,0,"L",0);
    
  }
  
  $pdf->ln();
  
  if (isset($mostraRescisao)) {
      
      $sSqlDadosRescisao = "select rh05_recis, 
                                   r59_causa||' - '||r59_descr as tipo_rescisao 
                              from rhpesrescisao 
                                   inner join rescisao on r59_causa = rh05_causa 
                                                      and r59_anousu = {$rh02_anousu}
                                                      and r59_mesusu = {$rh02_mesusu}
                                                      and r59_instit = {$rh02_instit}
                                                      and r59_regime = (select rh30_regime from rhregime where rh30_codreg = {$rh02_codreg})
                                                      and r59_caub = rh05_caub
                             where rh05_seqpes = {$rh02_seqpes}";
      $rsRescisao = db_query($sSqlDadosRescisao);
      if (pg_num_rows($rsRescisao) > 0) {
          
        $oRescisao = db_utils::fieldsMemory($rsRescisao,0);
        
        $pdf->ln();
        $pdf->setfont('arial','b',12);
        $pdf->cell(180,$alt,"RESCISÃO",0,1,"C",1);
        
        $pdf->setfont($fonte01,$b01,$tam01);
        $pdf->cell(35,$alt,"Tipo de Rescisão :",0,0,"R",0);
        $pdf->setfont($fonte02,$b02,$tam02);
        $pdf->cell(60,$alt,$oRescisao->tipo_rescisao,0,0,"L",0);
            
        $pdf->setfont($fonte01,$b01,$tam01);
        $pdf->cell(35,$alt,$RLr01_recis.":",0,0,"R",0);
        $pdf->setfont($fonte02,$b02,$tam02);
        $pdf->cell(60,$alt,db_formatar($oRescisao->rh05_recis,'d'),0,1,"L",0);
        
      }
      
      $pdf->ln();
      
  }
  
  $iContadorColunas = 0;
  
  if (isset($mostraLocaisTrabalho)) {

      $sSql = "select distinct
                        rh56_localtrab,
                        rh55_estrut,
                        rh55_descr,
                        case when rh56_princ = true then 'Sim' else 'Não' end as principal,
                        rh56_datainicio, 
                        rh56_datafim
                   from rhpeslocaltrab
                        inner join rhpessoalmov                  on rhpeslocaltrab.rh56_seqpes           = rhpessoalmov.rh02_seqpes
                        inner join rhlocaltrab                   on  rhlocaltrab.rh55_codigo             = rhpeslocaltrab.rh56_localtrab
                  where rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."
                    and rhpessoalmov.rh02_regist = {$regist}
                    and rhpessoalmov.rh02_anousu = {$ano}
                    and rhpessoalmov.rh02_mesusu = {$mes}
                  order by principal desc, rh56_datainicio, rh56_datafim";
      
      $rsLocalTrab = db_query($sSql);
      $iLinhasLocalTrab = pg_num_rows($rsLocalTrab);
      
      $pdf->ln();
      $pdf->setfont('arial','b',12);
      $pdf->cell(180,$alt,"LOCAIS DE TRABALHO",0,1,"C",1);

      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->cell(15,$alt,"Código",0,0,"R",0);
      $pdf->cell(18,$alt,"Estrutural",0,0,"L",0);
      $pdf->cell(98,$alt,"Descrição",0,0,"L",0);
      $pdf->cell(17,$alt,"Principal",0,0,"L",0);
      $pdf->cell(17,$alt,"Dt. Inicio",0,0,"L",0);
      $pdf->cell(17,$alt,"Dt. Fim",0,1,"L",0);
      $pdf->setfont($fonte02,$b02,$tam02);
      
      for ($iLinhaLocal = 0; $iLinhaLocal < $iLinhasLocalTrab; $iLinhaLocal++) {
          
          $oLocalTrab = db_utils::fieldsMemory($rsLocalTrab,$iLinhaLocal);
          
          $pdf->cell(15,$alt,$oLocalTrab->rh56_localtrab,0,0,"R",0);
          $pdf->cell(18,$alt,$oLocalTrab->rh55_estrut,0,0,"L",0);
          $pdf->cell(98,$alt,$oLocalTrab->rh55_descr,0,0,"L",0);
          $pdf->cell(17,$alt,$oLocalTrab->principal,0,0,"L",0);
          $pdf->cell(17,$alt,db_formatar($oLocalTrab->rh56_datainicio,"d"),0,0,"L",0);
          $pdf->cell(17,$alt,db_formatar($oLocalTrab->rh56_datafim,"d"),0,1,"L",0);
          
      }
      
      $pdf->ln();
      
      
      
  }
  
#############################################################################################################

if (isset($mostraFerias)) {
    
  $sql = "select r30_perai, 
                 r30_peraf,
                 r30_faltas,
                 r30_ndias,
                 r30_dias1,
                 r30_per1i,
                 r30_per1f,
                 r30_proc1,
                 r30_dias2,
                 r30_per2i,
                 r30_per2f,
                 r30_proc2,
                 r30_abono,
                 r30_ponto,
                 r30_obs
            from cadferia 
           where r30_regist = {$regist} 
             and r30_anousu = {$ano} 
             and r30_mesusu = {$mes} 
           order by r30_perai";
  $result2 = db_query($sql);

  if (pg_num_rows($result2) > 0) {
      
    $pdf->ln();
    $pdf->ln();
    $pdf->setfont('arial','b',12);
    $pdf->cell(180,$alt,"FÉRIAS",0,0,"C",1);
    $pdf->ln();
  
    $pdf->setfont($fonte01,$b01,$tam03);
    $pdf->cell(27,$alt,"Período Aquisitivo",1,0,"C",1);
    $pdf->cell(8,$alt,"Faltas",1,0,"C",1);
    $pdf->cell(10,$alt,"Direito",1,0,"C",1);
    $pdf->cell(10,$alt,"Dias 1",1,0,"C",1);
    $pdf->cell(27,$alt,"Período de Gozo 1",1,0,"C",1);
    $pdf->cell(15,$alt,"Pgto 1",1,0,"C",1);
    $pdf->cell(10,$alt,"Dias 2",1,0,"C",1);
    $pdf->cell(27,$alt,"Período de Gozo 2",1,0,"C",1);
    $pdf->cell(15,$alt,"Pgto 2",1,0,"C",1);
    $pdf->cell(10,$alt,"Abono",1,0,"C",1);
    $pdf->cell(21,$alt,"Ponto",1,0,"C",1);
    $pdf->ln();
    $pdf->setfont($fonte02,$b02,$tam03);
    $pre = 1;
    
    for ($i = 0;$i < pg_num_rows($result2);$i++){
      db_fieldsmemory($result2,$i);
  
      if ($pdf->getY() > $pdf->getH() - 30 ){
        $pdf->addpage();
        $pdf->setfont($fonte01,$b01,$tam03);
        $pdf->cell(27,$alt,"Período Aquisitivo",1,0,"C",1);
        $pdf->cell(8,$alt,"Faltas",1,0,"C",1);
        $pdf->cell(10,$alt,"Direito",1,0,"C",1);
        $pdf->cell(10,$alt,"Dias 1",1,0,"C",1);
        $pdf->cell(27,$alt,"Período de Gozo 1",1,0,"C",1);
        $pdf->cell(15,$alt,"Pgto 1",1,0,"C",1);
        $pdf->cell(10,$alt,"Dias 2",1,0,"C",1);
        $pdf->cell(27,$alt,"Período de Gozo 2",1,0,"C",1);
        $pdf->cell(15,$alt,"Pgto 2",1,0,"C",1);
        $pdf->cell(10,$alt,"Abono",1,0,"C",1);
        $pdf->cell(21,$alt,"Ponto",1,0,"C",1);
        $pdf->ln();
        $pdf->setfont($fonte02,$b02,$tam03);
        $pre = 1;
      }  
      
      if($pre == 1){
       $pre = 0;
      }else{
       $pre = 1;
      }
  
      $pdf->cell(27,$alt,db_formatar($r30_perai,'d').' - '.db_formatar($r30_peraf,'d'),1,0,"C",$pre);
      $pdf->cell(8,$alt,$r30_faltas,1,0,"C",$pre);
      $pdf->cell(10,$alt,$r30_ndias,1,0,"C",$pre);
      $pdf->cell(10,$alt,$r30_dias1,1,0,"C",$pre);
      $pdf->cell(27,$alt,db_formatar($r30_per1i,'d').' - '.db_formatar($r30_per1f,'d'),1,0,"C",$pre);
      $pdf->cell(15,$alt,$r30_proc1,1,0,"C",$pre);
      $pdf->cell(10,$alt,$r30_dias2,1,0,"C",$pre);
      $pdf->cell(27,$alt,db_formatar($r30_per2i,'d').' - '.db_formatar($r30_per2f,'d'),1,0,"C",$pre);
      $pdf->cell(15,$alt,$r30_proc2,1,0,"C",$pre);
      $pdf->cell(10,$alt,$r30_abono,1,0,"C",$pre);
      $pdf->cell(21,$alt,($r30_ponto == "C"?"Complementar":"Salário"),1,0,"C",$pre);
      $pdf->ln();
      
      if(trim($r30_obs) != ''){
        $pdf->MultiCell(180,3,$r30_obs,1, "J", $pre);
      }
  
    }  
  }
}
$pdf->ln();

########################################################################################################################
  if (isset($mostraDependentes)) {
      
    $sql = "select rh31_nome,
                   rh31_dtnasc,
                   case rh31_gparen
                     when 'C' then 'Cônjuge'
                     when 'F' then 'Filho(a)'
                     when 'P' then 'Pai'
                     when 'M' then 'Mãe'
                     when 'A' then 'Avô(ó)'
                     when 'O' then 'Outros'
                   end as rh31_gparen,
                   case rh31_depend
                     when 'C' then 'Cálculo'
                     when 'S' then 'Sempre'
                     when 'N' then 'Não'
                   end as rh31_depend,
                   case rh31_irf
                     when '0' then 'Não Dependente'
                     when '1' then 'Cônjuge/Companheiro(a)'
                     when '2' then 'Filho(a)/Enteado(a), ate 21 anos de idade'
                     when '3' then 'Filho(a) ou enteado(a),  24 anos de idade cursando ensino superior'
                     when '4' then 'Irmao(a), neto(a) ou bisneto(a),  ate 21 anos'
                     when '5' then 'Irmao(a), neto(a) ou bisneto(a), de 21 a 24 anos c/ensino superior'
                     when '6' then 'Pais, avos e bisavos'
                     when '7' then 'Menor pobre ate 21 anos, com a guarda judicial'
                     when '8' then 'Pessoa absolutamente incapaz'
                    end as rh31_irf
               from rhdepend 
                    where rh31_regist = {$regist}"; 
    $result3 = db_query($sql);
    if (pg_num_rows($result3) > 0 ) {
        
      $pdf->ln();
      $pdf->setfont('arial','b',12);
      $pdf->cell(180,$alt,"DEPENDENTES",0,0,"C",1);
      $pdf->setfont('arial','b',9);
      $pdf->ln();

      $pdf->setfont($fonte01, $b01, $tam01);
      $pdf->cell(75, $alt, 'Nome', 1, 0, "C", 1);
      $pdf->cell(20, $alt, 'Nascimento', 1, 0, "C", 1);
      $pdf->cell(15, $alt, 'Parent.', 1, 0, "C", 1);
      $pdf->cell(15, $alt, 'Sal.Fam.', 1, 0, "C", 1);
      $pdf->cell(69, $alt, 'IRRF', 1, 0, "C", 1);
      $pdf->ln();
      $pdf->setfont($fonte02, $b02, $tam02);

      for ($i = 0; $i < pg_num_rows($result3); $i++) {
          
        db_fieldsmemory($result3, $i);
        if ($pdf->getY() > $pdf->getH() - 30) {
            $pdf->setfont($fonte01, $b01, $tam01);
            $pdf->addpage();
            $pdf->cell(75, $alt, $RLrh31_nome, 1, 0, "C", 1);
            $pdf->cell(20, $alt, 'Nascimento', 1, 0, "C", 1);
            $pdf->cell(15, $alt, 'Parent.', 1, 0, "C", 1);
            $pdf->cell(15, $alt, 'Sal.Fam.', 1, 0, "C", 1);
            $pdf->cell(69, $alt, 'IRRF', 1, 0, "C", 1);
            $pdf->ln();
            $pdf->setfont($fonte02, $b02, $tam02);
        }
        
        $pdf->cell(75, $alt, $rh31_nome, 1, 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($rh31_dtnasc, 'd'), 1, 0, "C", 0);
        $pdf->cell(15, $alt, $rh31_gparen, 1, 0, "C", 0);
        $pdf->cell(15, $alt, $rh31_depend, 1, 0, "C", 0);
        $pdf->multicell(69, $alt, $rh31_irf, 1, "L", 0);

      }
      
    }
    
  }
  
  if (isset($mostraObservacoes)) {
      
    if (trim($observacao) != '') {
        
        $pdf->ln();
        $pdf->ln();
        $pdf->setfont('arial', 'b', 12);
        $pdf->cell(180, $alt, "OBSERVAÇÕES", 0, 1, "C", 1);
        $pdf->setfont('arial', '', 9);
        $pdf->MultiCell(180, 3, $observacao, 0, "J");
      
    }
    
  }
  
  $pdf->ln();
}
$pdf->output('I');
