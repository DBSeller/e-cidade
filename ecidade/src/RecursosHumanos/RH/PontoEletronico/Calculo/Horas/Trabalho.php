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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPontoSaida;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\CalculoHoraLinear;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Trabalho as RegraHoraTrabalho;

/**
 * Classe responsável pelo cálculo de horas trabalhadas de um servidor em um dia de trabalho
 * Class Trabalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Trabalho extends BaseHora implements Horas {

    private $marcacoes = null;

    public function __construct(DiaTrabalho $oDiaTrabalho) {

        $this->setDiaTrabalho($oDiaTrabalho);
        $this->setTipoHora(BaseHora::HORAS_TRABALHO);
        parent::__construct();

        $this->marcacoes = $this->ajustarMarcacoesParaCalculo($oDiaTrabalho);
    }

    /**
     * @return \DateTime
     */
    public function calcular() {

        $this->logger->debug("------------------------------------------------------");
        $this->logger->debug("-------------- CALCULO DE HORAS TRABALHO -------------");

        $oDiaTrabalhado    = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 00:00');
        $jornada           = $this->getDiaTrabalho()->getJornada();
        $horasDaJornada    = $jornada->getHoras();

        $aMarcacoesCalcular   = $this->marcacoes->getMarcacoes();
        $marcacoesParaCalculo = array();
        $calculaIntervaloTrabalhado = true;

        if( $this->marcacoes->getQuantidadeMarcacoes() > count($jornada->getHoras())) {

            $this->logger->debug("-- Existem mais marcacações que a jornada");
            return $this->calcularHorasTrabalhoPelaRegra();
        }

        foreach ($aMarcacoesCalcular as $oMarcacao) {

            $minutosTolerancia                   = $this->getDiaTrabalho()->getTolerancia();
            $marcacaoCalculo                     = $oMarcacao;
            $tipoMarcacao                        = $marcacaoCalculo->getTipo();
            $marcacoesParaCalculo[$tipoMarcacao] = $marcacaoCalculo;


            if (empty($horasDaJornada[$tipoMarcacao - 1])) {
                
                $debug = "-- Nao encontrada na jornada a marcacao tipo: ". MarcacaoPonto::getDescricaoTipoMarcacao($tipoMarcacao);
                $this->logger->debug($debug);
                continue;
            }

            $horaDaJornada                       = $horasDaJornada[$tipoMarcacao - 1];

            if ($marcacaoCalculo->getMarcacao() == null) {

                $debug = "-- A marcacao do tipo ". MarcacaoPonto::getDescricaoTipoMarcacao($marcacaoCalculo->getTipo())." esta nula";
                $this->logger->debug($debug);
                continue;
            }

            switch ($marcacaoCalculo->getTipo()) {

                case MarcacaoPonto::ENTRADA_1:

                    if($marcacaoCalculo->getMarcacao()->getTimeStamp() < $horaDaJornada->oHora->getTimeStamp() || $this->marcacaoEstaNaTolerancia($marcacaoCalculo, $minutosTolerancia)) {                        

                        /**
                         * Pega a próxima marcação, e verifica se não é menor que o horário da primeira jornada.
                         * Este caso pode ocorrer, em situações que o servidor trabalhou fora da jornada.
                         * Ex.:
                         *    - Jornada: 13:00 / 17:00
                         *    - Marcações: 07:12 / 11:05
                         * Ou seja, alterar o horário de entrada, faria com que esta se torne maior que a saída
                         */
                        $proximaMarcacao = $aMarcacoesCalcular[$tipoMarcacao + 1];

                        if($proximaMarcacao->getMarcacao() != null && $horaDaJornada->oHora->getTimeStamp() < $proximaMarcacao->getMarcacao()->getTimestamp()) {


                            /**
                             * Estamos alterando o horário da entrada com a difereça entre a batida e o horário da jornada,
                             * para que a batida fique com o mesmo horário da jornada, já que está dentro da tolerância
                             */
                            $marcacao          = $marcacaoCalculo->getMarcacao();
                            $intervalo         = $horaDaJornada->oHora->diff($marcacao);
                            $intervalo->invert = (int)!$intervalo->invert;
                            $marcacao->add($intervalo);
                        }

                        $debug = "-- Proxima marcacao:". (($proximaMarcacao->getMarcacao() instanceof \DateTime) ? $proximaMarcacao->getMarcacao()->format('H:i') : '');
                        $this->logger->debug($debug);
                    }

                    break;

                case MarcacaoPonto::SAIDA_2:

                    if($marcacaoCalculo->getMarcacao()->getTimeStamp() > $horaDaJornada->oHora->getTimeStamp() || $this->marcacaoEstaNaTolerancia($marcacaoCalculo, $minutosTolerancia)) {
                        $marcacaoCalculo->setMarcacao(clone $horaDaJornada->oHora);
                    }

                    $marcacaoCalculo->setMarcacaoEntrada($marcacoesParaCalculo[$tipoMarcacao-1]->getMarcacao());
                    break;

                case MarcacaoPonto::SAIDA_1:

                    if(count($horasDaJornada) == 2) {

                        if($marcacaoCalculo->getMarcacao()->getTimeStamp() > $horaDaJornada->oHora->getTimeStamp() || $this->marcacaoEstaNaTolerancia($marcacaoCalculo, $minutosTolerancia)) {
                            $marcacaoCalculo->setMarcacao(clone $horaDaJornada->oHora);
                            $debug = '-- Hora da marcacao maior que hora da jornada ou marcacao dentro da tolerancia ';
                            $this->logger->debug($debug);
                        }

                        $marcacaoCalculo->setMarcacaoEntrada($marcacoesParaCalculo[$tipoMarcacao-1]->getMarcacao());
                    }

                    /**
                     * Caso:
                     * - Jornada com 4 marcações( Ex.: 07:00 11:00 12:00 16:00 )
                     * - Servidor tem 2 batidas no dia( Ex.: 12:00 16:09 )
                     * Nesse caso, deve ser validado se a batida de saída está dentro da tolerância,
                     * se comparada com a última marcação de saída configurada na jornada
                     */
                    if(count($horasDaJornada) == 4 && $this->marcacoes->getQuantidadeMarcacoes() == 2) {

                        if($this->marcacaoSaidaDentroToleranciaComApenasDuasMarcacoes()) {
                            $marcacaoCalculo->setMarcacao(clone $horasDaJornada[3]->oHora);
                        }

                        $primeiraMarcacao = $aMarcacoesCalcular[1]->getMarcacao();
                        $retornoIntervaloJornada = $horasDaJornada[2]->oHora;
                        $ultimaHoraJornada = $horasDaJornada[3]->oHora;

                        /**
                         * Ex.:
                         * - Jornada:   06:00 10:00 11:00 15:00
                         * - Marcações: 12:26 21:51
                         * Pega a diferença entre a primeira marcação e a saída da jornada
                         */
                        if($primeiraMarcacao != null) {
                            if($primeiraMarcacao->getTimestamp() > $retornoIntervaloJornada->getTimestamp()) {
                                if($primeiraMarcacao->getTimestamp() < $ultimaHoraJornada->getTimestamp()) {
                                    $oDiaTrabalhado->add($primeiraMarcacao->diff($ultimaHoraJornada));
                                    $calculaIntervaloTrabalhado = false;
                                }
                            }
                        }
                    }

                    break;

                case MarcacaoPonto::ENTRADA_2:


                    if(count($horasDaJornada) > 2) {

                        $minutosTolerancia  = 5;
                        $marcacaoAnterior   = $marcacoesParaCalculo[$tipoMarcacao-1]->getMarcacao();

                        if(empty($marcacaoAnterior) || is_null($marcacaoCalculo->getMarcacao())) {
                            continue 2;
                        }

                        $intervaloDaJornada = $this->getHorasIntervalo();
                        $intervaloDaJornada = $this->converterIntervaloEmMinutos($intervaloDaJornada);

                        $intervaloRealizado = $this->getDiferencaHoras($marcacaoAnterior, $marcacaoCalculo->getMarcacao());
                        $intervaloRealizado = $this->converterIntervaloEmMinutos($intervaloRealizado);

                        if ($intervaloRealizado != $intervaloDaJornada) {

                            $minutosDiferenca = $intervaloDaJornada - $intervaloRealizado;

                            /**
                             * Caso o intervalo realizado seja inferior ao intervalo devido
                             * ou o intervalo realizado esteja dentro da tolerância de 5 minutos em relação ao intervalo devido
                             * então adicionamos o intervalo da jornada à marcação de saída
                             * para o intervalo afim de que o intervalo realizado seja
                             * semelhante ào intervalo da jornada e calcular corretamente o horário de trabalho
                             */
                            if (abs($minutosDiferenca) <= $minutosTolerancia) {

                                $novaMarcacaoEntrada = clone $marcacaoAnterior;
                                $novaMarcacaoEntrada->modify("+ {$intervaloDaJornada} minutes");

                                $marcacaoAtual = $marcacaoCalculo->getMarcacao();
                                $marcacaoAtual->setTime($novaMarcacaoEntrada->format('H'), $novaMarcacaoEntrada->format('i'));
                            }
                        }
                    }
                    break;
            }

            if($calculaIntervaloTrabalhado && $marcacaoCalculo->isMarcacaoSaida()) {

                $intervaloTrabalhado = $marcacaoCalculo->getHorarioTrabalhado();

                $debug  = ' -- Calculado intervalo: ';
                $debug .= ($intervaloTrabalhado instanceof \DateInterval ? $intervaloTrabalhado->format('%H:%I') . ' com a marcacao: '. MarcacaoPonto::getDescricaoTipoMarcacao($marcacaoCalculo->getTipo()) : '__:__');

                if(!empty($intervaloTrabalhado)) {
                    $oDiaTrabalhado->add($intervaloTrabalhado);
                }
            }
        }

        $dataTrabalhada = new \DBDate($oDiaTrabalhado->format('Y-m-d'));

        if($dataTrabalhada->getTimeStamp() < $this->getDiaTrabalho()->getData()->getTimeStamp()) {
            $oDiaTrabalhado->setDate(
              $this->getDiaTrabalho()->getData()->getAno(),
              $this->getDiaTrabalho()->getData()->getMes(),
              $this->getDiaTrabalho()->getData()->getDia()
            );
        }

        if ($jornada->temHorarioNoturno($oDiaTrabalhado->format('Y-m-d'))) {
            $oDiaTrabalhado = $this->calcularHoratrabalhadaComHorarioNoturno();
        }

        return $oDiaTrabalhado;
    }

    /**
     * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection
     */
    public function getMarcacoes()
    {
        return $this->marcacoes;
    }

    /**
     * @param mixed $marcacoes
     *
     * @return self
     */
    public function setMarcacoes($marcacoes)
    {
        $this->marcacoes = $marcacoes;

        return $this;
    }

    /**
     * Calcula a Hora total Trabalhada
     * @return \DateTime
     */
    public function calcularHoraTrabalhoTotal() {

        $oDiaTrabalhado = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 00:00');
        $oMarcacoes     = clone $this->getMarcacoesReais();

        if($oMarcacoes->getMarcacoes() == null) {
            return $oDiaTrabalhado;
        }

        $aMarcacoesCalcular = $oMarcacoes->getMarcacoes();
        $iTotalMarcacoes    = count($aMarcacoesCalcular);

        /**
         * Caso tenha um número impar de marcações,
         * é preciso saber se há 1 ou 3 marcações,
         * se há apenas 1 não há como calcular, retorna zero,
         * se houver 3 excluo a última
         */
        if($iTotalMarcacoes % 2 != 0) {

            switch ($iTotalMarcacoes) {
                case 1:
                    return $oDiaTrabalhado;
                    break;

                default:
                    array_pop($aMarcacoesCalcular);
                    break;
            }
        }

        foreach ($aMarcacoesCalcular as $oMarcacao) {

            if($oMarcacao->getJustificativa() !== null && $oMarcacao->getMarcacao() !== null) {

                $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

                if(isset($aHorasJornada[$oMarcacao->getTipo()-1]) && $this->getDiaTrabalho()->getHorasExtrasAutorizadas() == null) {
                    $oMarcacao->setMarcacao(clone $aHorasJornada[$oMarcacao->getTipo()-1]->oHora);
                }
            }

            if($oMarcacao instanceof MarcacaoPontoSaida) {

                $oMarcacaoEntrada = $oMarcacao->getMarcacaoEntrada();

                if($oMarcacao->getJustificativa() !== null) {

                    $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

                    if(isset($aHorasJornada[$oMarcacao->getTipo()-2])) {
                        $oMarcacao->setMarcacaoEntrada(clone $aHorasJornada[$oMarcacao->getTipo()-2]->oHora);
                    }
                }

                if($oMarcacao->getHorarioTrabalhado() != null) {

                    $oIntervaloTrabalhado = $oMarcacao->getHorarioTrabalhado();
                    $oDiaTrabalhado->add($oIntervaloTrabalhado);
                }
            }
        }

        return $oDiaTrabalhado;
    }

    /**
     * Calcula a hora trabalhada do servidor com horário noturno
     * @return \DateTime
     */
    private function calcularHoratrabalhadaComHorarioNoturno() {

        $cargaHoraria = new \DateTime($this->getDiaTrabalho()->getData()->getDate());
        
        $debug = "-- Metodo calcularHoratrabalhadaComHorarioNoturno() ";
        $this->logger->debug($debug);

        $horaNoturnaVinteDuas = clone $cargaHoraria;
        $horaNoturnaVinteDuas->setTime(22, 0);

        $horaNoturnaCincoMesmoDia = clone $cargaHoraria;
        $horaNoturnaCincoMesmoDia->setTime(5, 0);

        $horaNoturnaCincoDiaSeguinte = clone $cargaHoraria;
        $horaNoturnaCincoDiaSeguinte->setTime(5, 0);
        $horaNoturnaCincoDiaSeguinte->modify('+1 day');
        $cargaHorariaDiurna  = 0;
        $cargaHorariaNoturna = 0;

        foreach ($this->getMarcacoes()->getMarcacoes() as $marcacao) {

            if ($marcacao instanceof MarcacaoPontoSaida) {

                if ($marcacao->getMarcacaoEntrada() == '' || $marcacao->getMarcacao() == '') {
                    continue;
                }

                $momentoAtual = clone $marcacao->getMarcacaoEntrada();
                $horaFim      = $marcacao->getMarcacao();

                do{


                    if(   ($this->horaEstaNoIntervalo($momentoAtual, $horaNoturnaVinteDuas, $horaNoturnaCincoDiaSeguinte) && $momentoAtual->getTimestamp() > $horaNoturnaVinteDuas->getTimestamp())
                      || $momentoAtual->getTimestamp() < $horaNoturnaCincoMesmoDia->getTimestamp()
                    ){

                        $cargaHorariaNoturna++;

                    } else {

                        $cargaHorariaDiurna++;

                    }
                    $momentoAtual->modify('+1 minute');

                } while ($momentoAtual->getTimestamp() < $horaFim->getTimestamp());

            }
        }

        $cargaHorariaNoturnaConvertida = BaseHora::converterMinutosEmMinutosNoturnos($cargaHorariaNoturna);
        $cargaHoraria->modify("+ {$cargaHorariaNoturnaConvertida} minutes");
        $cargaHoraria->modify("+ {$cargaHorariaDiurna} minutes");

        $debug = "-- Carga Horaria...................: " . ($cargaHoraria instanceof \DateTime ? $cargaHoraria->format('H:i') : '');
        $this->logger->debug($debug);

        $debug = "-- Carga HoráriaDiurna.............: " . ($cargaHorariaDiurna);
        $this->logger->debug($debug);

        $debug = "-- Carga HoráriaNoturna............: " . ($cargaHorariaNoturna);
        $this->logger->debug($debug);

        $debug = "-- Carga HoráriaConvertida.........: " . ($cargaHorariaNoturnaConvertida);
        $this->logger->debug($debug);

        return $cargaHoraria;

    }

    /**
     * @return bool
     */
    private function marcacaoSaidaDentroToleranciaComApenasDuasMarcacoes()
    {
        return $this->marcacaoEstaNaTolerancia($this->marcacoes->getUltimaMarcacaoComRegistro(), $this->getDiaTrabalho()->getTolerancia(), MarcacaoPonto::SAIDA_2);
    }

    /**
     * Ajusta as marcações de acordo com os horários da jornada, para cálculo dos dias trabalhados, e consequentemente,
     * das faltas
     * @param DiaTrabalho $diaTrabalho
     * @return MarcacoesPontoCollection
     */
    private function ajustarMarcacoesParaCalculo(DiaTrabalho $diaTrabalho)
    {
        $horasJornada = $diaTrabalho->getJornada()->getHoras();
        $marcacoesPontoCollection = $diaTrabalho->getMarcacoes();

        if(count($horasJornada) > 2) {
            if($marcacoesPontoCollection->getQuantidadeMarcacoes() == 2) {
                $marcacoesPontoCollection = $this->ajustesComDuasMarcacoes($marcacoesPontoCollection, $horasJornada);
            }

            if($marcacoesPontoCollection->getQuantidadeMarcacoes() == 4) {
                $marcacoesPontoCollection = $this->ajustesComQuatroMarcacoes($marcacoesPontoCollection, $horasJornada);
            }
        }

        return $marcacoesPontoCollection;
    }

    /**
     * Ajusta o período do intervalo nos casos em que foi feito um tempo menor que o período da jornada
     * Ex.:
     * Jornada   07:00 11:00 12:00 17:00
     * Marcações 07:00 11:16 12:00 17:00
     *
     * @param MarcacoesPontoCollection $marcacoesPontoCollection
     * @param $horasJornada
     * @return MarcacoesPontoCollection
     */
    private function ajustesComQuatroMarcacoes(MarcacoesPontoCollection $marcacoesPontoCollection, $horasJornada)
    {
        $diferencaIntervaloJornada = $horasJornada[1]->oHora->diff($horasJornada[2]->oHora);
        $horaDiferencaIntervaloJornada = $this->getHoraZerada();
        $horaDiferencaIntervaloJornada->add($diferencaIntervaloJornada);

        $diferencaIntervaloMarcacoes = $marcacoesPontoCollection->getMarcacaoSaida1()->getMarcacao()->diff(
          $marcacoesPontoCollection->getMarcacaoEntrada2()->getMarcacao()
        );

        $horaDiferencaIntervaloMarcacoes = $this->getHoraZerada();
        $horaDiferencaIntervaloMarcacoes->add($diferencaIntervaloMarcacoes);

        if($horaDiferencaIntervaloMarcacoes->getTimestamp() < $horaDiferencaIntervaloJornada->getTimestamp()) {
            $minutosAdicionarIntervalo = $horaDiferencaIntervaloMarcacoes->diff($horaDiferencaIntervaloJornada);
            $cloneMarcacaoEntrada2 = clone $marcacoesPontoCollection->getMarcacaoEntrada2();
            $cloneMarcacaoEntrada2->getMarcacao()->add($minutosAdicionarIntervalo);
            $marcacoesPontoCollection->atualizaMarcacao($cloneMarcacaoEntrada2);
        }

        return $marcacoesPontoCollection;
    }

    private function ajustesComDuasMarcacoes(MarcacoesPontoCollection $marcacoesPontoCollection, $horasJornada)
    {
        $primeiraMarcacao = $marcacoesPontoCollection->getMarcacaoEntrada1();
        $segundaMarcacao = $marcacoesPontoCollection->getMarcacaoSaida1();
        $terceiraMarcacao = $marcacoesPontoCollection->getMarcacaoEntrada2();
        $quartaMarcacao = $marcacoesPontoCollection->getMarcacaoSaida2();        

        /**
         * Primeiro período tem Justificativa. Logo, devem ser verificadas as marcações do segundo período
         */
        if($primeiraMarcacao->hasJustificativa()) {
            /**
             * Jornada    07:00 11:00 12:00 17:00
             * Marcações  JUST  JUST  11:48 17:00
             * Resultado  JUST  JUST  12:00 17:00
             */
            if($terceiraMarcacao->getMarcacao() != null) {
                if($terceiraMarcacao->getMarcacao()->getTimestamp() < $horasJornada[2]->oHora->getTimeStamp()) {
                    $terceiraMarcacao->setMarcacao($horasJornada[2]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($terceiraMarcacao);
                }
            }

            /**
             * Jornada    07:00 11:00 12:00 17:00
             * Marcações  JUST  JUST  12:00 17:18
             * Resultado  JUST  JUST  12:00 17:00
             */
            if($quartaMarcacao->getMarcacao() != null) {
                if($quartaMarcacao->getMarcacao()->getTimestamp() > $horasJornada[3]->oHora->getTimeStamp()) {
                    $quartaMarcacao->setMarcacao($horasJornada[3]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($quartaMarcacao);
                }
            }
        }

        /**
         * Segundo período tem Justificativa. Logo, devem ser verificadas as marcações do primeiro período
         */
        if(!empty($terceiraMarcacao) && $terceiraMarcacao->hasJustificativa()) {
            $atualizouPrimeiraMarcacao = false;

            /**
             * Jornada    07:00 11:00 12:00 17:00
             * Marcações  06:46 11:00 JUST  JUST
             * Resultado  07:00 11:00 JUST  JUST
             */
            if($primeiraMarcacao->getMarcacao() != null) {
                if($primeiraMarcacao->getMarcacao()->getTimestamp() < $horasJornada[0]->oHora->getTimeStamp()) {
                    $primeiraMarcacao->setMarcacao($horasJornada[0]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($primeiraMarcacao);
                    $atualizouPrimeiraMarcacao = true;
                }
            }

            if($segundaMarcacao->getMarcacao() != null) {
                $atualizouSegundaMarcacao = false;

                if($atualizouPrimeiraMarcacao) {
                    $segundaMarcacao->setMarcacaoEntrada($primeiraMarcacao->getMarcacao());
                }

                /**
                 * Jornada    07:00 11:00 12:00 17:00
                 * Marcações  12:00 17:19 JUST  JUST
                 * Resultado  12:00 17:00 JUST  JUST
                 */
                if($segundaMarcacao->getMarcacao()->getTimestamp() >= $horasJornada[3]->oHora->getTimeStamp()) {
                    $segundaMarcacao->setMarcacao($horasJornada[3]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($segundaMarcacao);
                    $atualizouSegundaMarcacao = true;
                }

                /**
                 * Caso a marcação não seja maior que a última saída, verificamos se encontra-se entre o período de intervalo
                 */
                if(!$atualizouSegundaMarcacao) {

                    /**
                     * Jornada    07:00 11:00 12:00 17:00
                     * Marcações  12:00 11:19 JUST  JUST
                     * Resultado  12:00 11:00 JUST  JUST
                     */
                    if($segundaMarcacao->getMarcacao()->getTimestamp() > $horasJornada[1]->oHora->getTimeStamp()) {
                        if($segundaMarcacao->getMarcacao()->getTimestamp() < $horasJornada[2]->oHora->getTimeStamp()) {
                            // $segundaMarcacao->setMarcacao($horasJornada[1]->oHora);
                            // $marcacoesPontoCollection->atualizaMarcacao($segundaMarcacao);
                        }
                    }
                }
            }
        }

        /**
         * Segundo conversa com Jean em 30/05 a regra de cálculo para jornadas com 4 marcações e apenas
         * 2 marcações existentes sem justificativas o sistema deve calcular o horário trabalhado, como sendo o 
         * intervalo entre as marcações para q a falta seja a diferença entre a carga horária da jornada e o horário 
         * trabalhado. Uma solução seria 'Estou com sorte (rand(0, 1)) ;)'
         */
        if(!$primeiraMarcacao->hasJustificativa() && !$terceiraMarcacao->hasJustificativa() && (0 == 1)) {
            /**
             * Jornada    07:00 11:00 12:00 17:00
             * Marcações  07:00 16:49   -     -
             * Resultado  07:00 11:00   -     -
             */
            if($primeiraMarcacao->getMarcacao()->getTimestamp() < $horasJornada[1]->oHora->getTimeStamp()) {
                if($segundaMarcacao->getMarcacao()->getTimestamp() > $horasJornada[2]->oHora->getTimeStamp()) {
                    $segundaMarcacao->setMarcacao($horasJornada[1]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($segundaMarcacao);
                }
            }

            if($primeiraMarcacao->getMarcacao()->getTimestamp() > $horasJornada[1]->oHora->getTimeStamp()) {
                /**
                 * Jornada    07:00 11:00 12:00 17:00
                 * Marcações  11:25 16:49   -     -
                 * Resultado  12:00 16:49   -     -
                 */
                if($primeiraMarcacao->getMarcacao()->getTimestamp() < $horasJornada[2]->oHora->getTimeStamp()) {
                    $primeiraMarcacao->setMarcacao($horasJornada[2]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($primeiraMarcacao);
                }

                /**
                 * Jornada    07:00 11:00 12:00 17:00
                 * Marcações  12:15 17:36   -     -
                 * Resultado  12:15 17:00   -     -
                 */
                if($segundaMarcacao->getMarcacao()->getTimestamp() >= $horasJornada[3]->oHora->getTimeStamp()) {
                    $segundaMarcacao->setMarcacao($horasJornada[3]->oHora);
                    $marcacoesPontoCollection->atualizaMarcacao($segundaMarcacao);
                }
            }
        }

        return $marcacoesPontoCollection;
    }

    public function calcularHorasTrabalhoPelaRegra()
    {
        $regraCalculo               = new RegraHoraTrabalho($this->getDiaTrabalho());
        $marcacoesParaCalculoLinear = clone $this->getDiaTrabalho()->getMarcacoes();
        $entradaNoDia               = ($marcacoesParaCalculoLinear->getMarcacaoEntrada1()          ? $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->getMarcacao() : null);
        $ultimaMarcacao             = ($marcacoesParaCalculoLinear->getUltimaMarcacaoComRegistro() ? $marcacoesParaCalculoLinear->getUltimaMarcacaoComRegistro()       : null);
        $jornada                    = $this->getDiaTrabalho()->getJornada();

        if( $entradaNoDia && ($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora->getTimestamp() > $entradaNoDia->getTimestamp()) ) {
            if (isset($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora) && !empty($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora)) {
                $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora);
            } else {
                $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1));
            }
        } else {
            if ($entradaNoDia && ($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora->getTimestamp() < $entradaNoDia->getTimestamp())) {
                // TODO solucao imediata
                $tolerancia = new \DateTime(date("Y-m-d h:i",$jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora->getTimestamp()));
                $tolerancia = $tolerancia->add(new \DateInterval('PT' . $this->getDiaTrabalho()->getTolerancia() . 'M'));
                // Verifica se a batida de entrada esta dentro da tolerancia de tempo configurada
                if ($tolerancia->getTimestamp() > $entradaNoDia->getTimestamp()) {                
                    if (isset($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora) && !empty($jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora)) {
                        $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1)->oHora);
                    } else {
                        $marcacoesParaCalculoLinear->getMarcacaoEntrada1()->setMarcacao(clone $jornada->getHora(MarcacaoPonto::ENTRADA_1));
                    }
                }
            }
        }


        if( $ultimaMarcacao && $jornada->getUltimaHora() && ($jornada->getUltimaHora()->oHora->getTimestamp() < $ultimaMarcacao->getMarcacao()->getTimestamp()) ) {
            $ultimaMarcacao->setMarcacao(clone $jornada->getUltimaHora()->oHora);
        }

        $calculoHoraLinear = new CalculoHoraLinear($this->getDiaTrabalho());
        $calculoHoraLinear->executarCalculo($marcacoesParaCalculoLinear, $regraCalculo);

        return BaseHora::converterStringHoraEmDateTime(BaseHora::converterMinutosEmHoraMinuto($regraCalculo->getMinutosTrabalho()));
    }
}
