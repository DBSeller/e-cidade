<?php
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao;

use ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao\InterfacePosProcessamento;

/**
 * Class Conta
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao
 */
class Conta implements InterfacePosProcessamento
{

    /**
     * Executa os métodos que validam os dados de conta
     * @param $codigoLancamento
     * @throws \Exception
     */
    public function processar($codigoLancamento)
    {
        $this->possuiContasRepetidas($codigoLancamento);
    }

    /**
     * Verifica se houve repetição de contas lançadas a crédito ou débito no mesmo evento contábil.
     * @param integer $codigoLancamento
     * @throws \Exception
     */
    private function possuiContasRepetidas($codigoLancamento)
    {
        $contas = array('c69_debito', 'c69_credito');
        foreach ($contas as $coluna) {
            $descricaoOrigem = $coluna === 'c69_debito' ? 'Débito' : 'Crédito';
            $campos = implode(',', array(
                "{$coluna} as conta",
                "c69_anousu as ano_lancamento",
                "'{$descricaoOrigem}' as origem",
                'array_to_string(array_accum(c69_ordem order by c69_ordem), \', \') as ordens',
                "count(*)"
            ));

            $where  = " c69_codlan = {$codigoLancamento} ";
            $where .= " group by 1, 2 having count(*) > 1 ";
            $daoConlancamval = new \cl_conlancamval();
            $buscaLinhas = $daoConlancamval->sql_query_file(null, $campos, null, $where);
            $resultBusca = db_query($buscaLinhas);
            if (!$resultBusca) {
                throw new \Exception("Ocorreu um erro ao validar as informações do lançamento contábil executado.");
            }
            if (pg_num_rows($resultBusca) > 0) {
                $daoDocumento = new \cl_conlancamdoc();
                $where = "c70_codlan = {$codigoLancamento}";
                $buscaDocumento = $daoDocumento->sql_query(null, "conhistdoc.*", null, $where);
                $resultDocumento = db_query($buscaDocumento);
                if (!$resultDocumento) {
                    throw new \Exception("Não foi possível consultar o documento contábil.");
                }

                $stdDadosDocumento = \db_utils::fieldsMemory($resultDocumento, 0);
                $stdDadosLancamento = \db_utils::fieldsMemory($resultBusca, 0);
                $dadosConta = \ContaPlanoPCASPRepository::getContaPorReduzido(
                    $stdDadosLancamento->conta,
                    $stdDadosLancamento->ano_lancamento
                );
                $descricaoConta = "{$dadosConta->getEstrutural()} - {$dadosConta->getDescricao()}";
                $mensagem  = "Identificamos o lançamento duplicado da conta abaixo nos lançamentos ";
                $mensagem .= "{$stdDadosLancamento->ordens} a {$stdDadosLancamento->origem}.\n\n";
                $mensagem .= "Documento: {$stdDadosDocumento->c53_coddoc} - {$stdDadosDocumento->c53_descr}\n";
                $mensagem .= "Conta: {$descricaoConta}\n";
                $mensagem .= "Código da Conta: {$dadosConta->getCodigoConta()}\n";
                $mensagem .= "Código Reduzido: {$stdDadosLancamento->conta}\n";
                throw new \Exception($mensagem);
            }
        }
    }
}
