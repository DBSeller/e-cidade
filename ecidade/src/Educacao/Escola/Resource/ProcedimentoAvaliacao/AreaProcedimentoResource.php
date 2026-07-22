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

namespace ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao;

use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;

/**
 * Class AreaProcedimentoResource
 * @package ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao
 */
class AreaProcedimentoResource
{
    /**
     * @param AreaProcedimento $areaProcedimento
     * @return object
     * @throws Exception
     */
    public static function toObject(AreaProcedimento $areaProcedimento)
    {
        $resultado = $areaProcedimento->getResultado();
        $resourceResultado = null;
        if ($resultado instanceof AreaProcedimentoResultado) {
            $resourceResultado = AreaProcedimentoResultadoResource::toObject($resultado);
        }

        return (object) [
            'codigo' => $areaProcedimento->getCodigo(),
            'procedimento' => ProcedimentoResource::toObject($areaProcedimento->getProcedimento()),
            'avaliacoes' => AreaProcedimentoAvaliacaoResource::toArray($areaProcedimento->getAvaliacoes()),
            'resultado' => $resourceResultado
        ];
    }
}
