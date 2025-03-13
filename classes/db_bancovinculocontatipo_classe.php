<?php

class cl_bancovinculocontatipo extends DAOBasica
{

    public function __construct()
    {

        parent::__construct('configuracoes.bancovinculocontatipo');
    }

    /**
     * Retorna todos os registros da tabela, em formato de stdClass;
     * @return  array
     */
    public function getAll()
    {

        $sqlTiposVinculo = $this->sql_query_file(null,"*", "db501_sequencial");
        $tipoContas        =  db_utils::getCollectionByRecord(db_query($sqlTiposVinculo));
        return $tipoContas;
    }
}
