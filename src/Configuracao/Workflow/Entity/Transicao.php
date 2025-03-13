<?php

namespace ECidade\Configuracao\Workflow\Entity;

use ECidade\Configuracao\Workflow\Collection\Acoes;
use ECidade\Configuracao\Workflow\Atividade;

class Transicao
{
    
    /**
     * @var Acoes
     */
    private $acoes;
    
    /**
     * @var Atividade
     */
    private $atividadeOrigem;
    
    /**
     * @var Atividade
     */
    private $atividadeDestino;
    
    /**
     * @var string | null
     */
    private $resultado;

    /**
     * @var object | null
     */
    private $artefatos;
    
    /**
     * @var string | null
     */
    private $erro;

    /**
     * @param Acoes
     */
    public function addAcao($acao)
    {
        if (empty($this->acoes)) {
            return;
        }
        
        $this->acoes->add($acao);
    }
    
    /**
     * @param Acoes
     */
    public function setAcoes($acoes)
    {
        $this->acoes = $acoes;
    }

    /**
     * @return Acoes
     */
    public function getAcoes()
    {
        return $this->acoes;
    }

    /**
     * @return Atividade
     */
    public function getAtividadeOrigem()
    {
        return $this->atividadeOrigem;
    }

    /**
     * @return Atividade
     */
    public function getAtividadeDestino()
    {
        return $this->atividadeDestino;
    }

    /**
     * @return string | null
     */
    public function getResultado()
    {
        return $this->resultado;
    }
    
    /**
     * @return object | null
     */
    public function getArtefatos()
    {
        return $this->artefatos;
    }

    /**
     * @return string | null
     */
    public function getErro()
    {
        return $this->erro;
    }

    public function run()
    {
        $resultados  = array();
        $artefatos   = array();
        $erros       = array();
        
        if (!empty($acoes)) {
            foreach ($acoes as $acao) {
                try {
                    $acao->validate();
                    $resultados[] = $acao->run();
                    $artefatos [] = $acao->getArtefato();
                } catch (Exception $e) {
                    $erros[] = $e->getMessage();
                    break;
                }
            }
        }

        if (!empty($resultados)) {
            $this->resultado = implode("\n", $resultados);
        }

        if (!empty($erros)) {
            $this->erro = implode("\n", $erros);
            return false;
        }

        return true;
    }
}
