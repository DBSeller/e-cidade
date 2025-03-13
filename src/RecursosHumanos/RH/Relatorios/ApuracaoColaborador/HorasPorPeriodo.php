<?php
/**
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

namespace ECidade\RecursosHumanos\RH\Relatorios\ApuracaoColaborador;

use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\Repository\EspelhoPontoCache;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;

/**
 * Class Faltas
 * @package ECidade\RecursosHumanos\RH\Relatorios\ApuracaoColaborador
 */
class HorasPorPeriodo extends Layout
{
    public function __construct()
    {
        $this->titulo1Relatorio = 'HORAS POR PERÍODO';
        parent::__construct();
    }

    public function imprimirDados()
    {
        if (count($this->getDadosRelatorio()) == 0) {
            throw new \BusinessException('Nenhum registro encontrado para os filtros selecionados.');
        }

        if (($this->getPdf()->gety () > $this->getPdf()->h - 35) || ($this->getPdf()->getCurrentPage() < 1)) {
            $this->getPdf()->AddPage();
        }

        $dadosRelatorio = $this->getDadosRelatorio();
        foreach ($dadosRelatorio as $index => $servidor) {
            if ($index == 'totalizadorHoras') {
                continue;
            }

            if (isset($servidor->matricula) && isset($servidor->situacoes)) {
                $servidor->relatorio = $this->titulo2Relatorio;

                $this->setServidorAtual($servidor);
                $this->linhaServidor();

                foreach ($servidor->situacoes as $situacao) {
                    $this->linhaDados($situacao);
                }

                if (!empty($dadosRelatorio['matriculasInconsistentes'][$servidor->matricula])) {
                    $this->linhaInconsistencia($dadosRelatorio['matriculasInconsistentes'][$servidor->matricula]);
                }
                $this->getPdf()->Ln();
            }
        }

        $this->linhaTotalizadora($dadosRelatorio['totalHorasAssentamento']);
    }

    public function montarConteudo()
    {
        $dadosRelatorio = $this->getDadosRelatorio();
        $indiceTotalizador = 'totalHorasAssentamento';
        $dadosRelatorio[$indiceTotalizador] = array();
        $matriculasInconsistencia = array();

        foreach ($this->servidores as $servidor) {

            $situacoes = array();

            foreach ($this->getDatasIntervalo() as $dataAtual) {
                $this->setEscalaServidorNaData($servidor->getEscala($dataAtual));

                $matricula = $servidor->getMatricula();
                if (!isset($dadosRelatorio[$matricula])) {
                    $dadosRelatorio[$matricula] = $this->getDadosServidor($servidor);
                }

                unset($dadosRelatorio[$matricula]->jornadas);

                $espelhoPonto = EspelhoPontoCache::init()->getEspelhoPontoCacheValido(array($matricula), $dataAtual, $dataAtual);
                if(empty($espelhoPonto[$matricula])) {

                    $matriculasInconsistencia[$matricula][] = $dataAtual->getDate(\DBDate::DATA_PTBR);
                    continue;
                }

                $espelhoPonto = array_pop($espelhoPonto[$matricula]);
                $totalizadores = $espelhoPonto['totalHorasAssentamento'];
                foreach ($totalizadores as $totalizadorHora) {
                    $situacao = clone $totalizadorHora;

                    if (empty($situacao->horasDiurnas) && empty($situacao->horasNoturnas)) {
                        continue;
                    }

                    if(empty($situacoes[$situacao->sequencial])) {
                        $situacoes[$situacao->sequencial] = $situacao;
                    } else {
                        $situacoes[$situacao->sequencial]->horasDiurnas = EspelhoPonto::somarTotalizador(array(
                            $situacoes[$situacao->sequencial]->horasDiurnas,
                            $situacao->horasDiurnas,
                        ));

                        $situacoes[$situacao->sequencial]->horasNoturnas = EspelhoPonto::somarTotalizador(array(
                            $situacoes[$situacao->sequencial]->horasNoturnas,
                            $situacao->horasNoturnas,
                        ));
                    }
                }
            }

            foreach ($situacoes as $totalHoras) {
                $situacao = clone $totalHoras;

                if(empty($dadosRelatorio[$indiceTotalizador][$situacao->sequencial])) {
                    $dadosRelatorio[$indiceTotalizador][$situacao->sequencial] = $situacao;
                } else {
                    $dadosRelatorio[$indiceTotalizador][$situacao->sequencial]->horasDiurnas = EspelhoPonto::somarTotalizador(array(
                        $situacao->horasDiurnas,
                        $dadosRelatorio[$indiceTotalizador][$situacao->sequencial]->horasDiurnas,
                    ));

                    $dadosRelatorio[$indiceTotalizador][$situacao->sequencial]->horasNoturnas = EspelhoPonto::somarTotalizador(array(
                        $situacao->horasNoturnas,
                        $dadosRelatorio[$indiceTotalizador][$situacao->sequencial]->horasNoturnas,
                    ));
                }
            }

            $dadosRelatorio[$matricula]->situacoes = $situacoes;
        }

        $msgs = array();
        foreach ($matriculasInconsistencia as $matricula => $datasInconsistente) {
            $sDatasInconsistente = implode(', ', $datasInconsistente);

            $msg  = "Não há processamento do ponto para a(s) data(s) {$sDatasInconsistente} na matrícula: {$matricula}.\n";
            $msg .= "Acesse o menu RH > Relatórios > Ponto Eletrônico > Espelho Ponto e faça a emissão do espelho ponto para o servidor.";

            $msgs[$matricula] = $msg;
        }

        $dadosRelatorio['matriculasInconsistentes'] = $msgs;
        $this->setDadosRelatorio($dadosRelatorio);
    }

