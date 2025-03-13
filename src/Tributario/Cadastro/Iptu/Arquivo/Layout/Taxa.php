<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class Taxa extends Layout
{
    /**
     * @var integer|null
     *
     * Variável com o codigo da receita
     */
    private $codigoReceita;

    /**
     * @var string|null
     *
     * Variável com a descricao da receita
     */
    private $descricaoReceita;

    /**
     * Construtor de classe
     */ 
    public function __construct ($codigoReceita = " ", $descricaoReceita = " ")
    {
        if(empty($codigoReceita)) {
            throw new BusinessException("Informe o codigo da receita.");
        }

        if(empty($descricaoReceita)) {
            throw new BusinessException("Informe a descricao da receita.");
        }

        $this->codigoReceita    = $codigoReceita;
        $this->descricaoReceita = $descricaoReceita;
        
        $this->fields = array(
            'VENCIMENTOPARCELA' => array(
                'name'           => 'DESCR{$descricaoReceita}{$codigoReceita}'
                ,'description'   => 'DESCRICAO {$descricaoReceita}'
                ,'size'           => 40
            )
            ,'VALORPARCELA' => array(
                'name'           => 'QUANT{$descricaoReceita}{$codigoReceita}'
                ,'description'   => 'QUANTIDADE {$descricaoReceita}'
                ,'size'           => 10
            )
            ,'VALORJUROPARCELA' => array(
                'name'           => 'VAL{$descricaoReceita}{$codigoReceita}'
                ,'description'   => 'VALOR {$descricaoReceita}'
                ,'size'           => 18
            )
            ,'VALORMULTAPARCELA' => array(
                'name'           => 'VALPARC{$descricaoReceita}{$codigoReceita}'
                ,'description'   => 'VALOR {$descricaoReceita} PARA CADA PARCELA'
                ,'size'           => 18
            )
        );
    }

    /**
     * @param integer $counter
     * @return string
     *
     * Retorna o layout
     */
    public function get($counter)
    {
        if(empty($counter)) {
            throw new BusinessException('Informe contador de inicio.'. get_class());
        }
        
        if($this->getStart() === null) {
            throw new BusinessException('Informe o início dos blocos.'. get_class());
        }

        $start = $this->getStart();

        $codigoReceita    = $this->codigoReceita;
        $descricaoReceita = $this->descricaoReceita;

        $searchVariable = function ($item) use ($codigoReceita, $descricaoReceita) {

            if(strpos($item, '{') > 0 )  return $item;

            switch($item) {
                case ('{$codigoReceita}'):
                    return str_pad($codigoReceita, 3, '0', STR_PAD_LEFT);
                case ('{$descricaoReceita}'):
                    return $descricaoReceita;
            }
        };

        $r = $this->build($counter, $start, $searchVariable);

        $this->setLast($r->counter);
        $this->setEnd($r->end);

        return $r->layout;
    }
}
