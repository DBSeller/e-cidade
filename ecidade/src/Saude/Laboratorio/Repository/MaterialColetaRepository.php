<?php


namespace ECidade\Saude\Laboratorio\Repository;

use ECidade\Saude\Laboratorio\Model\MaterialColeta;

class MaterialColetaRepository
{
    /**
     * @var \cl_lab_materialcoleta
     */
    private $dao;

    /**
     * MaterialColetaRepository constructor.
     * @param \cl_lab_materialcoleta $dao
     */
    public function __construct(\cl_lab_materialcoleta $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param string $columns
     * @return bool|MaterialColeta
     */
    public function find($id, $columns = '*')
    {
        $sql = $this->dao->sql_query($id, $columns);
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            return false;
        }

        return MaterialColeta::fromState(pg_fetch_array($rs));
    }

    /**
     * @param $codigoExame
     * @param \cl_lab_examematerial $daoExameMaterial
     * @return array|bool
     */
    public function getMateriaisExame($codigoExame, \cl_lab_examematerial $daoExameMaterial)
    {
        $where = "la19_i_exame = {$codigoExame}";
        $fields = 'la15_i_codigo, la15_c_descr';
        $sql = $daoExameMaterial->sql_query('', $fields, '', $where);

        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            return false;
        }

        $array = pg_fetch_all($rs);
        $materiaisColeta = array();
        foreach ($array as $materialColeta) {
            $materiaisColeta[] = MaterialColeta::fromState($materialColeta);
        }

        return $materiaisColeta;
    }
}
