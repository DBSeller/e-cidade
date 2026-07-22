<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\ColunaEstruturalRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use Exception;

/**
 * Class ColunaEstrutural
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class ColunaEstrutural
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var bool
     */
    private $exclusao;
    /**
     * @var string
     */
    private $estrutural;
    /**
     * @var Coluna
     */
    private $coluna;
    /**
     * @var int
     */
    private $ano;

    /**
     * @param array $state
     * @return ColunaEstrutural
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o158_sequencial', $state)) {
            $self->setSequencial($state['o158_sequencial']);
        }

        if (array_key_exists('o158_exclusao', $state)) {
            $self->setExclusao($state['o158_exclusao'] === 't');
        }

        if (array_key_exists('o158_estrutural', $state)) {
            $self->setEstrutural($state['o158_estrutural']);
        }

        if (array_key_exists('o158_orcparamseqcoluna', $state)) {
            $self->setColuna(ColunaRegistry::get($state['o158_orcparamseqcoluna']));
        }

        if (array_key_exists('o158_ano', $state)) {
            $self->setAno($state['o158_ano']);
        }

        ColunaEstruturalRegistry::set($self);

        return $self;
    }

    /**
     * @param bool $deep
     * @return array
     */
    public function toArray($deep = false)
    {
        $dados = array(
            'sequencial' => $this->getSequencial(),
            'exclusao' => $this->isExclusao(),
            'estrutural' => $this->getEstrutural(),
            'codigo_coluna' => $this->getColuna()->getSequencial(),
            'ano' => $this->getAno(),
        );

        if ($deep) {
            $dados['coluna'] = $this->getColuna()->toArray();
        }

        return $dados;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return bool
     */
    public function isExclusao()
    {
        return (bool)$this->exclusao;
    }

    /**
     * @param bool $exclusao
     */
    public function setExclusao($exclusao)
    {
        $this->exclusao = (bool)$exclusao;
    }

    /**
     * @return string
     */
    public function getEstrutural()
    {
        return (string)$this->estrutural;
    }

    /**
     * @param string $estrutural
     */
    public function setEstrutural($estrutural)
    {
        $this->estrutural = (string)$estrutural;
    }

    /**
     * @return Coluna
     */
    public function getColuna()
    {
        return $this->coluna;
    }

    /**
     * @param Coluna $coluna
     */
    public function setColuna(Coluna $coluna)
    {
        $this->coluna = $coluna;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }
}
