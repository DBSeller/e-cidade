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

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RCLPlanejamentoService
{
    /**
     * @param Planejamento $planejamento
     * @param $filtros
     * @return Collection
     */
    public function getRCLProjecao(Planejamento $planejamento, $filtros)
    {
        return DB::table('estimativareceita')
            ->join('valores', function ($join) {
                $join->on('valores.pl10_chave', '=', 'estimativareceita.id')
                    ->where('valores.pl10_origem', '=', Valor::ORIGEM_RECEITA);
            })
            ->where('planejamento_id', '=', $planejamento->pl2_codigo)
            ->when(!empty($filtros['instituicoes']), function ($query) use ($filtros) {
                $query->whereIn('instituicao_id', $filtros['instituicoes']);
            })
            ->select('pl10_ano as exercicio', DB::raw('sum(pl10_valor) as valor'))
            ->groupBy('pl10_ano')
            ->get();
    }
}
