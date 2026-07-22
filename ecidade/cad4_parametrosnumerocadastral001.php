<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2022  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));

$clrotulo    = new rotulocampo();
$clrotulo->label("codcam");
$clrotulo->label("descricao");

?>
<!doctype html>
<html lang="pt-BR">
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>

</head>
<body class="body-default">
<div class="container">
  
  <form name="formNumeroCadastral" id="formNumeroCadastral" method="post">
  <fieldset id="parametroInscricao">
  <legend>Parâmetros Gerar Número Cadastral</legend>
  <table class="form-container">
    <tr>
      <td>
        <label for="separador">Separador da mascara:</label>
      </td>  
    </tr>  
    <tr>
      <td>  
        <select name="separador" id="separador">
          <option value="">Selecione</option>
          <option value=".">Ponto</option>
          <option value="#">HashTag</option>
        </select>
      </td>  
    </tr>  
    <tr>
      <td>
   
  <div id="divConfiguracao" class="container" style="width: 900px; margin-top: 1rem;">
    <table id="data-table-configuracao" 
        class="table">
    </table>
  </div>             
   </td>  
  </tr>  
</table>
  </fieldset>
  <button type="button" id="btnSalvar" class="btn btn-light">
        <i class="far fa-save"></i>
      Salvar
    </button>
  </form>
  <div id="modalParametro" class="container">
        <fieldset>
            <table class="form-container">
              <tr>
                <td nowrap="nowrap" title="<?php echo $Tcodcam; ?>">
                  <label for="codcam">
                    <a href="" id="ancora-campos"><?php echo $Lcodcam; ?></a>
                  </label>
                </td>
                <td nowrap="nowrap">
                  <?php
                    db_input('codcam', 10, @$Icodcam, true, 'text', $db_opcao, "data='codcam'");
                    db_input('nomecam', 50, $Idescricao, true, 'text', 3, "data='nomecam'");
                  ?>
                </td>
              </tr>
              <tr>
              <td>
                  <label for="tamanho">
                    Tamanho Campo na Máscara: 
                  </label>
                </td>
                <td>
                  <input type="number" name="tamanho" id="tamanho" min="1" max="5" size="6" value="1">
                </td>
                <td>
                  <input type="hidden" name="index" id="index" value="">
                </td>                
              </tr>
            </table>
        </fieldset>
        
        <button class="btn btn-light" id="btnLancarParametro">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            Adicionar
        </button>
        <button class="btn btn-light" id="btnAlterarParametro">
            <i class="fa fa-save" aria-hidden="true"></i>
            Alterar
        </button>
    </div>
  </fieldset>  
</div> 
<script>

var oLookupCampos = new DBLookUp($("ancora-campos"), $("codcam"), $("nomecam"), {
  "sArquivo" : "func_camposdisponiveis.php",
  "sObjetoLookUp" : "db_iframe",
  "zIndex": "10001"
})
</script>

<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>

$.noConflict();

const routes = {
  listar: 'tributario/cadastro/configuracao/listar',   
  salvar: 'tributario/cadastro/configuracao/salvar'
};

const inputCodigo     = document.getElementById('codcam');
const inputCampo  = document.getElementById('nomecam');
const inputTamanho    = document.getElementById('tamanho');
const selectSeparador = document.getElementById('separador');
const inputIndex      = document.getElementById('index');

