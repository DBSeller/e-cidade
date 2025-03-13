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

use AvaliacaoPeriodica;
use Exception;
use ProcedimentoAvaliacao;
use stdClass;

/**
 * Class ProcedimentoResource
 * @package ECidade\Educacao\Escola\Resource
 */
class ProcedimentoResource
{

    /**
     * @param ProcedimentoAvaliacao[] $procedimentos
     * @return stdClass[]
     * @throws Exception
     */
    public static function toArray(array $procedimentos)
    {
        $dados = [];
        foreach ($procedimentos as $procedimento) {
            $dados[] = self::toObject($procedimento);
        }

        return $dados;
    }

    public static function toObject(ProcedimentoAvaliacao $procedimento)
    {
        $elementos = [];
        $elementosAvaliacao = $procedimento->getElementos();

        foreach ($elementosAvaliacao as $elementoAvaliacao) {
            if ($elementoAvaliacao instanceof AvaliacaoPeriodica) {
                $elementos[] = AvaliacaoPeriodicaResource::toStdClass($elementoAvaliacao);
            } else {
                $elementos[] = ResultadoAvaliacaoResource::toStdClass($elementoAvaliacao);
            }
        }
        return (object)[
            'codigo' => $procedimento->getCodigo(),
            'descricao' => $procedimento->getDescricao(),
            'formaAvaliacao' => FormaAvaliacaoResource::toObject($procedimento->getFormaAvaliacao()),
            'elementos' => $elementos
        ];
    }
}
