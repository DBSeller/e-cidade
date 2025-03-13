<?php

namespace ECidade\RecursosHumanos\Pessoal\Model;

use DateTime;
use ECidade\RecursosHumanos\Pessoal\Servidor\Model\Cargo;
use Exception;
use Instituicao;
use InstituicaoRepository;
use cl_rhpescargo;

class ServidorMovimentacao
{
    /**
     * @var Instituicao
     */
    private $instituicao;
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $ano;
    /**
     * @var int
     */
    private $mes;
    /**
     * @var int
     */
    private $matricula;
    /**
     * @var int
     */
    private $regime;
    /**
     * @var string
     */
    private $tipoSalario;
    /**
     * @var string
     */
    private $folha;
    /**
     * @var int
     */
    private $formaPagamento;
    /**
     * @var int
     */
    private $tabelaCalculoPrevidencia;
    /**
     * @var double
     */
    private $horasSemanais;
    /**
     * @var double
     */
    private $horasMensais;
    /**
     * @var string
     */
    private $agentesNocivos;
    /**
     * @var bool
     */
    private $recebeComplementacaoSalarial;
    /**
     * @var int
     */
    private $tipoContrato;
    /**
     * @var int
     */
    private $vinculo;
    /**
     * @var double
     */
    private $salario;
    /**
     * @var int
     */
    private $lotacao;
    /**
     * @var int
     */
    private $funcao;
    /**
     * @var string
     */
    private $tipoAposentadoriaPensao;
    /**
     * @var DateTime
     */
    private $validadePensao;
    /**
     * @var bool
     */
    private $deficienteFisico;
    /**
     * @var bool
     */
    private $portadorMolestia;
    /**
     * @var DateTime
     */
    private $dataLaudoMolestia;
    /**
     * @var int
     */
    private $tipoDeficiencia;
    /**
     * @var bool
     */
    private $permanenciaAbonada;
    /**
     * @var DateTime
     */
    private $dataPermanenciaAbonada;
    /**
     * @var int
     */
    private $diasGozoFerias;
    /**
     * @var int
     */
    private $horasDiarias;
    /**
     * @var string
     */
    private $onus;
    /**
     * @var string
     */
    private $ressarcimento;
    /**
     * @var DateTime
     */
    private $dataCedencia;
    /**
     * @var string
     */
    private $cnpjCedencia;
    /**
     * @var string
     */
    private $cedencia;
    /**
     * @var int
     */
    private $regimeJornadaTrabalho;
    /**
     * @var int
     */
    private $tipoRegime;
    /**
     * @var string
     */
    private $descricaoInstrumento = "";
    /**
     * @var bool
     */
    private $pensaoJudicial = false;
    /**
     * @param array $state
     * @return ServidorMovimentacao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self;

        if (!empty($state['rh02_instit'])) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['rh02_instit']));
        }

        if (!empty($state['rh02_seqpes'])) {
            $self->setSequencial($state['rh02_seqpes']);
        }

        if (!empty($state['rh02_anousu'])) {
            $self->setAno($state['rh02_anousu']);
        }

        if (!empty($state['rh02_mesusu'])) {
            $self->setMes($state['rh02_mesusu']);
        }

        if (!empty($state['rh02_regist'])) {
            $self->setMatricula($state['rh02_regist']);
        }

        if (!empty($state['rh02_codreg'])) {
            $self->setRegime($state['rh02_codreg']);
        }

        if (!empty($state['rh02_tipsal'])) {
            $self->setTipoSalario($state['rh02_tipsal']);
        }

        if (!empty($state['rh02_folha'])) {
            $self->setFolha($state['rh02_folha']);
        }

        if (!empty($state['rh02_fpagto'])) {
            $self->setFormaPagamento($state['rh02_fpagto']);
        }

        if (!empty($state['rh02_tbprev'])) {
            $self->setTabelaCalculoPrevidencia($state['rh02_tbprev']);
        }

        if (!empty($state['rh02_hrsmen'])) {
            $self->setHorasMensais($state['rh02_hrsmen']);
        }

        if (!empty($state['rh02_hrssem'])) {
            $self->setHorasSemanais($state['rh02_hrssem']);
        }

        if (!empty($state['rh02_ocorre'])) {
            $self->setAgentesNocivos($state['rh02_ocorre']);
        }

        if (!empty($state['rh02_equip'])) {
            $self->setRecebeComplementacaoSalarial($state['rh02_equip'] === 't');
        }

        if (!empty($state['rh02_tpcont'])) {
            $self->setTipoContrato($state['rh02_tpcont']);
        }

        if (!empty($state['rh02_vincrais'])) {
            $self->setVinculo($state['rh02_vincrais']);
        }

        if (!empty($state['rh02_salari'])) {
            $self->setSalario($state['rh02_salari']);
        }

        if (!empty($state['rh02_lota'])) {
            $self->setLotacao($state['rh02_lota']);
        }

        if (!empty($state['rh02_funcao'])) {
            $self->setFuncao($state['rh02_funcao']);
        }

        if (!empty($state['rh02_rhtipoapos'])) {
            $self->setTipoAposentadoriaPensao($state['rh02_rhtipoapos']);
        }

        if (!empty($state['rh02_validadepensao'])) {
            $self->setValidadePensao(new DateTime($state['rh02_validadepensao']));
        }

        if (!empty($state['rh02_deficientefisico'])) {
            $self->setDeficienteFisico($state['rh02_deficientefisico'] === 't');
        }

        if (!empty($state['rh02_portadormolestia'])) {
            $self->setPortadorMolestia($state['rh02_portadormolestia'] === 't');
        }

        if (!empty($state['rh02_datalaudomolestia'])) {
            $self->setDataLaudoMolestia(new DateTime($state['rh02_datalaudomolestia']));
        }

        if (!empty($state['rh02_tipodeficiencia'])) {
            $self->setTipoDeficiencia($state['rh02_tipodeficiencia']);
        }

        if (!empty($state['rh02_abonopermanencia'])) {
            $self->setPermanenciaAbonada($state['rh02_abonopermanencia'] === 't');
        }

        if (!empty($state['rh02_dataabonopermanencia'])) {
            $self->setDataPermanenciaAbonada(new DateTime($state['rh02_dataabonopermanencia']));
        }

        if (!empty($state['rh02_diasgozoferias'])) {
            $self->setDiasGozoFerias($state['rh02_diasgozoferias']);
        }

        if (!empty($state['rh02_horasdiarias'])) {
            $self->setHorasDiarias($state['rh02_horasdiarias']);
        }

        if (!empty($state['rh02_onus'])) {
            $self->setOnus($state['rh02_onus']);
        }

        if (!empty($state['rh02_ressarcimento'])) {
            $self->setRessarcimento($state['rh02_ressarcimento']);
        }

        if (!empty($state['rh02_datacedencia'])) {
            $self->setDataCedencia(new DateTime($state['rh02_datacedencia']));
        }

        if (!empty($state['rh02_cnpjcedencia'])) {
            $self->setCnpjCedencia($state['rh02_cnpjcedencia']);
        }

        if (!empty($state['rh02_cedencia'])) {
            $self->setCedencia($state['rh02_cedencia']);
        }

        if (!empty($state['rh02_regimejornadatrabalho'])) {
            $self->setRegimeJornadaTrabalho($state['rh02_regimejornadatrabalho']);
        }

        if (!empty($state['rh52_regime'])) {
            $self->setTipoRegime($state['rh52_regime']);
        }

        if (!empty($state['rh02_descinstrumento'])) {
            $self->setDescricaoInstrumento($state['rh02_descinstrumento']);
        }
        if (!empty($state['rh02_sitpagbeneficio'])) {
            $self->setPensaoJudicial($state['rh02_sitpagbeneficio'] === 't');
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $validadePensao = $this->getValidadePensao() instanceof DateTime
        ? $this->getValidadePensao()->format('d/m/Y')
        : null;

        $dataLaudoMolestia = $this->getDataLaudoMolestia() instanceof DateTime
        ? $this->getDataLaudoMolestia()->format('d/m/Y')
        : null;

        $dataCedencia = $this->getDataCedencia() instanceof DateTime
        ? $this->getDataCedencia()->format('d/m/Y')
        : null;

        $dataPermanenciaAbonada = $this->getDataPermanenciaAbonada() instanceof DateTime
        ? $this->getDataPermanenciaAbonada()->format('d/m/Y')
        : null;

        return array(
        'instituicao' => $this->getInstituicao() instanceof Instituicao ? $this->getInstituicao()->toArray() : null,
        'sequencial' => $this->getSequencial(),
        'ano' => $this->getAno(),
        'mes' => $this->getMes(),
        'matricula' => $this->getMatricula(),
        'regime' => $this->getRegime(),
        'tipoSalario' => $this->getTipoSalario(),
        'folha' => $this->getFolha(),
        'formaPagamento' => $this->getFormaPagamento(),
        'tabelaCalculoPrevidencia' => $this->getTabelaCalculoPrevidencia(),
        'horasSemanais' => $this->getHorasSemanais(),
        'horasMensais' => $this->getHorasMensais(),
        'agentesNocivos' => $this->getAgentesNocivos(),
        'recebeComplementacaoSalarial' => $this->isRecebeComplementacaoSalarial(),
        'tipoContrato' => $this->getTipoContrato(),
        'vinculo' => $this->getVinculo(),
        'salario' => $this->getSalario(),
        'lotacao' => $this->getLotacao(),
        'funcao' => $this->getFuncao(),
        'tipoAposentadoriaPensao' => $this->getTipoAposentadoriaPensao(),
        'validadePensao' => $validadePensao,
        'deficienteFisico' => $this->isDeficienteFisico(),
        'portadorMolestia' => $this->isPortadorMolestia(),
        'dataLaudoMolestia' => $dataLaudoMolestia,
        'tipoDeficiencia' => $this->getTipoDeficiencia(),
        'permanenciaAbonada' => $this->isPermanenciaAbonada(),
        'dataPermanenciaAbonada' => $dataPermanenciaAbonada,
        'diasGozoFerias' => $this->getDiasGozoFerias(),
        'horasDiarias' => $this->getHorasDiarias(),
        'onus' => $this->getOnus(),
        'ressarcimento' => $this->getRessarcimento(),
        'dataCedencia' => $dataCedencia,
        'cnpjCedencia' => $this->getCnpjCedencia(),
        'cedencia' => $this->getCedencia(),
        'regimeJornadaTrabalho' => $this->getRegimeJornadaTrabalho(),
        'descricaoInstrumento' => $this->getDescricaoInstrumento(),
        'pensaoJudicial' => $this->isPensaoJudicial()
        );
    }

    /**
     * Diz se esta movimentação pertence a um regime de rpps
     *
     * @return bool
     */
    public function isRpps()
    {
        /**
         * Velharias do sistema
         */
        return $this->getTabelaCalculoPrevidencia() + 2 > 3;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return (int)$this->mes;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = (int)$mes;
    }

