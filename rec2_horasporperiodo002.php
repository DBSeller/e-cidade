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

use ECidade\RecursosHumanos\RH\Relatorios\ApuracaoColaborador\HorasPorPeriodo;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

$parametros = db_utils::postMemory($_GET);
$instanciaClasse = null;
try {
    if (empty($parametros->selecao) && empty($parametros->matriculas) && empty($parametros->localTrabalho)) {
        throw new ParameterException('Seleção, Matrículas ou Local de Trabalho não informado.');
    }

    if (empty($parametros->dataInicial) && empty($parametros->dataFinal)) {
        throw new ParameterException('Período de datas não informado.');
    }

    $pdf = new \PDFDocument();
    $pdf->open();
        
    try {

        $instanciaClasse = new HorasPorPeriodo();

        if (!empty($parametros->selecao)) {
            $instanciaClasse->setSelecao($parametros->selecao);
        }

        if (!empty($parametros->matriculas)) {
            $instanciaClasse->setMatriculas(explode(',', $parametros->matriculas));
        }

        if (!empty($parametros->localTrabalho)) {
            $instanciaClasse->setLocalTrabalho($parametros->localTrabalho);
        }

        $instanciaClasse->setDataInicial(new DBDate($parametros->dataInicial));
        $instanciaClasse->setDataFinal(new DBDate($parametros->dataFinal));
        $instanciaClasse->setFiltroSelecionado($parametros->filtro);
        $instanciaClasse->setPdf($pdf);

        $instanciaClasse->imprimir();

    }catch (Exception $exception) {
        throw new Exception($exception);
    }

} catch (Exception $erro) {
     db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($erro->getMessage()));
}
