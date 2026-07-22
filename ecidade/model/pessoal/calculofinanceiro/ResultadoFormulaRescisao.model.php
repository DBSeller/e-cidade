<?php 
/**
 * 
 */
abstract class ResultadoFormulaRescisao {
  /**
   * Recebe uma formula de rubrica e retorna o valor com base nos parametros de rescisao
   * @param  [type] $sFormula       
   * @param  [type] $sTabelaPonto   
   * @param  [type] $sTabelaCalculo 
   * @param  [type] $sSiglaPonto    
   * @param  [type] $sSiglaCalculo  
   * @param  [type] $sMatricula     
   * @param  [type] $sCodigoRubrica 
   * @return String formula com os valores modificados                 
   */
  public static function parse( $sFormula = null, $sTabelaPonto=null, $sTabelaCalculo=null, $sSiglaPonto=null, $sSiglaCalculo=null, $sMatricula=null,$sCodigoRubrica=null) {

    $formula         = $sFormula;
    $area0           = $sTabelaPonto;
    $area1           = $sTabelaCalculo;
    $sigla           = $sSiglaPonto;
    $sigla2          = $sSiglaCalculo;
    $nro_do_registro = $sMatricula;
    $rubrica         = $sCodigoRubrica;

    global $carregarubricas_geral,$pessoal,$Ipessoal;
    global $anousu, $mesusu, $DB_instit;
    global $F001, $F002, $F004, $F005, $F006, $F007, $F008, $F009, $F010, $F011, $F012, $F013, $F014, $F015, $F016, $F017, $F018, $F019, $F020, $F021, $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;

    global $quais_diversos;
    eval($quais_diversos);

    global $r110_regist;
    global ${$area0}, $inssirf_base_ferias;
    global $opcao_tipo;
    global $subpes,$rescisao,$bases, $transacao2, $gerfant, $transacao1,$basesr;

    LogCalculoFolha::write("------------------------------------Parâmetros da funcao-----------------------------------");
    LogCalculoFolha::write("Parametro: Rubrica.........:" . $rubrica        );
    LogCalculoFolha::write("Parametro: Formula.........:" . $formula        );
    LogCalculoFolha::write("Parametro: Ponto...........:" . $area0          );
    LogCalculoFolha::write("Parametro: Calculo.........:" . $area1          );
    LogCalculoFolha::write("Parametro: Sigla Ponto.....:" . $sigla          );
    LogCalculoFolha::write("Parametro: Sigla Calculo...:" . $sigla2         );
    LogCalculoFolha::write("Parametro: Registro?.......:" . $nro_do_registro);

    LogCalculoFolha::write("-----------------------------------Parâmetros da Rescisao----------------------------------");
    LogCalculoFolha::write("Incide Ferias Previdencia..: " . ( $rescisao[0]["r59_finss"]   == "t" ? "Sim" : "Nao" ) );
    LogCalculoFolha::write("Incide 13 Previdencia......: " . ( $rescisao[0]["r59_13inss"]  == "t" ? "Sim" : "Nao" ) );
    LogCalculoFolha::write("Incide Ferias IRRF.........: " . ( $rescisao[0]["r59_firrf"]   == "t" ? "Sim" : "Nao" ) );
    LogCalculoFolha::write("Incide 13 IRRF.............: " . ( $rescisao[0]["r59_13irrf"]  == "t" ? "Sim" : "Nao" ) );

    $pos_base = strpos("#".$formula,"B")+0;

    if( $pos_base > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){

      $base_mae = substr("#".$formula,$pos_base,4);
      LogCalculoFolha::write("Base mãe.......: $base_mae");

      while( $pos_base  > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){

        $base = substr("#".$formula,$pos_base,4);

        $condicaoaux  = " and r08_codigo = ".db_sqlformat( $base );
        db_selectmax( "bases", "select * from bases ".bb_condicaosubpes("R08_").$condicaoaux );

        $abre_base     = "(0";
        $n1            = 0;
        $valor         = 0;
        $monta_formula = "(0";

        // R987 --> ( B002 ou B039 ) // BASE PREVIDENCIA S/FERIAS
        // B003 --> BASE PREVIDENCIA (13 SALARIO)
        // B005 --> BASE IRRF (FERIAS)
        // B006 --> BASE IRRF (13 SALARIO)

        LogCalculoFolha::write("Base a ser analisada.......: $base");


        //echo "<BR><BR>  rescisao ---> ".print_r($rescisao);
        //echo "<BR>f( ($rubrica == 'R987' && ('f' == ".($rescisao[0]["r59_finss"]?"1":"2")."))";
        if( ( $rubrica == "R987" && ('f' == $rescisao[0]["r59_finss"]))    // Incide Ferias Previdencia
          ||  ($base == "B003"   && ('f' == $rescisao[0]["r59_13inss"]))   // Incide 13 Previdencia
          ||  ($base == "B005"   && ('f' == $rescisao[0]["r59_firrf"]))    // Incide Ferias IRRF
          ||  ($base == "B006"   && ('f' == $rescisao[0]["r59_13irrf"]))){ // Incide 13 IRRF
          $valor = 0;
          LogCalculoFolha::write("Valor Modificado pelos parametros de rescisao.....: $valor");
        }else{
          LogCalculoFolha::write("Valor    N A O   foi zerado");

          //     echo "<BR> 2 passou aqui !!!!!!!!";
          //echo "<BR> base 1.2 --> $base";

          if ( ('t' == $bases[0]["r08_calqua"]) && 'f' == $bases[0]["r08_mesant"] ) {

            LogCalculoFolha::write("Calcula Quantidade.........: SIM");
            LogCalculoFolha::write("Mes Anteiror...............: NÃO");

            // B801 --> BASE P/ABONO FERIAS COLETIVAS
            // B807 --> INSALUB/PENOSID/PERICULOSIDADE
            // B808 --> HORA EXTRA(COMPOSICAO DE BASE)
            // B809 --> HORA EXTRA (INSAL/PERIC/PENOS)

            $condicaoaux  = " and ".$sigla."_regist = ".db_sqlformat( $r110_regist );
            $condicaoaux .= " order by ".$sigla."_regist, ".$sigla."_rubric ";
            global $transacao1;
            db_selectmax( "transacao1", "select * from ".$area0." ".bb_condicaosubpes( $sigla."_" ).$condicaoaux );

            for($i=0;$i<count($transacao1);$i++){

              eval('$campo_rubrica = $transacao1'."[$i]['".$sigla."_rubric'];");
              eval('$campo_quant   = $transacao1'."[$i]['".$sigla."_quant'];");
              eval('$campo_valor   = $transacao1'."[$i]['".$sigla."_valor'];");
              $rubrica_contem = $carregarubricas_geral[$campo_rubrica];

              $campo_pd       = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
              $formula2       = '$formula1 = '.substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1).";";

              global $basesr;
              $achou = false;
              $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
              $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
              if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
                $condicaoaux .= " and rh54_rubric = ".db_sqlformat( $campo_rubrica );
                if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
                  $achou = true;
                }
              }else{
                $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
                $condicaoaux .= " and r09_rubric = ".db_sqlformat( $campo_rubrica );
                if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
                  $achou = true;
                }
              }
              if($achou){
                if( db_at($base,"B804-B805-B806") > 0){
                  $valor += ( $campo_quant / 100 );
                  LogCalculoFolha::write("Valor Modificado para $campo_rubrica.....................: + ".( $campo_quant / 100 ));
                }else{
                  $valor += $campo_quant;
                  LogCalculoFolha::write("Valor Modificado para $campo_rubrica.....................: + ".$campo_quant );
                }
              }
            }
          } else if( ('t' == $bases[0]["r08_mesant"])) {

            LogCalculoFolha::write("Mes Anteiror...............: NÃO");
            global $basesr;
            $achou = false;
            $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
            $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
            if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
              $achou = true;
              $rubric = "rh54_rubric";
              //     echo "<BR> base rhbasesreg 4 --> $condicaoaux";
            }else{
              $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
              if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
                $achou = true;
                $rubric = "r09_rubric";
                //     echo "<BR> base basesr 4 --> $condicaoaux";
              }
            }
            if($achou){

              for($i=0;$i<count($basesr);$i++){
                if( !db_empty($basesr[$i][$rubric])){
                  $condicaoaux = " and r14_regist = ".db_sqlformat( $r110_regist );
                  $condicaoaux .= " and r14_rubric = ".db_sqlformat( $basesr[$i][$rubric] );

                  if( db_selectmax( "gerfant", "select * from gerfsal ".bb_condicaosubpesanterior( "r14_" ).$condicaoaux )){
                    if( $gerfant[0]["r14_pd"] == 1){
                      if( !('t' == $bases[0]["r08_calqua"])){
                        $valor += $gerfant[0]["r14_valor"];
                        LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$gerfant[0]["r14_valor"]}");
                      }else{
                        $valor += $gerfant[0]["r14_quant"];
                        LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$gerfant[0]["r14_quant"]}");
                      }
                    }else{
                      if( substr("#".$basesr[$i][$rubric],1,1) == "R" && db_val(substr("#".$basesr[$i][$rubric],2,3)) > 922){
                        if( !('t' == $bases[0]["r08_calqua"])){
                          $valor += $gerfant[0]["r14_valor"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$gerfant[0]["r14_valor"]}");
                        }else{
                          $valor += $gerfant[0]["r14_quant"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$gerfant[0]["r14_quant"]}");
                        }
                      }else{
                        if( !('t' == $bases[0]["r08_calqua"])){
                          $valor -= $gerfant[0]["r14_valor"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: - {$gerfant[0]["r14_valor"]}");
                        }else{
                          $valor -= $gerfant[0]["r14_quant"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: - {$gerfant[0]["r14_quant"]}");
                        }
                      }
                    }
                  }
                }
              }
            }
          } else if( ('t' == $bases[0]["r08_pfixo"]) && $area0 != "pontofx"){

            LogCalculoFolha::write("Calcula ponto fixo.........: SIM");
            LogCalculoFolha::write("Mes Anteiror...............: NÃO");
            global $basesr;
            $achou = false;
            $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
            $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
            if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
              $achou = true;
              $rubric = "rh54_rubric";
              //     echo "<BR> base rhbasesreg 1--> $condicaoaux";
            }else{
              $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
              if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
                $achou = true;
                $rubric = "r09_rubric";
                //     echo "<BR> base basesr 1--> $condicaoaux";
              }
            }
            if($achou){
              for($i=0;$i<count($basesr);$i++){
                if( !db_empty($basesr[$i][$rubric])){
                  $condicaoaux  = " and r53_regist = ".db_sqlformat( $r110_regist );
                  $condicaoaux .= " and r53_rubric = ".db_sqlformat( $basesr[$i][$rubric] );
                  global $transacao2;
                  if( db_selectmax( "transacao2", "select * from gerffx ".bb_condicaosubpes( "r53_" ).$condicaoaux )){
                    if( $transacao2[0]["r53_pd"] == 1){
                      if( !('t' == $bases[0]["r08_calqua"])){
                        $valor += $transacao2[0]["r53_valor"];
                        LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$transacao2[0]["r53_valor"]}");
                      }else{
                        $valor += $transacao2[0]["r53_quant"];
                        LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$transacao2[0]["r53_valor"]}");
                      }
                    }else{
                      if( substr("#".$basesr[$i][$rubric],1,1) == "R"
                        && db_val(substr("#".$basesr[$i][$rubric],2,3)) > 922){
                        if( !('t' == $bases[0]["r08_calqua"])){
                          $valor += $transacao2[0]["r53_valor"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$transacao2[0]["r53_quant"]}");
                        }else{
                          $valor += $transacao2[0]["r53_quant"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: + {$transacao2[0]["r53_quant"]}");
                        }
                      }else{
                        if( !('t' == $bases[0]["r08_calqua"])){
                          $valor -= $transacao2[0]["r53_valor"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: - {$transacao2[0]["r53_valor"]}");
                        }else{
                          $valor -= $transacao2[0]["r53_quant"];
                          LogCalculoFolha::write("Valor Modificado para {$basesr[$i][$rubric]}.....................: - {$transacao2[0]["r53_valor"]}");
                        }
                      }
                    }
                  }
                }
              }
            }
          } else {


            //echo "<BR> base 1.3 --> $base";
            LogCalculoFolha::write("Percorrendo os dados da tabela $area0");

            for($i=0;$i<count($$area0);$i++){

              eval('$campo_rubrica = $'.$area0."[$i]['".$sigla."_rubric'];");

              if( $campo_rubrica > "2000" && $campo_rubrica < "4000"                               // rubricas de rescisao // $campo_rubrica > "2000" && $campo_rubrica < "4000"
                && (
                  (('f' == $rescisao[0]["r59_rinss"]) && $base == "B002")                          // Incide Rescisao na Base de Previdencia                                            
                  || (('f' == $rescisao[0]["r59_rirrf"]) && $base == "B004")                       // Incide Rescisao na Base de IRRF
                  || (('f' == $rescisao[0]["r59_rfgts"]) && ($base == "B007" || $base == "B077")) )// Incide Rescisao na Base de FGTS
              ){ 
              LogCalculoFolha::write("Rubrica $campo_rubrica ignorado pela condição das bases.");
              continue;
              }

              if( db_at($base, "B007-B077") > 0
                && ( ( ('f' == $rescisao[0]["r59_ffgts"])
                && ( ( $campo_rubrica >="2000" && $campo_rubrica <"4000" )
                || $campo_rubrica == "R931"
                || $campo_rubrica == "R932" ) ) )
                || ($pessoal[$Ipessoal]["r01_regime"] == 2 && ('f' == $rescisao[0]["r59_13fgts"]) && ( $campo_rubrica >="4000" && $campo_rubrica < "6000" ) )
                || ($pessoal[$Ipessoal]["r01_regime"] == 2 && ('f' == $rescisao[0]["r59_rfgts"])  && ( $campo_rubrica >="6000" && $campo_rubrica < "8000" ))){

                LogCalculoFolha::write("Rubrica $campo_rubrica ignorado pela condição das bases.");
                continue;
              }

              eval('$campo_quant   = $'.$area0."[$i]['".$sigla."_quant'];");
              eval('$campo_valor   = $'.$area0."[$i]['".$sigla."_valor'];");

              $conteudo_rubrica = "R".$campo_rubrica;
              $rubrica_contem   = $carregarubricas_geral[$campo_rubrica];
              $campo_pd         = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
              $formula1         = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);

              global $basesr;
              $achou            = false;

              $condicaoaux      = " where rh54_base = ".db_sqlformat( $base );
              $condicaoaux     .= " and rh54_regist = ".db_sqlformat( $r110_regist );

              if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){

                $condicaoaux .= " and rh54_rubric = ".db_sqlformat( $campo_rubrica );

                if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
                  $achou = true;
                }
              }else{

                $condicaoaux  = " and r09_base   = ".db_sqlformat( $base );
                $condicaoaux .= " and r09_rubric = ".db_sqlformat( $campo_rubrica );

                if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
                  $achou = true;
                }
              }

              if($achou){

                if( $area0 == "pontoprovfer" ){
                  $tpgto = $pontoprovfer[$i]["r91_tpp"];
                }else if($area0 == " pontofr"){
                  $tpgto = $pontofr[$i]["r19_tpp"];
                }
                //echo "<BR> passou aqui 4";
                // r01_propi --> Perc.Inativo
                if( $pessoal[$Ipessoal]["r01_propi"] > 0 && $pessoal[$Ipessoal]["r01_propi"] < 100){

                  $condicaoaux  = " and ".$sigla2."_regist = ".db_sqlformat( $r110_regist );
                  $condicaoaux .= " and ".$sigla2."_pd = ".db_sqlformat( $campo_pd );
                  $condicaoaux .= " and ".$sigla2."_rubric = ".db_sqlformat( $campo_rubrica );
                  if( db_at($area0,"pontoprovfer pontoprovs13 pontofr") > 0 ){
                    $condicaoaux .= " and upper(".$sigla2."_tpp) = ".db_sqlformat( strtoupper($tpgto) );
                  }
                  global $transacao;
                  if( db_selectmax( "transacao", "select * from ".$area1." ".bb_condicaosubpes( $sigla2."_" ).$condicaoaux )){

                    if( $rubrica == "R988" || $rubrica == "R989" || $rubrica == "R979"){
                      /**
                       * @TODO Verificar se eventos do tipo base(3) devem ser desconsiderados...
                       */
                      if( $campo_pd == "1"){
                        $valor -= $transacao[0][$sigla2."_valor"];
                        LogCalculoFolha::write("Valor Modificado para $rubrica......................: -".$transacao[0][$sigla2."_valor"]);

                      }else{
                        $valor += $transacao[0][$sigla2."_valor"];
                        LogCalculoFolha::write("Valor Modificado para $rubrica......................: +".$transacao[0][$sigla2."_valor"]);

                      }
                    }else{
                      /**
                       * @TODO Verificar se eventos do tipo base(3) devem ser desconsiderados...
                       */
                      if( $campo_pd == "1"){
                        $valor += round( $transacao[0][$sigla2."_valor"],2);
                        LogCalculoFolha::write("Valor Modificado para $rubrica......................: +".round( $transacao[0][$sigla2."_valor"],2));

                      }else{
                        $valor -= round( $transacao[0][$sigla2."_valor"],2);
                        LogCalculoFolha::write("Valor Modificado para $rubrica......................: -".round( $transacao[0][$sigla2."_valor"],2));

                      }
                    }
                    continue;
                  }

                }

                if( !db_empty($formula1) && $campo_valor == 0){
                  if( (strpos("#".$rubrica_contem,"A")+0) == 0 && (strpos("#".$rubrica_contem,"B")+0) == 0 && (strpos("#".$rubrica_contem,"P")+0) == 0 ){

                    //echo "F0->".$campo_rubrica."\n";
                    //echo "F1->".$formula2."\n";
                    $formula2 ='$formula1 = '.$formula1.";";
                    ob_start();
                    eval($formula2);
                    db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$formula1,$rubrica);
                    //echo "F2->".$formula1."\n";
                    $resultado = round($campo_quant * $formula1 ,2);
                    //echo "F3->".$resultado."\n";


                    if( $campo_pd == "1"){

                      $valor += $resultado;
                      LogCalculoFolha::write("Valor Modificado para $campo_rubrica......................: + $resultado");

                    }else{
                      $valor -= $resultado;
                      LogCalculoFolha::write("Valor Modificado para $campo_rubrica......................: - $resultado");

                    }
                  }else{
                    $base_na_rubrica = substr("#".$rubrica_contem,(strpos("#".$rubrica_contem,"B")+0),4);

                    if( ( $base_na_rubrica != $base) && ( $base_na_rubrica != $base_mae)){

                      if( db_empty($campo_quant)){

                        if( $campo_pd == "1"){
                          $abre_base .= $rubrica_contem;
                          LogCalculoFolha::write("Adicionado a Fórmula da Base '$base' a Rubrica '$campo_rubrica': + $rubrica_contem");
                        }else{
                          $abre_base .= "-".$rubrica_contem;
                          LogCalculoFolha::write("Adicionado a Fórmula da Base '$base' a Rubrica '$campo_rubrica': - $rubrica_contem");
                        }
                      }else{

                        if ( $campo_pd == "1" ) {
                          $abre_base .= '+('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')' ;
                          LogCalculoFolha::write("Adicionado a Fórmula da Base '$base' a Rubrica '$campo_rubrica':".'+('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')');
                        } else {
                          $abre_base .= '-('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')' ;
                          LogCalculoFolha::write("Adicionado a Fórmula a Formula da rubrica '$campo_rubrica'Base '$base' a :".'-('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')');
                        }
                      }
                    }else{
                      $n1 = 1;
                    }


                    if ( db_empty($campo_quant) ) {

                      if( $campo_pd == "1"){
                        $monta_formula .= $rubrica_contem;
                        LogCalculoFolha::write("Modificando montagem de fórmula: $monta_formula");
                      }else{
                        $monta_formula .= "-". $rubrica_contem;
                        LogCalculoFolha::write("Modificando montagem de fórmula: $monta_formula");
                      }

                    }else{

                      if ( $campo_pd == "1" ) {
                        $monta_formula .= '+('.$formula1.'*'.db_strtran((db_str($campo_quant,7,2)),",",".").')' ;
                        LogCalculoFolha::write("Modificando montagem de fórmula: $monta_formula");
                      } else {
                        $monta_formula .= '-('.$formula1.'*'.db_strtran((db_str($campo_quant,7,2)),",",".").')' ;
                        LogCalculoFolha::write("Modificando montagem de fórmula: $monta_formula");
                      }

                    }
                  }
                }else{

                  if ( $campo_pd == "1" ) {

                    $valor += $campo_valor;
                    LogCalculoFolha::write("Valor Modificado para $campo_rubrica .....................: + $campo_valor");
                  } else {

                    $valor -= $campo_valor;
                    LogCalculoFolha::write("Valor Modificado para $campo_rubrica......................: - $campo_valor");
                  }
                }
              }
            }
          }
        }

        LogCalculoFolha::write();
        LogCalculoFolha::write("Fórmula Geral Antes.....................: $formula");
        LogCalculoFolha::write("Base Modificada.........................: $base");

        $sValor         = db_strtran((db_str($valor,20,2)),",",".");
        $abre_base     .= preg_replace( '/\s+/i', ' ', "+ {$sValor} )");
        $monta_formula .= preg_replace( '/\s+/i', ' ', "+ {$sValor} )");

        $sFormula       = db_strtran($formula,$base,$abre_base);
        $formula        = preg_replace( '/\s+/i', ' ', $sFormula );

        LogCalculoFolha::write("Valor encontrado........................: $sValor");
        LogCalculoFolha::write("Fórmula da Base.........................: $abre_base");
        LogCalculoFolha::write("Fórmula Geral ..........................: $formula");

        if( $n1 == 1){
          $formula = db_strtran($formula,$base,$monta_formula);
          LogCalculoFolha::write("Fórmula...............................: $formula");
        }

        $pos_base = strpos("#".$formula,"B")+0;
      }
    }
    LogCalculoFolha::write("Fim do Parse da Fórmula, resultado......: $formula");
    return $formula;

  }
}
