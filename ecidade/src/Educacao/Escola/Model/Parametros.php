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

use Escola;
use EscolaRepository;

/**
 * Class Parametros
 * @package ECidade\Educacao\Escola\Model
 */
class Parametros
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var Escola
     */
    private $escola;

    /**
     * Dia/mês
     * @var string
     */
    private $dataBaseCalculoIdade;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Parametros
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return Escola
     */
    public function getEscola()
    {
        return $this->escola;
    }

    /**
     * @param Escola $escola
     * @return Parametros
     */
    public function setEscola($escola)
    {
        $this->escola = $escola;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataBaseCalculoIdade()
    {
        return $this->dataBaseCalculoIdade;
    }

    /**
     * @param string $dataBaseCalculoIdade
     * @return Parametros
     */
    public function setDataBaseCalculoIdade($dataBaseCalculoIdade)
    {
        $this->dataBaseCalculoIdade = $dataBaseCalculoIdade;
        return $this;
    }

    /**
     * @param array $state
     * @return Parametros
     */
    public static function fromState($state)
    {
        $self = new self();

        if (array_key_exists('ed233_i_codigo', $state)) {
            $self->setCodigo($state['ed233_i_codigo']);
        }

        if (array_key_exists('ed233_i_escola', $state)) {
            $self->setEscola(EscolaRepository::getEscolaByCodigo($state['ed233_i_escola']));
        }

        if (array_key_exists('ed233_c_database', $state)) {
            $self->setDataBaseCalculoIdade($state['ed233_c_database']);
        }

        return $self;
    }
}
