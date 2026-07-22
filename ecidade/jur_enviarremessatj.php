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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
$rotulo = new rotulocampo;
$rotulo->label('k60_codigo');
$rotulo->label('k60_descr');
$rotulo->label('z01_numcgm');
$rotulo->label('z01_nome');
$rotulo->label('j01_matric');
$rotulo->label('q02_inscr');
$rotulo->label('y50_codauto');
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, strings.js, numbers.js, arrays.js, prototype.js, AjaxRequest.js, datagrid.widget.js, pades.js");
    db_app::load("widgets/Collection.widget.js, widgets/DatagridCollection.widget.js, widgets/DBDownload.widget.js");
    db_app::load("dbmessageBoard.widget.js, widgets/windowAux.widget.js,  widgets/DBLookUp.widget.js");
    ?>
    <link type="text/css" rel="stylesheet" href="estilos.css">
    <style>
        .assinatura {
            color: black;
            font-weight: bold;
        }
        .retornoerro {
            color: red;
            font-weight: bold;
        }
        .assinado {
            color: darkorange;
            font-weight: bold;
        }
        .enviado {
            color: blue;
            font-weight: bold;
        }
        .processocriado {
            color: green;
            font-weight: bold;
        }
        .inicialpaga {
            color: #1a72fd;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container" style="width: 70%">
    <fieldset>
        <legend>
                Enviar Remessa
        </legend>

        <table>
            <tr>
                <td>
                    <label for="k60_codigo" id="lblLista">
                        <?=$Lk60_codigo?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input("k60_codigo", 4, $Ik60_codigo, true, "text", 4);
                    db_input("k60_descr", 40, $Ik60_descr, true, "text", 3);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label id='lblNumCgm' for="z01_numcgm">
                        <?=$Lz01_nome?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input("z01_numcgm", 4, $Iz01_numcgm, true, "text", 1);
                    db_input("z01_nome", 40, $Iz01_nome, true, "text", 3, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label id='lblIptuBase' for="j01_matric">
                        <?=$Lj01_matric?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input("j01_matric", 4, $Ij01_matric, true, "text", 1);
                    db_input("z01_nome_matricula", 40, $Iz01_nome, true, "text", 3, "data='z01_nome'");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label id='lblIssBase' for="q02_inscr">
                        <?=$Lq02_inscr?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input("q02_inscr", 4, $Iq02_inscr, true, "text", 1);
                    db_input("z01_nome_inscricao", 40, $Iz01_nome, true, "text", 3, "data='z01_nome'");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label id='lblAutoInfracao' for="y50_codauto">
                        <?=$Ly50_codauto?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input("y50_codauto", 4, $Iy50_codauto, true, "text", 1, "data='dl_Auto'");
                    db_input("z01_nome_auto", 40, $Iz01_nome, true, "text", 3, "data='z01_nome'");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label id='lblTiposDeFiltro' for="cboTiposDeFiltro">
                       <b>Situação:</b>
                    </label>
                </td>
                <td>
                   <select id="cboTiposDeFiltro">

                   </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="button" id="pesquisar" value="Pesquisar" onclick="verificarProcessamentoDaLista();">
    <fieldset>
        <legend>
            Iniciais para Envio <span id="tipoAmbiente"></span>
        </legend>
        <div id="gridIniciaisContainer">

        </div>
    </fieldset>

    <input type="button" id="btnAssinar" value="Assinar" onclick="assinar();">
    <input type="button" id="processar" value="Enviar Remessa" onclick="enviarRemessa();">
    <input type="button" id="btnRelatorio" value="Relatório de Inconsistências" onclick="relatorioErrosEnvios();">
    <input type="button" id="btnReProcessar" value="Reprocessar Arquivos" onclick="reprocessarArquivos();">
</div>
</body>
</html>
<script>
    var situacoes = [];
    situacoes[0]  = {estilo:'',               descricao:'Todas'};
    situacoes[1]  = {estilo:'assinatura',     descricao:'Assinatura'};
    situacoes[2]  = {estilo:'assinado',       descricao:'Assinado'};
    situacoes[3]  = {estilo:'enviado',        descricao:'Enviado'};
    situacoes[4]  = {estilo:'retornoerro',    descricao:'Retorno - erro'};
    situacoes[5]  = {estilo:'processocriado', descricao:'Processo criado'};
    situacoes[6]  = {estilo:'inicialpaga',    descricao:'Inicial Paga'};
    for (var i in situacoes) {
        if (typeof(situacoes[i]) == 'function') {
            continue;
        }
        $('cboTiposDeFiltro').add(new Option(situacoes[i].descricao, i));
    }

    const MENSAGENS  = 'tributario.juridico.jur4_processainiciais.';
    var collection   = new Collection().setId('inicial');

    var gridIniciaisRemessa = DatagridCollection.create(collection).configure("order", false);

    gridIniciaisRemessa.addColumn("inicial_key",    {label: "Inicial",    align: "center", width: "10%"})
    gridIniciaisRemessa.addColumn("inicial",    {label: "Inicial",    align: "center", width: "10%"})
        .transform(function(valor, itemCollection){
            return "<a href='#' onclick='abrirConsultaInicial("+itemCollection.inicial+");return false'>"+valor+"</a>";
        });

    gridIniciaisRemessa.addColumn("origem", {label: "Origem", align: "center", width: "10%"})
        .transform(function(valor, itemCollection) {
            return itemCollection.tipo + ' - ' + itemCollection.origem;
        });

    gridIniciaisRemessa.addColumn("nome",  {label: "Nome",       align: "left", width: "40%"} )
        .transform(function(valor, itemCollection){
            return "<span style='clear:left; float:left'>"+ valor + "</span><span style='clear:right;float:right;display:none' id='icone_assinatura_"+itemCollection.inicial+"'><img src='imagens/ajax-loader.gif'></span>";
        });
    gridIniciaisRemessa.addColumn("ano_inicial",  {label: "Exercício", align: "right", width: "10%"} );
    gridIniciaisRemessa.addColumn("documento",  {label: "Doc",  align: "center", width: "5%"} )
        .transform(function (valor, itemCollection){

            var documentos = '';
            for (var documento of itemCollection.documentos) {

                documentos += "<a href='data:application/pdf;base64," +documento.arquivo + "' download='" + documento.documento + "' title='"+documento.documento+"'>";
                documentos  += "<img src='imagens/boleto.png' style='width:12px; height:15px'></a>&nbsp;";
            }
            return documentos;
        });

    gridIniciaisRemessa.addColumn("situacao",  {label: "Situação",  align: "center", width: "10%"})
        .transform(function(valor, itemCollection){
            return '<span class="'+situacoes[valor].estilo+'">'+situacoes[valor].descricao+'</span>';
        });

    gridIniciaisRemessa.addColumn("acao",  {label: "Ações",  align: "center", width: "10%"} )
        .transform(function(valor, itemCollection){
            var disabled = '';
            if (itemCollection.situacao == 5) {
                disabled = " disabled ";
            }
            var botao = "<input type='button' onclick='reprocessarDocumentos("+itemCollection.codigo_processo_eletronico+");' value='R' "+disabled+" title='Reprocessar Arquivo' id='btn"+itemCollection.inicial+"'> ";
            return botao;
        });
    gridIniciaisRemessa.getGrid().setCheckbox(0);

    gridIniciaisRemessa.hideColumns([1]);
    gridIniciaisRemessa.show($('gridIniciaisContainer'));

    const URL_RPC = 'jur4_processainiciais.RPC.php';

    var oAncoraCGM  = new DBLookUp( $('lblNumCgm'), $('z01_numcgm'),  $('z01_nome'),
        {
            sArquivo      : 'func_cgm.php',
            sObjetoLookUp : 'func_nome'
        }
    );

    var oAncoraLista  = new DBLookUp( $('lblLista'), $('k60_codigo'),  $('k60_descr'),
        {
            sArquivo      : 'func_lista.php',
            sObjetoLookUp : 'func_lista'
        }
    );

    var oAncoraMatricula  = new DBLookUp( $('lblIptuBase'), $('j01_matric'),  $('z01_nome_matricula'),
        {
            sArquivo      : 'func_iptubase.php',
            sObjetoLookUp : 'func_iptu'
        }
    );

    var oAncoraInscricao  = new DBLookUp( $('lblIssBase'), $('q02_inscr'),  $('z01_nome_inscricao'),
        {
            sArquivo      : 'func_issbase.php',
            sObjetoLookUp : 'func_issbase'
        }
    );

    var oAncoraAuto  = new DBLookUp( $('lblAutoInfracao'), $('y50_codauto'),  $('z01_nome_auto'),
        {
            sArquivo      : 'func_autoalt.php',
            sObjetoLookUp : 'func_auto',
            aParametrosAdicionais : ['cgf=1']
        }
    );

    
    function reprocessarArquivos() {

        var listaprocessos = [];
                 
        var processoselecionados = gridIniciaisRemessa.getGrid().getSelection();

        for (var processoDaLista of processoselecionados) {

            var processoEletronico = collection.get(processoDaLista[0]);

            if (processoEletronico.situacao == 4) {
               listaprocessos.push(processoEletronico.codigo_processo_eletronico);
            }

        }
 
        const params = {"exec":"atualizarDocumento", "processo_eletronico": listaprocessos, "lista": $F('k60_codigo') };  
                         
        new AjaxRequest('jur4_processainiciais.RPC.php', params,
            function (retorno, erro) {
                
                console.log(retorno);
                if (erro) {
                    alert(retorno.mensagem);
                    return;
                }

                pesquisar();

            }).setMessage('Aguarde,Regerando documentos...').execute();
    }



    /**
     * Realiza o processamento dos dados da lista de CDAs
     */
    function pesquisar() {

        if (empty($F('k60_codigo'))) {
            alert('Para esse processamento, é necessário selecionar uma lista.');
            return;
        }

        collection.clear();
        gridIniciaisRemessa.clear();
        var param = {
            exec: 'pesquisarIniciais',
            lista: $F('k60_codigo'),
            cgm: $F('z01_numcgm'),
            matricula: $F('j01_matric'),
            inscricao: $F('q02_inscr'),
            auto: $F('y50_codauto'),
            situacao: $F('cboTiposDeFiltro'),
        };

        new AjaxRequest(URL_RPC, param, function (retorno, erro) {
            if (erro) {
                alert(retorno.mensagem);
                return;
            }
            var stringTipoAmbiente = ' - <span style="font-weight: bold; color:blue">Ambiente de Produção</span>';
            if (retorno.tipoAmbiente == 2) {
                stringTipoAmbiente = ' - <span style="font-weight: bold; color:red;font-size:12pt">Ambiente de homologação</span>';
            }
            $('tipoAmbiente').innerHTML = stringTipoAmbiente;
            collection.clear();
            for (var inicial of retorno.iniciais) {
                inicial.inicial_key = inicial.inicial;
                collection.add(inicial);
            }
            gridIniciaisRemessa.reload();


        }).setMessage("Aguarde, iniciais da lista.").execute();
    }

    /**
     * REaliza o envio da remessa para o TJ
     */
    enviarRemessa = function() {

        if (empty($F('k60_codigo'))) {
            alert('Para esse processamento, é necessário selecionar uma lista.');
            return;
        }

        var processosEletronicos = [];
        var processoselecionados = gridIniciaisRemessa.getGrid().getSelection();

        if (processoselecionados.length == 0) {
            alert('Nenhuma inicial selecionada para envio.');
            return;
        }

        for (var processoDaLista of processoselecionados) {

            var processoEletronico = collection.get(processoDaLista[0]);
            if (processoEletronico.situacao != 2) {
                continue;
            }
            processosEletronicos.push(processoEletronico.codigo_processo_eletronico);
        }

        if (processosEletronicos.length == 0 && processoselecionados.length > 0) {
            alert('Apenas iniciais com a situção "Assinada" podem ser enviadas.');
            return;
        }

        if (!confirm('Confirma o envio ao TJ das iniciais selecionadas?')) {
            return false;
        }

        new AjaxRequest('jur4_proceletronicoremessa.RPC.php', {"exec":"processar", "iLista":$F('k60_codigo'), "processosEletronicos" :processosEletronicos},
             function (retorno, erro){

                alert(retorno.message);
                pesquisar();
        }).setMessage("Enviado remessa....").execute();
    };

    /**
     * lista os documentos para assinatura
     */
    function assinar()
    {

        totalIniciaisAssinadas = 0;
        iniciaisParaAssinar = [];
        var iniciaisSelecionadas = gridIniciaisRemessa.getGrid().getSelection();
        for (var inicialDaLista of iniciaisSelecionadas) {

            var inicial = collection.get(inicialDaLista[0]);
            if (inicial.situacao != 1) {
                continue;
            }
            inicialParaAssinar = {inicial: inicial.inicial, documentos:[]};
            for (var documentoInicial of inicial.documentos) {

                var documento = {
                    content: documentoInicial.arquivo,
                    name: documentoInicial.documento,
                    inicial: inicial.inicial,
                    assinado: false,
                    id: inicial.codigo_processo_eletronico
                };
                inicialParaAssinar.documentos.push(documento);

            }
            iniciaisParaAssinar.push(inicialParaAssinar);
        }

        if (iniciaisParaAssinar.length == 0) {

            alert('Todos os documentos informados já foram assinados.');
            return false;
        }

        assinador(iniciaisParaAssinar, (signed_files, documento) => {
                
                arquivos = new FormData();
                arquivos.append('json', JSON.stringify({'exec': 'gravarDocumentoAssinado', 'files': signed_files, 'id': documento.id}));
                fetch(URL_RPC, {
                    method: 'POST',
                    body: arquivos,
                    credentials: 'include',
                }).then(function (response) {
                    verificarInicialAssinada(documento);
                }).then(filhodoJ => {
                    alert(filhodoJ);
                    verificarInicialAssinada(documento);
                })

            });
    }

    /**
     * Verifica quais as iniciais foram assinadas e muda a situação das mesmas.
     */
    verificarInicialAssinada = function(documento)    {


        for (var inicial of iniciaisParaAssinar) {

            if (inicial.inicial != documento.inicial) {
                continue;
            }
            documento.assinado = true;
            var totalAssinados = 0;
            for (documentoNaInicial of inicial.documentos) {
                if (documentoNaInicial.assinado) {
                    totalAssinados++;
                }
            }
            if (totalAssinados == 1) {
                $('icone_assinatura_'+documento.inicial).style.display='none';
                totalIniciaisAssinadas++;
            }

        }
        if (totalIniciaisAssinadas === iniciaisParaAssinar.length) {

            js_removeObj('msgAssinatura');
            alert('Todas as iniciais selecionadas foram assinadas.');
            delete iniciaisParaAssinar;
            pesquisar();
        }
    };

    /**
     * Abre a janela para realizar a assinatura eletronica
     */
    function assinador(arquivos, userCallback)
    {

        var callback = userCallback;
        var certificados = [];
        function init()
        {

            windowCertificados = new windowAux('windAssinador', 'Assinar Documentos', 400, 300);
            var content = '<div style="width:100%">';
            content += '  <fieldset>';
            content += '  <legend>';
            content += '     Assinar Documentos';
            content += '  </legend>';
            content += '  <table>';
            content += '     <tr>';
            content += '       <td>';
            content += "          <label for='cboCertificados'><b>Certificado:</b></label>";
            content += '       </td>';
            content += '       <td>';
            content += "          <select id='cboCertificados' style='width:100%'></select>";
            content += '       </td>';
            content += '    </tr>';
            content += '    <tr>';
            content += '      <td>';
            content += "         <label for='txtSenhaAssinador'><b>PIN:</b></label>";
            content += '      </td>';
            content += '       <td>';
            content += "          <input type='password' id='txtSenhaAssinador' style='width:100%' />";
            content += '       </td>';
            content += '    </tr>';
            content += '  </table>';
            content += '</fieldset>';
            content += '<center>';
            content += "   <input type='button' disabled id='btnAssinarDocumentos' value='Assinar'>";
            content += '</center>';
            content += '</div>';
            windowCertificados.setContent(content);
            windowCertificados.setShutDownFunction(function () {

                windowCertificados.destroy();
                delete iniciaisParaAssinar;
            });

            var oMsgBoardAssinador = new DBMessageBoard('msgboardAssinador',
                'Assinar Documentos',
                'Selecione o certificado e informe o PIN',
                windowCertificados.getContentContainer()).show();

            windowCertificados.show(0, 0, true);

            var oDates = {
                convert: function (d) {
                    return (
                        d.constructor === Date ? d :
                            d.constructor === Array ? new Date(d[0], d[1], d[2]) :
                                d.constructor === Number ? new Date(d) :
                                    d.constructor === String ? new Date(d) :
                                        typeof d === "object" ? new Date(d.year, d.month, d.date) :
                                            NaN
                    );
                },
                compare: function (a, b) {
                    return (
                        isFinite(a = this.convert(a).valueOf()) &&
                        isFinite(b = this.convert(b).valueOf()) ?
                            (a > b) - (a < b) :
                            NaN
                    );
                },
                inRange: function (d, start, end) {
                    return (
                        isFinite(d = this.convert(d).valueOf()) &&
                        isFinite(start = this.convert(start).valueOf()) &&
                        isFinite(end = this.convert(end).valueOf()) ?
                            start <= d && d <= end :
                            NaN
                    );
                }
            }

            if (Pades != null && Pades != undefined) {

                js_divCarregando('Aguarde, pesquisando certificados', 'msgCertificados');
             
                Pades.util.server_available()
                    .then(function (available) {
                        if (!available) {
                            js_removeObj('msgCertificados');
                            alert("Servidor de assinatura está desligado.");
                            document.getElementById("btnAssinarDocumentos").disabled = true;
                        }
                    });

                Pades.PKCS11.certificates()
                    .then(function (certificates) {

                        certificates.forEach(function (oCertificate) {
                            var isvalid = oDates.inRange(new Date(), oCertificate.not_before, oCertificate.not_after);

                            if (isvalid) {

                                certificados.push(oCertificate);
                                $('cboCertificados').add(new Option(oCertificate.label, oCertificate.fingerprint));
                                document.getElementById("btnAssinarDocumentos").disabled = false;
                                $('cboCertificados').value = oCertificate.fingerprint;
                            }

                        });
                        js_removeObj('msgCertificados');
                    }).catch(function (err) {
                    js_removeObj('msgCertificados');
                     alert(err);
                     return;
                });
            } else {
                document.getElementById("btnAssinarDocumentos").disabled = true;
                js_removeObj('msgCertificados');
            }
        }

        function assinarDocumento(file, certificado, senha) {
          
            Pades.PKCS11.cms_sign(certificado, senha, [Pades.util.base64_to_file(file.content, file.name)])
                .then(signed_files => {
                    callback(signed_files, file);
                })
                .catch(err => alert(err));

        }

        init();
        $('btnAssinarDocumentos').onclick = function (){

            if (empty($F('txtSenhaAssinador'))) {

                alert('Informe o PIN do certificado.');
                return false;
            }
            if (!confirm('Confirma a assinatura digital das iniciais selecionadas?')) {
                return false;
            }
            var senha = $F('txtSenhaAssinador');
            var certificado = $F('cboCertificados');
            js_divCarregando('Aguarde,  assinando documentos. Esse processo pode levar alguns instantes.','msgAssinatura');
            windowCertificados.destroy();
            for (var inicial of arquivos) {
                $('icone_assinatura_'+inicial.inicial).style.display = '';
                for (var arquivo of inicial.documentos) {
                    assinarDocumento(arquivo, certificado, senha);
                }
            }
        };

    }

    /**
     * Verifica se a lista já foi processada
     */
    verificarProcessamentoDaLista = function() {

        if (empty($F('k60_codigo'))) {
            alert('Para esse processamento, é necessário selecionar uma lista.');
            return;
        }
       new AjaxRequest('jur4_processainiciais.RPC.php', {"exec":"verificarProcessamento", lista:$F('k60_codigo')},
           function (retorno, erro) {

            if (erro) {
                alert(retorno.mensagem);
                return;
            }

            if (retorno.deveProcessar) {
                abrirJanelaDeProcessamento(retorno.tipoDalista);
                return;
            }
            pesquisar();
        }).setMessage('Aguarde, verificando processamento da lista...').execute();
    }

    /**
     * Abre janela para usuário escolher agrupamento das iniciais
     */
    abrirJanelaDeProcessamento = function (tipoLista)
    {
        windowProcessamento = new windowAux('windProcessamento', 'Processsar da iniciais Lista', 400, 300);
        var content = '<div>';
        /*
        content += '  <fieldset>';
        content += '  <legend>';
        content += '     Processsar da iniciais da Lista '+$F('k60_codigo');
        content += '  </legend>';
        content += '  <table style="width: 100%;">';
        content += '    <tr>';
        content += '      <td>';
        content += '        <labelid="lblAgrupamento" for="agruparDebitos">';
        content += '                <b>Agrupar Por:</b>';
        content += '            </label>';
        content += '        </td>';
        content += '        <td>';
        content += '            <select id="agruparDebitos" style="width: 100%">';
        content += '                <option value="1">CGM</option>';
        content += '                <option value="2">Matrícula</option>';
        content += '                <option value="3">Inscrição</option>';
        content += '            </select>';
        content += '        </td>';
        content += '    </tr>';
        content += '  </table>';
        content += '</fieldset>';
        */
        content += '<div class="container">';
        content += "  <input type='button' id='btnProcessarLista' value='Processar'>";
        content += '</div>';
        content += '</div>';
        windowProcessamento.setContent(content);
        windowProcessamento.setShutDownFunction(function () {
            windowProcessamento.destroy();
        });
        windowProcessamento.show(null, null, true);
        oMsgBoardAutorizacoes = new DBMessageBoard('msgboardProcessamento',
            'Processamento das iniciais da lista',
            '',
            windowProcessamento.getContentContainer()).show();

        $('btnProcessarLista').observe('click', function(){

            if (empty($F('k60_codigo'))) {
                alert(_M(MENSAGENS+"nenhuma_lista_informada"));
                return;
            }

            if (!confirm(_M(MENSAGENS+"confirmar_processar"))) {
                return;
            }

            var param = {
                exec: 'processarLista',
                lista : $F('k60_codigo'),
                //agrupar: $F('agruparDebitos'),
            };

            new AjaxRequest('jur4_processainiciais.RPC.php', param, function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                    return;
                }

                alert('Processo concluido com sucesso.');
                windowProcessamento.destroy();
                pesquisar();
            }).setMessage("Aguarde, processamento dados da lista.").execute();
        });
    }

    function abrirConsultaInicial(iCodigoIncial) {

        js_OpenJanelaIframe('','db_consulta_inicial_iframe',  'func_inicialmovcert.php?v50_inicial=' + iCodigoIncial ,'Consulta Inicial: '+iCodigoIncial,true );
    }

    function abrirConsultaCertidao(iCodigoCertidao) {

        js_OpenJanelaIframe('','db_consulta_certidao_iframe',  'jur3_emiteinicial011.php?certidao=' + iCodigoCertidao ,'Consulta Certidão: '+iCodigoCertidao,true );
    }

    function visualizarDocumento(documento)
    {

        js_OpenJanelaIframe('','db_consulta_certidao_iframe',  'jur3_emiteinicial011.php?certidao=' + iCodigoCertidao ,'Consulta Certidão: '+iCodigoCertidao,true );
    }


    /**
     * Verifica se a lista já foi processada
     */
    reprocessarDocumentos = function(processo) {

        if (empty($F('k60_codigo'))) {
            alert('Para esse processamento, é necessário selecionar uma lista.');
            return;
        }
        new AjaxRequest('jur4_processainiciais.RPC.php', {"exec":"atualizarDocumento", "processo_eletronico": [processo], "lista":$F('k60_codigo')},
            function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                    return;
                }

                retorno.iniciais[0].inicial_key = retorno.iniciais[0].inicial;
                collection.add(retorno.iniciais[0]);
                collection.sort('asc', ['inicial']);
                gridIniciaisRemessa.reload();

            }).setMessage('Aguarde,Regerando documentos...').execute();
    }


    function relatorioErrosEnvios()
    {
        var lista = $F('k60_codigo');
        if (empty(lista)) {
            alert('Para esse processamento, é necessário selecionar uma lista.');
            return;
        }

        var url = 'jur2_integracaotjinconsistencias.php?lista='+lista
        window.open(url, '', 'location=0');
    }
</script>
