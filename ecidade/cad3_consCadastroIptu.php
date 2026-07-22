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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('j01_datacad');
$clrotulo->label('j01_baixa');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j40_refant');
$clrotulo->label('j40_registrocartografico');
$clrotulo->label('proprietario');
$clrotulo->label('z01_numimob');
$clrotulo->label('j34_zona');
$clrotulo->label('j91_codigo');
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');
$clrotulo->label('j34_lote');

db_sel_instit(db_getsession("DB_instit"), "db21_codcli");

$where = " EXISTS(SELECT 1 FROM iptubase WHERE iptubase.j01_matric = proprietario.j01_matric AND j01_tipoimovel = 1) ";

if (@$cod_matricula != "") {
	$where .= " AND j01_matric = $cod_matricula ";
} elseif (@$cod_matricularegimo != "") {
	$where .= " AND j04_matricregimo  = $cod_matricularegimo ";
}

$areaconst1   = 0;
$oDaoIptubase = db_utils::getDao('iptubase');
$rsIptubase   = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_proprietariolote($where));

if ($oDaoIptubase->numrows > 0) {
	$oDadosMatricula = db_utils::fieldsMemory($rsIptubase, 0, null);
} else {
	db_redireciona("db_erros.php?db_erro=Matrícula não cadastrada.");
}

$rsAreaTotal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_total($oDadosMatricula->j34_setor,
$oDadosMatricula->j34_quadra,
$oDadosMatricula->j34_lote));
$nAreaTotal  = db_utils::fieldsMemory($rsAreaTotal, 0)->area_total;
//$oDadosMatricula->area_matric = $nAreaTotal; // (M22952)

$rsAreaConstruida = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_contruida($oDadosMatricula->j01_matric));
$nAreaConstruida  = db_utils::fieldsMemory($rsAreaConstruida, 0)->area_construida;

$rsAreaConstruidaLote = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_contruida_lote($oDadosMatricula->j01_idbql));
$oDadosMatricula->j34_totcon = db_utils::fieldsMemory($rsAreaConstruidaLote, 0)->area_construidalote;
$rsImobiliaria = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_imobiliaria($oDadosMatricula->j01_matric, 'z01_nome'));

$lImobiliaria  = false;
if($oDaoIptubase->numrows > 0) {
	$lImobiliaria = true;
	$imobiliaria  = db_utils::fieldsMemory($rsImobiliaria, 0)->z01_nome;
}
$rsSetorFiscal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_setorfiscal($oDadosMatricula->j01_matric));
if($oDaoIptubase->numrows > 0) {
	$oSetorFiscal = db_utils::fieldsMemory($rsSetorFiscal, 0);
}

$oDaoCfIptu = db_utils::getDao('cfiptu');
$rsCfIptu   = $oDaoCfIptu->sql_record($oDaoCfIptu->sql_query_file(null, "j18_utilizaloc", "", "j18_anousu = ". db_getsession("DB_anousu")));
$lUtilizaLoc = db_utils::fieldsMemory($rsCfIptu, 0)->j18_utilizaloc == 't' ? true : false;

