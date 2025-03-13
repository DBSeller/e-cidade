<?php


namespace ECidade\Financeiro\Orcamento\Service;

use ECidade\Financeiro\Orcamento\Model\Especificacao;
use ECidade\Financeiro\Orcamento\Registry\EspecificacaoRegistry;
use ECidade\Financeiro\Orcamento\Repository\EspecificacaoRepository;
use Exception;
use stdClass;

/**
 * Class EspecificacaoService
 * @package ECidade\Financeiro\Orcamento\Service
 */
class EspecificacaoService
{
    /**
     * @var EspecificacaoRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new EspecificacaoRepository();
    }

    /**
     * @param stdClass $parametros
     * @return Especificacao
     * @throws Exception
     */
    protected function stdClassToEspecificacao(stdClass $parametros)
    {
        $this->validarCamposInformados($parametros);
        $especificacao = new Especificacao();
        $especificacao->setId(!empty($parametros->codigo) ? $parametros->codigo : null);
        $especificacao->setEstado(!empty($parametros->estado) ? $parametros->estado : null);
        $especificacao->setCodigo($parametros->codigoEspecificacao);
        $especificacao->setDescricao($parametros->nomeEspecificacao);
        return $especificacao;
    }

    /**
     * @param stdClass $parametros
     * @return Especificacao
     * @throws Exception
     */
    public function salvar(stdClass $parametros)
    {
        $especificacao = $this->stdClassToEspecificacao($parametros);

        $this->validarPersistencia($especificacao);

        return $this->repository->salvar($especificacao);
    }

    /**
     * @param stdClass $parametros
     * @return bool
     * @throws Exception
     */
    public function excluir(stdClass $parametros)
    {
        $especificacao = $this->stdClassToEspecificacao($parametros);
        if ($this->especificacaoTemVinculoRecurso($especificacao)) {
            throw new Exception(sprintf(
                'Você não pode excluir o código de uma especificação quando ela esta vinculada a um Recurso.'
            ), 412);
        }
        return $this->repository->excluir($especificacao);
    }

    /**
     * @param stdClass $parametros
     * @return bool
     * @throws Exception
     */
    private function validarCamposInformados(stdClass $parametros)
    {
        if (empty($parametros->codigoEspecificacao)) {
            throw new Exception('O campo "Código" da Especificação do Recurso não foi informado.', 412);
        }
        if (empty($parametros->nomeEspecificacao)) {
            throw new Exception('A "Descrição" da Especificação do Recurso não foi informada.', 412);
        }

        return true;
    }

    /**
     * @param Especificacao $especificacao
     * @return boolean
     * @throws Exception
     */
    private function validarPersistencia(Especificacao $especificacao)
    {
        $this->repository->scopeEspecificacaoExiste($especificacao);
        if (!is_null($especificacao->getId())) {
            // valida se tentou alterar a especificação para uma especificação existente
            $this->repository->scopeId($especificacao->getId(), "!=");
        }
        $e = $this->repository->first();
        $this->repository->resetScopes();

        if (!is_null($e)) {
            throw new Exception(sprintf(
                'O código de especificação "%s" já esta sendo utilizado e não pode ser utilizado',
                $especificacao->getCodigo()
            ), 406);
        }

        if (!is_null($especificacao->getId())) {
            // busca a especificação da base para verificar se alterou o código da especificação
            $especificacaoBase = EspecificacaoRegistry::get($especificacao->getId());
            if ($especificacaoBase->getCodigo() !== $especificacao->getCodigo()) {
                if ($this->especificacaoTemVinculoRecurso($especificacaoBase)) {
                    throw new Exception(sprintf(
                        'Você não pode alterar o código de uma especificação quando ela esta vinculada a um Recurso.'
                    ), 406);
                }
            }
        }

        return true;
    }

    /**
     * @param Especificacao $especificacao
     * @return false
     * @throws Exception
     */
    private function especificacaoTemVinculoRecurso(Especificacao $especificacao)
    {
        $e = $this->repository->scopeEspecificacaoVinculadaRecurso($especificacao)->first();
        $this->repository->resetScopes();
        if (!is_null($e)) {
            return true;
        }
        return false;
    }
}
