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
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use DBDate;
use JSON;

class Contrato
{
    /**
     * @var int
     */
    private $tipoContrato;

    /**
     * @var string
     */
    private $indicativoContrato;

    /**
     * @var \DBDate | null
     */
    private $dataAdmissaoOrigem;

    /**
     * @var string
     */
    private $indicativoReintegracao;

    /**
     * @var string
     */
    private $indicativoCategoria;

    /**
     * @var string
     */
    private $indicativoNaturezaAtividade;

    /**
     * @var string
     */
    private $indicativoMotivoDesligamento;

    /**
     * @var string
     */
    private $codigoCBO;

    /**
     * @var string
     */
    private $naturezaAtividade;

    /**
     * @var string
     */
    private $competenciaInicial;

    /**
     * @var string
     */
    private $competenciaFinal;

    /**
     * @var string
     */
    private $indicativoRepercussao;

    /**
     * @var string
     */
    private $matricula;

    /**
     * @var integer
     */
    private $codigoCatergoria;

    /**
     * @var \DBDate | null
     */
    private $dataInicioTSVE;

    /**
     * @var int
     */
    private $sequencialProcessoServidor;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var string
     */
    private $nomeServidor;

    /**
     * @var \DBDate | null
     */
    private $dataSentenca;

    /**
     * @var \DBDate | null
     */
    private $dataAcordo;

    /**
     * @var integer
     */
    private $codigoCategoria;

    /**
     * @var string
     */
    private $indicativoIndenizacaoSD;

    /**
     * @var string
     */
    private $indenizacaoAbono;

    /**
     * @var array
     */
    private $servidorProcesso;

    /**
     * @var array
     */
    private $processoJudicial;

    /**
     * @var array
     */
    private $mudancaCategoriaAtividade;

    /**
     * @var array
     */
    private $unicidadeContratual;

    /**
     * @param array $state
     * @return Contrato
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $contrato = new self();

        if (array_key_exists('rh273_sequencial', $state)) {
            $contrato->setSequencial((int)$state['rh273_sequencial']);
        }

        if (array_key_exists('rh273_sequencialprocessoservidor', $state)) {
            $contrato->setSequencialProcessoServidor((int)$state['rh273_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh273_tpcontr', $state)) {
            $contrato->setTipoContrato($state['rh273_tpcontr']);
        }

        if (array_key_exists('rh273_indcontr', $state)) {
            $contrato->setIndicativoContrato($state['rh273_indcontr']);
        }

        if (array_key_exists('rh273_dtadmorig', $state)) {
            $contrato->setDataAdmissaoOrigem($state['rh273_dtadmorig']);
        }

        if (array_key_exists('rh273_indreint', $state)) {
            $contrato->setIndicativoReintegracao($state['rh273_indreint']);
        }

        if (array_key_exists('rh273_indcateg', $state)) {
            $contrato->setIndicativoCategoria($state['rh273_indcateg']);
        }

        if (array_key_exists('rh273_indnatativ', $state)) {
            $contrato->setIndicativoNaturezaAtividade($state['rh273_indnatativ']);
        }

        if (array_key_exists('rh273_indmotdeslig', $state)) {
            $contrato->setIndicativoMotivoDesligamento($state['rh273_indmotdeslig']);
        }

        if (array_key_exists('rh273_dinicio', $state)) {
            $contrato->setDataInicioTSVE($state['rh273_dinicio']);
        }

        if (array_key_exists('rh273_codcbo', $state)) {
            $contrato->setCodigoCBO($state['rh273_codcbo']);
        }

        if (array_key_exists('rh273_natatividade', $state)) {
            $contrato->setNaturezaAtividade($state['rh273_natatividade']);
        }

        if (array_key_exists('rh273_compini', $state)) {
            $contrato->setCompetenciaInicial($state['rh273_compini']);
        }

        if (array_key_exists('rh273_compfim', $state)) {
            $contrato->setCompetenciaFinal($state['rh273_compfim']);
        }

        if (array_key_exists('rh273_indreperc', $state)) {
            $contrato->setIndicativoRepercussao($state['rh273_indreperc']);
        }

        if (array_key_exists('rh273_indensd', $state)) {
            $contrato->setIndicativoIndenizacaoSD($state['rh273_indensd']);
        }

        if (array_key_exists('rh273_indenabono', $state)) {
            $contrato->setIndenizacaoAbono($state['rh273_indenabono']);
        }

        return $contrato;
    }


    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of tipoContrato
     *
     * @return  int
     */
    public function getTipoContrato()
    {
        return $this->tipoContrato;
    }

    /**
     * Set the value of tipoContrato
     *
     * @param  int  $tipoContrato
     */
    public function setTipoContrato($tipoContrato)
    {
        $this->tipoContrato = $tipoContrato;
    }

