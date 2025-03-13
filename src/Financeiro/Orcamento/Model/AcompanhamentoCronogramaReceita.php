<?php

namespace ECidade\Financeiro\Orcamento\Model;

class AcompanhamentoCronogramaReceita
{
    public $id;
    public $receita_id;
    public $exercicio;
    public $base_calculo;
    public $janeiro;
    public $fevereiro;
    public $marco;
    public $abril;
    public $maio;
    public $junho;
    public $julho;
    public $agosto;
    public $setembro;
    public $outubro;
    public $novembro;
    public $dezembro;

    /**
     * @param array $state
     * @return AcompanhamentoCronogramaReceita
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('id', $state)) {
            $self->id = $state['id'];
        }

        if (array_key_exists('receita_id', $state)) {
            $self->receita_id = $state['receita_id'];
        }

        if (array_key_exists('exercicio', $state)) {
            $self->exercicio = $state['exercicio'];
        }

        if (array_key_exists('base_calculo', $state)) {
            $self->base_calculo = $state['base_calculo'];
        }

        if (array_key_exists('janeiro', $state)) {
            $self->janeiro = $state['janeiro'];
        }

        if (array_key_exists('fevereiro', $state)) {
            $self->fevereiro = $state['fevereiro'];
        }

        if (array_key_exists('marco', $state)) {
            $self->marco = $state['marco'];
        }

        if (array_key_exists('abril', $state)) {
            $self->abril = $state['abril'];
        }

        if (array_key_exists('maio', $state)) {
            $self->maio = $state['maio'];
        }

        if (array_key_exists('junho', $state)) {
            $self->junho = $state['junho'];
        }

        if (array_key_exists('julho', $state)) {
            $self->julho = $state['julho'];
        }

        if (array_key_exists('agosto', $state)) {
            $self->agosto = $state['agosto'];
        }

        if (array_key_exists('setembro', $state)) {
            $self->setembro = $state['setembro'];
        }

        if (array_key_exists('outubro', $state)) {
            $self->outubro = $state['outubro'];
        }

        if (array_key_exists('novembro', $state)) {
            $self->novembro = $state['novembro'];
        }

        if (array_key_exists('dezembro', $state)) {
            $self->dezembro = $state['dezembro'];
        }

        return $self;
    }
}
