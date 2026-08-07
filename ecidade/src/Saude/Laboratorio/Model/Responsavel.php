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

namespace ECidade\Saude\Laboratorio\Model;

use CgmFisico;
use CgmJuridico;
use Laboratorio;

/**
 * Class Responsavel
 * @package ECidade\Saude\Laboratorio\Model
 */
class Responsavel
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var CgmFisico|CgmJuridico
     */
    private $cgm;

    /**
     * @var string
     */
    private $orgaoClasse;

    /**
     * @var Laboratorio
     */
    private $laboratorio;

    /**
     * @return CgmFisico|CgmJuridico
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param CgmFisico|CgmJuridico $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return string
     */
    public function getOrgaoClasse()
    {
        return $this->orgaoClasse;
    }

    /**
     * @param string $orgaoClasse
     */
    public function setOrgaoClasse($orgaoClasse)
    {
        $this->orgaoClasse = $orgaoClasse;
    }

    /**
     * @return Laboratorio
     */
    public function getLaboratorio()
    {
        return $this->laboratorio;
    }

    /**
     * @param Laboratorio $laboratorio
     */
    public function setLaboratorio($laboratorio)
    {
        $this->laboratorio = $laboratorio;
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
}
