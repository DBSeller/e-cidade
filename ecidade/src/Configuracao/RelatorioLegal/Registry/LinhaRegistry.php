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

namespace ECidade\Configuracao\RelatorioLegal\Registry;

use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use Exception;

class LinhaRegistry
{
    /**
     * @var Linha[]
     */
    private static $storage = array();

    /**
     * @param Relatorio $relatorio
     * @param int $key
     * @return Linha
     * @throws Exception
     */
    public static function get(Relatorio $relatorio, $key)
    {
        $hash = "{$relatorio->getSequencial()}{$key}";

        if (!array_key_exists($hash, self::$storage)) {
            $resultado = LinhaRepositorio::find($relatorio, $key);
            if ($resultado) {
                self::set($resultado);
            } else {
                return null;
            }
        }

        return self::$storage[$hash];
    }

    /**
     * @param Linha $linha
     */
    public static function set(Linha $linha)
    {
        self::$storage["{$linha->getRelatorio()->getSequencial()}{$linha->getLinha()}"] = $linha;
    }
}
