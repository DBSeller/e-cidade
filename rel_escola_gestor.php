<?php
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$lDbOpcao = 1;
?>
<html>
<head>
    <title>DBSeller Sistemas Integrados</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0"/>

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body class="body-default">
<div class="container">
    <fieldset>
        <legend>Listagem de Corpo Gestor</legend>
        <form name="frm">
            <table class="form-container">
                <tr>
                    <td>
                        <label for="codEscolaBairro">Busca (Cod. Ref/Escola/Bairro): </label>
                    </td>
                    <td>
                        <input title="Nome da Escola"
                               name="codEscolaBairro"
                               id="codEscolaBairro"
                               type="text"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="distrito">Distrito: </label>
                    </td>
                    <td>
                        <input title="Distrito"
                               name="distrito"
                               id="distrito"
                               type="text"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="selectZona">Zona de Residência: </label>
                    </td>
                    <td>
                      <select name="selectZona" id="selectZona" style="width: 159px;">
                          <option value="0">Selecione </option>
                          <option value="0">Todas </option>
                          <option value="1">Urbana </option>
                          <option value="2">Rural </option>
                      </select>
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Opções</legend>
                <div id="filtros" align="left">
                    <input name="chk_escolas_bairro" id="chk_escolas_bairro" type="checkbox">Escolas por bairro<br/>
                    <input name="chk_diretor" id="chk_diretor" type="checkbox">Diretor<br/>
                    <input name="chk_diretor_interino" id="chk_diretor_interino" type="checkbox">Diretor Interino<br/>
                    <input name="chk_diretor_adjunto" id="chk_diretor_adjunto" type="checkbox">Diretor Adjunto<br/>
                    <input name="chk_orientador" id="chk_orientador" type="checkbox">Orientador<br/>
                    <input name="chk_corpo_gestor_completo" id="chk_corpo_gestor_completo" type="checkbox">Corpo Gestor
                    Completo<br/>
                    <input name="chk_funcional" id="chk_funcional" type="checkbox">
                    Quadro Funcional Completo
                    <p style="font-weight: bold;"> * Para gerar lista simples n&atildeo selecione os campos acima</p>
                    <br/>
                    <input name="chk_alunos" id="chk_alunos" type="checkbox">N. Alunos<br/>
                    <div>
                        <select multiple name="calendario" id="calendario">
                            <?php
                            $codigo_escola = db_getsession("DB_coddepto");
                            $ano_atual = date('Y');
                            $sql = "SELECT distinct(ed52_c_descr) as nomecal
	                            FROM calendario
	                                WHERE ed52_i_ano = $ano_atual
	                                ORDER BY ed52_c_descr ";

                            $result_calendario = pg_query($conn, $sql);

                            while ($row_calendario = pg_fetch_array($result_calendario)) {
                                $nomecal = $row_calendario['nomecal'];
                                echo "<option value='$nomecal'>$nomecal</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <p style="font-weight: bold;">
                        * Para gerar o n&uacutemero de alunos &eacute necess&aacuterio selecionar calend&aacuterio. <br>
                        Para selecionar mais de um calend&aacuterio pressione CTRL e escolha os desejados
                    </p><br/>
                    <input type="radio" name="opt_escola" value="1">ESCOLAS (somente escolas) <br/>
                    <input type="radio" name="opt_escola" value="2">CEIs (somente Centro de Ed. Infantil) <br/>
                    <input type="radio" name="opt_escola" value="3" checked>ESCOLAS e CEI (ambos) <br/>
                    <br/>
                    <button type="button" id="processar">Gerar listagem</button>
                </div>
            </fieldset>
        </form>
    </fieldset>
</div>
<?php db_menu(); ?>
<script type="text/javascript">
    const btnProcessar = document.getElementById('processar');

    const chkEscolasBairro = document.getElementById('chk_escolas_bairro');
    const chkDiretor = document.getElementById('chk_diretor');
    const chkDiretor_interino = document.getElementById('chk_diretor_interino');
    const chkDiretor_adjunto = document.getElementById('chk_diretor_adjunto');
    const chkOrientador = document.getElementById('chk_orientador');
    const chkCorpoGestorCompleto = document.getElementById('chk_corpo_gestor_completo');
    const chkFuncional = document.getElementById('chk_funcional');

    const chkAlunos = document.getElementById('chk_alunos');
    const cboCalendario = document.getElementById('calendario');
    const codEscolaBairro = document.getElementById('codEscolaBairro');
    const distritoNome = document.getElementById('distrito');

    const selectZona = document.querySelector('#selectZona');

    chkAlunos.addEventListener('change', (e) => {
        if (!e.target.checked) {
            let countItens = cboCalendario.length;
            for (let x = 0; x < countItens; x++) {
                cboCalendario.options[x].selected = false;
            }
        }
    });

    chkCorpoGestorCompleto.addEventListener('change', (e) => {
        if (e.target.checked) {
            chkDiretor.checked = true;
            chkDiretor_interino.checked = true;
            chkDiretor_adjunto.checked = true;
            chkOrientador.checked = true;
            return;
        }
        chkDiretor.checked = false;
        chkDiretor_interino.checked = false;
        chkDiretor_adjunto.checked = false;
        chkOrientador.checked = false;
        chkFuncional.checked = false;
    });

    chkFuncional.addEventListener('change', (e) => {
        if (e.target.checked) {
            chkDiretor.checked = true;
            chkDiretor_interino.checked = true;
            chkDiretor_adjunto.checked = true;
            chkOrientador.checked = true;
            chkFuncional.checked = true;
            chkCorpoGestorCompleto.checked = true;
            return;
        }
        chkDiretor.checked = false;
        chkDiretor_interino.checked = false;
        chkDiretor_adjunto.checked = false;
        chkOrientador.checked = false;
        chkFuncional.checked = false;
        chkCorpoGestorCompleto.checked = false;
    });

    chkDiretor.addEventListener('change', (e) => {
        if (!e.target.checked) {
            chkCorpoGestorCompleto.checked = false;
            chkFuncional.checked = false;
        }
    });
    chkDiretor_interino.addEventListener('change', (e) => {
        if (!e.target.checked) {
            chkCorpoGestorCompleto.checked = false;
            chkFuncional.checked = false;
        }
    });
    chkDiretor_adjunto.addEventListener('change', (e) => {
        if (!e.target.checked) {
            chkCorpoGestorCompleto.checked = false;
            chkFuncional.checked = false;
        }
    });
    chkOrientador.addEventListener('change', (e) => {
        if (!e.target.checked) {
            chkCorpoGestorCompleto.checked = false;
            chkFuncional.checked = false;
        }
    });

    btnProcessar.addEventListener('click', () => {
        const chk_escolas_bairro = document.frm.chk_escolas_bairro.checked;
        const chk_diretor = document.frm.chk_diretor.checked;
        const chk_diretor_interino = document.frm.chk_diretor_interino.checked;
        const chk_diretor_adjunto = document.frm.chk_diretor_adjunto.checked;
        const chk_orientador = document.frm.chk_orientador.checked;
        const chk_corpo_gestor_completo = document.frm.chk_corpo_gestor_completo.checked;
        const chk_funcional = document.frm.chk_funcional.checked;
        const chk_alunos = document.frm.chk_alunos.checked;

        const zona = selectZona.value;
        let countItens = cboCalendario.length;

        let calendarios = [];
        for (let x = 0; x < countItens; x++) {
            if (cboCalendario.options[x].selected) {
                let valor = cboCalendario.options[x].value;
                calendarios.push(encodeURIComponent(tagString(valor)));
            }
        }

        const opt_escola = document.frm.opt_escola.value;
        jan = window.open(
            'sec2_escola_gestor002.php?' +
            'codEscolaBairro=' + codEscolaBairro.value + '&' +
            'nome_distrito=' + distritoNome.value + '&' +
            'chk_escolas_bairro=' + chk_escolas_bairro + '&' +
            'chk_diretor=' + chk_diretor + '&' +
            'chk_diretor_interino=' + chk_diretor_interino + '&' +
            'chk_diretor_adjunto=' + chk_diretor_adjunto + '&' +
            'chk_orientador=' + chk_orientador + '&' +
            'chk_corpo_gestor_completo=' + chk_corpo_gestor_completo + '&' +
            'chk_funcional=' + chk_funcional + '&' +
            'chk_alunos=' + chk_alunos + '&' +
            'calendarios=' + calendarios.join(',') + '&' +
            'opt_escola=' + opt_escola + '&' +
            'zona=' + zona,
            '',
            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
        );
        jan.moveTo(0, 0);
    });

</script>
</body>
</html>
