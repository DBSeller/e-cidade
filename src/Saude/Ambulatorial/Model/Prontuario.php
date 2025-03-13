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

namespace ECidade\Saude\Ambulatorial\Model;

use Cassandra\Date;
use Cgs;

/**
 * Classe para controle dos dados do Prontuário
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Ambulatorial
 */
class Prontuario
{

    /**
     * Código do prontuário
     * @var integer
     */
    private $codigo;

    /**
     * Ano do prontuário
     * @var integer
     */
    private $ano;

    /**
     * Mes do prontuário
     * @var integer
     */
    private $mes;

    /**
     * Sequencia do prontuário
     * @var integer
     */
    private $sequencia;

    /**
     * Unidade do prontuário
     * @var integer
     */
    private $unidade;

    /**
     * Instância de Cgs
     * @var Cgs
     */
    private $cgs;

    /**
     * Motivo do atendimento
     * @var string
     */
    private $motivo;

    /**
     * Data em que o paciente foi atendimento na recepção
     * @var DBDate|null
     */
    private $data_cadastro = null;

    /**
     * Hora em que o paciente foi atendimento na recepção
     * @var string
     */
    private $hora_cadastro = null;

    /**
     * Código internacional de doença
     * @var integer
     */
    private $cid;

    /**
     * Pressão
     * @var string
     */
    private $pressao;

    /**
     * Peso
     * @var float
     */
    private $peso;

    /**
     * Temperatura
     * @var float
     */
    private $temperatura;

    /**
     * Código do profissional
     * @var integer
     */
    private $profissional;

    /**
     * Diagnostico
     * @var string
     */
    private $diagnostico;

    /**
     * Sistema de informação ambulatorial, sistema de informação hospitalares
     * @var integer
     */
    private $siasih;

    /**
     * @var string
     */
    private $digitada;

    /**
     * @var integer
     */
    private $login;

    /**
     * Código do motivo do atendimento, pois foi criado uma tabela de motivos.
     * @var integer
     */
    private $motivo_atendimento;

    /**
     * Tipo do atendimento.
     * @var integer
     */
    private $tipo;

    /**
     * Código da ação programatica do atendimento - ações do governo para verificação de indicadores.
     * @var integer
     */
    private $acao_programatica;

    /**
     * Setor amgulatorial do atendimento.
     * @var integer
     */
    private $setor_ambulatorial;

    /**
     * Idade gestacional do paciente se sexo feminino.
     * @var integer
     */
    private $idade_gestacional;

    /**
     * data da última menstruação do paciente se sexo feminino.
     * @var DBDate|null
     */
    private $dum;

    /**
     * Controla se Prontuário está finalizado
     * @var boolean
     */
    private $finalizado = false;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_prontuarios_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo da FAA
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set código do prontuário
     *
     * @param  integer  $codigo  Código do prontuário
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get ano do prontuário
     *
     * @return  integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Set ano do prontuário
     *
     * @param  integer  $ano  Ano do prontuário
     *
     * @return  self
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get mes do prontuário
     *
     * @return  integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set mes do prontuário
     *
     * @param  integer  $mes  Mes do prontuário
     *
     * @return  self
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get sequencia do prontuário
     *
     * @return  integer
     */
    public function getSequencia()
    {
        return $this->sequencia;
    }

    /**
     * Set sequencia do prontuário
     *
     * @param  integer  $sequencia  Sequencia do prontuário
     *
     * @return  self
     */
    public function setSequencia($sequencia)
    {
        $this->sequencia = $sequencia;

        return $this;
    }

    /**
     * Get unidade do prontuário
     *
     * @return  integer
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * Set unidade do prontuário
     *
     * @param  integer  $unidade  Unidade do prontuário
     *
     * @return  self
     */
    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * Retorno uma instância de Cgs
     * @return Cgs
     */
    public function getCgs()
    {
        return $this->cgs;
    }

