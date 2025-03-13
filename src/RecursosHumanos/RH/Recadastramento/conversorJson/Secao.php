<?php


namespace ECidade\RecursosHumanos\RH\Recadastramento\conversorJson;

class Secao
{
    private $nome;
    private $label;
    private $tipo;
    private $resposta = array();
    private $campos = [];

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getTermo()
    {
        return $this->termo;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


    /**
     * @param array $campos
     */
    public function setCampos(Campo $campo)
    {
        $this->campos[$campo->getNome()] = $campo;
    }

    /**
     * @param $nome
     * @return false|Campo
     */
    public function getCampo($nome)
    {
        if (!empty($this->campos[$nome])) {
            return $this->campos[$nome];
        }
        return false;
    }

    /**
     * @return \stdClass[]
     */
    public function getResposta()
    {
        return $this->resposta;
    }

    /**
     * @param array $resposta
     */
    public function setResposta($resposta)
    {
        $this->resposta = $resposta;
    }
}
