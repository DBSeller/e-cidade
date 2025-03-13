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

namespace ECidade\Tributario\Divida\Certidao;

/**
 * Entidade que modela a tabela certdiv do banco de dados.
 *
 * @author Matheus.lino <matheus.lino@dbseller.com.br>
 */
class ListaCDA
{
    /**
     * @var Integer
     */
    private $codigo;

    /**
     * @var Integer
     */
    private $lista;

    /**
     * @var Integer
     */
    private $certidao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return ListaCDA
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getLista()
    {
        return $this->lista;
    }

    /**
     * @param int $lista
     * @return ListaCDA
     */
    public function setLista($lista)
    {
        $this->lista = $lista;
        return $this;
    }

    /**
     * @return int
     */
    public function getCertidao()
    {
        return $this->certidao;
    }

    /**
     * @param int $certidao
     * @return ListaCDA
     */
    public function setCertidao($certidao)
    {
        $this->certidao = $certidao;
        return $this;
    }

    /**
     * @param  $state
     * @return ListaCDA
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v81_sequencial', $state)) {
            $self->setCodigo($state['v81_sequencial']);
        }

        if (array_key_exists('v81_lista', $state)) {
            $self->setLista($state['v81_lista']);
        }

        if (array_key_exists('v81_certid', $state)) {
            $self->setCertidao($state['v81_certid']);
        }

        return $self;
    }
}
