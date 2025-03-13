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

namespace ECidade\Educacao\Escola\Service;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Factory\FormulaCalculoAreaFactory;
use ECidade\Educacao\Escola\Mapper\AvaliacaoPorAreaConhecimento;
use ECidade\Educacao\Escola\Mapper\DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Enum\Educacao\Escola\FormaObtencaoEnum;
use Exception;

class CalcularResultadoAreaService
{
    /**
     * @var AreaProcedimento
     */
    private $areaProcedimento;
    /**
     * @var DiarioAvaliacaoDisciplina[]
     */
    private $diarioAvaliacoesDisciplinas = [];

    /**
     * @param AreaProcedimento $areaProcedimento
     * @param DiarioAvaliacaoDisciplina[] $diarioAvaliacoesDisciplinas
     * @throws Exception
     */
    public function __construct(AreaProcedimento $areaProcedimento, array $diarioAvaliacoesDisciplinas)
    {
        $this->areaProcedimento = $areaProcedimento;
        $this->diarioAvaliacoesDisciplinas = $diarioAvaliacoesDisciplinas;
    }

    /**
     * @return DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper[]
     * @throws Exception
     */
    public function calcular()
    {
        $diarioAvaliacoesDisciplinasPorArea = $this->organizaAvaliacoesPorAreaConhecimento();

        $this->calcularAvaliacoes($diarioAvaliacoesDisciplinasPorArea);
        $this->calcularResultado($diarioAvaliacoesDisciplinasPorArea);

        return $diarioAvaliacoesDisciplinasPorArea;
    }

    /**
     * Retorna um array de Avaliacoes organizado por área de conhecimento
     * @return DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper[]
     */
    private function organizaAvaliacoesPorAreaConhecimento()
    {
        $areas = [];
        foreach ($this->diarioAvaliacoesDisciplinas as $diarioAvaliacoesDisciplina) {
            $areaConhecimento = $diarioAvaliacoesDisciplina->getRegencia()->getAreaConhecimento();

            if (array_key_exists($areaConhecimento->getCodigo(), $areas)) {
                $areas[$areaConhecimento->getCodigo()]->addDiarioAvaliacoes($diarioAvaliacoesDisciplina);
            } else {
                $areas[$areaConhecimento->getCodigo()] = new DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper();
                $areas[$areaConhecimento->getCodigo()]->setAreaConhecimento($areaConhecimento);
                $areas[$areaConhecimento->getCodigo()]->addDiarioAvaliacoes($diarioAvaliacoesDisciplina);
            }
        }

        return $areas;
    }

    /**
     * Calcula a avaliação de uma área de conhecimento percorrendo os elementos do procedimento de avaliação por áreaa
     * de conhecimento aplicando a formula de cálculo do elemento conforme configurado no procedimento de avaliação
     *
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $diarioAvaliacaoPorArea
     * @return AvaliacaoPorAreaConhecimento[]
     * @throws Exception
     */
    private function calcularAvaliacaoArea(DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper $diarioAvaliacaoPorArea)
    {
        $avaliacoes = $this->areaProcedimento->getAvaliacoes();

        $avaliacoesPorArea = [];
        foreach ($avaliacoes as $avaliacao) {
            $formula = FormulaCalculoAreaFactory::get($avaliacao->getFormaObtencao());
            $formula->setAvaliacoesDisciplina($diarioAvaliacaoPorArea->getDiarioAvaliacoesDisciplinas());
            $avaliacaoPorArea = $formula->calcularAvaliacoes($avaliacao);

            $avaliacoesPorArea[] = $avaliacaoPorArea;
        }

        return $avaliacoesPorArea;
    }

    /**
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper[] $diarioAvaliacoesDisciplinasPorArea
     * @throws Exception
     */
    protected function calcularAvaliacoes(array $diarioAvaliacoesDisciplinasPorArea)
    {
        foreach ($diarioAvaliacoesDisciplinasPorArea as $diarioAvaliacaoPorArea) {
            $diarioAvaliacaoPorArea->setAvaliacoes($this->calcularAvaliacaoArea($diarioAvaliacaoPorArea));
        }
    }

    /**
     * @param DiarioAvaliacaoDisciplinasPorAreaConhecimentoMapper[] $diarioAvaliacoesDisciplinasPorArea
     * @throws Exception
     */
    private function calcularResultado(array $diarioAvaliacoesDisciplinasPorArea)
    {
        $resultado = $this->areaProcedimento->getResultado();

        foreach ($diarioAvaliacoesDisciplinasPorArea as $diarioAvaliacaoPorArea) {
            $avaliacoesDisciplinas = $diarioAvaliacaoPorArea->getDiarioAvaliacoesDisciplinas();

            $formula = FormulaCalculoAreaFactory::get($resultado->getFormaObtencao());
            $formula->setAvaliacoesDisciplina($avaliacoesDisciplinas);
            $formula->setAvaliacoesAreaConhecimento($diarioAvaliacaoPorArea->getAvaliacoes());

            $diarioAvaliacaoPorArea->setResultado($formula->calcularResultado($resultado));

            if ($resultado->getFormaObtencao()->value() !== FormaObtencaoEnum::ATRIBUIDO &&
            $avaliacoesDisciplinas[0]->getResultadoFinal()->getValorAprovacao() == 'Parecer') {
                $sResultadoFrequencia = $avaliacoesDisciplinas[0]->getResultadoFinal()->getResultadoFrequencia();
                $resultadoAtribuido = $avaliacoesDisciplinas[0]->getResultadoFinal()->getResultadoFinal();
                $resultadoPorAreaConhecimeno = $diarioAvaliacaoPorArea->getResultado();
                $resultadoPorAreaConhecimeno->setResultadoAvaliacao($resultadoAtribuido);
                $resultadoPorAreaConhecimeno->setResultadoFrequencia($sResultadoFrequencia);
                $diarioAvaliacaoPorArea->setResultado($resultadoPorAreaConhecimeno);
            }
        }
    }
}
