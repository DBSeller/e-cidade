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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$rotulo = new rotulocampo;
$rotulo->label('arquivo');

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>

<div class="container">

    <form enctype="multipart/form-data" method="post">
        <fieldset style="width: 500px;">
            <legend class="bold">Atualização do Ementário da Receita</legend>
            <table>
                <tr>
                    <td class="bold">
                        <label for="origem">
                            Origem:
                        </label>
                    </td>
                    <td>
                        <select name="origem" id="origem">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="bold">
                        <label for="db_arquivo">
                            <?php echo $Larquivo; ?>
                        </label>
                    </td>
                    <td>
                        <input type="file" name="arquivo" id="arquivo" size="50000">
                    </td>
                </tr>
            </table>
        </fieldset>
        <p>
            <input type="button" value="Processar" id="btn-processar" onclick="processar();"/>
        </p>
    </form>

</div>

<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  var input = {
    'origem': $('origem'),
    'arquivo': $('arquivo')
  };

  const RPC = 'con1_ementarioreceita.RPC.php';

  function getOrigem() {

    AjaxRequest.create(
      RPC,
      {'exec': 'getModelos', 'exercicio': 2018},
      function (retorno, erro) {

        retorno.modelos.forEach(
          function (modelo) {

            var newOption = document.createElement('option');
            newOption.value = modelo.id;
            newOption.innerHTML = modelo.nome;
            input.origem.appendChild(newOption);
          }
        );

      }
    ).execute();
  }

  function processar() {

    if (input.origem.value === "") {

      alert("Origem é de preenchimento obrigatório.");
      return false;
    }

    if (input.arquivo.value === "") {

      alert("Origem é de preenchimento obrigatório.");
      return false;
    }

    var ultimaAtualizacao = true;

    AjaxRequest.create(
      RPC,
      {
        exec: 'verificarImportacaoAntiga',
        modelo: input.origem.value,
        exercicio: 2018
      },
      function(retorno, error) {

        var sMsg = 'Foi identificado uma importação de arquivo anterior.\n\nDeseja sobreescrever a importação do arquivo existente? Todos os vínculo já criados serão perdidos.';

        if (retorno.possuiImportacao && !confirm(sMsg)) {
          ultimaAtualizacao = false;
          return false;
        }

      }
    ).asynchronous(false).execute();


    if (ultimaAtualizacao) {
      AjaxRequest.create(
        RPC,
        {'exec': 'importar', 'modelo': input.origem.value},
        function (retorno, erro) {

          alert(retorno.mensagem);
          if (!erro) {

            input.origem.value = '';
            input.arquivo.value = '';
          }

        }
      ).addFileInput(input.arquivo).setMessage('Enviando o arquivo, aguarde...').execute();
    }
  }

  getOrigem();
</script>