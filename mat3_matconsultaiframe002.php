<?php
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_db_depart_classe.php"));
include(modification("classes/db_matestoquetipo_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_depart = new cl_db_depart;
$clmatestoquetipo = new cl_matestoquetipo;
$clrotulo = new rotulocampo;
$clrotulo->label("");

$rsDecimais = db_query("select e30_numdec from empparametro where e39_anousu = ".db_getsession("DB_anousu"));
$oDec       = db_utils::fieldsmemory($rsDecimais, 0);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_lanca(codigo){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lanca','mat3_matconsultaiframe003.php?codigo='+codigo,'Consulta Lançamentos',true);
}
function js_relo(){
  document.form1.submit();
}
</script>
<style>

.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr>
<td  align="center" valign="top" >

<table border='0'>
<form name='form1'>
  <tr align=left >
    <td colspan=6 align='left' nowrap >
    <b>Depósito:</b>
<?php
    $daoDeposito = new cl_db_almox();
    $sqlDeposito = $daoDeposito->sql_query(null, 'm91_codigo, descrdepto', '1');
    $rsDepositos = db_query($sqlDeposito);

    db_selectrecord("deposito", $rsDepositos, true, 2, "", "", "", "", "js_relo();");
?>
    <b>Tipo de Lançamento:</b>
<?php
    $sqlLancamento = $clmatestoquetipo->sql_query_file(null, "*", "m81_descr");
    $result_lanc=$clmatestoquetipo->sql_record($sqlLancamento);
   db_selectrecord("lancamento", $result_lanc, true, 2, "", "", "", "0", "js_relo();");
?>
    </td>
  </tr>
  <tr>
    <td colspan=6  align=center >
    <?php
    if (isset($lNovaConsulta) && !$lNovaConsulta) {
        echo "<input type='button' value='Voltar' onclick='parent.db_iframe_lancamentos.hide();' >";
    }
    ?>
    <br>
    </td>
  </tr>
  <tr>
  <td colspan=6 align=center>

<?php
  db_input('codmater', 10, '', true, 'hidden', 3);


if (isset($codmater)&&$codmater!="") {
    $where = "";
    $and="";

    if (isset($deposito)&&$deposito!=0&&$deposito!="") {
        $where.=" and m91_codigo=$deposito ";
    }
    if (isset($lancamento)&&$lancamento!=0&&$lancamento!="") {
        $where.=" and m80_codtipo=$lancamento ";
    }
    if (isset($db_where)&&$db_where!="") {
        if ($db_where=="D") {
            $depto_atual=db_getsession("DB_coddepto");
            $where.="  and m80_coddepto=$depto_atual ";
        } else {
            $where.=" and $db_where  ";
        }
    }
    $where.=" and instit = " . db_getsession("DB_instit");
    if (isset($db_inner)&&$db_inner!="") {
        $inner="  $db_inner  ";
    } else {
        $inner="";
    }
    $sql  = " select m80_codigo, ";
    $sql .= "	       m81_descr, ";
    $sql .= "        m81_entrada, ";
    $sql .= "        origem as  dl_Lanc_Origem, ";
    $sql .= "	       (select sum(m82_quant) ";
    $sql .= "           from matestoqueinimei ";
    $sql .= "             inner join matestoqueitem on m71_codlanc = m82_matestoqueitem ";
    $sql .= "             inner join matestoque on m71_codmatestoque = m70_codigo ";
    $sql .= "           where m82_matestoqueini = m80_codigo ";
    $sql .= "             and m70_codmatmater = {$codmater} ";
    $sql .= "         ) as dl_Quantidade, ";
    $sql .= "        round(avg(m89_valorunitario), {$oDec->e30_numdec}) as dl_Valor_Unitário, ";
    $sql .= "        round(avg(m89_precomedio), {$oDec->e30_numdec}) as dl_Preço_Medio, ";
    $sql .= "        m89_valorfinanceiro as dl_Valor_Total,";
    $sql .= "        case when m80_codtipo = 12 then '' else descrdepto end as dl_Depósito_de_origem, ";
    $sql .= "        case when m80_codtipo = 12 then descrdepto else coalesce(coddepto_destino, (

  select destino.descrdepto
   from  matestoqueini as matestoqueiniorigem
  inner join matestoqueinill on matestoqueinill.m87_matestoqueini = matestoqueiniorigem.m80_codigo
  inner join matestoqueinil  on matestoqueinil.m86_matestoqueini = matestoqueinill.m87_matestoqueini
  inner join matestoqueinill as matestoqueinilldestino on matestoqueinilldestino.m87_matestoqueinil = matestoqueinil.m86_codigo
  inner join matestoqueini   as matestoqueinidestino on matestoqueinidestino.m80_codigo = matestoqueinilldestino.m87_matestoqueini
  inner join db_depart as destino on destino.coddepto = matestoqueinidestino.m80_coddepto
     where matestoqueiniorigem.m80_codigo =  x.m80_codigo
           )) end as dl_Departamento_destino, ";

    $sql .= "	       m80_data,   ";
    $sql .= "	       m80_hora,   ";
    $sql .= "	       nome, ";
    $sql .= "	       m80_codtipo";
    $sql .= "   from ( select m80_codigo, ";
    $sql .= "	                m81_descr, ";
    $sql .= "                 m81_entrada, ";
    $sql .= "	                m86_matestoqueini, ";
    $sql .= "                 case ";
    $sql .= "                   when m86_matestoqueini is not null then m86_matestoqueini   ";
    $sql .= "                   else ( case  ";
    $sql .= "                            when m52_codordem  is not null and m81_descr = 'ENTRADA DA ORDEM DE COMPRA' then m52_codordem  ";
    $sql .= "                            else null ";
    $sql .= "                          end ) ";
    $sql .= "                 end as origem, ";
    $sql .= "                 round(m89_precomedio, 5)::numeric as m89_precomedio,  ";
    $sql .= "                 round(m89_valorunitario, 5)::numeric as m89_valorunitario,  ";
    $sql .= "	                m82_quant, ";
    $sql .= "	                m89_valorfinanceiro, ";
    $sql .= "                 round(m89_precomedio, {$oDec->e30_numdec})::numeric as fc_calculapm , ";
    $sql .= "	                descrdepto,  ";
    $sql .= "	                m80_data, ";
    $sql .= "	                m80_hora, ";
    $sql .= "	                nome, ";
    $sql .= "	                m80_codtipo, ";
    $sql .= "                (select db_depart.descrdepto";
    $sql .= "	                  from matestoqueinimei ";
    $sql .= "	                       inner join matestoqueitem      on matestoqueitem.m71_codlanc                  = matestoqueinimei.m82_matestoqueitem ";
    $sql .= "	                       inner join matestoqueinimeiari on matestoqueinimeiari.m49_codmatestoqueinimei = matestoqueinimei.m82_codigo   ";
    $sql .= "	                       inner join atendrequiitem      on atendrequiitem.m43_codigo                   = matestoqueinimeiari.m49_codatendrequiitem ";
    $sql .= "	                       inner join matrequiitem        on matrequiitem.m41_codigo                     = atendrequiitem.m43_codmatrequiitem ";
    $sql .= "	                       inner join matrequi            on matrequi.m40_codigo                         = matrequiitem.m41_codmatrequi  ";
    $sql .= "	                       inner join db_depart           on db_depart.coddepto                          = matrequi.m40_depto";
    $sql .= "	                 where matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo              ";
    $sql .= "	                 limit 1) as coddepto_destino ";
    $sql .= "            from matestoqueini ";
    $sql .= "	                inner join matestoquetipo on m80_codtipo = m81_codtipo ";
    $sql .= "	                inner join matestoqueinimei on m82_matestoqueini = m80_codigo ";
    $sql .= "	                inner join db_usuarios on m80_login = id_usuario ";
    $sql .= "	                inner join db_depart on m80_coddepto = coddepto ";
    $sql .= "	                inner  join material.db_almox ON db_almox.m91_depto = db_depart.coddepto";
    $sql .= "	                inner join matestoqueitem on m82_matestoqueitem = m71_codlanc ";
    $sql .= "	                inner join matestoque on m71_codmatestoque = m70_codigo ";
    $sql .= "	                inner join matmater on m60_codmater = m70_codmatmater ";
    $sql .= "                 left join matestoqueitemoc on  m71_codlanc = m73_codmatestoqueitem and m73_cancelado is false ";
    $sql .= "                 left join matordemitem on m52_codlanc = m73_codmatordemitem ";
    $sql .= "	                left join matestoqueinill on m87_matestoqueini = m80_codigo ";
    $sql .= "	                left join matestoqueinil on m86_codigo = m87_matestoqueinil ";
    $sql .= "                 left join matestoqueinimeipm on m82_codigo  = m89_matestoqueinimei ";
    $sql .= "	                $inner";
    $sql .= "           where m70_codmatmater = $codmater $where and m71_servico is false ";
    $sql .= "         ) as x ";

    $sql .= "group by m80_data, ";
    $sql .= "         m80_codigo,";
    $sql .= "	        m81_descr,";
    $sql .= "         m81_entrada,";
    $sql .= "	        m86_matestoqueini,";
    $sql .= "	        descrdepto,		 ";
    $sql .= "	        m80_hora,";
    $sql .= "	        nome,";
    $sql .= "	        m89_valorfinanceiro,";
    $sql .= "         origem,coddepto_destino, m80_codtipo ";
    $sql .= "order by m80_data desc, ";
    $sql .= "         m80_hora desc,";
    $sql .= "         m80_codigo,";
    $sql .= "         m81_descr,";
    $sql .= "         m81_entrada,";
    $sql .= "         m86_matestoqueini,";
    $sql .= "         descrdepto,	";
    $sql .= "         nome,";
    $sql .= "         origem  ";
}

$repassa = array('dblov'=>'0');
?>
</form>
<?php
  $repassa = array('dblov'=>'0');
  db_lovrot(@$sql, 15, "()", "", "", "", "NoMe", $repassa);

?>
</td>
</tr>
</table>

</td>
</tr>
</table>
<script>
</script>
</body>
</html>
