<?php

chdir(__DIR__);

require_once "../libs/db_utils.php";
require_once "../libs/db_conn.php";

$DB_BASE     = "ecidade_dicionario_dados";
$DB_SERVIDOR = "pgatend.rs.dbseller.com.br";
$DB_PORTA    = "5432";
$DB_USUARIO  = "ecidade";

$aTabelas = array();
if (count($argv) > 1) {

  foreach ($argv as $i => $tabela) {

    if ($i == 0) {
      continue;
    }
    $aTabelas[] = $tabela;
  }
}

$sTabelasParaGerarXML =  count($aTabelas) > 0 ? "'".implode("','", $aTabelas)."'" : '';

$lProcessaCompleto = true;

if (count($aTabelas) == 0) {
  system("rm ../dd/tabelas/*");
} else {
  foreach ($aTabelas as $tabela) {
    system("rm ../dd/tabelas/*.{$tabela}.*");
  }
}
system("rm ../dd/table_wrappers.dd.xml");

if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  echo "Contate com Administrador do Sistema! (Conexão Inválida.)\n";
  exit;
}

$rs = pg_query("select fc_startsession();");
$sSqlTabelas = "select db_sysarquivo.*, nomemod as nome_schema
                  from db_sysarquivo
                       inner join db_sysarqmod on db_sysarqmod.codarq = db_sysarquivo.codarq 
                       inner join db_sysmodulo on db_sysmodulo.codmod = db_sysarqmod.codmod";
if (!empty($sTabelasParaGerarXML)) {
  $sSqlTabelas .= " where nomearq in ({$sTabelasParaGerarXML})";
}

