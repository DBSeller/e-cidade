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

use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

/**
 * Class Coluna
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class Coluna
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $ano;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var int
     */
    private $tipo;
    /**
     * @var string
     */
    private $default;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $formula;
    /**
     * @var int
     */
    private $origem = 0;
    /**
     * @var Relatorio
     */
    private $relatorio;
    /**
     * @var ColunaEstrutural[]
     */
    private $colunaEstruturais = array();

    /**
     * @param array $state
     * @return Coluna
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o115_sequencial', $state)) {
            $self->setSequencial($state['o115_sequencial']);
        }

        if (array_key_exists('o115_anousu', $state)) {
            $self->setAno($state['o115_anousu']);
        }

        if (array_key_exists('o115_descricao', $state)) {
            $self->setDescricao($state['o115_descricao']);
        }

        if (array_key_exists('o115_tipo', $state)) {
            $self->setTipo($state['o115_tipo']);
        }

        if (array_key_exists('o115_valoresdefault', $state)) {
            $self->setDefault($state['o115_valoresdefault']);
        }

        if (array_key_exists('o115_nomecoluna', $state)) {
            $self->setNome($state['o115_nomecoluna']);
        }

        if (array_key_exists('o115_formula', $state)) {
            $self->setFormula($state['o115_formula']);
        }

        if (array_key_exists('o115_origem', $state)) {
            $self->setOrigem($state['o115_origem']);
        }

        if (array_key_exists('o115_relatorio', $state) && !empty($state['o115_relatorio'])) {
            $self->setRelatorio(RelatorioRegistry::get($state['o115_relatorio']));
        }

        ColunaRegistry::set($self);

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $colunas = array();

        foreach ($this->getColunaEstruturais() as $colunaEstrutural) {
            $colunas[] = $colunaEstrutural->toArray();
        }

        return array(
            'sequencial' => $this->getSequencial(),
            'ano' => $this->getAno(),
            'descricao' => $this->getDescricao(),
            'tipo' => $this->getTipo(),
            'default' => $this->getDefault(),
            'nome' => $this->getNome(),
            'formula' => $this->getFormula(),
            'origem' => $this->getOrigem(),
            'relatorio' => $this->getRelatorio() instanceof Relatorio ? $this->getRelatorio()->getSequencial() : null,
            'contas' => $colunas,
        );
    }

    /**
     * @return ColunaEstrutural[]
     */
    public function getColunaEstruturais()
    {
        return $this->colunaEstruturais;
    }

    /**
     * @param ColunaEstrutural[] $colunaEstruturais
     */
    public function setColunaEstruturais(array $colunaEstruturais)
    {
        $this->colunaEstruturais = $colunaEstruturais;
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

    /**
     * @return string
     */
    public function getDescricao()
    {
        return (string)$this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = (string)$descricao;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return (int)$this->tipo;
    }

    /**
     * @param int $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = (int)$tipo;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return (string)$this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = (string)$default;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return (string)$this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = (string)$nome;
    }

    /**
     * @return string
     */
    public function getFormula()
    {
        return (string)$this->formula;
    }

    /**
     * @param string $formula
     */
    public function setFormula($formula)
    {
        $this->formula = (string)$formula;
    }

    /**
     * @return int
     */
    public function getOrigem()
    {
        return (int)$this->origem;
    }

    /**
     * @param int $origem
     */
    public function setOrigem($origem)
    {
        $this->origem = (int)$origem;
    }

    /**
     * @param ColunaEstrutural $colunaEstrutural
     */
    public function addColunaEstrutural(ColunaEstrutural $colunaEstrutural)
    {
        $this->colunaEstruturais[$colunaEstrutural->getSequencial()] = $colunaEstrutural;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
    }
}
