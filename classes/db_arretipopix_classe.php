<?php

class cl_arretipopix
{
    public function excluir($k00_tipo)
    {
        if (is_numeric($k00_tipo))
        {
            db_query("DELETE FROM caixa.arretipopixasso WHERE k00_tipo = {$k00_tipo};");
            db_query("DELETE FROM caixa.arretipopix WHERE k00_tipo = {$k00_tipo};");

            return true;
        }

        return false;
    }
}
