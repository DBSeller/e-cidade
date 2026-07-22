<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;
use Exception;
use RubricaRepository;

/**
 * Formata os dados da Rubrica
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class RubricaFormatter extends Formatter
{
    private $rubricasNaoProcessadas = [];
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $rubricasValidas = [];
        foreach ($dados as $dadosRubrica) {
            foreach ($dadosRubrica->respostas as $dadosRubricaResposta) {
                if (!empty($dadosRubricaResposta->perguntas['codRubr']->resposta->resposta)) {
                    $respostaRubrica = $dadosRubricaResposta->perguntas['codRubr']->resposta->resposta;
                    try {
                        $rubrica = RubricaRepository::getInstanciaByCodigo($respostaRubrica);
                        if ($rubrica->isAtivo() != true || $rubrica->isAtivo() != 't') {
                            continue;
                        }
                        $rubricasValidas[] = $dadosRubrica;
                    } catch (Exception $e) {
                        $this->rubricasNaoProcessadas[$respostaRubrica] = "Rubrica provavelmente excluida do sistema.";
                    }
                }
            }
        }
        $dadosFormatado = parent::formatar($rubricasValidas);
        return $this->posProcessamento($dadosFormatado);
    }

    /**
     * Realiza uma consistencia nos dados enviados
     *
     * @param array  $dadosFormatado
     * @return array
     */
    private function posProcessamento($dadosFormatado)
    {
        $fieldsToArray = array(
            'ideProcessoCP',
            'ideProcessoIRRF',
            'ideProcessoFGTS'
        );

        foreach ($dadosFormatado as $dados) {
            if (!isset($dados->ideRubrica->fimValid) || empty($dados->ideRubrica->fimValid)) {
                $dados->ideRubrica->fimValid = null;
            }

            if (!isset($dados->dadosRubrica->codIncCPRP) || empty($dados->dadosRubrica->codIncCPRP)) {
                $dados->dadosRubrica->codIncCPRP = null;
            }

            if (!isset($dados->dadosRubrica->tetoRemun) || empty($dados->dadosRubrica->tetoRemun)) {
                $dados->dadosRubrica->tetoRemun = null;
            }

            foreach ($fieldsToArray as $field) {
                if (empty($dados->dadosRubrica->{$field})) {
                    continue;
                }

                foreach ($dados->dadosRubrica->{$field} as $valor) {
                    $valorFiltrado = array_filter((array) $valor, function ($item) {
                        if (is_numeric($item) || !empty($item)) {
                            return true;
                        }
                        return false;
                    });

                    $dados->dadosRubrica->{$field} = array((object) $valorFiltrado);

                    if (empty($valorFiltrado)) {
                        $dados->dadosRubrica->{$field} = array();
                    }
                }
            }
        }

        return $dadosFormatado;
    }

    public function getRubricasNaoProcessadas()
    {
        return $this->rubricasNaoProcessadas;
    }
}