    /**
     * @return int
     */
    public function getMatricula()
    {
        return (int)$this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = (int)$matricula;
    }

    /**
     * @return int
     */
    public function getRegime()
    {
        return (int)$this->regime;
    }

    /**
     * @param int $regime
     */
    public function setRegime($regime)
    {
        $this->regime = (int)$regime;
    }

    /**
     * @return string
     */
    public function getTipoSalario()
    {
        return (string)$this->tipoSalario;
    }

    /**
     * @param string $tipoSalario
     */
    public function setTipoSalario($tipoSalario)
    {
        $this->tipoSalario = (string)$tipoSalario;
    }

    /**
     * @return string
     */
    public function getFolha()
    {
        return (string)$this->folha;
    }

    /**
     * @param string $folha
     */
    public function setFolha($folha)
    {
        $this->folha = (string)$folha;
    }

    /**
     * @return int
     */
    public function getFormaPagamento()
    {
        return (int)$this->formaPagamento;
    }

    /**
     * @param int $formaPagamento
     */
    public function setFormaPagamento($formaPagamento)
    {
        $this->formaPagamento = (int)$formaPagamento;
    }

    /**
     * @return int
     */
    public function getTabelaCalculoPrevidencia()
    {
        return (int)$this->tabelaCalculoPrevidencia;
    }

