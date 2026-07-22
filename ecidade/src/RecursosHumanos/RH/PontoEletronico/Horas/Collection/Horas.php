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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Collection;

use BusinessException;
use DateTime;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model\Hora;

class Horas
{
    /**
     * @var Hora[]
     */
    private $colecao = array();

    /**
     * @param Hora $hora
     */
    public function add(Hora $hora)
    {
        if (!array_key_exists($hora->getTipo(), $this->colecao)) {
            $this->colecao[$hora->getTipo()] = $hora;
        }
    }

    /**
     * @param int $tipo
     * @return Hora
     * @throws BusinessException
     */
    public function getHoraByTipo($tipo)
    {
        if (!array_key_exists($tipo, $this->colecao)) {
            throw new BusinessException('Tipo de hora não encontrado.');
        }

        return $this->colecao[$tipo];
    }

    /**
     * @return Hora[]
     */
    public function getAll()
    {
        return $this->colecao;
    }

    /**
     * @param DateTime $dateTime
     */
    public function inicializarHoras(DateTime $dateTime)
    {
        foreach ($this->colecao as $hora) {
            $hora->setDiurna(clone $dateTime);
            $hora->setNoturna(clone $dateTime);
        }
    }
}
