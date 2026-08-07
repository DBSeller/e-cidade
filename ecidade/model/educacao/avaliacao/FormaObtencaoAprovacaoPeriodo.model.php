<?php

require_once(modification("model/educacao/avaliacao/FormaObtencao.model.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));

class FormaObtencaoAprovacaoPeriodo extends FormaObtencao implements IFormaObtencao
{
    /**
     * Define as notas que ira ser usado no calculo
     * Deverá ser instancias de AvaliacaoAproveitamento
     * @param $aAproveitamentos
     * @param $iAno
     * @return ValorAproveitamentoNota
     */
    public function processarResultado($aAproveitamentos, $iAno)
    {
        /**
         * Verificamos a menor nota entre os Aproveitamentos
         */
        $mAproveitamento = new ValorAproveitamentoNota('');
        $aNotasPeriodos = $this->getElementosParaCalculo($aAproveitamentos, $iAno);

        $aElementos = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
        foreach ($aNotasPeriodos as $oNotaDoAproveitamento) {
            if ($oNotaDoAproveitamento->isAmparado()) {
                continue;
            }

            $oElemento = $aElementos[$oNotaDoAproveitamento->getOrdemSequencia()];
            $nAproveitamentoPeriodo = $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento();
            if (!$this->isCalculoNotaParcial() && $oElemento->isObrigatorio() && $nAproveitamentoPeriodo === "") {
                $mAproveitamento = new ValorAproveitamentoNota('');
                break;
            }

            if ($oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento() < $mAproveitamento->getAproveitamento() ||
                $mAproveitamento->getAproveitamento() == '') {
                $mAproveitamento = $oNotaDoAproveitamento->getValorAproveitamento();
            }
        }

        $mAproveitamento = ArredondamentoNota::arredondar($mAproveitamento, $iAno);

        /**
         * Devolvemos as notas Originais
         */
        $this->acertaNotasSubstituidasParaCalculo($aNotasPeriodos);
        return $mAproveitamento;
    }
}