    /**
     * @param int $tabelaCalculoPrevidencia
     */
    public function setTabelaCalculoPrevidencia($tabelaCalculoPrevidencia)
    {
        $this->tabelaCalculoPrevidencia = (int)$tabelaCalculoPrevidencia;
    }

    /**
     * @return double
     */
    public function getHorasSemanais()
    {
        return (double)$this->horasSemanais;
    }

    /**
     * @param double $horasSemanais
     */
    public function setHorasSemanais($horasSemanais)
    {
        $this->horasSemanais = (double)$horasSemanais;
    }

    /**
     * @return double
     */
    public function getHorasMensais()
    {
        return (double)$this->horasMensais;
    }

    /**
     * @param double $horasMensais
     */
    public function setHorasMensais($horasMensais)
    {
        $this->horasMensais = (double)$horasMensais;
    }

    /**
     * @return string
     */
    public function getAgentesNocivos()
    {
        return (string)$this->agentesNocivos;
    }

    /**
     * @param string $agentesNocivos
     */
    public function setAgentesNocivos($agentesNocivos)
    {
        $this->agentesNocivos = (string)$agentesNocivos;
    }

    /**
     * @return bool
     */
    public function isRecebeComplementacaoSalarial()
    {
        return (bool)$this->recebeComplementacaoSalarial;
    }

