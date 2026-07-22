<?
# function a_ata_number_format
# $string_column é a coluna selecionada 

function a_ata_number_format($string_column)
{
  $sRetorno = '';
  if (!empty($string_column)) {
    $sRetorno = number_format($string_column,2,',','.');
  } else {
    $sRetorno = number_format(0,2,',','.');
  }
  return $sRetorno;

}
?>
