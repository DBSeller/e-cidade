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

namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Iniciativa
 * @package App\Domain\Financeiro\Orcamento\Models
 * @property $o55_anousu
 * @property $o55_tipo
 * @property $o55_projativ
 * @property $o55_descr
 * @property $o55_finali
 * @property $o55_instit
 * @property $o55_descrunidade
 * @property $o55_valorunidade
 * @property $o55_especproduto
 * @property $o55_tipoacao
 * @property $o55_formaimplementacao
 * @property $o55_detalhamentoimp
 * @property $o55_origemacao
 * @property $o55_baselegal
 * @property $o55_orcproduto

 */
class ProjetoAtividade extends Model
{
    protected $table = 'orcamento.orcprojativ';

    public function formataCodigo()
    {
        return str_pad($this->o55_projativ, 4, '0', STR_PAD_LEFT);
    }

    public function toArray()
    {
        $this->produto;
        return parent::toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'o55_orcproduto', 'o22_codproduto');
    }
}