    /**
     * @param bool $recebeComplementacaoSalarial
     */
    public function setRecebeComplementacaoSalarial($recebeComplementacaoSalarial)
    {
        $this->recebeComplementacaoSalarial = (bool)$recebeComplementacaoSalarial;
    }

    /**
     * @return int
     */
    public function getTipoContrato()
    {
        return (int)$this->tipoContrato;
    }

    /**
     * @param int $tipoContrato
     */
    public function setTipoContrato($tipoContrato)
    {
        $this->tipoContrato = (int)$tipoContrato;
    }

    /**
     * @return int
     */
    public function getVinculo()
    {
        return (int)$this->vinculo;
    }

    /**
     * @param int $vinculo
     */
    public function setVinculo($vinculo)
    {
        $this->vinculo = (int)$vinculo;
    }

    /**
     * @return double
     */
    public function getSalario()
    {
        return (double)$this->salario;
    }

    /**
     * @param double $salario
     */
    public function setSalario($salario)
    {
        $this->salario = (double)$salario;
    }

    /**
     * @return int
     */
    public function getLotacao()
    {
        return (int)$this->lotacao;
    }

    /**
     * @param int $lotacao
     */
    public function setLotacao($lotacao)
    {
        $this->lotacao = (int)$lotacao;
    }

    /**
     * @return int
     */
    public function getFuncao()
    {
        return (int)$this->funcao;
    }

    /**
     * @param int $funcao
     */
    public function setFuncao($funcao)
    {
        $this->funcao = (int)$funcao;
    }

    public function getCargo()
    {
        $daoCargo = new cl_rhpescargo;
        $sSql = $daoCargo->sql_query_file($this->sequencial, 'rh20_cargo');
        $rsCargo = db_query($sSql);
        $cargo = \db_utils::fieldsMemory($rsCargo, 0)->rh20_cargo;

        return $cargo;
    }

    /**
     * @return string
     */
    public function getTipoAposentadoriaPensao()
    {
        return $this->tipoAposentadoriaPensao;
    }

    /**
     * @param string $tipoAposentadoriaPensao
     */
    public function setTipoAposentadoriaPensao($tipoAposentadoriaPensao)
    {
        $this->tipoAposentadoriaPensao = $tipoAposentadoriaPensao;
    }

    /**
     * @return DateTime
     */
    public function getValidadePensao()
    {
        return $this->validadePensao;
    }

    /**
     * @param DateTime $validadePensao
     */
    public function setValidadePensao(DateTime $validadePensao)
    {
        $this->validadePensao = $validadePensao;
    }

    /**
     * @return bool
     */
    public function isDeficienteFisico()
    {
        return (bool)$this->deficienteFisico;
    }

    /**
     * @param bool $deficienteFisico
     */
    public function setDeficienteFisico($deficienteFisico)
    {
        $this->deficienteFisico = (bool)$deficienteFisico;
    }

    /**
     * @return bool
     */
    public function isPortadorMolestia()
    {
        return (bool)$this->portadorMolestia;
    }

    /**
     * @param bool $portadorMolestia
     */
    public function setPortadorMolestia($portadorMolestia)
    {
        $this->portadorMolestia = (bool)$portadorMolestia;
    }

