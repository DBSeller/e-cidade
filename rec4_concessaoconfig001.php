<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" href="assets/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css" href="estilos.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css" rel="stylesheet">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>

</head>
<style>
    .hidden {
        display: none;
        visibility: hidden;
    }

    span {
        font-size: 10;
    }

    .DBAncora strong {
        text-decoration: none;
        display: block;
        color: blue;
        text-decoration: none;
    }
</style>

<body class="body-default">
    <div id="abas"></div>
    <div id="aba_assentamentos">
        <div class="container">
            <form name="form1" method="post">
                <table align="center" border="0" cellspacing="4" cellpadding="0">
                    <tr>
                        <td>
                            <label id="ancoraassentamento" class='bold m-2'
                            onclick="js_pesquisa_assentamento(true);" for="assentamento">
                                <a href="#">Assentamento : </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" onchange="js_pesquisa_assentamento(false);"
                            onkeyup="js_ValidaMaiusculo(this,'f',event);"
                            oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);"
                            onkeydown="return js_controla_tecla_enter(this,event);"
                            id="assentamento" name="assentamento"
                            lang="h12_codigo" class="field-size2">
                            <input type="text" id="descricaoassentamento" lang="h12_descr"
                            class="field-size8 readonly" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="ancoraconcessao" class='m-2 bold'
                            onclick="js_pesquisa_concessao(true);" for="concessao">
                                <a href="#">Concessão : </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" onchange="js_pesquisa_concessao(false);"
                            onkeyup="js_ValidaMaiusculo(this,'f',event);"
                            oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);"
                            onkeydown="return js_controla_tecla_enter(this,event);"
                            id="concessao" name="concessao" lang="h12_codigo" class="field-size2">
                            <input type="text" id="descricaoconcessao" 
                            lang="h12_descr" class="field-size8 readonly" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="ancoranaoconcessao" class=' m-2 bold' 
                            onclick="js_pesquisa_naoconcessao(true);" for="naoconcessao">
                                <a href="#">Não Concessão : </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" onchange="js_pesquisa_naoconcessao(false);"
                            onkeyup="js_ValidaMaiusculo(this,'f',event);"
                            oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);"
                            onkeydown="return js_controla_tecla_enter(this,event);"
                            id="naoconcessao" name="naoconcessao" lang="h12_codigo" class="field-size2">
                            <input type="text" id="descricaonaoconcessao"
                            lang="h12_descr" class="field-size8 readonly" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="ancoraselecao" class='bold m-2'
                            onclick="js_geraform_pesquisaselecao(true);" for="assentamento">
                                <a href="#">Seleção : </a>
                            </label>
                        </td>
                        <td>
                            <input type="text" id="selecao" title="Seleção Campo:r44_selec" 
                            onchange="js_geraform_pesquisaselecao(false,1);" 
                            onkeyup="js_ValidaMaiusculo(this,'f',event);" 
                            oninput="js_ValidaCampos(this,1,'Seleção','f','f',event);" 
                            onkeydown="return js_controla_tecla_enter(this,event);" 
                            labelvalidacao="Seleção" lang="Seleção" class="field-size2">
                            <input type="text" id="descricaoselecao" name="r44_descr" 
                            lang="r44_descr" class="field-size8 readonly" 
                            title="Descrição Campo:r44_descr"  readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="m-2"> Data Limite</p>
                        </td>
                        <td>
                            <?php
                            $dataprocessamento = date("Y-m-d");
                            $dataprocessamento_dia = date("d");
                            $dataprocessamento_mes = date("m");
                            $dataprocessamento_ano = date("Y");
                            db_inputdata(
                                'datalimite',
                                $dataprocessamento_dia,
                                $dataprocessamento_mes,
                                $dataprocessamento_ano,
                                true,
                                'text',
                                2
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <button type="reset" class="m-2 addaba1">
                                Limpar
                            </button>
                            <button type="button" onClick="atualizarAssent()" 
                            class="m-2">Salvar</button>
                            <button style="display: none;" type="button" 
                            onClick="cancelarformAba1()" class="m-2 altearaba1">Novo</button>
                        </td>
                    </tr>
                </table>
            </form>
            <table id="tableAba1"></table>
        </div>
    </div>
    <!--  Proxima Aba -->
    <div id="aba_intervalos">
        <?php include 'rec4_concessaoconfig002.php'; ?>
    </div>
    <div id="aba_configuracao" class="container">
        <?php include 'rec4_concessaoconfig003.php'; ?>
    </div>
    <div id="aba_validacao" class="container">
        <?php include 'rec4_concessaoconfig004.php'; ?>
    </div>
    <?php
    db_menu(
        db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
    );
    ?>
</body>

</html>
<script>
    const url = '<?= ECIDADE_REQUEST_PATH ?>';
    const DB_instit = '<?= (db_getsession("DB_instit")) ?>';
</script>
<script src="scripts/classes/recursoshumanos/concessaodireitos/script.js"></script>
<script src="scripts/classes/recursoshumanos/concessaodireitos/script_aba4.js"></script>
<script src="scripts/classes/recursoshumanos/concessaodireitos/script_aba3.js"></script>
<script src="scripts/classes/recursoshumanos/concessaodireitos/script_aba2.js"></script>
<script src="scripts/classes/recursoshumanos/concessaodireitos/script_aba1.js"></script>