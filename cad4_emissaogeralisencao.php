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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

?>
<html>

<head>
  <?php
  db_app::load(array(
    "estilos.css",
    "prototype.js",
    "scripts.js",
    "strings.js",
    "DBLancador.widget.js",
    "DBAncora.widget.js",
    "DBLookUp.widget.js",
    "EmissaoRelatorio.js"
  ));
  ?>

<script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>

</head>

<body class="body-default">
  <div class="container">
    <fieldset>
      <legend>Emissão de certidão de isenção</legend>
      <table>
        <tr>
          <td>
            <label for="j45_tipo" class="for">
              <a id="tipoisen_ancora">Tipo de isenção:</a>
            </label>
          </td>
          <td>
            <input type="text" name="j45_tipo" id="j45_tipo">
            <input type="text" name="j45_descr" id="j45_descr">
          </td>
        </tr>
        <tr>
          <td  style="text-align: right">
            <label class="bold" for="ano">Ano:</label>
          </td>
          <td>
            <?php db_input('ano',4,1,true,'text',1,"","","","",4); ?>
          </td>
        </tr>
        <tr>
          <td colspan="6">
            <div style="margin-top: 20px;" id='ctnBairro'></div>
          </td>
        </tr>
        <tr> 
        <tr>
          <td colspan="6">
            <div style="margin-top: 20px;" id='ctnCondominio'></div>
          </td>
        </tr>
        <tr>     
        <tr>
          <td colspan="6">
            <div style="margin-top: 20px;" id='ctnTipoIsencao'> </div>
          </td>
        </tr>
      </table>
    </fieldset>

    <input type="button" name="emitir" id="emitir" value="Emitir" />
  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">

    var oTipoIsencao = $("tipoisen_ancora");
    var oTipo = $("j45_tipo");
    var oDescr = $("j45_descr");
    var oTipoIsencaoLookup = new DBLookUp(oTipoIsencao, oTipo, oDescr, {
      "sArquivo": "func_tipoisen.php",
      "sObjetoLookUp": "db_iframe",
      "sLabel": "Pesquisar"
    });

    var alturaGrid = "100px";

    /**
     * Cria o lançador para os tipos de isenção
     */
    function js_criarLancadorTipoIsencao() {
      oLancadorTipoIsencao = new DBLancador("oLancadorTipoIsencao");
      oLancadorTipoIsencao.setNomeInstancia("oLancadorTipoIsencao");
      oLancadorTipoIsencao.setLabelAncora("Tipos de Isenções: ");
      oLancadorTipoIsencao.setTextoFieldset("Filtro de Tipos de Isenções");
      oLancadorTipoIsencao.setParametrosPesquisa("func_tipoisen.php", ['j45_tipo', 'j45_descr']);
      oLancadorTipoIsencao.setGridHeight(alturaGrid);
      oLancadorTipoIsencao.setTituloJanela("Pesquisar Tipos");
      oLancadorTipoIsencao.show($("ctnTipoIsencao"));
    }

    js_criarLancadorTipoIsencao();

    $('adicionar_oLancadorTipoIsencao').addEventListener('click', function() {
      var j45_tipo = oTipo.value;
      var aListaTiposSelecionados = oLancadorTipoIsencao.getRegistros();
      var aTipoIsencoes = [];
      aListaTiposSelecionados.each(function(oValores, iIndice) {
      
        aTipoIsencoes.push(oValores.sCodigo);
        function encontrar() {
        for (let tipoIsencao of aTipoIsencoes) {
            if (tipoIsencao === j45_tipo) {
                alert('O tipo de isenção é o mesmo selecionado para a emissão da certidão');
                oLancadorTipoIsencao.removerRegistro(tipoIsencao);
            }
          }
        }
        encontrar();
      });
    });

    /**
     * Cria o lançador para os condomínios
     */
    function js_criarLancadorCondominios() {
      oLancadorCondominio = new DBLancador("oLancadorCondominio");
      oLancadorCondominio.setNomeInstancia("oLancadorCondominio");
      oLancadorCondominio.setLabelAncora("Condomínios: ");
      oLancadorCondominio.setTextoFieldset("Filtro de Condomínios");
      oLancadorCondominio.setParametrosPesquisa("func_condominio.php", ['j107_sequencial', 'j107_nome']);
      oLancadorCondominio.setGridHeight(alturaGrid);
      oLancadorCondominio.setTituloJanela("Pesquisar Condomínios");
      oLancadorCondominio.show($("ctnCondominio"));
    }

    js_criarLancadorCondominios();

    /**
     * Cria o lançador para os bairros
     */
    function js_criarLancadorBairros() {
      oLancadorBairro = new DBLancador("oLancadorBairro");
      oLancadorBairro.setNomeInstancia("oLancadorBairro");
      oLancadorBairro.setLabelAncora("Bairros: ");
      oLancadorBairro.setTextoFieldset("Filtro de Bairros");
      oLancadorBairro.setParametrosPesquisa("func_bairro.php", ['j13_codi', 'j13_descr']);
      oLancadorBairro.setGridHeight(alturaGrid);
      oLancadorBairro.setTituloJanela("Pesquisar Condomínios");
      oLancadorBairro.show($("ctnBairro"));
    }

    js_criarLancadorBairros();

    function js_verifica() {

      if (empty(j45_tipo.value)) {
        alert('Insira um tipo de isenção');
        return false;
      }

    
      if (ano.value <= 1950 || ano.value >= 2050) {
        alert('O ano inserido é inválido');
        return false;
      }

      return true;
    }

    $('emitir').addEventListener('click', function() {

      if (js_verifica() == true) {

        var aListaTiposSelecionados = oLancadorTipoIsencao.getRegistros();
        var aTipoIsencao = [];
        aListaTiposSelecionados.each(function(oValores, iIndice) {
        
          aTipoIsencao.push(oValores.sCodigo);
        });

        var aListaCondominios = oLancadorCondominio.getRegistros();
        var aCondominio = [];
        aListaCondominios.each(function(oValores1, iIndice1) {

          aCondominio.push(oValores1.sCodigo);
        });

        var aListaBairros = oLancadorBairro.getRegistros();
        var aBairro = [];
        aListaBairros.each(function(oValores2, iIndice2) {

          aBairro.push(oValores2.sCodigo);
        });
        
        var oParametros = {
          tipoIsencao: j45_tipo.value,
          ano: ano.value
        };

        if (aTipoIsencao.length > 0) {
          oParametros.tiposignorar = aTipoIsencao.join(',');  
        }
      
        if (aCondominio.length > 0) {
          oParametros.condominios = aCondominio.join(',');  
        }

        if (aBairro.length > 0) {
          oParametros.bairros = aBairro.join(',');  
        }
               
      new EmissaoRelatorio("cad4_emissaogeralisencao002.php", oParametros).open();
      
      }
    });
    
  </script>
</body>

</html>