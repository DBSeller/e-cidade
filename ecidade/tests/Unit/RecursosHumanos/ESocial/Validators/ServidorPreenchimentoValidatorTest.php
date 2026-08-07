<?php

namespace Tests\Unit\RecursosHumanos\ESocial\Validators;

use ECidade\RecursosHumanos\ESocial\Validators\ServidorPreenchimentoValidator;
use Tests\TestCase;

class ServidorPreenchimentoValidatorTest extends TestCase
{
    public function testCpfObrigatorioQuandoIrrfPreenchidoTemErro()
    {
        $validador = new ServidorPreenchimentoValidator();

        $perguntas = [
            'codPergunta1' => [
                (object)['identificador' => 'cpfDep_1', 'valor' => null],
            ],
            'codPergunta2' => [
                (object)[
                    'identificador' => 'depIRRF_1',
                    'valor' => 1,
                    'identificador_opcao' => 'dependente_1_depIRRF_S'
                ],
            ]
        ];

        $validador->setPerguntas($perguntas);

        $validador->validar();

        $this->assertTrue($validador->temErros());
    }

    public function testCpfObrigatorioQuandoIrrfPreenchidoNaoTemErro()
    {
        $validador = new ServidorPreenchimentoValidator();

        $perguntas = [
            'codPergunta1' => [
                (object)['identificador' => 'cpfDep_1', 'valor' => '36831826016'],
            ],
            'codPergunta2' => [
                (object)[
                    'identificador' => 'depIRRF_1',
                    'valor' => 1,
                    'identificador_opcao' => 'dependente_1_depIRRF_S'
                ],
            ]
        ];

        $validador->setPerguntas($perguntas);

        $validador->validar();

        $this->assertFalse($validador->temErros());
    }
}
