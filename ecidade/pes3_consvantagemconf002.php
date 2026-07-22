<?PHP

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $queryString);
foreach ($queryString as $key => $value) {
        ${$key} = $value;
}

db_postmemory($HTTP_POST_VARS);

if (isset($atualizar)) {

        db_inicio_transacao();



        $sql = "delete 
	  from assentform
          where h88_assent = $codigo_assent";
        $res = pg_query($sql);
        if (!$res) {
                echo "Erro ao deletar formula.";
                $erro = true;
        }

        $erro = false;

        if (isset($asse0) && $asse0 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse0, '$cond0','$form0',$codigo_assent,'$operador0','$multiplicador0') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula. $sql";
                        $erro = true;
                }
        }

        if (isset($asse1) && $asse1 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse1, '$cond1','$form1',$codigo_assent,'$operador1','$multiplicador1') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        echo "Erro ao gravar formula. $sql";
                        $erro = true;
                }
        }

        if (isset($asse2) && $asse2 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse2, '$cond2','$form2',$codigo_assent,'$operador2','$multiplicador2') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse3) && $asse3 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse3, '$cond3','$form3',$codigo_assent,'$operador3','$multiplicador3') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse4) && $asse4 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse4, '$cond4','$form4',$codigo_assent,'$operador4','$multiplicador4') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse5) && $asse5 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse5, '$cond5','$form5',$codigo_assent,'$operador5','$multiplicador5') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse6) && $asse6 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse6, '$cond6','$form6',$codigo_assent,'$operador6','$multiplicador6') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse7) && $asse7 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse7, '$cond7','$form7',$codigo_assent,'$operador7','$multiplicador7') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse8) && $asse8 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse8, '$cond8','$form8',$codigo_assent,'$operador8','$multiplicador8') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse9) && $asse9 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse9, '$cond9','$form9',$codigo_assent,'$operador9','$multiplicador9') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse10) && $asse10 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse10, '$cond10','$form10',$codigo_assent,'$operador10','$multiplicador10') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse11) && $asse11 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse11, '$cond11','$form11',$codigo_assent,'$operador11','$multiplicador11') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }


        if (isset($asse12) && $asse12 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse12, '$cond12','$form12',$codigo_assent,'$operador12','$multiplicador12') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse13) && $asse13 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse13, '$cond13','$form13',$codigo_assent,'$operador13','$multiplicador13') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse14) && $asse14 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse14, '$cond14','$form14',$codigo_assent,'$operador14','$multiplicador14') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse15) && $asse15 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse15, '$cond15','$form15',$codigo_assent,'$operador15','$multiplicador15') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }

        if (isset($asse16) && $asse16 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse16, '$cond16','$form16',$codigo_assent,'$operador16','$multiplicador16') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse17) && $asse17 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse17, '$cond17','$form17',$codigo_assent,'$operador17','$multiplicador17') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse18) && $asse18 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse18, '$cond18','$form18',$codigo_assent,'$operador18','$multiplicador18') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }
        if (isset($asse19) && $asse19 != "Nenhum") {
                $sql = "insert into assentform 
                         ( h88_codigo , h88_condicao, h88_resultado,h88_assent,h88_operador,h88_multiplicador)
			 values( $asse19, '$cond19','$form19',$codigo_assent,'$operador19','$multiplicador19') ";
                $res = pg_query($sql);
                if (!$res) {
                        echo "Erro ao gravar formula.";
                        $erro = true;
                }
        }



        db_fim_transacao($erro);
}


?>
<html>