    /**
     * Seta uma instância de Cgs
     * @param Cgs
     */
    public function setCgs(Cgs $cgs)
    {
        $this->cgs = $cgs;
    }

    /**
     * Get motivo do atendimento
     *
     * @return  string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set motivo do atendimento
     *
     * @param  string  $motivo  Motivo do atendimento
     *
     * @return  self
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * Get data em que o paciente foi atendimento na recepção
     *
     * @return  DBDate|null
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set data em que o paciente foi atendimento na recepção
     *
     * @param  DBDate|null  $data_cadastro  Data em que o paciente foi atendimento na recepção
     *
     * @return  self
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;

        return $this;
    }

    /**
     * Get hora em que o paciente foi atendimento na recepção
     *
     * @return  string
     */
    public function getHoraCadastro()
    {
        return $this->hora_cadastro;
    }

    /**
     * Set hora em que o paciente foi atendimento na recepção
     *
     * @param  string  $hora_cadastro  Hora em que o paciente foi atendimento na recepção
     *
     * @return  self
     */
    public function setHoraCadastro($hora_cadastro)
    {
        $this->hora_cadastro = $hora_cadastro;

        return $this;
    }

    /**
     * Get código internacional de doença
     *
     * @return  integer
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set código internacional de doença
     *
     * @param  integer  $cid  Código internacional de doença
     *
     * @return  self
     */
    public function setCid($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    /**
     * Get pressão
     *
     * @return  string
     */
    public function getPressao()
    {
        return $this->pressao;
    }

    /**
     * Set pressão
     *
     * @param  string  $pressao  Pressão
     *
     * @return  self
     */
    public function setPressao($pressao)
    {
        $this->pressao = $pressao;

        return $this;
    }

    /**
     * Get peso
     *
     * @return  float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set peso
     *
     * @param  float  $peso  Peso
     *
     * @return  self
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get temperatura
     *
     * @return  float
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    /**
     * Set temperatura
     *
     * @param  float  $temperatura  Temperatura
     *
     * @return  self
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    /**
     * Get código do profissional
     *
     * @return  integer
     */
    public function getProfissional()
    {
        return $this->profissional;
    }

    /**
     * Set código do profissional
     *
     * @param  integer  $profissional  Código do profissional
     *
     * @return  self
     */
    public function setProfissional($profissional)
    {
        $this->profissional = $profissional;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return  string
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set diagnostico
     *
     * @param  string  $diagnostico  Diagnostico
     *
     * @return  self
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get sistema de informação ambulatorial, sistema de informação hospitalares
     *
     * @return  integer
     */
    public function getSiasih()
    {
        return $this->siasih;
    }

    /**
     * Set sistema de informação ambulatorial, sistema de informação hospitalares
     *
     * @param  integer  $siasih  Sistema de informação ambulatorial, sistema de informação hospitalares
     *
     * @return  self
     */
    public function setSiasih($siasih)
    {
        $this->siasih = $siasih;

        return $this;
    }

    /**
     * Get the value of digitada
     *
     * @return  string
     */
    public function getDigitada()
    {
        return $this->digitada;
    }

    /**
     * Set the value of digitada
     *
     * @param  string  $digitada
     *
     * @return  self
     */
    public function setDigitada($digitada)
    {
        $this->digitada = $digitada;

        return $this;
    }

    /**
     * Get the value of login
     *
     * @return  integer
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @param  integer  $login
     *
     * @return  self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get código do motivo do atendimento, pois foi criado uma tabela de motivos.
     *
     * @return  integer
     */
    public function getMotivoAtendimento()
    {
        return $this->motivo_atendimento;
    }

    /**
     * Set código do motivo do atendimento, pois foi criado uma tabela de motivos.
     *
     * @param  integer  $motivo_atendimento  Código do motivo do atendimento, pois foi criado uma tabela de motivos.
     *
     * @return  self
     */
    public function setMotivoAtendimento($motivo_atendimento)
    {
        $this->motivo_atendimento = $motivo_atendimento;

        return $this;
    }

    /**
     * Get tipo do atendimento.
     *
     * @return  integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tipo do atendimento.
     *
     * @param  integer  $tipo  Tipo do atendimento.
     *
     * @return  self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get código da ação programatica do atendimento - ações do governo para verificação de indicadores.
     *
     * @return  integer
     */
    public function getAcaoProgramatica()
    {
        return $this->acao_programatica;
    }

    /**
     * Set código da ação programatica do atendimento - ações do governo para verificação de indicadores.
     *
     * @param  integer  $acao_programatica  Código da ação programatica do atendimento -
     * ações do governo para verificação de indicadores.
     *
     * @return  self
     */
    public function setAcaoProgramatica($acao_programatica)
    {
        $this->acao_programatica = $acao_programatica;

        return $this;
    }

    /**
     * Get setor amgulatorial do atendimento.
     *
     * @return  integer
     */
    public function getSetorAmbulatorial()
    {
        return $this->setor_ambulatorial;
    }

    /**
     * Set setor amgulatorial do atendimento.
     *
     * @param  integer  $setor_ambulatorial  Setor amgulatorial do atendimento.
     *
     * @return  self
     */
    public function setSetorAmbulatorial($setor_ambulatorial)
    {
        $this->setor_ambulatorial = $setor_ambulatorial;

        return $this;
    }

    /**
     * Get idade gestacional do paciente se sexo feminino.
     *
     * @return  integer
     */
    public function getIdadeGestacional()
    {
        return $this->idade_gestacional;
    }

    /**
     * Set idade gestacional do paciente se sexo feminino.
     *
     * @param  integer  $idade_gestacional  Idade gestacional do paciente se sexo feminino.
     *
     * @return  self
     */
    public function setIdadeGestacional($idade_gestacional)
    {
        $this->idade_gestacional = $idade_gestacional;

        return $this;
    }

    /**
     * Get data da última menstruação do paciente se sexo feminino.
     *
     * @return  DBDate|null
     */
    public function getDum()
    {
        return $this->dum;
    }

    /**
     * Set data da última menstruação do paciente se sexo feminino.
     *
     * @param  DBDate|null  $dum  data da última menstruação do paciente se sexo feminino.
     *
     * @return  self
     */
    public function setDum($dum)
    {
        $this->dum = $dum;

        return $this;
    }

    /**
     * Retorna se o prontuário está finalizado
     * @return boolean
     */
    public function isFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Define se o prontuário está finalizado
     * @param boolean $lFinalizado
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;
    }

    /**
     * @param array $state
     * @return Prontuario
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $prontuario = new self();

        if (array_key_exists('sd24_i_codigo', $state)) {
            $prontuario->setCodigo((int)$state['sd24_i_codigo']);
        }

        if (array_key_exists('sd24_i_ano', $state)) {
            $prontuario->setAno(((int)$state['sd24_i_ano']));
        }

        if (array_key_exists('sd24_i_mes', $state)) {
            $prontuario->setMes((int)$state['sd24_i_mes']);
        }

        if (array_key_exists('sd24_i_seq', $state)) {
            $prontuario->setSequencia((int)$state['sd24_i_seq']);
        }

        if (array_key_exists('sd24_i_unidade', $state)) {
            $prontuario->setUnidade((int)$state['sd24_i_unidade']);
        }

        if (array_key_exists('sd24_i_numcgs', $state)) {
            $prontuario->setCgs(\CgsRepository::getByCodigo((int)$state['sd24_i_numcgs']));
        }

        if (array_key_exists('sd24_v_motivo', $state)) {
            $prontuario->setMotivo($state['sd24_v_motivo']);
        }

        if (array_key_exists('sd24_d_cadastro', $state)) {
            $prontuario->setDataCadastro(new Date($state['sd24_d_cadastro']));
        }

        if (array_key_exists('sd24_c_cadastro', $state)) {
            $prontuario->setHoraCadastro($state['sd24_c_cadastro']);
        }

        if (array_key_exists('sd24_i_cid', $state)) {
            $prontuario->setCid($state['sd24_i_cid']);
        }

        if (array_key_exists('sd24_v_pressao', $state)) {
            $prontuario->setPressao((int)$state['sd24_v_pressao']);
        }

        if (array_key_exists('sd24_f_peso', $state)) {
            $prontuario->setPeso((float)$state['sd24_f_peso']);
        }

        if (array_key_exists('sd24_f_temperatura', $state)) {
            $prontuario->setTemperatura((float)$state['sd24_f_temperatura']);
        }

        if (array_key_exists('sd24_i_profissional', $state)) {
            $prontuario->setProfissional((int)$state['sd24_i_profissional']);
        }

        if (array_key_exists('sd24_t_diagnostico', $state)) {
            $prontuario->setDiagnostico($state['sd24_t_diagnostico']);
        }

        if (array_key_exists('sd24_i_siasih', $state)) {
            $prontuario->setSiasih((int)$state['sd24_i_siasih']);
        }

        if (array_key_exists('sd24_c_digitada', $state)) {
            $prontuario->setDigitada($state['sd24_c_digitada']);
        }

        if (array_key_exists('sd24_i_login', $state)) {
            $prontuario->setLogin((int)$state['sd24_i_login']);
        }

        if (array_key_exists('sd24_i_motivo', $state)) {
            $prontuario->setMotivoAtendimento((int)$state['sd24_i_motivo']);
        }

        if (array_key_exists('sd24_i_tipo', $state)) {
            $prontuario->setTipo((int)$state['sd24_i_tipo']);
        }

        if (array_key_exists('sd24_i_acaoprog', $state)) {
            $prontuario->setAcaoProgramatica((int)$state['sd24_i_acaoprog']);
        }
        
        if (array_key_exists('sd24_setorambulatorial', $state)) {
            $prontuario->setSetorAmbulatorial((int)$state['sd24_setorambulatorial']);
        }
        
        if (array_key_exists('sd24_idadegestacional', $state)) {
            $prontuario->setIdadeGestacional((int)$state['sd24_idadegestacional']);
        }
        
        if (array_key_exists('sd24_dum', $state)) {
            $prontuario->setDum(new Date($state['sd24_dum']));
        }

        return $prontuario;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $cgs = $this->getCgs();

        $retorno = array(
            'sd24_i_codigo' => $this->getCodigo(),
            'sd24_i_ano'  => $this->getAno(),
            'sd24_i_mes' => $this->getMes(),
            'sd24_i_seq' => $this->getMes(),
            'sd24_i_unidade' => $this->getUnidade(),
            'sd24_v_motivo' => $this->getMotivo(),
            'sd24_d_cadastro' => $this->getDataCadastro(),
            'sd24_c_cadastro' => $this->getHoraCadastro(),
            'sd24_i_cid' => $this->getCid(),
            'sd24_v_pressao' => $this->getPressao(),
            'sd24_f_peso' => $this->getPeso(),
            'sd24_f_temperatura' => $this->getTemperatura(),
            'sd24_i_profissional' => $this->getProfissional(),
            'sd24_t_diagnostico' => $this->getDiagnostico(),
            'sd24_i_siasih' => $this->getSiasih(),
            'sd24_c_digitada' => $this->getDigitada(),
            'sd24_i_login' => $this->getLogin(),
            'sd24_i_motivo' => $this->getMotivoAtendimento(),
            'sd24_i_tipo' => $this->getTipo(),
            'sd24_i_acaoprog' => $this->getAcaoProgramatica(),
            'sd24_setorambulatorial' => $this->getSetorAmbulatorial(),
            'sd24_idadegestacional' => $this->getIdadeGestacional(),
            'sd24_dum' => $this->getDum(),
            'cgs' => !is_null($cgs) ? $cgs : null
        );

        return $retorno;
    }
}
