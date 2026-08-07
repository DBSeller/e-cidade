<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;

class PersonaCgm extends Model
{
    protected $table = 'protocolo.personacgm';
    protected $primaryKey = 'p121_sequencial';
    public $timestamps = false;
    protected $fillable = [
        'p121_persona',
        'p121_cgm'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, "p121_persona");
    }
}
