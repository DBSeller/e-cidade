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

include(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_regenciahorario_classe.php"));
include(modification("classes/db_periodoescola_classe.php"));
include(modification("classes/db_escola_classe.php"));
include(modification("classes/db_turma_classe.php"));
include(modification("classes/db_turmaturnoadicional_classe.php"));
include(modification("classes/db_diasemana_classe.php"));
require_once(modification("model/educacao/QuadroGeralHorario.model.php"));
ini_set('memory_limit', '-1');
set_time_limit(0);
$parametros = $HTTP_SERVER_VARS["QUERY_STRING"];
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$anoAtual = db_getsession("DB_anousu");
$pCorQuadro = QuadroGeralHorarioRepository::getParamQuadroHorarios();
if (isset($iEtapa) && !empty($iEtapa)) {
    $iEtapa = str_replace(",", "-", $iEtapa);
}
if (isset($iTurno) && !empty($iTurno)) {
    $iTurno = str_replace(",", "-", $iTurno);
}


$arrQuadroGeralHorarios = QuadroGeralHorarioRepository::getDadosQuadroGeralHorarios(
    $anoAtual,
    $iEscola,
    $iDisciplina,
    $iEtapa,
    $iDia,
    $iTurno,
    $iVinculo,
    $iPeriodo,
    $iFuncionario
);

foreach ($arrQuadroGeralHorarios as $dados) {
    $codEscola = $dados->codEscola;
    $nomEscola = $dados->nomEscola;
    $codTurma  = $dados->codTurma;
    $dscTurma  = $dados->dscTurma;
    $codTurno  = $dados->codTurno;
    $dscTurno  = $dados->dscTurno;

    if(!isset($arrEscolas) || !in_array($codEscola, $arrEscolas)){
        $dtEscola = new stdClass();
        $dtEscola->codEscola = $codEscola;
        $dtEscola->nomEscola = $nomEscola;
        $arrEscolas[$codEscola] = $dtEscola;
    }
    
    $dtTurmas = new stdClass();
    $dtTurmas->codTurma = $codTurma;
    $dtTurmas->dscTurma = $dscTurma;
    $dtTurmas->dscTurno = $dscTurno;
    $arrTurmas[$codEscola][$codTurma] = $dtTurmas;

    $diasemana = $dados->codigo_diasemana;
    $dsc_semana = $dados->descr_semana;
    $periodo = $dados->codigo_periodo;
    $dsc_periodo = $dados->descricao_periodo;
    $cDscDisciplina = $dados->codigo_disciplina;
    $dDscDisciplina = $dados->disciplina;
    $cod_regente = $dados->codigo_regente;
    $z01_nome = $dados->z01_nome;
    $matricula = $dados->matricula;
    $cod_tipohora = $dados->codigo_tipohora;
    $abr_tipohora = $dados->abreviatura_tipohora;
    $ausente_hoje = $dados->ausente_hoje;
    $ausencia_inicio = $dados->ausencia_inicio;
    $ausencia_final = $dados->ausencia_final;
    $substituto = $dados->substituto;
    $substituto_inicio = $dados->subtituto_inicio;
    $substituto_final = $dados->subtituto_final;
    $corhtml = $dados->corhtml;

    $dtGradeTurmas = new stdClass();
    $dtGradeTurmas->cDscDisciplina = $cDscDisciplina;
    $dtGradeTurmas->dDscDisciplina = $dDscDisciplina;
    $dtGradeTurmas->cod_regente = $cod_regente;
    $dtGradeTurmas->z01_nome = $z01_nome;
    $dtGradeTurmas->matricula = $matricula;
    $dtGradeTurmas->cod_tipohora = $cod_tipohora;
    $dtGradeTurmas->abr_tipohora = $abr_tipohora;
    $dtGradeTurmas->ausente_hoje = $ausente_hoje;
    $dtGradeTurmas->ausencia_inicio = $ausencia_inicio;
    $dtGradeTurmas->ausencia_final = $ausencia_final;
    $dtGradeTurmas->substituto = $substituto;
    $dtGradeTurmas->substituto_inicio = $substituto_inicio;
    $dtGradeTurmas->substituto_final = $substituto_final;
    $dtGradeTurmas->corhtml = $corhtml;
    $arrGradeTurmas[$codEscola][$diasemana][$periodo][$codTurma] = $dtGradeTurmas;
    
    $dtGradeSemana = new stdClass();
    $dtGradeSemana->diasemana = $diasemana;
    $dtGradeSemana->dsc_semana = $dsc_semana;
    $arrGradeSemana[$codEscola][$diasemana] = $dtGradeSemana;

    $arrDiaSemana[] = $diasemana;

    $dtTempo = new stdClass();
    $dtTempo->periodo = $periodo;
    $dtTempo->dsc_periodo = $dsc_periodo;
    $arrTempo[$codEscola][$periodo] = $dtTempo;
}

$arrTurmas = array_unique($arrTurmas, SORT_REGULAR);
$arrDiaSemana = array_unique($arrDiaSemana, SORT_REGULAR);
natsort($arrDiaSemana);

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.css">
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js">
    </script>
    <?php
        db_app::load("scripts.js, strings.js, arrays.js");
        db_app::load("estilos.css");
    ?>
    <style>
        .regente {
            font-size: 10px;
            font-style: italic;
        }

        .ausencia {
            font-size: 10px;
            color: darkorange;
        }

        .table-fixed-scroll {
             overflow-x: auto;
             height: 300px;
        }

        .tableFixHead          { overflow-x: scroll; height: auto; max-height: 600px;}
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1;}
        .tableFixHead tbody th { position: sticky; left: 0;}

        table  { border-collapse: collapse; width: 100%; border: 1px solid #ddd;}
        th, td { padding: 8px 16px; text-align: center; border: 1px solid #ddd;}
        th     { background:#eee; text-align: center; white-space:nowrap; border: 1px solid #ddd;}

    </style>
</head>
<body class="body-default">
<form name="form1">
<?php
foreach ($arrEscolas as $dtEscola) {
    $codEscola = $dtEscola->codEscola;
    $nomEscola = $dtEscola->nomEscola;
    $tableDiv = "table".$codEscola;
    $tableIcon = "icon".$codEscola;

    ?>
    <legend onclick="jsExpand(<?=$codEscola?>);">
        <?php echo $codEscola." - ".$nomEscola; ?>
        <i id="<?=$tableIcon?>" class="bi bi-caret-right-fill"></i>
    </legend>
    <div class="tableFixHead" id="<?=$tableDiv?>" style="display: none;">
    <table>
    <thead>
    <tr>
    <th style='width: 40px'><i class='fas fa-calendar-alt'>
        </i> Dias / <i class='far fa-clock'></i> Horários
    </th>
    <?php
    foreach ($arrTurmas[$codEscola] as $dtTurma) {
        $codTurma = $dtTurma->codTurma;
        $dscTurma = $dtTurma->dscTurma;
        $dscTurno = $dtTurma->dscTurno;
        ?> <th><i class='fas fa-users'></i> <?php echo $dscTurma, ' - ',  $dscTurno; ?></th> <?php
    }
    ?>
    </tr></thead>
    <tbody>
    <?php
    ksort($arrGradeTurmas);
    foreach ($arrDiaSemana as $semana) {
        $codDiaSemana = $arrGradeSemana[$codEscola][$semana]->diasemana;
        $dscDiaSemana = $arrGradeSemana[$codEscola][$semana]->dsc_semana;

        foreach ($arrTempo[$codEscola] as $diaTempo) {
            $codTempo = $diaTempo->periodo;
            $dscTempo = $diaTempo->dsc_periodo;
            ?>
            <tr><th><?php echo $dscDiaSemana; ?></br /><?php echo $dscTempo ?></th>
            <?php
            foreach ($arrTurmas[$codEscola] as $dtTurma) {
                $codTurma2 = $dtTurma->codTurma;
                $dscTurma2 = $dtTurma->dscTurma;

                if (isset($arrGradeTurmas[$codEscola][$codDiaSemana][$codTempo][$codTurma2])) {
                    $dtTurma = $arrGradeTurmas[$codEscola][$codDiaSemana][$codTempo][$codTurma2];

                    $dCodDisciplina     = $dtTurma->cDscDisciplina;
                    $dDscDisciplina     = $dtTurma->dDscDisciplina;
                    $dCodRegente        = $dtTurma->cod_regente;
                    $dRegente           = $dtTurma->z01_nome;
                    $dMatricula         = $dtTurma->matricula;
                    $dCodTipoHora       = $dtTurma->cod_tipohora;
                    $dAbrTipoHora       = $dtTurma->abr_tipohora;
                    $dAusenteHoje       = $dtTurma->ausente_hoje;
                    $dAusenciaInicio    = $dtTurma->ausencia_inicio;
                    $dAusenciaFinal     = $dtTurma->ausencia_final;
                    $dSubstituto        = $dtTurma->substituto;
                    $dSubstitutoInicio  = $dtTurma->substituto_inicio;
                    $dSubstitutoFinal   = $dtTurma->substituto_final;
                    $dCorHtml           = $dtTurma->corhtml;

                    $tipoDeHora = "";

                    if ($dAbrTipoHora == "H") {
                        $tipoDeHora = "<br><b style='color: #2b669a'>HN</b>";
                    } else {
                        $tipoDeHora = "<br><b style='color: #2b669a'>{$dAbrTipoHora}</b>";
                    }

                    $sAusencia = '';
                    $sAusenciaMarcador = '';

                    if ($dAusenteHoje == "t") {
                        if ($dSubstituto == "t") {
                            $sAusenciaFinal = empty($dAusenciaFinal) ? 'em aberto' : $dAusenciaFinal;
                            $sSubstitutoFinal = empty($dSubstitutoFinal) ? 'em aberto' : $dSubstitutoFinal;

                            $sAusencia  = "Professor Ausente ({$dAusenciaInicio} - {$dAusenciaFinal}) \n";
                            $sAusencia .= "Substituto: {$dSubstituto} ({$dSubstitutoInicio} - {$dSubstitutoFinal})";

                            $sAusenciaMarcador = "<i class='fas fa-info-circle ausencia'></i>";
                        } else {
                            $sAusenciaFinal = empty($dAusenciaFinal) ? 'em aberto' : $dAusenciaFinal;
                            $sAusencia = "Professor Ausente ({$dAusenciaInicio} - {$dAusenciaFinal})";
                            $sAusenciaMarcador = "<i style='' class='fas fa-info-circle ausencia'></i>";
                        }
                    }

                    if (($dCodTipoHora != $iVinculo && $iVinculo != 0)
                        || ($dCodRegente != $iFuncionario
                            && $iFuncionario != 0
                            && $iFuncionario != ''
                            && $iFuncionario != 1)
                        || ($iVinculo == 0
                            && $iFuncionario == 1
                            && $dRegente != '')
                        || ($dDscDisciplina != $iDisciplina
                            && $iDisciplina != ''
                            && $iDisciplina != 0
                        )
                    ) {
                        $dTurma  = "<td>";
                        $dTurma .= "<i class='far fa-calendar-alt' style='color: #0000CC; font-size: 12px'></i>";
                        $dTurma .= "<br><b style='color: #0000CC; font-size: 10px;'>HORÁRIO PREENCHIDO</b>";
                        $dTurma .= "</td>";
                    }

                    if ($dRegente == "") {
                        $dTurma  = "<td style='background: yellow'>";
                        $dTurma .= "<b style='font-size: 10px;'>";
                        $dTurma .= $dDscDisciplina;
                        $dTurma .= "</b> <br>";
                        $dTurma .= "<i style='color: red;font-size: 10px'>";
                        $dTurma .= "DISCIPLINA SEM REGENTE";
                        $dTurma .= "</i>";
                    } else {
                        $dTurma  = $pCorQuadro == 0 ? "<td style='background: #ffffff'>" : "<td style='background:".$dCorHtml."'>";
                        $dTurma .= $pCorQuadro == 0 ? "<b style='font-size: 10px; color: ".$dCorHtml."'>" : "<b style='font-size: 10px;'>";
                        $dTurma .= $dDscDisciplina;
                        $dTurma .= "</b> <br>";
                        $dTurma .= $pCorQuadro == 0 ? "<span class='regente' style='color: ".$dCorHtml."' title='{$sAusencia}'>" : "<span class='regente' title='{$sAusencia}'>";
                        $dTurma .= "{$dRegente} {$sAusenciaMarcador}";
                        $dTurma .= "<br>";
                        $dTurma .= "<b>MATR.({$dMatricula})</b>";
                        $dTurma .= "</span>";
                        $dTurma .= $tipoDeHora;
                    }
                    $dTurma .= "</td>";

                } else {
                    $dTurma  = "<td style='background: #ffffff'>";
                    $dTurma .= "<i class='fas fa-check' style='color: #00de00'></i>";
                    $dTurma .= "<br><b style='color: #00de00'>HORÁRIO DISPONÍVEL</b>";
                    $dTurma .= "</td>";
                }
                echo $dTurma;
            }
            ?></tr><?php
        }
    }
    ?>
    </tbody>
    </table>
    </div>
    </br>
    <?php
}
?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script>
function jsExpand(value){
    var nomeTabela = 'table'+value.toString();
    var iconTabela = 'icon'+value.toString();

    var x = document.getElementById(nomeTabela);
    var y = document.getElementById(iconTabela);

    if (x.style.display === "none") {
        x.style.display = "block";
        y.classList.remove('bi-caret-right-fill');
        y.classList.add('bi-caret-down-fill');
    } else {
        x.style.display = "none";
        y.classList.remove('bi-caret-down-fill');
        y.classList.add('bi-caret-right-fill');
    }
}

</script>
</form>
</body>
</html>
