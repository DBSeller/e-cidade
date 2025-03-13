<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

class LayoutParcelasReceitas extends LayoutParcelas
{
    /**
     * @var
     * 
     * Array de receitas
     */
    protected $receitas;

    /**
     * Construtor de classe
     */ 
    public function __construct ($parcelas, array $receitas)
    {
        $this->receitas = $receitas;
        parent::__construct($parcelas);
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

        $l          = '';
        $nroParcela = 0;
        $start      = $this->getStart();

        for($nroParcela = 1; $nroParcela <= $this->parcelas; $nroParcela++) {

            foreach($this->receitas as $codigoReceita) {

                $searchVariable = function ($item) use ($nroParcela, $codigoReceita) {

                    if(strpos($item, '{') > 0 )  return $item;

                    switch($item) {
                        case ('{$nroParcela}'):
                            return str_pad($nroParcela, 3, 0, STR_PAD_LEFT) ;
                        case ('{$codigoReceita}'):
                            return str_pad($codigoReceita, 3, 0, STR_PAD_LEFT) ;
                    }
                };

                $r        = $this->build($counter, $start, $searchVariable);
                $l       .= $r->layout;
                $counter  = $r->counter;
                $end      = $r->end;
                $start    = $end;
                $start++;
            }
        }

        $this->setLast($counter);
        $this->setEnd($end);

        return $l;
    }
}
