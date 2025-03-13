<?php
/*
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

namespace ECidade\Financeiro\Orcamento\Model;

/**
 * Class FonteReceita
 * @package ECidade\Financeiro\Orcamento\Model
 */
class FonteReceita
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var integer
     */
    private $exercicio;
    /**
     * @var string
     */
    private $fonte;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $finalidade;
    /**
     * @var bool
     */
    private $analitica = false;

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param integer $codigo
     * @return FonteReceita
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return integer
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param integer $exercicio
     * @return FonteReceita
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return FonteReceita
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalidade()
    {
        return $this->finalidade;
    }

    /**
     * @param string $finalidade
     * @return FonteReceita
     */
    public function setFinalidade($finalidade)
    {
        $this->finalidade = $finalidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getFonte()
    {
        return $this->fonte;
    }

    /**
     * @param string $fonte
     * @return FonteReceita
     */
    public function setFonte($fonte)
    {
        $this->fonte = $fonte;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAnalitica()
    {
        return $this->analitica;
    }

    /**
     * @param bool $analitica
     * @return FonteReceita
     */
    public function setAnalitica($analitica)
    {
        $this->analitica = $analitica;
        return $this;
    }

    /**
     * @param array $dados
     * @return FonteReceita
     */
    public static function fromState(array $dados)
    {
        $self = new self();

        if (array_key_exists('o57_codfon', $dados)) {
            $self->setCodigo($dados['o57_codfon']);
        }
        if (array_key_exists('o57_anousu', $dados)) {
            $self->setExercicio($dados['o57_anousu']);
        }
        if (array_key_exists('o57_fonte', $dados)) {
            $self->setFonte($dados['o57_fonte']);
        }
        if (array_key_exists('o57_descr', $dados)) {
            $self->setDescricao($dados['o57_descr']);
        }
        if (array_key_exists('o57_finali', $dados)) {
            $self->setFinalidade($dados['o57_finali']);
        }
        if (array_key_exists('analitica', $dados)) {
            $self->setAnalitica($dados['analitica'] == 't');
        }

        return $self;
    }
}
