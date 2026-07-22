<?php

namespace App\Domain\Patrimonial\Protocolo\Repository;

use App\Domain\Configuracao\Usuario\Models\Usuario;
use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\DocumentoAndamento;
use App\Domain\Patrimonial\Protocolo\Model\DocumentoMovimentacao;
use cl_documentos_movimentacao;
use Exception;

class DocumentosMovimentacaoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected $modelClass = DocumentoMovimentacao::class;
    /**
     * @var cl_documentos_movimentacao
     */
    private $dao;

    private $scopes = [];

    public function __construct()
    {
        $this->dao = new cl_documentos_movimentacao();
    }

    /**
     * @return DocumentoMovimentacao[]
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query_file('', '*', 'p117_data', implode(' AND ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Movimentações do Documento");
        }
        $dados = [];
        while ($state = pg_fetch_assoc($rs)) {
            $dados[] = DocumentoMovimentacao::fromState($state);
        }
        return $dados;
    }

    public function first()
    {
        $data = $this->get();
        if (count($data) == 0) {
            return null;
        }
        return array_shift($data);
    }

    public function last()
    {
        $movimentacoes = $this->get();
        if (count($movimentacoes) == 0) {
            return null;
        }
        return array_pop($movimentacoes);
    }

    /**
     * @throws Exception
     */
    public function salvar(DocumentoMovimentacao $documentoMovimentacao)
    {
        $this->dao->p117_codigo = $documentoMovimentacao->p117_codigo;
        $this->dao->p117_documento_andamento = $documentoMovimentacao->p117_documento_andamento;
        $this->dao->p117_id_usuario = $documentoMovimentacao->p117_id_usuario;
        $this->dao->p117_protprocessodocumento = $documentoMovimentacao->p117_protprocessodocumento;
        $this->dao->p117_processo_atividadesexecucao = $documentoMovimentacao->p117_processo_atividadesexecucao;
        $this->dao->p117_devolucao = $documentoMovimentacao->p117_devolucao ? 't' : 'f';
        $this->dao->p117_invalida = $documentoMovimentacao->p117_invalida ? 't' : 'f';
        $this->dao->p117_data = $documentoMovimentacao->p117_data;

        if (empty($this->dao->p117_codigo)) {
            $data = date("Y-m-d", db_getsession("DB_datausu"));
            $hora = date("H:i:s");
            $dataSessao = new \DateTime("{$data} {$hora}");
            $this->dao->p117_data = $dataSessao->format('Y-m-d H:i:s');
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($this->dao->p117_codigo);
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Movimentação do Documento.");
        }

        $documentoMovimentacao->p117_codigo = $this->dao->p117_codigo;
        return $documentoMovimentacao;
    }

    public function scopeDocumento(DocumentoAndamento $documento)
    {
        $this->scopes['documento'] = "p117_documento_andamento = {$documento->p116_codigo}";
        return $this;
    }

    public function scopeAtividade($atividadeExecucao)
    {
        $this->scopes['atividade'] = "p117_processo_atividadesexecucao = {$atividadeExecucao}";
        return $this;
    }

    public function scopeDataMenor($data)
    {
        $this->scopes['dataMenor'] = "p117_data < {$data}";
        return $this;
    }

    public function scopeAtividadeExecucaoMaior($p117_processo_atividadesexecucao)
    {
        $this->scopes['dataMenor'] = "p117_processo_atividadesexecucao > {$p117_processo_atividadesexecucao}";
        return $this;
    }

    public function scopeDevolucao($isDevolucao)
    {
        $this->scopes['devolucao'] = "p117_devolucao is {$isDevolucao}";
        return $this;
    }

    public function scopeInvalida($isInvalida)
    {
        $this->scopes['devolucao'] = "p117_invalida is {$isInvalida}";
        return $this;
    }

    public function scopeUsuario(Usuario $usuario)
    {
        $this->scopes['usuario'] = "p117_id_usuario = {$usuario->id_usuario}";
        return $this;
    }

    public function resetScopes()
    {
        $this->scopes = [];
        return $this;
    }
}
