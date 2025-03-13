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

namespace ECidade\Patrimonial\Ouvidoria\Externa\Model;

use CgmBase;
use DBDate;
use DBDepartamento;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;
use Instituicao;
use UsuarioSistema;

/**
 * Class PreProcesso
 * @package ECidade\Patrimonial\Ouvidoria\Model
 */
class PreProcesso
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * @var UsuarioSistema
     */
    private $usuario;

    /**
     * @var CgmBase
     */
    private $cgm;

    /**
     * @var string
     */
    private $requerente;

    /**
     * @var DBDepartamento
     */
    private $departamento;

    /**
     * @var string
     */
    private $observacao;

    /**
     * @var string
     */
    private $despacho;

    /**
     * @var string
     */
    private $hora;

    /**
     * @var boolean
     */
    private $interno;

    /**
     * @var boolean
     */
    private $publico;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var int
     */
    private $ano;

    /**
     * @var string
     */
    private $metadados;

    /**
     * @var TipoProcesso
     */
    private $tipoProcesso;

    /**
     * @var int
     */
    private $codigoAtendimentoAnterior;

    /**
     * @return TipoProcesso
     */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    /**
     * @param TipoProcesso $tipoProcesso
     */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
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
     * @return UsuarioSistema
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param UsuarioSistema $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return CgmBase
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param CgmBase $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return string
     */
    public function getRequerente()
    {
        return $this->requerente;
    }

    /**
     * @param string $requerente
     */
    public function setRequerente($requerente)
    {
        $this->requerente = $requerente;
    }

    /**
     * @return DBDepartamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param DBDepartamento $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * @return string
     */
    public function getDespacho()
    {
        return $this->despacho;
    }

    /**
     * @param string $despacho
     */
    public function setDespacho($despacho)
    {
        $this->despacho = $despacho;
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
    public function isInterno()
    {
        return $this->interno;
    }

    /**
     * @param bool $interno
     */
    public function setInterno($interno)
    {
        $this->interno = $interno;
    }

    /**
     * @return bool
     */
    public function isPublico()
    {
        return $this->publico;
    }

    /**
     * @param bool $publico
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return string
     */
    public function getMetadados()
    {
        return $this->metadados;
    }

    /**
     * @param string $metadados
     */
    public function setMetadados($metadados)
    {
        $this->metadados = $metadados;
    }

    /**
     * @return int
     */
    public function getCodigoAtendimentoAnterior()
    {
        return $this->codigoAtendimentoAnterior;
    }

    /**
     * @param int $codigoAtendimentoAnterior
     */
    public function setCodigoAtendimentoAnterior($codigoAtendimentoAnterior)
    {
        $this->codigoAtendimentoAnterior = $codigoAtendimentoAnterior;
    }
}
