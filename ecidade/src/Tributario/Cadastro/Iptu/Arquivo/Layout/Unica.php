<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \DateTime;
use \BusinessException;

final class Unica extends Layout
{
    /**
     * @var integer|null
     *
     * Variável contem o valor de desconto
     */
    private $desconto;

    /**
     * @var DateTime|null
     *
     * Variável contem a data de desconto da unica
     */
    private $vencimento;

    public function __construct ($desconto, $vencimento)
    {
        if(empty($desconto)) {
            throw BusinessException("Informe o valor de desconto para a única.");
        }

        if(empty($vencimento)) {
            throw BusinessException("Informe o dia de desconto para a única.");
        }

        $this->desconto   = $desconto;
        $this->vencimento = $vencimento;

        $this->fields = array(
            'OPERACAOUNICA' => array(
                'name'         => 'OPERUNICA{$desconto}'
                ,'description' => 'OPERACAO/LANCAMENTO DA UNICA DE {$desconto}% DE DESCONTO COM VENCIMENTO EM {$vencimento}'
                ,'size'        => 10
            )
            ,'VENCIMENTOUNICA' => array(
                'name'         => 'VENCUNICA{$desconto}'
                ,'description' => 'VENCIMENTO'
                ,'size'        => 10
            )
            ,'PERCENTUALDESCONTOUNICA' => array(
                'name'         => 'PERCDESCUNICA{$desconto}'
                ,'description' => 'PERCENTUAL DE DESCONTO'
                ,'size'        => 15
            )
            ,'VALORHISTORICOUNICA' => array(
                'name'         => 'VLRHISTUNICA{$desconto}'
                ,'description' => 'VALOR HISTORICO'
                ,'size'        => 15
            )
            ,'VALORCORRIGIDOUNICA' => array(
                'name'         => 'VLRCORUNICA{$desconto}'
                ,'description' => 'VALOR CORRIGIDO'
                ,'size'        => 15
            )
            ,'JUROSUNICA' => array(
                'name'         => 'JURUNICA{$desconto}'
                ,'description' => 'JUROS'
                ,'size'        => 15
            )
            ,'MULTAUNICA' => array(
                'name'         => 'MULUNICA{$desconto}'
                ,'description' => 'MULTA'
                ,'size'        => 15
            )
            ,'DESCONTOUNICA' => array(
                'name'         => 'DESCUNICA{$desconto}'
                ,'description' => 'DESCONTO'
                ,'size'        => 15
            )
            ,'TOTALUNICA' => array(
                'name'         => 'TOTALUNICA{$desconto}'
                ,'description' => 'TOTAL (VALOR CORRIGIDO + JUROS + MULTA)'
                ,'size'        => 15
            )
            ,'TOTALLIQUIDOUNICA' => array(
                'name'         => 'TOTALLIQUNICA{$desconto}'
                ,'description' => 'TOTAL - DESCONTO DE {$desconto}'
                ,'size'        => 15
            )
            ,'CODIGOARRECADACAO' => array(
                'name'         => 'CODARREC{$desconto}'
                ,'description' => 'NUMERO DE ARRECADACAO'
                ,'size'        => 11
            )
            ,'BARRASUNICA' => array(
                'name'         => 'BARRASUNICA{$desconto}'
                ,'description' => 'CODIGO DE BARRAS'
                ,'size'        => 101
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

        $desconto   = $this->desconto;
        $vencimento = $this->vencimento;

        $searchVariable = function ($item) use ($desconto, $vencimento) {

            if(strpos($item, '{') > 0 )  return $item;

            switch($item) {
                case ('{$desconto}'):
                    return $desconto;
                case ('{$vencimento}'):
                    return $vencimento->format('d/m/Y');
            }
        };

        $r = $this->build($counter, $start, $searchVariable);

        $this->setLast($r->counter);
        $this->setEnd($r->end);

        return $r->layout;
    }
}
