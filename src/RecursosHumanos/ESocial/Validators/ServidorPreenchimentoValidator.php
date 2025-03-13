<?php

namespace ECidade\RecursosHumanos\ESocial\Validators;

class ServidorPreenchimentoValidator extends EsocialPreenchimentoValidator
{
    public function validar()
    {
        $this->validaCpfDependente();
    }

    private function validaCpfDependente()
    {
        // Validar os 10 dependentes
        for ($i = 1; $i <= 10; $i++) {
            $cpf = $this->getPerguntaByIdentificador("cpfDep_{$i}");
            $irrf = $this->getPerguntaByIdentificador("depIRRF_{$i}");

            // Se essas perguntas não existem,
            // pule este dependente
            if (empty($cpf) && empty($irrf)) {
                continue;
            }

            $cpf = $this->getValorPerguntaDescritiva($cpf);
            $irrf = $this->getValorPerguntaObjetiva($irrf);

            if (empty($cpf) && $irrf === "dependente_{$i}_depIRRF_S") {
                $this->log("O CPF do dependente $i deve ser informado!");
            }
        }
    }
}
