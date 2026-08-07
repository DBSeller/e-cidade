<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model;

use DateTime;

class Hora
{
    const EXTRA50 = 1;
    const EXTRA75 = 2;
    const EXTRA100 = 3;

    /**
     * @var DateTime
     */
    protected $diurna;

    /**
     * @var DateTime
     */
    protected $noturna;

    /**
     * @var int
     */
    protected $tipo;

    /**
     * @return DateTime
     */
    public function getDiurna()
    {
        return $this->diurna;
    }

    /**
     * @param DateTime $diurna
     */
    public function setDiurna($diurna)
    {
        $this->diurna = $diurna;
    }

    /**
     * @return DateTime
     */
    public function getNoturna()
    {
        return $this->noturna;
    }

    /**
     * @param DateTime $noturna
     */
    public function setNoturna($noturna)
    {
        $this->noturna = $noturna;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @param $tipo
     * @return bool
     */
    public function validaTipo($tipo)
    {
        return in_array($tipo, array(
            self::EXTRA50,
            self::EXTRA75,
            self::EXTRA100
        ));
    }
}