$rsTabelas       = pg_query($sSqlTabelas);
$iNumRowsTabelas = pg_num_rows($rsTabelas);
if ($lProcessaCompleto) {

  /*
    @TODO - Gerar valores default
  */
  for ($iTabelas=0;$iTabelas < $iNumRowsTabelas; $iTabelas++) {

    echo "Processando tabelas ... {$iTabelas} de {$iNumRowsTabelas} \r";

    $oTabela = db_utils::fieldsMemory($rsTabelas,$iTabelas);

    $rsArq = fopen("../dd/tabelas/{$oTabela->nome_schema}.{$oTabela->nomearq}.dd.xml", "a+");
    $sXml  = "<?xml version=\"1.0\" standalone=\"yes\" ?>\n";
    // $sXml .= "<tabela codarq=\"{$oTabela->codarq}\" nomearq=\"{$oTabela->nomearq}\" descricao=\"".utf8_encode($oTabela->descricao)."\" sigla=\"{$oTabela->sigla}\" dataincl=\"{$oTabela->dataincl}\" rotulo=\"".utf8_encode($oTabela->rotulo)."\" tipotabela=\"{$oTabela->tipotabela}\" naolibclass=\"{$oTabela->naolibclass}\" naolibfunc=\"{$oTabela->naolibfunc}\" naolibprog=\"{$oTabela->naolibprog}\" naolibform=\"{$oTabela->naolibform}\">\n";
    $sXml .= "<table codigo='{$oTabela->codarq}' name='{$oTabela->nome_schema}.{$oTabela->nomearq}' description=\"".tratamentoString($oTabela->descricao)."\" prefix=\"{$oTabela->sigla}\" label=\"".tratamentoString($oTabela->rotulo)."\" type=\"{$oTabela->tipotabela}\">\n";

    // select buscando os campos
    $sSql = " select db_syscampo.descricao as descricao_campo,
                     db_syscampo.rotulo    as rotulo_campo,
                     
                     ( select cp.nomecam
                         from db_syscampodep  
                              inner join db_syscampo cp  on cp.codcam = db_syscampodep.codcampai
                        where db_syscampodep.codcam = db_sysarqcamp.codcam ) as nome_campo_pai,
                     exists ( select 1 
                                from db_syssequencia
                               where db_syssequencia.codsequencia = db_sysarqcamp.codsequencia ) as tem_sequencia,
  	                 case 
  	                   when db_sysprikey.codcam is not null then true 
  	                   else false 
  	                 end as chave_primaria,
  	                 
                     ( select nomesequencia 
                         from db_syssequencia
                        where db_syssequencia.codsequencia = db_sysarqcamp.codsequencia ) as nomesequencia,
                     *
                from db_sysarqcamp 
                     inner join db_syscampo     on db_syscampo.codcam   = db_sysarqcamp.codcam
                     left  join  db_sysprikey   on db_sysprikey.codarq  = db_sysarqcamp.codarq
                                               and db_sysprikey.codcam  = db_sysarqcamp.codcam   
               where db_sysarqcamp.codarq = {$oTabela->codarq} 
               order by db_sysprikey.sequen";
    //die($sSql);
    $rs = pg_query($sSql);
    $iTotalRegistros = pg_num_rows($rs);

    //    $sXml .= "  <campos>\n";
    $sXml .= "  <fields>\n";
    // Processando os campos da tabela
    for ($i=0;$i < $iTotalRegistros; $i++) {

      $o = db_utils::fieldsMemory($rs,$i);

      /**
       * 1 - Gerar campo principal
       * 2 - Gerar valores default
       */
      $sXmlSequencia = "";
      if ($o->codsequencia != "0" && $o->codsequencia != "") {
        $sXmlSequencia = "\n      <sequence name='{$oTabela->nome_schema}.{$o->nomesequencia}' />\n    ";
      }
      $sXml .= "    <field codigo=\"$o->codcam\" \n";
      $sXml .= "           campo_api='' \n";
      $sXml .= "           name=\"{$o->nomecam}\" \n";
      $sXml .= "           conteudo=\"".tratamentoString($o->conteudo)."\" \n";
      $sXml .= "           description=\"".tratamentoString($o->descricao_campo)."\" \n";
      $sXml .= "           inivalue=\"{$o->valorinicial}\" \n";
      $sXml .= "           label=\"".tratamentoString($o->rotulo_campo)."\" \n";
      $sXml .= "           size=\"{$o->tamanho}\" \n";
      $sXml .= "           null=\"{$o->nulo}\" \n";
      $sXml .= "           uppercase=\"{$o->maiusculo}\" \n";
      $sXml .= "           autocompl=\"{$o->autocompl}\" \n";
      $sXml .= "           aceitatipo=\"{$o->aceitatipo}\" \n";
      $sXml .= "           tipoobj=\"{$o->tipoobj}\" \n";
      $sXml .= "           labelrel=\"".tratamentoString($o->rotulorel)."\" \n";
      $sXml .= "           reference=\"{$o->nome_campo_pai}\" \n";
      $sXml .= "           ispk=\"{$o->chave_primaria}\" \n";
      $sXml .= "           hassequence=\"{$o->tem_sequencia}\" > \n";
      $sXml .= "           {$sXmlSequencia} \n";
      $sXml .= "    </field> \n";
    }

    $sXml .= "  </fields>\n";

    $sSqlChavePrimaria = " select db_sysprikey.*,
                                  db_syscampo.nomecam 
                             from db_sysprikey 
                                  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam 
                            where codarq = {$oTabela->codarq} ";
    $rsChavePrimaria = pg_query($sSqlChavePrimaria);
    $iTotalRegistros = pg_num_rows($rsChavePrimaria);
    $sXml .= "  <primarykey>\n";
    for ($i=0;$i < $iTotalRegistros; $i++) {
      $o = db_utils::fieldsMemory($rsChavePrimaria,$i);
      $sXml .= "    <fieldpk name=\"{$o->nomecam}\"></fieldpk> \n";
    }
    $sXml .= "  </primarykey>\n";

    $sSqlChaveEstrangeira  = " select db_sysforkey.*, ";
    $sSqlChaveEstrangeira .= "        ( select nomecam ";
    $sSqlChaveEstrangeira .= "            from db_syscampo ";
    $sSqlChaveEstrangeira .= "           where codcam = db_sysforkey.codcam ) as campo_principal, ";
    $sSqlChaveEstrangeira .= "        tr.nomearq as nomearq_ref, ";
    $sSqlChaveEstrangeira .= "        ( select nomecam ";
    $sSqlChaveEstrangeira .= " 	          from db_sysprikey ";
    $sSqlChaveEstrangeira .= " 	               inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam ";
    $sSqlChaveEstrangeira .= "           where db_sysprikey.codarq = db_sysforkey.referen ";
    $sSqlChaveEstrangeira .= " 	           and db_sysprikey.sequen = db_sysforkey.sequen ) as campo_referente, ";
    $sSqlChaveEstrangeira .= "        not ( select nulo ";
    $sSqlChaveEstrangeira .= "                from db_syscampo ";
    $sSqlChaveEstrangeira .= "               where codcam = db_sysforkey.codcam ) as inner ";
    $sSqlChaveEstrangeira .= "   from db_sysforkey ";
    $sSqlChaveEstrangeira .= "        inner join db_sysarquivo tr on tr.codarq = db_sysforkey.referen ";
    $sSqlChaveEstrangeira .= "  where db_sysforkey.codarq = {$oTabela->codarq} order by tr.codarq ";

    $sXml .= "  <foreignkeys>\n";
    $rsChaveEstrangeira = pg_query($sSqlChaveEstrangeira);
    $iTotalRegistros = pg_num_rows($rsChaveEstrangeira);

    $aProcessadas = array();
    $lPrimeiro    = true;
    for ($i=0;$i < $iTotalRegistros; $i++) {
      $o = db_utils::fieldsMemory($rsChaveEstrangeira,$i);

      if ($lPrimeiro || !in_array($o->referen,$aProcessadas)){
        if (!$lPrimeiro) {
          $sXml .= "    </foreignkey>\n";
        }
        $sXml .= "    <foreignkey reference=\"$o->nomearq_ref\" inner='{$o->inner}'>\n";

        $aProcessadas[] = $o->referen;
        $lPrimeiro      = false;
      }

      $sXml .= "      <fieldfk name=\"{$o->campo_principal}\" reference=\"{$o->campo_referente}\" /> \n";

    }
    if ($iTotalRegistros > 0){
      $sXml .= "    </foreignkey>\n";
    }

    $sXml .= "  </foreignkeys>\n";

    $sXml .= "</table>\n";
    fputs($rsArq,utf8_encode($sXml));
    fclose($rsArq);


  }
}

