<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("dbforms/db_funcoes.php"));
parse_str($_SERVER["QUERY_STRING"]);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="300" align="left" valign="top" bgcolor="#CCCCCC">
	    <?php


$cor = "";
if ($tipo == "conta") {
	echo "<table bordercolor=\"#000000\" style=\"font-size:12px\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "<tr bgcolor=\"#FFCC66\">\n";
	echo "<th>Código</th>\n";
	echo "<th>Descrição</th>\n";
	echo "<th>Rec</th>\n";
	echo "<th>Saldo Ant.</th>\n";
	echo "<th>Vlr. Debit.</th>\n";
	echo "<th>Vlr. Cred.</th>\n";
	echo "<th>Saldo Atual</th>\n";
	echo "</tr>\n";
} else {
	echo "<table bordercolor=\"#000000\" style=\"font-size:12px\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "<tr bgcolor=\"#FFCC66\">\n";
	echo "<th>R.</th>\n";
	echo "<th>Descrição</th>\n";
	echo "<th>Saldo Ant.</th>\n";
	echo "<th>Vlr. Debit.</th>\n";
	echo "<th>Vlr. Cred.</th>\n";
	echo "<th>Saldo Atual</th>\n";
	echo "</tr>\n";
}

$totval1 = 0;
$totval2 = 0;
$totval3 = 0;
$totval4 = 0;

$tval1 = 0;
$tval2 = 0;
$tval3 = 0;
$tval4 = 0;

