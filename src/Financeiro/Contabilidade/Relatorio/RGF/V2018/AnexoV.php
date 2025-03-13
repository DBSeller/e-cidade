<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoV as AnexoV2017;
use ECidade\Financeiro\Orcamento\Repository\RecursoRepository;
use Exception;
use RelatoriosLegaisBase;

/**
 * Class AnexoV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018
 */
class AnexoV extends AnexoV2017
{
    /**
     *
     */
    const CODIGO_RELATORIO = 187;

    /**
     * @var array
     */
    protected $linhasAnaliticas = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16);

    /**
     * @var array
     */
    private $recursos = array();

    /**
     * @return object
     * @throws Exception
     */
    public function getDadosSimplificado()
    {
        $this->getDados();

        return (object)array(
            'rp_nao_processado' => $this->aLinhasConsistencia[17]->rp_empenhado_nao_processado,
            'disponibilidade_caixa_liquida' => $this->aLinhasConsistencia[17]->disp_caixa_liquida
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        RelatoriosLegaisBase::getDados($trazerConfiguracaoPadrao);

        $this->processarColunaCaixaBruta();
        $this->processarColunaRestosAPagar();
        $this->processarColunaBalanceteDespesa();
        $this->processarObrigacoesFinanceiras();

        foreach ($this->linhasAnaliticas as $linha) {
            $this->processarFormulaDaLinhaEColuna($linha, 6);
        }

        $this->processarFormulaDaLinha(1);
        $this->processarFormulaDaLinha(14);
        $this->processarFormulaDaLinha(17);

        $this->arredondarValores(2);

        return $this->aLinhasConsistencia;
    }

    /**
     * @throws Exception
     */
    protected function processarObrigacoesFinanceiras()
    {
        $recursos = RecursoRepository::getValoresRecursosPorCompetencia(
            $this->getAno(),
            $this->getPeriodo()->getMesFinal(),
            '8211302',
            $this->getInstituicoes(true),
            false
        );

        foreach ($recursos as $recurso) {
            $this->recursos[$recurso->codigo] = $recurso->total;
        }

        foreach ($this->linhasAnaliticas as $linha) {
            $stdLinha = $this->aLinhasConsistencia[$linha];
            $recursosConfigurados = $stdLinha->parametros->orcamento->recurso->valor;

            if (empty($recursosConfigurados)) {
                continue;
            }

            foreach ($recursosConfigurados as $recursoConfigurado) {
                if (empty($this->recursos[$recursoConfigurado])) {
                    continue;
                }
                $stdLinha->financeira += $this->recursos[$recursoConfigurado];
            }
        }
    }
}
