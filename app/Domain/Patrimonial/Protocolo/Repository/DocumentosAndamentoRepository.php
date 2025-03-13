<?php

namespace App\Domain\Patrimonial\Protocolo\Repository;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\DocumentoAndamento;
use cl_documentos_andamento;
use Exception;

class DocumentosAndamentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected $modelClass = DocumentoAndamento::class;
    /**
     * @var cl_documentos_andamento
     */
    private $dao;

    private $scopes = [];

    public function __construct()
    {
        $this->dao = new cl_documentos_andamento();
    }

    /**
     * @return DocumentoAndamento[]
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Documento");
        }
        $dados = [];
        while ($state = pg_fetch_assoc($rs)) {
            $dados[] = DocumentoAndamento::fromState($state);
        }
        return $dados;
    }

    /**
     * @return DocumentoAndamento|null
     * @throws Exception
     */
    public function first()
    {
        $data = $this->get();
        if (count($data) == 0) {
            return null;
        }
        return array_shift($data);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function buscarDocumentosPorUsuario($usuario)
    {
        $where = ["p119_id_usuario = {$usuario->getCodigo()}"];
        $where[] = "not exists(
        select 1
            from documentos_movimentacao
                join processo_atividadesexecucao atividade_executada ON
                    atividade_executada.p118_codigo = documentos_movimentacao.p117_processo_atividadesexecucao
                where atividade_executada.p118_atividadesexecucao = proxima_atividade.p118_atividadesexecucao
                    and p117_id_usuario = p119_id_usuario
                    and p117_documento_andamento = p116_codigo
                    and p117_devolucao is false
                    and p117_invalida is false)";

        $sql = $this->dao->sql_query_documentos_usuario(
            null,
            'distinct p116_codigo',
            null,
            implode(' and ', $where)
        );
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Documentos para Andamento");
        }
        $dados = [];
        while ($state = pg_fetch_assoc($rs)) {
            $dados[] = DocumentoAndamento::with('atividadeAtual', 'proximaAtividade')->find($state['p116_codigo']);
        }
        return $dados;
    }

    /**
     * @throws Exception
     */
    public function salvar(DocumentoAndamento $documentoAndamento)
    {
        $this->dao->p116_codigo = $documentoAndamento->p116_codigo;
        $this->dao->p116_descricao = $documentoAndamento->p116_descricao;
        $this->dao->p116_protprocesso = $documentoAndamento->p116_protprocesso;
        $this->dao->p116_protprocessodocumento = $documentoAndamento->p116_protprocessodocumento;
        $this->dao->p116_atividade_atual = $documentoAndamento->p116_atividade_atual;
        $this->dao->p116_proxima_atividade = $documentoAndamento->p116_proxima_atividade;
        $this->dao->p116_codigo_origem = $documentoAndamento->p116_codigo_origem;
        $dataSessao = new \DateTime(date("Y-m-d", db_getsession("DB_datausu")) . date("H:i:s", time()));
        $this->dao->p116_data_modificacao = $dataSessao->format('Y-m-d H:i:s');

        if (empty($this->dao->p116_codigo)) {
            $this->dao->p116_qrcode = $documentoAndamento->p116_qrcode;
            $this->dao->p116_data_criacao = $dataSessao->format('Y-m-d H:i:s');
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($this->dao->p116_codigo);
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Documento");
        }

        $documentoAndamento->p116_codigo = $this->dao->p116_codigo;
        return $documentoAndamento;
    }

    /**
     * @param $qrcode
     * @return $this
     */
    public function scopeQRCode($qrcode)
    {
        $this->scopes["qrcode"] = "p116_qrcode = '{$qrcode}'";
        return $this;
    }

    public function scopeCodigoOrigem($codigoOrigem)
    {
        $this->scopes["origem"] = "p116_codigo_origem = {$codigoOrigem}";
        return $this;
    }
}
