<?php
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/db_utils.php'));

$oRotulo           = new rotulocampo;
$oRotulo->label("la02_i_codigo");
$oRotulo->label("la24_i_setor");
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <!-- PLUGIN AUTENTICADORA - Adicionando script scripts/autenticadora-client.js -->
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
    <form name='form1' class="container">
        <fieldset>
            <legend>Mapa de Coleta</legend>
            <table class="form-container">
                <tr>
                    <td class="bold">
                        Período:
                    </td>
                    <td>
                        <?php
                        db_inputdata('dData1', @$iDia1, @$iMes1, @$iAno1, true, 'text', 1, "onChange='atualizaDataFinal();'", '', '', 'parent.atualizaDataFinal()');
                        ?>
                        A
                        <?php
                        db_inputdata('dData2', @$iDia2, @$iMes2, @$iAno2, true, 'text', 1, "");
                        ?>
                    </td>
                </tr>
                <tr></tr>
                <tr >
                    <td class="bold" nowrap title="Laborat&oacute;rio">
                        <?php
                        db_ancora('<b>Laborat&oacute;rio:</b>', 'js_pesquisala02_i_laboratorio(true);', '');
                        ?>
                    </td>
                    <td>
                        <?php
                        db_input('la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "",
                            " onchange='js_pesquisala02_i_laboratorio(false);'"
                        );
                        db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="bold" nowrap title="<?=@$Tla24_i_setor?>">
                        <?php
                        db_ancora(@$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "");
                        ?>
                    </td>
                    <td>
                        <?php
                        db_input('la24_i_setor', 10, $Ila24_i_setor, true, 'text', "",
                            " onchange='js_pesquisala24_i_setor(false);'"
                        );
                        db_input('la24_i_codigo', 10, '', true, 'hidden', '', '');
                        db_input('la23_c_descr', 50, @$Ila23_c_descr, true, 'text', 3, '');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="bold" nowrap title="Modelo">
                        Modelo:
                    </td>
                    <td>
                        <select name="modelo" id="modelo">
                            <option value="1" selected>PDF</option>
                            <option value="2">Matricial</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input id="start" name="start" type="button" value="Gerar" onclick="js_mandaDados()" >
    </form>
</body>
</html>

<script>
    function js_validadata() {
        if (document.form1.dData1.value != '' && document.form1.dData2.value != '') {
            aIni = document.form1.dData1.value.split('/');
            aFim = document.form1.dData2.value.split('/');
            dIni = new Date(aIni[2], aIni[1], aIni[0]);
            dFim = new Date(aFim[2], aFim[1], aFim[0]);

            if (dFim < dIni) {
                alert("A data final não pode ser menor que a data inicial.");
                $('dData2').value = '';

                return false;
            }

            return true;
        }

        alert('Preencha o periodo.');

        return false
    }

    function js_mandaDados() {
        if (!js_validadata()) {
            return false;
        }

        var mensagemErro = '';
        if (!$('la02_i_codigo').value) {
            mensagemErro += 'Laboratório';
        }

        if (mensagemErro) {
            mensagemErro = 'Os seguintes campos não foram informados: ' + mensagemErro;
            return alert(mensagemErro);
        }

        var parametros = 'dataInicial=' + $('dData1').value;
        parametros += '&dataFinal=' + $('dData2').value;
        parametros += '&laboratorio=' + $('la02_i_codigo').value;
        parametros += '&setor=' + $('la24_i_setor').value;
        parametros += '&modelo=' + $('modelo').value;

        if ($('modelo').value == 1) {
            var janela = window.open(
                'lab2_mapacoletaconsulta.php?' + parametros,
                '',
                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
            );
            janela.moveTo( 0, 0 );
        } else {
            HttpClient.get('lab2_mapacoletaconsulta.php?' + parametros).then(retorno => {
                if (retorno.erro || !retorno.utilizarAutenticadoraNova) {
                    return alert(retorno.mensagem);
                }

                // PLUGIN AUTENTICADORA - Conectando com AutenticadoraClient
            });
        }
    }

    function js_pesquisala02_i_laboratorio(lMostra) {
        if (lMostra == true) {
            js_OpenJanelaIframe(
                '',
                'db_iframe_lab_laboratorio',
                'func_lab_laboratorio.php?checkLaboratorio=true'
                + '&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                'Pesquisa',
                true
            );
        } else {
            if (document.form1.la02_i_codigo.value != '') {
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_lab_laboratorio',
                    'func_lab_laboratorio.php?checkLaboratorio=true&pesquisa_chave='
                    + document.form1.la02_i_codigo.value+'&funcao_js=parent.js_mostralaboratorio',
                    'Pesquisa',
                    false
                );
            } else {
                document.form1.la02_c_descr.value = '';
            }
        }

    }

    function js_mostralaboratorio(la02_c_descr, lErro) {
        document.form1.la02_c_descr.value = la02_c_descr;
        if (lErro == true) {
            document.form1.la02_i_codigo.focus();
            document.form1.la02_i_codigo.value = '';
        }
        $('la24_i_setor').value = '';
        $('la23_c_descr').value = '';
    }

    function js_mostralaboratorio1(la02_i_codigo, la02_c_descr) {
        document.form1.la02_i_codigo.value = la02_i_codigo;
        document.form1.la02_c_descr.value  = la02_c_descr;
        db_iframe_lab_laboratorio.hide();
    }

    function js_pesquisala24_i_setor(lMostra) {
        if (document.form1.la02_i_codigo.value == '') {
            alert('Escolha um laboratorio primeiro.');
            js_limpaCamposTrocaLab();
            return false;
        }

        sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';
        if (lMostra == true) {
            js_OpenJanelaIframe(
                '',
                'db_iframe_lab_labsetor',
                'func_lab_labsetor.php?'
                + sPesq
                + 'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo',
                'Pesquisa',
                true
            );
        } else {
            if (document.form1.la24_i_setor.value != '') {
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_lab_labsetor',
                    'func_lab_labsetor.php?'+sPesq
                    + 'pesquisa_chave='+document.form1.la24_i_setor.value
                    + '&funcao_js=parent.js_mostralab_labsetor',
                    'Pesquisa',
                    false
                );
            } else {
                document.form1.la23_c_descr.value  = '';
                document.form1.la24_i_codigo.value = '';
            }
        }
    }

    function js_mostralab_labsetor(la23_c_descr, lErro, la24_i_codigo) {
        document.form1.la23_c_descr.value  = la23_c_descr;
        document.form1.la24_i_codigo.value = la24_i_codigo;
        if (lErro == true) {
            document.form1.la24_i_setor.focus();
            document.form1.la24_i_setor.value  = '';
            document.form1.la24_i_codigo.value = '';
        }
    }

    function js_mostralab_labsetor1(la24_i_setor, la23_c_descr, la24_i_codigo) {
        document.form1.la24_i_setor.value  = la24_i_setor;
        document.form1.la24_i_codigo.value = la24_i_codigo;
        document.form1.la23_c_descr.value  = la23_c_descr;
        db_iframe_lab_labsetor.hide();
    }

    function atualizaDataFinal() {
        document.getElementById('dData2').value = $('dData1').value;
    }

</script>
