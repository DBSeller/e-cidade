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
        <title>DBSeller Informática Ltda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body class="body-default">
    <div class="container">
        <form name="form1" method="post">
            <fieldset>
                <legend>Relatório de Qualificação Cadastral</legend>
                <table class="form-container">
                    <tr>
                        <td class="field-size2"><label for="cbxArquivoImportado">Arquivo importado:</label></td>
                        <td class="field-size9">
                            <select id="cbxArquivoImportado">
                                <option value="0">Selecione...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <fieldset class="separator">
                                <legend>Filtros</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="rh37_funcao"><a id="labelCargo">Cargo:</a></label>
                                        </td>
                                        <td>
                                            <input type="text" name="rh37_funcao" id="rh37_funcao">
                                            <input type="text" name="rh37_descr" id="rh37_descr">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="r70_codigo"><a id="labelLotacao">Lotação:</a></label>
                                        </td>
                                        <td>
                                            <input type="text" name="r70_codigo" id="r70_codigo">
                                            <input type="text" name="r70_descr" id="r70_descr">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="cbxListarServidores">Listar Servidores:</label></td>
                                        <td>
                                            <select id="cbxListarServidores">
                                                <option value="0">Todos</option>
                                                <option value="1">Somente com Inconsistências</option>
                                                <option value="2">Somente sem Inconsistências</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir">
        </form>
    </div>
    </body>

    <?php db_menu(); ?>
    
    <script type="text/javascript">

        function buscarArquivosImportados(){

            new AjaxRequest("eso4_qualificacaocadastral.RPC.php", {"executa" : "buscarArquivosImportados"}, function(retorno, erro){
                var cbxArquivo = $('cbxArquivoImportado');
                for(var arquivo of retorno.arquivos) {

                    var nome = arquivo.data + ' - ' + arquivo.nomeArquivo;
                    cbxArquivo.add(new Option(nome, arquivo.id));
                }

            }).setMessage("Aguarde...").execute();
        }
        buscarArquivosImportados();

        var cargoLookUp = new DBLookUp($('labelCargo'), $('rh37_funcao'), $('rh37_descr'), {
          "sArquivo" : "func_rhfuncao.php",
          "sObjetoLookUp" : "db_iframe_rhfuncao",
          "sLabel" : "Pesquisar Cargo",
        });

        var lotacaoLookUp = new DBLookUp($('labelLotacao'), $('r70_codigo'), $('r70_descr'), {
          "sArquivo" : "func_rhlota.php",
          "sObjetoLookUp" : "db_iframe_rhlota",
          "sLabel" : "Pesquisar Lotação",
        });

        $('btnImprimir').onclick = function(){
            var descricaoListarServidores = '';

            if (empty($F('cbxArquivoImportado'))) {
                alert("Informe o arquivo importado.");
                return false;
            }

            if ($F('cbxListarServidores') == 1) {
                descricaoListarServidores = 'Somente com inconsistências.';
            } else if ($F('cbxListarServidores') == 2) {
                descricaoListarServidores = 'Somente sem inconsistências.';
            }
            
            var relatolrio = new EmissaoRelatorio(
              'eso3_qualificacaocadastral002.php',
              {
                'arquivo' : $F('cbxArquivoImportado'),
                'cargo' : $F('rh37_funcao'),
                'lotacao' : $F('r70_codigo'),
                'descricaoCargo' : $F('rh37_descr'),
                'descricaoLotacao' : $F('r70_descr'),
                'descricaoListarServidores' : descricaoListarServidores,
                'listaServidores' : $F('cbxListarServidores'),
              }
            );
            relatolrio.open();
        };
    </script>
</html>
