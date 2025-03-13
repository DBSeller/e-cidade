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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Model;

/**
 * Class TagXML
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Model
 */
class TagXML
{
    /**
     * @var string
     */
    private $nome;

    /**
     * Campo identificador da tag pai do elemento
     * @var string
     */
    private $campoPai;

    /**
     * @var boolean
     */
    private $unico = true;

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
     * Retorna a tag pai do elemento
     * @return string
     */
    public function getCampoPai()
    {
        return $this->campoPai;
    }

    /**
     * Define a tag pai do elemento
     * @param string $campoPai
     */
    public function setCampoPai($campoPai)
    {
        $this->campoPai = $campoPai;
    }

    /**
     * @return bool
     */
    public function isUnico()
    {
        return $this->unico;
    }

    /**
     * @param bool $unico
     */
    public function setUnico($unico)
    {
        $this->unico = $unico;
    }
}
