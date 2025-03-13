<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\HoraExtra;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory\TipoHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\ExtraDiaTrabalho as RegraExtraDiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\CalculoHoraLinear;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;

/**
 * Class HoraExtraCalculo
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author John Lenon Reis <john.reis@dbseller.com.br>
 */
class HoraExtraCalculo extends HoraExtra
{

    /**
     * @var Extra
     */
    private $oHorasExtra50;

    /**
     * @var Extra
     */
    private $oHorasExtra75;

    /**
     * @var Extra
     */
    private $oHorasExtra100;

    /**
     * @var Extra
     */
    private $oHorasExtra50NaoAutorizada;

    /**
     * @var Extra
     */
    private $oHorasExtra75NaoAutorizada;

    /**
     * @var Extra
     */
    private $oHorasExtra100NaoAutorizada;

    /**
     * @var Extra
     */
    private $oHorasExtraNaoAutorizada;

    /**
     * @var \DateTime
     */
    private $oInicioHorarioNoturno;

    /**
     * @var \DateTime
     */
    private $oFinalHorarioNoturno;

    /**
     * @var int
     */
    private $iMaximoHorasExtras50;

    /**
     * @var int
     */
    private $iMaximoHorasExtras75;

    /**
     * @var int
     */
    private $iMaximoHorasExtras100;

    /**
     * @var int
     */
    private $iHorasExtrasAutorizadas;

    /**
     * @var int
     */
    private $iMaximoHorasExtras50NaoAutorizadas;

    /**
     * @var int
     */
    private $iMaximoHorasExtras75NaoAutorizadas;

    /**
     * @var int
     */
    private $iMaximoHorasExtras100NaoAutorizadas;

    /**
     * @var HoraExtraArtefato[]
     */
    private $horasCalculadas = array();

