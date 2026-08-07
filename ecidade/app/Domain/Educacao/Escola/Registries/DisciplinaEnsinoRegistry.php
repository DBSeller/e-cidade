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

namespace App\Domain\Educacao\Escola\Registries;

use App\Domain\Educacao\Escola\Models\DisciplinaEnsino;

/**
 * Class DisciplinaEnsinoRegistry
 * @package App\Domain\Educacao\Escola\Registries
 */
class DisciplinaEnsinoRegistry
{
    /**
     * @var DisciplinaEnsino[]
     */
    private static $storage = [];

    /**
     * @param DisciplinaEnsino $disciplinaEnsino
     */
    public static function set(DisciplinaEnsino $disciplinaEnsino)
    {
        self::$storage[$disciplinaEnsino->getCodigo()] = $disciplinaEnsino;
    }

    /**
     * @param $key
     * @return DisciplinaEnsino|null
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $disciplinaEnsino = DisciplinaEnsino::find($key);
            if (is_null($disciplinaEnsino)) {
                return null;
            }

            self::set($disciplinaEnsino);
        }

        return self::$storage[$key];
    }
}
