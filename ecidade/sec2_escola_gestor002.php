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

use ECidade\Educacao\Escola\Relatorios\CorpoGestorRelatorio;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$aCalendarios = [];
foreach (explode(',', $oGet->calendarios) as $calendario) {
    $aCalendarios[] = db_stdClass::normalizeStringJsonEscapeString(trim($calendario));
}
$oGet->calendarios = $aCalendarios;

$aFiltros = [];
if ($oGet->chk_escolas_bairro == 'true') {
    $oGet->chk_escolas_bairro = 1;
    $aFiltros[] = "Bairro";
} else {
    $oGet->chk_escolas_bairro = 0;
}
if ($oGet->chk_diretor == 'true') {
    $oGet->chk_diretor = 1;
    $aFiltros[] = "Diretor";
} else {
    $oGet->chk_diretor = 0;
}
if ($oGet->chk_diretor_interino == 'true') {
    $oGet->chk_diretor_interino = 1;
    $aFiltros[] = " Diretor Interino";
} else {
    $oGet->chk_diretor_interino = 0;
}
if ($oGet->chk_diretor_adjunto == 'true') {
    $oGet->chk_diretor_adjunto = 1;
    $aFiltros[] = "Diretor Adjunto";
} else {
    $oGet->chk_diretor_adjunto = 0;
}
if ($oGet->chk_orientador == 'true') {
    $oGet->chk_orientador = 1;
    $aFiltros[] = "Orientador";
} else {
    $oGet->chk_orientador = 0;
}
if ($oGet->chk_alunos == 'true') {
    $oGet->chk_alunos = 1;
    $aFiltro[] = "Alunos";
} else {
    $oGet->chk_alunos = 0;
}

if ($oGet->chk_corpo_gestor_completo == 'true') {
    $oGet->chk_corpo_gestor_completo = 1;
    $aFiltros = ["Bairro", "Diretor"," Diretor Interino", "Diretor Adjunto", "Orientador", "Alunos"];
} else {
    $oGet->chk_corpo_gestor_completo = 0;
}

if ($oGet->chk_funcional == 'true') {
    $oGet->chk_funcional = 1;
    $aFiltros = ["Quadro Funcional Completo"];
} else {
    $oGet->chk_funcional = 0;
}

if ($oGet->chk_corpo_gestor_completo == 'true') {
    $oGet->chk_corpo_gestor_completo = 1;
} else {
    $oGet->chk_corpo_gestor_completo = 0;
}
$filtros_cabecalho = implode(', ', $aFiltros);
$corpoGestorRelatorio = new CorpoGestorRelatorio($oGet, $filtros_cabecalho);
$corpoGestorRelatorio->emitir();