<head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
        <form name="form1" method="post">
                <table align="center" border="0" cellspacing="4" cellpadding="0">

                        <tr>
                                <td> <strong>Assentamento:</strong> </td>
                                <td colspan='2'>
                                        <?PHP
                                        echo $codigo_assent . "-" . $descr
                                        ?>
                                        <input name='codigo_assent' value='<?= $codigo_assent ?>' type='hidden'>
                                <td>
                        </tr>
                        <tr>

                                <td>Assentamento
                                </td>
                                <td>Condicao
                                </td>
                                <td>Acao
                                </td>
                                <td>Formula
                                </td>
                                <td>Multiplicacao
                                </td>

                        </tr>
                        <?PHP
                        $sql = "select h12_codigo,h12_assent||'-'||h12_descr as h12_descr
              from tipoasse
	      where h12_codigo != $codigo_assent
                  ";
                        $record = pg_query($sql);



                        $sql = "select * 
	from assentform
        where h88_assent = $codigo_assent";
                        $res = pg_query($sql);


                        for ($i = 0; $i < pg_num_rows($res); $i++) {

                                $tipo = pg_result($res, $i, 'h88_condicao');
                                $form = pg_result($res, $i, 'h88_resultado');
                                $operador = pg_result($res, $i, 'h88_operador');
                                $multiplicador = pg_result($res, $i, 'h88_multiplicador');

                                echo " <tr> 
    <td > ";
                                eval('$asse' . $i . ' = ' . pg_result($res, $i, 'h88_codigo') . ';');
                                db_selectrecord("asse$i", $record, 1, 2, "", "", "", "Nenhum");

                                // <input name='asse$i' size='5' value='".pg_result($res,$i,'h88_codigo')."'>
                                echo "
    </td>
    <td >
     <select name='cond$i'>
      <option value='inicio' " . ($tipo == 'inicio' ? 'selected' : '') . ">Inicio</option>
      <option value='antesdoinicio' " . ($tipo == 'antesdoinicio' ? 'selected' : '') . ">Antes do Inicio</option>
      <option value='meio' " . ($tipo == 'meio' ? 'selected' : '') . ">Protela</option>
      <option value='final' " . ($tipo == 'final' ? 'selected' : '') . ">Final</option>
     </select>

    </td>
    <td>
     <select name='form$i'>
      <option value='+dias' " . ($form == '+dias' ? 'selected' : '') . ">Protela</option>
      <option value='-dias' " . ($form == '-dias' ? 'selected' : '') . ">Antecipa</option>
     </select>
    </td>
    <td>
     <select name='operador$i'>
      <option value='+' " . ($operador == '+' ? 'selected' : '') . ">+dias</option>
      <option value='-' " . ($operador == '-' ? 'selected' : '') . ">-dias</option>
      <option value='*' " . ($operador == '-' ? 'selected' : '') . ">*dias</option>
      <option value='m+' " . ($operador == 'm+' ? 'selected' : '') . ">+Meses</option>
      <option value='m-' " . ($operador == 'm-' ? 'selected' : '') . ">-Meses</option>
      <option value='m*' " . ($operador == 'm*' ? 'selected' : '') . ">*Meses</option>
     </select>
    </td>
    <td>
     <input name='multiplicador$i' value='$multiplicador' width='10'>
     </select>
    </td>



  </tr>";
                        }

                        for ($x = $i; $x < 20; $x++) {
                                echo " <tr> 
    <td >
     ";
                                db_selectrecord("asse$x", $record, 2, 2, "", "", "", "Nenhum");
                                echo ">
    </td>
    <td >
      <select name='cond$x'>
      <option value='inicio' >Inicio</option>
      <option value='antesdoinicio' >Antes do Inicio</option>
      <option value='meio' >Protela</option>
      <option value='final' >Final</option>
     </select>

    </td>
    <td>
     <select name='form$x'>
      <option value='+dias' >Protela</option>
      <option value='-dias' >Antecipa</option>
     </select>

    </td> 
   <td>
     <select name='operador$x'>
      <option value='+' >+dias</option>
      <option value='-' >-dias</option>
      <option value='*' >*dias </option>
      <option value='m+' >+Meses</option>
      <option value='m-' >+Meses</option>
      <option value='m*' >+Meses</option>
     </select>
    </td>
    <td>
     <input name='multiplicador$x' width='10'>
    </td>
 
  </tr>";
                        }

                        ?>
                        <tr>
                                <td align="center" colspan="2">
                                        <input type="submit" value="Atualizar" name="atualizar">
                                </td>
                        </tr>
                </table>
        </form>
        <?
        db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
        ?>
</body>
<script>
        const url = '<?= ECIDADE_REQUEST_PATH ?>';
        const routers = {
                'search': url + '/v4/api/recursos-humanos/rh/concessaodireitos/configuracao'
        };
        const data = {
                h88_assent: 103
        };
        const dado = new FormData;
        for (index in data) {
                dado.append(index, data[index]);
        }
        console.log(routers.search);
        HttpClient.post(routers.search, {
                        body: dado
                })
                .then((res) => {
                        console.log(res)
                        if (res.hasOwnProperty('data')) {
                                console.log(res)
                        } else {
                                alert(JSON.stringify(res));
                        }
                });
</script>

</html>