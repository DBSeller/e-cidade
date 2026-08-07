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

namespace ECidade\Patrimonial\Material\Estoque\Movimentacao;
use cl_matestoqueini;
use cl_matestoqueinimei;
use cl_matestoqueitem;
use MaterialEstoqueAlmoxarifado;
use UsuarioSistema;

abstract class Movimentacao
{
    /**
     * @var MaterialEstoqueAlmoxarifado
     */
    protected $material;

    /**
     * @var UsuarioSistema
     */
    protected $usuario;

    /**
     * @var float
     */
    protected $quantidade;

    /**
     * @var float
     */
    protected $valorUnitario;

    /**
     * @var \DBDate
     */
    protected $data;

    /**
     * @var string
     */
    protected $observacao;

    protected $codigoMatEstoqueIni;
    protected $codigoMatestoqueItem;
    protected $codigoMatEstoqueIniMei;

    protected $tipo;

    public function salvar()
    {
        $daoMatEstoqueIni = new cl_matestoqueini();
        $daoMatEstoqueIni->m80_login = $this->usuario->getCodigo();
        $daoMatEstoqueIni->m80_data = $this->data->getDate();
        $daoMatEstoqueIni->m80_hora = date('H:i:s');
        $daoMatEstoqueIni->m80_obs = $this->observacao;
        $daoMatEstoqueIni->m80_codtipo = $this->tipo; // @todo ver um tipo
        $daoMatEstoqueIni->m80_coddepto = $this->material->getCodigoDepartamento();
        $daoMatEstoqueIni->incluir(null);
        if ($daoMatEstoqueIni->erro_status == 0) {
            throw new DBException("Erro ao incluir movimentação no estoque.\n" . $daoMatEstoqueIni->erro_msg);
        }
        $this->codigoMatEstoqueIni = $daoMatEstoqueIni->m80_codigo;

        $daoMatestoqueItem = new cl_matestoqueitem();
        $daoMatestoqueItem->m71_codmatestoque = $this->material->getCodigo();
        $daoMatestoqueItem->m71_data          = $this->data->getDate();
        $daoMatestoqueItem->m71_valor         = ($this->quantidade * $this->valorUnitario);
        $daoMatestoqueItem->m71_quant         = $this->quantidade;
        $daoMatestoqueItem->m71_quantatend    = '0';
        $daoMatestoqueItem->incluir(null);

        if ($daoMatestoqueItem->erro_status == 0) {
            throw new DBException("Erro ao incluir item no estoque.\n" . $daoMatestoqueItem->erro_msg);
        }
        $this->codigoMatestoqueItem = $daoMatestoqueItem->m71_codlanc;

        $daoMatEstoqueIniMei = new cl_matestoqueinimei();

        $daoMatEstoqueIniMei->m82_matestoqueitem = $this->codigoMatestoqueItem;
        $daoMatEstoqueIniMei->m82_matestoqueini  = $this->codigoMatEstoqueIni;
        $daoMatEstoqueIniMei->m82_quant          = $this->quantidade;
        $daoMatEstoqueIniMei->incluir(null);

        if ($daoMatEstoqueIniMei->erro_status == 0) {
            throw new DBException("Erro ao movimentação no estoque.\n" . $daoMatEstoqueIniMei->erro_msg);
        }

        $this->codigoMatEstoqueIniMei = $daoMatEstoqueIniMei->m82_codigo;
        return true;
    }

    /**
     * @return MaterialEstoqueAlmoxarifado
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param MaterialEstoqueAlmoxarifado $material
     */
    public function setMaterial($material)
    {
        $this->material = $material;
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
     * @return float
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param float $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return float
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    /**
     * @param float $valorUnitario
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;
    }

    /**
     * @return \DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DBDate $data
     */
    public function setData($data)
    {
        $this->data = $data;
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
     * @return mixed
     */
    public function getCodigoMatEstoqueIni()
    {
        return $this->codigoMatEstoqueIni;
    }

    /**
     * @param mixed $codigoMatEstoqueIni
     */
    public function setCodigoMatEstoqueIni($codigoMatEstoqueIni)
    {
        $this->codigoMatEstoqueIni = $codigoMatEstoqueIni;
    }

    /**
     * @return mixed
     */
    public function getCodigoMatestoqueItem()
    {
        return $this->codigoMatestoqueItem;
    }

    /**
     * @param mixed $codigoMatestoqueItem
     */
    public function setCodigoMatestoqueItem($codigoMatestoqueItem)
    {
        $this->codigoMatestoqueItem = $codigoMatestoqueItem;
    }

    /**
     * @return mixed
     */
    public function getCodigoMatEstoqueIniMei()
    {
        return $this->codigoMatEstoqueIniMei;
    }

    /**
     * @param mixed $codigoMatEstoqueIniMei
     */
    public function setCodigoMatEstoqueIniMei($codigoMatEstoqueIniMei)
    {
        $this->codigoMatEstoqueIniMei = $codigoMatEstoqueIniMei;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}