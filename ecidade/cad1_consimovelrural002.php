<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));
require_once(modification("classes/db_iptubase_classe.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j40_refant');
$clrotulo->label('j40_registrocartografico');
$clrotulo->label('proprietario');
$clrotulo->label('z01_numimob');
$clrotulo->label('j34_zona');
$clrotulo->label('j91_codigo');
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');
$clrotulo->label('j34_lote');

db_sel_instit(db_getsession("DB_instit"), "db21_codcli");

$parametros = JSON::requestParameters();

if (!isset($parametros->matricula)) {
    db_redireciona("db_erros.php?db_erro=Matrícula não informada.");
}

$where = "j01_matric = {$parametros->matricula}";
$areaconst1 = 0;
$oDaoIptubase = new cl_iptubase();
$rsIptubase = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_proprietariolote($where));

$oDaoIptuender = new cl_iptuender();
$rsIptuender = db_query($oDaoIptuender->sql_query_file($parametros->matricula));
$oDadosEndereco = db_utils::fieldsMemory($rsIptuender, 0, null);

if ($oDaoIptubase->numrows == 0) {
    db_redireciona("db_erros.php?db_erro=Matrícula não cadastrada.");
}

$oDadosMatricula = db_utils::fieldsMemory($rsIptubase, 0, null);

$rsAreaTotal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_total($oDadosMatricula->j34_setor,
  $oDadosMatricula->j34_quadra,
  $oDadosMatricula->j34_lote));
$nAreaTotal = db_utils::fieldsMemory($rsAreaTotal, 0)->area_total;

$rsAreaConstruida = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_contruida($oDadosMatricula->j01_matric));
$nAreaConstruida = db_utils::fieldsMemory($rsAreaConstruida, 0)->area_construida;

$rsImobiliaria = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_imobiliaria($oDadosMatricula->j01_matric,
  'z01_nome'));

$lImobiliaria = false;
if ($oDaoIptubase->numrows > 0) {
    $lImobiliaria = true;
    $imobiliaria = db_utils::fieldsMemory($rsImobiliaria, 0)->z01_nome;
}
$rsSetorFiscal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_setorfiscal($oDadosMatricula->j01_matric));
if ($oDaoIptubase->numrows > 0) {
    $oSetorFiscal = db_utils::fieldsMemory($rsSetorFiscal, 0);
}

$oDaoCfIptu = db_utils::getDao('cfiptu');
$rsCfIptu = $oDaoCfIptu->sql_record($oDaoCfIptu->sql_query_file(null, "j18_utilizaloc", "",
  "j18_anousu = " . db_getsession("DB_anousu")));
$lUtilizaLoc = db_utils::fieldsMemory($rsCfIptu, 0)->j18_utilizaloc == 't' ? true : false;

$oDaoPercPosseRural = new cl_percposserural();
$sqlPercPosseRural = $oDaoPercPosseRural->sql_query_file(null, "j166_percentual", null, "j166_numcgm = $oDadosMatricula->z01_numcgm AND j166_matric = $oDadosMatricula->j01_matric");
$rsPercPosseRural = db_query($sqlPercPosseRural);

$j166_percentual = null;
if ($rsPercPosseRural && pg_num_rows($rsPercPosseRural) > 0) {
  $j166_percentual = db_utils::fieldsMemory($rsPercPosseRural, 0)->j166_percentual;
}

$sLoteloc = '';
if ($lUtilizaLoc) {
    $sLoteloc = $oDadosMatricula->j05_codigoproprio . ' - ' . $oDadosMatricula->j05_descr . '-' . $oDadosMatricula->j06_quadraloc . '/' . $oDadosMatricula->j06_lote;
}

?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
  <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
  <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
  <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
  <script rel="script" type="text/javascript" src="scripts/arrays.js"></script>
  <script rel="script" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
  <style>
    .valores {
      background-color: #FFFFFF;
      padding-left: 10px;
    }
  </style>

</head>

