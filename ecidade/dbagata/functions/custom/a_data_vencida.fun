<?
# function a_data_vencida
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_data_vencida($string_column, $array_row)
{

$ano = substr($string_column,6,4);
$mes = substr($string_column,3,2);
$dia = substr($string_column,0,2);
$string_column = mktime ( 24, 59, 59, $mes, $dia, $ano);   

$hoje = strtotime('now');

if ( $string_column < $hoje )
   {
   return 1;   
   }
   else
   {
   return 0;
   }

}
?>
