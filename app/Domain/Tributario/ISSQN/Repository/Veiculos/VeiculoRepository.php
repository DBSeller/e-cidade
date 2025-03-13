<?php
namespace App\Domain\Tributario\ISSQN\Repository\Veiculos;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Tributario\ISSQN\Model\Veiculos\Veiculo;
use App\Domain\Tributario\ISSQN\Repository\Veiculos\Contracts\VeiculoRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*/
final class VeiculoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     * @var string
     */
    protected $modelClass = Veiculo::class;

    /**
     * Função que salva um novo registro
     *
     * @param Veiculo $model
     * @return boolean
     */
    public function persist(
        Veiculo $model
    ) {
        return $model->save();
    }

    /**
     * Função que remove um registro
     * @param integer $id
     * @return boolean
     */
    public function delete($id)
    {
        return Veiculo::destroy($id);
    }


    /**
     * Função que retorna um registro
     * @param integer $id
     */
    public function getVeiculo($id)
    {
        $query = $this->newQuery();
        $query->select(
            'issveiculo.*',
            'cgm.z01_numcgm',
            'cgm.z01_nome',
            'cgm.z01_ender',
            'cgm.z01_munic',
            'cgm.z01_cep',
            'cgm.z01_cgccpf',
            'veiccadmodelo.ve22_descr',
            'bairro.j13_codi',
            'bairro.j13_descr',
            'issruas.q02_numero',
            'issruas.q02_compl',
            'issruas.q02_cxpost',
            'issruas.z01_cep as issruas_cep',
            'ruas.j14_codigo',
            'ruas.j14_nome',
            'issbase.q02_formalocalvara'
        );
        $query->join('issqn.issbase', 'q02_inscr', 'q172_issbase')
              ->join('issqn.issbairro', 'q13_inscr', 'q02_inscr')
              ->join('cadastro.bairro', 'j13_codi', 'q13_bairro')
              ->join('issqn.issruas', 'issruas.q02_inscr', 'issbase.q02_inscr')
              ->join('cadastro.ruas', 'ruas.j14_codigo', 'issruas.j14_codigo')
              ->join('protocolo.cgm', 'z01_numcgm', 'q02_numcgm')
              ->leftJoin('veiculos.veiccadmodelo', 've22_codigo', 'q172_modelo')
              ->where('q172_sequencial', '=', $id);

        return $query->first();
    }
}
