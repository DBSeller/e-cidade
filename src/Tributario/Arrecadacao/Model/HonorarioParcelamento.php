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

namespace ECidade\Tributario\Arrecadacao\Model;

use BusinessException;
use ECidade\Tributario\Juridico\Inicial\Inicial;
use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;
use ECidade\Tributario\Library\Model;
use ECidade\Tributario\Divida\Repository\InicialRepository;
use ECidade\Tributario\Arrecadacao\Repository\Taxa as TaxaRepository;
use ECidade\Tributario\Juridico\ProcessoForo\Repository\ProcessoForo as ProcessoForoRepository;
use Taxa;

/**
 * Class HonorarioParcelamento
 * @package ECidade\Tributario\Arrecadacao\Model
 */
final class HonorarioParcelamento extends Model
{
    /**
     * @var Integer
     */
    private $sequencial;
    /**
     * @var ProcessoForo
     */
    /**
     * @var ProcessoForo
     */
    private $processoforo;
    /**
     * @var Inicial
     */
    private $inicial;
    /**
     * @var Integer
     */
    private $numeroparcelas;
    /**
     * @var Taxa[]
     */
    private $taxas;

    /**
     * @param array $state
     * @return HonorarioParcelamento
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $honorarioParcelamento = new self();
        $taxaRepository = TaxaRepository::getInstance();

        if (array_key_exists('ar43_sequencial', $state)) {
            $honorarioParcelamento->setSequencial($state['ar43_sequencial']);
        }

        if (array_key_exists('ar43_processoforo', $state)) {
            $processoForoRepository = ProcessoForoRepository::getInstance();
            $processoForo = $processoForoRepository->getByCodigo($state['ar43_processoforo']);
            $honorarioParcelamento->setProcessoForo($processoForo);

            $taxasModel = $taxaRepository->getTaxaHonorarioParaProcessoForo();

            if (!empty($taxasModel)) {
                $honorarioParcelamento->setTaxas($taxasModel);
            }
        }

        if (array_key_exists('ar43_inicial', $state)) {
            $inicial = InicialRepository::find($state['ar43_inicial']);
            $honorarioParcelamento->setInicial($inicial);

            $taxasModel = $taxaRepository->getTaxaHonorarioParaInicial();

            if (!empty($taxasModel)) {
                $honorarioParcelamento->setTaxas($taxasModel);
            }
        }

        if (array_key_exists('ar43_numeroparcelas', $state)) {
            $honorarioParcelamento->setNumeroParcelas($state['ar43_numeroparcelas']);
        }

        return $honorarioParcelamento;
    }

    /**
     * @return Integer
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return $this
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;

        return $this;
    }

    /**
     * @return ProcessoForo
     */
    public function getProcessoForo()
    {
        return $this->processoforo;
    }

    /**
     * @param ProcessoForo|null $processoforo
     * @return $this
     * @throws BusinessException
     */
    public function setProcessoForo($processoforo)
    {
        if (!$processoforo instanceof ProcessoForo && $processoforo !== null) {
            throw new BusinessException('Tipo de dado inválido para Processo Foro.');
        }

        $this->processoforo = $processoforo;

        return $this;
    }

    /**
     * @return Inicial
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @param Inicial|null $inicial
     * @return $this
     * @throws BusinessException
     */
    public function setInicial($inicial)
    {
        if (!$inicial instanceof Inicial && $inicial !== null) {
            throw new BusinessException('Tipo de dado inválido para Inicial.');
        }

        $this->inicial = $inicial;

        return $this;
    }

    /**
     * @return Integer
     */
    public function getNumeroParcelas()
    {
        return $this->numeroparcelas;
    }

    /**
     * @param int $numeroparcelas
     * @return $this
     */
    public function setNumeroParcelas($numeroparcelas)
    {
        $this->numeroparcelas = $numeroparcelas;

        return $this;
    }

    /**
     * @return Taxa[]
     */
    public function getTaxas()
    {
        return $this->taxas;
    }

    /**
     * @param Taxa[] $taxa
     * @return $this
     */
    public function setTaxas($taxa)
    {
        $this->taxas = $taxa;

        return $this;
    }
}
