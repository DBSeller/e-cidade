<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\AndamentoPadrao;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao\CamposDinamicos;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\
AndamentoPadrao\CamposDinamicosRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class CamposDinamicosRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = CamposDinamicos::class;

    /**
     * Repository of respostas
     *
     * @var CamposDinamicosRespostaRepository
     */
    private $respostaRepository;

    /**
     * Construtor da classe
     *
     * @param cl_camposandpadrao $dao
     */
    public function __construct(
        \cl_camposandpadrao $dao,
        CamposDinamicosRespostaRepository $respostaRepository
    ) {
        $this->dao = $dao;
        $this->respostaRepository = $respostaRepository;
    }

    /**
     * Função que salva um novo registro
     *
     * @param CamposDinamicos $model
     */
    public function persist(CamposDinamicos $model)
    {
        $this->dao->p110_andpadrao_codigo = $model->getAndpadraoCodigo();
        $this->dao->p110_andpadrao_ordem  = $model->getAndpadraoOrdem();
        $this->dao->p110_codcam           = $model->getCodcam();
        $this->dao->p110_obrigatorio      = ($model->getObrigatorio()) ? 'true' : 'false';

        if (empty($model->getCodigo())) {
            $this->dao->incluir(null);
            $model->setCodigo($this->dao->p110_sequencial);
        } else {
            $this->dao->alterar($model->getCodigo());
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar campos dinamicos do processo!\\n{$this->dao->erro_msg}");
        }

        return $this->dao;
    }

    public function findAllByTipoprocessoAndOrdem($tipoProcesso, $ordem)
    {
        return $this->newQuery()
                    ->join('db_syscampo', 'db_syscampo.codcam', '=', 'camposandpadrao.p110_codcam')
                    ->where('p110_andpadrao_codigo', $tipoProcesso)
                    ->where('p110_andpadrao_ordem', $ordem)
                    ->get();
    }

    public function findByCodigo($codigo)
    {
        return $this->newQuery()
                    ->join('db_syscampo', 'db_syscampo.codcam', '=', 'camposandpadrao.p110_codcam')
                    ->where('p110_sequencial', $codigo)
                    ->get();
    }

    public function getAll()
    {
        return $this->newQuery()
                    ->join('db_syscampo', 'db_syscampo.codcam', '=', 'camposandpadrao.p110_codcam')
                    ->get();
    }

    public function getByProcessoDepto($codigo_processo, $codigoDepto)
    {
        return $this->newQuery()
                    ->join('db_syscampo', 'db_syscampo.codcam', '=', 'camposandpadrao.p110_codcam')
                    ->join('andpadrao', function ($joinAndPadrao) {
                        $joinAndPadrao->on('andpadrao.p53_codigo', '=', 'camposandpadrao.p110_andpadrao_codigo')
                                      ->on('andpadrao.p53_ordem', '=', 'camposandpadrao.p110_andpadrao_ordem');
                    })
                    ->join('protprocesso', 'protprocesso.p58_codigo', '=', 'andpadrao.p53_codigo')
                    ->leftJoin('db_syscampodef', 'db_syscampodef.codcam', '=', 'db_syscampo.codcam')
                    ->where('protprocesso.p58_codproc', '=', $codigo_processo)
                    ->where('andpadrao.p53_coddepto', '=', $codigoDepto)
                    ->get();
    }

    public function getByProcesso($codigo_processo)
    {
        return $this->newQuery()
                    ->join('db_syscampo', 'db_syscampo.codcam', '=', 'camposandpadrao.p110_codcam')
                    ->join('andpadrao', function ($joinAndPadrao) {
                        $joinAndPadrao->on('andpadrao.p53_codigo', '=', 'camposandpadrao.p110_andpadrao_codigo')
                                      ->on('andpadrao.p53_ordem', '=', 'camposandpadrao.p110_andpadrao_ordem');
                    })
                    ->join('protprocesso', 'protprocesso.p58_codigo', '=', 'andpadrao.p53_codigo')
                    ->leftJoin('db_syscampodef', 'db_syscampodef.codcam', '=', 'db_syscampo.codcam')
                    ->where('protprocesso.p58_codproc', '=', $codigo_processo)
                    ->get();
    }

    public function deleteByCodigo($codigo)
    {
        $this->verificaRespostaByCodigo($codigo);
        $this->newQuery()
            ->where('p110_sequencial', $codigo)
            ->delete();
    }

    public function deleteByTipoprocessoAndOrdem($tipoProcesso, $ordem)
    {
        $this->verificaRespostaByTipoprocessoAndOrdem($tipoProcesso, $ordem);
        $this->newQuery()
            ->where('p110_andpadrao_codigo', $tipoProcesso)
            ->where('p110_andpadrao_ordem', $ordem)
            ->delete();
    }

    private function verificaRespostaByCodigo($codigo)
    {
        $this->verificaRespostaCampo($codigo);
    }

    private function verificaRespostaByTipoprocessoAndOrdem($tipoProcesso, $ordem)
    {
        $campo = $this->findAllByTipoprocessoAndOrdem($tipoProcesso, $ordem)->first();

        if ($campo !== null) {
            $this->verificaRespostaCampo($campo->getCodigo());
        }
    }

    private function verificaRespostaCampo($codigoVinculoCampo)
    {
        if (is_null($codigoVinculoCampo)) {
            throw new \Exception("Não foi possível identificar o código do campo.");
        }

        $respostas = $this->buscaRespostaCampo($codigoVinculoCampo);

        if ($respostas !== null) {
            throw new \Exception("Não é possível excluir, existem campos já preenchidos.");
        }
    }

    private function buscaRespostaCampo($codigoVinculoCampo)
    {
        $respostas = $this->respostaRepository->getAll($codigoVinculoCampo);
        
        if ($respostas->isEmpty()) {
            return null;
        }

        return $respostas;
    }
}
