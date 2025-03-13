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

use ECidade\Educacao\Escola\Registry\CensoUfRegistry;

class CensoUf
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $sigla;
    /**
     * @var string
     */
    private $nome;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return CensoUf
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return CensoUf
     */
    public function setSigla($sigla)
    {
        $this->sigla = strtoupper($sigla);
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return CensoUf
     */
    public function setNome($nome)
    {
        $this->nome = trim($nome);
        return $this;
    }

    /**
     * @param array $state
     * @return CensoUf
     */
    public static function fromState($state = array())
    {
        $self = new self();
        if (array_key_exists('ed260_i_codigo', $state)) {
            $self->setCodigo($state['ed260_i_codigo']);
        }
        if (array_key_exists('ed260_c_sigla', $state)) {
            $self->setSigla($state['ed260_c_sigla']);
        }
        if (array_key_exists('ed260_c_nome', $state)) {
            $self->setNome($state['ed260_c_nome']);
        }

        CensoUfRegistry::set($self);

        return $self;
    }
}
