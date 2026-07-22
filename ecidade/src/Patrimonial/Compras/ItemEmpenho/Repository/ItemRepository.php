<?php
namespace ECidade\Patrimonial\Compras\ItemEmpenho\Repository;

use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;
use ECidade\Patrimonial\Compras\ItemEmpenho\Model\Item;

class ItemRepository
{

    /**
     * @var \cl_pcmater
     */
    private $dao;

    /**
     * ItemRepository constructor.
     * @param \cl_pcmater $dao
     */
    public function __construct(\cl_pcmater $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Item
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query_file($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception('');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Item::fromState($resultado);
    }


    /**
     * @param \cl_empautitem $daoItemAutorizacao
     * @param Autorizacao $autorizacao
     * @return array|null
     * @throws \Exception
     */
    public function getItensPorAutorizacao(\cl_empautitem $daoItemAutorizacao, Autorizacao $autorizacao)
    {
        $sql = $daoItemAutorizacao->sql_query_file($autorizacao->getCodigoAutorizacao());
        $rs = db_query($sql);

        $numrows = pg_num_rows($rs);
        if (!$rs || $numrows === 0) {
            return null;
        }

        $retorno = array();

        for ($i = 0; $i < $numrows; $i++) {
            $codigo = pg_fetch_result($rs, $i, 'e55_item');
            $retorno[] = $this->find((int) $codigo);
        }
        return $retorno;
    }
}
