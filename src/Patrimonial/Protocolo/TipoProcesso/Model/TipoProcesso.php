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
namespace ECidade\Patrimonial\Protocolo\TipoProcesso\Model;

/**
 * Class TipoProcesso
 * @package ECidade\Patrimonial\Protocolo\TipoProcesso\Model
 */
class TipoProcesso
{
    const GRUPO_PROTOCOLO = 1;
    const GRUPO_OUVIDORIA = 2;
    const GRUPO_HABITACAO = 3;

    /**
     * @var int
     */
    private $codigo;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var int
     */
    private $codigo_grupo;

    private $codigoInstituicao;

    private $linkSaibaMais;

    private $itemMenu;

    private $mensagem;

    /**
     * @var boolean
     */
    private $identificado = false;

    /**
     * @return mixed
     */
    public function getItemMenu()
    {
        return $this->itemMenu;
    }

    /**
     * @param mixed $itemMenu
     */
    public function setItemMenu($itemMenu)
    {
        $this->itemMenu = $itemMenu;
    }

    /**
     * @return mixed
     */
    public function getLinkSaibaMais()
    {
        return $this->linkSaibaMais;
    }

    /**
     * @param mixed $linkSaibaMais
     */
    public function setLinkSaibaMais($linkSaibaMais)
    {
        $this->linkSaibaMais = $linkSaibaMais;
    }

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
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return int
     */
    public function getCodigoGrupo()
    {
        return $this->codigo_grupo;
    }

    /**
     * @param int $codigo_grupo
     */
    public function setCodigoGrupo($codigo_grupo)
    {
        $this->codigo_grupo = $codigo_grupo;
    }

    /**
     * @return mixed
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * @param mixed $codigoInstituicao
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    /**
     * @return mixed
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param mixed $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * @return bool
     */
    public function isIdentificado()
    {
        return $this->identificado;
    }

    /**
     * @param bool $identificado
     */
    public function setIdentificado($identificado)
    {
        $this->identificado = $identificado;
    }
}
