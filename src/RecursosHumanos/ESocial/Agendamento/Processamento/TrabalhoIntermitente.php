<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class TrabalhoIntermitente extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    public function processar()
    {
        $bAlteracao = false;
        $oDadosESocial = new DadosESocial();
        $oDadosESocial->setCgmEmpregador($this->cgm);

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::TRABALHO_INTERMITENTE);
        $oFormatter = FormatterFactory::get(Tipo::S2260);
        $aDadosPreenchimento = $oFormatter->formatar($oDadosPreenchimento);

        foreach ($aDadosPreenchimento as $iIndice => $oDados) {
            $oEvento = new Evento(Tipo::S2260, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila()) {
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }

    public function getCgm()
    {
        return $this->cgm;
    }

    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }
}