/**
 * Cria o arquivo table_wrappers referente a sigla de cada tabela
 */


$sSqlTabelas        = "select nomearq,sigla from db_sysarquivo order by sigla";
$sSqlTabelas        = "select distinct 
                              nomearq, 
                              split_part(nomecam,'_',1) as sigla 
                         from db_syscampo 
                              inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam 
                              inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq
                        order by 2 ";
$rsTabelas          = pg_query($sSqlTabelas);
$iNumRowsTabelas    = pg_num_rows($rsTabelas);
$aSiglasProcessadas = array();
$lPrimeiro          = true;

$rsTabelaSigla      = fopen("../dd/table_wrappers.dd.xml", "a+");
$sXmlTabelaSigla    = "<?xml version=\"1.0\" standalone=\"yes\" ?>\n";
$sXmlTabelaSigla   .= "  <prefixes>\n";

for ($iTabelas=0;$iTabelas < $iNumRowsTabelas; $iTabelas++) {

  echo "Processando tabelas ... {$iTabelas} de {$iNumRowsTabelas} \r";

  $oTabela = db_utils::fieldsMemory($rsTabelas,$iTabelas);

  if ( ! in_array($oTabela->sigla,$aSiglasProcessadas) ) {

    if ( ! $lPrimeiro ) {
      $sXmlTabelaSigla .= "    </prefix>\n";
    }
    $sXmlTabelaSigla .= "    <prefix name=\"{$oTabela->sigla}\">\n";
    $aSiglasProcessadas[] = $oTabela->sigla;
    $lPrimeiro        = false;

  }

  $sXmlTabelaSigla .= "      <table name=\"{$oTabela->nomearq}\"></table>\n";

}

$sXmlTabelaSigla .= "    </prefix>\n";
$sXmlTabelaSigla .= "</prefixes>\n";

fputs($rsTabelaSigla,utf8_decode($sXmlTabelaSigla));

fclose($rsTabelaSigla);

function tratamentoString($sString){

  $aRetirar = array('<b>','</b>',"\"","<i>","</i>");

  foreach ($aRetirar as $sRetirar ) {

    $sString = str_replace($sRetirar,"",$sString);

  }

  return $sString;

}


?>
