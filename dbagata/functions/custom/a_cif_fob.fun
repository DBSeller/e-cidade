<?
# function a_cif_fob
# Troca o F por FOB e o C por CIF
# By Rodrigo Carvalhaes
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_cif_fob($string_column, $array_row)
{
    $new_string = "";
    
    if ($string_column == 'F')
    {
    $new_string = 'FOB';
    }
    elseif ($string_column == 'C')
    {
    $new_string = 'CIF';
    }

    
    return $new_string;

}

?>
