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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class MetasObjetivo
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl21_codigo
 * @property $pl21_objetivosprogramaestrategico
 * @property $pl21_texto
 * @property $created_at
 * @property $updated_at
 */
class MetasObjetivo extends Model
{
    protected $table = 'planejamento.metasobjetivoprogramaestrategico';

    protected $primaryKey = 'pl21_codigo';
    private $storage = [];

    /**
     * Seta a collection de valores
     * @param Collection $valores
     */
    public function setValores(Collection $valores)
    {
        $this->storage['valores'] = $valores;
    }

    /**
     * Retorna os valores das metas do objetivo estratégico
     * @return Collection
     */
    public function getValores()
    {
        if (!array_key_exists('valores', $this->storage)) {
            $this->storage['valores'] = Valor::where('pl10_chave', '=', $this->pl21_codigo)
                ->where('pl10_origem', '=', Valor::ORIGEM_META_OBJETIVO)
                ->orderBy('pl10_ano')
                ->get();
        }

        return $this->storage['valores'];
    }

    public function toArray()
    {
        $dados = parent::toArray();
        $dados['valores'] = $this->getValores();
        return $dados;
    }

    public function objetivo()
    {
        return $this->belongsTo(ObjetivoProgramaEstrategico::class, 'pl21_objetivosprogramaestrategico', 'pl11_codigo');
    }
}
