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

use ECidade\Patrimonial\Protocolo\TipoProcesso\Collection\TipoProcesso as TipoProcessoCollection;

/**
 * Class CategoriaTipoProcesso
 * @package ECidade\Patrimonial\Protocolo\TipoProcesso\Model
 */
class CategoriaTipoProcesso
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var TipoProcessoCollection
     */
    private $tiposProcesso;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = empty($sequencial) ? null : $sequencial;
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
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @param TipoProcessoCollection $tipoProcesso
     */
    public function setTiposProcesso(TipoProcessoCollection $tipoProcesso)
    {
        $this->tiposProcesso = $tipoProcesso;
    }

    /**
     * @return TipoProcessoCollection
     */
    public function getTiposProcesso()
    {
        return $this->tiposProcesso;
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
}
