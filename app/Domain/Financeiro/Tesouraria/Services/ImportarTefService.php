<?php


namespace App\Domain\Financeiro\Tesouraria\Services;

use App\Domain\Financeiro\Tesouraria\Builder\TefBuilder;
use App\Domain\Financeiro\Tesouraria\Mappers\TefMapper;
use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;
use ECidade\Financeiro\Tesouraria\Models\LinhaTef;
use ECidade\Financeiro\Tesouraria\Repository\LinhaTefRepository;

class ImportarTefService
{
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var LinhaTefRepository
     */
    private $repository;

    public function __construct($fileName, $filePath)
    {
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->repository = new LinhaTefRepository();
    }

    public function process()
    {
        $builder = new TefBuilder();
        $builder->setMapper(new TefMapper());
        $builder->setFilePath($this->filePath);
        $collection = $builder->build();

        $this->saveFile($collection);
    }

    /**
     * @param LinhaTef[] $collection
     */
    private function saveFile(array $collection)
    {

        foreach ($collection as $arquivoTef) {
            if (!$this->validaConfirmacaoAutorizadora($arquivoTef)) {
                continue;
            }

            if ($this->exist($arquivoTef)) {
                continue;
            }

            $aDados[] = $arquivoTef;
            $this->save($arquivoTef);
        }
    }

    private function exist(LinhaTef $arquivoTef)
    {
        return $this->repository->scopeNumeroAutorizacao($arquivoTef->getNumeroAutorizacao())
            ->scopeNumeroCv($arquivoTef->getNumeroCv())
            ->scopeCartao($arquivoTef->getCartao())
            ->scopeDataVenda($arquivoTef->getDataVenda())
            ->scopeDataVencimento($arquivoTef->getDataVencimento())
            ->scopeParcela($arquivoTef->getParcela())
            ->scopeTotalParcelas($arquivoTef->getTotalParcelas())
            ->scopeValorOriginal($arquivoTef->getValorOriginal())
            ->scopeValorBruto($arquivoTef->getValorBruto())
            ->scopeValorDescontos($arquivoTef->getValorDescontos())
            ->scopeValorLiquido($arquivoTef->getValorLiquido())
            ->exists();
    }

    /*
        validacao para nao incluir registros que tenham sido cancelados
        se retornar registros nesta condição , não deve incluir a linha
        validar com a operacoesrealizadastef onde
            k198_codigoaprovacao   = numeroAutorizacao
            k198_nsuautorizadora   = numeroCv
            k198_confirmadoautorizadora = false

    */
    private function validaConfirmacaoAutorizadora(LinhaTef $arquivoTef)
    {
        $codigoAprovacao = $arquivoTef->getNumeroAutorizacao();
        $nsuAutorizadora = $arquivoTef->getNumeroCv();

        $oValidacao = Operacoesrealizadastef::query()
            ->where('k198_nsuautorizadora', '=', $nsuAutorizadora)
            ->whereRaw('k198_confirmadoautorizadora is false')
            ->when(\DBNumber::isInteger($codigoAprovacao), function ($query) use ($codigoAprovacao) {
                $codigoAprovacao = trim($codigoAprovacao);
                $query->whereRaw("k198_codigoaprovacao::integer = {$codigoAprovacao}");
            })
            ->when(!\DBNumber::isInteger($codigoAprovacao), function ($query) use ($codigoAprovacao) {
                $query->whereRaw("k198_codigoaprovacao = '{$codigoAprovacao}'");
            })
        ->first();

        if (!is_null($oValidacao)) {
            return false;
        }

        return true;
    }

    private function save($arquivoTef)
    {
        $this->repository->save($arquivoTef);
    }
}
