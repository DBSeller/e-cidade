<?php

namespace ECidade\RecursosHumanos\Pessoal\Interfaces;

use ECidade\RecursosHumanos\Pessoal\Interfaces\PontoModel;
use Exception;

interface PontoRepository
{
    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     * @param $rubrica
     * @return bool|PontoSalario
     * @throws Exception
     */
    public static function find($matricula = null, $ano = null, $mes = null, $rubrica = null);

    /**
     * @param integer $instituicao
     * @return PontoRepository
     */
    public function scopeInstituicao($instituicao);

    /**
     * @param string $rubrica
     * @return $rubrica
     */
    public function scopeRubrica($rubrica);

    /**
     * @return PontoModel[]
     * @throws Exception
     */
    public function get();

    /**
     * @param PontoModel $pontoModel
     * @return boolean
     */
    public function save(PontoModel $pontoModel);

    /**
     * @param PontoModel $pontoModel
     * @return boolean
     */
    public function delete(PontoModel $pontoModel);

    /**
     * @param PontoModel $pontoModel
     * @throws Exception
     */
    public function validate(PontoModel $pontoModel);
}
