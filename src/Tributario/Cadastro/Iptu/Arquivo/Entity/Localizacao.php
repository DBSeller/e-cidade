<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class Localizacao extends Entity
{
    const SEQUENCIAL_SETOR_LOCALIZACAO     = 'SEQUENCIALSETORLOCALIZACAO';
    const CODIGO_PROPRIO_SETOR_LOCALIZACAO = 'CODIGOPROPRIOSETORLOCALIZACAO';
    const DESCRICAO_SETOR_LOCALIZACAO      = 'DESCRICAOSETORLOCALIZACAO';
    const QUADRA_LOCALIZACAO               = 'QUADRALOCALIZACAO';
    const LOTE_LOCALIZACAO                 = 'LOTELOCALIZACAO';

    /**
     * @var string|null SEQUENCIAL DO SETOR DE LOCALIZACAO
     */
    private $sequencialSetorLocalizacao = '';
    
    /**
     * @var string|null CODIGO PROPRIO DO SETOR DE LOCALIZACAO
     */
    private $codigoProprioSetorLocalizacao = '';
    
    /**
     * @var string|null DESCRICAO DO SETOR DE LOCALIZACAO
     */
    private $descricaoSetorLocalizacao = '';
    
    /**
     * @var string|null QUADRA DE LOCALIZACAO
     */
    private $quadraLocalizacao = '';
    
    /**
     * @var string|null LOTE DE LOCALIZACAO
     */
    private $loteLocalizacao = '';

    /**
     * @return string|null
     *
     * Retorna o SEQUENCIAL DO SETOR DE LOCALIZACAO
     */
    public function getSequencialSetorLocalizacao()
    {
        return $this->sequencialSetorLocalizacao;
    }
    
    /**
     * @return string|null
     *
     * Retorna o CODIGO PROPRIO DO SETOR DE LOCALIZACAO
     */
    public function getCodigoProprioSetorLocalizacao()
    {
        return $this->codigoProprioSetorLocalizacao;
    }
    
    /**
     * @return string|null
     *
     * Retorna a DESCRICAO DO SETOR DE LOCALIZACAO
     */
    public function getDescricaoSetorLocalizacao()
    {
        return $this->descricaoSetorLocalizacao;
    }
    
    /**
     * @return string|null
     *
     * Retorna a QUADRA DE LOCALIZACAO
     */
    public function getQuadraLocalizacao()
    {
        return $this->quadraLocalizacao;
    }
    
    /**
     * @return string|null
     *
     * Retorna o LOTE DE LOCALIZACAO
     */
    public function getLoteLocalizacao()
    {
        return $this->loteLocalizacao;
    }

    /**
     * @param string|null
     *
     * Define o SEQUENCIAL DO SETOR DE LOCALIZACAO
     */
    public function setSequencialSetorLocalizacao($sequencialSetorLocalizacao)
    {
        $this->sequencialSetorLocalizacao = $sequencialSetorLocalizacao;
        return $this;
    }
    
    /**
     * @param string|null
     *
     * Define o CODIGO PROPRIO DO SETOR DE LOCALIZACAO
     */
    public function setCodigoProprioSetorLocalizacao($codigoProprioSetorLocalizacao)
    {
        $this->codigoProprioSetorLocalizacao = $codigoProprioSetorLocalizacao;
        return $this;
    }
    
    /**
     * @param string|null
     *
     * Define a DESCRICAO DO SETOR DE LOCALIZACAO
     */
    public function setDescricaoSetorLocalizacao($descricaoSetorLocalizacao)
    {
        $this->descricaoSetorLocalizacao = $descricaoSetorLocalizacao;
        return $this;
    }
    
    /**
     * @param string|null
     *
     * Define a QUADRA DE LOCALIZACAO
     */
    public function setQuadraLocalizacao($quadraLocalizacao)
    {
        $this->quadraLocalizacao = $quadraLocalizacao;
        return $this;
    }
    
    /**
     * @param string|null
     *
     * Define o LOTE DE LOCALIZACAO
     */
    public function setLoteLocalizacao($loteLocalizacao)
    {
        $this->loteLocalizacao = $loteLocalizacao;
        return $this;
    }
}
