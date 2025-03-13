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
 *  02111-1307, USA.save
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository as ServidorProcessoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Contrato\Model\ContratoJornada;
use ECidade\RecursosHumanos\Pessoal\Model\ContratoEmergencial;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOutrosVinculosRepository;
use Admissao;
use Cedencia;
use DBDate;
use JSON;

class Servidor
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPESSOALPROCESSOJUDICIALESOCIAL
     */
    private $sequencialProcesso;

    /**
     * @var int
     */
    private $matricula;

    /**
     * @var int
     */
    private $codigoCategoria;

    /**
     * @var int
     */
    private $codigoInstituicao;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $nomeServidor;

    /**
     * @var  \DBDate  $dataAdmissao  | null
     */
    private $dataAdmissao;

    /**
     * @var  \DBDate  $dataDemissao  | null
     */
    private $dataDemissao;

    /**
     * @var int
     */
    private $tipoRegimeTrabalhista;

    /**
     * @var string
     */
    private $codigoCBO;

    /**
     * @var int
     */
    private $tipoRegimePrevidenciario;

    /**
     * @var int
     */
    private $tipoContratoTempoParcial;


    /**
     * @var string
     */
    private $tipoContratoTemporario;

    /**
     * @var int
     */
    private $tipoContratoPrazo;

    /**
     * @var \DBDate  $dataTerminoPrazoDeterminado  | null
     */
    private $dataTerminoPrazoDeterminado;

    /**
     * @var string
     */
    private $clausulaAssecuratoria;

    /**
     * @var string
     */
    private $motivoDesligamento;

    /**
     * @var \DBDate  $dataProjetadaAvisoPrevioIndenizado  | null
     */
    private $dataProjetadaAvisoPrevioIndenizado;

    /**
     * @var int
     */
    private $tipoInscricao;

    /**
     * @var string
     */
    private $inscricao;

    /**
     * @var string
     */
    private $objetoDeterminante;

    /**
     * @var array
     */
    private $processoJudicial;

    /**
     * @var array
     */
    private $remuneracao = [];

    /**
     * @var array
     */
    private $deParaRegimeTrabalho = [
        1 => 2,
        2 => 1,
        3 => 2
    ];

    /**
     * @var ServidorFolha
     */
    private $servidorFolha;

    

    /**
     * Get the value of sequencialProcesso referencia a tabela RHPESSOALPROCESSOJUDICIALESOCIAL
     *
     * @return  int
     */
    public function getSequencialProcesso()
    {
        return $this->sequencialProcesso;
    }

    /**
     * Set the value of sequencialProcesso referencia a tabela RHPESSOALPROCESSOJUDICIALESOCIAL
     *
     * @param  int  $sequencialProcesso
     */
    public function setSequencialProcesso($sequencialProcesso)
    {
        $processoRepository = new ProcessoJudicialRepository();
        $processoJudicial = $processoRepository
            ->scopeSequencial($sequencialProcesso)
            ->get();
        $this->setProcessoJudicial($processoJudicial);

        $this->sequencialProcesso = $sequencialProcesso;
    }

    /**
     * Get the value of matricula
     *
     * @return  int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set the value of matricula
     *
     * @param  int  $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
        $this->setServidorFolha(\ServidorRepository::getInstanciaByCodigo($matricula));
        $this->setNomeServidor($this->getServidorFolha()->getCgm()->getNome());
        $this->setCpf($this->getServidorFolha()->getCgm()->getCpf());
        $this->setDataDemissao($this->getServidorFolha()->getDadosRescisao()->rh05_recis);
        $this->setDataAdmissao($this->getServidorFolha()->getDataAdmissao()->getDate());
        $this->setCodigoCBO($this->getServidorFolha()->getDadosCargo()->rh37_cbo);
        $this->setTipoRegimeTrabalhista($this->deParaRegimeTrabalho[$this->getServidorFolha()
            ->getVinculo()
            ->getRegime()
            ->getCodigo()]);
        $this->setTipoRegimePrevidenciario($this->getServidorFolha()->isRgps() ? 1 : 2);
        $servidorProcessoRepository = new ServidorProcessoRepository();
        $this->setRemuneracao($servidorProcessoRepository->getRemuneracaoServidor($this));
        $this->setTipoInscricaoNumero();
        $this->setTipoPrevidencia();
    }

    /**
     * Get the value of codigoCategoria
     *
     * @return  int
     */
    public function getCodigoCategoria()
    {
        return $this->codigoCategoria;
    }

    /**
     * Set the value of codigoCategoria
     *
     * @param  int  $codigoCategoria
     */
    public function setCodigoCategoria($codigoCategoria)
    {
        $this->codigoCategoria = $codigoCategoria;
    }

    /**
     * Get the value of codigoInstituicao
     *
     * @return  int
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * Set the value of codigoInstituicao
     *
     * @param  int  $codigoInstituicao
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    /**
     * @param array $state
     * @return Servidor
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $servidor = new self();

        if (array_key_exists('rh271_sequencial', $state)) {
            $servidor->setSequencial((int)$state['rh271_sequencial']);
        }

        if (array_key_exists('rh271_sequencialprocesso', $state)) {
            $servidor->setSequencialProcesso($state['rh271_sequencialprocesso']);
        }

        if (array_key_exists('rh271_matricula', $state)) {
            $servidor->setMatricula($state['rh271_matricula']);
        }

        if (array_key_exists('rh271_instituicao', $state)) {
            $servidor->setCodigoInstituicao($state['rh271_instituicao']);
        }

        if (array_key_exists('rh271_codcateg', $state)) {
            $servidor->setCodigoCategoria($state['rh271_codcateg']);
        }

        if (array_key_exists('z01_cgccpf', $state)) {
            $servidor->setCpf($state['z01_cgccpf']);
        }

        return $servidor;
    }


    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    private function setTipoPrevidencia()
    {
        $servidorFolha = $this->getServidorFolha();
        if ($servidorFolha->getTabelaPrevidencia() != 0) {
            $this->setTipoRegimePrevidenciario($servidorFolha->isRgps() ? 1 : 2);
        } else {
            $registroCedencia = new Cedencia($servidorFolha->getMatricula());
            if ($registroCedencia->getTipoCedencia() == 'A') {
                $this->setTipoRegimePrevidenciario(2);
            }
        }
    }

    private function setTipoInscricaoNumero()
    {
        $servidorFolha = $this->getServidorFolha();
        if ($servidorFolha->getLocalTrabalhoPrincial()) {
            $this->setTipoInscricao($servidorFolha->getLocalTrabalhoPrincial()->getTipoInscricao());
            $this->setInscricao($servidorFolha->getLocalTrabalhoPrincial()->getInstituicao()->getCNPJ());

            if (empty($this->getTipoInscricao()) || empty($this->getInscricao())) {
                $this->setTipoInscricao(1);
                $this->setInscricao($servidorFolha->getLocalTrabalhoPrincial()->getInstituicao()->getCNPJ());
            }
        }
    }

    /**
     * Get the value of sequencial
     *
     * @return  int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  int  $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * Get the value of cpf
     *
     * @return  string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * Get the value of nomeServidor
     *
     * @return  string
     */
    public function getNomeServidor()
    {
        return $this->nomeServidor;
    }

    /**
     * Set the value of nomeServidor
     *
     * @param  string  $nomeServidor
     */
    public function setNomeServidor($nomeServidor)
    {
        $this->nomeServidor = $nomeServidor;
    }

    /**
     * Get the value of tipoRegimeTrabalhista
     *
     * @return  int
     */
    public function getTipoRegimeTrabalhista()
    {
        return $this->tipoRegimeTrabalhista;
    }

    /**
     * Set the value of tipoRegimeTrabalhista
     *
     * @param  int  $tipoRegimeTrabalhista
     */
    public function setTipoRegimeTrabalhista($tipoRegimeTrabalhista)
    {
        $this->tipoRegimeTrabalhista = $tipoRegimeTrabalhista;
    }

    /**
     * Get the value of processoJudicial
     *
     * @return  array
     */
    public function getProcessoJudicial()
    {
        return $this->processoJudicial;
    }

    /**
     * Set the value of processoJudicial
     *
     * @param  array  $processoJudicial
     */
    public function setProcessoJudicial($processoJudicial)
    {
        $this->processoJudicial = $processoJudicial;
    }

    /**
     * Get the value of dataDemissao
     *
     * @return  string
     */
    public function getDataDemissao()
    {
        return $this->dataDemissao;
    }

    /**
     * Set the value of dataDemissao
     *
     * @param  string  $dataDemissao
     */
    public function setDataDemissao($dataDemissao)
    {
        $this->dataDemissao = $dataDemissao;
    }

    /**
     * Get $dataAdmissao | null
     *
     * @return  \DBDate
     */
    public function getDataAdmissao()
    {
        return $this->dataAdmissao;
    }

    /**
     * Set $dataAdmissao | null
     *
     * @param  string $dataAdmissao  $dataAdmissao | null
     */
    public function setDataAdmissao($dataAdmissao)
    {
        $this->dataAdmissao = $dataAdmissao;
    }

    /**
     * Get the value of remuneracao
     *
     * @return  array
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Set the value of remuneracao
     *
     * @param  array  $remuneracao
     *
     * @return  self
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
    }

    /**
     * Get the value of codigoCBO
     *
     * @return  string
     */
    public function getCodigoCBO()
    {
        return $this->codigoCBO;
    }

    /**
     * Set the value of codigoCBO
     *
     * @param  string  $codigoCBO
     *
     * @return  self
     */
    public function setCodigoCBO($codigoCBO)
    {
        $this->codigoCBO = $codigoCBO;
    }


    /**
     * Get the value of tipoRegimePrevidenciario
     *
     * @return  int
     */
    public function getTipoRegimePrevidenciario()
    {
        return $this->tipoRegimePrevidenciario;
    }

    /**
     * Set the value of tipoRegimePrevidenciario
     *
     * @param  int  $tipoRegimePrevidenciario
     *
     * @return  self
     */
    public function setTipoRegimePrevidenciario($tipoRegimePrevidenciario)
    {
        $this->tipoRegimePrevidenciario = $tipoRegimePrevidenciario;
    }


    /**
     * Get the value of tipoContratoTempoParcial
     *
     * @return  int
     */
    public function getTipoContratoTempoParcial()
    {
        return $this->tipoContratoTempoParcial;
    }

    /**
     * Set the value of tipoContratoTempoParcial
     *
     * @param  int  $tipoContratoTempoParcial
     */
    public function setTipoContratoTempoParcial($tipoContratoTempoParcial)
    {
        $this->tipoContratoTempoParcial = $tipoContratoTempoParcial;
    }

    /**
     * Get the value of servidorFolha
     *
     * @return  ServidorFolha
     */
    public function getServidorFolha()
    {
        return $this->servidorFolha;
    }

    /**
     * Set the value of servidorFolha
     *
     * @param  ServidorFolha  $servidorFolha

     */
    public function setServidorFolha($servidorFolha)
    {
        $this->servidorFolha = $servidorFolha;
    }

    /**
     * Get the value of tipoContratoTemporario
     *
     * @return  string
     */
    public function getTipoContratoTemporario()
    {
        return $this->tipoContratoTemporario;
    }

    /**
     * Set the value of tipoContratoTemporario
     *
     * @param  string  $tipoContratoTemporario
     *
     * @return  self
     */
    public function setTipoContratoTemporario($tipoContratoTemporario)
    {
        $this->tipoContratoTemporario = $tipoContratoTemporario;
    }

    /**
     * Get the value of tipoContratoPrazo
     *
     * @return  string
     */
    public function getTipoContratoPrazo()
    {
        return $this->tipoContratoPrazo;
    }

    /**
     * Set the value of tipoContratoPrazo
     *
     * @param  int  $tipoContratoPrazo
     *
     * @return  self
     */
    public function setTipoContratoPrazo($tipoContratoPrazo)
    {
        $this->tipoContratoPrazo = $tipoContratoPrazo;
        return $this;
    }

    /**
     * Get $dataTerminoPrazoDeterminado | null
     *
     * @return  \DBDate
     */
    public function getDataTerminoPrazoDeterminado()
    {
        return $this->dataTerminoPrazoDeterminado;
    }

    /**
     * Set $dataTerminoPrazoDeterminado | null
     *
     * @param  \DBDate  $dataTerminoPrazoDeterminado  $dataTerminoPrazoDeterminado | null
     *
     * @return  self
     */
    public function setDataTerminoPrazoDeterminado($dataTerminoPrazoDeterminado)
    {
        $this->dataTerminoPrazoDeterminado = $dataTerminoPrazoDeterminado;
    }

    /**
     * Get the value of clausulaAssecuratoria
     *
     * @return  string
     */
    public function getClausulaAssecuratoria()
    {
        return $this->clausulaAssecuratoria;
    }

    /**
     * Set the value of clausulaAssecuratoria
     *
     * @param  string  $clausulaAssecuratoria
     */
    public function setClausulaAssecuratoria($clausulaAssecuratoria)
    {
        $this->clausulaAssecuratoria = $clausulaAssecuratoria;
    }

    /**
     * Get the value of motivoDesligamento
     *
     * @return  string
     */
    public function getMotivoDesligamento()
    {
        return $this->motivoDesligamento;
    }

    /**
     * Set the value of motivoDesligamento
     *
     * @param  string  $motivoDesligamento
     *
     * @return  self
     */
    public function setMotivoDesligamento($motivoDesligamento)
    {
        $this->motivoDesligamento = $motivoDesligamento;
    }

    /**
     * Get $dataProjetadaAvisoPrevioIndenizado | null
     *
     * @return  \DBDate
     */
    public function getDataProjetadaAvisoPrevioIndenizado()
    {
        return $this->dataProjetadaAvisoPrevioIndenizado;
    }

    /**
     * Set $dataProjetadaAvisoPrevioIndenizado | null
     *
     * @param  \DBDate  $dataProjetadaAvisoPrevioIndenizado  $dataProjetadaAvisoPrevioIndenizado | null
     */
    public function setDataProjetadaAvisoPrevioIndenizado($dataProjetadaAvisoPrevioIndenizado)
    {
        $this->dataProjetadaAvisoPrevioIndenizado = $dataProjetadaAvisoPrevioIndenizado;
    }

    /**
     * Get the value of tipoInscricao
     *
     * @return  string
     */
    public function getTipoInscricao()
    {
        return $this->tipoInscricao;
    }

    /**
     * Set the value of tipoInscricao
     *
     * @param  int  $tipoInscricao
     */
    public function setTipoInscricao($tipoInscricao)
    {
        $this->tipoInscricao = $tipoInscricao;
    }

    /**
     * Get the value of inscricao
     *
     * @return  string
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * Set the value of inscricao
     *
     * @param  string  $inscricao
     *
     * @return  self
     */
    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * Get the value of objetoDeterminante
     *
     * @return  string
     */
    public function getObjetoDeterminante()
    {
        return $this->objetoDeterminante;
    }

    /**
     * Set the value of objetoDeterminante
     *
     * @param  string  $objetoDeterminante
     */
    public function setObjetoDeterminante($objetoDeterminante)
    {
        $this->objetoDeterminante = $objetoDeterminante;
    }
}