    /**
     * Get the value of indicativoContrato
     *
     * @return  string
     */
    public function getIndicativoContrato()
    {
        return $this->indicativoContrato;
    }

    /**
     * Set the value of indicativoContrato
     *
     * @param  string  $indicativoContrato
     */
    public function setIndicativoContrato($indicativoContrato)
    {
        $this->indicativoContrato = $indicativoContrato;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataAdmissaoOrigem()
    {
        return $this->dataAdmissaoOrigem;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataAdmissaoOrigem  | null
     */
    public function setDataAdmissaoOrigem($dataAdmissaoOrigem)
    {
        $this->dataAdmissaoOrigem = $dataAdmissaoOrigem;
    }

    /**
     * Get the value of indicativoReintegracao
     *
     * @return  string
     */
    public function getIndicativoReintegracao()
    {
        return $this->indicativoReintegracao;
    }

    /**
     * Set the value of indicativoReintegracao
     *
     * @param  string  $indicativoReintegracao
     */
    public function setIndicativoReintegracao($indicativoReintegracao)
    {
        $this->indicativoReintegracao = $indicativoReintegracao;
    }

    /**
     * Get the value of indicativoCategoria
     *
     * @return  string
     */
    public function getIndicativoCategoria()
    {
        return $this->indicativoCategoria;
    }

    /**
     * Set the value of indicativoCategoria
     *
     * @param  string  $indicativoCategoria
     */
    public function setIndicativoCategoria($indicativoCategoria)
    {
        $this->indicativoCategoria = $indicativoCategoria;
    }

    /**
     * Get the value of indicativoNaturezaAtividade
     *
     * @return  string
     */
    public function getIndicativoNaturezaAtividade()
    {
        return $this->indicativoNaturezaAtividade;
    }

    /**
     * Set the value of indicativoNaturezaAtividade
     *
     * @param  string  $indicativoNaturezaAtividade
     */
    public function setIndicativoNaturezaAtividade($indicativoNaturezaAtividade)
    {
        $this->indicativoNaturezaAtividade = $indicativoNaturezaAtividade;
    }

    /**
     * Get the value of indicativoMotivoDesligamento
     *
     * @return  string
     */
    public function getIndicativoMotivoDesligamento()
    {
        return $this->indicativoMotivoDesligamento;
    }

    /**
     * Set the value of indicativoMotivoDesligamento
     *
     * @param  string  $indicativoMotivoDesligamento
     */
    public function setIndicativoMotivoDesligamento($indicativoMotivoDesligamento)
    {
        $this->indicativoMotivoDesligamento = $indicativoMotivoDesligamento;
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
     */
    public function setCodigoCBO($codigoCBO)
    {
        $this->codigoCBO = $codigoCBO;
    }

    /**
     * Get the value of naturezaAtividade
     *
     * @return  string
     */
    public function getNaturezaAtividade()
    {
        return $this->naturezaAtividade;
    }

    /**
     * Set the value of naturezaAtividade
     *
     * @param  string  $naturezaAtividade
     */
    public function setNaturezaAtividade($naturezaAtividade)
    {
        $this->naturezaAtividade = $naturezaAtividade;
    }

    /**
     * Get the value of competenciaInicial
     *
     * @return  string
     */
    public function getCompetenciaInicial()
    {
        return $this->competenciaInicial;
    }

    /**
     * Set the value of competenciaInicial
     *
     * @param  string  $competenciaInicial
     */
    public function setCompetenciaInicial($competenciaInicial)
    {
        $this->competenciaInicial = $competenciaInicial;
    }

    /**
     * Get the value of competenciaFinal
     *
     * @return  string
     */
    public function getCompetenciaFinal()
    {
        return $this->competenciaFinal;
    }

    /**
     * Set the value of competenciaFinal
     *
     * @param  string  $competenciaFinal
     */
    public function setCompetenciaFinal($competenciaFinal)
    {
        $this->competenciaFinal = $competenciaFinal;
    }

    /**
     * Get the value of matricula
     *
     * @return  string
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set the value of matricula
     *
     * @param  string  $matricula
     */
    public function setMatricula(string $matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * Get the value of codigoCatergoria
     *
     * @return  integer
     */
    public function getCodigoCatergoria()
    {
        return $this->codigoCatergoria;
    }

    /**
     * Set the value of codigoCatergoria
     *
     * @param  integer  $codigoCatergoria
     */
    public function setCodigoCatergoria($codigoCatergoria)
    {
        $this->codigoCatergoria = $codigoCatergoria;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataInicioTSVE()
    {
        return $this->dataInicioTSVE;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataInicioTSVE  | null
     */
    public function setDataInicioTSVE($dataInicioTSVE)
    {
        $this->dataInicioTSVE = $dataInicioTSVE;
    }

    /**
     * Get the value of sequencialProcessoServidor
     *
     * @return  int
     */
    public function getSequencialProcessoServidor()
    {
        return $this->sequencialProcessoServidor;
    }

    /**
     * Set the value of sequencialProcessoServidor
     *
     * @param  int  $sequencialProcessoServidor
     */
    public function setSequencialProcessoServidor($sequencialProcessoServidor)
    {
        $processoServidorRepository = new ServidorRepository();
        $processoServidor = $processoServidorRepository
            ->scopeSequencial($sequencialProcessoServidor)
            ->get();
        $this->setServidorProcesso($processoServidor);

        $processoJudicialRepository = new ProcessoJudicialRepository;
        $processoJudicial = $processoJudicialRepository
            ->scopeSequencial($processoServidor[0]->getSequencialProcesso())
            ->get();
        $this->setProcessoJudicial($processoJudicial);

        $this->sequencialProcessoServidor = $sequencialProcessoServidor;
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
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataSentenca()
    {
        return $this->dataSentenca;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataSentenca  | null
     */
    public function setDataSentenca($dataSentenca)
    {
        $this->dataSentenca = $dataSentenca;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataAcordo()
    {
        return $this->dataAcordo;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataAcordo  | null
     */
    public function setDataAcordo($dataAcordo)
    {
        $this->dataAcordo = $dataAcordo;
    }

    /**
     * Get the value of codigoCategoria
     *
     * @return  integer
     */
    public function getCodigoCategoria()
    {
        return $this->codigoCategoria;
    }

    /**
     * Set the value of codigoCategoria
     *
     * @param  integer  $codigoCategoria
     */
    public function setCodigoCategoria($codigoCategoria)
    {
        $this->codigoCategoria = $codigoCategoria;
    }

    /**
     * Get the value of indicativoIndenizacaoSD
     *
     * @return  string
     */
    public function getIndicativoIndenizacaoSD()
    {
        return $this->indicativoIndenizacaoSD;
    }

    /**
     * Set the value of indicativoIndenizacaoSD
     *
     * @param  string  $indicativoIndenizacaoSD
     */
    public function setIndicativoIndenizacaoSD($indicativoIndenizacaoSD)
    {
        $this->indicativoIndenizacaoSD = $indicativoIndenizacaoSD;
    }

    /**
     * Get the value of indenizacaoAbono
     *
     * @return  string
     */
    public function getIndenizacaoAbono()
    {
        return $this->indenizacaoAbono;
    }

    /**
     * Set the value of indenizacaoAbono
     *
     * @param  string  $indenizacaoAbono
     */
    public function setIndenizacaoAbono($indenizacaoAbono)
    {
        $this->indenizacaoAbono = $indenizacaoAbono;
    }

    /**
     * Get the value of servidorProcesso
     *
     * @return  array
     */
    public function getServidorProcesso()
    {
        return $this->servidorProcesso;
    }

    /**
     * Set the value of servidorProcesso
     *
     * @param  array  $servidorProcesso
     */
    public function setServidorProcesso($servidorProcesso)
    {
        $this->servidorProcesso = $servidorProcesso;
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
     * Get the value of indicativoRepercussao
     *
     * @return  string
     */
    public function getIndicativoRepercussao()
    {
        return $this->indicativoRepercussao;
    }

    /**
     * Set the value of indicativoRepercussao
     *
     * @param  string  $indicativoRepercussao
     */
    public function setIndicativoRepercussao($indicativoRepercussao)
    {
        $this->indicativoRepercussao = $indicativoRepercussao;
    }

    /**
     * Get the value of mudancaCategoriaAtividade
     *
     * @return  array
     */
    public function getMudancaCategoriaAtividade()
    {
        return $this->mudancaCategoriaAtividade;
    }

    /**
     * Set the value of mudancaCategoriaAtividade
     *
     * @param  array  $mudancaCategoriaAtividade
     */
    public function setMudancaCategoriaAtividade($mudancaCategoriaAtividade)
    {
        $this->mudancaCategoriaAtividade = $mudancaCategoriaAtividade;
    }

    /**
     * Get the value of unicidadeContratual
     *
     * @return  array
     */
    public function getUnicidadeContratual()
    {
        return $this->unicidadeContratual;
    }

    /**
     * Set the value of unicidadeContratual
     *
     * @param  array  $unicidadeContratual
     */
    public function setUnicidadeContratual($unicidadeContratual)
    {
        $this->unicidadeContratual = $unicidadeContratual;
    }
}
