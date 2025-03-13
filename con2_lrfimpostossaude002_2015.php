<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/PDFDocument.php");
require_once modification("fpdf151/assinatura.php");

$oGet = db_utils::postMemory($_GET);

try {

    if (empty($oGet->periodo)) {
        throw new Exception("Periodo não informado.");
    }

    $instituicoes = InstituicaoRepository::getInstituicoes();
    $codigoInstituicoes = "";
    $virgula = "";

    foreach ($instituicoes as $instituicao) {
        $codigoInstituicoes .= $virgula . $instituicao->getCodigo();
        $virgula = ",";
    }

    $oRelatorio = new AnexoXIIDemonstrativoDasDespesasComSaude(db_getsession("DB_anousu"),
      AnexoXIIDemonstrativoDasDespesasComSaude::CODIGO_RELATORIO, $oGet->periodo);
    $oRelatorio->setInstituicoes($codigoInstituicoes);

    $oRelatorio->emitir();

} catch (Exception $e) {
    db_redireciona("db_erros.php?db_erro=" . $e->getMessage());
}
