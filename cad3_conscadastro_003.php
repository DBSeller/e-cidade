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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_iptubase_classe.php"));

if (!isset($j39_numero)) {
    $j39_numero = 0;
    $filtrotipo = 'todos';
}

$radioAmbos = $filtrotipo == "todos" || !isset($filtrotipo) ? "checked" : "";
$radioPredial = $filtrotipo == "Predial" ? "checked" : "";
$radioTerritorial = $filtrotipo == "Territorial" ? "checked" : "";
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default'>

<div class='container' id='ctnView'>
    <?php
    if (isset($pesquisaRua)) {
        ?>
      <form name="form1" method="post">
        <center>
          <table>
            <tr>
              <td>
                <label class="bold">Tipo: </label>
              </td>
              <td>
                <input id="radioAmbos" type="radio" name="filtrotipo" value="todos" <?php echo $radioAmbos ?>>
                <label for="radioAmbos">Ambos</label>
                <input id="radioPredial" type="radio" name="filtrotipo" value="Predial" <?php echo $radioPredial ?>>
                <label for="radioPredial">Predial</label>
                <input id="radioTerritorial" type="radio" name="filtrotipo"
                       value="Territorial" <?= $radioTerritorial ?>>
                <label for="radioTerritorial">Territorial</label>

              </td>
            </tr>
            <tr>
              <td>
                  <?php
                  $clrotulo = new rotulocampo;
                  $clrotulo->label("j39_numero");
                  ?>
                <label><?php echo $Lj39_numero; ?></label>
              </td>
              <td>
                  <?php
                  db_input("j39_numero", 6, $Ij39_numero, true, 'text', 2)
                  ?>
                <input name="pesquisaRua" value="<?= $pesquisaRua ?>" type="hidden">
                <input name="pesquisa" value="Pesquisa" type="submit">
              </td>
            </tr>
          </table>
        </center>
      </form>
        <?php
    }
    $funcao_js = "parent.mostraJanelaDadosImovel|0";
    $clsqlamatriculas = new cl_iptubase;
    $whereTipoImovel = " AND iptubase.j01_tipoimovel = 1 ";

    if (isset($pesquisaPorNome)) {
        // a variavel $pesquisaPorNome retorna com o numro do cgm do registro selecionado

        $sCampos = 'j01_matric as dl_Matricula,proprietario as dl_Tipo,j01_idbql,z01_nome,j01_baixa,j34_setor,           ';
        $sCampos .= 'j34_quadra,j34_lote,j34_area,j34_areal,j34_totcon,j34_zona,j34_areapreservada,j49_face,              ';
        $sCampos .= 'j88_descricao as dl_Tipo_Logr,j14_codigo,j14_nome,numero as dl_Numero,complemento as dl_Complemento, ';
        $sCampos .= 'j13_codi,j13_descr,j13_rural,j13_codant                                                              ';

        $pesquisaPorNome .= $whereTipoImovel;
        $sql = $clsqlamatriculas->sqlmatriculas_nome($pesquisaPorNome, $sCampos);
        db_lovrot($sql,15,"()",$pesquisaPorNome,$funcao_js);
    } elseif (isset($pesquisaPorImobiliaria)) {

        $pesquisaPorImobiliaria .= $whereTipoImovel;
        $sql = $clsqlamatriculas->sqlmatriculas_imobiliaria($pesquisaPorImobiliaria);
        db_lovrot($sql,15,"()",$pesquisaPorImobiliaria,$funcao_js);
    } elseif (isset($pesquisaPorIDBQL)) {

        $pesquisaPorIDBQL .= $whereTipoImovel;
        $sql = $clsqlamatriculas->sqlmatriculas_IDBQL($pesquisaPorIDBQL);
        db_lovrot($sql,15,"()",$pesquisaPorIDBQL,$funcao_js);
    } elseif (isset($pesquisaRua)) {
        // aqui é usado o filtro "todos","Predial","Territorial"
        $temNumero = false;
        $sql = $clsqlamatriculas->sqlmatriculas_ruas($pesquisaRua, $j39_numero, $filtrotipo);

        if (!pg_fetch_row(db_query($sql))) {
          db_msgbox('Nenhum Registro Encontrado');

          $sql = $clsqlamatriculas->sqlmatriculas_ruas($pesquisaRua, 0, $filtrotipo);
          
        }

        db_lovrot($sql, 100, "()", $pesquisaRua, $funcao_js, "", "NoMe", array(), false);
    } elseif (isset($pesquisaBairro)) {
        $sql = $clsqlamatriculas->sqlmatriculas_bairros($pesquisaBairro);
        db_lovrot($sql,15,"()",$pesquisaBairro,$funcao_js);
    }

    ?>
</div>
</body>
</html>

<script>

  $('ctnView').style.width = '800px';
  $('ctnView').style.display = 'block';

</script>
