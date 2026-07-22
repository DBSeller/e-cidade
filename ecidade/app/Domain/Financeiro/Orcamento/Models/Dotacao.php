<?php


namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dotacao
 * @package App\Domain\Financeiro\Orcamento\Models
 * @property $o58_anousu
 * @property $o58_coddot
 * @property $o58_orgao
 * @property $o58_unidade
 * @property $o58_subfuncao
 * @property $o58_projativ
 * @property $o58_codigo
 * @property $o58_funcao
 * @property $o58_programa
 * @property $o58_codele
 * @property $o58_valor
 * @property $o58_instit
 * @property $o58_localizadorgastos
 * @property $o58_datacriacao
 * @property $o58_concarpeculiar
 * @property $o58_esferaorcamentaria
 */
class Dotacao extends Model
{
    protected $table = 'orcamento.orcdotacao';

    public function recurso()
    {
        return $this->hasOne(Recurso::class, 'o15_codigo', 'o58_codigo');
    }

    public function fonteRecurso()
    {
        return $this->recurso->fonteRecurso($this->o58_anousu);
    }
}