<body>
<fieldset style="height:230px; width:49.3%; float:left;">
  <legend>
    <b>Dados Cadastrais do Imóvel (<?= @$oDadosMatricula->j01_tipoimp ?>) </b>
      <?
      if (!empty($oDadosMatricula->j01_baixa)) {
          echo "<span class='aviso'>
                <font color='red'><b>Matrícula Baixada</b></font>
              </span>";
      }
      ?>
  </legend>
  <table>
    <tr>
      <td title="<?= $Tj01_matric ?>" style="width: 120px;"><?= $Lj01_matric ?>
      </td>
      <td title="<?= $Tj01_matric ?>" nowrap class='valores'
          style="width: 300px;"><?= $oDadosMatricula->j01_matric ?>
      </td>
      <td style="width: 10px;"></td>
      <td title="<?= $Tj40_refant ?>" style="width: 110px;"><?= $Lj40_refant ?>
      </td>
      <td title="<?= $Tj40_refant ?>" nowrap class='valores'
          style="width: 300px;"><?= $oDadosMatricula->j40_refant ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tz01_nome ?>"><? db_ancora($Lz01_nome,
            "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_cgmpri')", 2); ?>
      </td>
      <td title="<?= $Tz01_nome ?>" nowrap class='valores'><?= $oDadosMatricula->z01_nome ?>
      </td>
      <td></td>
      <td title="Proprietário"><b><? db_ancora('Proprietário',
                "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numcgm')", 2); ?>
        </b>
      </td>
      <td title="Proprietário" nowrap class='valores'><?= $oDadosMatricula->proprietario ?>
      </td>
    </tr>
    <tr>
      <td><b>Posse (%):</b></td>
      <td title="" nowrap class='valores'><?=$j166_percentual?></td>
      <td></td>
      <td title="<?= $Tj34_zona ?>"><?= $Lj34_zona ?>
      </td>
      <td title="" nowrap class='valores'><?= $oDadosMatricula->j34_zona . " - " . $oDadosMatricula->j50_descr ?>
      </td>
    </tr>
    <tr>
      <td title="">
        <strong>
            <?
            if ($lImobiliaria) {
                db_ancora("Imobiliária:", "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numimob')", 2);
            } else {
                echo "Imobiliária:";
            }
            ?>
        </strong>
      </td>
      <td title="" nowrap class='valores'><?
          if ($lImobiliaria) {
              echo $imobiliaria;
          } else {
              echo "Matricula sem Imobiliária vinculada.";
          }
          ?>
      </td>
      <td></td>
      <td title="<?= $Tj91_codigo ?>"><?= $Lj91_codigo ?>
      </td>
      <td nowrap class='valores'><?= @$oSetorFiscal->j91_codigo . " - " . @$oSetorFiscal->j90_descr ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tj34_setor ?>"><b>Setor/Quadra/Lote:</b>
      </td>
      <td nowrap class='valores'>
          <?
          echo $oDadosMatricula->j34_setor . ' - ' . $oDadosMatricula->j30_descr . '/' . $oDadosMatricula->j34_quadra . '/' . $oDadosMatricula->j34_lote;
          ?>
      </td>
      <td></td>
      <td title="Construído no lote:"><b>Construído no lote:</b>
      </td>

      <td nowrap class='valores'><?= $nAreaConstruida ?> - &Aacute;rea real
        construida no lote: <?= $oDadosMatricula->j34_totcon ?>
      </td>
    </tr>
    <tr>
      <td title="Área do lote"><b>Área do lote:</b>
      </td>
      <td title="" nowrap class='valores'><?= db_formatar($oDadosMatricula->area_matric, "f"); ?>
        - Área real do lote: <?= @db_formatar($nAreaTotal, 'f'); ?>
      </td>
      <td></td>
      <td title=""><b>Loteamento:</b>
      </td>
      <td title="" nowrap class='valores'><?= @$oDadosMatricula->j34_descr ?>
      </td>
    </tr>
    <tr>
      <td title=""><b>Logradouro:<b>

      </td>
      <td title="" nowrap class='valores'><?= @$oDadosMatricula->codpri ?> -
          <?= @$oDadosMatricula->tipopri ?> . <?= @$oDadosMatricula->nomepri ?>
        , <?= @$oDadosMatricula->j39_numero ?> <?= (@$oDadosMatricula->j39_compl != "" ? "/" : "") ?>
          <?= @$oDadosMatricula->j39_compl ?>
      </td>
      <td></td>
      <td title=""><b>Setor/Quadra/Lote de localização:</b>
      </td>
      <td title="" nowrap class='valores'>
          <?= $lUtilizaLoc == true ? $sLoteloc : '' ?>
      </td>
    </tr>
    <tr>
      <td title="Bairro"><b>Bairro:</b>
      </td>
      <td title="" nowrap class='valores'><?= @$oDadosMatricula->j13_codi . "-" . @$oDadosMatricula->j13_descr ?>
      </td>
      <td style="width: 10px;"></td>
      <td title="<?= $Tj40_registrocartografico ?>" style="width: 110px;"><?= $Lj40_registrocartografico ?>
      </td>
      <td title="<?= $Tj40_registrocartografico ?>" nowrap class='valores'
          style="width: 300px;"><?= $oDadosMatricula->j40_registrocartografico ?>
      </td>
    </tr>
  </table>
</fieldset>

<fieldset style="height:230px; width:48%;">
  <legend>
    <b>Outros Proprietários </b>
  </legend>
  <div id="container_outros_propri">

