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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));


?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/cadastro/civitas/DBViewAtualizacaoCadastral.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <style>
        .codigo_matricula{
            cursor : pointer;
        }
        .codigo_matricula:hover{
            text-decoration: underline;
        }
    </style>
</head>
<body class='body-default'>

<div class='container' style='width:400px;'>
    <form action="post" name='form1'>
        <fieldset>
            <legend>Parâmetros de pesquisa</legend>
            <table class='form-container'>
                <tr>
                    <td class='field-size2'><label for="cboSchema" >Importação:</label></td>
                    <td>
                        <select name="schema" id="cboSchema">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'><label for="cboSetor">Setor:</label></td>
                    <td>
                        <select name="setor" id="cboSetor">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'><label for="cboQuadra">Quadra:</label></td>
                    <td>
                        <select name="quadra" id="cboQuadra">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'>
                        <label for="lote">Lote:</label>
                    </td>
                    <td>
                        <select id="lote">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'>
                        <label for="matricula">
                            <a href="#" id="ancoraMatricula">Matrícula do imóvel:</a>
                        </label>
                    </td>
                    <td>
                        <input id="j01_matric" type="text" value="" class="field-size2" />
                        <input id="z01_nome" type="text" value="" class="field-size7 readonly" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'><label for="logradouro">Logradouro:</label></td>
                    <td>
                        <input id="logradouro" type="text" value="" class="field-size9" />
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'><label for="situacao">Situação:</label></td>
                    <td>
                        <select name="situacao" id="situacao">
                            <option value="0">Pendente</option>
                            <option value="2">Aprovada</option>
                            <option value="3">Rejeitada</option>
                            <option value="4">Processada</option>
                            <option value="5">Todas</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='field-size2'><label for="cboFiltro">Filtro:</label></td>
                    <td>
                        <select name="filtro" id="cboFiltro">
                            <option value="0">Visualizar todos</option>
                            <option value="1">Somente com aumento de IPTU</option>
                            <option value="2">Somente com diminuição de IPTU</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" value="Pesquisar" id='btnPesquisar' disabled />
        <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Imprimir" onClick="js_AbreJanelaRelatorio()"/>
    </form>
</div>
<input type="hidden" id="aMatric" />
<div class='subcontainer' style='width:1280px;' >
    <fieldset >
        <legend>Matrículas</legend>
        <div id='ctnGrid'></div>
    </fieldset>
    <input type="button" value="Aprovar"    id='btnAtualizarGeral' /> &nbsp;
    <input type="button" value="Rejeitar"   id='btnRejeitarGeral' /> &nbsp;
    <input type="button" value="Processar"  id='btnProcessarGeral' />
</div>

<?php db_menu(); ?>

