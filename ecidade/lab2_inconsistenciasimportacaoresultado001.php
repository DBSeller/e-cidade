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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));


$clrotulo = new rotulocampo;

$clrotulo->label("la02_i_codigo");
$clrotulo->label("la24_i_setor");
$clrotulo->label("la24_c_descr");
$clrotulo->label("la24_i_codigo");
$clrotulo->label("la02_c_descr");
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC">
    <div class="container">
        <form name="form1" method="post" class="form-container">
            <fieldset style="width: 67%;">
                <legend>Inconsistências da Requisição</legend>
                <table>
                    <tr>
                        <td>
                            <?php
                            db_ancora( '<b>Laboratório:</b>', "js_pesquisala02_i_laboratorio(true);", "" );
                            ?>
                        </td>
                        <td>
                            <?php
                            db_input( 'la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "", "onchange='js_pesquisala02_i_laboratorio(false);'");
                            ?>
                        </td>
                        <td>
                            <?php
                            db_input( 'la02_c_descr',  50, @$Ila02_c_descr, true, 'text', 3 );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            db_ancora(@$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "");
                            ?>
                        </td>
                        <td>
                            <?php
                            db_input( 'la24_i_setor',  10, $Ila24_i_setor,  true, 'text', "", "onchange='js_pesquisala24_i_setor(false);'");
                            ?>
                        </td>
                        <td>
                            <?php
                            db_input( 'la24_i_codigo', 10, '',              true, 'hidden', '' );
                            db_input( 'la23_c_descr',  38, @$Ila23_c_descr, true, 'text', 3 );
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="gerar" type="button" id="gerar" value="Gerar" onclick="js_mandaDados();">
        </form>
    </div>
</body>
</html>

<script>
    function js_limpaCamposTrocaLab() {
        document.form1.la24_i_setor.value  = '';
        document.form1.la24_i_codigo.value = '';
    }

    function js_pesquisala02_i_laboratorio(mostra) {
        if( mostra == true ) {
            js_OpenJanelaIframe(
                '',
                'db_iframe_lab_laboratorio',
                'func_lab_laboratorio.php?checkLaboratorio=true'
                +'&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                'Pesquisa',
                true
            );
        } else {
            if( document.form1.la02_i_codigo.value != '' ) {
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_lab_laboratorio',
                    'func_lab_laboratorio.php?checkLaboratorio=true'
                    +'&pesquisa_chave='+document.form1.la02_i_codigo.value
                    +'&funcao_js=parent.js_mostralaboratorio',
                    'Pesquisa',
                    false
                );
            } else {
                document.form1.la02_c_descr.value = '';
                js_limpaCamposTrocaLab();
            }
        }
    }

    function js_mostralaboratorio(chave, erro) {
        document.form1.la02_c_descr.value = chave;

        if( erro == true ) {
            document.form1.la02_i_codigo.focus();
            document.form1.la02_i_codigo.value = '';
        }
    }

    function js_mostralaboratorio1(chave1, chave2) {
        document.form1.la02_i_codigo.value = chave1;
        document.form1.la02_c_descr.value  = chave2;
        db_iframe_lab_laboratorio.hide();
    }

    function js_pesquisala24_i_setor(mostra) {

        if( empty( $F('la02_i_codigo') ) ) {
            alert( 'É necessário informar um laboratório.' );
            $('la24_i_setor').value = '';

            return;
        }

        sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';

        if( mostra == true ) {
            js_OpenJanelaIframe(
                '',
                'db_iframe_lab_labsetor',
                'func_lab_labsetor.php?'+sPesq+'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor'
                +'|la23_c_descr|la24_i_codigo',
                'Pesquisa',
                true
            );
        } else {
            if(document.form1.la24_i_setor.value != '') {
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_lab_labsetor',
                    'func_lab_labsetor.php?'+sPesq
                    +'pesquisa_chave='+document.form1.la24_i_setor.value
                    +'&funcao_js=parent.js_mostralab_labsetor',
                    'Pesquisa',
                    false
                );
            } else {
                $('la24_i_codigo').value          = '';
                document.form1.la23_c_descr.value = '';
            }
        }
    }

    function js_mostralab_labsetor(chave, erro, chave2) {
        document.form1.la23_c_descr.value  = chave;
        document.form1.la24_i_codigo.value = chave2;

        if (erro == true) {
            document.form1.la24_i_setor.focus();
            document.form1.la24_i_setor.value  = '';
            document.form1.la24_i_codigo.value = '';
        }
    }

    function js_mostralab_labsetor1( chave1, chave2, chave3 ) {
        document.form1.la24_i_setor.value = chave1;
        document.form1.la24_i_codigo.value = chave3;
        document.form1.la23_c_descr.value = chave2;
        db_iframe_lab_labsetor.hide();
    }

    function js_mandaDados() {
        if ($('la24_i_setor').value && !$('la02_i_codigo').value) {
            return alert('Por favor informar o laboratório.');
        } else if (!$('la24_i_setor').value && !$('la02_i_codigo').value) {
            return alert('Necessário informar pelo menos o laboratório.');
        }

        var parametros = '';
        parametros += 'laboratorio=' + $('la02_i_codigo').value;
        parametros += '&setor=' + $('la24_i_setor').value;
        parametros += '&flag=menuRelatorio';

        var janela = window.open(
            'lab2_inconsistenciasimportacaoresultado002.php?' + parametros,
            '',
            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
        );
        janela.moveTo(0, 0);
    }
</script>
