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

namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\DiarioAlunoRegistry;
use Exception;

/**
 * Class DiarioAreaResultadoFinal
 * @package ECidade\Educacao\Escola\Model
 */
class DiarioAlunoResultadoFinal
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var DiarioAluno
     */
    private $diarioAluno;
    /**
     * @var string
     */
    private $resultadoFinal;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DiarioAlunoResultadoFinal
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return DiarioAluno
     */
    public function getDiarioAluno()
    {
        return $this->diarioAluno;
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return DiarioAlunoResultadoFinal
     */
    public function setDiarioAluno(DiarioAluno $diarioAluno)
    {
        $this->diarioAluno = $diarioAluno;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultadoFinal()
    {
        return $this->resultadoFinal;
    }

    /**
     * @param string $resultadoFinal
     * @return DiarioAlunoResultadoFinal
     */
    public function setResultadoFinal($resultadoFinal)
    {
        $this->resultadoFinal = $resultadoFinal;
        return $this;
    }

    /**
     * @param array $state
     * @return DiarioAlunoResultadoFinal
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed165_codigo', $state)) {
            $self->setCodigo($state['ed165_codigo']);
        }
        if (array_key_exists('ed165_diarioaluno', $state)) {
            $self->setDiarioAluno(DiarioAlunoRegistry::get($state['ed165_diarioaluno']));
        }
        if (array_key_exists('ed165_resultado_final', $state)) {
            $self->setResultadoFinal($state['ed165_resultado_final']);
        }

        return $self;
    }
}
