<?php

namespace ECidade\RecursosHumanos\RH\Recadastramento\conversorJson;

class Formatter
{
    private $json;
    private $secoes = [];

    public function __construct($json)
    {
        $this->secoes = [];
        $this->json = $json;
        if (!$this->isJson($this->json)) {
            throw new \Exception("Json inválido: ERRO ".json_last_error_msg());
        }

        $this->extrairDados();
    }

    private function isJson($string)
    {
        $this->json = \JSON::create()->parse($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function extrairDados()
    {

        foreach ($this->json->secoes as $secao) {
            $secaoObj = new Secao();
            $secaoObj->setLabel($secao->label);
            $secaoObj->setNome($this->removeEspacoEConverteParaMinusculo($secao->nome));
            $secaoObj->setTipo($this->removeEspacoEConverteParaMinusculo($secao->tipo));
            if ($secaoObj->getTipo() == 'tabela' || $secaoObj->getTipo() == 'anexo') {
                $secaoObj->setResposta($secao->resposta);
            } else {
                foreach ($secao->campos as $campo) {
                    $campoObj = new Campo();
                    $campoObj->setTipo($this->removeEspacoEConverteParaMinusculo($campo->tipo));
                    $campoObj->setNome($this->removeEspacoEConverteParaMinusculo($campo->nome));
                    $campoObj->setResposta($campo->resposta);
                    $secaoObj->setCampos($campoObj);
                }
            }

            $this->setSecoes($secaoObj);
        }
    }

    private function removeEspacoEConverteParaMinusculo($string)
    {
        return strtolower(str_replace(" ", "_", trim($string)));
    }

    /**
     * @return Secao[]
     */
    public function getSecoes()
    {
        return $this->secoes;
    }

    /**
     * @param Secao
     */
    public function setSecoes(Secao $secao)
    {
        $this->secoes[$secao->getNome()] = $secao;
    }

    /**
     * @param $nome
     * @return false| Secao
     */
    public function getSecao($nome)
    {
        if (!empty($this->secoes[$nome])) {
            return $this->secoes[$nome];
        }
        return false;
    }
}
