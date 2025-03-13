<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 07/02/18
 * Time: 09:03
 */
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = '';

try {

    switch ($oParam->exec) {

        case 'getVariaveis':

            unset($_SESSION["bases_folha"]);
            $oVariaveis         = new stdClass();
            $fs = array(
                'F001'     => "Salário Hora (F007/F008)",
                'F002'     => "Horas semanais",
                'F003'     => "Data de admissão",
                'F004'     => "Idade",
                'F005'     => "Dependentes IRRF",
                'F006'     => "Dependentes Sal. Família",
                'F006_clt' => 'Dependentes Sal. Família clt',
                'F007' => "Sal. base sem progressão",
                'F008' => "Horas mensais",
                'F009' => "Meses 13o. Salário",
                'F010' => "Salário Base com progressão",
                'F011' => "Salário hora",
                'F012' => "Anos trabalhados",
                'F013' => "Qtd. de triênios",
                'F014' => "Qtd. de progressões",
                'F015' => "% de progressão",
                'F019' => "Dias de Férias",
                'F020' => "Dias de abono no mês",
                'F021' => "Numero de dias p/ calc do FGTS no mes",
                'F022' => "Qtd. de quinquênios",
                'F023' => "Numero de dias de Adiantamento de férias",
                'F024' => " F024 - Meses para progressão ",
                'F025' => "Dias no mês",
                'F030' => "adrão base de previdência",
                'F031' => "Domingos no mês",
                'F032' => "Dias úteis do mês",
            );
            $aFs = array();
            foreach ($fs as $codigo => $descricao) {

                $oClasse = new \stdClass();
                $oClasse->codigo = $codigo;
                $oClasse->descricao = $descricao;
                $aFs[] = $oClasse;
            }
            if (empty($_SESSION["bases_folha"])) {

                $sqlBases  = " select  r08_codigo as codigo, r08_descr as descricao";
                $sqlBases .= "   from pessoal.bases ";
                $sqlBases .= "where r08_mesusu = " . DBPessoal::getMesFolha() . " and r08_anousu = " . DBPessoal::getAnoFolha();
                $sqlBases .= "  and r08_instit = " . $_SESSION["DB_instit"];
                $rsBases   = db_query($sqlBases);
                 $_SESSION["bases_folha"] = db_utils::makeCollectionFromRecord($rsBases, function($base) {
                     $base->descricao = trim($base->descricao);
                    return $base;
                });

            }
            $oVariaveis->bases         = (array_merge($_SESSION["bases_folha"], $aFs));
            $oRetorno->oListaVariaveis = $oVariaveis;
            break;
    }

} catch (Exception $oErro) {

    db_fim_transacao(true);
    $oRetorno->status = 2;
    $oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo JSON::create()->stringify($oRetorno);