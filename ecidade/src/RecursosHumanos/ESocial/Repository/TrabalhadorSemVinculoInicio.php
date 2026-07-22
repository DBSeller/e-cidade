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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_avaliacaogruporespostatsveinicial;
use db_utils;
use Exception;
use Servidor;

/**
 * Class TrabalhadorSemVinculoInicio
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class TrabalhadorSemVinculoInicio
{
    /**
     * @return null|int
     * @throws Exception
     * @Deprecated
     */
    public static function buscarCodigoCategoriaServidor(Servidor $servidor)
    {
        $dao = new cl_avaliacaogruporespostatsveinicial();
        $codigoServidor = $servidor->getMatricula();
        $sql = $dao->buscaRespostasPorPerguntaMatricula(3001562, $codigoServidor, 'db106_resposta');
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o código da categoria do servidor {$codigoServidor}.");
        }

        $codigoCategoria = null;

        if (pg_num_rows($rs) > 0) {
            $codigoCategoria = db_utils::fieldsMemory($rs, 0)->db106_resposta;
        }

        return $codigoCategoria;
    }
}