    /**
     * @return DateTime
     */
    public function getDataLaudoMolestia()
    {
        return $this->dataLaudoMolestia;
    }

    /**
     * @param DateTime $dataLaudoMolestia
     */
    public function setDataLaudoMolestia(DateTime $dataLaudoMolestia)
    {
        $this->dataLaudoMolestia = $dataLaudoMolestia;
    }

    /**
     * @return int
     */
    public function getTipoDeficiencia()
    {
        return (int)$this->tipoDeficiencia;
    }

    /**
     * @param int $tipoDeficiencia
     */
    public function setTipoDeficiencia($tipoDeficiencia)
    {
        $this->tipoDeficiencia = (int)$tipoDeficiencia;
    }

    /**
     * @return bool
     */
    public function isPermanenciaAbonada()
    {
        return (bool)$this->permanenciaAbonada;
    }

    /**
     * @param bool $permanenciaAbonada
     */
    public function setPermanenciaAbonada($permanenciaAbonada)
    {
        $this->permanenciaAbonada = (bool)$permanenciaAbonada;
    }

    /**
     * @param DateTime $datapermanenciaAbonada
     */
    public function setDataPermanenciaAbonada(DateTime $dataPermanenciaAbonada)
    {
        $this->dataPermanenciaAbonada = $dataPermanenciaAbonada;
    }

    /**
     * @return DateTime|null
     */
    public function getDataPermanenciaAbonada()
    {
        return $this->dataPermanenciaAbonada;
    }

    /**
     * @return int
     */
    public function getDiasGozoFerias()
    {
        return (int)$this->diasGozoFerias;
    }

    /**
     * @param int $diasGozoFerias
     */
    public function setDiasGozoFerias($diasGozoFerias)
    {
        $this->diasGozoFerias = (int)$diasGozoFerias;
    }

    /**
     * @return int
     */
    public function getHorasDiarias()
    {
        return (int)$this->horasDiarias;
    }

    /**
     * @param int $horasDiarias
     */
    public function setHorasDiarias($horasDiarias)
    {
        $this->horasDiarias = (int)$horasDiarias;
    }

    /**
     * @return string
     */
    public function getOnus()
    {
        return (string)$this->onus;
    }

    /**
     * @param string $onus
     */
    public function setOnus($onus)
    {
        $this->onus = (string)$onus;
    }

    /**
     * @return string
     */
    public function getRessarcimento()
    {
        return (string)$this->ressarcimento;
    }

    /**
     * @param string $ressarcimento
     */
    public function setRessarcimento($ressarcimento)
    {
        $this->ressarcimento = (string)$ressarcimento;
    }

    /**
     * @return DateTime
     */
    public function getDataCedencia()
    {
        return $this->dataCedencia;
    }

    /**
     * @param DateTime $dataCedencia
     */
    public function setDataCedencia(DateTime $dataCedencia)
    {
        $this->dataCedencia = $dataCedencia;
    }

    /**
     * @return string
     */
    public function getCnpjCedencia()
    {
        return (string)$this->cnpjCedencia;
    }

    /**
     * @param string $cnpjCedencia
     */
    public function setCnpjCedencia($cnpjCedencia)
    {
        $this->cnpjCedencia = (string)$cnpjCedencia;
    }

    /**
     * @return string
     */
    public function getCedencia()
    {
        return (string)$this->cedencia;
    }

    /**
     * @param string $cedencia
     */
    public function setCedencia($cedencia)
    {
        $this->cedencia = (string)$cedencia;
    }

    /**
     * @return int
     */
    public function getRegimeJornadaTrabalho()
    {
        return (int)$this->regimeJornadaTrabalho;
    }

    /**
     * @param int $regimeJornadaTrabalho
     */
    public function setRegimeJornadaTrabalho($regimeJornadaTrabalho)
    {
        $this->regimeJornadaTrabalho = (int)$regimeJornadaTrabalho;
    }

    /**
     * @return int
     */
    public function getTipoRegime()
    {
        return (int)$this->tipoRegime;
    }

    /**
     * @param int $tipoRegime
     */
    public function setTipoRegime($tipoRegime)
    {
        $this->tipoRegime = (int)$tipoRegime;
    }

