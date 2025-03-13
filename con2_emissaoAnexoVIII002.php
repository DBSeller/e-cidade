<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("model/relatorioContabil.model.php"));

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoVIII as FactoryAnexo;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout\AnexoVIII as Layout;

$oGet = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession('DB_anousu');

try {

    if (empty($oGet->periodo)) {
        throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoVIII.codigo_periodo_invalido'));
    }

    $instituicoes = InstituicaoRepository::getInstituicoes();
    $codigoInstituicoes = "";
    $virgula = "";

    foreach ($instituicoes as $instituicao) {
        $codigoInstituicoes .= $virgula . $instituicao->getCodigo();
        $virgula = ",";
    }

    $relatorio = FactoryAnexo::getInstance($iAnoSessao, $oGet->periodo);
    $relatorio->setInstituicoes($codigoInstituicoes);

    $layout = Layout::getInstance($iAnoSessao);

    $layout->setAnexo($relatorio);
    $layout->emitir();

} catch (Exception $e) {
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $e->getMessage());
}