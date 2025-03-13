<?php

namespace App\Domain\Patrimonial\Protocolo\Repository;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use App\Domain\Patrimonial\Protocolo\Model\ProcessoAtividadeExecucao;
use cl_processo_atividadesexecucao;
use Exception;

class ProcessoAtividadeExecucaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected $modelClass = ProcessoAtividadeExecucao::class;
    /**
     * @var cl_processo_atividadesexecucao
     */
    private $dao;

    private $scopes = [];

    public function __construct()
    {
        $this->dao = new cl_processo_atividadesexecucao();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query_file('', '*', 'p118_ordem', implode(' AND ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Fluxo de Atividades de Execução do Processo");
        }
        $dados = [];
        while ($state = pg_fetch_assoc($rs)) {
            $dados[] = ProcessoAtividadeExecucao::fromState($state);
        }
        return $dados;
    }

    /**
     * @return ProcessoAtividadeExecucao|null
     * @throws Exception
     */
    public function first()
    {
        $processoAtividadeExecucao = $this->get();
        if (count($processoAtividadeExecucao) == 0) {
            return null;
        }
        return array_shift($processoAtividadeExecucao);
    }

    /**
     * @param ProcessoAtividadeExecucao $processoAtividadeExecucao
     * @return ProcessoAtividadeExecucao
     * @throws Exception
     */
    public function salvar(ProcessoAtividadeExecucao $processoAtividadeExecucao)
    {
        $this->dao->p118_codigo = $processoAtividadeExecucao->p118_codigo;
        $this->dao->p118_protprocesso = $processoAtividadeExecucao->p118_protprocesso;
        $this->dao->p118_atividadesexecucao = $processoAtividadeExecucao->p118_atividadesexecucao;
        $this->dao->p118_ordem = $processoAtividadeExecucao->p118_ordem;
        if (empty($this->dao->p118_codigo)) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($this->dao->p118_codigo);
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar vinculo de atividades de execução com Processo");
        }

        $processoAtividadeExecucao->p118_codigo = $this->dao->p118_codigo;
        return $processoAtividadeExecucao;
    }

    public function scopeOrdem($ordem)
    {
        $this->scopes['ordem'] = "p118_ordem = {$ordem}";
        return $this;
    }

    public function scopeExecutadas($ordem)
    {
        $this->scopes['ordem'] = "p118_ordem < {$ordem}";
        return $this;
    }

    public function scopeProcesso(Processo $processo)
    {
        $this->scopes['processo'] = "p118_protprocesso = {$processo->getCodigoProcesso()}";
        return $this;
    }
}
