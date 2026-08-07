<?php


namespace ECidade\Tributario\Juridico\Repository;

use ECidade\Tributario\Juridico\Inicial\Inicial;
use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;

class HonorariosParcelamento extends \BaseClassRepository
{
    /**
     * @param Inicial $inicial
     * @return integer|null
     * @throws \Exception
     */
    public function getByInicial(Inicial $inicial)
    {
        $where = " ar43_inicial = ". $inicial->getCodigo();
        $retorno = $this->getByWhere($where);

        return isset($retorno->ar43_numeroparcelas) ? $retorno->ar43_numeroparcelas : null;
    }

    /**
     * @param ProcessoForo $processoForo
     * @return integer|null
     * @throws \Exception
     */
    public function getByProcessoForo(ProcessoForo $processoForo)
    {
        $where = " ar43_processoforo = ". $processoForo->getCodigo();
        $retorno = $this->getByWhere($where);

        return isset($retorno->ar43_numeroparcelas) ? $retorno->ar43_numeroparcelas : null;
    }

    /**
     * @param string $where
     * @return \stdClass|null
     * @throws \Exception
     */
    private function getByWhere($where)
    {
        $dao = new \cl_honorariosparcelamento();
        $sql = $dao->sql_query_file(null, '*', null, $where);
        $result = db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar quantidade de parcelas de honorários');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $retorno = \db_utils::fieldsMemory($result, 0);

        return $retorno;
    }
}
