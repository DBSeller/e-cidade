<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxaEspecifica as TaxaEspecificaModel;

use cl_tabdesc;
use DateTime;
use db_utils;
use Exception;
use stdClass;

/**
 * Class TaxaEspecifica
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class TaxaEspecifica extends \BaseClassRepository
{
    /**
     * @var cl_tabdesc
     */
    private $dao;

    /**
     * @param stdClass $stdClass
     * @return TaxaEspecificaModel
     */
    private function buildModel(stdClass $stdClass)
    {
        $taxaModel = new TaxaEspecificaModel();
        $taxaModel->setCodigoInflator($stdClass->k07_codinf);
        $taxaModel->setCodigoReceita($stdClass->k07_codigo);
        $taxaModel->setCodigoSubReceita($stdClass->codsubrec);
        $taxaModel->setDataCriacao(DateTime::createFromFormat('Y-m-d', $stdClass->k07_data));
        $taxaModel->setDescricaoSubReceita($stdClass->k07_descr);
        $taxaModel->setValorFixo($stdClass->k07_valorf);
        $taxaModel->setCodigoInstituicao($stdClass->k07_instit);

        return $taxaModel;
    }

    /**
     * @param TaxaEspecificaModel $taxaEspecificaModel
     * @return float
     * @throws Exception
     */
    public function calculaInflator(TaxaEspecificaModel $taxaEspecificaModel)
    {
        $inflator = $taxaEspecificaModel->getCodigoInflator();
        $valorFixo = $taxaEspecificaModel->getValorFixo();
        $dataCriacao = $taxaEspecificaModel->getDataCriacao()->format('Y-m-d');
        $dataAtual = date('Y-m-d');

        $dao = $this->getDao();
        $campos = "fc_infla('{$inflator}', {$valorFixo}, '{$dataCriacao}', '{$dataAtual}') as valor";
        $where = array(
            "k07_instit = {$taxaEspecificaModel->getCodigoInstituicao()}",
            "codsubrec = {$taxaEspecificaModel->getCodigoSubReceita()}"
        );

        $sql = $dao->sql_query_file(null, $campos, null, implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            throw new Exception('Não foi possível calcular o inflator.');
        }

        return db_utils::fieldsMemory($rs, 0)->valor;
    }

    /**
     * @param $codigoSubReceita
     * @return TaxaEspecificaModel
     */
    public function getByCodigoSubReceita($codigoSubReceita)
    {
        return self::getInstanciaPorCodigo($codigoSubReceita);
    }

    /**
     * @param $codigoSubReceita
     * @return TaxaEspecificaModel|null
     * @throws Exception
     */
    public function getByCodigo($codigoSubReceita)
    {
        $dao = $this->getDao();
        $sql = $dao->sql_query_file($codigoSubReceita);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar a taxa específica.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return self::getInstance()->buildModel(db_utils::fieldsMemory($rs, 0));
    }

    /**
     * @return cl_tabdesc
     */
    protected function getDao()
    {
        $this->dao = !empty($this->dao) ? $this->dao : new cl_tabdesc();

        return $this->dao;
    }

    /**
     * @param $codigoSubReceita
     * @return TaxaEspecificaModel|null|void
     * @throws Exception
     */
    protected function make($codigoSubReceita)
    {
        return self::getInstance()->getByCodigo($codigoSubReceita);
    }
}