    /**
     * HoraExtraCalculo constructor.
     * @param DiaTrabalho $oDiaTrabalho
     */
    public function __construct(DiaTrabalho $oDiaTrabalho)
    {
        $this->setDiaTrabalho($oDiaTrabalho);
        parent::__construct();

        $this->oInicioHorarioNoturno = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . " 22:00");
        $this->oFinalHorarioNoturno = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . " 05:00");
        $this->oFinalHorarioNoturno->modify("+1 day");

        $this->converteConfiguracaoHorasExtrasEmMinutos();

        /**
         * Se o parâmetro para calcular horas extras somente com autorização estiver como SIM e possuir assentamento de autorização
         */
        if ($this->getDiaTrabalho()->getHorasExtrasAutorizadas()) {
            $oIntervaloAutorizado = $this->converteDateTimeParaInterval($this->getDiaTrabalho()->getHorasExtrasAutorizadas());
            $iMinutosAutorizados = BaseHora::converterIntervaloEmMinutos($oIntervaloAutorizado);
            $this->setHorasExtrasAutorizadas($iMinutosAutorizados);

            $this->atualizaLimitesAutorizados();
        }

        $this->verificaFeriadoDSR();

        if ($this->getDiaTrabalho()->getData()->getDiaSemana() == \DBDate::DOMINGO && $this->getDiaTrabalho()->getJornada()->isDiaTrabalhado()) {
            $this->setMaximoHorasExtras50(0);
        }

        $this->setHorasExtras50(new Extra($this->getMaximoHorasExtras50()));
        $this->setHorasExtra50NaoAutorizada(new Extra($this->getMaximoHorasExtras50NaoAutorizadas()));

        $this->setHorasExtras75(new Extra($this->getMaximoHorasExtras75()));
        $this->setHorasExtra75NaoAutorizada(new Extra($this->getMaximoHorasExtras75NaoAutorizadas()));

        $this->setHorasExtras100(new Extra($this->getMaximoHorasExtras100()));
        $this->setHorasExtra100NaoAutorizada(new Extra($this->getMaximoHorasExtras100NaoAutorizadas()));

        $this->setHorasExtraNaoAutorizada(new Extra(INF));
    }

    /**
     * @return \DateTime
     */
    public function getInicioHorarioNoturno()
    {
        return $this->oInicioHorarioNoturno;
    }

    /**
     * @param \DateTime $oInicioHorarioNoturno
     */
    public function setInicioHorarioNoturno($oInicioHorarioNoturno)
    {
        $this->oInicioHorarioNoturno = $oInicioHorarioNoturno;
    }

    /**
     * @return \DateTime
     */
    public function getFinalHorarioNoturno()
    {
        return $this->oFinalHorarioNoturno;
    }

    /**
     * @param \DateTime $oFinalHorarioNoturno
     */
    public function setFinalHorarioNoturno($oFinalHorarioNoturno)
    {
        $this->oFinalHorarioNoturno = $oFinalHorarioNoturno;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras50()
    {
        return $this->iMaximoHorasExtras50;
    }

    /**
     * @param int $iMaximoHorasExtras50
     */
    public function setMaximoHorasExtras50($iMaximoHorasExtras50)
    {
        $this->iMaximoHorasExtras50 = $iMaximoHorasExtras50;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras75()
    {
        return $this->iMaximoHorasExtras75;
    }

    /**
     * @param int $iMaximoHorasExtras75
     */
    public function setMaximoHorasExtras75($iMaximoHorasExtras75)
    {
        $this->iMaximoHorasExtras75 = $iMaximoHorasExtras75;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras100()
    {
        return $this->iMaximoHorasExtras100;
    }

    /**
     * @param int $iMaximoHorasExtras100
     */
    public function setMaximoHorasExtras100($iMaximoHorasExtras100)
    {
        $this->iMaximoHorasExtras100 = $iMaximoHorasExtras100;
    }

    /**
     * @return Extra
     */
    public function getHorasExtras50()
    {
        return $this->oHorasExtra50;
    }

    /**
     * @param Extra $oHorasExtra50
     */
    public function setHorasExtras50($oHorasExtra50)
    {
        $this->oHorasExtra50 = $oHorasExtra50;
    }

    /**
     * @return Extra
     */
    public function getHorasExtras75()
    {
        return $this->oHorasExtra75;
    }

    /**
     * @param Extra $oHorasExtra75
     */
    public function setHorasExtras75($oHorasExtra75)
    {
        $this->oHorasExtra75 = $oHorasExtra75;
    }

    /**
     * @return Extra
     */
    public function getHorasExtras100()
    {
        return $this->oHorasExtra100;
    }

    /**
     * @param Extra $oHorasExtra100
     */
    public function setHorasExtras100($oHorasExtra100)
    {
        $this->oHorasExtra100 = $oHorasExtra100;
    }

    /**
     * @return int
     */
    public function getHorasExtrasAutorizadas()
    {
        return $this->iHorasExtrasAutorizadas;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras50NaoAutorizadas()
    {
        return $this->iMaximoHorasExtras50NaoAutorizadas;
    }

    /**
     * @param int $iMaximoHorasExtras50NaoAutorizadas
     */
    public function setMaximoHorasExtras50NaoAutorizadas($iMaximoHorasExtras50NaoAutorizadas)
    {
        $this->iMaximoHorasExtras50NaoAutorizadas = $iMaximoHorasExtras50NaoAutorizadas;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras75NaoAutorizadas()
    {
        return $this->iMaximoHorasExtras75NaoAutorizadas;
    }

    /**
     * @param int $iMaximoHorasExtras75NaoAutorizadas
     */
    public function setMaximoHorasExtras75NaoAutorizadas($iMaximoHorasExtras75NaoAutorizadas)
    {
        $this->iMaximoHorasExtras75NaoAutorizadas = $iMaximoHorasExtras75NaoAutorizadas;
    }

    /**
     * @return int
     */
    public function getMaximoHorasExtras100NaoAutorizadas()
    {
        return $this->iMaximoHorasExtras100NaoAutorizadas;
    }

    /**
     * @param int $iMaximoHorasExtras100NaoAutorizadas
     */
    public function setMaximoHorasExtras100NaoAutorizadas($iMaximoHorasExtras100NaoAutorizadas)
    {
        $this->iMaximoHorasExtras100NaoAutorizadas = $iMaximoHorasExtras100NaoAutorizadas;
    }

    /**
     * @param int $iHorasExtrasAutorizadas
     */
    public function setHorasExtrasAutorizadas($iHorasExtrasAutorizadas)
    {
        $this->iHorasExtrasAutorizadas = $iHorasExtrasAutorizadas;
    }

    /**
     * @return Extra
     */
    public function getHorasExtra50NaoAutorizada()
    {
        return $this->oHorasExtra50NaoAutorizada;
    }

    /**
     * @param Extra $oHorasExtra50NaoAutorizada
     */
    public function setHorasExtra50NaoAutorizada($oHorasExtra50NaoAutorizada)
    {
        $this->oHorasExtra50NaoAutorizada = $oHorasExtra50NaoAutorizada;
    }

    /**
     * @return Extra
     */
    public function getHorasExtra75NaoAutorizada()
    {
        return $this->oHorasExtra75NaoAutorizada;
    }

    /**
     * @param Extra $oHorasExtra75NaoAutorizada
     */
    public function setHorasExtra75NaoAutorizada($oHorasExtra75NaoAutorizada)
    {
        $this->oHorasExtra75NaoAutorizada = $oHorasExtra75NaoAutorizada;
    }

    /**
     * @return Extra
     */
    public function getHorasExtra100NaoAutorizada()
    {
        return $this->oHorasExtra100NaoAutorizada;
    }

    /**
     * @param Extra $oHorasExtra100NaoAutorizada
     */
    public function setHorasExtra100NaoAutorizada($oHorasExtra100NaoAutorizada)
    {
        $this->oHorasExtra100NaoAutorizada = $oHorasExtra100NaoAutorizada;
    }

    /**
     * @return Extra
     */
    public function getHorasExtraNaoAutorizada()
    {
        return $this->oHorasExtraNaoAutorizada;
    }

    /**
     * @param Extra $horaExtra
     */
    public function setHorasExtraNaoAutorizada($horaExtra)
    {
        $this->oHorasExtraNaoAutorizada = $horaExtra;
    }

    public function calcular()
    {
        $diaTrabalho = $this->getDiaTrabalho();

        if ($diaTrabalho->getJornada()->isFolga() && $this->getDiaTrabalho()->getFeriado() == null) {
            return $this->processarDSRFeriado();
        } else {
            if ($diaTrabalho->getJornada()->isDSR() || $diaTrabalho->getFeriado()) {
                return $this->processarDSRFeriado();
            } else {

                $marcacoesJornada = $this->getDiaTrabalho()->getJornada()->getHoras();
                $marcacoesNoDia   = $this->getDiaTrabalho()->getMarcacoesSemAlteracao();
                $totalMarcacacoesNoDia = $marcacoesNoDia->getQuantidadeMarcacoes();

                if (count($marcacoesJornada) == 2 && $totalMarcacacoesNoDia > 2) {
                    return $this->executarCalculoLinear($marcacoesNoDia);
                }

                $oHoraExtraInicial = $this->getHoraExtraInicial();
                $oHoraExtraIntervalo = $this->getHoraExtraIntervalo();
                $oHoraExtraFinal = $this->getHoraExtraFinal();

                $this->logger->debug("-- Hora Extra Inicial.................: " . ($oHoraExtraInicial instanceof \DateTime ? $oHoraExtraInicial->format('H:i') : ''));
                $this->logger->debug("-- Hora Extra Intervalo...............: " . ($oHoraExtraIntervalo instanceof \DateTime ? $oHoraExtraIntervalo->format('H:i') : ''));
                $this->logger->debug("-- Hora Extra Final...................: " . ($oHoraExtraFinal instanceof \DateTime ? $oHoraExtraFinal->format('H:i') : ''));

                if (!empty($oHoraExtraInicial) && !$this->isZero($oHoraExtraInicial)) {
                    $oInicioExtraInicial = $this->getDiaTrabalho()->getMarcacoesSemAlteracao()->getMarcacaoEntrada1()->getMarcacao();
                    $oExtraInicialSeparada = $this->getValorDiurnoNoturno($oInicioExtraInicial, $oHoraExtraInicial);

                    if ($oExtraInicialSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraInicialSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraInicialSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                    } else {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraInicialSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraInicialSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                    }
                }

                if (!empty($oHoraExtraIntervalo) && !$this->isZero($oHoraExtraIntervalo)) {
                    $oInicioExtraIntervalo = $this->getDiaTrabalho()->getMarcacoesSemAlteracao()->getMarcacaoEntrada2()->getMarcacao();
                    $oExtraIntervaloSeparada = $this->getValorDiurnoNoturno($oInicioExtraIntervalo, $oHoraExtraIntervalo);

                    if ($oExtraIntervaloSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraIntervaloSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraIntervaloSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                    } else {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraIntervaloSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraIntervaloSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                    }
                }

                if (!empty($oHoraExtraFinal) && !$this->isZero($oHoraExtraFinal)) {
                    $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();
                    $oInicioExtraFinal = end($aHorasJornada)->oHora;
                    $oExtraFinalSeparada = $this->getValorDiurnoNoturno($oInicioExtraFinal, $oHoraExtraFinal);

                    if ($oExtraFinalSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraFinalSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraFinalSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                    } else {
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraFinalSeparada->getMinutosNoturnos(),
                            Extra::TIPO_NOTURNA
                        );
                        $this->horasCalculadas[] = new ExtraCalculada(
                            $oExtraFinalSeparada->getMinutosDiurnos(),
                            Extra::TIPO_DIURNA
                        );
                    }
                }
            }
        }

        /**
         * Realiza o processo de soma de valores de horas extras diurnas e noturnas
         */
        foreach ($this->horasCalculadas as $horaCalculada) {
            $this->processaHorasExtras($horaCalculada);
        }

        $diaTrabalho->setHorasExtra50(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras50()->getDiurnas()));
        $diaTrabalho->setHorasExtra50Noturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtras50()->getNoturnas())));

        $diaTrabalho->setHorasExtra75(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras75()->getDiurnas()));
        $diaTrabalho->setHorasExtra75Noturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtras75()->getNoturnas())));

        $diaTrabalho->setHorasExtra100(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras100()->getDiurnas()));
        $diaTrabalho->setHorasExtra100Noturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtras100()->getNoturnas())));

        $diaTrabalho->setHorasExtra50NaoAutorizadas(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtra50NaoAutorizada()->getDiurnas()));
        $diaTrabalho->setHorasExtra50NaoAutorizadasNoturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtra50NaoAutorizada()->getNoturnas())));

        $diaTrabalho->setHorasExtra75NaoAutorizadas(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtra75NaoAutorizada()->getDiurnas()));
        $diaTrabalho->setHorasExtra75NaoAutorizadasNoturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtra75NaoAutorizada()->getNoturnas())));

        $diaTrabalho->setHorasExtra100NaoAutorizadas(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtra100NaoAutorizada()->getDiurnas()));
        $diaTrabalho->setHorasExtra100NaoAutorizadasNoturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtra100NaoAutorizada()->getNoturnas())));

        $diaTrabalho->setHorasExtraNaoAutorizadas(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtraNaoAutorizada()->getDiurnas()));
        $diaTrabalho->setHorasExtraNaoAutorizadasNoturna(BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterMinutosEmMinutosNoturnos($this->getHorasExtraNaoAutorizada()->getNoturnas())));

        $this->logger->debug("------------------------------------------------------ ");
    }

    /**
     * Busca limites de horas extras configurados e converte em minutos
     */
    private function converteConfiguracaoHorasExtrasEmMinutos()
    {
        $dataTrabalho = $this->getHoraZerada()->format("Y-m-d") . " ";
        $oExtra50Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho . $this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra50()));
        $this->setMaximoHorasExtras50(BaseHora::converterIntervaloEmMinutos($oExtra50Maximo));

        $oExtra75Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho . $this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra75()));
        $this->setMaximoHorasExtras75(BaseHora::converterIntervaloEmMinutos($oExtra75Maximo));

        $oExtra100Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho . $this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra100()));
        $this->setMaximoHorasExtras100(BaseHora::converterIntervaloEmMinutos($oExtra100Maximo));
    }

    public function converteDateTimeParaInterval(\DateTime $dateTime)
    {
        return $this->getDiferencaHoras($dateTime, $this->getHoraZerada());
    }

    public function getValorDiurnoNoturno($oInicio, $oValor)
    {
        $oHoraExtraArtefato = new HoraExtraArtefato($this->getInicioHorarioNoturno(), $this->getFinalHorarioNoturno(), $oInicio, $oValor);
        return $oHoraExtraArtefato;
    }

    /**
     * Realiza a separacao das horas extras dentro dos limites configurados.
     * @param ExtraCalculada $horaCalculada
     */
    protected function processaHorasExtras(ExtraCalculada $horaCalculada)
    {
        // Se valor atual 50 >= maximo minutos 50 : passa reto
        if ($this->getHorasExtras50()->deveCalcular()) {
            $resto = $this->getHorasExtras50()->incrementar($horaCalculada);

            if ($resto > 0) {
                $horaCalculada->setMinutos($resto);
                $this->processaHorasExtras($horaCalculada);
            }
            return;
        }

        // Se valor atual 75 >= maximo minutos 75 : passa reto
        if ($this->getHorasExtras75()->deveCalcular()) {
            $resto = $this->getHorasExtras75()->incrementar($horaCalculada);

            if ($resto) {
                $horaCalculada->setMinutos($resto);
                $this->processaHorasExtras($horaCalculada);
            }
            return;
        }

        // Se valor atual 100 >= maximo minutos 100 : passa reto
        if ($this->getHorasExtras100()->deveCalcular()) {
            $resto = $this->getHorasExtras100()->incrementar($horaCalculada);

            if ($resto) {
                $horaCalculada->setMinutos($resto);
                $this->processaHorasExtras($horaCalculada);
            }
        }

        $resto = $horaCalculada->getMinutos();
        //Se ainda temos minutos, então temos horas extras não autorizadas
        if ($resto > 0) {
            if ($this->getHorasExtra50NaoAutorizada()->deveCalcular() && $this->getHorasExtra50NaoAutorizada()->getLimite() != 0) {
                $resto = $this->getHorasExtra50NaoAutorizada()->incrementar($horaCalculada);
            } else {
                if ($this->getHorasExtra75NaoAutorizada()->deveCalcular() && $this->getHorasExtra75NaoAutorizada()->getLimite() != 0) {
                    $resto = $this->getHorasExtra75NaoAutorizada()->incrementar($horaCalculada);
                } else {
                    if ($this->getHorasExtra100NaoAutorizada()->deveCalcular() && $this->getHorasExtra100NaoAutorizada()->getLimite() != 0) {
                        $resto = $this->getHorasExtra100NaoAutorizada()->incrementar($horaCalculada);
                    }
                }
            }

            $horaCalculada->setMinutos($resto);
            $horaCalculada->setMinutos(
                $this->getHorasExtraNaoAutorizada()->incrementar($horaCalculada)
            );

        }
    }

    /**
     * Caso o parâmetro de autorização de hora extra for SIM e possuir assentamento de autorização
     * Atualiza os limites das horas extras com base nas horas autorizadas no assentamento
     * @return bool
     */
    private function atualizaLimitesAutorizados()
    {
        $intervalo = $this->getDiaTrabalho()->getHorasExtrasAutorizadas();
        $iMinutosAutorizados = BaseHora::converterDateTimeEmMinutos($intervalo);
        $iSomaLimitesConfigurados = $this->iMaximoHorasExtras50 + $this->iMaximoHorasExtras75 + $this->iMaximoHorasExtras100;

        $this->setMaximoHorasExtras50NaoAutorizadas(0);
        $this->setMaximoHorasExtras75NaoAutorizadas(0);
        $this->setMaximoHorasExtras100NaoAutorizadas(0);

        if ($iSomaLimitesConfigurados <= $iMinutosAutorizados) {
            return false;
        }

        if ($this->iMaximoHorasExtras50 >= $iMinutosAutorizados) {
            $totalNaoAutorizado = $this->iMaximoHorasExtras50 - $iMinutosAutorizados;

            $this->setMaximoHorasExtras50($iMinutosAutorizados);
            $this->setMaximoHorasExtras75(0);
            $this->setMaximoHorasExtras100(0);
            $this->setMaximoHorasExtras50NaoAutorizadas($totalNaoAutorizado);

            $iMinutosAutorizados = 0;
        } else {
            $iMinutosAutorizados -= $this->iMaximoHorasExtras50;
        }

        if ($this->iMaximoHorasExtras75 >= $iMinutosAutorizados) {
            $totalNaoAutorizado = $this->iMaximoHorasExtras75 - $iMinutosAutorizados;

            $this->setMaximoHorasExtras75($iMinutosAutorizados);
            $this->setMaximoHorasExtras100(0);
            $this->setMaximoHorasExtras75NaoAutorizadas($totalNaoAutorizado);

            $iMinutosAutorizados = 0;
        } else {
            $iMinutosAutorizados -= $this->iMaximoHorasExtras75;
        }

        if ($this->iMaximoHorasExtras100 >= $iMinutosAutorizados) {
            $totalNaoAutorizado = $this->iMaximoHorasExtras75 - $iMinutosAutorizados;

            $this->setMaximoHorasExtras100($iMinutosAutorizados);
            $this->setMaximoHorasExtras100NaoAutorizadas($totalNaoAutorizado);
        }

        return true;
    }

    /**
     * Realiza o ajuste das Horas extras na folga
     * @throws \Exception
     */
    private function verificaFeriadoDSR()
    {
        if ($this->getDiaTrabalho()->getJornada()->isDSR() || ($this->getDiaTrabalho()->getFeriado())) {
            $aHoraSeparada = explode(":", $this->getDiaTrabalho()->getHorasTotaisDeTrabalho());
            $iMinutosTrabalhados = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHoraSeparada[0]}H{$aHoraSeparada[1]}M"));
            $iSomaTotaisExtras = +$this->iMaximoHorasExtras50 + $this->iMaximoHorasExtras75 + $this->iMaximoHorasExtras100;

            $this->setMaximoHorasExtras100($iMinutosTrabalhados);
            if ($this->getDiaTrabalho()->getHorasExtrasAutorizadas() && $iMinutosTrabalhados > $iSomaTotaisExtras) {
                $this->setMaximoHorasExtras100($iSomaTotaisExtras);
            }

            $this->setMaximoHorasExtras50(0);
            $this->setMaximoHorasExtras75(0);
            return;
        }
    }

    private function processarFolga()
    {

        $this->setMaximoHorasExtras50(0);
        $this->setMaximoHorasExtras75(0);
        $this->setMaximoHorasExtras100(0);

        $aHorasTrabalhadas = explode(":", $this->getDiaTrabalho()->getHorasTotaisDeTrabalho());
        $aHorasNoturnas = explode(":", $this->getDiaTrabalho()->getHorasAdicionalNoturno());

        $iMinutos2Horas = 120;
        $iMinutosTrabalhados = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHorasTrabalhadas[0]}H{$aHorasTrabalhadas[1]}M"));
        $iMinutosNoturnos = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHorasNoturnas[0]}H{$aHorasNoturnas[1]}M"));
        $iMinutosDiurnos = $iMinutosTrabalhados - $iMinutosNoturnos;

        /**
         * Quando é folga, segue a seguinte regra:
         * 2 primeiras horas são 50%
         * Horas seguintes são 75%
         * Horario noturno é inteiro 100%
         */

        $this->setMaximoHorasExtras50($iMinutosDiurnos);
        $this->setMaximoHorasExtras100($iMinutosNoturnos);

        if ($iMinutosDiurnos > $iMinutos2Horas) {
            $this->setMaximoHorasExtras50($iMinutos2Horas);
            $this->setMaximoHorasExtras75($iMinutosDiurnos - $iMinutos2Horas);
        }

        /**
         * Quanto tiver autorizaçao de hora extra, atualiza os limites conforme as horas autorizadas
         */
        if ($this->getDiaTrabalho()->getHorasExtrasAutorizadas()) {
            $this->atualizaLimitesAutorizados();
        }

        $this->getHorasExtras50()->setLimite($this->getMaximoHorasExtras50());
        $this->getHorasExtras75()->setLimite($this->getMaximoHorasExtras75());
        $this->getHorasExtras100()->setLimite($this->getMaximoHorasExtras100());

        $this->horasCalculadas[] = new ExtraCalculada($iMinutosDiurnos, Extra::TIPO_DIURNA);
        $this->horasCalculadas[] = new ExtraCalculada($iMinutosNoturnos, Extra::TIPO_NOTURNA);
    }

    private function processarDSRFeriado()
    {
        $this->logger->debug("--- PROCESSANDO CALCULO DE EXTRAS PARA DSR/FERIADO --- ");
        $this->logger->debug("------------------------------------------------------ ");

        $diaTrabalho = $this->getDiaTrabalho();
        $oCalculoExtraFeriado = TipoHora::getHora($this->getDiaTrabalho(), BaseHora::HORAS_CALCULO_EXTRA_LINEAR);

        $horaExtra = \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00');
        $oCalculoExtraFeriado->calcular(
            $horaExtra,
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00'),
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00'),
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00'),
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00'),
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00'),
            \DateTime::createFromFormat('Y-m-d H:i', $diaTrabalho->getData()->getDate() . ' 00:00')
        );

        $this->logger->debug("-- Horas Extras 50.................: " . ($diaTrabalho->getHorasExtra50() ? $diaTrabalho->getHorasExtra50() : ''));
        $this->logger->debug("-- Horas Extras 75.................: " . ($diaTrabalho->getHorasExtra75() ? $diaTrabalho->getHorasExtra75() : ''));
        $this->logger->debug("-- Horas Extras 100................: " . ($diaTrabalho->getHorasExtra100() ? $diaTrabalho->getHorasExtra100() : ''));
        $this->logger->debug("-- Horas Extras 50 Noturna.........: " . ($diaTrabalho->getHorasExtra50Noturna() ? $diaTrabalho->getHorasExtra50Noturna() : ''));
        $this->logger->debug("-- Horas Extras 75 Noturna.........: " . ($diaTrabalho->getHorasExtra75Noturna() ? $diaTrabalho->getHorasExtra75Noturna() : ''));
        $this->logger->debug("-- Horas Extras 100 Noturna........: " . ($diaTrabalho->getHorasExtra100Noturna() ? $diaTrabalho->getHorasExtra100Noturna() : ''));
    }


    public function executarCalculoLinear($marcacoesNoDia)
    {
        $regraCalculoExtra          = new RegraExtraDiaTrabalho($this->getDiaTrabalho());
        $marcacoesParaCalculoLinear = clone $marcacoesNoDia;
        $entradaNoDia               = ($marcacoesParaCalculoLinear->getMarcacaoEntrada1()          ? $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->getMarcacao() : null);
        $ultimaMarcacao             = ($marcacoesParaCalculoLinear->getUltimaMarcacaoComRegistro() ? $marcacoesParaCalculoLinear->getUltimaMarcacaoComRegistro()       : null);
        $jornada                    = $this->getDiaTrabalho()->getJornada();

        if( $entradaNoDia && ($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora->getTimestamp() < $entradaNoDia->getTimestamp()) ) {
            // validacao minimizando impactos
            if (isset($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora) && !empty($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora)) {
                $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora);
            } else {
            $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1));
            }
        }

        if( $ultimaMarcacao && $jornada->getUltimaHora() && ($jornada->getUltimaHora()->oHora->getTimestamp() > $ultimaMarcacao->getMarcacao()->getTimestamp()) ) {
            $ultimaMarcacao->setMarcacao(clone $jornada->getUltimaHora()->oHora);
        }

        $calculoLinear = new CalculoHoraLinear($this->getDiaTrabalho());
        $calculoLinear->executarCalculo($marcacoesParaCalculoLinear, $regraCalculoExtra);
        $horasExtrasNaoAutorizadas       = $regraCalculoExtra->getHorasExtrasNaoAutorizadas();
        $horasExtrasCalculadas           = $regraCalculoExtra->getHorasExtras();
        $tiposHorasExtrasNaoAutorizadas  = array_keys($horasExtrasNaoAutorizadas);
        $tiposHorasCalculadas            = array_keys($horasExtrasCalculadas);
        $tiposHorasNaoCalculadas         = (array_diff(array(
             BaseHora::HORAS_EXTRA50
            ,BaseHora::HORAS_EXTRA50_NOTURNA
            ,BaseHora::HORAS_EXTRA75
            ,BaseHora::HORAS_EXTRA75_NOTURNA
            ,BaseHora::HORAS_EXTRA100
            ,BaseHora::HORAS_EXTRA100_NOTURNA            
        ), $tiposHorasCalculadas));

        if(is_array($tiposHorasNaoCalculadas) && !empty($tiposHorasNaoCalculadas)) {

            foreach ($tiposHorasNaoCalculadas as $tipoHorasNaoCalculada) {
                $horasExtrasCalculadas[$tipoHorasNaoCalculada] = 0;
            }
        }

        $tiposHorasNaoAutorizadasFaltantes = (array_diff(array(
             BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS
            ,BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS_NOTURNA
            ,BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS
            ,BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS_NOTURNA
            ,BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS
            ,BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS_NOTURNA
        ), $tiposHorasExtrasNaoAutorizadas));

        if(is_array($tiposHorasNaoAutorizadasFaltantes) && !empty($tiposHorasNaoAutorizadasFaltantes)) {

            foreach ($tiposHorasNaoAutorizadasFaltantes as $tipoHoraNaoAutorizadaFaltante) {
                $horasExtrasNaoAutorizadas[$tipoHoraNaoAutorizadaFaltante] = 0;
            }
        }

        foreach ($horasExtrasCalculadas as $tipo => $horaCalculada) {

            if(empty($horaCalculada)) {
                $horaCalculada = 0;
            }

            $tempoCalculado = BaseHora::converterMinutosEmHoraMinuto($horaCalculada);
            
            switch ($tipo) {

                case BaseHora::HORAS_EXTRA50:   //2
                    $this->getDiaTrabalho()->setHorasExtra50($tempoCalculado);
                    break;

                case BaseHora::HORAS_EXTRA50_NOTURNA:  //7
                    $this->getDiaTrabalho()->setHorasExtra50Noturna($tempoCalculado);
                    break;

                case BaseHora::HORAS_EXTRA75:  //3
                    $this->getDiaTrabalho()->setHorasExtra75($tempoCalculado);
                    break;

                case BaseHora::HORAS_EXTRA75_NOTURNA:  //8
                    $this->getDiaTrabalho()->setHorasExtra75Noturna($tempoCalculado);
                    break;
                    
                case BaseHora::HORAS_EXTRA100: //4
                    $this->getDiaTrabalho()->setHorasExtra100($tempoCalculado);
                    break;

                case BaseHora::HORAS_EXTRA100_NOTURNA: //9
                    $this->getDiaTrabalho()->setHorasExtra100Noturna($tempoCalculado);
                    break;
            }
        }
        
        foreach ($horasExtrasNaoAutorizadas as $tipo => $horaNaoAutorizada) {

            if(empty($horaNaoAutorizada)) {
                $horaNaoAutorizada = 0;
            }

            $tempoNaoAutorizado = BaseHora::converterMinutosEmHoraMinuto($horaNaoAutorizada);
            
            switch ($tipo) {

                case BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS: 
                    $this->getDiaTrabalho()->setHorasExtra50NaoAutorizadas($tempoNaoAutorizado);
                    break;

                case BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS_NOTURNA: 
                    $this->getDiaTrabalho()->setHorasExtra50NaoAutorizadasNoturna($tempoNaoAutorizado);
                    break;

                case BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS: 
                    $this->getDiaTrabalho()->setHorasExtra75NaoAutorizadas($tempoNaoAutorizado);
                    break;

                case BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS_NOTURNA: 
                    $this->getDiaTrabalho()->setHorasExtra75NaoAutorizadasNoturna($tempoNaoAutorizado);
                    break;

                case BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS: 
                    $this->getDiaTrabalho()->setHorasExtra100NaoAutorizadas($tempoNaoAutorizado);
                    break;

                case BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS_NOTURNA: 
                    $this->getDiaTrabalho()->setHorasExtra100NaoAutorizadasNoturna($tempoNaoAutorizado);
                    break;
            }
        }
                    
        $this->getDiaTrabalho()->setHorasExtraNaoAutorizadas(array_sum(array(
             $horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS]
            ,$horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS]
            ,$horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS]
        )));

        $this->getDiaTrabalho()->setHorasExtraNaoAutorizadasNoturna(array_sum(array(
             $horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS_NOTURNA]
            ,$horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS_NOTURNA]
            ,$horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS_NOTURNA]
        )));
    }
}