    public function deParaDinamico()
    {
        $instituicao = db_getsession("DB_instit");
        $sql = "
        SELECT DISTINCT r33_codtab AS r33_codtab_real,
            cast(r33_codtab AS integer)-2 AS r33_codtab,
            r33_nome
        FROM inssirf
        WHERE r33_instit = {$instituicao}
            AND r33_codtab BETWEEN 3 AND 6
            AND r33_mesusu = {$this->getMes()}
            AND r33_anousu = {$this->getAno()}
            ORDER BY r33_codtab;";

        $result = \db_query($sql);
        $totalRegistros = pg_num_rows($result);
        $tabPrev = $this->getTabelaCalculoPrevidencia();

        for ($row = 0; $row < $totalRegistros; $row++) {
            $current = \db_utils::fieldsMemory($result, $row);
            if ($tabPrev == $current->r33_codtab) {
                return $current->r33_codtab_real;
            }
        }
    }

    public function getTipoSegregacao()
    {
        // afim de manter a consistencia da query
        $default = $this->deParaDinamico() == "" ? 'b.rh02_tbprev' : $this->deParaDinamico();

        $sql = "
        select
            a.r33_codtab,
            a.r33_tiposegregacao
        from
            pessoal.inssirf a
            inner join pessoal.rhpessoalmov b on {$default} = a.r33_codtab and
            a.r33_anousu = b.rh02_anousu and
            a.r33_mesusu = b.rh02_mesusu and
            a.r33_anousu = {$this->getAno()} and
            a.r33_instit = b.rh02_instit and
            a.r33_mesusu = {$this->getMes()}
        where b.rh02_regist = {$this->matricula} and a.r33_tiposegregacao is not null limit 1;";

        $result = \db_query($sql);
        $tiposegregacao = \db_utils::fieldsMemory($result, 0);

        return ($tiposegregacao->r33_tiposegregacao);
    }

    public function getIndTetoRGPS($matricula)
    {
        $instituicao = db_getsession("DB_instit");
        $sql = "
        SELECT count(*)
        FROM rhinssoutros  WHERE rh51_seqpes IN (SELECT rh02_seqpes AS rh51_seqpes
        FROM rhpessoal
        INNER JOIN rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
        AND rh02_mesusu = {$this->getMes()}
        AND rh02_anousu = {$this->getAno()}
        AND rh02_instit = {$instituicao}
        INNER JOIN rhregime ON rhpessoalmov.rh02_codreg = rhregime.rh30_codreg
        INNER JOIN cgm ON cgm.z01_numcgm = rhpessoal.rh01_numcgm
        LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
        WHERE rh01_regist = {$matricula}
            AND rh02_anousu = {$this->getAno()}
            AND rh02_mesusu = {$this->getMes()});";

        $result = \db_query($sql);
        if (pg_num_rows($result) == 0) {
            return 'N';
        }
        return 'S';
    }

    public function isPensionista()
    {
        $funcao = new Cargo($this->getFuncao());
        if (strtolower(trim($funcao->getDescricao())) == "pensionista") {
            return true;
        }
        return false;
    }


    /**
     * @param $sequencial
     * @return ServidorMovimentacao|false
     * @throws Exception
     */
    public static function find($sequencial)
    {
        $rhpessoal = new  \cl_rhpessoalmov();
        $sql = $rhpessoal->sql_query($sequencial);
        $rs = $rhpessoal->sql_record($sql);
        $movimentacao = pg_fetch_assoc($rs, 0);

        if (empty($movimentacao)) {
            return false;
        }
        return self::fromState($movimentacao);
    }

    /**
     * return string
     */
    public function getDescricaoInstrumento()
    {
        return $this->descricaoInstrumento;
    }

    /**
     * @param string $descricaoInstrumento
     */
    public function setDescricaoInstrumento($descricaoInstrumento)
    {
        $this->descricaoInstrumento = $descricaoInstrumento;
    }

    /**
     * return bool
     */
    public function isPensaoJudicial()
    {
        return $this->pensaoJudicial;
    }

    /**
     * @param bool $pensaoJudicial
     */
    public function setPensaoJudicial($pensaoJudicial)
    {
        $this->pensaoJudicial = $pensaoJudicial;
    }
}
