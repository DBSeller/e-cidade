<?php

namespace ECidade\Educacao\Secretaria\Services;

use cl_sec_parametros;
use ECidade\Educacao\Secretaria\Models\ParametrosGlobais;
use ECidade\V3\Extension\Registry;
use Exception;

class ParametrosGlobaisService
{

    /**
     * @var ParametrosGlobais
     */
    private $parametros;

    public function __construct()
    {
        $dao = new cl_sec_parametros();
        $sql = $dao->sql_query_file(null, '*');
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Parâmetros Globais da Secretaría.");
        }

        $this->parametros = ParametrosGlobais::fromState(pg_fetch_array($rs));
    }

    /**
     * @return ParametrosGlobais
     */
    public function getParametros()
    {
        return $this->parametros;
    }


    public static function get()
    {
        $configuracaoBaseCurricular = Registry::get('secretaria.configuracao_global');

        if (is_null($configuracaoBaseCurricular)) {
            $service = new ParametrosGlobaisService();

            Registry::set('secretaria.configuracao_global', $service->getParametros());
            $configuracaoBaseCurricular = $service->getParametros();
        }

        return $configuracaoBaseCurricular;
    }
}
