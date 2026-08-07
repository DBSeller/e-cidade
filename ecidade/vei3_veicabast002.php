<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBSeller Servicos de Informatica             
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
//MODULO: material
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"], $queryString);
db_postmemory($_POST);

$clveicabast = new cl_veicabast;
$clveicabastposto = new cl_veicabastposto;
$clveicabastpostoempnota = new cl_veicabastpostoempnota;
$clveicabastretirada = new cl_veicabastretirada;
$clveicabastanu = new cl_veicabastanu;
$clveiculos = new cl_veiculos;
$clveictipoabast = new cl_veictipoabast;

$clveicabast->rotulo->label();
$clveicabastanu->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ve01_placa");
$clrotulo->label("ve26_descr");
$clrotulo->label("nome");
$clrotulo->label("ve71_veiccadposto");
$clrotulo->label("ve71_nota");
$clrotulo->label("ve72_empnota");
$clrotulo->label("ve73_veicretirada");
$clrotulo->label("ve60_codigo");
$clrotulo->label("ve70_observacoes");

if (isset($codigo) && $codigo != "") {
    $result = $clveicabast->sql_record($clveicabast->sql_query($codigo));
    if ($clveicabast->numrows > 0) {
        db_fieldsmemory($result, 0);
    }
    $ve70_codigo = $codigo;

    $result = $clveicabast->sql_record($clveicabast->sql_query_nota(null, "e69_numero,ve71_nota,ve70_veiculos", null,
      "ve70_codigo=$codigo"));
    if ($clveicabast->numrows > 0) {
        db_fieldsmemory($result, 0);
        $ve72empnota = $e69_numero;
    }
    $result = $clveiculos->sql_record($clveiculos->sql_query(null, "ve01_veictipoabast", null,
      "ve01_codigo=$ve70_veiculos"));
    if ($clveiculos->numrows > 0) {
        db_fieldsmemory($result, 0);
    }
    $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,
      "ve07_sigla"));
    if ($clveictipoabast->numrows > 0) {
        db_fieldsmemory($result_veictipoabast, 0);
    }

    $result_posto = $clveicabastposto->sql_record($clveicabastposto->sql_query_tip(null, "*", null,
      "ve71_veicabast=$ve70_codigo"));
    if ($clveicabastposto->numrows > 0) {
        db_fieldsmemory($result_posto, 0);
        if ($descrdepto != "") {
            $posto = $descrdepto;
        }
        if ($z01_nome != "") {
            $posto = $z01_nome;
        }
    }
    $result_retirada = $clveicabastretirada->sql_record($clveicabastretirada->sql_query(null, "*", null,
      "ve73_veicabast=$ve70_codigo"));
    if ($clveicabastretirada->numrows > 0) {
        db_fieldsmemory($result_retirada, 0);
    }
    $result_empnota = $clveicabastpostoempnota->sql_record($clveicabastpostoempnota->sql_query(null, "ve72_codigo",
      null, "ve71_veicabast=$ve70_codigo"));
    if ($clveicabastpostoempnota->numrows > 0) {
        db_fieldsmemory($result_empnota, 0);
    }
    $ve72_empnota = $e69_numero;

    $sqlAbastacimentoAnulado = $clveicabastanu->sql_query(null, "*", null, "ve74_veicabast = {$codigo}");
    $result = db_query($sqlAbastacimentoAnulado);
    $temAnulacao = pg_num_rows($result) > 0;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
  </script>
  <style>
    .bordas {
      border: 2px solid #cccccc;
      border-top-color: #999999;
      border-right-color: #999999;
      border-left-color: #999999;
      border-bottom-color: #999999;
      background-color: #999999;
    }

    .bordas_corp {
      border: 1px solid #cccccc;
      border-top-color: #999999;
      border-right-color: #999999;
      border-left-color: #999999;
      border-bottom-color: #999999;
      background-color: #cccccc;
    }
  </style>
