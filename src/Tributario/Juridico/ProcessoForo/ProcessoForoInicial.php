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

namespace ECidade\Tributario\Juridico\ProcessoForo;

/**
 * Class ProcessoForoInicial
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class ProcessoForoInicial
{
    /** @var integer sequencial */
    private $sequencial;

    /** @var integer id_usuario */
    private $usuario;

    /** @var integer inicial */
    private $inicial;

    /** @var integer $processoforo */
    private $processoForo;

    /** @var \DateTime data */
    private $data;

    /** @var bool anulado */
    private $anulado;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return ProcessoForoInicial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int $usuario
     * @return ProcessoForoInicial
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @param int $inicial
     * @return ProcessoForoInicial
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcessoForo()
    {
        return $this->processoForo;
    }

    /**
     * @param int $processoForo
     * @return ProcessoForoInicial
     */
    public function setProcessoForo($processoForo)
    {
        $this->processoForo = $processoForo;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     * @return ProcessoForoInicial
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAnulado()
    {
        return $this->anulado;
    }

    /**
     * @param bool $anulado
     * @return ProcessoForoInicial
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;
        return $this;
    }
}
