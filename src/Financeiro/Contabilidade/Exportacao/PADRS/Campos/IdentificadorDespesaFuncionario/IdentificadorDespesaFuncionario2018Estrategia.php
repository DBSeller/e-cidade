<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\IdentificadorDespesaFuncionario;

use ECidade\Enum\Financeiro\Empenho\PAD\IdentificadorDespeaFuncionarioEnum;
use Exception;

class IdentificadorDespesaFuncionario2018Estrategia extends IdentificadorDespesaFuncionarioEstrategia
{
    /**
     * @var IdentificadorDespeaFuncionarioEnum
     */
    protected $identificadorDespensa;

    /**
     * @return object|null
     * @throws Exception
     */
    protected function identificaNaturezaElemento()
    {
        $sql = "
            select substr(o56_elemento, 1, 3) as classificacao, substr(o56_elemento, 6, 2) as natureza
              from empempenho
              join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
                             and empempenho.e60_coddot = orcdotacao.o58_coddot
              join orcelemento on o56_anousu = o58_anousu
                              and o58_codele = o56_codele
             where e60_numemp ={$this->empenho}
        ";

        if (!$resultado = db_query($sql)) {
            $this->handlegetValorError();
        }

        if (pg_num_rows($resultado)) {
            return pg_fetch_object($resultado, 0);
        }

        return null;
    }

    /**
     * @return IdentificadorDespeaFuncionarioEnum
     * @throws Exception
     */
    public function getIdentificador()
    {
        $data = $this->identificaNaturezaElemento();

        if (is_null($data)) {
            return $this->identificadorDespensa = new IdentificadorDespeaFuncionarioEnum(
                IdentificadorDespeaFuncionarioEnum::NAO_APLICA
            );
        }

        if ($this->isObrigracaoPatronal($data->natureza)) {
            return $this->identificadorDespensa = new IdentificadorDespeaFuncionarioEnum(
                IdentificadorDespeaFuncionarioEnum::OBRIGACAO_PATRONAL
            );
        }

        if ($this->isFolhaPagamento($data->classificacao, $data->natureza)) {
            return $this->identificadorDespensa = new IdentificadorDespeaFuncionarioEnum(
                IdentificadorDespeaFuncionarioEnum::FOLHA_PAGAMENTO
            );
        }

        if ($this->isIndenizacaoNaoInclusaFolhaPagamento($data->natureza)) {
            return $this->identificadorDespensa = new IdentificadorDespeaFuncionarioEnum(
                IdentificadorDespeaFuncionarioEnum::INDENIZACOES
            );
        }

        return $this->identificadorDespensa = new IdentificadorDespeaFuncionarioEnum(
            IdentificadorDespeaFuncionarioEnum::NAO_APLICA
        );
    }

    public function getDescricao()
    {
        return $this->getIdentificador()->name();
    }

    public function getValor()
    {
        return $this->getIdentificador()->value();
    }

    /**
     * @throws Exception
     */
    private function handlegetValorError()
    {
        throw new Exception(sprintf(
            '%s %s',
            "Não foi possível buscar o identificador da despesa do funcionário do empenho {$this->empenho}.",
            "Por favor entre em contato com o administrador do sistema."
        ));
    }

    /**
     * @param string $classificacao
     * @param string $natureza
     * @return bool
     */
    private function isFolhaPagamento($classificacao, $natureza)
    {
        // Elementos de despesa de 01 a 12, 16, 17, 53 a 59
        $valores = array_map(function ($valor) {
            return str_pad($valor, '2', '0', STR_PAD_LEFT);
        }, range(1, 12));

        $valores = array_merge($valores, [16, 17], range(53, 59));

        if ($classificacao == '331' || in_array($natureza, $valores)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $naturreza
     * @return bool
     */
    private function isObrigracaoPatronal($naturreza)
    {
        // Elemento de despesa 13
        if ($naturreza == '13') {
            return true;
        }

        return false;
    }

    /**
     * @param $naturreza
     * @return bool
     */
    private function isIndenizacaoNaoInclusaFolhaPagamento($naturreza)
    {
        // Elementos de despesa 14, 15, 46, 47, 49, 93, 94, 95
        if (in_array($naturreza, [14, 15, 46, 47, 49, 93, 94, 95])) {
            return true;
        }

        return false;
    }
}
