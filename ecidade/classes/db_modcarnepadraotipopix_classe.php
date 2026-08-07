<?php

class cl_modcarnepadraotipopix
{
    /**
     * Método para excluir os dados do banco modcarnepadraotipopix e 
     * os modcarnepadraotipopixasso referente ao k48_sequencial
     * 
     * @param int $k48_sequencial Indece referente ao modcarnepadroatipo
     * 
     * @return bool
     */
    public function excluir($k48_sequencial)
    {
        if (is_numeric($k48_sequencial))
        {
            db_query("DELETE FROM caixa.modcarnepadraopixasso WHERE k48_sequencial = {$k48_sequencial};");
            db_query("DELETE FROM caixa.modcarnepadraopix WHERE k48_sequencial = {$k48_sequencial};");
            
            return true;
        }

        return false;
    }
}
