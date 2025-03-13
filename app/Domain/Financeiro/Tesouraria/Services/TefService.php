<?php


namespace App\Domain\Financeiro\Tesouraria\Services;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;
use DBDate;
use ECidade\Financeiro\Tesouraria\Models\LinhaTef;
use ECidade\Financeiro\Tesouraria\Models\LinhaTefProcessado;
use ECidade\Financeiro\Tesouraria\Repository\LinhaTefProcessadoRepository;
use ECidade\Financeiro\Tesouraria\Repository\LinhaTefRepository;
use EventoContabil;
use Exception;
use LancamentoAuxiliarTef;

/**
 * Class TefService
 * @package App\Domain\Financeiro\Tesouraria\Services
 */
class TefService
{
    private $repository;
    /**
     * @var DBDate
     */
    private $dataLancamento;

    public function __construct(LinhaTefRepository $repository)
    {
        $this->repository = $repository;
    }

    public function linhasNaoProcessadas()
    {
        $linhas = $this->repository->scopeNaoProcessado()->scopeConsistente()->getLinhaTefOperacaoRealizada();
        return collect($linhas)->map(function (LinhaTef $linhaTef) {
            return $linhaTef->toArray();
        });
    }

    /**
     * @param array $linhasTef
     * @throws Exception
     */
    public function processar(array $linhasTef)
    {
        $repositoryLinhaProcessada = new LinhaTefProcessadoRepository();
        foreach ($linhasTef as $idLinhaTef) {
            $linhaTef = $this->buscaLinhaNaoProcessada($idLinhaTef);

            $operacao = Operacoesrealizadastef::query()
                ->where('k198_nsuautorizadora', '=', $linhaTef->getNumeroCv())
                ->where('k198_codigoaprovacao', '=', str_pad($linhaTef->getNumeroAutorizacao(), 6, "0", STR_PAD_LEFT))
                ->first();

            $observacao = sprintf(
                'Confirmação do retorno de receita via TEF. Atorização %s',
                $linhaTef->getNumeroAutorizacao()
            );
            $data = $this->dataLancamento->convertTo(DBDate::DATA_EN);
            $iAnoUsu = $this->dataLancamento->getAno();
            $lancamentoAuxiliarTef = new LancamentoAuxiliarTef();
            $lancamentoAuxiliarTef->setObservacaoHistorico($observacao);
            $lancamentoAuxiliarTef->setValorTotal($linhaTef->getValorBruto());
            $lancamentoAuxiliarTef->setOperacoesrealizadastef($operacao->getSequencial());
            $lancamentoAuxiliarTef->setDocumento(169);

            $oEventoContabil = new EventoContabil(169, $iAnoUsu);
            $oEventoContabil->executaLancamento($lancamentoAuxiliarTef, $data);

            $linhaProcessada = new LinhaTefProcessado();
            $linhaProcessada->setCodigoLancamento($oEventoContabil->getCodigoLancamento());
            $linhaProcessada->setArquivoTefId($linhaTef->getId());

            $repositoryLinhaProcessada->save($linhaProcessada);
        }
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function marcarVistoInconsistente($id)
    {
        $linhaTef = $this->repository->find($id);
        $linhaTef->setConsistente(false);
        $this->repository->save($linhaTef);
        return true;
    }

    /**
     * @param $idLinhaTef
     * @return LinhaTef
     * @throws Exception
     */
    private function buscaLinhaNaoProcessada($idLinhaTef)
    {
        $linhaTef = $this->repository
            ->scopeNaoProcessado()
            ->scopeId($idLinhaTef)
            ->scopeConsistente()
            ->first();

        if (!$linhaTef) {
            throw new Exception("Não foi encontrado a referência da linha de ID: {$idLinhaTef}", 403);
        }
        return $linhaTef;
    }

    public function setDataLancamento(DBDate $data)
    {
        $this->dataLancamento = $data;
    }
}