$sLoteloc = '';
if($lUtilizaLoc) {
	$sLoteloc = $oDadosMatricula->j05_codigoproprio .' - '. $oDadosMatricula->j05_descr . '-' . $oDadosMatricula->j06_quadraloc . '/' . $oDadosMatricula->j06_lote;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"
	src="scripts/prototype.js"></script>
<style>
.valores {
	background-color: #FFFFFF;
	padding-left: 10px;
}
</style>
</head>

<body>
	<fieldset>
		<legend>
			<b>Dados Cadastrais do Imóvel (<?=@$oDadosMatricula->j01_tipoimp?>) </b>
			<?php
			if(!empty($oDadosMatricula->j01_baixa)) {
                $sDataBaixa = db_formatar($oDadosMatricula->j01_baixa, "d");
				echo "<span class='aviso'>
                <font color='red'><b>Matrícula Baixada em {$sDataBaixa}</b></font>
              </span>";
			}
			?>
		</legend>
		<table>
			<tr>
				<td title="<?=$Tj01_matric?>" style="width: 120px;"><?=$Lj01_matric?>
				</td>
				<td title="<?=$Tj01_matric?>" nowrap class='valores'
					style="width: 300px;"><?=$oDadosMatricula->j01_matric?>
				</td>
				<td style="width: 10px;"></td>
				<td title="<?=$Tj40_refant?>" style="width: 110px;"><?=$Lj40_refant?>
				</td>
				<td title="<?=$Tj40_refant?>" nowrap class='valores'
					style="width: 300px;"><?=$oDadosMatricula->j40_refant?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tz01_nome?>"><?php db_ancora($Lz01_nome, "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_cgmpri')", 2); ?>
				</td>
				<td title="<?=$Tz01_nome?>" nowrap class='valores'><?=$oDadosMatricula->z01_nome?>
				</td>
				<td></td>
				<td title="Proprietário"><b><?php db_ancora('Proprietário',"js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numcgm')",2); ?>
				</b>
				</td>
				<td title="Proprietário" nowrap class='valores'><?=$oDadosMatricula->proprietario?>
				</td>
			</tr>
			<tr>
				<td title="">
				  <strong>
    				<?
    				if($lImobiliaria) {
    					db_ancora("Imobiliária:", "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numimob')", 2);
    				} else {
    					echo "Imobiliária:";
    				}
    				?>
				  </strong>
				</td>
				<td title="" nowrap class='valores'><?php
				if($lImobiliaria) {
					echo $imobiliaria;
				} else {
					echo "Matricula sem Imobiliária vinculada.";
				}
				?>
				</td>
				<td></td>
				<td title="<?=$Tj34_zona?>"><?=$Lj34_zona?>
				</td>
				<td title="" nowrap class='valores'><?= $oDadosMatricula->j34_zona  . " - " . $oDadosMatricula->j50_descr ?>
				</td>
			</tr>
			<tr>
				<td><strong><?=$Lj01_datacad?></strong></td>
				<td class='valores'><?=db_formatar($oDadosMatricula->j01_datacad,'d') ?></td>
				<td></td>
                <td><strong><?=$Lj01_baixa?></strong></td>
                <td class='valores'><?= db_formatar($oDadosMatricula->j01_baixa,'d') ?></td>
			</tr>
			<tr>
                <td><strong>Condomínio:</strong></td>
                <td class='valores'><?php
				                        if (!empty($oDadosMatricula->j108_condominio)) {
				                           echo $oDadosMatricula->j108_condominio  . " - " . $oDadosMatricula->j107_nome;
										}
                                    ?>
                </td>
                <td></td>
                <td><strong>Prédio:</strong></td>
                <td class='valores'><?php
				                        if (!empty($oDadosMatricula->j111_sequencial)) {
											echo $oDadosMatricula->j111_sequencial  . " - " . $oDadosMatricula->j111_nome;
										}
                                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td title="<?=$Tj34_setor?>"><b>Setor/Quadra/Lote:</b></td>
                <td nowrap class='valores'>
                    <?php
                        echo $oDadosMatricula->j34_setor  .  ' / ' . $oDadosMatricula->j34_quadra . ' / ' . $oDadosMatricula->j34_lote.' - '.$oDadosMatricula->j30_descr;
                    ?>
                </td>
                <td></td>
                <td title="Loteamento"><b>Loteamento:</b></td>
                <td title="" nowrap class='valores'><?=@$oDadosMatricula->j34_descr?></td> 				
            </tr>
            <tr>
                <td title="Área Total do Lote:"><b>Área Total do Lote:</b></td>
                <td title="" nowrap class='valores'><?php echo db_formatar($nAreaTotal,'f').' m²';?></td>
                <td></td>
                <td title="Construído no lote:"><b>Total Construido no Lote:</b></td>
                <td nowrap class='valores'><?php echo db_formatar($oDadosMatricula->j34_totcon,'f').' m²'?></td>
                <td></td>
            </tr>
			<tr>
				<td title="Área da Fração do Lote"><b>Área da Fração do Lote:</b></td>
				<td title="" nowrap class='valores'><?=db_formatar($oDadosMatricula->area_matric,"f").' m²';?> </td>
                <td></td>
                <td title="Construído na Unidade:"><b>Total Construido na Unidade:</b></td>
                <td nowrap class='valores'><?=db_formatar($nAreaConstruida,'f').' m²'?></td>
			</tr>
			<tr>
				<td title=""><b>Endereço do Imóvel:<b></td>
				<td title="" nowrap class='valores'><?=@$oDadosMatricula->codpri?> -
				<?=@$oDadosMatricula->tipopri?> . <?=@$oDadosMatricula->nomepri?>
					, <?=@$oDadosMatricula->j39_numero?> <?=(@$oDadosMatricula->j39_compl != ""?"/":"")?>
					<?=@$oDadosMatricula->j39_compl?>
				</td>
				<td></td>
				<td title=""><b>Setor/Quadra/Lote de localização:</b></td>
				<td title="" nowrap class='valores'><?php echo $lUtilizaLoc == true ? $sLoteloc : ''?></td>
			</tr>
			<tr>
                <td Title="Logradouro testada"><b>Testada do Imóvel:</b></td>
                <td title="" nowrap class='valores'><?=@$oDadosMatricula->j14_codigo?> -
                    <?=@$oDadosMatricula->j14_tipo?> . <?=@$oDadosMatricula->j14_nome?>, 
                    <?=$oDadosMatricula->j15_numero?> / <?=empty($oDadosMatricula->j15_compl)?'':$oDadosMatricula->j15_compl?>
                </td>
                <td style="width: 10px;"></td>
                <td title="<?=$Tj40_registrocartografico?>" style="width: 110px;"><?=$Lj40_registrocartografico?></td>
                <td title="<?=$Tj40_registrocartografico?>" nowrap class='valores'
                    style="width: 300px;"><?=$oDadosMatricula->j40_registrocartografico?>
                </td>
            </tr>
            <tr>
                <td title="Bairro"><b>Bairro:</b></td>
                <td title="" nowrap class='valores'><?=@$oDadosMatricula->j13_codi . " - " . @$oDadosMatricula->j13_descr?></td>                               
                <td></td>
                <td title="<?=$Tj91_codigo?>"><?=$Lj91_codigo?></td>
                <td nowrap class='valores'><?=@$oSetorFiscal->j91_codigo . " - " . @$oSetorFiscal->j90_descr?></td>
            </tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>
			<b>Detalhamento</b>
		</legend>
		<?php
		$oTabDetalhes = new verticalTab("detalhesemp",300);

		$oTabDetalhes->add("CaracteristicaImovel", "Caracteristicas do Imóvel",
        "cad3_conscadastro_002_detalhes.php?solicitacao=CaracteristicasDoImovel&parametro1=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("Isencoes", "Isenções",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Isencoes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucaoAtiva", "Construções ativas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Construcoes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucaoDemolida", "Construções demolidas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Construcoesdemolidas&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucoesEscrituradas", "Construções escrituradas" ,
        "cad3_conscadastro_002_detalhes.php?solicitacao=ConstrucoesEscrituradas&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Testada", "Testada",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Testada&parametro=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("TestadasInternas", "Testadas internas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=TestadasInternas&parametro=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("DemonstrativoCalculo", "Demonstrativo de Cálculo",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Imagens&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("EnderecoEntrega", "Endereço de entrega",
        "cad3_conscadastro_002_detalhes.php?solicitacao=EnderecoDeEntrega&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("OutrosProprietarios", "Outros proprietários",
        "cad3_conscadastro_002_detalhes.php?solicitacao=OutrosProprietarios&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("PromitentesCompradores", "Promitentes Compradores",
        "cad3_conscadastro_002_detalhes.php?solicitacao=OutrosPromitentes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ListaITBI", "Lista de ITBI",
        "cad3_conscadastro_002_detalhes.php?solicitacao=ListaITBI&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Averbacao", "Averbação",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Averbacao&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Calculo", "Cálculo",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("OutrosDados", "Outros dados",
        "cad3_conscadastro_002_detalhes.php?solicitacao=outros&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ImprimeBICCompleta", "Imprime BIC Completa (Novo)",
        "cad3_consbicresumida001.php?matricula=".$oDadosMatricula->j01_matric."&parametro=Completa");

		$oTabDetalhes->add("ImprimeBICResumida", "Imprime BIC Resumida (Novo)",
        "cad3_consbicresumida001.php?matricula=".$oDadosMatricula->j01_matric."&parametro=Resumida");

		$oTabDetalhes->add("ImprimeBICModeloNovo", "Imprime BIC - Modelo Novo",
        "cad3_conscadastrodetalhesmodelonovo001.php?matricula=".$oDadosMatricula->j01_matric);

                /* PLUGIN ESPELHO CALCULO SAO BORJA M15047 */

		$oTabDetalhes->add("Ocorrencias", "Ocorrências",
        "agu3_conscadastro_002_detalhes.php?solicitacao=Ocorrencia&parametro=".$oDadosMatricula->j01_matric);

		if ($oDadosMatricula->j04_sequencial != null) {

			$oTabDetalhes->add("DadosRegistroImoveis", "Dados do Registro de Imóveis",
          "cad3_conscadastro_002_detalhes.php?solicitacao=RegistroImovel&parametro=".$oDadosMatricula->j04_sequencial);
		}

		if (!empty($oDadosMatricula->j01_baixa)) {

			$oTabDetalhes->add("DadosBaixa", "Dados da Baixa",
          "cad3_conscadastro_002_detalhes.php?solicitacao=dadosbaixa&parametro=".$oDadosMatricula->j01_matric);
		}

	  $oTabDetalhes->add("certidaoConstrucao", "Certidão de Construção",
	      "cad3_conscadastro_002_detalhes.php?solicitacao=certidaoConstrucao&parametro=".$oDadosMatricula->j01_matric);

	  $oTabDetalhes->add("anexos", "Anexos",
	      "cad3_conscadastro_002_detalhes.php?solicitacao=anexos&parametro=".$oDadosMatricula->j01_matric);

    if ($db21_codcli == 19985){
       $oTabDetalhes->add("bicanterior", "Bic Recadastramento",
           "cad3_conscadastro_002_marica.php?parametro=".$oDadosMatricula->j01_matric);
    }


		$oTabDetalhes->show();
		?>

	</fieldset>
</body>
</html>
