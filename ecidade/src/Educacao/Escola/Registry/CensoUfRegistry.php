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

namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\CensoUf;
use ECidade\Educacao\Escola\Repository\CensoUfRepository;
use Exception;

class CensoUfRegistry
{
    /**
     * @var CensoUf[]
     */
    private static $storage = array();

    /**
     * @param CensoUf $censoUf
     */
    public static function set(CensoUf $censoUf)
    {
        self::$storage[$censoUf->getSigla()] = $censoUf;
    }

    /**
     * @param string $uf
     * @return CensoUf
     * @throws Exception
     */
    public static function get($uf)
    {
        $uf = strtoupper($uf);
        if (!array_key_exists($uf, self::$storage)) {
            $censoUf = CensoUfRepository::find($uf);
            if (is_null($censoUf)) {
                return null;
            }

            self::set($censoUf);
        }

        return self::$storage[$uf];
    }

    /**
     * @param $id
     * @return CensoUf|null
     * @throws Exception
     */
    public static function getFromId($id)
    {
        foreach (self::$storage as $censoUf) {
            if ($censoUf->getCodigo() === $id) {
                return $censoUf;
            }
        }

        $censoUf = CensoUfRepository::findId($id);
        if (is_null($censoUf)) {
            return null;
        }

        self::set($censoUf);
        return self::$storage[$censoUf->getSigla()];
    }
}
