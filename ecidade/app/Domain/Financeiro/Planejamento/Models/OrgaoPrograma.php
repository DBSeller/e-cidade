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

use App\Domain\Financeiro\Orcamento\Models\Orgao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrgaoPrograma
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $id
 * @property $pl27_programaestrategico
 * @property $pl27_orcorgao
 * @property $pl27_anoorcamento
 */
class OrgaoPrograma extends Model
{
    protected $table = 'planejamento.orgaoprogramaestregico';

    public $timestamps = false;

    private $storage = [];

    /**
     * retorna o ProgramaEstrategico
     * @return ProgramaEstrategico
     */
    public function getProgramaEstrategico()
    {
        if (!array_key_exists('programaEstrategico', $this->storage)) {
            $this->storage['programaEstrategico'] = $this->programaEstrategico;
        }

        return $this->storage['programaEstrategico'];
    }

    /**
     * Retorna o orgao do orcamento
     * @return Orgao
     */
    public function getOrgaoOrcamento()
    {
        if (!array_key_exists('orgaoOrcamento', $this->storage)) {
            $this->storage['orgaoOrcamento'] = Orgao::where('o40_orgao', '=', $this->pl27_orcorgao)
                ->where('o40_anousu', '=', $this->pl27_anoorcamento)
                ->first();
        }

        return $this->storage['orgaoOrcamento'];
    }

    /**
     * @return BelongsTo
     */
    public function programaEstrategico()
    {
        return $this->belongsTo(ProgramaEstrategico::class, 'pl27_programaestrategico', 'pl9_codigo');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $orgao = parent::toArray();
        $orgaoOrcamento = $this->getOrgaoOrcamento();
        $orgao['orgao'] = str_pad($orgaoOrcamento->o40_orgao, 2, '0', STR_PAD_LEFT);
        $orgao['descricao'] = $orgaoOrcamento->o40_descr;
        return $orgao;
    }
}
