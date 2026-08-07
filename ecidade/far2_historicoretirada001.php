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
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_far_farmacia_classe.php"));

db_postmemory($_POST);

$rotulo = new rotulocampo;
$rotulo->label("z01_i_cgsund");
$rotulo->label("fa06_i_matersaude");
$rotulo->label("fa13_i_departamento");
$rotulo->label("fa13_i_codigo");
$rotulo->label("z01_v_nome");
$rotulo->label("descrdepto");

?>
<html lang="">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
</head>
<body class="body-default">
  <div class="container">
      <form name="form1" method="post" action="">
          <fieldset>
              <legend>Filtros</legend>
              <table class="form-container">
                  <tr style="display: none">
                      <td>
                          Ordenar por:
                      </td>
                      <td align='center'>
                            <?php
                              $aX = array('1'=>'CGS', '2'=>'MEDICAMENTO');
                              db_select('ordem', $aX, true, 1, '');
                            ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="field-size3">
                          <label for="data_inicio">Período de Retirada:</label>
                      </td>
                      <td>
                          <?php db_inputdata('data_inicio', '', '', '', true, 'text', 1, ""); ?>
                          <label for="data_fim"> até </label>
                          <?php db_inputdata('data_fim', '', '', '', true, 'text', 1, ""); ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="field-size3">
                          <label for="cbxTotalizadores">Somente Totalizadores:</label>
                      </td>
                      <td>
                          <select id="cbxTotalizadores" style="width: 90px;">
                              <option value="false">Não</option>
                              <option value="true">Sim</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                <td class="bold" nowrap='nowrap'>
                    <b>Tipo de Receita:</b>
                </td>
                <td nowrap='nowrap'>
                    <select name="fa04_i_tiporeceita" id="fa04_i_tiporeceita" rel='ignore-css'>
                        <option value="">TODOS</option>
                    </select>
                </td>
            </tr>
                  <tr>
                      <td colspan="2">
                          <br>
                            <div id="status-microarea" class="alert-danger" style="text-align: center;" role="alert" hidden>
                                Paciente sem cadastro em uma microárea!
                            </div>
                          <div id="lancadorPaciente"></div>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="2">
                          <br>
                          <div id="lancadorMedicamento"></div>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="2">
                          <br>
                          <div id="lancadorDepartamento"></div>
                      </td>
                  </tr>
              </table>
          </fieldset>
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandaDados();" >
      </form>
  </div>
<?php
db_menu();
?>
</body>
</html>
<script rel="script" type="text/javascript" src="scripts/classes/saude/ValidaCgs.js"></script>
<script>
    var lancadorPaciente = new DBLancador("lancadorPaciente");
    lancadorPaciente.setNomeInstancia("lancadorPaciente");
    lancadorPaciente.setLabelAncora("Paciente:");
    lancadorPaciente.setLabelValidacao("Paciente");
    lancadorPaciente.setTextoFieldset("Pacientes");
    lancadorPaciente.setParametrosPesquisa("func_cgs_und.php", ['z01_i_cgsund', 'z01_v_nome']);
    lancadorPaciente.setCallbackSelecao(() => {
        document.getElementById('txtCodigolancadorPaciente').dispatchEvent(new Event('change'));
    });
    lancadorPaciente.setGridHeight("100px");
    lancadorPaciente.setTituloJanela("Pesquisar Pacientes");
    lancadorPaciente.withIcon = true;
    lancadorPaciente.show($("lancadorPaciente"));

    var lancadorMedicamento = new DBLancador("lancadorMedicamento");
    lancadorMedicamento.setNomeInstancia("lancadorMedicamento");
    lancadorMedicamento.setLabelAncora("Medicamento:");
    lancadorMedicamento.setLabelValidacao("Medicamento");
    lancadorMedicamento.setTextoFieldset("Medicamentos");
    lancadorMedicamento.setParametrosPesquisa("func_far_matersaude.php", ['fa01_i_codigo', 'm60_descr'], 'lancador');
    lancadorMedicamento.setGridHeight("100px");
    lancadorMedicamento.setTituloJanela("Pesquisar Medicamentos");
    lancadorMedicamento.withIcon = true;
    lancadorMedicamento.show($("lancadorMedicamento"));

    var lancadorDepartamento = new DBLancador("lancadorDepartamento");
    lancadorDepartamento.setNomeInstancia("lancadorDepartamento");
    lancadorDepartamento.setLabelAncora("Departamento:");
    lancadorDepartamento.setLabelValidacao("Departamento");
    lancadorDepartamento.setTextoFieldset("Departamentos");
    lancadorDepartamento.setParametrosPesquisa("func_unidades.php", ['sd02_i_codigo', 'descrdepto']);
    lancadorDepartamento.setGridHeight("100px");
    lancadorDepartamento.setTituloJanela("Pesquisar Departamentos");
    lancadorDepartamento.withIcon = true;
    lancadorDepartamento.show($("lancadorDepartamento"));

