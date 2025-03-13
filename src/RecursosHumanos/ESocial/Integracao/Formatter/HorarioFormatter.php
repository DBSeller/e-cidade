<?php
namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Formatter responsável por formatar os dados de horários.
 *
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 */
class HorarioFormatter extends Formatter
{
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $dadosFormatado = parent::formatar($dados);
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
        foreach ($dadosFormatado as $dados) {
            if (empty($dados->ideHorContratual->fimValid)) {
                $dados->ideHorContratual->fimValid = null;
            }

            if (!empty($dados->dadosHorContratual->hrEntr)) {
                $dados->dadosHorContratual->hrEntr = $this->formatTime($dados->dadosHorContratual->hrEntr);
            }

            if (!empty($dados->dadosHorContratual->hrSaida)) {
                $dados->dadosHorContratual->hrSaida = $this->formatTime($dados->dadosHorContratual->hrSaida);
            }

            if (!empty($dados->dadosHorContratual->horarioIntervalo)) {
                foreach ($dados->dadosHorContratual->horarioIntervalo as $horarioIntervalo) {
                    if (!empty($horarioIntervalo->iniInterv)) {
                        $horarioIntervalo->iniInterv = $this->formatTime($horarioIntervalo->iniInterv);
                    } else {
                        unset($horarioIntervalo->iniInterv);
                    }

                    if (!empty($horarioIntervalo->termInterv)) {
                        $horarioIntervalo->termInterv = $this->formatTime($horarioIntervalo->termInterv);
                    } else {
                        unset($horarioIntervalo->termInterv);
                    }
                }
            }
        }

        return $dadosFormatado;
    }

    /**
     * Formata o horário de acordo com a API (HHMM).
     *
     * @param string $time
     *
     * @return string
     */
    private function formatTime($time)
    {
        return date('Hi', strtotime($time));
    }
}