</head>
<body>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>DADOS CADASTRAIS</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?php echo $Tve70_codigo ?>">
              <?php echo $Lve70_codigo ?>
          </td>
          <td>
              <?php
              db_input('ve70_codigo', 10, $Ive70_codigo, true, 'text', 3, "");
              ?>
          </td>

          <td nowrap title="<?php echo $Tve70_veiculos ?>">
              <?php echo $Lve70_veiculos ?>
          </td>
          <td>
              <?php
              db_input('ve70_veiculos', 10, $Ive70_veiculos, true, 'text', 3, "");
              db_input('ve01_placa', 10, $Ive01_placa, true, 'text', 3, '');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve70_veiculoscomb ?>">
              <?php echo $Lve70_veiculoscomb ?>
          </td>
          <td>
              <?php
              db_input('ve70_veiculoscomb', 10, $Ive70_veiculoscomb, true, 'text', 3, "");
              db_input('ve26_descr', 40, $Ive26_descr, true, 'text', 3, '');
              ?>
          </td>

          <td nowrap title="<?php echo $Tve70_dtabast ?>">
              <?php echo $Lve70_dtabast ?>
          </td>
          <td>
              <?php
              db_inputdata('ve70_dtabast', $ve70_dtabast_dia, $ve70_dtabast_mes, $ve70_dtabast_ano, true,
                'text', 3, "");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve70_litros ?>">
              <?php echo $Lve70_litros ?>
          </td>
          <td>
              <?php
              db_input('ve70_litros', 15, $Ive70_litros, true, 'text', 3, "");
              ?>
          </td>

          <td nowrap title="<?php echo $Tve70_vlrun ?>">
              <?php echo $Lve70_vlrun ?>
          </td>
          <td>
              <?php
              db_input('ve70_vlrun', 15, $Ive70_vlrun, true, 'text', 3, "");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve70_valor ?>">
              <?php echo $Lve70_valor ?>
          </td>
          <td>
              <?php
              db_input('ve70_valor', 15, $Ive70_valor, true, 'text', 3, "");
              ?>
          </td>


          <td nowrap title="<?php echo $Tve70_medida ?>">
              <?php echo $Lve70_medida ?>
          </td>
          <td>
              <?php
              db_input('ve70_medida', 15, $Ive70_medida, true, 'text', 3, "");

              if (isset($ve07_sigla) && trim($ve07_sigla) != "") {
                  db_input("ve07_sigla", 3, 0, true, "text", 3);
              }
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve71_veiccadposto ?>">
              <?php
              db_ancora($Lve71_veiccadposto, "", 3);
              ?>
          </td>
          <td>
              <?php
              db_input('ve71_veiccadposto', 10, $Ive71_veiccadposto, true, 'text', 3, "");
              db_input('posto', 40, "", true, 'text', 3, '');
              ?>
          </td>

          <td nowrap title="<?php echo $Tve71_nota ?>">
              <?php echo $Lve71_nota ?>
          </td>
          <td>
              <?php
              db_input('ve71_nota', 20, $Ive71_nota, true, 'text', 3, "");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve72_empnota ?>">
              <?php echo $Lve72_empnota ?></td>
          <td>
              <?php
              db_input("ve72_empnota", 6, $Ive72_empnota, true, "text", 3, "");
              ?>
          </td>

          <td nowrap title="<?php echo $Tve73_veicretirada ?>">
              <?php
              db_ancora($Lve73_veicretirada, "js_pesquisave73_veicretirada(true);", 3);
              ?>
          </td>
          <td>
              <?php
              db_input('ve73_veicretirada', 10, $Ive73_veicretirada, true, 'text', 3, "");
              db_input('ve60_codigo', 10, $Ive60_codigo, true, 'hidden', 3, '');
              ?>
          </td>
        </tr>


        <tr>
          <td nowrap title="<?php echo $Tve70_ativo ?>">
              <?php echo $Lve70_ativo ?>
          </td>
          <td>
              <?php
              $x = array('1' => 'Sim', '0' => 'Não');
              db_select('ve70_ativo', $x, true, 3, "");
              ?>
          </td>

          <td nowrap title="<?php echo $Tve70_usuario ?>">
              <?php echo $Lve70_usuario ?>
          </td>
          <td>
              <?php
              db_input('ve70_usuario', 10, $Ive70_usuario, true, 'text', 3, "");
              db_input('nome', 40, $Inome, true, 'text', 3, '');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve70_data ?>">
              <?php echo $Lve70_data ?>
          </td>
          <td>
              <?php
              db_inputdata('ve70_data', $ve70_data_dia, $ve70_data_mes, $ve70_data_ano, true, 'text', 3, "");
              ?>
          </td>

          <td nowrap title="<?php echo $Tve70_hora ?>">
              <?php echo $Lve70_hora ?>
          </td>
          <td>
              <?php
              db_input('ve70_hora', 5, $Ive70_hora, true, 'text', 3, "");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve70_observacao ?>">
              <?php echo $Lve70_observacao ?>
          </td>
          <td>
              <?php
              db_textarea('ve70_observacao', 5, 50, $Ive70_observacao, true, 'text', 3);
              ?>
          </td>
        </tr>
      </table>
    </fieldset>

      <?php
      if ($temAnulacao) {
          $nome = "";
          db_fieldsmemory($result, 0);
          ?>
        <fieldset>
          <legend>DADOS ANULAÇÃO</legend>
          <table class="form-container">
            <tr>
              <td nowrap title="<?php echo $Tve74_motivo ?>">
                  <?php echo $Lve74_motivo ?>
              </td>
              <td colspan='3'>
                  <?php
                  db_textarea('ve74_motivo', 0, 90, $Ive74_motivo, true, 'text', 3, "");
                  ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tve74_data ?>">
                  <?php echo $Lve74_data ?>
              </td>
              <td>
                  <?php
                  db_inputdata('ve74_data', $ve74_data_dia, $ve74_data_mes, $ve74_data_ano, true, 'text', 3, "");
                  ?>
              </td>

              <td nowrap title="<?php echo $Tve74_hora ?>">
                  <?php echo $Lve74_hora ?>
              </td>
              <td>
                  <?php
                  db_input('ve74_hora', 5, $Ive74_hora, true, 'text', 3, "");
                  ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tve74_usuario ?>">
                  <?php echo $Lve74_usuario ?>
              </td>
              <td>
                  <?php
                  db_input('ve74_usuario', 10, $Ive74_usuario, true, 'text', 3, "");
                  db_input('nome', 40, $Inome, true, 'text', 3, '');
                  ?>
              </td>
              <td></td>
              <td></td>
            </tr>
          </table>
        </fieldset>
          <?php
      }
      ?>

  </form>
</div>
<script>
</script>
</body>
</html>
