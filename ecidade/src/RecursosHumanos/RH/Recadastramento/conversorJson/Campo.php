<?php


namespace ECidade\RecursosHumanos\RH\Recadastramento\conversorJson;

class Campo
{
      private $nome;
      private $tipo;
      private $resposta;

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
     * @return mixed
     */
    public function getResposta()
    {
        return $this->resposta;
    }

    /**
     * @param mixed $resposta
     */
    public function setResposta($resposta)
    {
        $this->resposta = $resposta;
    }
}
