<?php

namespace Ecidade\RecursosHumanos\ESocial\Validators;

use stdClass;

abstract class EsocialPreenchimentoValidator
{
    /**
     * @var stdClass[]
     */
    protected $perguntas = array();

    /**
     * @var string[]
     */
    private $erros = array();

    abstract public function validar();

    /**
     * Diz se foram encontradas inconsistências no preenchimento
     *
     * @return bool
     */
    public function temErros()
    {
        return !empty($this->erros);
    }

    public function formataErros()
    {
        return implode('<br>', $this->erros);
    }

    /**
     * Registra um erro/inconsistência
     *
     * @param string $erro A mensagem de erro
     * @return void
     */
    protected function log($erro)
    {
        $this->erros[] = $erro;
    }

    /**
     * @param string $identificador
     * @return stdClass
     */
    protected function getPerguntaByIdentificador($identificador)
    {
        foreach ($this->perguntas as $pergunta) {
            if ($identificador == $pergunta->identificador) {
                return $pergunta;
            }
        }

        return null;
    }

    /**
     * Retorna o valor da primiera opção com valor não empty
     *
     * @param stdClass $pergunta
     * @return string
     */
    protected function getValorPerguntaDescritiva($pergunta)
    {
        foreach ($pergunta->opcoes as $opcao) {
            if (!empty($opcao->valor)) {
                return $opcao->valor;
            }
        }

        return null;
    }

    /**
     * Retorna o identificador_opcao da primeira opção com valor 1
     *
     * @param stdClass $pergunta
     * @return string
     */
    protected function getValorPerguntaObjetiva($pergunta)
    {
        foreach ($pergunta->opcoes as $opcao) {
            if ($opcao->valor === 1) {
                return $opcao->identificador_opcao;
            }
        }

        return null;
    }

    /**
     * @param   array  $perguntas
     */
    public function setPerguntas(array $perguntas)
    {
        $this->perguntas = array();

        foreach ($perguntas as $codPergunta => $opcoes) {
            $pergunta = new stdClass();
            $pergunta->codPergunta = $codPergunta;
            $pergunta->opcoes = $opcoes;

            $pergunta->identificador = null;
            if (!empty($pergunta->opcoes)) {
                $pergunta->identificador = $pergunta->opcoes[0]->identificador;
            }

            $this->perguntas[] = $pergunta;
        }
    }
}
