<?php


namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ValorEstrutural
 * @package App\Domain\Financeiro\Orcamento\Models
 * @property integer $db121_sequencial
 * @property integer $db121_db_estrutura
 * @property string $db121_estrutural
 * @property string $db121_descricao
 * @property integer $db121_estruturavalorpai
 * @property integer $db121_nivel
 * @property integer $db121_tipoconta
 */
class ValorEstrutural extends Model
{
    protected $table = 'configuracoes.db_estruturavalor';
    protected $primaryKey = 'db121_sequencial';
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'db121_sequencial' => 'integer',
        'db121_db_estrutura' => 'integer',
        'db121_estrutural' => 'string',
        'db121_descricao' => 'string',
        'db121_estruturavalorpai' => 'integer',
        'db121_nivel' => 'integer',
        'db121_tipoconta' => 'integer',
    ];
}
