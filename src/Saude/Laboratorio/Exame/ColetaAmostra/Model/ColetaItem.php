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

namespace ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Model;

use DBDate;
use RequisicaoExame;
use UsuarioSistema;

/**
 * Class ColetaItem
 * @package ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Model
 */
class ColetaItem
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var UsuarioSistema
     */
    private $usuarioSistema;

    /**
     * @var RequisicaoExame
     */
    private $requisicaoExame;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * @var string
     */
    private $hora;

    /**
     * @var bool
     */
    private $avisaPaciente = false;

    /**
     * @var string
     */
    private $horaEntrega;

    /**
     * @var DBDate
     */
    private $dataEntrega;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return UsuarioSistema
     */
    public function getUsuarioSistema()
    {
        return $this->usuarioSistema;
    }

    /**
     * @param UsuarioSistema $usuarioSistema
     */
    public function setUsuarioSistema($usuarioSistema)
    {
        $this->usuarioSistema = $usuarioSistema;
    }

    /**
     * @return RequisicaoExame
     */
    public function getRequisicaoExame()
    {
        return $this->requisicaoExame;
    }

    /**
     * @param RequisicaoExame $requisicaoExame
     */
    public function setRequisicaoExame($requisicaoExame)
    {
        $this->requisicaoExame = $requisicaoExame;
    }

    /**
     * @return DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DBDate $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param string $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return bool
     */
    public function isAvisaPaciente()
    {
        return $this->avisaPaciente;
    }

    /**
     * @param bool $avisaPaciente
     */
    public function setAvisaPaciente($avisaPaciente)
    {
        $this->avisaPaciente = $avisaPaciente;
    }

    /**
     * @return string
     */
    public function getHoraEntrega()
    {
        return $this->horaEntrega;
    }

    /**
     * @param string $horaEntrega
     */
    public function setHoraEntrega($horaEntrega)
    {
        $this->horaEntrega = $horaEntrega;
    }

    /**
     * @return DBDate
     */
    public function getDataEntrega()
    {
        return $this->dataEntrega;
    }

    /**
     * @param DBDate $dataEntrega
     */
    public function setDataEntrega($dataEntrega)
    {
        $this->dataEntrega = $dataEntrega;
    }
}
