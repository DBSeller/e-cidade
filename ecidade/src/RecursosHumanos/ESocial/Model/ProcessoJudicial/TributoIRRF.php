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

use DBDate;
use JSON;

class TributoIRRF
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPESSOALPROCESSOSERVIDOR
     */
    private $sequencialProcessoServidor;

    /**
     * @var int
     *
     */
    private $codigoReceita;

    /**
     * @var numeric
     *
     */
    private $valorIRRF;

    /**
     * @var string
     *
     */
    private $periodoPagamento;

    /**
     * @var numeric
     *
     */
    private $valorRendimentoTributavel;

    /**
     * @var numeric
     *
     */
    private $valorRendimentoTributavel13;

    /**
     * @var numeric
     *
     */
    private $valorRendimentoMolestia;

    /**
     * @var numeric
     *
     */
    private $valorIsenta65;

    /**
     * @var numeric
     *
     */
    private $valorJurosMora;

    /**
     * @var numeric
     *
     */
    private $valorRendimentoIsento;

    /**
     * @var string
     *
     */
    private $descricaoIsento;

    /**
     * @var numeric
     *
     */
    private $valorPrevidenciaOficial;

    /**
     * @var string
     *
     */
    private $descricaoRendimentoAcumula;

    /**
     * @var int
     *
     */
    private $quantidadeMesAcumula;

    /**
     * @var numeric
     *
     */
    private $valorDespesaCusta;

    /**
     * @var numeric
     *
     */
    private $valorDespesaAdvogados;

    /**
     * @param array $state
     * @return TributoIRRF
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $tributoIRRF = new self();

        if (array_key_exists('rh299_sequencial', $state)) {
            $tributoIRRF->setSequencial((int)$state['rh299_sequencial']);
        }

        if (array_key_exists('rh299_sequencialprocessoservidor', $state)) {
            $tributoIRRF->setSequencialProcessoServidor((int)$state['rh299_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh299_tpcr', $state)) {
            $tributoIRRF->setCodigoReceita($state['rh299_tpcr']);
        }

        if (array_key_exists('rh299_vcr', $state)) {
            $tributoIRRF->setValorIRRF($state['rh299_vcr']);
        }

        if (array_key_exists('rh299_pagamento', $state)) {
            $tributoIRRF->setPeriodoPagamento($state['rh299_pagamento']);
        }

        if (array_key_exists('rh299_vrrendtrib', $state)) {
            $tributoIRRF->setValorRendimentoTributavel($state['rh299_vrrendtrib']);
        }

        if (array_key_exists('rh299_vrrendtrib13', $state)) {
            $tributoIRRF->setValorRendimentoTributavel13($state['rh299_vrrendtrib13']);
        }

        if (array_key_exists('rh299_vrrendmolegrave', $state)) {
            $tributoIRRF->setValorRendimentoMolestia($state['rh299_vrrendmolegrave']);
        }

        if (array_key_exists('rh299_vrrendisen65', $state)) {
            $tributoIRRF->setValorIsenta65($state['rh299_vrrendisen65']);
        }

        if (array_key_exists('rh299_vrjurosmora', $state)) {
            $tributoIRRF->setValorJurosMora($state['rh299_vrjurosmora']);
        }

        if (array_key_exists('rh299_vrrendisenntrib', $state)) {
            $tributoIRRF->setValorRendimentoIsento($state['rh299_vrrendisenntrib']);
        }

        if (array_key_exists('rh299_descisenntrib', $state)) {
            $tributoIRRF->setDescricaoIsento($state['rh299_descisenntrib']);
        }

        if (array_key_exists('rh299_vrprevoficial', $state)) {
            $tributoIRRF->setValorPrevidenciaOficial($state['rh299_vrprevoficial']);
        }

        if (array_key_exists('rh299_descrra', $state)) {
            $tributoIRRF->setDescricaoRendimentoAcumula($state['rh299_descrra']);
        }

        if (array_key_exists('rh299_qtdmesesrra', $state)) {
            $tributoIRRF->setQuantidadeMesAcumula($state['rh299_qtdmesesrra']);
        }

        if (array_key_exists('rh299_vlrdespcustas', $state)) {
            $tributoIRRF->setValorDespesaCusta($state['rh299_vlrdespcustas']);
        }

        if (array_key_exists('rh299_vlrdespadvogados', $state)) {
            $tributoIRRF->setValorDespesaAdvogados($state['rh299_vlrdespadvogados']);
        }

        return $tributoIRRF;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
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
        $this->sequencialProcessoServidor = $sequencialProcessoServidor;
    }

    /**
     * Get the value of codigoReceita
     *
     * @return  int
     */
    public function getCodigoReceita()
    {
        return $this->codigoReceita;
    }

    /**
     * Set the value of codigoReceita
     *
     * @param  int  $codigoReceita
     */
    public function setCodigoReceita($codigoReceita)
    {
        $this->codigoReceita = $codigoReceita;
    }

    /**
     * Get the value of valorIRRF
     *
     * @return  numeric
     */
    public function getValorIRRF()
    {
        return $this->valorIRRF;
    }

    /**
     * Set the value of valorIRRF
     *
     * @param  numeric  $valorIRRF
     */
    public function setValorIRRF($valorIRRF)
    {
        $this->valorIRRF = $valorIRRF;
    }

    /**
     * Get the value of periodoPagamento
     *
     * @return  string
     */
    public function getPeriodoPagamento()
    {
        return $this->periodoPagamento;
    }

    /**
     * Set the value of periodoPagamento
     *
     * @param  string  $periodoPagamento
     */
    public function setPeriodoPagamento($periodoPagamento)
    {
        $this->periodoPagamento = $periodoPagamento;
    }

    /**
     * Get the value of valorRendimentoTributavel
     *
     * @return  numeric
     */
    public function getValorRendimentoTributavel()
    {
        return $this->valorRendimentoTributavel;
    }

    /**
     * Set the value of valorRendimentoTributavel
     *
     * @param  numeric  $valorRendimentoTributavel
     */
    public function setValorRendimentoTributavel($valorRendimentoTributavel)
    {
        $this->valorRendimentoTributavel = $valorRendimentoTributavel;
    }

    /**
     * Get the value of valorRendimentoTributavel13
     *
     * @return  numeric
     */
    public function getValorRendimentoTributavel13()
    {
        return $this->valorRendimentoTributavel13;
    }

    /**
     * Set the value of valorRendimentoTributavel13
     *
     * @param  numeric  $valorRendimentoTributavel13
     */
    public function setValorRendimentoTributavel13($valorRendimentoTributavel13)
    {
        $this->valorRendimentoTributavel13 = $valorRendimentoTributavel13;
    }

    /**
     * Get the value of valorRendimentoMolestia
     *
     * @return  numeric
     */
    public function getValorRendimentoMolestia()
    {
        return $this->valorRendimentoMolestia;
    }

    /**
     * Set the value of valorRendimentoMolestia
     *
     * @param  numeric  $valorRendimentoMolestia
     */
    public function setValorRendimentoMolestia($valorRendimentoMolestia)
    {
        $this->valorRendimentoMolestia = $valorRendimentoMolestia;
    }

    /**
     * Get the value of valorIsenta65
     *
     * @return  numeric
     */
    public function getValorIsenta65()
    {
        return $this->valorIsenta65;
    }

    /**
     * Set the value of valorIsenta65
     *
     * @param  numeric  $valorIsenta65
     */
    public function setValorIsenta65($valorIsenta65)
    {
        $this->valorIsenta65 = $valorIsenta65;
    }

    /**
     * Get the value of valorJurosMora
     *
     * @return  numeric
     */
    public function getValorJurosMora()
    {
        return $this->valorJurosMora;
    }

    /**
     * Set the value of valorJurosMora
     *
     * @param  numeric  $valorJurosMora
     */
    public function setValorJurosMora($valorJurosMora)
    {
        $this->valorJurosMora = $valorJurosMora;
    }

    /**
     * Get the value of valorRendimentoIsento
     *
     * @return  numeric
     */
    public function getValorRendimentoIsento()
    {
        return $this->valorRendimentoIsento;
    }

    /**
     * Set the value of valorRendimentoIsento
     *
     * @param  numeric  $valorRendimentoIsento
     */
    public function setValorRendimentoIsento($valorRendimentoIsento)
    {
        $this->valorRendimentoIsento = $valorRendimentoIsento;
    }

    /**
     * Get the value of descricaoIsento
     *
     * @return  string
     */
    public function getDescricaoIsento()
    {
        return $this->descricaoIsento;
    }

    /**
     * Set the value of descricaoIsento
     *
     * @param  string  $descricaoIsento
     */
    public function setDescricaoIsento($descricaoIsento)
    {
        $this->descricaoIsento = $descricaoIsento;
    }

    /**
     * Get the value of valorPrevidenciaOficial
     *
     * @return  numeric
     */
    public function getValorPrevidenciaOficial()
    {
        return $this->valorPrevidenciaOficial;
    }

    /**
     * Set the value of valorPrevidenciaOficial
     *
     * @param  numeric  $valorPrevidenciaOficial
     */
    public function setValorPrevidenciaOficial($valorPrevidenciaOficial)
    {
        $this->valorPrevidenciaOficial = $valorPrevidenciaOficial;
    }

    /**
     * Get the value of descricaoRendimentoAcumula
     *
     * @return  string
     */
    public function getDescricaoRendimentoAcumula()
    {
        return $this->descricaoRendimentoAcumula;
    }

    /**
     * Set the value of descricaoRendimentoAcumula
     *
     * @param  string  $descricaoRendimentoAcumula
     */
    public function setDescricaoRendimentoAcumula($descricaoRendimentoAcumula)
    {
        $this->descricaoRendimentoAcumula = $descricaoRendimentoAcumula;
    }

    /**
     * Get the value of quantidadeMesAcumula
     *
     * @return  int
     */
    public function getQuantidadeMesAcumula()
    {
        return $this->quantidadeMesAcumula;
    }

    /**
     * Set the value of quantidadeMesAcumula
     *
     * @param  int  $quantidadeMesAcumula
     */
    public function setQuantidadeMesAcumula($quantidadeMesAcumula)
    {
        $this->quantidadeMesAcumula = $quantidadeMesAcumula;
    }

    /**
     * Get the value of valorDespesaCusta
     *
     * @return  numeric
     */
    public function getValorDespesaCusta()
    {
        return $this->valorDespesaCusta;
    }

    /**
     * Set the value of valorDespesaCusta
     *
     * @param  numeric  $valorDespesaCusta
     */
    public function setValorDespesaCusta($valorDespesaCusta)
    {
        $this->valorDespesaCusta = $valorDespesaCusta;
    }

    /**
     * Get the value of valorDespesaAdvogados
     *
     * @return  numeric
     */
    public function getValorDespesaAdvogados()
    {
        return $this->valorDespesaAdvogados;
    }

    /**
     * Set the value of valorDespesaAdvogados
     *
     * @param  numeric  $valorDespesaAdvogados
     */
    public function setValorDespesaAdvogados($valorDespesaAdvogados)
    {
        $this->valorDespesaAdvogados = $valorDespesaAdvogados;
    }
}
