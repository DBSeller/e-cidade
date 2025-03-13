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
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use DBDate;
use JSON;

class Unicidade
{
    /**
     * @var int
     */
    private $matriculaUnicidade;

    /**
     * @var int
     */
    private $codigoCategoriaUnicidade;

    /**
     * @var \DBDate | null
     */
    private $dataInicioUnicidade;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialProcessoContrato;

    /**
     * @var array
     */
    private $processoContrato;

    /**
     * @param array $state
     * @return Unicidade
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $mudanca = new self();

        if (array_key_exists('rh281_sequencial', $state)) {
            $mudanca->setSequencial((int)$state['rh281_sequencial']);
        }

        if (array_key_exists('rh281_sequencialprocessocontrato', $state)) {
            $mudanca->setSequencialProcessoContrato((int)$state['rh281_sequencialprocessocontrato']);
        }

        if (array_key_exists('rh281_codcateg', $state)) {
            $mudanca->setCodigoCategoriaUnicidade($state['rh281_codcateg']);
        }

        if (array_key_exists('rh281_matunic', $state)) {
            $mudanca->setMatriculaUnicidade($state['rh281_matunic']);
        }

        if (array_key_exists('rh281_dtinicio', $state)) {
            $mudanca->setDataInicioUnicidade($state['rh281_dtinicio']);
        }

        return $mudanca;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of matriculaUnicidade
     *
     * @return  int
     */
    public function getMatriculaUnicidade()
    {
        return $this->matriculaUnicidade;
    }

    /**
     * Set the value of matriculaUnicidade
     *
     * @param  int  $matriculaUnicidade
     */
    public function setMatriculaUnicidade($matriculaUnicidade)
    {
        $this->matriculaUnicidade = $matriculaUnicidade;
    }

    /**
     * Get the value of codigoCategoriaUnicidade
     *
     * @return  int
     */
    public function getCodigoCategoriaUnicidade()
    {
        return $this->codigoCategoriaUnicidade;
    }

    /**
     * Set the value of codigoCategoriaUnicidade
     *
     * @param  int  $codigoCategoriaUnicidade
     */
    public function setCodigoCategoriaUnicidade($codigoCategoriaUnicidade)
    {
        $this->codigoCategoriaUnicidade = $codigoCategoriaUnicidade;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataInicioUnicidade()
    {
        return $this->dataInicioUnicidade;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataInicioUnicidade  | null
     *
     * @return  self
     */
    public function setDataInicioUnicidade($dataInicioUnicidade)
    {
        $this->dataInicioUnicidade = $dataInicioUnicidade;
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
     * Get the value of sequencialProcessoContrato
     *
     * @return  int
     */
    public function getSequencialProcessoContrato()
    {
        return $this->sequencialProcessoContrato;
    }

    /**
     * Set the value of sequencialProcessoContrato
     *
     * @param  int  $sequencialProcessoContrato
     */
    public function setSequencialProcessoContrato($sequencialProcessoContrato)
    {
        $contratoRepository = new ContratoRepository();
        $contrato = $contratoRepository
            ->scopeSequencial($sequencialProcessoContrato)
            ->get();

        $this->setProcessoContrato($contrato);

        $this->sequencialProcessoContrato = $sequencialProcessoContrato;
    }

    /**
     * Get the value of processoContrato
     *
     * @return  array
     */
    public function getProcessoContrato()
    {
        return $this->processoContrato;
    }

    /**
     * Set the value of processoContrato
     *
     * @param  array  $processoContrato
     *
     * @return  self
     */
    public function setProcessoContrato(array $processoContrato)
    {
        $this->processoContrato = $processoContrato;

        return $this;
    }
}
