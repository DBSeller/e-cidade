<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\AndamentoPadraoRepository as RepositoryInterface;

use cl_andpadrao;
use Exception;

/**
* Classe repository do model que faz ligação entre um processo e um atendimento da ouvidoria
*
* @var string
*/
final class AndamentoPadraoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = AndamentoPadrao::class;
    /**
     * @var cl_andpadrao
     */
    private $dao;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->dao = new cl_andpadrao();
    }

    /**
     * Função que retorna o departamento do andamento padrao para a ordem passada
     *
     * @param int $tipoProcesso
     * @param int $ordem
     * @return int $departamento
     */
    public function getDepartamentoAndamentoPadrao($tipoProcesso, $ordem)
    {
        $andamentoPadrao = $this->newQuery()
                                ->select('p53_coddepto as departamento')
                                ->where("p53_codigo", "=", $tipoProcesso)
                                ->where("p53_ordem", "=", $ordem)
                                ->first();

        return (!empty($andamentoPadrao)) ? $andamentoPadrao->departamento : false;
    }
}
