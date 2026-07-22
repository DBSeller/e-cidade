<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2009  DBSeller Servicos de Informatica
*                    www.dbseller.com.br
*                 e-cidade@dbseller.com.br
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
?>
<form class="container" id="form" style="width: 800px;">
    <fieldset>
        <legend>Formulário de Cadastro para o eSocial</legend>
        <div id="formulario"></div>
        <div style="text-align: center;"><input type="button" value="Salvar" onclick="salvar()" id="btnDownload"></div>
    </fieldset>
</form>
<script>
    var 
        viewAvaliacao,
        codigoAvaliacao = 3000036,
        rpc = 'eso4_remuneracaorgps.RPC.php';

    carregar();

    function carregar(preenchimento = null) {
        const
            rpc = 'con4_manutencaoformulario.RPC.php',
            formData = new FormData(),
            objData = {};

        objData.exec = 'getDadosFormulario';
        objData.formulario = codigoAvaliacao;
        objData.codigo_resposta = $F('avaliacaogruporesposta');
        formData.append('json',  JSON.stringify(objData));

        return fetch(rpc, {
            method: 'POST',
            body: formData,
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            if (!!response.erro) {
                return alert(response.mensagem);
            }

            viewAvaliacao = DBViewFormulario.makeFromObject(response.oFormulario).show($('formulario'), true);
            
        });
    }

    function salvar() {        

        const msg = validaInputs();
        if (msg != "")
          return alert(msg);
        
        const 
            formData = new FormData(),
            objData = {};

        objData.executa             = 'salvarAvaliacao';
        objData.cgm                 = $('z01_numcgm').value;
        objData.mes                 = $('mes').value;
        objData.ano                 = $('ano').value;
        objData.avaliacao           = codigoAvaliacao;
        objData.avaliacaogruporesposta = $F('avaliacaogruporesposta');
        objData.perguntasRespostas  = viewAvaliacao.getDados();

        formData.append('json', JSON.stringify(objData));
        
        js_divCarregando('Salvando Formulário', 'loading_message');

        return fetch(rpc, {
          method: 'POST',
          body: formData,
          credentials: 'include',
        }).then(response => response.json()).then(response => {
          alert(response.mensagem);

          if (response.error) {
            return;
          }
        }).finally(() => js_removeObj('loading_message'));
    }

    function validaInputs(){
        const
            formulario = $('formulario'),
            radioList = formulario.querySelectorAll('input[type="radio"][name="tipo-de-processo-judicial"]'),
            arrTipoProcesso = Array.from(radioList).filter(node => node.checked == true),
            codigoIrrf = 4000950,
            codigoContribuicoesSociais = 4000951;
        var
            msg = "";

        //Verifica se algum processo foi selecionado
        if (arrTipoProcesso.length == 0)
            return "Nenhum tipo de processo selecionado";

        const codigoTipoProcesso = arrTipoProcesso[0].getAttribute('codigo');

        //Verifica se tipo de processo = 1 ou 2, nesses casos codico indicativo é obrigatorio
        if(codigoTipoProcesso == codigoIrrf || codigoTipoProcesso == codigoContribuicoesSociais){
            const codigoIndicativo = formulario.querySelector('input[identificador="codSusp"]');
            if(codigoIndicativo.value == "")
                return "Necessário preencher o código indicativo para esse tipo de processo!";
        }

        const numeroProcesso = formulario.querySelector('input[identificador="nrProcJud"]');
        if(numeroProcesso.value == "")
            return "Necessário preencher o número do processo!";

        return msg;
    }
</script>