jQuery(document).ready(function($) {
  
  
  var tabelaConfiguracao = jQuery('#data-table-configuracao');
  const btnSalvar        = jQuery('#btnSalvar');
  const btnLancar        = jQuery('#btnLancarParametro');
  const btnAlterar       = jQuery('#btnAlterarParametro');
  
  window.operateEvents = {
      'click .alterar': (e, value, row, index) => {
        
        btnLancar.hide();
        btnAlterar.show();
        console.log(index, row);
        inputCodigo.value    = row.codigo;
        inputCampo.value = row.campo;
        inputTamanho.value   = row.tamanho;
        inputIndex.value     = index;
        windowParametro.show(0, 0, true);
      },
      'click .excluir': (e, value, row, index) => {
         
        let dadosAtualizados = tabelaConfiguracao.bootstrapTable('getData')
        .filter((dados, indexdados) => {
          
          if(indexdados != index) {
             return dados
          }
          
        })

        let dadosReordenados = [];
        dadosAtualizados.map((dadosAtualizaOrdem, indexordem) => {
            
          dadosAtualizaOrdem.ordem = indexordem + 1;
          dadosReordenados.push(dadosAtualizaOrdem);
        })

        tabelaConfiguracao.bootstrapTable('load', dadosReordenados); 
      }
      
    };
    
    function buttons() {
            return {
                btnAdd: {
                    text: 'Adicionar Configuração',
                    icon: 'fa-plus',
                    event: function () {
                        
                      btnLancar.show();
                      btnAlterar.hide();
                      windowParametro.show(0, 0, true);
                    },
                    attributes: {
                        title: 'Clique para adicionar uma nova configuração'
                    }
                }
            }
    }
    
  const modalParametro     = document.getElementById('modalParametro');
        const hideWindowParametro = () => {
            
          if (!!windowParametro.oDBMask) {
              windowParametro.oDBMask.destroy();
          }
            
          windowParametro.hide();
        }

        var windowParametro = new windowAux('windowParametro', 'Lançar Parâmetro', 700, 400);
        windowParametro.setContent(modalParametro);
        windowParametro.allowCloseWithEsc(true);
        windowParametro.setShutDownFunction(function () {
          hideWindowParametro();
        });
 
       const colunas = [
           
            {
                field: 'ordem',
                title: 'Ordem',
                halign: 'center',
                align: 'left',
                width: 30,
                visible: true               
            },
            {
                field: 'codigo',
                title: 'Código',
                halign: 'center',
                align: 'left',
                width: 30,
                visible: false
                
            },
            {
                field: 'campo',
                title: 'Campo',
                halign: 'center',
                align: 'left',
                width: 300
            },
            {
                field: 'tamanho',
                title: 'Tamanho Campo',
                halign: 'center',
                align: 'center',
                width: 50,
                formatter: (valor, data, index) => {

                  return valor;  
                }
            },
            {
                field: 'acao',
                title: 'Ações',
                halign: 'center',
                align: 'center',
                width: 50,
                formatter: (valor, data, index) => {

                  return ['<a class="alterar" href="javascript:void(0)" title="Alterar">',
                          '  <i class="fa fa-edit"></i>',
                          '</a>',
                          '&nbsp;&nbsp;',
                          '<a class="excluir" href="javascript:void(0)" title="Excluir">',
                          '  <i class="fas fa-trash-alt"></i>',
                          '</a>'].join('')  
                },
                events:  window.operateEvents
            }
            
        ];  

  tabelaConfiguracao.createTable = function() {
    
    tabelaConfiguracao.bootstrapTable('destroy');
    tabelaConfiguracao.bootstrapTable({
        locale: 'pt-BR',
        height: 350,
        search: false,
        class: "table table-sm",
        columns: colunas,
        showButtonText: true,
        cache: false,
        buttons: buttons,
        useRowAttrFunc: true,
        reorderableRows: true
      });
    
      PHPSession.loadData().then(() => {
            
        HttpClient.get(`${PHPSession.requestApi}/${routes.listar}`).then(response => {
          
          let data              = response.data.configuracao;
          selectSeparador.value = response.data.separador;
          tabelaConfiguracao.bootstrapTable('load', data);      
        });
        });
      
        tabelaConfiguracao.on('reorder-row.bs.table', (dados, newdados) => {
          
          tabelaConfiguracao.bootstrapTable('load', newdados);
        });    
  } 

  tabelaConfiguracao.createTable();
  btnLancar.on('click', () => {
    
    if(inputCodigo.value == '' || inputCampo.value == '' || inputTamanho.value == '') {

      return alert('Os campos devem estar preenchidos');
    }
    let dados = tabelaConfiguracao.bootstrapTable('getData'); 

        tabelaConfiguracao.bootstrapTable('insertRow', {
        index: dados.length,
        row: {
          ordem: dados.length + 1,
          codigo: inputCodigo.value.trim(),
          campo: inputCampo.value.trim(), 
          tamanho: inputTamanho.value.trim()
        }
    })
    
    inputCodigo.value    = '';
    inputCampo.value = '';
    inputTamanho.value   = '1';
    hideWindowParametro();
  })
  
  btnAlterar.on('click', () => {
    
    if(inputCodigo.value == '' || inputCampo.value == '' || inputTamanho.value == '') {

      return alert('Os campos devem estar preenchidos');
    }

    tabelaConfiguracao.bootstrapTable('updateRow', {
      
      index: inputIndex.value,
        row: {
          codigo:  inputCodigo.value.trim(),
          campo:   inputCampo.value.trim(), 
          tamanho: inputTamanho.value.trim()
        }
    })
    
    inputCodigo.value    = '';
    inputCampo.value = '';
    inputTamanho.value   = '1';
    inputIndex.value     = '';
    hideWindowParametro();
  })

  btnSalvar.on('click', () => {
    
    let configuracao = JSON.stringify(tabelaConfiguracao.bootstrapTable('getData'));
    const formData   = new FormData();

    formData.append('separador', selectSeparador.value);
    formData.append('configuracao', configuracao);
    PHPSession.appendFormData(formData);
        
        HttpClient.post(`${PHPSession.requestApi}/${routes.salvar}`, 
                         { 
                           body: formData, 
                           reportMessage: 'Salvando dados ...'
                         }
                       )
        .then(response => {

          return alert(response.message);
        })
  })
});

</script>   
</body>
</html>