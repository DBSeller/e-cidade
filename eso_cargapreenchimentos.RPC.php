<?php
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/JSON.php');
require_once modification('fpdf151/pdf.php');

use ECidade\RecursosHumanos\ESocial\Factory\Relatorio as RelatorioFactory;
use ECidade\Integracao\Sped\Common\Configuracao\ConfiguracaoFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\FormularioRepository;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;


$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

try {
    db_inicio_transacao();
    ini_set('memory_limit', '-1');

    switch ($oParam->exec) {
        case "gerarCargaAdiantamento":
            $oRetorno->sMessage = "Rotina desabilitada.";
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}


function removeHtmlContent($text, $tags = '', $invert = false)
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) && count($tags) > 0) {
        if (!$invert) {
            return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
        }
    } elseif (!$invert) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
}

function geraCabecalho(&$pdf, $colunas) {
    $pdf->setfillcolor(235);
    $pdf->setfont('arial', 'B', 8);
    $pdf->cell($colunas['rubrica'], 5, "Código Rubrica", 1, 0, 'C', 1);
    $pdf->cell($colunas['inss'], 5, "INSS", 1, 0, 'C', 1);
    $pdf->cell($colunas['irrf'], 5, "IRRF", 1, 0, 'C', 1);
    $pdf->cell($colunas['fgts'], 5, "FGTS", 1, 1, 'C', 1);
    $pdf->setfont('arial', '', 6);
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);
