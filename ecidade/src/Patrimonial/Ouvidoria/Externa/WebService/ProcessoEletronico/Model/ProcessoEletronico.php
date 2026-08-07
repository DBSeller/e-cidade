<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2020  DBSeller Servicos de Informatica
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

namespace ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico\Model;

use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;

use \UsuarioSistemaModel;
use \Departamento;
use \Instituicao;

class ProcessoEletronico
{
    const SEQ = 1;
    const CODIGO_USUARIO = 1;
    const FORMA_RECLAMACAO_PROCESSO_ELETRONICO = 5;

  /**
   * @var Requerente
   */
    protected $requerente;

  /**
   * @var String
   */
    protected $solicitacaoJSON;

  /**
   * @var TipoProcesso
   */
    protected $tipoProcesso;

  /**
   * @var UsuarioSistemaModel
   */
    protected $usuario;

  /**
   * @var Departamento
   */
    protected $departamento;

  /**
   * @var Instituicao
   */
    protected $instituicao;

    /**
     * @var CodigoAtendimento
     */
    protected $codigoAtendimento;

    /**
     * @var codigoAtendimentoAnterior
     */
    protected $codigoAtendimentoAnterior;

    protected $clientAPPAtendimentoID;

  /**
   * @param Requerente $requerente
   */
    public function setRequerente($requerente)
    {
        $this->requerente = $requerente;
    }

  /**
   * @return Requerente
   */
    public function getRequerente()
    {
        return $this->requerente;
    }

  /**
   * @param Json $solicitacaoJSON
   */
    public function setSolicitacaoJSON($solicitacaoJSON)
    {
        $this->solicitacaoJSON = $solicitacaoJSON;
    }

  /**
   * @return Json
   */
    public function getSolicitacaoJSON()
    {
        return $this->solicitacaoJSON;
    }

  /**
   * @param TipoProcesso $tipoProcesso
   */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

  /**
   * @return TipoProcesso
   */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

  /**
   * @param UsuarioSistemaModel $usuario
   */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

  /**
   * @return UsuarioSistemaModel
   */
    public function getUsuario()
    {
        return $this->usuario;
    }

  /**
   * @param Departamento $departamento
   */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

  /**
   * @return Departamento
   */
    public function getDepartamento()
    {
        return $this->departamento;
    }

  /**
   * @param Instituicao $instituicao
   */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

  /**
   * @return Instituicao
   */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @return CodigoAtendimentoAnterior
     */
    public function getCodigoAtendimentoAnterior()
    {
        return $this->codigoAtendimentoAnterior;
    }

    /**
     * @param CodigoAtendimentoAnterior $codigoAtendimentoAnterior
     */
    public function setCodigoAtendimentoAnterior($codigoAtendimentoAnterior)
    {
        $this->codigoAtendimentoAnterior = $codigoAtendimentoAnterior;
    }

    /**
     * @return mixed
     */
    public function getClientAPPAtendimentoID()
    {
        return $this->clientAPPAtendimentoID;
    }

    /**
     * @param mixed $clientAPPAtendimentoID
     */
    public function setClientAPPAtendimentoID($clientAPPAtendimentoID)
    {
        $this->clientAPPAtendimentoID = $clientAPPAtendimentoID;
    }
}
