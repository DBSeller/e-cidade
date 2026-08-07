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

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\MetasObjetivo;
use App\Domain\Financeiro\Planejamento\Models\ObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarMetaObjetivoRequest;
use Exception;

/**
 * Class MetaObjetivoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class MetaObjetivoService
{
    /**
     * @param SalvarMetaObjetivoRequest $request
     * @return MetasObjetivo|mixed
     * @throws Exception
     */
    public function salvarFromRequest(SalvarMetaObjetivoRequest $request)
    {
        $id = $request->get('pl21_codigo');
        $idObjetivo = $request->get('pl21_objetivosprogramaestrategico');
        $meta = new MetasObjetivo();
        if (!empty($id)) {
            $meta = MetasObjetivo::find($id);
        }
        $meta->objetivo()->associate(ObjetivoProgramaEstrategico::find($idObjetivo));
        $meta->pl21_texto = $request->get('pl21_texto');

        $meta->save();

        $meta->setValores(
            ValoresService::saveFromJson($request->get('valores'), Valor::ORIGEM_META_OBJETIVO, $meta->pl21_codigo)
        );

        return $meta;
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function remover($id)
    {
        MetasObjetivo::destroy($id);
    }
}
