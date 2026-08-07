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

namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Financeiro\Orcamento\Models\FonteReceita;
use App\Domain\Financeiro\Orcamento\Models\Programa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FatorCorrecaoReceita
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $id
 * @property $planejamento_id
 * @property $orcfontes_id
 * @property $anoorcamento
 * @property $exercicio
 * @property $deflator
 * @property $percentual
 * @property $created_at
 * @property $updated_at

 */
class FatorCorrecaoReceita extends Model
{
    protected $table = 'planejamento.fatorcorrecaoreceita';
    /**
     * @var array
     */
    private $storage = [];

    protected $casts = [
        'percentual' => 'float'
    ];

    /**
     * Retorna a fonte de receita
     * @return FonteReceita
     */
    public function getFonteReceita()
    {
        if (!array_key_exists('fonte', $this->storage)) {
            $this->storage['fonte'] = FonteReceita::
            where('o57_codfon', '=', $this->anoorcamento)
                ->where('o57_anousu', '=', $this->orcfontes_id)
                ->first();
        }

        return $this->storage['fonte'];
    }

    /**
     * @return BelongsTo
     */
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'planejamento_id', 'pl2_codigo');
    }
}
