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
namespace ECidade\Educacao\Escola\Censo\Registry;

use ECidade\Educacao\Escola\Censo\Log\Log;
use Exception;

abstract class LogCensoRegistry
{
    const MATRICULA_INICIAL = 1;
    const SITUACAO_ALUNO = 2;

    private static $storedValues = array();

    /**
     * @var array
     */
    private static $allowedKeys = array(
        self::MATRICULA_INICIAL,
        self::SITUACAO_ALUNO,
    );

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     * @throws Exception
     */
    public static function set($key, $value)
    {
        if (!in_array($key, self::$allowedKeys)) {
            throw new Exception('Invalid key given');
        }

        self::$storedValues[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return Log
     * @throws Exception
     */
    public static function get($key)
    {
        if (!in_array($key, self::$allowedKeys) || !isset(self::$storedValues[$key])) {
            throw new Exception('Invalid key given');
        }

        return self::$storedValues[$key];
    }
}