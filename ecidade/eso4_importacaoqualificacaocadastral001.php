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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

?>

<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body class="body-default">
    <div class="container" style="text-align: left">
        <form name="form1" method="post" action="" enctype="multipart/form-data">
            <fieldset style="width: 600px;">
                <legend>Importação Retorno da Qualificação Cadastral</legend>
                <div id="arquivoImportacao"></div>
            </fieldset>
            <div style="width: 100%; text-align: center;">
                <input name="importar" id="importar" type="button" value="Importar" onclick="importarArquivo()"/>
            </div>
            <fieldset style="width: 600px;">
                <legend>Arquivos Importados</legend>
                <div id="gridArquivosImportados"></div>
            </fieldset>
        </form>
    </div>
    </body>

    <?php db_menu(); ?>
    
    <script type="text/javascript">
        const RPC = 'eso4_qualificacaocadastral.RPC.php';

        var collection = new Collection().setId('id');
        var gridArquivosImportados = DatagridCollection.create(collection).configure("order", false);

        var arquivoImportacao = new DBFileUpload();
        arquivoImportacao.show($('arquivoImportacao'));

        gridArquivosImportados.addColumn("data",{label: "Data", align: "center", width: "16%"});
        gridArquivosImportados.addColumn("hora",{label: "Hora", align: "center", width: "10%"});
        gridArquivosImportados.addColumn("nomeArquivo",{label: "Nome", align: "center", width: "40%"});
        gridArquivosImportados.addColumn("processado",{label: "Tipo", align: "center", width: "17%"});
        gridArquivosImportados.addAction('Excluir', 'Excluir', function (elemento, arquivo) {
            excluirArquivo(arquivo.id);
        });

        gridArquivosImportados.show($('gridArquivosImportados'));

        /**
         * Adiciona um array de arquivos na Grid
         * @param Array arquivos
         */
        function adicionarArquivosGrid(arquivos) {
            if (!Array.isArray(arquivos)) {
                return;
            }

            gridArquivosImportados.clear();
            for (var arquivo of arquivos) {
                arquivo.processado = arquivo.processado == 't' ? 'Processado' : 'Rejeitado';
                collection.add(arquivo);
            }
            gridArquivosImportados.reload();
        }

        /**
         * Busca os arquivos para serem adicionados na grid.
         */
        function buscarArquivosImportados() {
            new AjaxRequest(RPC, {executa: 'buscarArquivosImportados'}, function (retorno, erro) {
                if (erro){
                    alert(retorno.mensagem);
                    return;
                }

                adicionarArquivosGrid(retorno.arquivos);
            })
                .setMessage('Buscando arquivos importados...')
                .execute();
        }

        /**
         * Exclui um arquivo que foi importado.
         * @param int codigo
         */
        function excluirArquivo(codigo) {
            if (!confirm('Deseja excluir o arquivo importado?')) {
                return false;
            }

            new AjaxRequest(RPC, {executa: 'excluirArquivo', codigo: codigo}, function (retorno, erro) {
                alert(retorno.mensagem);
                if (!erro) {
                    removerArquivoGrid(codigo);
                }
            })
                .execute();
        }

        /**
         * Remove arquivo importado da grid.
         * @param codigo int
         */
        function removerArquivoGrid(codigo) {
            collection.remove(codigo);
            gridArquivosImportados.reload();
        }

        /**
         * Envia o arquivo txt para ser importado no servidor
         */
        function importarArquivo() {
            var parametros = {};
            parametros.executa = 'importarArquivo';
            parametros.arquivo = arquivoImportacao.file;
            parametros.caminhoArquivo = arquivoImportacao.filePath;

            new AjaxRequest( RPC, parametros, function(retorno, erro) {
                alert(retorno.mensagem);
                if (!erro) {
                    buscarArquivosImportados();
                }
            })
                .execute();
        }

        (function () {
            buscarArquivosImportados();
        })();
        
    </script>
</html>
