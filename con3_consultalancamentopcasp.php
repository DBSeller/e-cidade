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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('dbforms/db_funcoes.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta charset="iso-8859-1">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
   <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
   <link type="text/css" href="estilos.css" rel="stylesheet">
   <link type="text/css" href="grid.style.css" rel="styleshet">
   <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
   <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
   <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
   <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
   <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
   <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
   <script rel="script" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
</head>
<body>
   <div class="container">
      <fieldset>
         <legend>Consultar Lançamentos</legend>
         <div id="lancadorContas" style="width: 600px;"></div>
         <fieldset class='separator'>
            <legend>Filtro de Documentos</legend>
            <label for="filtroDocumentos" class='bold'>Visualização de Eventos</label>
            <select name="filtroDocumentos" id="filtroDocumentos">
               <option value="documento">Documento</option>
               <option value="tipo_documento">Tipo de Documento</option>
            </select>
         </fieldset>
         <fieldset class='separator'>
            <legend>Período</legend>
            <table>
               <tr> 
                  <td>
                     <label class='bold'>De:</label> &nbsp;
                  </td>
                  <td>
                     <input id="data-inicio"> &nbsp;
                  </td>
                  <td>
                     <label class='bold'>Até:</label> &nbsp;
                  </td>
                  <td>
                     <input id="data-fim">
                  </td>
               </tr>
            </table>
         </fieldset>
      </fieldset>
      <button type="button" id="btnBuscar">
         <i class="fas fa-search"></i>
         Buscar
      </button>
   </div>
   <div id="modalRecurso">
      <div class="alert alert-info" role="alert" style="height: 50px;">
         <ul>
            <li id="documentos-selecionados">
         </ul>
      </div>
      <div class="subcontainer" style="width: 1230px;"> 
         <fieldset>
            <legend id="legend-recurso">Lançamentos por Recurso</legend>
            <table
               id="data-table-recurso" 
               class="table table-sm" 
               style="width: 1200px;">
            </table>
         </fieldset>
      </div>
   </div>
   <div id="modalDocumentos">
      <div class="alert alert-info" role="alert" style="height: 100px;">
         <ul>
            <li>
               Clique em <kbd><i class="fa fa-plus"></i></kbd> para consultar os valores das contas por documento
               <ul>
                  <li>Clique em <i class="fas fa-info-circle"></i> para consultar os lançamentos da conta</li>
               </ul>
            </li>
            <li>
               Selecione os documentos desejados e clique em <kbd><i class="fas fa-search"></i> Consultar</kbd> 
               para totalizar os valores dos lançamentos por recurso.
            </li>      
         </ul>
      </div>
      <div class="subcontainer" style="width: 910px; height: 400px">   
         <fieldset>
            <legend id="legend-documentos">Documentos Encontrados</legend>
            <table id="data-table-documentos" 
               class="table table-sm"
               data-detail-view="true" 
               style="width: 900px;">
            </table>
         </fieldset>
         <button type="button" id="btnProcessar">
            <i class="fas fa-search"></i>
            Consultar
         </button>
      </div>
   </div>
   <div id="modalDocumentoDetalhes">
      <div class="alert alert-info" role="alert">
         <ul>
            <li>
               Dê um clique na linha desejada para mais informações do lançamento!
            </li>    
         </ul>
      </div>
      <div class="subcontainer" style="width: 910px;">
         <fieldset>
            <legend id="legend-detalhes">Lançamentos do Documento</legend>
            <table
               id="data-table-detalhes"
               class="table table-sm"
               style="width: 900px;">
            </table>
         </fieldset>
      </div>
   </div>
<?php
   db_menu();
?>
</body>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/infoLancamentoContabil.classe.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>   
<script>
   const rotas = {
      documentos: 'financeiro/contabilidade/consulta/lancamento/conta-pcasp/documentos',
      recursos: 'financeiro/contabilidade/consulta/lancamento/conta-pcasp/recursos',
      info: 'financeiro/contabilidade/consulta/lancamento/conta-pcasp/info'
   };
   const btnBuscar = document.getElementById('btnBuscar');
   const processarDocumentos = {
      modal: document.getElementById('modalDocumentos'),
      button: document.getElementById('btnProcessar')
   };
   const tabelaDocumentos = jQuery('#data-table-documentos');
   const modalDetalhes = document.getElementById('modalDocumentoDetalhes');
   const tabelaDetalhesDocumento = jQuery("#data-table-detalhes");
   const modalRecurso = document.getElementById('modalRecurso');
   const tabelaRecurso = jQuery('#data-table-recurso');

   const fechaModal = () => {
      if (!!windowDocumentos.oDBMask) {
         windowDocumentos.oDBMask.destroy();
      }
      windowDocumentos.hide();
   }

   const windowDocumentos = new windowAux('windowDocumentos', 'Selecionar Documentos', 1000, 550);
   windowDocumentos.setContent(processarDocumentos.modal);
   windowDocumentos.setShutDownFunction(() => {
      fechaModal()
   });

   const windowDetalhes = new windowAux('windowDetalhes', 'Detalhes do Documento', 1000, 600);
   windowDetalhes.setContent(modalDetalhes);
   windowDetalhes.setChildOf(windowDocumentos);

   const windowRecursos = new windowAux('windowRecursos', 'Lançamentos Encontrados por Recurso', 1250, 600);
   windowRecursos.setContent(modalRecurso);
   windowRecursos.setChildOf(windowDocumentos);
   $.noConflict();

   const data = new Date();
   let dia = data.getDate();
   let mes = data.getMonth() + 1;
   let ano = data.getFullYear();
   document.getElementById('data-inicio').value = `01/01/${ano}`;
   document.getElementById('data-fim').value = `${dia}/${mes}/${ano}`;
   const dataInicial = new DBInputDate(document.getElementById('data-inicio'));
   const dataFinal = new DBInputDate(document.getElementById('data-fim'));

   var lancadorContas = new DBLancador('lancadorContas');
   lancadorContas.iGridHeight = 100;
   lancadorContas.selecionarAposPesquisar = true;
   lancadorContas.setNomeInstancia('lancadorContas');
   lancadorContas.setLabelAncora('Conta:');
   lancadorContas.setTextoFieldset('Filtrar Contas');
   lancadorContas.setParametrosPesquisa('func_conplanoreduz.php', ['c61_reduz', 'c60_descr']);
   lancadorContas.show(document.getElementById('lancadorContas'));

   jQuery(document).ready(jQuery => {

      btnBuscar.addEventListener('click', () => {
         const formData = new FormData();
         let filtroDocumentos = document.getElementById('filtroDocumentos').value;
         formData.append('filtroDocumentos', filtroDocumentos);

         if (lancadorContas.getRegistros().length == 0) {
            return alert('Necessário informar pelo menos uma conta para realizar a consulta!');
         }
         if (empty(dataInicial.__toLocaleDateString()) || empty(dataFinal.__toLocaleDateString())) {
            return alert('Necessário informar o período para realizar a consulta!');
         }
         if (dataInicial.getValue().getUTCFullYear() != dataFinal.getValue().getUTCFullYear()) {
            return alert('Não é possível realizar consulta de lançamentos para anos diferentes!');
         }
         if (dataInicial.__toLocaleDateString() > dataFinal.__toLocaleDateString()) {
            return alert('A data inicial não pode ser maior que a data final!');
         }

         lancadorContas.getRegistros().map(conta => {
            formData.append('contas[]', conta.sCodigo);
         });
         formData.append('dataInicial', js_formatar(dataInicial.__toLocaleDateString(), 'd'));
         formData.append('dataFinal', js_formatar(dataFinal.__toLocaleDateString(), 'd'));

         PHPSession.loadData().then(() => {
            HttpClient.post(`${PHPSession.requestApi}/${rotas.documentos}`, {body: formData}).then((response) => {
               if (response.error) {
                  return alert(response.message);
               }
               windowDocumentos.show(0, 0, true);
               let legend = document.getElementById("legend-documentos");
               let periodoInicial = dataInicial.inputElement.value;
               let periodoFinal = dataFinal.inputElement.value;
               legend.textContent = `Documentos Encontrados, Período ${periodoInicial} até ${periodoFinal}`;
               tabelaDocumentos.bootstrapTable('load', response.data);
            })
         });
      });

      processarDocumentos.button.addEventListener('click', () => {
         const formData = new FormData();
         let documentosFiltrados = [];
         let contas = [];
         let selecoes = tabelaDocumentos.bootstrapTable('getSelections');
         let filtroDocumentos = document.getElementById('filtroDocumentos').value;
         formData.append('filtroDocumentos', filtroDocumentos);

         if(selecoes.length == 0){
            return alert('Necessário informar pelo menos um documento para realizar a consulta!');
         }

         documentosFiltrados = selecoes.map(selecao => {
            selecao.contas.each(conta => {
               if (contas.indexOf(conta.reduzido) == -1) {
                  contas.push(conta.reduzido);
                  formData.append('contas[]', conta.reduzido);
               }
            })
            return selecao.tipo;
         });

         formData.append('documentos[]', documentosFiltrados);
         formData.append('dataInicial', js_formatar(dataInicial.__toLocaleDateString(), 'd'));
         formData.append('dataFinal', js_formatar(dataFinal.__toLocaleDateString(), 'd'));

         HttpClient.post(`${PHPSession.requestApi}/${rotas.recursos}`, {body: formData}).then(response => {
            if (response.error) {
               return alert(response.message);
            }
            windowRecursos.show(0, 0, true);
            let legend = document.getElementById("legend-recurso");
            let infoSelecionados = document.getElementById("documentos-selecionados");
            let periodoInicial = dataInicial.inputElement.value;
            let periodoFinal = dataInicial.inputElement.value;
            legend.textContent = `
               Lançamentos por Recurso, 
               Período: ${periodoInicial} até ${periodoFinal}`;
            infoSelecionados.textContent = `Documentos Selecionados: ${documentosFiltrados.toString()}`;
            tabelaRecurso.bootstrapTable('load', response.data);
         });
      });

      const getNomeConta = (reduzido) => {
         let nome = '';
         lancadorContas.getRegistros().each(dadosLancados => {
            if (reduzido == dadosLancados.sCodigo) {
               nome = dadosLancados.sDescricao;
            }
         });
         return nome;
      };

      const formatterValor = (value) => {
         return js_formatar(value, 'f');
      };

      const formatterData = (value) => {
         return js_formatar(value, 'd');
      };

      tabelaDocumentos.bootstrapTable({
         height: 300,
         search: true,
         columns: [
            {
               checkbox: true,
               width: 40
            },
            {
               field: 'tipo', 
               title: 'Tipo',
               halign: 'center',
               align: 'left',
               width: 60 
            },   
            {
               field: 'descricao', 
               title: 'Descrição',
               halign: 'center',
               align: 'left',
               width: 800
            },
            {
               field: 'contas',
               visible: false
            }
         ],
         onExpandRow: (index, row, detail) => {
            expandTable(detail, row.contas);
         }
      });

      tabelaDetalhesDocumento.bootstrapTable({
         height: 400,
         search: true,
         pageSize: 50,
         pagination: true,
         columns: [
            {
               field: 'codigo',
               title: 'Código',
               halign: 'center',
               align: 'left',
               width: 80
            },
            {
               field: 'descricao',
               title: 'Descrição',
               halign: 'center',
               align: 'left',
               width: 300
            },
            {
               field: 'debito',
               title: 'Conta Débito',
               halign: 'center',
               align: 'left',
               width: 80
            },
            {
               field: 'credito',
               title: 'Conta Crédito',
               halign: 'center',
               align: 'left',
               width: 80
            },
            {
               field: 'valor',
               title: 'Valor',
               halign: 'center',
               align: 'right',
               width: 140,
               formatter: formatterValor
            },
            {
               field: 'data',
               title: 'Data',
               halign: 'center',
               align: 'center',
               width: 120,
               formatter: formatterData
            }
         ],
         onClickRow: row => {
            let modalInfo = new infoLancamentoContabil(row.codigo, windowDetalhes, true, 600);
            modalInfo.oWindowLancamentos.setShutDownFunction(() => {
               document.getElementById('wndLancamentos'+row.codigo).remove();
            });
         }
      });
      
      tabelaRecurso.bootstrapTable({
         locale: 'pt-BR',
         height: 400,
         search: true,
         showFooter: true,
         columns: [
            {
               field: 'reduzido',
               title: 'Reduzido',
               halign: 'center',
               align: 'left',
               width: 80,
               footerFormatter: () => {
                  return 'Total';
               }
            },
            {
               field: 'nome',
               title: 'Nome da Conta',
               halign: 'center',
               align: 'left',
               width: 280,
               formatter: (a, data) => {
                  return getNomeConta(data.reduzido);
               },
               footerFormatter: data => {
                  return data.length;
               }
            },
            {
               field: 'recurso',
               title: 'Recurso',
               halign: 'center',
               align: 'left',
               width: 280,
               formatter: (a, data) => {
                  return `${data.fonterecurso} - ${data.descricaorecurso}`;
               }
            },
            {
               field: 'complemento',
               title: 'Complemento',
               halign: 'center',
               align: 'left',
               width: 200,
               formatter: (a, data) => {
                  return `${data.complementorecurso} - ${data.descricaocomplemento}`;
               }
            },
            {
               field: 'valordebito',
               title: 'Débito',
               halign: 'center',
               align: 'right',
               width: 120,
               formatter: formatterValor,
               footerFormatter: data => {
                  let totalDebito = 0;
                  data.each(recurso => {
                     totalDebito += Number(recurso.valordebito);
                  });
                  return js_formatar(totalDebito, 'f');
               }
            },
            {
               field: 'valorcredito',
               title: 'Crédito',
               halign: 'center',
               align: 'right',
               width: 120,
               formatter: formatterValor,
               footerFormatter: data => {
                  let totalCredito = 0;
                  data.each(recurso => {
                     totalCredito += Number(recurso.valorcredito);
                  });
                  return js_formatar(totalCredito, 'f');
               }
            },
            {
               field: 'valorTotal',
               title: 'Total',
               halign: 'center',
               align: 'right',
               width: 120,
               formatter: (a, data) => {
                  let valorTotal = Number(data.valordebito) - Number(data.valorcredito);
                  return js_formatar(valorTotal, 'f');
               },
               footerFormatter: data => {
                  let total = 0;
                  data.each(recurso => {
                     total += Number(recurso.valordebito) - Number(recurso.valorcredito);
                  });
                  return js_formatar(total, 'f');
               }
            }
         ]
      });

      const getInfoLancamentos = {
         'click .info': (e, d, data) => {
            const formData = new FormData();
            let filtroDocumentos = document.getElementById('filtroDocumentos').value;
            formData.append('filtroDocumentos', filtroDocumentos);
            formData.append('conta', data.reduzido);
            formData.append('documento', data.tipoDocumento);
            formData.append('dataInicial', js_formatar(dataInicial.__toLocaleDateString(), 'd'));
            formData.append('dataFinal', js_formatar(dataFinal.__toLocaleDateString(), 'd'));
            
            HttpClient.post(`${PHPSession.requestApi}/${rotas.info}`, {body: formData}).then(response => {
               if (response.error) {
                  return alert(response.message);
               }
               windowDetalhes.show(0, 0, true);
               let legend = document.getElementById("legend-detalhes");
               let periodoInicial = dataInicial.inputElement.value;
               let periodoFinal = dataFinal.inputElement.value;
               legend.textContent = `
                  Lançamentos do Documento: ${data.tipoDocumento}, 
                  Reduzido: ${data.reduzido}, 
                  Período: ${periodoInicial} até ${periodoFinal}
               `;
               tabelaDetalhesDocumento.bootstrapTable('load', response.data);
            });
         }
      }
      
      function expandTable(detail, dados) {
         listarDocumentoOrigem(detail.html('<table></table>').find('table'), dados);
      }
      /**
       * SubTabela para listar as informações do documento
       */
      function listarDocumentoOrigem(tabela, contas) {
         let columns = [
            {
               field: 'reduzido',
               title: 'Reduzido',
               halign: 'center',
               align: 'left',
            },
            {
               field: 'valorDebito',
               title: 'Valor à Débito',
               halign: 'center',
               align: 'right',
               formatter: formatterValor
            },
            {
               field: 'valorCredito',
               title: 'Valor à Crédito',
               halign: 'center',
               align: 'right',
               formatter: formatterValor
            },
            {
               field: 'valorTotal',
               title: 'Valor Total',
               halign: 'center',
               align: 'right',
               formatter: formatterValor
            },
            {
               field: 'info',
               title: 'Info',
               align: 'center',
               formatter: () => {
                  return `<a class="info"><i class="fas fa-info-circle"></i></a>`;
               },
               events: getInfoLancamentos
            }
         ];
         tabela.bootstrapTable({
            columns: columns,
            data: contas
         });
      }
   });
</script>
</html>