</div>
</fieldset>

<fieldset>
  <legend>Endereço Entrega</legend>
  <table style="width:100%">
    <tr>
      <td title="Logradouro"><b>Logradouro:</b></td>
      <td title="" style="width:95%;" nowrap class='valores'><?=@$oDadosEndereco->j43_ender?>, <?=@$oDadosEndereco->j43_numimo?> <?=(@$oDadosEndereco->j43_comple != "" ? "/" : "")?></td>
    </tr>

    <tr>
      <td title="Bairro"><b>Bairro:</b></td>
      <td title="" style="width:95%;" nowrap class='valores'><?=@$oDadosEndereco->j43_bairro?></td>
    </tr>

    <tr>
      <td title="Município"><b>Município:</b></td>
      <td title="" style="width:95%;" nowrap class='valores'><?=@$oDadosEndereco->j43_munic?></td>
    </tr>

    <tr>
      <td title="CEP"><b>CEP:</b></td>
      <td title="" style="width:95%;" nowrap class='valores'><?=@$oDadosEndereco->j43_cep?></td>
    </tr>
  </table>
</fieldset>

<fieldset style="height:422px; width:49.3%; float:left;">
  <legend>Isenção</legend>
  <fieldset>
    <legend>
      Lista de Isenções
    </legend>
    <div id="container_isencao">

    </div>
  </fieldset>
</fieldset>

<fieldset style="height:422px; width:48%;">
  <legend>Anexos</legend>
  <form id='formDownload'>
    <table border="0" cellspacing="0" cellpadding="0" width=100%>
      <tr>            
        <td>
          <fieldset>
            <legend>
              Lista de Anexos
            </legend>
            <div id="container_arquivos"></div>                  
          </fieldset>
          <div style="margin-top:5px;">
            <center><input type="button" value="Download" id="btnDownload"></center>
          </div>
        </td>
      </tr>
    </table>
    <input type="hidden" id="exec" name="exec">
    <input type="hidden" id="j01_matric" name="j01_matric" value="<?=$oDadosMatricula->j01_matric?>">
    <? db_input('db59_sequencial',10,0,false,'hidden',3,""); ?>
  </form>
</fieldset>

