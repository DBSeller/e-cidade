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

namespace App\Domain\Configuracao\RelarorioLegal\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 * @package App\Domain\Configuracao\RelarorioLegal\Model
 * @property integer $c138_id
 * @property integer $c138_orcparamrel
 * @property integer $c138_periodo
 * @property integer $c138_modelo
 * @property string $c138_path
 */
class Template extends Model
{

    protected $table = 'configuracoes.templaterelatorioslegais';
    protected $primaryKey = 'c138_id';
    public $timestamps = false;
    public $incrementing = false;

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'c138_periodo', 'o114_sequencial');
    }

    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class, 'c138_orcparamrel', 'o42_codparrel');
    }
}
