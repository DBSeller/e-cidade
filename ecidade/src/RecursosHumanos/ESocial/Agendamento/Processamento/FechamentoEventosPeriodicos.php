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
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\ESocialEnvioRepository;
use Exception;
use Instituicao;

class FechamentoEventosPeriodicos extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * Rubrica constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws Exception
     * @throws \DBException
     */
    public function processar()
    {
        $competencia = new \stdClass();
        // Validacoes de campos obrigatórios
        if (empty($this->getIndicativoPeriodoApuracao()) || empty($this->getAnoCompetencia())
            || empty($this->getMesCompetencia())) {
            if (empty($this->getIndicativoPeriodoApuracao())) {
                throw new \BusinessException("É Necessário informar o período de Apuração.");
            }
            if (empty($this->getAnoCompetencia())) {
                throw new \BusinessException("É Necessário informar o Ano da competência.");
            }
            // Caso seja mensal = 1, é necessário informar o mes
            if ($this->getIndicativoPeriodoApuracao() == 1) {
                if (empty($this->getMesCompetencia())) {
                    throw new \BusinessException("É Necessário informar o Mes da competência.");
                }
            }
        }

        $bAlteracao = false;
        $oDadosESocial = new DadosESocial();

        $oDadosESocial->setInstituicao(new Instituicao(db_getsession('DB_instit')));
        $oDadosESocial->setReponsavelPeloPreenchimento($this->cgm);
        $oDadosESocial->setAno($this->getAnoCompetencia());
        $oDadosESocial->setMes($this->getMesCompetencia());
        $oDadosESocial->setIndicativoPeriodoApuracao($this->getIndicativoPeriodoApuracao());
        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::FECHAMENTO_EVENTOS_PERIODICOS);
        $oFormatter = FormatterFactory::get(Tipo::S1299);


        $aDadosPreenchimentoEmpregador = $oFormatter->formatar($oDadosPreenchimento);
        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }
        foreach ($aDadosPreenchimentoEmpregador as $iIndice => $oDados) {
            $oEvento = new Evento(TIPO::S1299, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila(false, $validaMd5)) {
                $repository = new ESocialEnvioRepository();
                $repository->scopeEmpregador($this->cgm);
                $repository->scopeEvento(current(Tipo::getLayout(Tipo::REABERTURA_EVENTOS_PERIODICOS)));
                $responsavel = "{$this->cgm}_{$oDados->perApur}";
                $repository->scopeResponsavelPreenchimento($responsavel);
                $reabertura_eventos = current($repository->get());
                if ($reabertura_eventos) {
                    $repository->atualizarEvento(
                        $reabertura_eventos->getCodigo(),
                        $reabertura_eventos->getSituacaosalva(),
                        true
                    );
                }
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }
}
