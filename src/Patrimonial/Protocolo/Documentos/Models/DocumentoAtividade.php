<?php

namespace ECidade\Patrimonial\Protocolo\Documentos\Models;

use App\Domain\Patrimonial\Protocolo\Model\AtividadeExecucao;
use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;

class DocumentoAtividade
{
    /**
     * @var integer
     */
    private $p118_codigo;
    /**
     * @var Processo
     */
    private $p118_protprocesso;
    /**
     * @var AtividadeExecucao
     */
    private $p118_atividadesexecucao;
    /**
     * @var integer
     */
    private $p118_ordem;

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->p118_codigo;
    }

    /**
     * @param integer $p118_codigo
     */
    public function setCodigo($p118_codigo)
    {
        $this->p118_codigo = $p118_codigo;
    }

    /**
     * @return Processo
     */
    public function getProcesso()
    {
        return $this->p118_protprocesso;
    }

    /**
     * @param Processo $p118_protprocesso
     */
    public function setProcesso(Processo $p118_protprocesso)
    {
        $this->p118_protprocesso = $p118_protprocesso;
    }

    /**
     * @return AtividadeExecucao
     */
    public function getAtividadeExecucao()
    {
        return $this->p118_atividadesexecucao;
    }

    /**
     * @param AtividadeExecucao $p118_atividadesexecucao
     */
    public function setAtividadeExecucao($p118_atividadesexecucao)
    {
        $this->p118_atividadesexecucao = $p118_atividadesexecucao;
    }

    /**
     * @return integer
     */
    public function getOrdem()
    {
        return $this->p118_ordem;
    }

    /**
     * @param integer $p118_ordem
     */
    public function setOrdem($p118_ordem)
    {
        $this->p118_ordem = $p118_ordem;
    }
}
