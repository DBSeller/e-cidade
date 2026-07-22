<?
# function a_accumulate_value
# $string_column  COLUNA ATUAL
# $array_row      LINHA ATUAL DO RELATORIO
# $array_last_row LINHA ANTERIOR DO RELATORIO
# $row_num is the current row number 
# $col_num is the current column number -- NOTE QUE ARRAY COMEÇA COM ZERO!

function a_saldo($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
	

	if ($array_last_row[0] !== $array_row[0]) // mudou o valor da coluna  - produto
	{
		$saldo = $string_column - $array_row[$col_num-1]; // Saldo em estoque - Diferença de pedidos
		return $saldo;
	}
	else
	{
		$anterior = $array_last_row[$col_num]; // Ultima Linha.
		return $anterior - $array_row[$col_num-1] ;
	}
}
?>

?>
