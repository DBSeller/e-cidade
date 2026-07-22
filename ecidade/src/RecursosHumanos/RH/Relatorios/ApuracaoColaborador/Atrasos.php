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

/**
 * Class Atrasos
 * @package ECidade\RecursosHumanos\RH\Relatorios\ApuracaoColaborador
 */
class Atrasos extends Layout
{
    public function __construct()
    {
        $this->titulo2Relatorio = 'Atrasos';
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

        foreach ($this->getDadosRelatorio() as $servidor) {
            $servidor->relatorio = $this->titulo2Relatorio;

            $this->setServidorAtual($servidor);
            $this->linhaServidor();

            foreach ($servidor->jornadas as $jornada) {
                $this->linhaJornada($jornada);

                foreach ($jornada->dadosApurados as $atrasos) {
                    $this->linhaDados($atrasos);
                }
            }

            $this->getPdf()->Ln();
        }
    }

    public function montarConteudo()
    {
        $dadosRelatorio = $this->getDadosRelatorio();

        foreach ($this->servidores as $servidor) {
            if ($servidor->isRescindido()) {
                continue;
            }
            foreach ($this->getDatasIntervalo() as $dataAtual) {
                $this->setEscalaServidorNaData($servidor->getEscala($dataAtual));

                if ($this->getEscalaServidorNaData() == null) {
                    continue;
                }

                $diaTrabalhoModel = $this->getDiaTrabalho($servidor, $dataAtual);

                if($diaTrabalhoModel === null) {
                    continue;
                }

                if ($diaTrabalhoModel->getHorasAtraso() === '') {
                    continue;
                }

                if ($diaTrabalhoModel->getHorasAtraso() === '00:00') {
                    continue;
                }

                $matricula = $servidor->getMatricula();
                $codigoJornada = $diaTrabalhoModel->getJornada()->getCodigo();

                if (!isset($dadosRelatorio[$matricula])) {
                    $dadosRelatorio[$matricula] = $this->getDadosServidor($servidor);
                }

                if (!isset($dadosRelatorio[$matricula]->jornadas[$codigoJornada])) {
                    $dadosRelatorio[$matricula]->jornadas[$codigoJornada] = $this->getDadosJornada($this->getEscalaServidorNaData(), $diaTrabalhoModel);
                }

                $atraso = new \stdClass();
                $atraso->data = $diaTrabalhoModel->getData()->getDate(\DBDate::DATA_PTBR);
                $atraso->dia = \DBDate::getDiasSemanaAbreviado(date('w', $diaTrabalhoModel->getData()->getTimeStamp()));
                $atraso->horas = $diaTrabalhoModel->getHorasAtraso();
                $atraso->marcacoes = $this->getMarcacoes($diaTrabalhoModel->getMarcacoes());
                $atraso->tipo = 'Atraso';

                $dadosRelatorio[$matricula]->jornadas[$codigoJornada]->dadosApurados[] = $atraso;
            }
        }

        $this->setDadosRelatorio($dadosRelatorio);
    }

    public function setPdf($pdf)
    {
        parent::setPdf($pdf);
    }
}
