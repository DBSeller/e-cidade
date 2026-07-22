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
use ECidade\Enum\Financeiro\Planejamento\TipoEnum;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PibService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class PibService
{
    /**
     * @param $planejamento_id
     * @return Collection
     */
    public function get($planejamento_id)
    {
        return Valor::query()
            ->where('pl10_chave', '=', $planejamento_id)
            ->where('pl10_origem', '=', Valor::ORIGEM_PIB)
            ->get();
    }

    public function salvarToArray($dados)
    {
        if (Planejamento::find($dados['planejamento_id'])->pl2_tipo !== TipoEnum::LDO) {
            throw new \Exception("Planejamento informado não é uma LDO.");
        }
        ValoresService::saveFromJson($dados['valores'], Valor::ORIGEM_PIB, $dados['planejamento_id']);

        return true;
    }
}
