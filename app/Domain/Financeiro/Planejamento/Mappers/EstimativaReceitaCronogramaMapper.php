<?php

namespace App\Domain\Financeiro\Planejamento\Mappers;

class EstimativaReceitaCronogramaMapper extends EstimativaReceitaMapper
{
    public $janeiro = 0;
    public $fevereiro = 0;
    public $marco = 0;
    public $abril = 0;
    public $maio = 0;
    public $junho = 0;
    public $julho = 0;
    public $agosto = 0;
    public $setembro = 0;
    public $outubro = 0;
    public $novembro = 0;
    public $dezembro = 0;
    public $exercicioMeta;
    private $idCronograma;

    /**
     * @param mixed $janeiro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setJaneiro($janeiro)
    {
        $this->janeiro = $janeiro;
        return $this;
    }

    /**
     * @param mixed $fevereiro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setFevereiro($fevereiro)
    {
        $this->fevereiro = $fevereiro;
        return $this;
    }

    /**
     * @param mixed $marco
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setMarco($marco)
    {
        $this->marco = $marco;
        return $this;
    }

    /**
     * @param mixed $abril
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setAbril($abril)
    {
        $this->abril = $abril;
        return $this;
    }

    /**
     * @param mixed $maio
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setMaio($maio)
    {
        $this->maio = $maio;
        return $this;
    }

    /**
     * @param mixed $junho
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setJunho($junho)
    {
        $this->junho = $junho;
        return $this;
    }

    /**
     * @param mixed $julho
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setJulho($julho)
    {
        $this->julho = $julho;
        return $this;
    }

    /**
     * @param mixed $agosto
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setAgosto($agosto)
    {
        $this->agosto = $agosto;
        return $this;
    }

    /**
     * @param mixed $setembro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setSetembro($setembro)
    {
        $this->setembro = $setembro;
        return $this;
    }

    /**
     * @param mixed $outubro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setOutubro($outubro)
    {
        $this->outubro = $outubro;
        return $this;
    }

    /**
     * @param mixed $novembro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setNovembro($novembro)
    {
        $this->novembro = $novembro;
        return $this;
    }

    /**
     * @param mixed $dezembro
     * @return EstimativaReceitaCronogramaMapper
     */
    public function setDezembro($dezembro)
    {
        $this->dezembro = $dezembro;
        return $this;
    }

    /**
     * exercício do cronograma de arrecadação
     * @param $exercicio
     * @return $this
     */
    public function setExercicioMeta($exercicio)
    {
        $this->exercicioMeta = $exercicio;
        return $this;
    }

    public function setIdCronograma($idCronograma)
    {
        $this->idCronograma = $idCronograma;
        return $this;
    }

    /**
     * Obs.: Esse metodo troca o id da estimativa pela propriedade estimativa_id e o id do cronograma pelo id
     * @return object
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data->estimativareceita_id = $data->id;
        $data->id = $this->idCronograma;
        $data->exercicio = $this->exercicioMeta;
        $data->janeiro = $this->janeiro;
        $data->fevereiro = $this->fevereiro;
        $data->marco = $this->marco;
        $data->abril = $this->abril;
        $data->maio = $this->maio;
        $data->junho = $this->junho;
        $data->julho = $this->julho;
        $data->agosto = $this->agosto;
        $data->setembro = $this->setembro;
        $data->outubro = $this->outubro;
        $data->novembro = $this->novembro;
        $data->dezembro = $this->dezembro;

        return $data;
    }
}
