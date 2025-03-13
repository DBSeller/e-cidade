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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

use \DadosProcessamentoCivitas;


$oRecadastramentoConsultaIptuHelper = new DadosProcessamentoCivitas($parametro, $schema);


// ----------------------------------  atual ------------------------------------------

$dadosLote        = $oRecadastramentoConsultaIptuHelper->getLote();
$aCaracLote       = $oRecadastramentoConsultaIptuHelper->getCarateristicasdoLote();
$oDadosCalculo    = $oRecadastramentoConsultaIptuHelper->getCaculo();
$oIptucalcpadrao  = $oRecadastramentoConsultaIptuHelper->getIptuPadraoValores();
$taxas            = $oRecadastramentoConsultaIptuHelper->getTaxas();
$rsConstrucoes    = $oRecadastramentoConsultaIptuHelper->getContrucao();
$aCaracteristicasImoveisAtual =  $oRecadastramentoConsultaIptuHelper->getCarateristicasdosImoveisDaMatricula();

$dadosTestada     = $oRecadastramentoConsultaIptuHelper->getEnderecoTestada();
$oRecadastramentoConsultaIptuHelper->getCalculoIptu();

// ----------------------------------- depois ----------------------- ------------------

$oRecadastramentoConsultaIptuHelper->defineSchema($schema);


$dadosLoteSchema       = $oRecadastramentoConsultaIptuHelper->getLote();
$aCaracLoteSchema      = $oRecadastramentoConsultaIptuHelper->getCarateristicasdoLote();
$oDadosCalculoSchema   = $oRecadastramentoConsultaIptuHelper->getCaculo();
$oIptucalcpadraoSchema = $oRecadastramentoConsultaIptuHelper->getIptuPadraoValores();
$taxasSchema           = $oRecadastramentoConsultaIptuHelper->getTaxas();
$rsConstrucoesSchema   = $oRecadastramentoConsultaIptuHelper->getContrucao();
$aCaracteristicasImoveisAtualSchema =  $oRecadastramentoConsultaIptuHelper->getCarateristicasdosImoveisDaMatricula();
$dadosTestadaSchema    = $oRecadastramentoConsultaIptuHelper->getEnderecoTestada();

