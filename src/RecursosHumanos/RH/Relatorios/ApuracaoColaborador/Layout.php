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

use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\Relatorios\EstruturaBasica;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;

/**
 * Class Layout
 * @package ECidade\RecursosHumanos\RH\Relatorios\ApuracaoColaborador
 */
class Layout extends EstruturaBasica
{
    public static $filtros = array(
      1 => 'Seleção',
      2 => 'Matrícula',
      3 => 'Local de Trabalho'
    );

    /**
     * @var \stdClass
     */
    private $servidorAtual;

    /**
     * @var array
     */
    private $dadosRelatorio = array();

    /**
     * @var EscalaServidor
     */
    private $escalaServidorNaData;

    /**
     * @return EscalaServidor
     */
    public function getEscalaServidorNaData()
    {
        return $this->escalaServidorNaData;
    }

    /**
     * @param EscalaServidor $escalaServidorNaData
     */
    public function setEscalaServidorNaData($escalaServidorNaData)
    {
        $this->escalaServidorNaData = $escalaServidorNaData;
    }

    /**
     * @param $filtro
     */
    public function setFiltroSelecionado($filtro)
    {
        $this->filtro = self::$filtros[$filtro];
    }

    /**
     * @param \stdClass $servidor
     */
    protected function setServidorAtual($servidor)
    {
        $this->servidorAtual = $servidor;
    }

    protected function linhaServidor()
    {
        $matriculaServidor = empty($this->servidorAtual->relatorio) ?
            $this->servidorAtual->matricula . ' - ' . $this->servidorAtual->nome
            :
            $this->servidorAtual->matricula . ' - ' . $this->servidorAtual->nome . ' - ' . $this->servidorAtual->relatorio;

        $this->getPdf()->setBold(true);
        $this->getPdf()->Cell(192, $this->getAlturaLinha(), $matriculaServidor, 0, 1, 'L');
    }

    protected function linhaJornada($jornada)
    {
        $horario = "Jornada: {$jornada->jornada} - {$jornada->horario}";

        $this->getPdf()->setBold(false);
        $this->getPdf()->Cell(96, $this->getAlturaLinha(), "Escala: {$jornada->escala}", 0, 0, 'R');

        $this->getPdf()->SetX(110);
        $this->getPdf()->Cell(82, $this->getAlturaLinha(), $horario, 0, 1, 'L');
    }

    protected function linhaDados($dadosApurados, $exibeMarcacao = true)
    {
        $data = "{$dadosApurados->data} {$dadosApurados->dia}";
        $marcacoes = $exibeMarcacao ? $dadosApurados->marcacoes : "";

        $this->getPdf()->setBold(false);

        $this->getPdf()->Cell(20, $this->getAlturaLinha(), $data, 0, 0, 'L');
        $this->getPdf()->Cell(76, $this->getAlturaLinha(), $marcacoes, 0, 0, 'L');

        $this->getPdf()->SetX(110);
        $this->getPdf()->Cell(25, $this->getAlturaLinha(), $dadosApurados->tipo, 0, 0, 'L');
        $this->getPdf()->Cell(62, $this->getAlturaLinha(), $dadosApurados->horas, 0, 1, 'L');
    }

    protected function getDadosRelatorio()
    {
        return $this->dadosRelatorio;
    }

    /**
     * @param $dadosRelatorio
     */
    protected function setDadosRelatorio($dadosRelatorio)
    {
        $this->dadosRelatorio = $dadosRelatorio;
    }

    /**
     * @param MarcacoesPontoCollection $marcacoesPontoCollection
     * @return string
     */
    protected function getMarcacoes(MarcacoesPontoCollection $marcacoesPontoCollection)
    {
        $marcacoes = array();

        foreach ($marcacoesPontoCollection->getMarcacoes() as $marcacao) {
            if ($marcacao === null || $marcacao->getMarcacao() === null) {
                continue;
            }

            $marcacoes[] = $marcacao->getMarcacao()->format('H:i');
        }

        return implode(" ", $marcacoes);
    }

    /**
     * @param \Servidor $servidor
     * @return \stdClass
     */
    protected function getDadosServidor(\Servidor $servidor)
    {
        $dadosServidor = new \stdClass();
        $dadosServidor->matricula = $servidor->getMatricula();
        $dadosServidor->nome = $servidor->getCgm()->getNome();
        $dadosServidor->jornadas = array();

        return $dadosServidor;
    }

    /**
     * @param EscalaServidor $escalaServidorData
     * @param DiaTrabalho $diaTrabalho
     * @return \stdClass
     */
    protected function getDadosJornada(EscalaServidor $escalaServidorData, DiaTrabalho $diaTrabalho)
    {
        $dadosJornada = new \stdClass();
        $dadosJornada->escala = $escalaServidorData->getEscalaTrabalho()->getCodigo();
        $dadosJornada->jornada = $diaTrabalho->getJornada()->getCodigo();

        $horarios = array();

        foreach ($diaTrabalho->getJornada()->getHoras() as $hora) {
            $horarios[] = $hora->sHora;
        }

        $dadosJornada->horario = implode(' ', $horarios);
        $dadosJornada->dadosApurados = array();

        return $dadosJornada;
    }

    /**
     * @param \Servidor $servidor
     * @param \DBDate $dataAtual
     * @return null|DiaTrabalho
     */
    protected function getDiaTrabalho(\Servidor $servidor, \DBDate $dataAtual)
    {
        $diaTrabalhoRepository = new DiaTrabalhoRepository();
        $diaTrabalhoRepository->setEscalaServidor($this->escalaServidorNaData);
        $diaTrabalhoRepository->buscarMarcacoesCalculos(true);
        $diaTrabalhoModel = $diaTrabalhoRepository->getDiaTrabalhoProcessadoServidor($servidor, $dataAtual);

        if (!$diaTrabalhoModel->getJornada()->isDiaTrabalhado()) {
            return null;
        }


        return $diaTrabalhoModel;
    }
}
