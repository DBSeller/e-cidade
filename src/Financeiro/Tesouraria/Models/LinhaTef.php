<?php

namespace ECidade\Financeiro\Tesouraria\Models;

/**
 * Class LinhaTef
 * @package ECidade\Financeiro\Tesouraria\Models
 */
class LinhaTef
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $numeroAutorizacao;
    /**
     * @var integer
     */
    private $numeroCv;
    /**
     * @var string
     */
    private $cartao;
    /**
     * @var string
     */
    private $dataVenda;
    /**
     * @var string
     */
    private $dataVencimento;
    /**
     * @var integer
     */
    private $parcela;
    /**
     * @var integer
     */
    private $totalParcelas;
    /**
     * @var float
     */
    private $valorOriginal;
    /**
     * @var float
     */
    private $valorBruto;
    /**
     * @var float
     */
    private $valorDescontos;
    /**
     * @var float
     */
    private $valorLiquido;

    private $idOperacoesRealizadasTef;

    private $consistente = true;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LinhaTef
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumeroAutorizacao()
    {
        return $this->numeroAutorizacao;
    }

    /**
     * @param int $numeroAutorizacao
     * @return LinhaTef
     */
    public function setNumeroAutorizacao($numeroAutorizacao)
    {
        $this->numeroAutorizacao = $numeroAutorizacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumeroCv()
    {
        return $this->numeroCv;
    }

    /**
     * @param int $numeroCv
     * @return LinhaTef
     */
    public function setNumeroCv($numeroCv)
    {
        $this->numeroCv = $numeroCv;
        return $this;
    }

    /**
     * @return string
     */
    public function getCartao()
    {
        return $this->cartao;
    }

    /**
     * @param string $cartao
     * @return LinhaTef
     */
    public function setCartao($cartao)
    {
        $this->cartao = $cartao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataVenda()
    {
        return $this->dataVenda;
    }

    /**
     * @param string $dataVenda
     * @return LinhaTef
     */
    public function setDataVenda($dataVenda)
    {
        $this->dataVenda = $dataVenda;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param string $dataVencimento
     * @return LinhaTef
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    /**
     * @return int
     */
    public function getParcela()
    {
        return $this->parcela;
    }

    /**
     * @param int $parcela
     * @return LinhaTef
     */
    public function setParcela($parcela)
    {
        $this->parcela = $parcela;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalParcelas()
    {
        return $this->totalParcelas;
    }

    /**
     * @param int $totalParcelas
     * @return LinhaTef
     */
    public function setTotalParcelas($totalParcelas)
    {
        $this->totalParcelas = $totalParcelas;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorOriginal()
    {
        return $this->valorOriginal;
    }

    /**
     * @param float $valorOriginal
     * @return LinhaTef
     */
    public function setValorOriginal($valorOriginal)
    {
        $this->valorOriginal = $valorOriginal;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorBruto()
    {
        return $this->valorBruto;
    }

    /**
     * @param float $valorBruto
     * @return LinhaTef
     */
    public function setValorBruto($valorBruto)
    {
        $this->valorBruto = $valorBruto;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorDescontos()
    {
        return $this->valorDescontos;
    }

    /**
     * @param float $valorDescontos
     * @return LinhaTef
     */
    public function setValorDescontos($valorDescontos)
    {
        $this->valorDescontos = $valorDescontos;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorLiquido()
    {
        return $this->valorLiquido;
    }

    /**
     * @param float $valorLiquido
     * @return LinhaTef
     */
    public function setValorLiquido($valorLiquido)
    {
        $this->valorLiquido = $valorLiquido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdOperacoesRealizadasTef()
    {
        return $this->idOperacoesRealizadasTef;
    }

    /**
     * @param mixed $idOperacoesRealizadasTef
     * @return LinhaTef
     */
    public function setIdOperacoesRealizadasTef($idOperacoesRealizadasTef)
    {
        $this->idOperacoesRealizadasTef = $idOperacoesRealizadasTef;
        return $this;
    }

    /**
     * @return bool $consistente
     */
    public function isConsistente()
    {
        return $this->consistente;
    }

    /**
     * @param bool $consistente
     * @return LinhaTef
     */
    public function setConsistente($consistente)
    {
        $this->consistente = $consistente;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('id', $state)) {
            $self->setId($state['id']);
        }
        if (array_key_exists('numero_autorizacao', $state)) {
            $self->setNumeroAutorizacao($state['numero_autorizacao']);
        }
        if (array_key_exists('numero_cv', $state)) {
            $self->setNumeroCv($state['numero_cv']);
        }
        if (array_key_exists('cartao', $state)) {
            $self->setCartao($state['cartao']);
        }
        if (array_key_exists('data_venda', $state)) {
            $self->setDataVenda($state['data_venda']);
        }
        if (array_key_exists('data_vencimento', $state)) {
            $self->setDataVencimento($state['data_vencimento']);
        }
        if (array_key_exists('parcela', $state)) {
            $self->setParcela($state['parcela']);
        }
        if (array_key_exists('total_parcelas', $state)) {
            $self->setTotalParcelas($state['total_parcelas']);
        }
        if (array_key_exists('valor_original', $state)) {
            $self->setValorOriginal($state['valor_original']);
        }
        if (array_key_exists('valor_bruto', $state)) {
            $self->setValorBruto($state['valor_bruto']);
        }
        if (array_key_exists('valor_descontos', $state)) {
            $self->setValorDescontos($state['valor_descontos']);
        }
        if (array_key_exists('valor_liquido', $state)) {
            $self->setValorLiquido($state['valor_liquido']);
        }
        if (array_key_exists('operacoesrealizadastef_id', $state)) {
            $self->setIdOperacoesRealizadasTef($state['operacoesrealizadastef_id']);
        }
        if (array_key_exists('consistente', $state)) {
            $self->setConsistente($state['consistente']);
        }

        return $self;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'numero_autorizacao' => $this->getNumeroAutorizacao(),
            'numero_cv' => $this->getNumeroCv(),
            'cartao' => $this->getCartao(),
            'data_venda' => $this->getDataVenda(),
            'data_vencimento' => $this->getDataVencimento(),
            'parcela' => $this->getParcela(),
            'total_parcelas' => $this->getTotalParcelas(),
            'valor_original' => $this->getValorOriginal(),
            'valor_bruto' => $this->getValorBruto(),
            'valor_descontos' => $this->getValorDescontos(),
            'valor_liquido' => $this->getValorLiquido(),
            'operacoesrealizadastef_id' => $this->getIdOperacoesRealizadasTef(),
            'consistente' => $this->isConsistente()
        ];
    }
}
