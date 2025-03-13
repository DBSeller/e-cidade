<?php

class cl_bancovinculoconta extends DAOBasica {

    public function __construct() {
        parent::__construct('configuracoes.bancovinculoconta');
    }

    /**
     * Retorna todos os vinculos do banco
     * @param $banco
     * @return array|stdClass[]
     */
    public function getVinculosDoBanco($banco)
    {
        $campos = "db502_sequencial as codigo, db501_sequencial as tipo, db501_descricao as descricao_tipo, c60_codcon as codigo_conta,";
        $campos .= "c60_estrut as estrutural, c61_reduz as codigo_reduzido, c60_descr as descricao_conta";
        $sqlVerificarVinculoExistente = $this->sql_query_banco($campos, "db501_sequencial", "db502_db_bancos = '{$banco}'");
        $rsExisteVinculoTipo          = db_query($sqlVerificarVinculoExistente);
        if (!$rsExisteVinculoTipo  || pg_num_rows($rsExisteVinculoTipo) == 0) {
            return array();
        }
        return db_utils::getCollectionByRecord($rsExisteVinculoTipo);
    }

    /**
     * Consulta com joins na conplano
     * @param string $campos
     * @param null   $orderBy
     * @param null   $where
     * @return string
     */
    public function sql_query_banco($campos = '*', $orderBy = null, $where = null)
    {
        $sql = "select {$campos}";
        $sql .= " from bancovinculoconta ";
        $sql .= "      inner join bancovinculocontatipo on db501_sequencial = db502_bancovinculocontatipo";
        $sql .= "      inner join conplanoreduz         on db502_reduz = c61_reduz";
        $sql .= "                                      and c61_anousu =  ".db_getsession("DB_anousu", false);
        $sql .= "      inner join conplano              on c61_codcon = c60_codcon";
        $sql .= "                                      and c61_anousu =  c60_anousu";
        if (!empty($where)) {
            $sql .= " where {$where}";
        }
        if (!empty($orderBy)) {
            $sql .= " order By {$orderBy}";
        }
        return $sql;
    }
}
