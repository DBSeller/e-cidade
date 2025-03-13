<?php

namespace App\Domain\Financeiro\Empenho\Services;

use App\Domain\Financeiro\Empenho\Repositories\TipoAquisicaoProducaoRuralRepository;

class TipoAquisicaoProducaoRuralService
{
    protected $repository;
    protected $model;

    public function __construct($id = null)
    {
        $this->repository = new TipoAquisicaoProducaoRuralRepository;
        $this->model = $this->repository->getModelInstance($id);
    }

    /**
     * Setter numemp
     *
     * @param int $numemp
     * @return void
     */
    public function setNumemp($numemp)
    {
        // verificar se já possui tipo de aquisicao para o empenho informado
        $tipoAquisicaoProducaoRural = $this->repository->findByEmpenho($numemp);

        if ($tipoAquisicaoProducaoRural) {
            $this->model = $tipoAquisicaoProducaoRural;
        } else {
            $this->model->e159_empempenho = $numemp;
        }

        unset($tipoAquisicaoProducaoRural);
    }

    /**
     * Setter tipo
     *
     * @param int $tipo
     * @return void
     */
    public function setTipo($tipo)
    {
        $this->model->e159_tipo = $tipo;
        $this->model->e159_label = $this->label($tipo);
    }

    /**
     * Getter numemp
     *
     * @return string
     */
    public function getNumemp()
    {
        return $this->model->e159_numemp;
    }

    /**
     * Getter tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->model->e159_tipo;
    }

    public function save()
    {
        $validate = $this->validate();
        if ($validate['error']) {
            throw new \Exception($validate['msg']);
            return false;
        }

        return $this->repository->persist($this->model);
    }

    /**
     * Validações dos atributos antes da persistência
     *
     * @return array
     */
    private function validate()
    {
        $validate = ['error'=> false, 'msg' => false];

        if ($this->getTipo() == '' && $this->getNumemp() == '') {
            $validate['error'] = true;
            $validate['msg'] .= "[Tipo de Aquisição] Campos obrigatórios não informados.\n";
        }

        return $validate;
    }

     /**
     * Retorna o label correspondente ao tipo passsado
     *
     * @param string $tipo
     * @return string
     */
    public function label($tipo)
    {
        return TipoAquisicaoProducaoRuralRepository::labels($tipo);
    }
}
