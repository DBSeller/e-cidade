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

use ECidade\Configuracao\Cadastro\Model\Feriado;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada as JornadaModel;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory\TipoHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\V3\Extension\Logger;
use ECidade\V3\Extension\Registry;

/**
 * Classe com as informações referentes ao dia de trabalho de um servidor
 * Class DiaTrabalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class DiaTrabalho
{

  /**
   * Código sequencial do dia dos registros
   * @var int
   */
    private $iCodigo;

  /**
   * Instância do servidor
   * @var \Servidor
   */
    private $oServidor;

  /**
   * Instância da jornada do dia do servidor
   * @var Jornada
   */
    private $oJornada;

  /**
   * Data do dia de trabalho
   * @var \DBDate
   */
    private $oData;

  /**
   * Marcações do servidor no dia
   * @var array
   */
    private $aMarcacoes = array();

  /**
   * Horas normais de trabalho
   * @var string
   */
    private $sHorasTrabalho;


    /**
     * Horas Totais de Trabalho
     * @var string
     */
    private $sHorasTotaisDeTrabalho;

  /**
   * Horas de Adicional Noturna
   * @var string
   */
    private $sHorasAdicionalNoturno;

  /**
   * Horas Extra50
   * @var string
   */
    private $sHorasExtra50;

  /**
   * Horas Extra75
   * @var string
   */
    private $sHorasExtra75;

  /**
   * Horas Extra100
   * @var string
   */
    private $sHorasExtra100;

  /**
   * Horas falta no dia
   * @var string
   */
    private $sHorasFalta;

    /**
     * @var string
     */
    private $sHorasFaltaNoturna;

  /**
   * Código do arquivo de marcações
   * @var Integer
   */
    private $iCodigoArquivo;

  /**
   * @var null|Feriado
   */
    private $oFeriado = null;

  /**
   * Configurações da lotação do servidor
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
    private $oConfiguracoesLotacao;

  /**
   * Tempo de Tolerância
   * @var Integer
   */
    private $iTolerancia;

  /**
   * Horas Extra50Noturna
   * @var string
   */
    private $sHorasExtra50Noturna;

  /**
   * Horas Extra75Noturna
   * @var string
   */
    private $sHorasExtra75Noturna;

  /**
   * Horas Extra100Noturna
   * @var string
   */
    private $sHorasExtra100Noturna;

  /**
   * Se há afastamento no dia de trabalho à processar
   * @var boolean
   */
    private $lAfastado = false;

  /**
   * A justificativa para o afastamento no dia de trabalho à processar
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
    private $oJustificativaAfastamento;

  /**
   * Assentamento
   * var Assentamento
   */
    private $afastamento;

  /**
   * @var ParametrosGerais
   */
    private $oParametrosPontoEletronico;

  /**
   * @var bool
   */
    private $lCalculaHoraExtra = false;

  /**
   * @var null|\DateTime
   */
    private $oHorasExtrasAutorizadas = null;

  /**
   * @var Evento
   */
    private $evento = null;

  /**
   * @var \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual []
   */
    private $assentamentosHoraExtraManual = array();

    private $oAssentamentosAbonofalta = null;


    /**
     * @var MarcacoesPontoCollection
     */
    private $marcacoesSemAlteracao = null;

  /**
     * @var string
     */
    private $sHorasAtraso;

    /**
     * @var string
     */
    private $sHorasAtrasoNoturno;

    /**
     * @var string
     */
    private $sHorasSaidaAntecipada;

    /**
     * @var string
     */
    private $sHorasSaidaAntecipadaNoturna;

    private $marcacoesReais;

    protected $logger = null;
    /**
     * @var string
     */
    private $sHorasExtra50NaoAutorizadas;
    /**
     * @var string
     */
    private $sHorasExtra75NaoAutorizadas;
    /**
     * @var string
     */
    private $sHorasExtra100NaoAutorizadas;
    /**
     * @var string
     */
    private $sHorasExtra50NaoAutorizadasNoturna;
    /**
     * @var string
     */
    private $sHorasExtra75NaoAutorizadasNoturna;
    /**
     * @var string
     */
    private $sHorasExtra100NaoAutorizadasNoturna;
    /**
     * @var string
     */
    private $sHorasExtraNaoAutorizadas;
    /**
     * @var string
     */
    private $sHorasExtraNaoAutorizadasNoturna;

    /** @var \Assentamento[] */
    private $assentamentosJustificativaServidor = array();

    /**
   * Construtor da classe
   *
   * @param \DBDate   $oData
   * @param \Servidor $oServidor
   */
    public function __construct($oData = null, $oServidor = null)
    {

        if (!empty($oData)) {
            $this->oData     = $oData;
        }

        if (!empty($oServidor)) {
            $this->oServidor = $oServidor;
        }

        $this->aMarcacoes = new MarcacoesPontoCollection();

        $path   = ECIDADE_PATH . 'tmp/.log/';

        if (!is_dir($path)) {
            mkdir($path);
        }

        $path  .= 'calculo_horas_ponto_eletronico_'. date('Ymd') .'.log';

        if (Registry::get('app.container')->has('app.ponto_eletronico.debug')
            && Registry::get('app.container')->get('app.ponto_eletronico.debug')) {
            $this->logger = new Logger($path, Logger::DEBUG);
        } else {
            $this->logger = new Logger();
        }
    }

  /**
   * @return int
   */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

  /**
   * Retorna o Servidor
   *
   * @return \Servidor
   */
    public function getServidor()
    {
        return $this->oServidor;
    }

  /**
   * Retorna a Jornada
   *
   * @return Jornada
   */
    public function getJornada()
    {
        return $this->oJornada;
    }

  /**
   * Retorna a Data
   *
   * @return \DBDate
   */
    public function getData()
    {
        return $this->oData;
    }

  /**
   * @return MarcacoesPontoCollection
   */
    public function getMarcacoes()
    {
        return $this->aMarcacoes;
    }

  /**
   * Retorna as Horas de trabalho
   *
   * @return string
   */
    public function getHorasTrabalho()
    {
        return $this->sHorasTrabalho;
    }

  /**
   * Retorna Horas de Adicional Noturna
   *
   * @return string
   */
    public function getHorasAdicionalNoturno()
    {
        return $this->sHorasAdicionalNoturno;
    }

  /**
   * Retorna Horas Extra50
   *
   * @return string
   */
    public function getHorasExtra50()
    {
        return $this->sHorasExtra50;
    }

  /**
   * Retorna Horas Extra75
   *
   * @return string
   */
    public function getHorasExtra75()
    {
        return $this->sHorasExtra75;
    }

  /**
   * Retorna Horas Extra100
   *
   * @return string
   */
    public function getHorasExtra100()
    {
        return $this->sHorasExtra100;
    }

  /**
   * Retorna Horas Extra50Noturna
   *
   * @return string
   */
    public function getHorasExtra50Noturna()
    {
        return $this->sHorasExtra50Noturna;
    }

  /**
   * Retorna Horas Extra75Noturna
   *
   * @return string
   */
    public function getHorasExtra75Noturna()
    {
        return $this->sHorasExtra75Noturna;
    }

  /**
   * Retorna Horas Extra100Noturna
   *
   * @return string
   */
    public function getHorasExtra100Noturna()
    {
        return $this->sHorasExtra100Noturna;
    }

  /**
   * Retorna as Horas de falta
   *
   * @return string
   */
    public function getHorasFalta()
    {
        return $this->sHorasFalta;
    }

  /**
   * Retorna o código do arquivo
   *
   * @return Integer
   */
    public function getCodigoArquivo()
    {
        return $this->iCodigoArquivo;
    }

  /**
   * @return Feriado
   */
    public function getFeriado()
    {
        return $this->oFeriado;
    }

  /**
   * Retorna as configurações da lotação do servidor
   *
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
    public function getConfiguracoesLotacao()
    {
        return $this->oConfiguracoesLotacao;
    }

  /**
   * Retorna o tempo configurado para tolerância em minutos
   *
   * @return integer [description]
   */
    public function getTolerancia()
    {
        return $this->iTolerancia;
    }

  /**
   * Retorna se no dia de trabalho em questão o servidor está afastado
   * @return boolean
   */
    public function isAfastado()
    {
        return $this->lAfastado;
    }

  /**
   * Retorna a justificativa do afastamento
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
    public function getJustificativaAfastamento()
    {
        return $this->oJustificativaAfastamento;
    }

  /**
   * @param int $iCodigo
   */
    public function setCodigo($iCodigo)
    {
        $this->iCodigo = $iCodigo;
    }

  /**
   * Define o Servidor
   *
   * @param \Servidor $oServidor
   */
    public function setServidor(\Servidor $oServidor)
    {
        $this->oServidor = $oServidor;
    }

  /**
   * Define a Jornada
   *
   * @param Jornada $oJornada
   */
    public function setJornada(JornadaModel $oJornada)
    {
        $this->oJornada = $oJornada;
    }

  /**
   * Define a Data
   *
   * @param \DBDate $oData
   */
    public function setData(\DBDate $oData)
    {
        $this->oData = $oData;
    }

  /**
   * Define as marcações
   * @param MarcacoesPontoCollection $aMarcacoes
   */
    public function setMarcacoes(MarcacoesPontoCollection $aMarcacoes)
    {
        $this->aMarcacoes = $aMarcacoes;
    }

  /**
   * Define as horas de trabalho
   *
   * @param string $sHorasTrabalho
   */
    public function setHorasTrabalho($sHorasTrabalho)
    {
        $this->sHorasTrabalho = $sHorasTrabalho;
    }

  /**
   * Define as Horas de Adicional Noturna
   *
   * @param string $sHorasAdicionalNoturno
   */
    public function setHorasAdicionalNoturno($sHorasAdicionalNoturno)
    {
        $this->sHorasAdicionalNoturno = $sHorasAdicionalNoturno;
    }

  /**
   * Define as Horas Extra50
   *
   * @param string $sHorasExtra50
   */
    public function setHorasExtra50($sHorasExtra50)
    {
        $this->sHorasExtra50 = $sHorasExtra50;
    }

  /**
   * Define as Horas Extra75
   *
   * @param string $sHorasExtra75
   */
    public function setHorasExtra75($sHorasExtra75)
    {
        $this->sHorasExtra75 = $sHorasExtra75;
    }

  /**
   * Define as Horas Extra100
   *
   * @param string $sHorasExtra100
   */
    public function setHorasExtra100($sHorasExtra100)
    {
        $this->sHorasExtra100 = $sHorasExtra100;
    }

  /**
   * Define as Horas Extra50Noturna
   *
   * @param string $sHorasExtra50Noturna
   */
    public function setHorasExtra50Noturna($sHorasExtra50Noturna)
    {
        $this->sHorasExtra50Noturna = $sHorasExtra50Noturna;
    }

  /**
   * Define as Horas Extra75Noturna
   *
   * @param string $sHorasExtra75Noturna
   */
    public function setHorasExtra75Noturna($sHorasExtra75Noturna)
    {
        $this->sHorasExtra75Noturna = $sHorasExtra75Noturna;
    }

  /**
   * Define as Horas Extra100Noturna
   *
   * @param string $sHorasExtra100Noturna
   */
    public function setHorasExtra100Noturna($sHorasExtra100Noturna)
    {
        $this->sHorasExtra100Noturna = $sHorasExtra100Noturna;
    }

  /**
   * Define as Horas de falta
   *
   * @param string $sHorasFalta
   */
    public function setHorasFalta($sHorasFalta)
    {
        $this->sHorasFalta = $sHorasFalta;
    }

  /**
   * Define o código do arquivo
   *
   * @param Integer $iCodigoArquivo
   */
    public function setCodigoArquivo($iCodigoArquivo)
    {
        $this->iCodigoArquivo = $iCodigoArquivo;
    }

  /**
   * @param Feriado $oFeriado
   */
    public function setFeriado(Feriado $oFeriado)
    {
        $this->oFeriado = $oFeriado;
    }

  /**
   * Define as configurações da lotação do servidor
   *
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao $oConfiguracoesLotacao
   */
    public function setConfiguracoesLotacao($oConfiguracoesLotacao)
    {
        $this->oConfiguracoesLotacao = $oConfiguracoesLotacao;
    }

  /**
   * Define o tempo de tolerância em minutos
   *
   * @param Integer $iTolerancia
   */
    public function setTolerancia($iTolerancia)
    {
        $this->iTolerancia = $iTolerancia;
    }

  /**
   * Define se o servidor está afastado
   * @param boolean $lAfastado
   * @return $this
   */
    public function setAfastado($lAfastado)
    {
        $this->lAfastado = $lAfastado;
        return $this;
    }

  /**
   * Define a justificativa do afastamento
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa $oJustificativaAfastamento
   * @return $this
   */
    public function setJustificativaAfastamento($oJustificativaAfastamento)
    {
        $this->oJustificativaAfastamento = $oJustificativaAfastamento;
        return $this;
    }

  /**
   * @return mixed
   */
    public function getParametrosPontoEletronico()
    {
        return $this->oParametrosPontoEletronico;
    }

  /**
   * @param mixed $oParametrosPontoEletronico
   */
    public function setParametrosPontoEletronico($oParametrosPontoEletronico)
    {
        $this->oParametrosPontoEletronico = $oParametrosPontoEletronico;
    }

  /**
   * @return bool
   */
    public function isCalculaHoraExtra()
    {
        return $this->lCalculaHoraExtra;
    }

  /**
   * @param bool $lCalculaHoraExtra
   */
    public function setCalculaHoraExtra($lCalculaHoraExtra)
    {
        $this->lCalculaHoraExtra = $lCalculaHoraExtra;
    }

  /**
   * @return \DateTime|null
   */
    public function getHorasExtrasAutorizadas()
    {
        return $this->oHorasExtrasAutorizadas;
    }

  /**
   * @param \DateTime|null $oHorasExtrasAutorizadas
   */
    public function setHorasExtrasAutorizadas($oHorasExtrasAutorizadas)
    {
        $this->oHorasExtrasAutorizadas = $oHorasExtrasAutorizadas;
    }

  /**
   * @param \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual[] $assentamentosHoraExtraManual
   * @return $this
   */
    public function setAssentamentosHoraExtraManual($assentamentosHoraExtraManual)
    {
        $this->assentamentosHoraExtraManual = $assentamentosHoraExtraManual;
        return $this;
    }

  /**
   * @return \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual[]
   */
    public function getAssentamentosHoraExtraManual()
    {
        return $this->assentamentosHoraExtraManual;
    }

  /**
   * Calcula as horas trabalhadas, extras, faltas e zaz
   */
    public function calcularHoras()
    {

        $this->logger->debug("--------------------------------");
        $this->logger->debug("-- INICIO DO CALCULO DE HORAS --");
        $this->logger->debug("--------------------------------");
        $this->logger->debug("-- MATRICULA...............: ". $this->getServidor()->getMatricula());
        $this->logger->debug("-- DATA....................: ". $this->getData()->getDate(\DBDate::DATA_PTBR));

        $this->setTolerancia($this->getConfiguracoesLotacao()->getTolerancia());

        $oHorasTrabalho        = TipoHora::getHora($this, BaseHora::HORAS_TRABALHO);

        $oHoraAdicionalNoturno = TipoHora::getHora($this, BaseHora::HORAS_ADICIONAL_NOTURNO);
        $oHoraFalta            = TipoHora::getHora($this, BaseHora::HORAS_FALTA);

        $oHoraAtraso           = TipoHora::getHora($this, BaseHora::HORAS_ATRASO);
        $oHoraSaidaAntecipada  = TipoHora::getHora($this, BaseHora::HORAS_SAIDA_ANTECIPADA);

        $this->marcacoesSemAlteracao  = clone $oHorasTrabalho->getMarcacoesReais();
        $this->sHorasTrabalho         = '';
        $this->sHorasFalta            = '';
        $this->sHorasAdicionalNoturno = '';
        $this->sHorasExtra50          = '';
        $this->sHorasExtra75          = '';
        $this->sHorasExtra100         = '';
        $this->sHorasExtra50Noturna   = '';
        $this->sHorasExtra75Noturna   = '';
        $this->sHorasExtra100Noturna  = '';
        $this->sHorasAtraso           = '';
        $this->sHorasSaidaAntecipada  = '';
        $this->sHorasExtra50NaoAutorizadas = '';
        $this->sHorasExtra75NaoAutorizadas = '';
        $this->sHorasExtra100NaoAutorizadas = '';
        $this->sHorasExtra50NaoAutorizadasNoturna = '';
        $this->sHorasExtra75NaoAutorizadasNoturna = '';
        $this->sHorasExtra100NaoAutorizadasNoturna = '';
        $this->sHorasExtraNaoAutorizadas = '';
        $this->sHorasExtraNaoAutorizadasNoturna = '';

        $this->setHorasTotaisDeTrabalho('');

        $this->logger->debug("-- JORNADA.................: " . implode('|', $this->getJornada()->toArray()));
        $this->logger->debug("-- MARCACOES...............: " . implode('|', $this->marcacoesSemAlteracao->toArray()));
        $this->logger->debug("-- TIPO DE JORNADA.........: " . $this->getJornada()->getTipoDescricao());
        $this->logger->debug("-- FERIADO.................: " . ($this->getFeriado() ? 'Sim' : 'Nao'));
        $this->logger->debug("-- SERVIDOR AFASTADO.......: " . ($this->isAfastado() ? 'Sim' : 'Nao'));

        $this->logger->debug("---------------------------- ");
        $this->logger->debug("-------- PARAMETROS -------- ");
        $this->logger->debug("---------------------------- ");
        $debug = "-- Hora Extra SOMENTE COM autorizacao..........: ";
        $debug .= ($this->getParametrosPontoEletronico()->horaExtraSomenteComAutorizacao() ? 'Sim' : 'Nao');
        $this->logger->debug($debug);

        $debug = "-- Extras automaticas no Feriado...............: ";
        if ($this->getServidor()->getEscala($this->getData())->getEscalaTrabalho()->isExtraAutomaticaFeriado()) {
            $debug .= "Sim";
        } else {
            $debug .= "Nao";
        }
        $this->logger->debug($debug);

        $debug = "-- Tem assentamento de autorização de HE.......: ";
        $this->logger->debug($debug . ((bool)$this->getHorasExtrasAutorizadas() ? 'Sim' : 'Nao'));
        $debug = "-- Calcular Horas Extras.......................: ";
        $this->logger->debug($debug . ($this->isCalculaHoraExtra() ? 'Sim' : 'Nao'));

        $debug = "-- Escala de Revezamento.......................: ";
        if ($this->getServidor()->getEscalas($this->getData())->getEscalaTrabalho()->isRevezamento()) {
            $debug .= "Sim";
        } else {
            $debug .= "Nao";
        }
        $this->logger->debug($debug);

        $debug = "-- Codigo da Escala............................: ";
        $debug .= $this->getServidor()->getEscalas($this->getData())->getEscalaTrabalho()->getCodigo();
        $this->logger->debug($debug);

        if ($this->getAssentamentosAbonofalta() !== null) {
            $aAssentamentosAbonofalta = $this->getAssentamentosAbonofalta();

            foreach ($aAssentamentosAbonofalta as $oAssentamentoAbonofalta) {
                if ($oAssentamentoAbonofalta instanceof AssentamentoAbonoFalta
                    && !$oAssentamentoAbonofalta->getInstanciaTipoAssentamento()->isGerarFaltas()) {
                    $debug  = '-- Assentamento Abono Falta....................: ';
                    if ($oAssentamentoAbonofalta->getHoraInicio() !== null) {
                        $debug .= $oAssentamentoAbonofalta->getHoraInicio();
                    } else {
                        $debug .= '0:00';
                    }

                    $debug .= ' as ';
                    if ($oAssentamentoAbonofalta->getHoraFim() !== null) {
                        $debug .= $oAssentamentoAbonofalta->getHoraFim();
                    } else {
                        $debug .= '0:00';
                    }

                    $debug .= ' = '. $oAssentamentoAbonofalta->getHora();
                    $this->logger->debug($debug);
                }
            }
        }

        if ($this->getJornada()->isDiaTrabalhado()) {
            $this->setHorasTotaisDeTrabalho($oHorasTrabalho->calcularHoraTrabalhoTotal()->format('H:i'));
            $oTotalHorasTrabalhadas = $oHorasTrabalho->calcular();
            $this->sHorasTrabalho   = $oTotalHorasTrabalhadas->format('H:i');

            $oHoraAdicionalNoturno->setHorasTrabalhadas($oTotalHorasTrabalhadas);
            $this->sHorasAdicionalNoturno = $oHoraAdicionalNoturno->calcular();

            $oHoraFalta->setHorasTrabalhadas($oTotalHorasTrabalhadas);
            $this->sHorasFalta = $oHoraFalta->calcular()->format('H:i');
            $this->sHorasFaltaNoturna = $oHoraFalta->getHorasFaltaNoturna()->format('H:i');

            $oHoraAtraso->setHorasTrabalhadas($oTotalHorasTrabalhadas);
            $this->sHorasAtraso = $oHoraAtraso->calcular()->format('H:i');
            $this->sHorasAtrasoNoturno = $oHoraAtraso->getHorasAtrasoNoturno()->format('H:i');

            $oHoraSaidaAntecipada->setHorasTrabalhadas($oTotalHorasTrabalhadas);
            $this->sHorasSaidaAntecipada = $oHoraSaidaAntecipada->calcular()->format('H:i');
            $this->sHorasSaidaAntecipadaNoturna = $oHoraSaidaAntecipada
                ->getHorasSaidaAntecipadaNoturna()->format('H:i');
        }

      /**
       * Se trabalhou (tem marcações),
       * faz os cálculos de extras e adicional Noturna
       * também calcular horas de trabalho e falta se precisar
       */
        if (!$this->getMarcacoes()->isEmpty()) {
            if (!$this->getJornada()->isDiaTrabalhado()) {
                $oTotalDeHorasTrabalhadosNoDia = $oHorasTrabalho->calcularHoraTrabalhoTotal();
                $this->setHorasTotaisDeTrabalho($oTotalDeHorasTrabalhadosNoDia->format('H:i'));
                $oTotalHorasTrabalhadas = $oHorasTrabalho->calcular();
                $this->sHorasTrabalho   = $oTotalHorasTrabalhadas->format('H:i');

                $oHoraAdicionalNoturno->setHorasTrabalhadas($oTotalDeHorasTrabalhadosNoDia);
                $this->sHorasAdicionalNoturno = $oHoraAdicionalNoturno->calcular();

                if ($this->getJornada()->temHorarioNoturno($this->getData()->getDate())) {
                    $horasTrabalhoAjustadas = $this->ajustarHorasTrabalhoNoturna($oHoraAdicionalNoturno);

                    if (preg_match('/\d+:\d+/', $horasTrabalhoAjustadas)) {
                        list($horaTrabalhadaAjustada, $minutoTrabalhadoAjustado) = explode(
                            ':',
                            $horasTrabalhoAjustadas
                        );

                        if (!empty($horaTrabalhadaAjustada) && !empty($minutoTrabalhadoAjustado)) {
                            $oTotalHorasTrabalhadas->setTime($horaTrabalhadaAjustada, $minutoTrabalhadoAjustado);
                        }
                    }
                }

                $oHoraFalta->setHorasTrabalhadas($oTotalHorasTrabalhadas);
                $this->sHorasFalta = $oHoraFalta->calcular()->format('H:i');
                $this->sHorasFaltaNoturna = $oHoraFalta->getHorasFaltaNoturna()->format('H:i');

                $oHoraAtraso->setHorasTrabalhadas($oTotalHorasTrabalhadas);
                $this->sHorasAtraso = $oHoraAtraso->calcular()->format('H:i');
                $this->sHorasAtrasoNoturno = $oHoraAtraso->getHorasAtrasoNoturno()->format('H:i');

                $oHoraSaidaAntecipada->setHorasTrabalhadas($oTotalHorasTrabalhadas);
                $this->sHorasSaidaAntecipada = $oHoraSaidaAntecipada->calcular()->format('H:i');
                $this->sHorasSaidaAntecipadaNoturna = $oHoraSaidaAntecipada
                    ->getHorasSaidaAntecipadaNoturna()
                    ->format('H:i');
            }

            if (!empty($this->evento)) {
                $this->logger->debug("---------------------------- ");
                $this->logger->debug("---------- EVENTO ---------- ");
                $this->logger->debug("---------------------------- ");

                $debug = "-- Evento.........: ";
                if ($this->evento->getTitulo()) {
                    $debug .= $this->evento->getTitulo();
                } else {
                    $debug .= '';
                }

                $periodoEvento = '';
                if ($this->evento->getDataInicial() instanceof \DBDate) {
                    $periodoEvento  = $this->evento->getDataInicial(\DBDate::DATA_PTBR);

                    if ($this->evento->getDataFinal() instanceof \DBDate) {
                        $periodoEvento .= ' a ';
                        $periodoEvento .= $this->evento->getDataFinal(\DBDate::DATA_PTBR);
                    }
                }
                $this->logger->debug("-- Periodo........: ". (!empty($periodoEvento) ? $periodoEvento : ''));

                return $this->calcularHorasDiaComEvento();
            }

            $oHoraExtraCalculo = TipoHora::getHora($this, BaseHora::HORAS_EXTRA_CALCULO);
            $oHoraExtraCalculo->calcular();

            /**
             * Se o parâmetro para calcular horas extras somente com autorização estiver como SIM e não possuir
             * assentamento de autorização
             */
            if ($this->getParametrosPontoEletronico()->horaExtraSomenteComAutorizacao()
                && !$this->isCalculaHoraExtra()) {
                $this->sHorasExtra50          = '';
                $this->sHorasExtra75          = '';
                $this->sHorasExtra100         = '';
                $this->sHorasExtra50Noturna   = '';
                $this->sHorasExtra75Noturna   = '';
                $this->sHorasExtra100Noturna  = '';
                $this->sHorasExtra50NaoAutorizadas = '';
                $this->sHorasExtra75NaoAutorizadas = '';
                $this->sHorasExtra100NaoAutorizadas = '';
                $this->sHorasExtra50NaoAutorizadasNoturna = '';
                $this->sHorasExtra75NaoAutorizadasNoturna = '';
                $this->sHorasExtra100NaoAutorizadasNoturna = '';
                $this->sHorasExtraNaoAutorizadas = '';
                $this->sHorasExtraNaoAutorizadasNoturna = '';

                $this->logger->debug("-- Zerando horas extras calculadas --- ");
            }
        }

        $this->logger->debug("---------------------------- ");
        $this->logger->debug("----- HORAS CALCULADAS ----- ");
        $this->logger->debug("---------------------------- ");

        $debug = "-- Horas de Trabalho.................: ";
        $this->logger->debug($debug . (!empty($this->sHorasTrabalho) ? $this->sHorasTrabalho : ''));

        $debug = "-- Horas de Falta....................: ";
        $this->logger->debug($debug . (!empty($this->sHorasFalta) ? $this->sHorasFalta : ''));

        $debug = "-- Horas de Adicional Noturno........: ";
        $this->logger->debug($debug . (!empty($this->sHorasAdicionalNoturno) ? $this->sHorasAdicionalNoturno : ''));

        $debug = "-- Horas Extra 50....................: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra50) ? $this->sHorasExtra50 : ''));

        $debug = "-- Horas Extra 75....................: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra75) ? $this->sHorasExtra75 : ''));

        $debug = "-- Horas Extra 100...................: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra100) ? $this->sHorasExtra100 : ''));

        $debug = "-- Horas Extra50 Noturnas............: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra50Noturna) ? $this->sHorasExtra50Noturna : ''));

        $debug = "-- Horas Extra75 Noturnas............: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra75Noturna) ? $this->sHorasExtra75Noturna : ''));

        $debug = "-- Horas Extra100 Noturnas...........: ";
        $this->logger->debug($debug . (!empty($this->sHorasExtra100Noturna) ? $this->sHorasExtra100Noturna : ''));

        $debug = "-- Horas de Atraso...................: ";
        $this->logger->debug($debug . (!empty($this->sHorasAtraso) ? $this->sHorasAtraso : ''));

        $debug = "-- Horas de Saida Antecipada.........: ";
        $this->logger->debug($debug . (!empty($this->sHorasSaidaAntecipada) ? $this->sHorasSaidaAntecipada : ''));

        $this->posCalcularHoras();

        $includedFiles = '';
        if ($this->logger->getVerbosity() == Logger::DEBUG_5) {
            $includedFiles = implode("\n-- ", get_included_files());
            $includedFiles = str_replace(ECIDADE_PATH, '', $includedFiles);
            preg_match_all("/(-- extension\/modification.*)/m", $includedFiles, $aIncludedFiles);
            $includedFiles = implode("\n", $aIncludedFiles[0]);

            $this->logger->debug("----------------------------------------");
            $this->logger->debug("--- FONTES UTILIZADOS PARA O CALCULO ---");
            $this->logger->debug("----------------------------------------");
            $this->logger->debug("\n". $includedFiles);
        }

        $this->logger->debug("--------------------------------");
        $this->logger->debug("--- FIM DO CALCULO DE HORAS ----");
        $this->logger->debug("--------------------------------");
    }

    private function calcularHorasDiaComEvento()
    {
        $oHora = \DateTime::createFromFormat('H:i', '0:00');
        $oHora->setDate(
            $this->getData()->getAno(),
            $this->getData()->getMes(),
            $this->getData()->getDia()
        );

        if (!$this->getEvento()->considerarHorarioTrabalhado()) {
            $this->calcularHorasDiaComEventoComHorasEvento();
        }

        $oHorasExtrasEvento = TipoHora::getHora($this, BaseHora::HORAS_EXTRAS_EVENTO);
        $oHorasExtrasEvento->calcular();

        $this->posCalcularHoras();
    }

    private function calcularHorasDiaComEventoComHorasEvento()
    {

        $marcacoes = $this->getMarcacoes()->getMarcacoes();

        foreach ($marcacoes as $tipo => $marcacao) {
            $horaMarcacao = null;

            switch ($tipo) {
                case MarcacaoPonto::ENTRADA_1:
                    $horaMarcacao = clone $this->evento->getEntradaUm();
                    break;

                case MarcacaoPonto::SAIDA_1:
                    $horaMarcacao = clone $this->evento->getSaidaUm();
                    break;

                case MarcacaoPonto::ENTRADA_2:
                    $horaMarcacao = !is_null($this->evento->getEntradaDois())
                        ? clone $this->evento->getEntradaDois() : null;
                    break;

                case MarcacaoPonto::SAIDA_2:
                    $horaMarcacao = !is_null($this->evento->getSaidaDois())
                        ? clone $this->evento->getSaidaDois() : null;
                    break;
            }

            $marcacao->limparHoraMarcacao();
            if (!empty($horaMarcacao)) {
                $marcacao->setMarcacao($horaMarcacao);
            }
        }
    }

  /**
   * Métodos a serem executados após o cálculo de horas
   */
    private function posCalcularHoras()
    {
        if ($this->evento) {
            $this->sHorasFalta = '00:00';
        }

        $escalaTrabalho = $this->getServidor()->getEscala($this->getData())->getEscalaTrabalho();
        $horasTrabalho = $this->sHorasTrabalho;

        if ($this->getFeriado() || $this->getJornada()->isFolga() || $this->getJornada()->isDSR()) {
            $this->sHorasTrabalho = '';

            if (!$this->getFeriado() && ($this->getJornada()->isFolga() || $this->getJornada()->isDSR())) {
                $this->sHorasFalta = '';
            }

            if ($this->getFeriado() && !$escalaTrabalho->isRevezamento()) {
                $this->sHorasFalta = '';
                $this->sHorasAtraso = '';
                $this->sHorasSaidaAntecipada = '';
            }

            if ($this->getFeriado() && $escalaTrabalho->isRevezamento()
                && !$escalaTrabalho->isExtraAutomaticaFeriado()) {
                $this->sHorasTrabalho = $horasTrabalho;
            }
        }

        $this->sobrescreverHorasExtras();
        $this->executaDiaGari();
    }

  /**
   * @param \Assentamento|null $assentamento
   */
    public function setAfastamento(\Assentamento $assentamento = null)
    {

        $this->afastamento = $assentamento;

        $this->lAfastado = false;
        if (!empty($assentamento)) {
            $this->lAfastado = true;
        }
    }

  /**
   * @return \Assentamento
   */
    public function getAfastamento()
    {
        return $this->afastamento;
    }

  /**
   * @param Evento $evento
   */
    public function setEvento(Evento $evento)
    {
        $this->evento = $evento;
    }

  /**
   * @return Evento
   */
    public function getEvento()
    {
        return $this->evento;
    }

  /**
   * Sobrescreve as horas extras existentes pelas horas cadastradas no assentamento do tipo HE Manual
   * @return bool
   */
    public function sobrescreverHorasExtras()
    {

        if (count($this->assentamentosHoraExtraManual) === 0) {
            return false;
        }

        foreach ($this->assentamentosHoraExtraManual as $assentamento) {
            $horasManuais = $assentamento->getHorasExtras();

            foreach ($horasManuais as $tipoHora => $horasExtras) {
                if (trim($horasExtras) == '') {
                    continue;
                }

                switch ($tipoHora) {
                    case BaseHora::HORAS_EXTRA50:
                        $this->sHorasExtra50 = $horasExtras;
                        break;

                    case BaseHora::HORAS_EXTRA75:
                        $this->sHorasExtra75 = $horasExtras;
                        break;

                    case BaseHora::HORAS_EXTRA100:
                        $this->sHorasExtra100 = $horasExtras;
                        break;

                    case BaseHora::HORAS_EXTRA50_NOTURNA:
                        $this->sHorasExtra50Noturna = $horasExtras;
                        break;

                    case BaseHora::HORAS_EXTRA75_NOTURNA:
                        $this->sHorasExtra75Noturna = $horasExtras;
                        break;

                    case BaseHora::HORAS_EXTRA100_NOTURNA:
                        $this->sHorasExtra100Noturna = $horasExtras;
                        break;
                }
            }
        }

        return true;
    }

    public function getAssentamentosAbonofalta()
    {
        return $this->oAssentamentosAbonofalta;
    }

    public function setAssentamentosAbonofalta(array $oAssentamentosAbonofalta)
    {
        $this->oAssentamentosAbonofalta = $oAssentamentosAbonofalta;
    }

  /**
   * Ajusta as Horas trabalhadas com as horas noturnas
   */
    public function ajustarHorasTrabalhoNoturna($horasNoturnas)
    {

        if ($horasNoturnas->getHorasCalculadasSemProporcao() == null) {
            return;
        }

        $horasTrabalho = explode(":", $this->getHorasTrabalho());

        $horasNoturnasComPropoporcao = explode(":", $horasNoturnas->getHorasCalculadasComProporcao());
        $horasNoturnasSemPropoporcao = explode(":", $horasNoturnas->getHorasCalculadasSemProporcao());

        $intervaloHorasNoturnasComProporcao = new
            \DateInterval("PT{$horasNoturnasComPropoporcao[0]}H{$horasNoturnasComPropoporcao[1]}M");
        $intervaloHorasNoturnasSemProporcao = new
            \DateInterval("PT{$horasNoturnasSemPropoporcao[0]}H{$horasNoturnasSemPropoporcao[1]}M");

        $horasTrabalhadas =  \DateTime::CreateFromFormat('H:i', "{$horasTrabalho[0]}:{$horasTrabalho[1]}");
        $horasTrabalhadas->sub($intervaloHorasNoturnasSemProporcao);
        $horasTrabalhadas->add($intervaloHorasNoturnasComProporcao);

        $this->setHorasTrabalho($horasTrabalhadas->format("H:i"));

        return $horasTrabalhadas->format("H:i");
    }

    /**
     * @return string
     */
    public function getHorasTotaisDeTrabalho()
    {
        return $this->sHorasTotaisDeTrabalho;
    }

    /**
     * @param string $sHorasTotaisDeTrabalho
     */
    public function setHorasTotaisDeTrabalho($sHorasTotaisDeTrabalho)
    {
        $this->sHorasTotaisDeTrabalho = $sHorasTotaisDeTrabalho;
    }

    /**
     * @return MarcacoesPontoCollection
     */
    public function getMarcacoesSemAlteracao()
    {
        return $this->marcacoesSemAlteracao;
    }

    /**
     * @return MarcacoesPontoCollection
     */
    public function setMarcacoesSemAlteracao($marcacoesSemAlteracao)
    {
        $this->marcacoesSemAlteracao = $marcacoesSemAlteracao;
        return $this;
    }

    public function getTotalExtrasNoturnas()
    {
        $horaExtrasNoturnas = 0;

        $extra50Noturna = BaseHora::converterIntervaloEmMinutos(BaseHora::converterStringHoraEmDateInterval(
            $this->sHorasExtra50Noturna
        ));
        $extra75Noturna = BaseHora::converterIntervaloEmMinutos(BaseHora::converterStringHoraEmDateInterval(
            $this->sHorasExtra75Noturna
        ));
        $extra100Noturna = BaseHora::converterIntervaloEmMinutos(BaseHora::converterStringHoraEmDateInterval(
            $this->sHorasExtra100Noturna
        ));

        $horaExtrasNoturnas += $extra50Noturna;
        $horaExtrasNoturnas += $extra75Noturna;
        $horaExtrasNoturnas += $extra100Noturna;

        return BaseHora::converterMinutosEmInterval($horaExtrasNoturnas);
    }

    public function adicionarExtrasNoturnasComAdicionalNoturno()
    {
        $horasAdicionalNoturoCalculado = BaseHora::converterStringHoraEmDateTime($this->sHorasAdicionalNoturno);
        $horasExtrasNoturnas           = $this->getTotalExtrasNoturnas();

        $horasAdicionalNoturoCalculado->add($horasExtrasNoturnas);
        $this->sHorasAdicionalNoturno = $horasAdicionalNoturoCalculado->format('H:i');
    }

    /**
     * @return string
     */
    public function getHorasAtraso()
    {
        return $this->sHorasAtraso;
    }

    /**
     * @param string $horasAtraso
     */
    public function setHorasAtraso($horasAtraso)
    {
        $this->sHorasAtraso = $horasAtraso;
    }

    /**
     * @return string
     */
    public function getHorasSaidaAntecipada()
    {
        return $this->sHorasSaidaAntecipada;
    }

    /**
     * @param string $horasSaidaAntecipada
     */
    public function setHorasSaidaAntecipada($horasSaidaAntecipada)
    {
        $this->sHorasSaidaAntecipada = $horasSaidaAntecipada;
    }

    /**
     * @return string
     */
    public function getHorasSaidaAntecipadaNoturna()
    {
        return $this->sHorasSaidaAntecipadaNoturna;
    }

    /**
     * @param $horasSaidaAntecipadaNoturna
     */
    public function setHorasSaidaAntecipadaNoturna($horasSaidaAntecipadaNoturna)
    {
        $this->sHorasSaidaAntecipadaNoturna = $horasSaidaAntecipadaNoturna;
    }

    /**
     * @param string $horasAtrasoNoturno
     */
    public function setHorasAtrasoNoturno($horasAtrasoNoturno)
    {
        $this->sHorasAtrasoNoturno = $horasAtrasoNoturno;
    }

    /**
     * @return string
     */
    public function getHorasAtrasoNoturno()
    {
        return $this->sHorasAtrasoNoturno;
    }

    /**
     * @return string
     */
    public function getHorasFaltaNoturna()
    {
        return $this->sHorasFaltaNoturna;
    }

    /**
     * @param string $horasFaltaNoturna
     */
    public function setHorasFaltaNoturna($horasFaltaNoturna)
    {
        $this->sHorasFaltaNoturna = $horasFaltaNoturna;
    }

    public function setLogVerbosity($verbosity)
    {
        if ($this->logger) {
            $this->logger->setVerbosity($verbosity);
        }
    }

    /**
     * @return string
     */
    public function getHorasExtra50NaoAutorizadas()
    {
        return $this->sHorasExtra50NaoAutorizadas;
    }

    /**
     * @param string $sHorasExtra50NaoAutorizadas
     */
    public function setHorasExtra50NaoAutorizadas($sHorasExtra50NaoAutorizadas)
    {
        $this->sHorasExtra50NaoAutorizadas = $sHorasExtra50NaoAutorizadas;
    }

    /**
     * @return string
     */
    public function getHorasExtra75NaoAutorizadas()
    {
        return $this->sHorasExtra75NaoAutorizadas;
    }

    /**
     * @param string $sHorasExtra75NaoAutorizadas
     */
    public function setHorasExtra75NaoAutorizadas($sHorasExtra75NaoAutorizadas)
    {
        $this->sHorasExtra75NaoAutorizadas = $sHorasExtra75NaoAutorizadas;
    }

    /**
     * @return string
     */
    public function getHorasExtra100NaoAutorizadas()
    {
        return $this->sHorasExtra100NaoAutorizadas;
    }

    /**
     * @param string $sHorasExtra100NaoAutorizadas
     */
    public function setHorasExtra100NaoAutorizadas($sHorasExtra100NaoAutorizadas)
    {
        $this->sHorasExtra100NaoAutorizadas = $sHorasExtra100NaoAutorizadas;
    }

    /**
     * @return string
     */
    public function getHorasExtra50NaoAutorizadasNoturna()
    {
        return $this->sHorasExtra50NaoAutorizadasNoturna;
    }

    /**
     * @param string $sHorasExtra50NaoAutorizadasNoturna
     */
    public function setHorasExtra50NaoAutorizadasNoturna($sHorasExtra50NaoAutorizadasNoturna)
    {
        $this->sHorasExtra50NaoAutorizadasNoturna = $sHorasExtra50NaoAutorizadasNoturna;
    }

    /**
     * @return string
     */
    public function getHorasExtra75NaoAutorizadasNoturna()
    {
        return $this->sHorasExtra75NaoAutorizadasNoturna;
    }

    /**
     * @param string $sHorasExtra75NaoAutorizadasNoturna
     */
    public function setHorasExtra75NaoAutorizadasNoturna($sHorasExtra75NaoAutorizadasNoturna)
    {
        $this->sHorasExtra75NaoAutorizadasNoturna = $sHorasExtra75NaoAutorizadasNoturna;
    }

    /**
     * @return string
     */
    public function getHorasExtra100NaoAutorizadasNoturna()
    {
        return $this->sHorasExtra100NaoAutorizadasNoturna;
    }

    /**
     * @param string $sHorasExtra100NaoAutorizadasNoturna
     */
    public function setHorasExtra100NaoAutorizadasNoturna($sHorasExtra100NaoAutorizadasNoturna)
    {
        $this->sHorasExtra100NaoAutorizadasNoturna = $sHorasExtra100NaoAutorizadasNoturna;
    }

    /**
     * @return string
     */
    public function getHorasExtraNaoAutorizadas()
    {
        return $this->sHorasExtraNaoAutorizadas;
    }

    /**
     * @param string $sHorasExtraNaoAutorizadas
     */
    public function setHorasExtraNaoAutorizadas($sHorasExtraNaoAutorizadas)
    {
        $this->sHorasExtraNaoAutorizadas = $sHorasExtraNaoAutorizadas;
    }

    /**
     * @return string
     */
    public function getHorasExtraNaoAutorizadasNoturna()
    {
        return $this->sHorasExtraNaoAutorizadasNoturna;
    }

    /**
     * @param string $sHorasExtraNaoAutorizadasNoturna
     */
    public function setHorasExtraNaoAutorizadasNoturna($sHorasExtraNaoAutorizadasNoturna)
    {
        $this->sHorasExtraNaoAutorizadasNoturna = $sHorasExtraNaoAutorizadasNoturna;
    }

    /**
     * @param \Assentamento[] $assentamentosJustificativaServidor
     * @return $this
     */
    public function setAssentamentosJustificativaServidor($assentamentosJustificativaServidor)
    {
        $this->assentamentosJustificativaServidor = $assentamentosJustificativaServidor;
        return $this;
    }

    /**
     * @return \Assentamento[]
     */
    public function getAssentamentosJustificativaServidor()
    {
        return $this->assentamentosJustificativaServidor;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->logger;
    }

    /**
     * Metodo implementado para o calculo do dia do Gari da CLIN (Niteroi)
     * Os valores fixos foram retirados de informacao com o cliente
     */
    public function executaDiaGari()
    {
        if ($this->getServidor()->getCodigoRegime() != 1810) {
            return false;
        }

        if ($this->getData()->getMes() . '-' . $this->getData()->getDia() != '05-16') {
            return false;
        }

        if ($this->getHorasTotaisDeTrabalho() == '00:00') {
            return false;
        }
        $horas = $this->getHorasTrabalho();
        if ($this->getHorasExtra50() !== null && $this->getHorasExtra50() != '00:00'
            && $this->getHorasExtra50() != '') {
            $horas = $this->getHorasTotaisDeTrabalho();
            $this->setHorasExtra50('00:00');
        }
        if ($this->getHorasExtra75() !== null && $this->getHorasExtra75() != '00:00'
            && $this->getHorasExtra75() != '') {
            $horas = $this->getHorasTotaisDeTrabalho();
            $this->setHorasExtra75('00:00');
        }
        if ($this->getHorasExtra100() !== null && $this->getHorasExtra100() != '00:00'
            && $this->getHorasExtra100() != '') {
            $horas = $this->getHorasTotaisDeTrabalho();
            $extra = $this->getHorasExtra100('00:00');
            /**
             * Valida se a quantidade de horas trabalhada é inferior a quamtidade de horas extras,
             *  e manter a maior quantidade
             */
            if (strtotime($horas) > strtotime($extra)) {
                $this->setHorasExtra100('00:00');
            } else {
                $horas = $this->getHorasExtra100('00:00');
            }
        }

        $this->setHorasExtra100($horas);

        $this->setHorasTrabalho('00:00');
    }
}