</body>
</html>
<script>
  /**
    * Função para Impressão BIC
    * @param boolen lParam - Parâmetro para confirmação de impressão da BIC
    * @return void()
    */
  function js_Impressao(lParam) {
    if(lParam) {
      var lGeraCalculo = confirm('Imprimir Demonstrativo de Cálculo?')
      window.open('cad3_conscadastro_impressao.php?tipo=2&geracalculo=' + lGeraCalculo + '&parametro=<?=$oDadosMatricula->j01_matric?>', '', 'location=0,HEIGHT=600,WIDTH=600');
    } else {
      window.open('cad3_conscadastro_impressao.php?tipo=1&parametro=<?=$oDadosMatricula->j01_matric?>', '', 'location=0,HEIGHT=600,WIDTH=600');
    }
  }

  function buscarOutrosPropri() {
    var oParametros = {
      'executa' : 'buscarOutrosPropri',
      'matricula' : <?=$oDadosMatricula->j01_matric?>
    },
    formData = createFormData(oParametros);

    HttpClient.post("cad4_consimovelrural.RPC.php", {body: formData}).then(response => {

      oGridOutrosPropri.clear();

      if (response.erro) {
        alert(response.mensagem);
        return;
      }

      response.outrosPropri.each(function(oOutrosPropri){
        oOutrosPropriCollection.add(oOutrosPropri);
      });

      oGridOutrosPropri.reload();

    });
  }

  const oOutrosPropriCollection = new Collection().setId('j166_sequencial');
  const oGridOutrosPropri = DatagridCollection.create(oOutrosPropriCollection).configure({"order" : false, "height" : "170px"});

  oGridOutrosPropri.addColumn("j166_numcgm",   {label : "CGM",   "width" : "15%"}).setOption("align","center");
  oGridOutrosPropri.addColumn("z01_nome", {label : "Nome", "width" : "70%"});
  oGridOutrosPropri.addColumn("j166_percentual", {label : "Posse (%)", "width" : "15%"});

  oGridOutrosPropri.show($('container_outros_propri'));
  
  buscarOutrosPropri();

  function buscarIsencoes() {
    var oParametros = {
        'executa' : 'buscaIsencoes',
        'matricula' : <?=$oDadosMatricula->j01_matric?>
      },
      formData = createFormData(oParametros);

    HttpClient.post("cad4_consimovelrural.RPC.php", {body: formData}).then(response => {
      
      oGridIsencao.clear();
      
      if (response.erro) {
        alert(response.mensagem);
        return;
      }
      
      response.isencoes.each(function(oIsencao){
        if (oIsencao.j46_dtini) {
          oIsencao.j46_dtini = new Date(oIsencao.j46_dtini).getDateBR();
        } 
        if(oIsencao.j46_dtfim) {
          oIsencao.j46_dtfim = new Date(oIsencao.j46_dtfim).getDateBR();
        }
        oIsencaoCollection.add(oIsencao);
      });

      oGridIsencao.reload();

    });
  }

  const oIsencaoCollection = new Collection().setId('j46_codigo');
  const oGridIsencao = DatagridCollection.create(oIsencaoCollection).configure("order", false);

  oGridIsencao.addColumn("j46_codigo",   {label : "Código",   "width" : "16.6%"}).setOption("align","center");
  oGridIsencao.addColumn("j46_tipo", {label : "Tipo", "width" : "16.6%"});
  oGridIsencao.addColumn("j46_dtini", {label : "Data Início", "width" : "16.6%"});
  oGridIsencao.addColumn("j46_dtfim", {label : "Data Fim", "width" : "16.6%"});
  oGridIsencao.addColumn("j46_perc", {label : "Percentual", "width" : "16.6%"});
  oGridIsencao.addColumn("j46_hist", {label : "Histórico", "width" : "16.6%"});

  oGridIsencao.show($('container_isencao'));

  buscarIsencoes();

  const oArquivosCollection = new Collection().setId('db59_sequencial');
  const oGridArquivos = DatagridCollection.create(oArquivosCollection).configure("order", false);
  const urlRpc = 'cad4_anexomatriculaimovel.RPC.php';

  inputSequencial = $('db59_sequencial');
  inputDescricao = $('j151_descricao');
  btnDownload =  $('btnDownload');

  oGridArquivos.grid.setCheckbox(1);
  oGridArquivos.addColumn("db59_sequencial",   {label : "Código",   "width" : "8%"}).setOption("align","center");
  oGridArquivos.addColumn("j151_descricao", {label : "Descrição", "width" : "72%"});

  btnDownload.addEventListener('click', event => {
     var 
        form = $('formDownload'),
        hiddenField = $("exec"),
        arrCheckbox = [];

      hiddenField.setAttribute("value", "multipledownload");
      form.setAttribute("target", "_blank");
      form.setAttribute("method", "POST");
      form.setAttribute("action", urlRpc);

      var aLinhas = oGridArquivos.grid.getSelection("object");

      if(empty(aLinhas)){
        alert('Nenhum arquivo selecionado!');
      } else {
        for (var linha of aLinhas){
          var checkbox = document.createElement("input");        
          checkbox.setAttribute("type", "hidden");
          checkbox.setAttribute("checked", "checked");
          checkbox.setAttribute("name", `sequencialdownload[]`);
          checkbox.setAttribute("value", `${linha.aCells[1].getValue()}`);
          form.appendChild(checkbox);
          arrCheckbox.push(checkbox);
        }

        form.submit();

        for (var checkbox of arrCheckbox){
          checkbox.remove();
        }  
      }

  });

  oGridArquivos.addAction("Download", null, function(event, oItem) {    
    var 
      form = $('formDownload'),
      inputOldValue = inputSequencial.value,
      hiddenField = $("exec");

    hiddenField.setAttribute("value", "download");
    inputSequencial.value = oItem.db59_sequencial;

    form.setAttribute("target", "_blank");
    form.setAttribute("method", "POST");
    form.setAttribute("action", urlRpc);

    form.submit();

    inputSequencial.value = inputOldValue;

  });

  oGridArquivos.show($("container_arquivos"));
  atualizaGrid();

  function getArquivos(){
    var 
      oParametros = {
        'exec' : 'listar',
        'j01_matric' : <?=$oDadosMatricula->j01_matric?>
      },
      formData = createFormData(oParametros);

    return HttpClient.post(urlRpc, {body: formData}).then(response => {
      return response.arrArquivos;
    });    
  }

  function atualizaGrid(){
    getArquivos().then(response => {
      oArquivosCollection.clear();
      for (var oArquivo of response) {

        oArquivosCollection.add({
          db59_sequencial    : oArquivo.db59_sequencial,
          j151_descricao     : oArquivo.j151_descricao,
        });
      }
      oGridArquivos.reload();
    });
  }

  function createFormData(oParametros){
    var formData = new FormData();
    for(parametro in oParametros){
      if(oParametros[parametro] instanceof Array){
        formData.append(`${parametro}[]`, oParametros[parametro]);
      } else {
        formData.append(parametro, oParametros[parametro]);
      }
    }
    return formData;
  }

</script>