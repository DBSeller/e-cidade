<?php 

foreach ($pessoal as $aDados) {
  $aMatriculasCalculadas[] = $aDados['r01_regist'];
}
$sMatriculas = implode(', ', $aMatriculasCalculadas);

$where_regist_fim = " and rh02_regist in ({$sMatriculas}) " ;

///// CALCULA A MARGEM CONSIGNAVEL

if( $opcao_geral == 1 ){


  function  getSQLBases($sBase) {

    $iAno         = DBPessoal::getAnoFolha();
    $iMes         = DBPessoal::getMesFolha();
    $iInstituicao = db_getsession("DB_instit");

    return
      " select r09_rubric                   \n".
      "   from basesr                       \n".
      "  where r09_anousu = {$iAno}         \n".
      "    and r09_mesusu = {$iMes}         \n".
      "    and r09_instit = {$iInstituicao} \n".
      "    and r09_base   = '{$sBase}'      \n";
  }

  $sSqlMargem  = "select r14_regist as regist,                                         " . PHP_EOL;

  if ( isset($db_debug) && $db_debug ) {
    $sSqlMargem .= "       B037,                                                       " . PHP_EOL;
    $sSqlMargem .= "       valor0690,                                                  " . PHP_EOL;
    $sSqlMargem .= "       B039,                                                       " . PHP_EOL;
  }

  $sSqlMargem .= "       ( (B037 - valor0690 ) /100 * 30 ) - B039 as margem            " . PHP_EOL;
  $sSqlMargem .= "  from ( select ger.r14_regist,                                      " . PHP_EOL;
  $sSqlMargem .= "                coalesce(                                            " . PHP_EOL;
  $sSqlMargem .= "                  (select sum(r53_valor)                             " . PHP_EOL;
  $sSqlMargem .= "                     from gerffx  fx                                 " . PHP_EOL;
  $sSqlMargem .= "                    where fx.r53_regist = ger.r14_regist             " . PHP_EOL;
  $sSqlMargem .= "                      and fx.r53_anousu = ger.r14_anousu             " . PHP_EOL;
  $sSqlMargem .= "                      and fx.r53_mesusu = ger.r14_mesusu             " . PHP_EOL;
  $sSqlMargem .= "                      and fx.r53_rubric in (".getSQLBases('B037').") " . PHP_EOL;
  $sSqlMargem .= "                 ),                                                  " . PHP_EOL;
  $sSqlMargem .= "                 0                                                   " . PHP_EOL;
  $sSqlMargem .= "                ) as B037,                                           " . PHP_EOL;
  $sSqlMargem .= "                                                                     " . PHP_EOL;
  $sSqlMargem .= "                coalesce(                                            " . PHP_EOL;
  $sSqlMargem .= "                  (select sum(r14_valor)                             " . PHP_EOL;
  $sSqlMargem .= "                     from gerfsal sal                                " . PHP_EOL;
  $sSqlMargem .= "                    where sal.r14_regist = ger.r14_regist            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_anousu = ger.r14_anousu            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_mesusu = ger.r14_mesusu            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_rubric = '0690'                    " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_rubric != 'R913'                   " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_rubric != 'R910'                   " . PHP_EOL;
  $sSqlMargem .= "                  ),0                                                " . PHP_EOL;
  $sSqlMargem .= "                ) as valor0690,                                      " . PHP_EOL;
  $sSqlMargem .= "                                                                     " . PHP_EOL;
  $sSqlMargem .= "                                                                     " . PHP_EOL;
  $sSqlMargem .= "                coalesce(                                            " . PHP_EOL;
  $sSqlMargem .= "                  (select sum(r14_valor)                             " . PHP_EOL;
  $sSqlMargem .= "                     from gerfsal sal                                " . PHP_EOL;
  $sSqlMargem .= "                    where sal.r14_regist = ger.r14_regist            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_anousu = ger.r14_anousu            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_mesusu = ger.r14_mesusu            " . PHP_EOL;
  $sSqlMargem .= "                      and sal.r14_rubric in (".getSQLBases('B039').")" . PHP_EOL;
  $sSqlMargem .= "                  ),0                                                " . PHP_EOL;
  $sSqlMargem .= "                ) as B039                                            " . PHP_EOL;
  $sSqlMargem .= "                                                                     " . PHP_EOL;
  $sSqlMargem .= "           from gerfsal as ger                                       " . PHP_EOL;
  $sSqlMargem .= "                inner join rhpessoalmov on rh02_regist = r14_regist  " . PHP_EOL;
  $sSqlMargem .= "                                       and rh02_anousu = r14_anousu  " . PHP_EOL;
  $sSqlMargem .= "                                       and rh02_mesusu = r14_mesusu  " . PHP_EOL;
  $sSqlMargem .= "                                       and rh02_instit = $DB_instit  " . PHP_EOL;
  $sSqlMargem .= "          where ger.r14_anousu = $anousu                             " . PHP_EOL;
  $sSqlMargem .= "            and ger.r14_mesusu = $mesusu                             " . PHP_EOL;
  $sSqlMargem .= "            and ger.r14_rubric = 'R803'                              " . PHP_EOL;
  $sSqlMargem .= "            and ger.r14_instit = $DB_instit                          " . PHP_EOL;
  $sSqlMargem .= "            and ger.r14_regist in ({$sMatriculas})                   " . PHP_EOL;
  $sSqlMargem .= "       ) as x                                                        " . PHP_EOL;

  $sql_margem = "update gerfsal set r14_valor = round(margem.margem,2) ".PHP_EOL;
  $sql_margem.= "  from ($sSqlMargem) as margem                        ".PHP_EOL;
  $sql_margem.= " where r14_anousu = $anousu                           ".PHP_EOL;
  $sql_margem.= "   and r14_mesusu = $mesusu                           ".PHP_EOL;
  $sql_margem.= "   and r14_rubric = 'R803'                            ".PHP_EOL;
  $sql_margem.= "   and r14_regist = margem.regist;                    ".PHP_EOL;

  $res_margem = db_query($sql_margem);

  $sql_margem_neg = "delete from gerfsal where r14_anousu = $anousu and r14_mesusu = $mesusu and r14_rubric = 'R803' and (r14_valor <= 0 or r14_valor is null)";
  $res_margem_neg = db_query($sql_margem_neg) or die($sql_margem_neg);


  $sql_arred = "update gerfsal set r14_valor = round(r14_valor,2) 
    from rhpessoalmov
    where r14_anousu = rh02_anousu 
    and r14_mesusu = rh02_mesusu 
    and r14_regist = rh02_regist
    and r14_anousu = $anousu 
    and r14_mesusu = $mesusu
    and r14_instit = $DB_instit
    $where_regist_fim ";
  $res_arred = db_query($sql_arred) or die($sql_arred);


}

