<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

class LayoutParcelas extends Layout
{
    /**
     * @var integer
     *
     * Número de parcelas
     */
    protected $parcelas;

      /**
     * @var integer
     *
     * Soma do tamanho dos atributos size para um micro-bloco
     */
    protected $sizeOfFields;

    /**
     * Construtor de classe
     */ 
    public function __construct ($parcelas)
    {
        if(empty($parcelas)) {
            throw BusinessException('Informe o número de parcelas para o layout');
        }
        
        $this->parcelas = $parcelas;

        foreach($this->fields as $field => $properties) {

            $size = ((int)$properties['size']);
            $this->sizeOfFields += $size;
        }

        parent::__construct();
        $this->length = $this->length * $parcelas;
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
            throw new BusinessException('Informe contador de inicio');
        }
        
        if($this->getStart() === null) {
            throw new BusinessException('Informe o início dos blocos');
        }

        $nroParcela = 0;
        $start      = $this->getStart();
        $l          = '';

        for($nroParcela = 1; $nroParcela <= $this->parcelas; $nroParcela++) {

            $searchVariable = function ($item) use ($nroParcela) {

                if(strpos($item, '{') > 0 )  return $item;

                switch($item) {
                    case ('{$nroParcela}'):
                        return str_pad($nroParcela, 3, 0, STR_PAD_LEFT) ;
                }
            };

            if(!empty($start) && $nroParcela > 1) {
                $start  = $this->sizeOfFields * ($nroParcela-1);
                $start += $this->getStart();
            }

            $r        = $this->build($counter, $start, $searchVariable);
            $l       .= $r->layout;
            $counter  = $r->counter;
            $end      = $r->end;
            $start    = $end;
            $start++;
        }

        $this->setLast($counter);
        $this->setEnd($end);

        return $l;
    }
}
