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

namespace ECidade\RecursosHumanos\RH\Assentamento;

use Assentamento;
use AssentamentoRepository;
use BusinessException;
use cl_assentamentoabonofalta;
use DateTime;
use db_utils;
use DBDate;
use DBException;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\AdicionalNoturno;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\HorasJustificadas;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use Exception;
use Servidor;
use ServidorRepository;

/**
 * Class AssentamentoAbonoFalta
 * @package ECidade\RecursosHumanos\RH\Assentamento
 */
class AssentamentoAbonoFalta extends Assentamento
{
    /**
     * Código da natureza do assentamento
     *
     * @var int
     */
    const CODIGO_NATUREZA = 9;

    /**
     * @var string
     */
    private $horaInicio = null;

    /**
     * @var string
     */
    private $horaFim = null;

    /**
     * @var string
     */
    private $saldoHoras = '00:00';

    /**
     * AssentamentoAbonoFalta constructor.
     * @param int|null $iCodigo
     * @throws Exception
     */
    public function __construct($iCodigo = null)
    {
        if (empty($iCodigo)) {
            return;
        }

        parent::__construct($iCodigo);

        $oDaoAssentamentoAbonoFalta = new cl_assentamentoabonofalta();
        $sSqlAbonoFalta = $oDaoAssentamentoAbonoFalta->sql_query_file(null, '*', null, "rh213_codigo = {$iCodigo}");
        $rsAbonoFalta = db_query($sSqlAbonoFalta);

        if (!$rsAbonoFalta) {
            throw new DBException(
                "Erro ao consultar dados de horas de abono do assentamento com código: ({$iCodigo}).
                Contate o suporte"
            );
        }

        if (pg_num_rows($rsAbonoFalta) == 0) {
            throw new BusinessException("Assentamento de abono falta ({$iCodigo}) não encontrado no sistema.");
        }

        $classe = $this;

        db_utils::makeCollectionFromRecord(
            $rsAbonoFalta,
            function ($oRetornoAbonoFalta) use ($classe) {

                $classe->setHoraInicio($oRetornoAbonoFalta->rh213_horainicio);
                $classe->setHoraFim($oRetornoAbonoFalta->rh213_horafim);
            }
        );
        $this->saldoHoras = $this->getHora();
    }

    /**
     * @return string
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param string $horaInicio
     * @return $this
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * @return string
     */
    public function getHoraFim()
    {
        return $this->horaFim;
    }

    /**
     * @param string $horaFim
     * @return $this
     */
    public function setHoraFim($horaFim)
    {
        $this->horaFim = $horaFim;

        return $this;
    }

