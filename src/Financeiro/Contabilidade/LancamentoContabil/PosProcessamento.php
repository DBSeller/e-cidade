<?php
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil;

/**
 * Class PosProcessamento
 * Classe utilizada para executar validações no pos processamento
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class PosProcessamento
{
    /**
     * @var array
     */
    private static $classes = array(
        '\ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao\Conta',
        '\ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao\Atributos'
    );

    /**
     * Instancia as classes definidas para validação e executa o método processar
     * @param $codigoLancamento
     */
    public static function processar($codigoLancamento, $itensParaNaoProcessar = array())
    {
        foreach (self::$classes as $classe) {
            if (in_array($classe, $itensParaNaoProcessar)) {
                continue;
            }
            $classeProcessamento = new $classe;
            $classeProcessamento->processar($codigoLancamento);
        }
    }
}