    public function setPdf($pdf)
    {
        parent::setPdf($pdf);
    }

    protected function linhaDados($dadosApurados, $exibeMarcacao = true)
    {
        $sequencial = in_array($dadosApurados->sequencial,
            array(
                'HorasNormais',
                'HorasFaltas',
                'HorasExt50diurnas',
                'HorasExt50noturnas',
                'HorasExt75diurnas',
                'HorasExt75noturnas',
                'HorasExt100diurnas',
                'HorasExt100noturnas',
                'HorasAdicional',
                'HorasSaidaAntecipada',
                'HorasAtraso',
                'HorasExt50',
                'HorasExt75',
                'HorasExt100',
                'HorasExt50NaoAutorizadas',
                'HorasExt75NaoAutorizadas',
                'HorasExt100NaoAutorizadas',
                'HorasExtNaoAutorizadas',
            )
        ) ? '---' : $dadosApurados->sequencial;

        $encoding = mb_internal_encoding();
        $dadosApurados->descricao = mb_strtoupper($dadosApurados->descricao, $encoding);
        $dadosApurados->horasDiurnas = mb_strtoupper($dadosApurados->horasDiurnas, $encoding);

        if (!empty($dadosApurados->horasDiurnas) && $dadosApurados->horasDiurnas != '00:00' && $dadosApurados->horasDiurnas != '0:00') {
            $this->getPdf()->setBold(false);
            $this->getPdf()->SetX(100);
            $this->getPdf()->Cell(10, $this->getAlturaLinha(), $sequencial, 0, 0, 'L');
            $this->getPdf()->Cell(62, $this->getAlturaLinha(), $dadosApurados->descricao, 0, 0, 'L');
            $this->getPdf()->Cell(25, $this->getAlturaLinha(), $dadosApurados->horasDiurnas, 0, 1, 'R');
        }

        if (!empty($dadosApurados->horasNoturnas) && $dadosApurados->horasNoturnas != '00:00' && $dadosApurados->horasNoturnas != '0:00') {
            $this->getPdf()->setBold(false);
            $this->getPdf()->SetX(100);
            $this->getPdf()->Cell(10, $this->getAlturaLinha(), $sequencial, 0, 0, 'L');
            $this->getPdf()->Cell(62, $this->getAlturaLinha(), $dadosApurados->descricao . ' ' . 'NOTURNO(a)', 0, 0, 'L');
            $this->getPdf()->Cell(25, $this->getAlturaLinha(), $dadosApurados->horasNoturnas, 0, 1, 'R');
        }
    }

    protected function linhaInconsistencia($msg)
    {
        $this->getPdf()->setBold(false);
        $this->getPdf()->MultiCell(200, $this->getAlturaLinha(), $msg, 0, 'L');
        $this->getPdf()->Ln();
    }

    protected function linhaTotalizadora($totalHoras)
    {
        $this->getPdf()->setBold(true);
        $this->getPdf()->Cell(200, $this->getAlturaLinha(), 'TOTAL GERAL', 0, 1, 'L');

        foreach ($totalHoras as $situacao) {
            $this->linhaDados($situacao);
        }
    }
}
