<?php

namespace ECidade\Core\Helpers;

class DataNascimentoHelper extends \DBDate
{
    private $idade;
    private $idadeMaxima = 120;
    
    private $hojeData;

    private $hojeDia;
    private $hojeMes;
    private $hojeAno;

    private $nascimentoData;

    private $nascimentoDia;
    private $nascimentoMes;
    private $nascimentoAno;

    public function __construct($nascimentoData)
    {

        parent::__construct($nascimentoData);

        $this->nascimentoData = $this->getDate();

        $this->nascimentoDia = $this->getDia();
        $this->nascimentoMes = $this->getMes();
        $this->nascimentoAno = $this->getAno();
        
        $this->hojeData = $this->now()->getDate();

        $this->hojeDia = $this->now()->getDia();
        $this->hojeMes = $this->now()->getMes();
        $this->hojeAno = $this->now()->getAno();
        
        $this->idade = $this->calculaIdade();

        $this->validaDataNascimento();
    }

    public function calculaIdade()
    {
        return $this->hojeAno - $this->nascimentoAno;
    }

    public function validaDataNascimento()
    {

        $nasceuAmanha = ($this->nascimentoData > $this->hojeData);
        $ultrapassouIdadeMaxima = ($this->idade > $this->idadeMaxima);

        if ($nasceuAmanha) {
            throw new \Exception("Data de Nascimento inválida");
        }

        if ($ultrapassouIdadeMaxima) {
            throw new \Exception("Data de Nascimento ultrapassou a idade máxima");
        }
    }
}