    /**
     * @param string $sHora
     */
    public function setHora($sHora)
    {
        parent::setHora($sHora);
        $this->setSaldoHoras($sHora);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function create()
    {
        return new static;
    }

    /**
     * Persiste na base de dados um assentamento de hora extra manual
     *
     * @return $this|mixed
     * @throws DBException
     */
    public function persist()
    {
        parent::persist();

        $DAOAssentamentoAbonoFalta = new cl_assentamentoabonofalta();
        $DAOAssentamentoAbonoFalta->excluir($this->getCodigo());

        if ($DAOAssentamentoAbonoFalta->erro_status == '0') {
            throw new DBException($DAOAssentamentoAbonoFalta->erro_msg);
        }

        $DAOAssentamentoAbonoFalta->rh213_codigo = $this->getCodigo();
        $DAOAssentamentoAbonoFalta->rh213_horainicio = $this->horaInicio;
        $DAOAssentamentoAbonoFalta->rh213_horafim = $this->horaFim;
        $DAOAssentamentoAbonoFalta->incluir(null);

        if ($DAOAssentamentoAbonoFalta->erro_status == '0') {
            throw new DBException($DAOAssentamentoAbonoFalta->erro_msg);
        }

        return $this;
    }

    /**
     * @param Servidor $servidor
     * @param DBDate $data
     * @param string $horaInicio
     * @param string $horaFim
     * @return array
     * @throws Exception
     */
    public static function getHorasDeAbono(Servidor $servidor, DBDate $data, $horaInicio, $horaFim)
    {
        $mensagem = '';
        $escalas = $servidor->getEscalas();

        $escalaNaData = ProcessamentoPontoEletronico::getEscalaNaData($escalas, $data);

        $diaTrabalhoRepository = new DiaTrabalhoRepository();
        $diaTrabalhoRepository->setEscalaServidor($escalaNaData);

        $diaTrabalho = $diaTrabalhoRepository->getDiaTrabalhoServidor($servidor, $data);

        $horaInicio = DateTime::createFromFormat('Y-m-d H:i', "{$data->getDate()} {$horaInicio}");
        $horaFim = DateTime::createFromFormat('Y-m-d H:i', "{$data->getDate()} {$horaFim}");

        if ($horaInicio->getTimestamp() > $horaFim->getTimestamp()) {
            $horaFim->modify('+1 day');
        }

        if ($diaTrabalho->getMarcacoes()->isEmpty() || !$servidor->registraPontoEletronico()) {
            $intervalo = $horaInicio->diff($horaFim);

            $horas = $intervalo->h + ($intervalo->days * 24);
            $horas = str_pad($horas, 2, '0', STR_PAD_LEFT);

            $diferenca = "{$horas}:{$intervalo->format('%S')}";

            return [$diferenca, $mensagem];
        }

        $horasJustificadas = new HorasJustificadas($diaTrabalho);
        $horasJustificadas->setHoraInicio($horaInicio)->setHoraFim($horaFim)->calcular();

        $diferenca = $horasJustificadas->getHoraJustificada()->format('H:i');

        if ($horasJustificadas->hasHoraTrabalhadaNoIntervaloJustificado()) {
            if ($horasJustificadas->getHoraJustificada()->format('H') != 0 ||
                $horasJustificadas->getHoraJustificada()->format('i') != 0) {
                $mensagem = "Existem horas trabalhadas no período informado.
                Será abonado o período de horas: {$diferenca}";
            }
        }

        return [$diferenca, $mensagem];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function retornarHorasAbonar()
    {
        $matricula = $this->getMatricula();
        $dataConcessao = $this->getDataConcessao();

        if (empty($matricula)) {
            throw new BusinessException('Informe a matrícula para verificar as horas justificadas.');
        }

        if (empty($dataConcessao)) {
            throw new BusinessException('Informe a data para verificar as horas justificadas.');
        }

        if (empty($this->horaInicio)) {
            throw new BusinessException('Informe a hora de início para verificar as horas justificadas.');
        }

        if (empty($this->horaFim)) {
            throw new BusinessException('Informe a hora final para verificar as horas justificadas.');
        }

        list($diferenca, $mensagem) = static::getHorasDeAbono(
            $this->getServidor(),
            $dataConcessao,
            $this->horaInicio,
            $this->horaFim
        );

        $this->setHora($diferenca);

        return $mensagem;
    }

    /**
     * @param $dataInicio
     * @param $dataFim
     * @param $matricula
     * @return bool
     * @throws Exception
     */
    public function validarExistenciaAssentamento($dataInicio, $dataFim, $matricula)
    {
        $aAssentamentoAbonoFaltaEmHoras = AssentamentoRepository::getAssentamentosServidorPorTipoENatureza(
            ServidorRepository::getInstanciaByCodigo($matricula),
            'S',
            new DBDate($dataInicio),
            Assentamento::NATUREZA_ABONO_FALTA
        );
        $dataInformada = $dataInicio;

        if (empty($aAssentamentoAbonoFaltaEmHoras)) {
            $aAssentamentoAbonoFaltaEmHoras = AssentamentoRepository::getAssentamentosServidorPorTipoENatureza(
                ServidorRepository::getInstanciaByCodigo($matricula),
                'S',
                new DBDate($dataFim),
                Assentamento::NATUREZA_ABONO_FALTA
            );
            $dataInformada = $dataFim;
        }

        if (!empty($aAssentamentoAbonoFaltaEmHoras)) {
            foreach ($aAssentamentoAbonoFaltaEmHoras as $oAssentamentoAbonoFaltaEmHoras) {
                if (empty($oAssentamentoAbonoFaltaEmHoras->getHorasAbonoHoras()->horaInicio) ||
                    empty($oAssentamentoAbonoFaltaEmHoras->getHorasAbonoHoras()->horaFim)) {
                    continue;
                }

                $horaInicioAssentamento = new DateTime(
                    $oAssentamentoAbonoFaltaEmHoras->getHorasAbonoHoras()->horaInicio
                );
                $horaFimAssentamento = new DateTime($oAssentamentoAbonoFaltaEmHoras->getHorasAbonoHoras()->horaFim);

                $horaInicioInformado = new DateTime($this->horaInicio);
                $horaFimInformado = new DateTime($this->horaFim);

                if ($horaFimAssentamento->getTimeStamp() > $horaInicioInformado->getTimeStamp()) {
                    if ($horaInicioAssentamento->getTimeStamp() < $horaFimInformado->getTimeStamp()) {
                        $msg = "Já existe um assentamento ({$oAssentamentoAbonoFaltaEmHoras->getCodigo()}) da natureza";
                        $msg .= " (abono falta) para este servidor.";
                        $msg .= "\nCom as seguintes informações: ({$dataInformada} das";
                        $msg .= " {$horaInicioAssentamento->format('H:i')}h às";
                        $msg .= " {$horaFimAssentamento->format('H:i')}h).";
                        $msg .= "\nRealize a alteração no assentamento existente.";

                        throw new Exception($msg);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param string $saldo
     * @return string
     */
    public function setSaldoHoras($saldo)
    {
        return $this->saldoHoras = $saldo;
    }

    /**
     * @return string
     */
    public function getSaldoHoras()
    {
        return $this->saldoHoras;
    }

    /**
     * Calcula as horas totais de um dia de trabalho para um Assentamento do tipo Afastamento, desmembrando essas horas
     * em horas diurnas e noturnas.
     *
     * @param DiaTrabalho $diaTrabalho
     * @return null|String
     */
    public function calcularHorasDiurnasNoturnasNoDia(DiaTrabalho $diaTrabalho)
    {
        $horasTotais = array();
        $baseHora = new AdicionalNoturno($diaTrabalho);

        $inicioJornada = $diaTrabalho->getJornada()->getInicioJornada();
        $fimJornada = $diaTrabalho->getJornada()->getFimJornada();

        if ($diaTrabalho->getMarcacoes()->getPrimeiraMarcacaoComRegistro() != null &&
            $diaTrabalho->getMarcacoes()->getUltimaMarcacaoComRegistro() != null) {
            $primeiraMarcacao = $diaTrabalho->getMarcacoes()->getPrimeiraMarcacaoComRegistro()->getMarcacao();
            $ultimaMarcacao = $diaTrabalho->getMarcacoes()->getUltimaMarcacaoComRegistro()->getMarcacao();

            $data = $diaTrabalho->getData();
            $horaInicio = BaseHora::converterStringHoraEmDateTime($this->getHoraInicio());
            $horaInicio->setDate($data->getAno(), $data->getMes(), $data->getDia());

            $horaFim = BaseHora::converterStringHoraEmDateTime($this->getHoraFim());
            $horaFim->setDate($data->getAno(), $data->getMes(), $data->getDia());

            if (($primeiraMarcacao > $inicioJornada) &&
                ($primeiraMarcacao > $horaInicio) &&
                ($primeiraMarcacao <= $horaFim)) {
                $horasTotais[] = $baseHora->percorreMinutoAMinuto($horaInicio, $primeiraMarcacao, true);
            }


            if (($ultimaMarcacao < $fimJornada) && ($ultimaMarcacao < $horaFim) && ($ultimaMarcacao >= $horaInicio)) {
                $horasTotais[] = $baseHora->percorreMinutoAMinuto($ultimaMarcacao, $horaFim, true);
            }
        }

        return $this->totalizarHorasDiurnasENoturnas($horasTotais);
    }
}
