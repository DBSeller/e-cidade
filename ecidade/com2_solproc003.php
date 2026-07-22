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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_caracter_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_solicita_classe.php"));
$clsolicita = new cl_solicita;
$cliframe_seleciona = new cl_iframe_seleciona;

$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
    </style>
</head>
<body>
<div class="container">
    <form name="form1" method="post" action="com2_solproc002.php" target="rel">
        <fieldset>
            <legend>Solicitações em processo</legend>

            <div>
                <?php
                $aux = new cl_arquivo_auxiliar;
                $aux->cabecalho = "<strong>Departamentos</strong>";
                $aux->codigo = "coddepto";
                $aux->descr = "descrdepto";
                $aux->nomeobjeto = 'depart';
                $aux->funcao_js = 'js_mostra';
                $aux->funcao_js_hide = 'js_mostra1';
                $aux->sql_exec = "";
                $aux->func_arquivo = "func_db_depart.php";
                $aux->nomeiframe = "db_iframe_depart";
                $aux->localjan = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = 2;
                $aux->linhas = 5;
                $aux->funcao_gera_formulario();
                ?>

            </div>

            <div style="
                margin-top: 10px;
                display: flex;
                flex-direction: column;
                align-items: start;
                justify-content: center"
            >

                <div>
                    <label for="pc10_dataINI"><b>Solicitações de: </b></label>
                    <?php
                    $dataInicial = date('d-m-Y', strtotime('first day of this month'));
                    $dataInicial = explode('-', $dataInicial);

                    db_inputdata(
                        'pc10_dataINI',
                        $dataInicial[0],
                        $dataInicial[1],
                        $dataInicial[2],
                        true,
                        'text',
                        1
                    );
                    ?>

                    <label for="pc10_dataFIM" style="right: 0"><b>Até:</b> </label>
                    <?php
                    $dataFinal = date('d-m-Y');
                    $dataFinal = explode('-', $dataFinal);
                    db_inputdata(
                        'pc10_dataFIM',
                        $dataFinal[0],
                        $dataFinal[1],
                        $dataFinal[2],
                        true,
                        'text',
                        1
                    );
                    ?>
                </div>

                <div style="margin-top: 5px">
                    <label for="param_depart"><b>Departamentos: </b></label>

                    <?php
                    $opcaoDepartamento = [
                        "S" => "Somente Selecionados",
                        "N" => "Menos os Selecionados"
                    ];

                    db_select('param_depart', $opcaoDepartamento, true, 2);
                    ?>
                </div>

                <div style="margin-top: 5px">
                    <label for="ordem"><b>Ordenar por: </b></label>
                    <select name="ordem" style="margin-left: 18px">
                        <option value="pc10_data">Data de emissão</option>
                        <option value="pc10_numero">Número da solicitação</option>
                        <option value="login">Usuário</option>
                        <option value="descrdepto">Departamento</option>
                    </select>
                </div>

                <div style="margin-top: 5px;">
                    <label for="listar"><b>Listar itens:</b> </label>
                    <select name="listar" style="margin-left: 23px">
                        <option value="s">Sim</option>
                        <option value="n">Não</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <div class="subcontainer" style="margin-top:10px">
            <input
                type="submit"
                name="relatorio1"
                value="Gerar Relatório"
                onClick="return imprime();"
            >
        </div>

        <?php
        db_input('inp_depart', "", 0, true, 'hidden', 3, "");
        db_input('par_depart', "", 0, true, 'hidden', 3, "");
        ?>
        <br>
        <?php
        db_input('inp_usuarios', "", 0, true, 'hidden', 3, "");
        db_input('par_usuarios', "", 0, true, 'hidden', 3, "");
        ?>
        <br>
    </form>
</div>
<script>
    //função para buscar dados dos iframes        action = "com2_solproc002.php" target="rel"
    function buscar_dados(frame, campo, obj, campo_inp, campo_par, campo_cod) {
        let cods = "";
        let vir = "";
        let var_obj = eval("parent.iframe_" + frame + ".document.getElementById('" + obj + "').length");

        for (let y = 0; y < var_obj; y++) {
            let var_if = eval(
                "parseInt(parent.iframe_" + frame + ".document.getElementById('" + obj + "').options[y].value)"
            );
            cods += vir + var_if;
            vir = ",";
        }

        eval("parent.iframe_g1.document.form1." + campo_inp + ".value =cods");
        eval(
            "parent.iframe_g1.document.form1."
            + campo_par
            + ".value =parent.iframe_"
            + frame
            + ".document.form1.param_"
            + obj
            + ".value"
        );
    }

    function imprime() {
        let dtini = "";
        let dtfim = "";
        let x = document.form1

        if (x.pc10_dataINI.value === '') {
            alert('Informe a data inicial!');
            return false;
        }

        if (x.pc10_dataINI_dia.value !== "" && x.pc10_dataINI_mes.value !== "" && x.pc10_dataINI_ano.value !== "") {
            dtini = new Date(x.pc10_dataINI_ano.value, x.pc10_dataINI_mes.value - 1, x.pc10_dataINI_dia.value);
        }
        if (x.pc10_dataFIM_dia.value !== "" && x.pc10_dataFIM_mes.value !== "" && x.pc10_dataFIM_ano.value !== "") {
            dtfim = new Date(x.pc10_dataFIM_ano.value, x.pc10_dataFIM_mes.value - 1, x.pc10_dataFIM_dia.value);
        }

        if (dtini !== "" && dtfim !== "" && dtfim < dtini) {
            alert("ERRO: Informe um intervalo de data válido!");
            x.pc10_dataINI_dia.focus();
            return false;
        }
        for (let i = 0; i < x.length; i++) {
            if (x.elements[i].type === "text") {
                if (x.elements[i].name.indexOf('inp_') !== -1 || x.elements[i].name.indexOf('par_') !== -1) {
                    x.elements[i].value = "";
                }
            }
        }
        buscar_dados("g1", "coddepto", "depart", "inp_depart", "par_depart");
        buscar_dados("g2", "id_usuario", "db_usuarios", "inp_usuarios", "par_usuarios");

        let jan = window.open(
            '',
            'rel',
            'width='
            + (screen.availWidth - 5)
            + ',height='
            + (screen.availHeight - 40)
            + ',scrollbars=1,location=0'
        );

        jan.moveTo(0, 0);
    }
</script>
</body>
</html>
