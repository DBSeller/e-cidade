<?php


namespace App\Domain\Financeiro\Contabilidade\Relatorios;

abstract class Pdf extends \ECidade\Pdf\Pdf
{

    /**
     * Altura que quebra página
     * @var int
     */
    protected $hQuebraPagina = 12;

    protected $fonte = 7;

    protected $wLinhaL = 279;
    protected $wLinhaP = 192;

    protected $hLinha = 4;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);

        $this->init(false);
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', '', 7);
    }

    abstract protected function imprimeCabecalho();

    protected function quebraPagina()
    {
        if ($this->validaQuebraPagina()) {
            $this->imprimeCabecalho();
        }
    }

    protected function validaQuebraPagina()
    {
        if ($this->getAvailHeight() < $this->hQuebraPagina) {
            return true;
        }

        return false;
    }

    /**
     * adiciona negrito na fonte
     */
    public function bold()
    {
        $this->SetFont('', 'B');
    }

    /**
     * tira o negrito da fonte
     */
    public function regular()
    {
        $this->SetFont('', '');
    }
}
