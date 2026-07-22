<?php

namespace App\Domain\Saude\ESF\Adapters;

use App\Domain\Saude\Ambulatorial\Models\Problema;
use App\Domain\Saude\ESF\Contracts\ConsultaIndividual;

class ConsultaIndividualProblemasAdapter implements ConsultaIndividual
{
    /**
     * @var integer
     */
    private $idProntuario;

    /**
     * @var \Illuminate\Support\Collection;
     */
    private $problemas;

    public function __construct($idProntuario)
    {
        $this->idProntuario = $idProntuario;
        $this->problemas = collect();
    }

    public function getIdProntuario()
    {
        return $this->idProntuario;
    }

    public function addProblema(Problema $problema)
    {
        $this->problemas->push($problema->s169_id);
        return $this;
    }

    public function isAsma()
    {
        return $this->problemas->search(Problema::ASMA) !== false;
    }

    public function isCancerColoUtero()
    {
        return $this->problemas->search(Problema::CANCER_COLO_UTERO) !== false;
    }

    public function isDengue()
    {
        return $this->problemas->search(Problema::DENGUE) !== false;
    }

    public function isDiabetes()
    {
        return $this->problemas->search(Problema::DIABETES) !== false;
    }

    public function isCancerMama()
    {
        return $this->problemas->search(Problema::CANCER_MAMA) !== false;
    }

    public function isDpoc()
    {
        return $this->problemas->search(Problema::DPOC) !== false;
    }

    public function isDesnutricao()
    {
        return $this->problemas->search(Problema::DESNUTRICAO) !== false;
    }

    public function isDst()
    {
        return $this->problemas->search(Problema::DST) !== false;
    }

    public function isHanseniase()
    {
        return $this->problemas->search(Problema::HANSENIASE) !== false;
    }

    public function isHipertensaoArterial()
    {
        return $this->problemas->search(Problema::HIPERTENSAO_ARTERIAL) !== false;
    }

    public function isObesidade()
    {
        return $this->problemas->search(Problema::OBESIDADE) !== false;
    }

    public function isPreNatal()
    {
        return $this->problemas->search(Problema::PRE_NATAL) !== false;
    }

    public function isPuericultura()
    {
        return $this->problemas->search(Problema::PUERICULTURA) !== false;
    }

    public function isPuerperio()
    {
        return $this->problemas->search(Problema::PUERPERIO) !== false;
    }

    public function isReabilitacao()
    {
        return $this->problemas->search(Problema::REABILITACAO) !== false;
    }

    public function isRiscoCardiovascular()
    {
        return $this->problemas->search(Problema::RISCO_CARDIOVASCULAR) !== false;
    }

    public function isSaudeMental()
    {
        return $this->problemas->search(Problema::SAUDE_MENTAL) !== false;
    }

    public function isSaudeSexualReprodutiva()
    {
        return $this->problemas->search(Problema::SAUDE_SEXUAL_REPRODUTIVA) !== false;
    }

    public function isTabagismo()
    {
        return $this->problemas->search(Problema::TABAGISMO) !== false;
    }

    public function isTuberculose()
    {
        return $this->problemas->search(Problema::TUBURCULOSE) !== false;
    }

    public function isUsuarioAlcool()
    {
        return $this->problemas->search(Problema::USUARIO_ALCOOL) !== false;
    }

    public function isUsuarioOutrasDrogas()
    {
        return $this->problemas->search(Problema::USUARIO_OUTRAS_DROGAS) !== false;
    }
}