<script type='text/javascript'>

    oAutoComplete = new dbAutoComplete(document.getElementById('logradouro'), 'cad4_recadastramento.RPC.php');
    oAutoComplete.setTxtFieldId($('logradouro'));
    oAutoComplete.show();
    oAutoComplete.setMinLength(3);
    oAutoComplete.setValidateFunction(function(){
        var lReturn = true;

        if (document.form1.cboSchema.value == "") {
            lReturn  = false;
        }
        return lReturn;
    });

    oAutoComplete.setHeightList(300);

    oAutoComplete.setQueryStringFunction(function() {
        var oParam      = new Object();
        oParam.exec     = 'buscaRua';
        oParam.sSchema   = getSchema();
        oParam.sLogradouro   = document.form1.logradouro.value;
        var sQuery  = 'json='+ Object.toJSON(oParam);
        return sQuery;
    });

    oAutoComplete.setCallBackFunction(function(cod, label) {
        $('logradouro').value = label;
    });

    function js_AbreJanelaRelatorio() {

        if ( empty($F('cboSchema')) || empty($F('cboSetor')) ) {
            alert('Selecione uma data de importação e um setor para pesquisar as matrículas.');
            return;
        }


        var oParametros = {
            exec : 'buscarMatriculas',
            sSchema     : getSchema(),
            iSchema     : $F('cboSchema'),
            iSetor      : $F('cboSetor'),
            sQuadra     : $F('cboQuadra'),
            sLote       : $F('lote'),
            iMatricula  : $F('j01_matric'),
            sLogradouro : $F('logradouro'),
            iSituacao   : $F('situacao'),
            iFiltro     : $F('cboFiltro'),
            ret         : 'json'
        }

        new AjaxRequest('cad4_recadastramento.RPC.php', oParametros, function(oRetorno, lErro) {

            jan  = window.open('cad4_recadastramentorelatorio.php?params='+  encodeURIComponent(JSON.stringify(oParametros)) ,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);
        }).setMessage('Gerando Relatório ...').execute();


        return;

    }


    var oLookupMatricula = new DBLookUp (
        $('ancoraMatricula'),
        $('j01_matric'),
        $('z01_nome'),
        {
            'sArquivo' : 'func_iptubase.php',
            'sLabel'   : 'Pesquisa de Matrícula do Imóvel'
        }
    );

    var oWindowObservacao = null;
    var sRPC = 'cad4_recadastramento.RPC.php';

    var oCollection     = new Collection().setId("iMatricula");
    var oGridMatriculas = new DatagridCollection(oCollection).configure({
        order    : false,
        height   : 300
    });

    oGridMatriculas.getGrid().setCheckbox(1);
    oGridMatriculas.addColumn("iMatricula", {
        label : "Matrícula",
        align : "right",
        width : "8%"
    }).transformCallback = function( iMatricula, itemCollection ) {
        var matricula = iMatricula;
        if (itemCollection.iNovaMatricula) {
            matricula = 'Nova matrícula';
        }

        return itemCollection.lProcessado ? matricula : "<span class='codigo_matricula' onclick='detalhamento("+iMatricula+")'>" + matricula +" </span>";
    };
    oGridMatriculas.addColumn("iSituacao", {
        label : "Situação",
        align : "center",
        width : "8%"
    }).transformCallback = function( iSituacao, itemCollection ) {

        var sSituacao = 'Pendente';

        switch(parseInt(iSituacao)) {

            case 2:
                sSituacao = 'Aprovada';
                break;

            case 3:
                sSituacao = 'Rejeitada';
                sSituacao = 'Rejeitada';
                break;
        }

        if (itemCollection.lProcessado) {
            sSituacao = 'Processada';
        }

        if (itemCollection.lProcessado &&  parseInt(iSituacao) == 3) {
            sSituacao = 'Proc. Rejeitada';
        }

        return sSituacao;
    };




    oGridMatriculas.addColumn("sSetor", {
        label  : "Setor",
        align  : "center",
    });

    oGridMatriculas.addColumn("sQuadra", {
        label  : "Quadra",
        align  : "center",
        width  : "5%"
    });

    oGridMatriculas.addColumn("sLote", {
        label  : "Lote",
        align  : "center",
        width  : "5%"
    });
    oGridMatriculas.addColumn("nValorAtual", {
        label : "IPTU antes",
        align : "right",
        width : "8%"
    }).transform('number');
    oGridMatriculas.addColumn("nValorNovo", {
        label : "IPTU depois",
        align : "right",
        width : "8%"
    }).transform('number');

    oGridMatriculas.addColumn("sQuadraLocalizacao", {
        label  : "Qd. local.",
        align  : "center",
        width  : "7%"
    });

    oGridMatriculas.addColumn("sLoteLocalizacao", {
        label  : "Lote local.",
        align  : "center",
        width  : "7%"
    });

    oGridMatriculas.addColumn("sEndereoCompleto", {
        label  : "Endereço",
        align  : "left",
        width  : "10%"
    }).transformCallback = function( sEndereoCompleto, itemCollection ) {
        return sEndereoCompleto.length > 10 ? criarTextoComHint(sEndereoCompleto.replace(/^(.*?)\s(Bairro.*)$/g, "$1<br/>$2"), 10, 10) : sEndereoCompleto;
    };

    oGridMatriculas.addColumn("sCaracteristicaConstrucao", {
        label  : "Caract. Const.",
        align  : "center",
        width  : "9%"
    }).transformCallback = function( sCaracteristicaConstrucao, itemCollection ) {
        return sCaracteristicaConstrucao.length > 10 ? criarTextoComHint(sCaracteristicaConstrucao, 10, 11) : sCaracteristicaConstrucao;
    };
    oGridMatriculas.addColumn("sAeu", {
        label  : "AEU",
        align  : "center",
        width  : "7%"
    }).transformCallback = function( sAeu, itemCollection ) {
        return sAeu.length > 10 ? criarTextoComHint(sAeu, 10, 12) : sAeu;
    };




    oGridMatriculas.addColumn("sMotivoRejeicao", {
        label  : "Motivo",
        align  : "left",
        width  : "8%"
    }).transformCallback = function( sMotivoRejeicao, itemCollection ) {
        return sMotivoRejeicao.length > 5 ? criarTextoComHint(sMotivoRejeicao, 5, 13) : sMotivoRejeicao;
    };
    oGridMatriculas.addColumn("sRazao", {
        label : "Proprietário",
        align : "left",
        width : "10%"
    }).transformCallback = function( sRazao, itemCollection ) {
        return sRazao.length > 8 ? criarTextoComHint(sRazao, 8, 14) : sRazao;
    };




    oGridMatriculas.grid.allowSelectColumns(true);
    // oGridMatriculas.hideColumns([3,4,5,6,7,8,9,10,11]);
    oGridMatriculas.show($('ctnGrid'));

    (function(){

        $('cboSchema').options.length = 0;
        $('cboSetor').options.length  = 0;
        $('cboSchema').add(new Option('Selecione...', '') );
        $('cboSetor').add(new Option('Selecione...', '') );
        new AjaxRequest(sRPC, {exec : 'buscarFiltros'}, function(oRetorno, lErro) {

            if ( lErro ) {

                alert(oRetorno.sMessage);
                return;
            }

            for (var oSchema of oRetorno.aSchemas) {

                var oOption       = document.createElement('option');
                oOption.value     = oSchema.j142_sequencial;
                oOption.innerHTML = oSchema.sDescricao;
                oOption.setAttribute('data-schema', oSchema.j142_schema);
                $('cboSchema').appendChild(oOption);
            }

            if (oRetorno.aSchemas.length > 0) {
                $('btnPesquisar').removeAttribute('disabled');
            }
        }).setMessage('Buscando schemas...').execute();

        $('cboSchema').addEventListener('change', function() {

            oGridMatriculas.clear();
            $('cboFiltro').value = 0;
            $('cboSetor').innerHTML = '';
            $('cboSetor').add(new Option('Selecione...', '') );
            $('cboQuadra').innerHTML = '';
            $('cboQuadra').add(new Option('Selecione...', '') );

            if (empty(this.value)) {
                return false;
            }

            var oParametros = {
                'exec'    : 'buscarSetores',
                'iSchema' : $('cboSchema').value,
                'sSchema' : getSchema()
            }

            new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

                for (var oSetor of oRetorno.aSetores) {

                    var oOption = document.createElement('option');
                    oOption.setAttribute('value', oSetor.j30_codi);
                    oOption.appendChild(document.createTextNode(oSetor.j30_descr));

                    $('cboSetor').appendChild(oOption);
                }
            }).setMessage('Buscando setores...').execute();
        });

        $('cboSetor').addEventListener('change', function() {

            oGridMatriculas.clear();
            $('cboFiltro').value = 0;
            $('cboQuadra').innerHTML = '';
            $('cboQuadra').add(new Option('Selecione...', '') );

            if (empty(this.value)) {
                return false;
            }

            var oParametros = {
                'exec'    : 'buscarQuadras',
                'iSchema' : $('cboSchema').value,
                'sSetor' : $('cboSetor').value,
                'sSchema' : getSchema()
            }

            new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

                for (var oQuadra of oRetorno.aQuadras) {

                    var oOption = document.createElement('option');
                    oOption.setAttribute('value', oQuadra.j34_quadra);
                    oOption.appendChild(document.createTextNode(oQuadra.j34_quadra));

                    $('cboQuadra').appendChild(oOption);
                }
            }).setMessage('Buscando quadras...').execute();
        });

        $('cboQuadra').addEventListener('change', function() {

            oGridMatriculas.clear();
            $('cboFiltro').value = 0;
            $('lote').innerHTML = '';
            $('lote').add(new Option('Selecione...', '') );

            if (empty(this.value)) {
                return false;
            }

            var oParametros = {
                'exec'    : 'buscarLotes',
                'iSchema' : $('cboSchema').value,
                'sSetor'  : $('cboSetor').value,
                'sQuadra' : $('cboQuadra').value,
                'sSchema' : getSchema()
            }

            new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

                for (var oLote of oRetorno.aLotes) {

                    var oOption = document.createElement('option');
                    oOption.setAttribute('value', oLote.j34_lote);
                    oOption.appendChild(document.createTextNode(oLote.j34_lote));

                    $('lote').appendChild(oOption);
                }
            }).setMessage('Buscando lotes...').execute();
        });

        $('cboFiltro').addEventListener('change', function() {
            oGridMatriculas.clear();
        });

    })();

    $('btnPesquisar').addEventListener('click', function() {

        if ( empty($F('cboSchema')) || empty($F('cboSetor')) ) {

            alert('Selecione uma data de importação e um setor para pesquisar as matrículas.');
            return;
        }

        oCollection.clear();
        oGridMatriculas.reload();
        var oParametros = {
            exec : 'buscarMatriculas',
            sSchema     : getSchema(),
            iSchema     : $F('cboSchema'),
            iSetor      : $F('cboSetor'),
            sQuadra     : $F('cboQuadra'),
            sLote       : $F('lote'),
            iMatricula  : $F('j01_matric'),
            sLogradouro : $F('logradouro'),
            iSituacao   : $F('situacao'),
            iFiltro     : $F('cboFiltro')
        }
        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

            if ( lErro ) {

                alert(oRetorno.sMessage);
                return;
            }

            var aMatric = [];

            for (var oMatricula of oRetorno.aMatriculas) {

                aMatric.push({"iMatricula": oMatricula.iMatricula, "sSetor" :oMatricula.sSetor, "sQuadra": oMatricula.sQuadra , "sLote": oMatricula.sLote});
                oCollection.add(oMatricula);
            }

            $('aMatric').value =  JSON.stringify(aMatric);

            oGridMatriculas.reload();

            oGridMatriculas.getGrid().getRows().forEach(function(oRow, iItem) {

                var oDados = oGridMatriculas.getCollection().get()[iItem];

                if (oDados.lProcessado) {

                    oRow.addClassName('disabled');

                    try {
                        $(oRow.getCells()[0].getId()).querySelector('input').disabled = true;
                    } catch (e) {
                        console.error(e);
                    }

                } else if (oDados.nValorAtual != oDados.nValorNovo) {

                    oRow.removeClassName('normal');

                    if (parseFloat(oDados.nValorAtual) < parseFloat(oDados.nValorNovo)) {
                        oRow.addClassName('sucess');
                    } else {
                        oRow.addClassName('error');
                    }
                }

                try {

                    for(var item of $(oRow.getId()).querySelectorAll('.hintTextoCompleto')) {

                        var textoHint       = item.getAttribute('data-texto-completo');
                        var colunaTextoHint = item.getAttribute('data-coluna-completo');

                        oGridMatriculas.grid.setHint(iItem, colunaTextoHint, '<b>' + textoHint + "</b>");
                    };
                } catch (e) {
                    console.error(e);
                }
            });
        }).setMessage('Buscando matrículas...').execute();

    })

    var oViewDetalhamento = null;

    /**
     * Abre a window de detalhamento da matrícula
     * @param {integer} iMatricula matrícula selecionada
     */
    function detalhamento(iMatricula) {

        if ( oViewDetalhamento != null ) {
            return;
        }

        var oSchema = {
            'sSchema' : getSchema(),
            'iSchema' : $F('cboSchema')
        };
    

        oViewDetalhamento = new DBViewAtualizacaoCadastral( oCollection.get(iMatricula).build(), oSchema);
        oViewDetalhamento.matriculasSelecionadas(oCollection.build());
        oViewDetalhamento.setCallbackFechar(function(){
            oViewDetalhamento = null;
        });

        oViewDetalhamento.show();
    }

    /**
     * Monta um array com as matrículas selecionadas na grid
     * @return {void}
     */
    function buscarSelecionados(processamento , completo) {

        var aMatriculasSelecionadas = [];
        var aLinhasGrid             = oGridMatriculas.getGrid().aRows;

        for (var oLinha of aLinhasGrid) {

            if (oLinha.isSelected ) {

                if (processamento) {

                    if (oLinha.itemCollection.iSituacao == 0 || oLinha.itemCollection.iSituacao == 1) {
                        continue;
                    }
                }

                if (completo) {
                    aMatriculasSelecionadas.push({"iMatricula" : oLinha.itemCollection.iMatricula ,"sSetor" :  oLinha.itemCollection.sSetor, "sQuadra" : oLinha.itemCollection.sQuadra , "sLote" : oLinha.itemCollection.sLote , 'iSituacao': oLinha.itemCollection.iSituacao});
                } else {
                    aMatriculasSelecionadas.push(oLinha.itemCollection.iMatricula);
                }

            }

        }

        return aMatriculasSelecionadas;
    }

    /**
     * Envia a uma requisição para Atualizar/Rejeitar as matrículas
     * @return {void}
     */
    function enviarRequisicao(lAtualizar) {

        var sMsg             = 'Aprovando matrículas selecionadas...';
        var acao             = 'atualizar';
        var sMsgConfirmacao  = "Tem certeza que deseja Aprovar o cadastro das matrículas selecionadas?";
        var iSituacao        = 2;

        if ( !lAtualizar ) {

            sMsg             = 'Rejeitando matrículas selecionadas...';
            acao             = 'rejeitar';
            sMsgConfirmacao  = "Tem certeza que deseja Rejeitar a atualização do cadastro das matrículas selecionadas?";
            iSituacao        = 3;
        }

        var aMatriculas = buscarSelecionados();

        if ( aMatriculas.length == 0 ) {

            alert('Selecione ao menos uma matrícula.');
            return false;
        }

        var bAtualizaOutras = false;
        var outrasMatriculas =   existMatricInSetorAndQuadraAndLote();

        if (!lAtualizar && outrasMatriculas.size() > 0) {
            if (confirm("Deseja rejeitar as demais matrículas que estão no mesmo setor / quadra / lote, que as matrículas selecionadas?")) {
                bAtualizaOutras = true
            }

        }


        if (!confirm( sMsgConfirmacao )) {
            return false;
        }

        var sMotivoRejeicao = '';

        if($('motivoRejeicao')) {
            sMotivoRejeicao = $F('motivoRejeicao');
        }

        var oParametros = {
            exec              : acao,
            aMatriculas       : aMatriculas,
            sNomeImportacao   : getSchema(),
            iCodigoImportacao : $F('cboSchema'),
            iSituacao         : iSituacao,
            sMotivoRejeicao   : sMotivoRejeicao
        };

        if (bAtualizaOutras) {
            oParametros['outrasMatriculas'] = outrasMatriculas;
        }


        new AjaxRequest(sRPC, oParametros, function (oRetorno, lErro){

            alert(oRetorno.sMessage);
            if ( lErro ) {
                return false;
            }

            $('btnPesquisar').click();

            if ( !lAtualizar ) {
                oWindowObservacao.divContent.querySelector('#motivoRejeicao').value = '';
                oWindowObservacao.destroy();
            }

        }).setMessage(sMsg).execute();
    };

    function enviarRequisicaoProcessamento() {

        var sMsg             = 'Processando matrículas selecionadas...';
        var acao             = 'processar';
        var sMsgConfirmacao  = "Tem certeza que deseja Processar a(s) matrícula(s) selecionada(s)?";

        var aMatriculas  = buscarSelecionados(true);

        if (aMatriculas.length == 0) {
            alert('Para processar selecione apenas matrículas que não estão com a situação pendente.');
            return false;
        }

        if (!confirm(sMsgConfirmacao)) {
            return false;
        }

        var aMatriculasProcessar = [];
        aMatriculas.forEach(function(matricula, i) {

            aMatriculasProcessar.push({
                iMatricula : matricula,
                iSituacao  : oGridMatriculas.collection.get(matricula).iSituacao
            })
        });

        var oParametros = {
            exec              : acao,
            aMatriculas       : aMatriculasProcessar,
            sNomeImportacao   : getSchema(),
            iCodigoImportacao : $F('cboSchema')
        };
        new AjaxRequest(sRPC, oParametros, function (oRetorno, lErro){

            alert(oRetorno.sMessage);
            if ( lErro ) {
                return false;
            }

            $('btnPesquisar').click();

        }).setMessage(sMsg).execute();
    };

    $('btnAtualizarGeral').addEventListener('click', function(){
        enviarRequisicao(true);
    });

    $('btnRejeitarGeral').addEventListener('click', function(){

        var aMatriculas = buscarSelecionados();
        if ( aMatriculas.length == 0 ) {

            alert('Selecione ao menos uma matrícula.');
            return false;
        }

        criarWindowMotivoRejeicao();
    });

    $('btnProcessarGeral').addEventListener('click', function(){
        enviarRequisicaoProcessamento();
    });

    function getSchema() {
        return $('cboSchema').options[$('cboSchema').selectedIndex].getAttribute("data-schema");
    }

    function criarWindowMotivoRejeicao() {

        if ( !empty(oWindowObservacao) ) {
            oWindowObservacao.show(205, false, true);
            return null;
        }

        oWindowObservacao = new windowAux("wndObservacaoRejeicao", "Motivo da Rejeição", 600, 245 );
        oWindowObservacao.zIndex = 9999;
        oWindowObservacao.allowCloseWithEsc(false);

        var sConteudo  = "<div class='observacao' style='width:100%;'>";
        sConteudo += "  <div id='conteudo' style='width:100%; '>";
        sConteudo += "    <div style='position: relative; width: 99%; margin: auto; '> ";
        sConteudo += "      <div id='a' style='width: 49%; float: left; display: inline-block; '> ";
        sConteudo += "        <fieldset>";
        sConteudo += "          <legend>Motivo</legend>";
        sConteudo += "          <textarea id='motivoRejeicao' rows='10'cols='75'></textarea>";
        sConteudo += "        </fieldset>";
        sConteudo += "      </div>";
        sConteudo += "    </div>";
        sConteudo += "    <div style='clear:both; padding-top:05px'>";
        sConteudo += "      <input type='button' id='btnSalvarObservacao' value='Rejeitar' style='display: block; margin: 0 auto' />";
        sConteudo += "    </div>";
        sConteudo += "  </div>";
        sConteudo += "</div>";

        oWindowObservacao.setShutDownFunction( function() {
            oWindowObservacao.destroy();
        }.bind(this));

        oWindowObservacao.setContent(sConteudo);
        oWindowObservacao.show(205, false, true);

        $('btnSalvarObservacao').addEventListener('click', function(){

            enviarRequisicao(false);

        });
    }
    function existMatricInSetorAndQuadraAndLote() {

        var allMatric = JSON.parse($F('aMatric'));
        var tmp = [];

        var aSelecionados = buscarSelecionados(false, true);

        var sTmp , sTmp2;
        var i = 0, acumulador = 0;

        for (var oSelecinado of aSelecionados) {

            acumulador = 0;
            sTmp = oSelecinado.sQuadra + '-' + oSelecinado.sSetor + '-' + oSelecinado.sLote;

            for (var matric of allMatric) {
                sTmp2 = matric.sQuadra + '-' + matric.sSetor + '-' + matric.sLote;

                if (sTmp == sTmp2 && matric.iMatricula != oSelecinado.iMatricula) {
                    if (tmp.indexOf(matric.iMatricula) == -1) {
                        tmp.push(matric.iMatricula);
                    }
                }
            }
        }

        return tmp;
    }

    function criarTextoComHint(textoCompleto, tamanho, coluna) {

        var oDiv = new Element('div');

        oDiv.setAttribute('data-texto-completo',textoCompleto);
        oDiv.setAttribute('data-coluna-completo',coluna);
        oDiv.addClassName('hintTextoCompleto');

        oDiv.innerHTML  = textoCompleto.substr(0, tamanho);
        oDiv.innerHTML += "...";

        return oDiv.outerHTML;
    }

</script>
</body>
</html>
