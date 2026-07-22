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


if (!function_exists('getDriveSpreadsheets')) {

    /**
     * Realiza o download de uma planilha no drive
     *
     * @param $key
     * @param $gid
     * @param string|null $saveAs Nome do arquivo salvo no sistema
     * @return string
     * @example como descobrir a KEY e o GID
     * https://docs.google.com/spreadsheets/d/<KEY>/export?gid=<GID>&format=csv
     * where <KEY> and <GID> can be obtained from your navigation's URL,
     * https://docs.google.com/spreadsheets/d/<KEY>/edit#gid=<GID>
     */
    function getDriveSpreadsheets($key, $gid, $saveAs = null)
    {
        $urlDrive = 'https://docs.google.com/feeds/download/spreadsheets/Export?key=<key>&exportFormat=xlsx&gid=<gid>';
        $urlDrive = str_replace(['<key>', '<gid>'], [$key, $gid], $urlDrive);

        if (is_null($saveAs)) {
            $saveAs = time() . ".xlsx";
        }

        $array = explode('.', $saveAs);
        if (array_pop($array) !== 'xlsx') {
            return false;
        }

        $tmpFile = sprintf('tmp/%s', $saveAs);

        file_put_contents($tmpFile, file_get_contents($urlDrive));
        return $tmpFile;
    }
}
