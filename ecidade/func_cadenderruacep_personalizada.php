<?php
/**
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
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
?>

<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <script type="text/javascript" language="JavaScript" src="scripts/scripts.js"></script>
    <script type="text/javascript" language="scripts/prototype.js"></script>
</head>

<body>
    <form name="formularioPesquisaCep" method="post" action="" class="container">
        <fieldset>
            <legend>Consulta de Cep</legend>

            <table class="form-container">
                <tr>
                    <td>
                        <label for="cep">Cep:</label>
                    </td>
                    <td>
                        <input type="text" id="cep" name="cep" maxlength="8" class="field-size2" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="municipio">Município:</label>
                    </td>
                    <td>
                        <input type="text" id="municipio" name="municipio" maxlength="100" class="field-size8" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="logradouro">Logradouro:</label>
                    </td>
                    <td>
                        <input type="text" id="logradouro" name="logradouro" maxlength="100" class="field-size8" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="numero">Número:</label>
                    </td>
                    <td>
                        <input type="text" id="numero" name="numero" maxlength="10" class="field-size2"  />
                    </td>
                </tr>
            </table>
        </fieldset>

        <input type="submit" id="pesquisar" name="pesquisar" value="Pesquisar" />
        <input type="button" id="limpar" name="limpar" value="Limpar" />
        <input type="button" id="fechar" name="fechar" value="Fechar" />
    </form>
</body>
</html>
<script>
    var 
        btnLimpar  = document.querySelector('#limpar'),
        btnFechar  = document.querySelector('#fechar'),
        btnPesquisar  = document.querySelector('#pesquisar'),
        inputCep    = document.querySelector("#cep"),
        inputMunicipio  = document.querySelector("#municipio"),
        inputLogradouro = document.querySelector("#logradouro"),
        inputNumero     = document.querySelector("#numero");

    btnLimpar.addEventListener('click', event => {
        inputCep.value = '';
        inputMunicipio.value = '';
        inputLogradouro.value = '';
        inputNumero.value = '';

        btnPesquisar.click();
    });

    btnFechar.addEventListener('click', event => {
        parent.db_iframe_cep.hide();
    });

    inputCep.focus();

    window.onload = function(){
        document.body.addEventListener('keydown', function(event){
        if(event.which == 13){
            btnPesquisar.click();
        }
    });

  };
    
</script>
<?php

    $cep = isset($_POST['cep']) ? $_POST['cep'] : "";
    $municipio = isset($_POST['municipio']) ? $_POST['municipio'] : "";
    $logradouro = isset($_POST['logradouro']) ? $_POST['logradouro'] : "";
    $numero = isset($_POST['numero']) ? $_POST['numero'] : "";

    $whereFiltrosPesquisa = array();

    if(!empty($cep)) {
        $whereFiltrosPesquisa[] = "db86_cep = '$cep'";
    }

    if(!empty($municipio)) {
        $whereFiltrosPesquisa[] = "db72_descricao ilike '$municipio%'";
    }

    if(!empty($logradouro)) {
        $whereFiltrosPesquisa[] = "db74_descricao ilike '$logradouro%'";
    }

    if(!empty($numero)) {
        $whereFiltrosPesquisa[] = "db75_numero = '$numero'";
    }

    if(isset($campos) == false) {

        if(file_exists("funcoes/db_func_cadenderruacep_personalizada.php") == true) {
            require_once("funcoes/db_func_cadenderruacep_personalizada.php");
        } else {
            $campos = "cadenderruacep.*";
        }
    }

    $where = implode(' and ', $whereFiltrosPesquisa);
    $daoCep = new cl_cadenderruacep;
    //$sql = $daoCep->sql_query_cep(null, $campos, null, $where);

    /*
      criado outro metodo na classe
      na rotina DB:PATRIMONIAL > Protocolo > Cadastros > Geral do Município - CGM (novo) > Alteração
        lancar > Pesquisa CEP
      nao necessita ir na cadenderlocal , aqui não busca nenhum campo desta tabela
       e faz o usuario acretitar estar duplicando o cadastro de logradouros
     */
    $sql = $daoCep->sql_query_cepSemCadEnderLocal(null, $campos, null, $where);



    echo '<div class="container">';
    echo '  <fieldset>';
    echo '      <legend>Resultado</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe");
    echo '  </fieldset>';
    echo '</div>';