if ($tipo == "conta") {

	$sql = "select k13_conta, k13_descr, c60_estrut, o15_recurso, o15_descr
		          from saltes
					     inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
					     inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and
											  c61_reduz = c62_reduz and
										      c61_instit = ".db_getsession("DB_instit")."
					     inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
					     inner join orctiporec on o15_codigo = c61_codigo
			     where c60_codsis in (5,6)
             and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
		         order by k13_descr";

} else if ($tipo == "instituicao") {

	$sql = "select db90_codban,db90_descr
		    from saltes
					   inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
					   inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and
		                                                            c61_reduz = c62_reduz and
		                                                            c61_instit = ".db_getsession("DB_instit")."
					   inner join conplano on c61_anousu = c60_anousu  and c61_codcon=c60_codcon
					   inner join orctiporec on o15_codigo = c61_codigo
		               inner join conplanoconta on c63_anousu=c60_anousu and c63_reduz = c61_reduz
		               inner join db_bancos on db90_codban = conplanoconta.c63_banco
			where c60_codsis in (5,6)
             and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
            group by db90_codban, db90_descr
		    order by db90_codban";

} else if ($tipo == "domicilio_bancario") {



    $instit = db_getsession("DB_instit");
    $data = "$datai_ano-$datai_mes-$datai_dia";

	$sql = "

         select  substr(saltessaldo,2,13)::float8 as anterior,
                 substr(saltessaldo,15,13)::float8 as debitado ,
                 substr(saltessaldo,28,13)::float8 as creditado,
                 substr(saltessaldo,41,13)::float8 as atual,
                 *
           from (

               select
                      k13_conta,
                      k13_descr,
                      db89_db_bancos || ' / ' || db89_codagencia || ' / ' ||  db83_conta || ' - ' || db83_dvconta as bancoagencia,
                      o15_recurso,
                      o15_descr,
                      db83_conta  ,
                      db83_dvconta,
                      db89_db_bancos,
                      o15_complemento,
                      c61_reduz,
                      fc_saltessaldo(k13_conta,'$data','$data',null, $instit) as saltessaldo
		        from saltes
					     inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
					     inner join conplanoreduz on c61_anousu =".db_getsession("DB_anousu")." and
		                                                              c61_reduz = c62_reduz and
		                                                              c61_instit = ".db_getsession("DB_instit")."
					     inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
					     inner join orctiporec on o15_codigo = c61_codigo
                         join conplanocontabancaria on c56_codcon = c61_codcon
                                                   and c56_anousu = c61_anousu
                                                   and c56_reduz = c61_reduz
                         join contabancaria on (c56_contabancaria) = (db83_sequencial)
                         join bancoagencia on (db83_bancoagencia) = (db89_sequencial)

			     where c60_codsis in (5,6)
             and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
	        group by
                    o15_recurso,
                    k13_conta,
                    k13_descr,
                     o15_descr,
                     db89_db_bancos,
                     db89_codagencia,
                     db83_conta,
                     db83_dvconta,
                     o15_complemento,
                     c61_reduz
             ) as xx
			order by o15_recurso ";

            $rsDomicilio = db_query($sql);
            if (pg_numrows($rsDomicilio) > 0) {

                $aContas = array();
                for ($i = 0; $i < pg_numrows($rsDomicilio); $i++) {

                    $oDados = db_utils::fieldsMemory($rsDomicilio, $i);
                    $oDadosContas = new stdClass();
                    $oDadosContas->k13_conta = $oDados->k13_conta;
                    $oDadosContas->k13_descr = $oDados->k13_descr;
                    $oDadosContas->bancoagencia = $oDados->bancoagencia;
                    $oDadosContas->o15_recurso = $oDados->o15_recurso;
                    $oDadosContas->o15_descr = $oDados->o15_descr;
                    $oDadosContas->db83_conta = $oDados->db83_conta;
                    $oDadosContas->db83_dvconta = $oDados->db83_dvconta;
                    $oDadosContas->db89_db_bancos = $oDados->db89_db_bancos;
                    $oDadosContas->o15_complemento = $oDados->o15_complemento;
                    $oDadosContas->vlr_anterior = $oDados->anterior;
                    $oDadosContas->vlr_debito = $oDados->debitado;
                    $oDadosContas->vlr_credito = $oDados->creditado;
                    $oDadosContas->vlr_atual = $oDados->atual;
                    $aContas[$oDados->bancoagencia][] = $oDadosContas;
                }

                $aRegistros = array();
                $oConta = null;
                foreach ($aContas as $conta => $aConta) {

                    $oTotais = new stdClass();
                    $totalAnterior = 0;
                    $totalDebito = 0;
                    $totalCredito = 0;
                    $totalAtual = 0;

                    foreach($aConta as $oConta){

                       if ($oConta->tipo != "2" || $oConta->tipo != "3") {

                            $totalAnterior += $oConta->vlr_anterior;
                            $totalDebito +=   $oConta->vlr_debito;
                            $totalCredito +=  $oConta->vlr_credito;
                            $totalAtual +=   $oConta->vlr_atual;
                        }

                        $aRegistros[$conta]['dados'][] = $oConta;
                    }

                    $oTotais->totalAnterior = $totalAnterior;
                    $oTotais->totalDebito = $totalDebito;
                    $oTotais->totalCredito = $totalCredito;
                    $oTotais->totalAtual = $totalAtual;
                    $aRegistros[$conta]['totais'] =$oTotais;

                }


            }

} else {

	$sql = "select o15_recurso,
                   o15_descr
		     from saltes
			inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
			inner join conplanoreduz on c61_anousu =".db_getsession("DB_anousu")." and
		                                c61_reduz = c62_reduz and
		                                c61_instit = ".db_getsession("DB_instit")."
					     inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
					     inner join orctiporec on o15_codigo = c61_codigo
			     where c60_codsis in (5,6)
             and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
	                group by o15_recurso, o15_descr
			order by o15_recurso ";

}
$result = db_query($sql);
if (empty ($datai_dia)) {
	$datai_dia = date('d', db_getsession("DB_datausu"));
	$datai_mes = date('m', db_getsession("DB_datausu"));
	$datai_ano = date('Y', db_getsession("DB_datausu"));
}
for ($i = 0; $i < pg_numrows($result); $i ++) {
	db_fieldsmemory($result, $i);

	if ($tipo == "conta") {
		$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
		$valor = pg_result($result1, 0, 0);
		$valor = preg_split("/\s+/", $valor);

		echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
		echo "<td align='center' title='$c60_estrut'>".$k13_conta."</td>\n";
		echo "<td><font size='1'>".$k13_descr."</font></td>\n";
		echo "<td title='$o15_descr'><font size='1'> {$o15_recurso}</font></td>\n";
		if ($valor[0] == "2")
			echo "<td  colspan=\"4\">Nada no Corrente</td>\n";
		else
			if ($valor[0] == "3")
				echo "<td  colspan=\"4\">Não encontrado no cfautent</td>\n";
			else {
				echo "<td align=\"right\">".db_formatar($valor[1], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[2], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[3], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[4], 'f')."</td>\n";
				$totval1 += (float) str_replace(",", "", $valor[1]);
				$totval2 += (float) str_replace(",", "", $valor[2]);
				$totval3 += (float) str_replace(",", "", $valor[3]);
				$totval4 += (float) str_replace(",", "", $valor[4]);
			}
		echo "</tr>";
	} else
		if ($tipo == "recurso") { // tipo = recurso
			// imprime recurso e totaliza contas

			echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
			echo "<td align='center'>$o15_recurso</td>\n";
			echo "<td><font size='1'>".$o15_descr."</font></td>\n";

			$sql = "select k13_conta
					     from saltes
						            inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")."
							                          and c62_reduz = k13_conta
						            inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and
                                                                                 c61_reduz = c62_reduz and
                                                                                 c61_instit = ".db_getsession("DB_instit")."
						            inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
						            inner join orctiporec on o15_codigo = c61_codigo
					     where orctiporec.o15_recurso = '{$o15_recurso}'
                   and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
						     	 and c60_codsis in (5,6)
						 order by k13_conta";
			$result_contas = db_query($sql);
			$nrows = pg_numrows($result_contas);
			for ($h = 0; $h < $nrows; $h ++) {
				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);
				if ($valor[0] != "2" || $valor[0] != "3") {
					$tval1 += (float) str_replace(",", "", $valor[1]);
					$tval2 += (float) str_replace(",", "", $valor[2]);
					$tval3 += (float) str_replace(",", "", $valor[3]);
					$tval4 += (float) str_replace(",", "", $valor[4]);
				}
			}
			echo "<td align=\"right\">".db_formatar($tval1, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval2, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval3, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval4, 'f')."</td>\n";
			echo "</tr>";
			$totval1 += $tval1;
			$totval2 += $tval2;
			$totval3 += $tval3;
			$totval4 += $tval4;
			$tval1 = 0;
			$tval2 = 0;
			$tval3 = 0;
			$tval4 = 0;
		} else if ($tipo == "instituicao") {


			// quebra por bancos e lista as contas abaixo
	        echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
			echo "<td align='center'><b>".$db90_codban."</b></td>\n";
			echo "<td><font size='1'><b>".$db90_descr."</b></font></td>\n";
			$sql = "select k13_conta, k13_descr, c60_estrut
					from saltes
						inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")."
							                          and c62_reduz = k13_conta
						inner join conplanoreduz on c61_anousu=".db_getsession("DB_anousu")."  and
                                                                     c61_reduz = c62_reduz and
                                                                     c61_instit = ".db_getsession("DB_instit")."
						inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
						inner join orctiporec on o15_codigo = c61_codigo
                        inner join conplanoconta on c63_codcon = conplano.c60_codcon and c63_anousu=c60_anousu and c63_reduz = c61_reduz
                        inner join db_bancos on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)
					where trim(db_bancos.db90_codban)::integer = $db90_codban
             and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
							 and c60_codsis in (5,6)

					order by k13_descr";
			$result_contas = db_query($sql);
			$nrows = pg_numrows($result_contas);
			for ($h = 0; $h < $nrows; $h ++) {

				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);
				if ($valor[0] != "2" || $valor[0] != "3") {
					$tval1 += (float) str_replace(",", "", $valor[1]);
					$tval2 += (float) str_replace(",", "", $valor[2]);
					$tval3 += (float) str_replace(",", "", $valor[3]);
					$tval4 += (float) str_replace(",", "", $valor[4]);
				}
			}
			echo "<td align=\"right\">".db_formatar($tval1, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval2, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval3, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval4, 'f')."</td>\n";
			echo "</tr>";
			$totval1 += $tval1;
			$totval2 += $tval2;
			$totval3 += $tval3;
			$totval4 += $tval4;
			$tval1 = 0;
			$tval2 = 0;
			$tval3 = 0;
			$tval4 = 0;
			/////// lista contas
			for ($h = 0; $h < $nrows; $h ++) {

				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);

				echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
				// echo "<td align='center' title='$c60_estrut'>".$k13_conta."</td>\n";
				echo "<td align='center' title='$c60_estrut'>&nbsp; </td>\n";
				echo "<td><font size='1'> ($k13_conta) ".$k13_descr."</font></td>\n";

				echo "<td align=\"right\">".db_formatar($valor[1], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[2], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[3], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[4], 'f')."</td>\n";
				echo "</tr>";
			}

        } else if ($tipo == "domicilio_bancario") {

           $totval1 = 0;
           $totval2 = 0;
           $totval3 = 0;
           $totval4 = 0;

           foreach( $aRegistros as $conta => $registros ){

             $oTatal = $registros['totais'];
             echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
             echo "<td align='center'><b>$conta</b></td>\n";
             echo "<td><font size='1'><b> </b></font></td>\n";
             echo "<td align=\"right\">".db_formatar($oTatal->totalAnterior, 'f')."</td>\n";
             echo "<td align=\"right\">".db_formatar($oTatal->totalDebito, 'f')."</td>\n";
             echo "<td align=\"right\">".db_formatar($oTatal->totalCredito, 'f')."</td>\n";
             echo "<td align=\"right\">".db_formatar($oTatal->totalAtual, 'f')."</td>\n";
             echo "</tr>";

             $totval1 += $oTatal->totalAnterior;
             $totval2 += $oTatal->totalDebito;
             $totval3 += $oTatal->totalCredito;
             $totval4 += $oTatal->totalAtual;

             foreach ($registros['dados'] as $oConta) {

				echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
				echo "<td align='center' title='$c60_estrut'>&nbsp; </td>\n";
				echo "<td><font size='1'> ($oConta->k13_conta) ".$oConta->k13_descr." - {$oConta->o15_recurso} - {$oConta->o15_complemento}  </font></td>\n";
				echo "<td align=\"right\">".db_formatar($oConta->vlr_anterior, 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($oConta->vlr_debito, 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($oConta->vlr_credito, 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($oConta->vlr_atual, 'f')."</td>\n";
				echo "</tr>";

             }

           }

           break;



		} else {



			echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
			echo "<td align='center'><b>$o15_recurso</b></td>\n";
			echo "<td><font size='1'><b>".$o15_descr."</b></font></td>\n";
			$sql = "select k13_conta, k13_descr, c60_estrut
					from saltes
						            inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")."
							                          and c62_reduz = k13_conta
						            inner join conplanoreduz on c61_anousu=".db_getsession("DB_anousu")." and
											 c61_reduz = c62_reduz and
                                                                                 c61_instit = ".db_getsession("DB_instit")."
						            inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
						            inner join orctiporec on o15_codigo = c61_codigo
					                 where orctiporec.o15_recurso = '{$o15_recurso}'
                   and (k13_limite is null or k13_limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')
							 and c60_codsis in (5,6)
							 order by k13_descr";
			$result_contas = db_query($sql);
			$nrows = pg_numrows($result_contas);
			for ($h = 0; $h < $nrows; $h ++) {
				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);
				if ($valor[0] != "2" || $valor[0] != "3") {
					$tval1 += (float) str_replace(",", "", $valor[1]);
					$tval2 += (float) str_replace(",", "", $valor[2]);
					$tval3 += (float) str_replace(",", "", $valor[3]);
					$tval4 += (float) str_replace(",", "", $valor[4]);
				}
			}
			echo "<td align=\"right\">".db_formatar($tval1, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval2, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval3, 'f')."</td>\n";
			echo "<td align=\"right\">".db_formatar($tval4, 'f')."</td>\n";
			echo "</tr>";
			$totval1 += $tval1;
			$totval2 += $tval2;
			$totval3 += $tval3;
			$totval4 += $tval4;
			$tval1 = 0;
			$tval2 = 0;
			$tval3 = 0;
			$tval4 = 0;
			/////// lista contas
			for ($h = 0; $h < $nrows; $h ++) {
				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);

				echo "<tr bgcolor=\"". ($cor = ($cor == "#E4F471" ? "#EFE029" : "#E4F471"))."\">\n";
				// echo "<td align='center' title='$c60_estrut'>".$k13_conta."</td>\n";
				echo "<td align='center' title='$c60_estrut'>&nbsp; </td>\n";
				echo "<td><font size='1'> ($k13_conta) ".$k13_descr."</font></td>\n";

				echo "<td align=\"right\">".db_formatar($valor[1], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[2], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[3], 'f')."</td>\n";
				echo "<td align=\"right\">".db_formatar($valor[4], 'f')."</td>\n";
				echo "</tr>";
			}

		}

}
// imprime totais
$totval1 = number_format($totval1, 2, ",", ".");
$totval2 = number_format($totval2, 2, ",", ".");
$totval3 = number_format($totval3, 2, ",", ".");
$totval4 = number_format($totval4, 2, ",", ".");
echo "<script>
			     parent.document.form1.tot_ant.value = '$totval1' ;
			     parent.document.form1.tot_deb.value = '$totval2' ;
			     parent.document.form1.tot_cred.value ='$totval3' ;
			     parent.document.form1.tot_atual.value = '$totval4' ;
		        </script>
		       ";
/*
echo "<td colspan=\"3\">&nbsp;<strong>Total Geral</strong></td>\n";
echo "<td align=\"right\">".number_format($totval1,2,",",".")."</td>\n";
echo "<td align=\"right\">".number_format($totval2,2,",",".")."</td>\n";
echo "<td align=\"right\">".number_format($totval3,2,",",".")."</td>\n";
echo "<td align=\"right\">".number_format($totval4,2,",",".")."</td>\n";
echo "<table>\n";
*/
?>
	</td>
  </tr>
</table>
</body>
</html>
