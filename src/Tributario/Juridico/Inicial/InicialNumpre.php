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

namespace ECidade\Tributario\Juridico\Inicial;

/**
 * Class InicialNumpre
 * @package ECidade\Tributario\Juridico\Inicial
 */
class InicialNumpre
{
    /**
     * @var int
     */
    private $inicial;

    /**
     * @var int
     */
    private $numpre;

    public static function fromState($state)
    {
        $inicialNumpre = new self();

        if (array_key_exists('v59_inicial', $state)) {
            $inicialNumpre->setInicial($state['v59_inicial']);
        }

        if (array_key_exists('v59_numpre', $state)) {
            $inicialNumpre->setNumpre($state['v59_numpre']);
        }

        return $inicialNumpre;
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
     */
    public function setInicial($inicial)
    {
        $this->inicial = (int)$inicial;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     */
    public function setNumpre($numpre)
    {
        $this->numpre = (int)$numpre;
    }
}
