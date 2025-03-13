<?php

namespace App\Domain\Saude\Ambulatorial\Repositories;

use Illuminate\Support\Facades\DB;
use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Saude\Ambulatorial\Models\Prontuario;

/**
 * @package App\Domain\Saude\Ambulatorial\Repositories
 */
class ProntuarioRepository extends BaseRepository
{
    protected $modelClass = Prontuario::class;

    /**
     * Considera a menor data dos procedimentos para busca dos atendimentos
     * @param \DateTime $periodoInicio
     * @param \DateTime $periodoFim
     * @param array $unidades
     * @param array|string|\Closure $where
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAtendimentos(\DateTime $periodoInicio, \DateTime $periodoFim, array $unidades, $where = null)
    {
        $dao = new \cl_prontproced;
        $sql = $dao->sql_query_file('', 'sd29_i_codigo', 'sd29_d_data limit 1', 'sd29_i_prontuario = sd24_i_codigo');
        
        $query = $this->newQuery()
            ->select(['prontuarios.*', 'sd29_d_data'])
            ->join('ambulatorial.prontuario_problemaspaciente', 's171_prontuario', 'sd24_i_codigo')
            ->join('ambulatorial.problemaspaciente', 's170_id', 's171_problemapaciente')
            ->join('plugins.psf_prontuario', 'sd30_i_prontuario', 'sd24_i_codigo')
            ->join('ambulatorial.prontproced', 'sd29_i_codigo', DB::raw("({$sql})"))
            ->whereBetween('sd29_d_data', [$periodoInicio->format('Y-m-d'), $periodoFim->format('Y-m-d')]);
        
        if (!empty($unidades)) {
            $query->whereIn('sd24_i_unidade', $unidades);
        }

        if ($where) {
            $query->where($where);
        }

        return $query->orderBy('sd29_d_data')->distinct()->get();
    }
}
