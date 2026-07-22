<?php


namespace ECidade\Financeiro\Tesouraria\Repository;

use cl_linha_tef;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Tesouraria\Models\LinhaTef;
use Exception;

class LinhaTefRepository extends Repository
{
    /**
     * @var cl_linha_tef
     */
    private $dao;

    private $campos = [
        '*',
        '(select k198_sequencial from operacoesrealizadastef
           where k198_nsuautorizadora = numero_cv
            and k198_codigoaprovacao = numero_autorizacao) as operacoesrealizadastef_id
    '];

    public function __construct()
    {
        $this->dao = new cl_linha_tef();
    }

    /**
     * @param $idLinhaTef
     * @return LinhaTef
     * @throws Exception
     */
    public function find($idLinhaTef)
    {
        $this->dao = new cl_linha_tef();
        $sql = $this->dao->sql_query_file($idLinhaTef, implode(', ', $this->campos));
        $rs = $this->execute($sql);
        return LinhaTef::fromState(pg_fetch_array($rs, 0));
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function exists()
    {
        $sql = $this->dao->sql_query_file(null, '1', null, implode(' and ', $this->scopes));
        $rs = $this->execute($sql);

        return pg_num_rows($rs) > 0;
    }


    public function getLinhaTefOperacaoRealizada($order = ['data_venda'])
    {
        $campos = "
        id,
        lpad(numero_autorizacao, 6, '0') AS numero_autorizacao,
        numero_cv,
        cartao,
        data_venda,
        data_vencimento,
        parcela,
        total_parcelas,
        valor_original,
        valor_bruto,
        valor_descontos,
        valor_liquido,
        consistente,
        k198_sequencial as operacoesrealizadastef_id
        ";

        $sql = $this->dao->sql_query_operacoesrealizadas($campos, implode(' and ', $this->scopes), "data_venda");
        $rs = $this->execute($sql);

        $linhas = [];
        while ($state = pg_fetch_array($rs)) {
            $linhas[] = LinhaTef::fromState($state);
        }

        return $linhas;
    }

    public function get($order = ['data_venda'])
    {
        $sql = $this->dao->sql_query_file(null, implode(', ', $this->campos), null, implode(' and ', $this->scopes));
        $rs = $this->execute($sql);

        $linhas = [];
        while ($state = pg_fetch_array($rs)) {
            $linhas[] = LinhaTef::fromState($state);
        }

        return $linhas;
    }

    /**
     * @return LinhaTef|false
     */
    public function first()
    {
        $data = $this->get();
        if (empty($data)) {
            return false;
        }

        return $data[0];
    }

    /**
     * @param LinhaTef $arquivoTef
     * @return LinhaTef
     * @throws Exception
     */
    public function save(LinhaTef $arquivoTef)
    {
        $id = $arquivoTef->getId();
        $this->dao = new cl_linha_tef();
        $this->dao->numero_autorizacao = $arquivoTef->getNumeroAutorizacao();
        $this->dao->numero_cv = $arquivoTef->getNumeroCv();
        $this->dao->cartao = $arquivoTef->getCartao();
        $this->dao->data_venda = \DBDate::format($arquivoTef->getDataVenda(), \DBDate::DATA_EN);
        $this->dao->data_vencimento = \DBDate::format($arquivoTef->getDataVencimento(), \DBDate::DATA_EN);
        $this->dao->parcela = $arquivoTef->getParcela();
        $this->dao->total_parcelas = $arquivoTef->getTotalParcelas();
        $this->dao->valor_original = $arquivoTef->getValorOriginal();
        $this->dao->valor_bruto = $arquivoTef->getValorBruto();
        $this->dao->valor_descontos = $arquivoTef->getValorDescontos();
        $this->dao->valor_liquido = $arquivoTef->getValorLiquido();
        $this->dao->consistente = $arquivoTef->isConsistente() ? 't' : 'f';

        if (empty($id)) {
            $this->dao->incluir();
        } else {
            $this->dao->id = $id;
            $this->dao->alterar($id);
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception($this->dao->erro_msg);
        }

        $arquivoTef->setId($this->dao->id);
        return $arquivoTef;
    }


    public function scopeNumeroAutorizacao($numeroAutorizacao)
    {
        $this->scopes['numero_autorizacao'] = "numero_autorizacao = '{$numeroAutorizacao}'";
        return $this;
    }

    public function scopeNumeroCv($numeroCv)
    {
        $this->scopes['numero_cv'] = "numero_cv = {$numeroCv}";
        return $this;
    }

    public function scopeCartao($cartao)
    {
        $this->scopes['cartao'] = "cartao = '{$cartao}'";
        return $this;
    }

    public function scopeDataVenda($dataVenda)
    {
        $this->scopes['data_venda'] = "data_venda = '{$dataVenda}'";
        return $this;
    }

    public function scopeDataVencimento($dataVencimento)
    {
        $this->scopes['data_vencimento'] = "data_vencimento = '{$dataVencimento}'";
        return $this;
    }

    public function scopeParcela($parcela)
    {
        $this->scopes['parcela'] = "parcela = {$parcela}";
        return $this;
    }

    public function scopeTotalParcelas($totalParcelas)
    {
        $this->scopes['total_parcelas'] = "total_parcelas = {$totalParcelas}";
        return $this;
    }

    public function scopeValorOriginal($valorOriginal)
    {
        $this->scopes['valor_original'] = "valor_original = {$valorOriginal}";
        return $this;
    }

    public function scopeValorBruto($valorBruto)
    {
        $this->scopes['valor_bruto'] = "valor_bruto = {$valorBruto}";
        return $this;
    }

    public function scopeValorDescontos($valorDescontos)
    {
        $this->scopes['valor_descontos'] = "valor_descontos = {$valorDescontos}";
        return $this;
    }

    public function scopeValorLiquido($valorLiquido)
    {
        $this->scopes['valor_liquido'] = "valor_liquido = {$valorLiquido}";
        return $this;
    }

    public function scopeNaoProcessado()
    {
        $this->scopes['nao_processado'] = "
            not exists( select 1 from caixa.linha_tef_processado where linha_tef_id = linha_tef.id)
        ";
        return $this;
    }

    public function scopeId($idLinhaTef)
    {
        $this->scopes['id'] = " id = {$idLinhaTef}";
        return $this;
    }

    public function scopeConsistente()
    {
        $this->scopes['consistente'] = "consistente is true";
        return $this;
    }
}
