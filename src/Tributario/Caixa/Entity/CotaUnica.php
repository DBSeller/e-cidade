<?php 

namespace ECidade\Tributario\Caixa\Entity;

use \DateTime;
use ECidade\Tributario\Library\Entity;

final class CotaUnica extends Entity
{
    private $numpre;

    private $vencimento;

    private $operacao;

    private $porcentagem;
    
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setVencimento(DateTime $vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function setOperacao(DateTime $operacao)
    {
        $this->operacao = $operacao;
    }

    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function getOperacao()
    {
        return $this->operacao;
    }

    public function getPorcentagem()
    {
        return $this->porcentagem;
    }
}
