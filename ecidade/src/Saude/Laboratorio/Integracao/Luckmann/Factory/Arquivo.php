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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Factory;

use ECidade\Saude\Laboratorio\Integracao\Luckmann\Enum\Parametros;
use Exception;

/**
 * Class Arquivo
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Factory
 */
class Arquivo
{
    /**
     * @param $tipo
     * @return string
     * @throws Exception
     */
    public static function getPorTipo($tipo)
    {
        switch ($tipo) {
            case Parametros::PEDIDOS:
                $arquivo = Parametros::JSON_PEDIDOS;
                break;

            case Parametros::RESULTADOS:
                $arquivo = Parametros::JSON_RESULTADOS;
                break;

            default:
                throw new Exception('Tipo não encontrado.');
                break;
        }

        return $arquivo;
    }
}
