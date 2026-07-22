DBViewMaterialGrupo = function (iMaterialGrupo, sInstance, oNode) {

    var me = this;
    this.sRPC = 'mat4_materialgrupo.RPC.php';
    me.instance = sInstance;
    me.iCodigoEstrutura = 0;
    me.iCodigoGrupo = iMaterialGrupo;
    me.onSaveComplete = function (oRetorno) {

    }

    me.onBeforeSave = function () {
        return true;
    }
    /**
     * Pesquisamos os dados da estrutura que está configurado para ser utilizado no material
     */
    var oParam = new Object();
    oParam.exec = 'getCodigoEstrutural';
    var oAjax = new Ajax.Request(this.sRPC,
        {
            method: 'post',
            asynchronous: false,
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function (oAjax) {

                var oRetorno = JSON.parse(oAjax.responseText);
                me.iCodigoEstrutura = oRetorno.iCodigoEstrutura;
            }
        });

    this.dbViewEstrutural = new DBViewEstruturaValor(me.iCodigoEstrutura,
        "Grupo/Subgrupo Material", me.instance + ".dbViewEstrutural", oNode);
    this.dbViewEstrutural.setAjuda("Informe os dados para composição do grupo/Subgrupo");
    /**
     *Adiciona os campos referentes ao grupo do material
     */
    var oTabelaEstrutura = $('tblDados');

    var oRowConta = document.createElement("TR");
    var oCelulaLblConta = document.createElement("TD");
    var sLabelContabil = '<a onclick="' + me.instance + '.pesquisaContaContabil(true);"';
    sLabelContabil += 'style="text-decoration: underline;" class="dbancora" href="#"><b>Conta Contábil:</b></a>';
    oCelulaLblConta.innerHTML = sLabelContabil;
    var oCelulaCtnConta = document.createElement("TD");
    oCelulaCtnConta.id = 'ctnTxtConta';
    oCelulaCtnConta.noWrap = 'noWrap';


    var oRowContaVPD = document.createElement("TR");
    var oCelulaLblContaVPD = document.createElement("TD");
    var sLabelContabilVPD = '<a onclick="' + me.instance + '.pesquisaContaContabilVPD(true);"';
    sLabelContabilVPD += 'style="text-decoration: underline;" class="dbancora" href="#"><b>Conta Contábil VPD:</b></a>';
    oCelulaLblContaVPD.innerHTML = sLabelContabilVPD;
    var oCelulaCtnContaVPD = document.createElement("TD");
    oCelulaCtnContaVPD.id = 'ctnTxtContaVPD';
    oCelulaCtnContaVPD.noWrap = 'noWrap';

    /**
     * Cria conteiners para o codigo e descrição da Conta
     */
    var oSpanCodigoConta = document.createElement("span");
    oSpanCodigoConta.id = 'ctnTxtCodigoConta';

    var oSpanDescricaoConta = document.createElement("span");
    oSpanDescricaoConta.id = 'ctnTxtDescricaoConta';

    var oSpanEstruturalConta = document.createElement("span");
    oSpanEstruturalConta.id = 'ctnTxtEstrutural';

    var oSpanEstruturalContaVPD = document.createElement("span");
    oSpanEstruturalContaVPD.id = 'ctnTxtEstruturalVPD';

    var oSpanCodigoContaVPD = document.createElement("span");
    oSpanCodigoContaVPD.id = 'ctnTxtCodigoContaVPD';

    var oSpanDescricaoContaVPD = document.createElement("span");
    oSpanDescricaoContaVPD.id = 'ctnTxtDescricaoContaVPD';

    oCelulaCtnConta.appendChild(oSpanCodigoConta);
    oCelulaCtnConta.appendChild(oSpanEstruturalConta);
    oCelulaCtnConta.appendChild(oSpanDescricaoConta);

    oCelulaCtnContaVPD.appendChild(oSpanCodigoContaVPD);
    oCelulaCtnContaVPD.appendChild(oSpanEstruturalContaVPD);
    oCelulaCtnContaVPD.appendChild(oSpanDescricaoContaVPD);

    var oRowAtivo = document.createElement("TR");
    var oCelulaLblAtivo = document.createElement("TD");
    oCelulaLblAtivo.innerHTML = '<b>Grupo/Subgrupo Ativo:</b>';
    var oCelulaCtnAtivo = document.createElement("TD");
    oCelulaCtnAtivo.id = 'ctnCboAtivo';

    oRowConta.appendChild(oCelulaLblConta);
    oRowConta.appendChild(oCelulaCtnConta);

    oRowContaVPD.appendChild(oCelulaLblContaVPD);
    oRowContaVPD.appendChild(oCelulaCtnContaVPD);

    oRowAtivo.appendChild(oCelulaLblAtivo);
    oRowAtivo.appendChild(oCelulaCtnAtivo);

    oTabelaEstrutura.appendChild(oRowConta);
    oTabelaEstrutura.appendChild(oRowContaVPD);

    oTabelaEstrutura.appendChild(oRowAtivo);


    /**
     * Criamos os compenentes para os campos especificos
     */
    var aTipos = new Array();
    aTipos[1] = 'Sim';
    aTipos[2] = 'Nao';
    me.cboAtivo = new DBComboBox('cboAtivo',
        me.instance + ".cboAtivo",
        aTipos,
        '100%');
    me.cboAtivo.show($('ctnCboAtivo'));

    me.txtCodigoConta = new DBTextField('txtCodigoConta', me.instance + ".txtCodigoConta", '', 10);
    me.txtCodigoConta.addEvent("onChange", ";" + me.instance + ".pesquisaContaContabil(false)");
    me.txtCodigoConta.show($('ctnTxtCodigoConta'));

    me.txtCodigoContaVPD = new DBTextField('txtCodigoContaVPD', me.instance + ".txtCodigoContaVPD", '', 10);
    me.txtCodigoContaVPD.addEvent("onChange", ";" + me.instance + ".pesquisaContaContabilVPD(false)");
    me.txtCodigoContaVPD.show($('ctnTxtCodigoContaVPD'));

    me.txtDescricaoConta = new DBTextField('txtDescricaoConta', me.instance + ".txtDescricaoConta", '', 35);
    me.txtDescricaoConta.show($('ctnTxtDescricaoConta'));
    me.txtDescricaoConta.setReadOnly(true);

    me.txtDescricaoContaVPD = new DBTextField('txtDescricaoContaVPD', me.instance + ".txtDescricaoContaVPD", '', 35);
    me.txtDescricaoContaVPD.show($('ctnTxtDescricaoContaVPD'));
    me.txtDescricaoContaVPD.setReadOnly(true);

    me.txtEstrutural = new DBTextField('txtEstrutural', me.instance + ".txtEstrutural", '', 12);
    me.txtEstrutural.addEvent("onChange", ";" + me.instance + ".pesquisaEstrutural()");
    me.txtEstrutural.show($('ctnTxtEstrutural'));

    me.txtEstruturalVPD = new DBTextField('txtEstruturalVPD', me.instance + ".txtEstruturalVPD", '', 12);
    me.txtEstruturalVPD.addEvent("onChange", ";" + me.instance + ".pesquisaEstruturalVPD()");
    me.txtEstruturalVPD.show($('ctnTxtEstruturalVPD'));

    this.dbViewEstrutural.show();


    me.salvar = function () {

        var oParam = new Object();
        oParam.exec = 'salvarGrupo';
        oParam.oGrupo = new Object();

        oParam.oGrupo.iCodigoEstrutura = me.iCodigoEstrutura;
        oParam.oGrupo.sDescricao = encodeURIComponent(tagString(me.dbViewEstrutural.txtDescricao.getValue()));
        oParam.oGrupo.sEstrutural = encodeURIComponent(tagString(me.dbViewEstrutural.txtEstrutural.getValue()));
        oParam.oGrupo.iTipo = me.dbViewEstrutural.cboTipoEstrutural.getValue();
        oParam.oGrupo.lAtivo = me.cboAtivo.getValue();
        oParam.oGrupo.iConta = me.txtCodigoConta.getValue();
        oParam.oGrupo.iContaVPD = me.txtCodigoContaVPD.getValue();
        oParam.iCodigoGrupo = me.iCodigoGrupo;
        if (!me.onBeforeSave(oParam.oGrupo)) {
            return false;
        }
        js_divCarregando('Aguarde, salvando informações do grupo/subgrupo', 'msgBox');
        var oAjax = new Ajax.Request(me.sRPC,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function (oAjax) {

                    js_removeObj('msgBox');
                    var oRetorno = JSON.parse(oAjax.responseText);
                    if (oRetorno.status == 2) {
                        alert(oRetorno.message.urlDecode());
                    } else {
                        me.onSaveComplete(oRetorno);
                    }
                }
            });
    }
    $('btnSalvar').observe("click", me.salvar);
    $('btnCancelar').style.display = 'none';

    me.pesquisaEstrutural = function () {
        if (me.txtEstrutural.getValue() != '') {
            js_OpenJanelaIframe('',
                'db_iframe_conplano',
                'func_conplano.php?chave_c60_estrut=' + me.txtEstrutural.getValue() +
                '&funcao_js=parent.' + me.instance + '.completaConta|c60_codcon|c60_descr|c60_estrut',
                'Escolha uma Conta Contábil', false);
        } else {
            me.txtDescricaoConta.setValue('');
            me.txtCodigoConta.setValue('');
        }
    }

    me.pesquisaContaContabil = function (mostra) {
        if (mostra) {
            js_OpenJanelaIframe('',
                'db_iframe_conplano',
                'func_conplano.php?contabilGrupo&contasPatrimoniais=true&funcao_js=parent.' + me.instance + '.completaConta|c60_codcon|c60_descr|c60_estrut',
                'Escolha uma Conta Contábil',
                true);
        } else {
            if (me.txtCodigoConta.getValue() != '') {
                js_OpenJanelaIframe('',
                    'db_iframe_conplano',
                    'func_conplano.php?contasPatrimoniais=true&pesquisa_chave=' + me.txtCodigoConta.getValue() +
                    '&funcao_js=parent.' + me.instance + '.completaConta',
                    'Escolha uma Conta Contábil', false);
            } else {
                me.txtDescricaoConta.setValue('');
                me.txtEstrutural.setValue('');
            }
        }
    }

    me.completaConta = function () {

        if (typeof (arguments[1]) == "boolean") {
            if (arguments[1]) {
                console.log(arguments)
                me.txtDescricaoConta.setValue(arguments[0]);
                me.txtCodigoConta.setValue('');
            } else {
                console.log(arguments)
                me.txtDescricaoConta.setValue(arguments[0]);
                me.txtEstrutural.setValue(arguments[2]);
            }
        } else {
            console.log(arguments)
            me.txtDescricaoConta.setValue(arguments[1]);
            me.txtEstrutural.setValue(arguments[2]);
            me.txtCodigoConta.setValue(arguments[0]);
            db_iframe_conplano.hide();
        }
    }
    /*
     * funcao para ancora da conta do VPD
     */

    me.pesquisaEstruturalVPD = function () {
        if (me.txtEstruturalVPD.getValue() != '') {
            js_OpenJanelaIframe('',
                'db_iframe_conplano',
                'func_conplano.php?chave_c60_estrut=' + me.txtEstruturalVPD.getValue() +
                '&funcao_js=parent.' + me.instance + '.completaContaVPD|c60_codcon|c60_descr|c60_estrut',
                'Escolha uma Conta Contábil', false);
        } else {
            me.txtDescricaoContaVPD.setValue('');
            me.txtCodigoContaVPD.setValue('');
        }
    }
    me.pesquisaContaContabilVPD = function (mostra) {

        if (mostra) {
            js_OpenJanelaIframe('',
                'db_iframe_conplanoVPD',
                'func_conplano.php?VPDGrupo&lEstrutural=1&funcao_js=parent.' + me.instance + '.completaContaVPD|c60_codcon|c60_descr|c60_estrut',
                'Escolha uma Conta Contábil VPD',
                true);
        } else {
            if (me.txtCodigoContaVPD.getValue() != '') {
                js_OpenJanelaIframe('',
                    'db_iframe_conplanoVPD',
                    'func_conplano.php?lEstrutural=1&pesquisa_chave=' + me.txtCodigoContaVPD.getValue() +
                    '&funcao_js=parent.' + me.instance + '.completaContaVPD',
                    'Escolha uma Conta Contábil VPD', false);
            } else {
                me.txtDescricaoContaVPD.setValue('');
                me.txtEstruturalVPD.setValue('');
            }
        }
    }
    me.completaContaVPD = function () {

        if (typeof (arguments[1]) == "boolean") {
            if (arguments[1]) {
                me.txtEstruturalVPD.setValue(arguments[2]);
                me.txtDescricaoContaVPD.setValue(arguments[0]);
                me.txtCodigoContaVPD.setValue('');
            } else {
                me.txtEstruturalVPD.setValue(arguments[2]);
                me.txtDescricaoContaVPD.setValue(arguments[0]);
            }
        } else {
            me.txtEstruturalVPD.setValue(arguments[2]);
            me.txtDescricaoContaVPD.setValue(arguments[1]);
            me.txtCodigoContaVPD.setValue(arguments[0]);
            db_iframe_conplanoVPD.hide();
        }
    }


    me.getDados = function (iGrupo) {

        js_divCarregando('Aguarde, pesquisando dados grupo/subgrupo', 'msgBox');
        var oParam = new Object();
        oParam.exec = 'getDadosGrupo';
        oParam.iCodigoGrupo = iGrupo;
        var oAjax = new Ajax.Request(me.sRPC,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function (oAjax) {

                    js_removeObj('msgBox');
                    var oRetorno = JSON.parse(oAjax.responseText);
                    if (oRetorno.status == 2) {
                        alert(oRetorno.message.urlDecode());
                    } else {
                        me.dbViewEstrutural.txtDescricao.setValue(oRetorno.descricao.urlDecode());
                        me.dbViewEstrutural.txtEstrutural.setValue(oRetorno.estrutural.urlDecode());
                        me.dbViewEstrutural.cboTipoEstrutural.setValue(oRetorno.tipoconta);
                        me.cboAtivo.setValue(oRetorno.ativo);
                        me.txtCodigoConta.setValue(oRetorno.codigoconta);
                        me.txtEstrutural.setValue(oRetorno.estruturalConta);
                        me.txtCodigoContaVPD.setValue(oRetorno.codigocontaVPD);
                        me.txtEstruturalVPD.setValue(oRetorno.estruturalContaVPD);
                        me.txtDescricaoConta.setValue(oRetorno.descricaoconta.urlDecode());
                        me.txtDescricaoContaVPD.setValue(oRetorno.descricaocontaVPD.urlDecode());
                        me.iCodigoGrupo = oRetorno.codigogrupo;
                    }
                }
            });
    }
}
