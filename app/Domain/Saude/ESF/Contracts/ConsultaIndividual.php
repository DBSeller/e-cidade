<?php

namespace App\Domain\Saude\ESF\Contracts;

interface ConsultaIndividual
{
    /**
     * @return integer
     */
    public function getIdProntuario();

    /**
     * @return boolean
     */
    public function isAsma();

    /**
     * @return boolean
     */
    public function isCancerColoUtero();

    /**
     * @return boolean
     */
    public function isCancerMama();

    /**
     * @return boolean
     */
    public function isDengue();

    /**
     * @return boolean
     */
    public function isDesnutricao();

    /**
     * @return boolean
     */
    public function isDiabetes();

    /**
     * @return boolean
     */
    public function isDpoc();

    /**
     * @return boolean
     */
    public function isDst();

    /**
     * @return boolean
     */
    public function isHanseniase();

    /**
     * @return boolean
     */
    public function isHipertensaoArterial();

    /**
     * @return boolean
     */
    public function isObesidade();

    /**
     * @return boolean
     */
    public function isPreNatal();

    /**
     * @return boolean
     */
    public function isPuericultura();

    /**
     * @return boolean
     */
    public function isPuerperio();

    /**
     * @return boolean
     */
    public function isReabilitacao();

    /**
     * @return boolean
     */
    public function isRiscoCardiovascular();

    /**
     * @return boolean
     */
    public function isSaudeMental();

    /**
     * @return boolean
     */
    public function isSaudeSexualReprodutiva();

    /**
     * @return boolean
     */
    public function isTabagismo();

    /**
     * @return boolean
     */
    public function isTuberculose();

    /**
     * @return boolean
     */
    public function isUsuarioAlcool();

    /**
     * @return boolean
     */
    public function isUsuarioOutrasDrogas();
}