if($opcao_geral == 1 ){
  $sql_del_molestia = "delete from gerfsal 
    where r14_rubric in ('R913', 'R914', 'R915') 
    and  r14_regist in (select rh02_regist 
    from rhpessoalmov 
    where rh02_anousu = $anousu
    and rh02_mesusu = $mesusu
    and rh02_portadormolestia = true ) 
    and r14_anousu = $anousu
    and r14_mesusu = $mesusu";
  $res_del_molestia = db_query($sql_del_molestia) or die($sql_del_molestia);

  $sql_del_r928 = "delete from gerfsal where r14_anousu = $anousu and r14_mesusu = $mesusu and r14_rubric = 'R928' and round(r14_valor) = 0 ";
  $res_del_r928 = db_query($sql_del_r928) or die($sql_del_r928);

}elseif($opcao_geral == 3 ){
  $sql_del_molestia = "delete from gerffer 
    where r31_rubric in ('R913', 'R914', 'R915') 
    and  r31_regist in (select rh02_regist 
    from rhpessoalmov 
    where rh02_anousu = $anousu
    and rh02_mesusu = $mesusu
    and rh02_portadormolestia = true ) 
    and r31_anousu = $anousu
    and r31_mesusu = $mesusu";
  $res_del_molestia = db_query($sql_del_molestia) or die($sql_del_molestia);
}elseif($opcao_geral == 4 ){
  $sql_del_molestia = "delete from gerfres 
    where r20_rubric in ('R913', 'R914', 'R915') 
    and  r20_regist in (select rh02_regist 
    from rhpessoalmov 
    where rh02_anousu = $anousu
    and rh02_mesusu = $mesusu
    and rh02_portadormolestia = true ) 
    and r20_anousu = $anousu
    and r20_mesusu = $mesusu";
  $res_del_molestia = db_query($sql_del_molestia) or die($sql_del_molestia);
}elseif($opcao_geral == 5 ){
  $sql_del_molestia = "delete from gerfs13 
    where r35_rubric in ('R913', 'R914', 'R915') 
    and  r35_regist in (select rh02_regist 
    from rhpessoalmov 
    where rh02_anousu = $anousu
    and rh02_mesusu = $mesusu
    and rh02_portadormolestia = true ) 
    and r35_anousu = $anousu
    and r35_mesusu = $mesusu";
  $res_del_molestia = db_query($sql_del_molestia) or die($sql_del_molestia);
}elseif($opcao_geral == 8 ){
  $sql_del_molestia = "delete from gerfcom 
    where r48_rubric in ('R913', 'R914', 'R915') 
    and  r48_regist in (select rh02_regist 
    from rhpessoalmov 
    where rh02_anousu = $anousu
    and rh02_mesusu = $mesusu
    and rh02_portadormolestia = true ) 
    and r48_anousu = $anousu
    and r48_mesusu = $mesusu";
  $res_del_molestia = db_query($sql_del_molestia) or die($sql_del_molestia);


}