$aMatriculaNoLote      = $oRecadastramentoConsultaIptuHelper->getMatriculaNoLote();

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <link  rel="stylesheet"  type="text/css" href="estilos/processamentoCivitas.css" />
</head>
<body>
<div class='subcontainer' style="width:100%">
    <div id="ctnAbas"></div>
    <div id='aba1'>
        <fieldset>
            <legend>Lote</legend>
            <table id="lote" width="95%" border="0" align="center" cellpadding="0" cellspacing="2" class="collapse-border">
                <thead>
                <tr>
                    <th align="center" class="bold field-size3">Descrição</th>
                    <th align="center" class="bold field-size9">Antes</th>
                    <th align="center" class="bold field-size9">Depois</th>
                </tr>
                </thead>
                <tbody>

                <tr align="center"  class="<?= ($dadosLote->setor != $dadosLoteSchema->setor ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Setor</b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= $dadosLote->setor ?> </td>
                    <td width="30%" align="left" nowrap> <?= $dadosLoteSchema->setor ?> </td>
                </tr>

                <tr align="center"  class="<?= ($dadosLote->quadra != $dadosLoteSchema->quadra ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Quadra</b>
                    </td>
                    <td width="30%" align="left" nowrap><?= $dadosLote->quadra ?></td>
                    <td width="30%" align="left" nowrap><?= $dadosLoteSchema->quadra ?></td>
                </tr>

                <tr align="center" class="<?= ($dadosLote->lote != $dadosLoteSchema->lote ? 'linha_alterada' : '') ?>">
                    <td align="left" nowrap>
                        <b>Lote </b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= $dadosLote->lote ?> </td>
                    <td width="30%" align="left" nowrap><?= $dadosLoteSchema->lote ?></td>
                </tr>

                <tr align="center"  class="<?= ($dadosLote->quadra_localizacao != $dadosLoteSchema->quadra_localizacao ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Quadra de localização </b>
                    </td>
                    <td width="30%" align="left" nowrap>  <?= $dadosLote->quadra_localizacao ?></td>
                    <td width="30%" align="left" nowrap> <?= $dadosLoteSchema->quadra_localizacao ?></td>
                </tr>

                <tr align="center" class="<?= ($dadosLote->lote_localizacao != $dadosLoteSchema->lote_localizacao ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Lote de localização</b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= $dadosLote->lote_localizacao ?></td>
                    <td width="30%" align="left" nowrap><?= $dadosLoteSchema->lote_localizacao ?></td>
                </tr>

                <tr align="center"  class="<?= ($dadosLote->endereco_completo != $dadosLoteSchema->endereco_completo ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Endereço</b>
                    </td>
                    </td>
                    <td  align="left" >
                        <?= !empty($dadosLote->endereco_completo) ? $dadosLote->endereco_completo : '' ?>
                    </td>
                    <td  align="left" > <?= !empty($dadosLoteSchema->endereco_completo) ? $dadosLoteSchema->endereco_completo : '' ?>  </td>
                </tr>

                <tr align="center">
                    <td align="left" nowrap>
                        <b>Endereço da testada </b>
                    </td>
                    <td width="30%" align="left" nowrap>  <?= !empty($dadosTestada->endereco_completo) ? $dadosTestada->endereco_completo : '' ?></td>
                    <td width="30%" align="left" nowrap>  <?= !empty($dadosTestadaSchema->endereco_completo) ? $dadosTestadaSchema->endereco_completo : '' ?></td>
                </tr>

                <tr align="center" class="<?= ($oDadosCalculo->j23_testad != $oDadosCalculoSchema->j23_testad ? 'linha_alterada' : '') ?>">
                    <td align="left" nowrap>
                        <b>Testada do cálculo</b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= $oDadosCalculo->j23_testad  ?></td>
                    <td width="30%" align="left" nowrap> <?= $oDadosCalculoSchema->j23_testad  ?></td>
                </tr>

                <tr align="center"  class="<?= ($oDadosCalculo->j23_arealo != $oDadosCalculoSchema->j23_arealo ? 'linha_alterada' : '') ?>">
                    <td align="left" nowrap>
                        <b>Area do lote para cálculo</b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= db_formatar($oDadosCalculo->j23_arealo, 'f')  ?></td>
                    <td width="28%" align="left" nowrap> <?= db_formatar($oDadosCalculoSchema->j23_arealo, 'f')  ?></td>
                </tr>
                <tr align="center" class="<?= ($dadosLote->area != $dadosLoteSchema->area ? 'linha_alterada' : '') ?>" >
                    <td align="left" nowrap>
                        <b>Area do lote</b>
                    </td>
                    <td width="30%" align="left" nowrap> <?= db_formatar($dadosLote->area, 'f')  ?></td>
                    <td width="28%" align="left" nowrap> <?= db_formatar($dadosLoteSchema->area, 'f')  ?></td>
                </tr>
                </tbody>
            </table>

            <fieldset class="separator">
                <legend> Características</legend>

                <table width="95%" border="0" align="center" cellpadding="1" cellspacing="2" class='caracteristicas'>
                    <thead>
                    <tr>
                        <th align="center" class="bold field-size2">Grupo</th>
                        <th align="center" class="bold field-size9" colspan="2">Antes</th>
                        <th align="center" class="bold field-size9" colspan="2">Depois</th>
                    </tr>
                    </thead>
                    <tbo
                    <?php

                    foreach ($aCaracLote as $i => $dadosCaracteristica) :

                        $estiloCaracteristica = '';

                        if (array_diff((array)$dadosCaracteristica, (array)$aCaracLoteSchema[$i]) || array_diff((array)$aCaracLoteSchema[$i], (array)$dadosCaracteristica)) {
                            $estiloCaracteristica = 'caracteristica_alterada';
                        }
                        ?>
                        <tr align="center" class="<?= $estiloCaracteristica ?>">
                            <td width="220" align="left" nowrap>

                                <?= substr($dadosCaracteristica->j32_descr, 0, 30) ?>
                            </td>
                            <td align="left" width="55">

                                <?=  substr($dadosCaracteristica->j31_descr, 0, 20);  ?>
                            </td>

                            <td width="10" align="center" nowrap>
                                <?= $dadosCaracteristica->j31_pontos ?>
                            </td>
                            <td align="left" width="55">
                                <?=  substr($aCaracLoteSchema[$i]->j31_descr, 0, 20);  ?>
                            </td>

                            <td width="10" align="center" nowrap>
                                <?= $aCaracLoteSchema[$i]->j31_pontos ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </fieldset>
        </fieldset>
    </div>
    <div id="aba2">
        <fieldset>
            <legend>Construções</legend>
            <table id="construcoes" width="95%" border="0" align="center" cellpadding="0" cellspacing="2" class="collapse-border">
                <thead>
                <tr>
                    <th align="center"  class="bold field-size3">Descrição</th>
                    <th align="center" class="bold field-size9">Antes</th>
                    <th align="center" class="bold field-size9">Depois</th>
                </tr>
                </thead>
                <tbody>

                <?php

                $totalLinhas = pg_num_rows($rsConstrucoes);

                for ($i = 0; $i < $totalLinhas; $i++) :

                    $dadosConstrucao  = db_utils::fieldsMemory($rsConstrucoes, $i);
                    $aCaracteristicasConstrucao  = $aCaracteristicasImoveisAtual[$dadosConstrucao->idcons];


                endfor;

                $totalLinhasDepois = pg_num_rows($rsConstrucoesSchema);

                for ($i = 0; $i < $totalLinhasDepois; $i++) :
                    $dadosConstrucaoDepois = db_utils::fieldsMemory($rsConstrucoesSchema, $i);
                    $aCaracteristicasConstrucaoSchema = $aCaracteristicasImoveisAtualSchema[$dadosConstrucaoDepois->idcons];
                endfor;
                ?>

                <tr align="center"  >
                    <td align="left"  class="field-size3" nowrap><b>Constru&ccedil;&atilde;o</b></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucao->idcons ?></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucaoDepois->idcons ?></td>
                </tr>

                <tr align="center"  class="<?= ( $dadosConstrucao->area !=$dadosConstrucaoDepois->area ? 'linha_alterada' : '') ?>"  >
                    <td align="left"  class="field-size3" nowrap><b>Área</b></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucao->area ?></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucaoDepois->area ?></td>
                </tr>

                <tr align="center" class="<?= ($dadosConstrucao->ano != $dadosConstrucaoDepois->ano ? 'linha_alterada' : '') ?>" >
                    <td align="left"  class="field-size3" nowrap><b>Ano Construção</b></td>
                    <td align="left" class="field-size9" nowrap> <?= $dadosConstrucao->ano ?></td>
                    <td align="left" class="field-size9" nowrap> <?= $dadosConstrucaoDepois->ano ?></td>
                </tr>

                <tr align="center" class="<?= ($dadosConstrucao->principal != $dadosConstrucaoDepois->principal ? 'linha_alterada' : '') ?>">
                    <td align="left"  class="field-size3" nowrap><b>Principal</b></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucao->principal ?> </td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucaoDepois->principal ?></td>
                </tr>

                <tr align="center">
                    <td align="left"  class="field-size3" nowrap><b>Demolida</b></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucao->data_demolicacao != '' ? 'Sim' : 'Não' ?></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucaoDepois->data_demolicacao != '' ? 'Sim' : 'Não' ?></td>
                </tr>

                <tr align="center" >
                    <td align="left"  class="field-size3" nowrap><b>Data da Demolicação</b></td>
                    <td align="left" class="field-size9" nowrap><?= db_formatar($dadosConstrucao->data_demolicacao, 'd') ?></td>
                    <td align="left" class="field-size9" nowrap><?= db_formatar($dadosConstrucaoDepois->data_demolicacao, 'd') ?></td>
                </tr>

                <tr align="center"  class="<?= ($dadosConstrucao->pavimentos != $dadosConstrucaoDepois->pavimentos ? 'linha_alterada' : '') ?>">
                    <td align="left"  class="field-size3" nowrap><b>Pavimentos</b></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucao->pavimentos ?></td>
                    <td align="left" class="field-size9" nowrap><?= $dadosConstrucaoDepois->pavimentos ?></td>
                </tr>

                <tr align="center">
                    <td align="left"  class="field-size3" nowrap><b>Endereço</b></td>
                    <td align="left" class="field-size9" nowrap> <?= !empty($dadosConstrucao->endereco_completo) ? $dadosConstrucao->endereco_completo : '' ?></td>
                    <td align="left" class="field-size9" nowrap> <?= !empty($dadosConstrucaoDepois->endereco_completo) ? trim($dadosConstrucaoDepois->endereco_completo) : '' ?></td>
                </tr>
                </tbody>
            </table>

            <fieldset class="separator">
                <legend>Características</legend>
                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class='caracteristicas'>
                    <thead>
                    <tr>
                        <th class="bold" align="center">Grupo</th>
                        <th class="bold" align="center" colspan="2">Antes</th>
                        <th class="bold" align="center" colspan="2">Depois</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $linhas = ( !empty($aCaracteristicasConstrucao) ? count($aCaracteristicasConstrucao) : count($aCaracteristicasConstrucaoSchema));
                    $caracteristicas = ( !empty($aCaracteristicasConstrucao) ? $aCaracteristicasConstrucao : $aCaracteristicasConstrucaoSchema);
                    foreach ($caracteristicas as $i => $aNaoUsar) :


                        $caracteristica = $aCaracteristicasConstrucao[$i];


                        $caracteristicaSchema  = !empty($aCaracteristicasConstrucaoSchema[$i]) ? $aCaracteristicasConstrucaoSchema[$i] : null;

                        $estiloCaracteristica  = '';
                        $grupoCaracteristica   = null;

                        if (!empty($caracteristicasImoveisAtual[$dadosConstrucao->idcons][$caracteristica->j32_grupo])) {
                            $grupoCaracteristica = $caracteristicasImoveisAtual[$dadosConstrucao->idcons][$caracteristica->j32_grupo];
                        }


                        if (array_diff((array)$caracteristica, (array)$aCaracteristicasConstrucaoSchema[$i]) || array_diff((array)$aCaracteristicasConstrucaoSchema[$i], (array)$caracteristica)) {
                            $estiloCaracteristica = 'caracteristica_alterada';
                        }

                        ?>

                        <tr class="<?=$estiloCaracteristica;?>">
                            <td nowrap class="field-size7"><?=  $caracteristica->j32_descr  ?></td>
                            <td nowrap align="left" class="field-size7"> <?=  substr($caracteristica->j31_descr, 0, 20) ?></td>
                            <td nowrap align="center"><?= $caracteristica->j31_pontos ?></td>


                            <td nowrap align="left" class="field-size7"><?= ( !empty($caracteristicaSchema->j31_descr) ? substr($caracteristicaSchema->j31_descr, 0, 20) : '' )?></td>
                            <td nowrap align="center"><?= (!empty($caracteristicaSchema->j31_descr) ? $caracteristicaSchema->j31_pontos : '' )?></td>
                        </tr>
                    <?php endforeach;  ?>

                    </tbody>
                </table>
            </fieldset>
        </fieldset>
    </div>
    <div id='aba3'>
        <fieldset>
            <legend>
                Cálculo
            </legend>
            <table id='dadosCalculo' width="95%" align="center" cellpadding="0" class="collapse-border">
                <thead>
                <tr>
                    <th align="center" nowrap class="field-size2">Descrição</th>
                    <th align="center" nowrap class="field-size5">Antes</th>
                    <th align="center" nowrap class="field-size5">Depois</th>
                </tr>
                </thead>
                <tbody>
                <tr class="<?= ($oDadosCalculo->j23_areafr != $oDadosCalculoSchema->j23_areafr ? 'linha_alterada' : '') ?>" >
                    <td nowrap class='field-size3 bold'>Fração de cálculo</td>
                    <td align="center" class="field-size9"><?= $oDadosCalculo->j23_areafr ?></td>
                    <td align="right" class="field-size9"><?= $oDadosCalculoSchema->j23_areafr ?> </td>
                </tr>
                <tr class="<?= ($oDadosCalculo->j23_m2terr != $oDadosCalculoSchema->j23_m2terr ? 'linha_alterada' : '') ?>">
                    <td nowrap class='field-size3 bold'>V0</td>
                    <td align="center" class="field-size9"><?= db_formatar($oDadosCalculo->j23_m2terr, 'f') ?></td>
                    <td align="right" class="field-size9"><?= db_formatar($oDadosCalculoSchema->j23_m2terr, 'f') ?> </td>
                </tr>
                <tr class="<?= ($oDadosCalculo->j23_vlrter!= $oDadosCalculoSchema->j23_vlrter ? 'linha_alterada' : '') ?>">
                    <td nowrap class='field-size3 bold'>Valor venal do terreno</td>
                    <td align="center" class="field-size9"><?= db_formatar($oDadosCalculo->j23_vlrter, 'f') ?></td>
                    <td align="right" class="field-size9"><?= db_formatar($oDadosCalculoSchema->j23_vlrter, 'f') ?></td>
                </tr>
                <tr class="<?= ($oDadosCalculo->j22_valor != $oDadosCalculoSchema->j22_valor ? 'linha_alterada' : '') ?>">
                    <td nowrap class='field-size3 bold'>Valor venal da construção</td>
                    <td align="center" class="field-size9"><?= db_formatar($oDadosCalculo->j22_valor, 'f') ?></td>
                    <td align="right" class="field-size9"><?=  db_formatar($oDadosCalculoSchema->j22_valor, 'f') ?> </td>
                </tr>
                <?php
                $valorVenal = ($oDadosCalculo->j23_vlrter + $oDadosCalculo->j22_valor);
                $valorVenalDepois = ($oDadosCalculoSchema->j23_vlrter + $oDadosCalculoSchema->j22_valor);
                ?>
                <tr class="<?= ($valorVenal != $valorVenalDepois ? 'linha_alterada' : '') ?>">
                    <td nowrap class='field-size3 bold'>Valor venal</td>
                    <td align="center" class="field-size9"><?= db_formatar(($oDadosCalculo->j23_vlrter + $oDadosCalculo->j22_valor), 'f') ?></td>
                    <td align="right" class="field-size9"><?= db_formatar(($oDadosCalculoSchema->j23_vlrter + $oDadosCalculoSchema->j22_valor), 'f') ?> </td>
                </tr>
                <?php
                $valorVenalF = $oIptucalcpadrao->j11_vlrcons + $oIptucalcpadrao->j10_vlrter;
                $valorVenalFDepois = $oIptucalcpadraoSchema->j11_vlrcons + $oIptucalcpadraoSchema->j10_vlrter;
                ?>
                <tr class="<?= ($valorVenalF != $valorVenalFDepois ? 'linha_alterada' : '') ?>">
                    <td nowrap class='field-size3 bold'>Valor venal forçado </td>
                    <td align="center" class="field-size9"><?= db_formatar($oIptucalcpadrao->j11_vlrcons + $oIptucalcpadrao->j10_vlrter, 'f') ?></td>
                    <td align="right" class="field-size9"><?= db_formatar($oIptucalcpadraoSchema->j11_vlrcons + $oIptucalcpadraoSchema->j10_vlrter, 'f') ?></td>
                </tr>
                <!---->
                <tr class="<?= ($oDadosCalculo->j23_aliq != $oDadosCalculoSchema->j23_aliq ? 'linha_alterada' : '') ?>" >
                    <td nowrap class='field-size3 bold'>Alíquota:</td>
                    <td align="center" class="field-size9"><?= db_formatar($oDadosCalculo->j23_aliq, 'f') ?></td>
                    <td align="right" class="field-size9"><?=  db_formatar($oDadosCalculoSchema->j23_aliq, 'f') ?> </td>
                </tr>
                </tbody>
            </table>
            <fieldset class="separator">
                <legend>Demonstrativo</legend>
                <table>
                    <theader>
                        <tr>
                            <th align="left"   class="bold field-size2"></th>
                            <th align="center" class="bold field-size5">Antes</th>
                            <th align="center" class="bold field-size5">Depois</th>
                        </tr>
                    </theader>
                    <tbody>
                    <tr>
                        <td class="field-size2"></td>
                        <td class="field-size5">
                            <table width="100%" border="0" cellspacing="2" cellpadding="0" class='tab_cinza'>
                                <thead>
                                <tr>
                                    <th width="9%" nowrap> Receita</th>
                                    <th width="40%" nowrap> Descrição</th>
                                    <th width="13%" nowrap> Valor Calculado</th>
                                    <th width="13%" nowrap> Valor Isenção</th>
                                    <th width="13%" nowrap> Isenção (%)</th>
                                    <th width="12%" nowrap> Saldo a pagar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $soma                 = 0;
                                $somacalc             = 0;
                                $somaisen             = 0;
                                $iPercentualIsencao   = 0;

                                for ($contador2 = 0; $contador2 < pg_numrows($taxas); $contador2++) :

                                    db_fieldsmemory($taxas, $contador2);

                                    $soma     = $soma + ($j21_valor - abs($j21_valorisen));
                                    $somacalc = $somacalc + $j21_valor;
                                    $somaisen = $somaisen + $j21_valorisen;
                                    $iPercentualIsencao = (100 * $j21_valorisen) / $j21_valor;
                                    ?>

                                    <tr>
                                        <td width="9%" nowrap>
                                            <?= $k02_codigo ?>
                                        </td>
                                        <td width="40%" nowrap>
                                            <?= substr($j17_descr, 0, 20) ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar($j21_valor, 'f') ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar($j21_valorisen, 'f') ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar(abs($iPercentualIsencao), 'f') ?>
                                        </td>
                                        <td width="16%" align="right" nowrap>
                                            <?= db_formatar(($j21_valor - abs($j21_valorisen)), 'f') ?>
                                        </td>
                                    </tr>
                                <?php endfor; ?>

                                <?php $iSomaPercentualIsencao = (100 * $somaisen) / $somacalc; ?>
                                <tr>
                                    <th colspan="2" align="right" nowrap>TOTAL
                                    </th>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar($somacalc, 'f') ?></strong>
                                    </td>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar($somaisen, 'f') ?></strong>
                                    </td>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar(abs($iSomaPercentualIsencao), 'f') ?></strong>
                                    </td>
                                    <td width="12%" align="right" nowrap>
                                        <strong><?= db_formatar($soma, 'f') ?></strong>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </td>
                        <td class="field-size5">
                            <table width="100%" border="0" cellspacing="2" cellpadding="0" class='tab_cinza'>
                                <thead>
                                <tr>
                                    <th width="9%" nowrap> Receita</th>
                                    <th width="40%" nowrap> Descrição</th>
                                    <th width="13%" nowrap> Valor Calculado</th>
                                    <th width="13%" nowrap> Valor Isenção</th>
                                    <th width="13%" nowrap> Isenção (%)</th>
                                    <th width="12%" nowrap> Saldo a pagar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $soma                 = 0;
                                $somacalc             = 0;
                                $somaisen             = 0;
                                $iPercentualIsencao   = 0;
                                for ($contador2 = 0; $contador2 < pg_numrows($taxasSchema); $contador2++) :

                                    db_fieldsmemory($taxasSchema, $contador2);

                                    $soma     = $soma + ($j21_valor - abs($j21_valorisen));
                                    $somacalc = $somacalc + $j21_valor;
                                    $somaisen = $somaisen + $j21_valorisen;
                                    $iPercentualIsencao = (100 * $j21_valorisen) / $j21_valor;

                                    ?>
                                    <tr>
                                        <td width="9%" nowrap>
                                            <?= $k02_codigo ?>
                                        </td>
                                        <td width="40%" nowrap>
                                            <?= substr($j17_descr, 0, 20) ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar($j21_valor, 'f') ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar($j21_valorisen, 'f') ?>
                                        </td>
                                        <td width="7%" align="right" nowrap>
                                            <?= db_formatar(abs($iPercentualIsencao), 'f') ?>
                                        </td>
                                        <td width="16%" align="right" nowrap>
                                            <?= db_formatar(($j21_valor - abs($j21_valorisen)), 'f') ?>
                                        </td>
                                    </tr>
                                <?php  endfor;  ?>
                                <tr>
                                    <th colspan="2" align="right" nowrap>TOTAL
                                    </th>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar($somacalc, 'f') ?></strong>
                                    </td>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar($somaisen, 'f') ?></strong>
                                    </td>
                                    <td width="13%" align="right" nowrap>
                                        <strong><?= db_formatar(abs($iSomaPercentualIsencao), 'f') ?></strong>
                                    </td>
                                    <td width="12%" align="right" nowrap>
                                        <strong><?= db_formatar($soma, 'f') ?></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
        </fieldset>
    </div>

    <?php

    if (!empty($aMatriculasNoLote) ) {

        $temMatriculaAlterada = false;
        $outrasMatriculas = array();
        foreach ($aMatriculasNoLote as $oMatricula) {

            if ($oMatricula->situacao == \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Processamento::MATRICULA_NOVA) {
                $temMatriculaAlterada = true;
            }
            if ($oMatricula->matricula != $parametro) {
                $outrasMatriculas[] = $oMatricula->matricula;
            }
        }

        if ($temMatriculaAlterada) {

            $sMensagemMatricula = "<br><div class='aviso' >Esse lote possui uma nova matrícula pendente de análise. Poderão ocorrer alterações na ";
            $sMensagemMatricula .= " fração das demais matrículas do lote. <br> Outras Matrículas do lote: " . implode(", ",
                    $outrasMatriculas) . "</div>";
            echo $sMensagemMatricula;
        }
    }
    ?>

</div>
</body>
</html>
<script>
    var oAbas = new DBAbas($('ctnAbas'));
    var oAba1 = oAbas.adicionarAba('Lote', $('aba1'));
    var oAba2 = oAbas.adicionarAba('Construções', $('aba2'));
    var oAba3 = oAbas.adicionarAba('Cálculo', $('aba3'));
</script>