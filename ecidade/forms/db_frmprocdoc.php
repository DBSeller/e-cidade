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

//MODULO: protocolo
$clprocdoc->rotulo->label();
?>
  <form name="form1" method="post" action="">
    <table class="form-container">
      <tr>
        <td>
          <fieldset>
            <legend><b>Cadastro de Documentos</b></legend>
            <table border="0">
              <tr style="display: none;">
                <td nowrap title="<?= $Tp56_coddoc ?>">
                    <?= $Lp56_coddoc ?>
                </td>
                <td>
                    <?php
                    db_input('p56_coddoc', 3, $Ip56_coddoc, true, 'text', 3, "")
                    ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?= $Tp56_descr ?>">
                    <?= $Lp56_descr ?>
                </td>
                <td>
                    <?php
                    db_input('p56_descr', 50, $Ip56_descr, true, 'text', $db_opcao, "")
                    ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <fieldset class="separator" id="fieldSistemasExternos">
                    <legend>Sistemas Externos</legend>

                    <table>
                      <tr>
                        <td>
                          <label for="p56_ouvidoriatipodado">
                            Tipo de Dado:
                          </label>
                        </td>
                        <td>
                            <?php
                            $opcoes = array(
                              '' => 'Selecione...',
                              1 => 'TEXTO LIVRE',
                              2 => 'NUMÉRICO',
                              3 => 'VALOR',
                              4 => 'ENDEREÇO',
                              5 => 'ARQUIVO'
                            );
                            db_select('p56_ouvidoriatipodado', $opcoes, true, $db_opcao);
                            ?>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
    <input name="db_opcao" type="submit" id="db_opcao"
           value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  </form>
  <script>
    document.form1.p56_descr.focus();
  </script>
  <script>
    new DBToogle('fieldSistemasExternos', false);

    function js_pesquisa() {
      js_OpenJanelaIframe('', 'db_iframe', 'func_procdoc.php?funcao_js=parent.js_preenchepesquisa|0', 'Pesquisa Documentos', true);
    }

    function js_preenchepesquisa(chave) {
      db_iframe.hide();
      location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>' + "?chavepesquisa=" + chave;
    }

    $('p56_coddoc').addClassName('field-size2');
    $('p56_descr').addClassName('field-size7');
  </script>
<?php
if ($db_opcao == 22 || $db_opcao == 33) {
    ?>
  <script>
    onLoad = js_pesquisa();
  </script>
    <?php
}
