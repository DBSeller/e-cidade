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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$idPais = $_GET['id_pais'];
$idEstado = $_GET['id_estado'];
$descricao = $_GET['input-municipio'];
$funcaoJs = $_GET['funcao_js'];

$sWhere = " AND municipio.db72_descricao <> ' ' ";

if ($descricao) {
    $sWhere = "AND municipio.db72_descricao ilike '%{$descricao}%'";
}

$sSql = "
    SELECT
        db72_sequencial,
        db72_descricao,
        db71_sigla
    FROM
        cadenderpais pais
    INNER JOIN cadenderestado estado
        ON estado.db71_cadenderpais = pais.db70_sequencial
    INNER JOIN cadendermunicipio municipio
        ON municipio.db72_cadenderestado = estado.db71_sequencial
    INNER JOIN cadendermunicipiosistema
        ON municipio.db72_sequencial = cadendermunicipiosistema.db125_cadendermunicipio
       AND cadendermunicipiosistema.db125_db_sistemaexterno = 4
      WHERE
        pais.db70_sequencial = {$idPais}
        AND estado.db71_sequencial = {$idEstado}
        {$sWhere}
";

$oPostgresResource = db_query($sSql);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <form method="GET" action="" style="margin-top:50px;">
        <input type="hidden" name="id_pais" value="<?php echo $idPais ?>" />
        <input type="hidden" name="id_estado" value="<?php echo $idEstado ?>" />
        <input type="hidden" name="funcao_js" value="<?php echo $funcao_js ?>" />

        <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <label for="input-municipio"><b>Descrição Município:</b></label>
                            </td>
                            <td>
                                <input type="text" id="input-municipio" name="input-municipio" class="input" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding-top: 10px;">
                    <input type="submit" value="Pesquisar">
                </td>
            </tr>
        </table>
    </form>
    <div style="display:table; margin: 0 auto;">
        <span>
            <?php db_lovrot($sSql, 15,'()', '', $funcao_js, '', 'NoMe'); ?>
        </span>
    </div>
</body>
</html>