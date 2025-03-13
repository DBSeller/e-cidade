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
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
    <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
    <script src="scripts/object.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/DBLookUp.widget.js" rel="script" type="text/javascript"></script>
</head>
<body>
<form action="sped02_preenchimento.php" class="container">
    <input type="hidden" value="1" name="integracao" id="integracao">
    <input type="hidden" value="27" name="formularioTipo" id="formularioTipo">
    <fieldset>
        <legend>Para alterar um preenchimento, selecione-o usando o filtro</legend>
        <table>
            <tr>
                <td><a id="ancoraProcesso" href="#">Prestação:</a></td>
                <td>
                    <input type="hidden" id="preenchimento" name="preenchimento" lang="db_efd05_avaliacaogruporesposta">
                    <input type="text" id="competencia" name="competencia" lang="efd05_competencia"
                           class="field-size2 readonly" readonly/>
                    <input type="text" id="inscricaoPrestadora" name="inscricaoPrestadora"
                           lang="efd05_inscricaoprestadora" class="field-size3 readonly" readonly/>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" value="Incluir" id="proximo" name="proximo">
</form>
</body>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
    const dbLookUp = new DBLookUp($('ancoraProcesso'), $('inscricaoPrestadora'), $('competencia'), {
        'sArquivo': 'func_efd_servicos_prestados.php',
        'sObjetoLookUp': 'db_iframe_efd_servicos_prestados',
        'sLabel': 'Pesquisar Serviços Prestados',
        'aCamposAdicionais': ['db_efd05_avaliacaogruporesposta'],
    });

    dbLookUp.setCallBack('onClick', (dados) => {
        $('preenchimento').value = '';
        if (dados[2] != '') {
            $('preenchimento').value = dados[2];
        }
        $('proximo').value = "Alterar"
    });

</script>
