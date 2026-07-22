<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020;

/**
 * Class AnexoIV
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020
 */
class AnexoIV extends \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoIV
{

    /**
     * @return \stdClass[]
     * @throws \Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        parent::getDados($trazerConfiguracaoPadrao);
        $this->alterarDescricaoLinhas();

        foreach ($this->aLinhasConsistencia[107]->colunas as $indiceColuna => $coluna) {
            $this->aLinhasConsistencia[107]->colunas[$indiceColuna]->o116_formula = 'F[95]';
        }
        $this->processarFormulaDaLinha(107);
        $this->processarFormulaDaLinha(112);
        $this->processarFormulaDaLinha(115);
        $this->processarFormulaDaLinha(116);
        return $this->aLinhasConsistencia;
    }

    /**
     * Altera os labels de linhas já existentes do relatório.
     */
    protected function alterarDescricaoLinhas()
    {
        $this->aLinhasConsistencia[24]->totalizar = true;
        $this->aLinhasConsistencia[83]->totalizar = true;
        /* quadro da receita */
        $this->aLinhasConsistencia[49]->descricao = 'TOTAL DAS DESPESAS PREVIDENCIÁRIAS RPPS (V) = (V + VI)';
        $this->aLinhasConsistencia[50]->descricao = 'RESULTADO PREVIDENCIÁRIO (VI) = (IV - V)';
        $this->aLinhasConsistencia[60]->descricao = 'RECEITAS CORRENTES (VII)';
        $this->aLinhasConsistencia[87]->descricao = 'RECEITAS DE CAPITAL (VIII)';
        $this->aLinhasConsistencia[91]->descricao = 'TOTAL DAS RECEITAS PREVIDENCIÁRIAS RPPS - (IX) = (VII + VIII)';
        /* quadro da despesa */
        $this->aLinhasConsistencia[107]->descricao = 'TOTAL DAS DESPESAS PREVIDENCIÁRIAS RPPS (X)';
        $this->aLinhasConsistencia[108]->descricao = 'RESULTADO PREVIDENCIÁRIO (XI) = (IX - X)2';
    }
}
