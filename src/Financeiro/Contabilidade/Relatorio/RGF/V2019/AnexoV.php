<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoV as AnexoV2018;

/**
 * Class AnexoV
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019
 */
class AnexoV extends AnexoV2018
{

    private $linhas = array();

    public function getDados($trazerConfiguracaoPadrao = true)
    {
        parent::getDados($trazerConfiguracaoPadrao);
        $this->organizaLinhas();
        return $this->aLinhasConsistencia;
    }

    public function getDadosSimplificado()
    {
        $this->getDados();

        return (object) array (
           'rp_nao_processado' => $this->aLinhasConsistencia[16]->rp_empenhado_nao_processado,
           'disponibilidade_caixa_liquida' => $this->aLinhasConsistencia[16]->disp_caixa_liquida
        );
    }

    /**
     * Organiza as linhas a serem impressas de acordo com o layout de 2019
     * @return \stdClass[] aLinhasConsistencia
     */
    private function organizaLinhas()
    {
        $ordemDePara = array(
            14 => 1,
            15 => 2,
            16 => 3,
            1  => 4,
            2  => 5,
            3  => 6,
            4  => 6,
            5  => 7,
            6  => 8,
            7  => 9,
            8  => 10,
            9  => 11,
            10 => 12,
            11 => 13,
            12 => 14,
            13 => 15,
            17 => 16,
        );

        $deParaDescricao = array(
            '1' => 'TOTAL DOS RECURSOS NÃO VINCULADOS (I)',
            '2' => 'Recursos Ordinários',
            '3' => 'Outros Recursos não Vinculados',
            '4' => 'TOTAL DOS RECURSOS VINCULADOS (II)',
            '5' => 'Receitas de Impostos e de Transferência de Impostos - Educação',
            '6' => 'Transferências do FUNDEB',
            '7' => 'Outros Recursos Vinculados à Educação',
            '8' => 'Receitas de Impostos e de Transferência de Impostos - Saúde',
            '9' => 'Outros Recursos Vinculados à Saúde',
            '10' => 'Recursos Vinculados à Assistência Social',
            '11' => 'Recursos Vinculados ao RPPS - Plano Previdenciário',
            '12' => 'Recursos Vinculados ao RPPS - Plano Financeiro',
            '13' => 'Recursos de Operações de Crédito (exceto vinculados à Educação e à Saúde)',
            '14' => 'Recursos de Alienação de Bens/Ativos',
            '15' => 'Outras Recursos Vinculados',
            '16' => 'TOTAL (III) = (I + II)',
        );

        $linhasOrganizadas = array();
        foreach ($ordemDePara as $ordemAntiga => $ordemNova) {
            if (empty($linhasOrganizadas[$ordemNova])) {
                $linhasOrganizadas[$ordemNova] = $this->aLinhasConsistencia[$ordemAntiga];
                $linhasOrganizadas[$ordemNova]->descricao = $deParaDescricao[$ordemNova];
            } else {
                $colunaAntiga = $this->aLinhasConsistencia[$ordemAntiga];
                $colunas = $colunaAntiga->colunas;
                foreach ($colunas as $dadosColuna) {
                    $nomeColuna = $dadosColuna->o115_nomecoluna;
                    $linhasOrganizadas[$ordemNova]->{$nomeColuna} += $colunaAntiga->{$nomeColuna};
                }
            }
        }

        foreach ($linhasOrganizadas as $linha) {
            $linha->disp_caixa_liquida_apos = ($linha->disp_caixa_liquida - $linha->rp_empenhado_nao_processado);
        }
        $linhasOrganizadas[6]->descricao = 'Transferências do FUNDEB';
        $this->aLinhasConsistencia = $linhasOrganizadas;
    }
}
