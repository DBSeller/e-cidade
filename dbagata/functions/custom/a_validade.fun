<?
# function a_validade
# Coloca a validade
# By Rodrigo Carvalhaes
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_validade($string_column, $array_row)
{
    $new_string = "";
    
    if ($string_column == 1)
    {
    $new_string = $string_column . ' DIA';
    }
    elseif ($string_column >1)
    {	
    $new_string = $string_column . ' DIAS';
    }
    elseif ($string_column == 0)
    {	
    $new_string = 'IMEDIATA';
    }

    
    return $new_string;

}

?>