const divAlert = document.getElementById('status-microarea');
const inputCgs = {
    id: document.getElementById('txtCodigolancadorPaciente'),
    nome: document.getElementById('txtDescricaolancadorPaciente')
};

const selectReceitas = document.getElementById("fa04_i_tiporeceita");

const validaCgs = new ValidaCgs(inputCgs);

window.onload = () => {
    validaCgs.cadastroMicroarea(inputCgs, divAlert);
    buscarReceitas();
}

function js_validadata() {

  if(document.form1.data_inicio.value == "" || document.form1.data_fim.value == "") {

    alert('Os campos data de início e de fim devem ser preenchidos.');
    document.form1.data_inicio.focus();
    return false;
  }

  inicio = new Date(document.form1.data_inicio.value.substring(6,10),
                    document.form1.data_inicio.value.substring(3,5),
                    document.form1.data_inicio.value.substring(0,2));
  fim    = new Date(document.form1.data_fim.value.substring(6,10),
                    document.form1.data_fim.value.substring(3,5),
                    document.form1.data_fim.value.substring(0,2));

  if( inicio > fim) {

    alert('A data de início está maior que a data de Fim.');
    document.form1.data_inicio.value = '';
    document.form1.data_fim.value    = '';
    document.form1.data_inicio.focus();
    return false;
  }

  return true;
}

function js_mandaDados() {

  if(js_validadata()) {

    var sChave = 'datas='+document.form1.data_inicio.value+','+document.form1.data_fim.value;

    var pacientes = lancadorPaciente.getRegistros().map(function (obj) {
      return obj.sCodigo;
    }).join(',');

    var medicamentos = lancadorMedicamento.getRegistros().map(function (obj) {
      return obj.sCodigo;
    }).join(',');

    var departamentos = lancadorDepartamento.getRegistros().map(function (obj) {
      return obj.sCodigo;
    }).join(',');

    if (pacientes) {
        sChave += '&iCgs=' + pacientes;
    }

    if (medicamentos) {
        sChave += '&medicamentos=' + medicamentos;
    }

    if (departamentos) {
        sChave += '&departamentos=' + departamentos;
    }

    sChave += '&ordem=' + document.form1.ordem.value;
    sChave += '&somenteTotalizadores=' + $F('cbxTotalizadores');
    sChave += '&tipoDeReceita=' + selectReceitas.value;
    sChave += '&descricaoReceita=' + selectReceitas.options[selectReceitas.selectedIndex].text;
    oJan    = window.open('far2_historicoretirada002.php?'+sChave, '', 'width='+(screen.availWidth-5)+',height='+
                       (screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJan.moveTo(0, 0);
  }
}

async function buscarReceitas(){
    const formData = new FormData();
    formData.append("acao", "getTiposDeReceita");

    const resposta = await HttpClient.post("far_retirada.RPC.php", {body: formData});

    if (resposta.erro) {
        alert(resposta.mensagem);
        return;
    }

    for (const receita of resposta.receitas) {
        selectReceitas.add(new Option(receita.descricao, receita.codigo));
    }

}
</script>
