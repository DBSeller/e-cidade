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

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\ESocialEnvioRepository;
use ECidade\RecursosHumanos\ESocial\Repository\FechamentoEventosPeriodicosRepository;
use CgmRepository;
use stdClass;
use ParameterException;
use Exception;

class ReaberturaEventosPeriodicos extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    private $ano;

    private $mes;

    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function processar()
    {
        $bAlteracao = false;

        if ($this->getIndicativoPeriodoApuracao() === null) {
            throw new ParameterException("Indicativo de Período de Apuração não informado.");
        }

        if ($this->getIndicativoPeriodoApuracao() === '1' && empty($this->ano) && empty($this->mes)) {
            throw new ParameterException("Ano e mês do período de apuração não informados.");
        }

        if ($this->getIndicativoPeriodoApuracao() === '2') {
            $this->mes = "";
            if (empty($this->ano)) {
                throw new ParameterException("Ano do período de apuração não informados.");
            }
        }

        $dados = new stdClass();
        $dados->inscricao_empregador = CgmRepository::buscarCNPJEmpregador($this->cgm);
        $dados->indApuracao = (int) $this->getIndicativoPeriodoApuracao();
        $dados->perApur = $this->ano;

        if ($dados->indApuracao === 1 && !empty($this->mes)) {
            $dados->perApur .= "-{$this->mes}";
        }

        $dados->referencia = $this->cgm . "_" . $dados->perApur;
        $oEvento = new Evento(Tipo::S1298, $this->cgm, $dados->referencia, $dados);
        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }

        if ($oEvento->adicionarFila(false, $validaMd5)) {
            $repository = new ESocialEnvioRepository();
            $repository->scopeEmpregador($this->cgm);
            $repository->scopeEvento(current(Tipo::getLayout(Tipo::FECHAMENTO_EVENTOS_PERIODICOS)));
            $tipo = Tipo::getTitulos(Tipo::FECHAMENTO_EVENTOS_PERIODICOS);
            $referenciaFechamento = FechamentoEventosPeriodicosRepository::buscarReferenciaPorCompetenciaEmpregador(
                $this->cgm,
                $this->ano,
                $this->mes
            );

            $repository->scopeResponsavelPreenchimento($referenciaFechamento);
            $fechamento_periodico = current($repository->get());

            if (!$fechamento_periodico) {
                $competencia = str_replace("-", "/", $dados->perApur);
                $mensagem = "Não é possível reabrir a competência {$competencia},"
                    . " pois o evento {$tipo} não foi enviado.";
                throw new Exception($mensagem);
            }

            $repository->atualizarEvento(
                $fechamento_periodico->getCodigo(),
                $fechamento_periodico->getSituacaosalva(),
                true
            );
            $bAlteracao = true;
        }

        return $bAlteracao;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function setMes($mes)
    {
        $this->mes = $mes;
    }
}
