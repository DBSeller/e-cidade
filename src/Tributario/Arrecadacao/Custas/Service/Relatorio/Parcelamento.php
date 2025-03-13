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

namespace ECidade\Tributario\Arrecadacao\Custas\Service\Relatorio;

use ECidade\Tributario\Arrecadacao\Custas;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

/**
 * Serviço responsavel por calcular custas a partir da emissao de recibos.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Parcelamento implements Custas\Interfaces\Service
{
    /** @var integer */
    private $tipoDebito;

    /** @var integer */
    private $cadTipo;

    /** @var array */
    private $debitos;

    /** @var \DBDate */
    private $dataUsuario;

    public function __construct($tipoDebito, $cadTipo, $iniciais)
    {
        if (!\db_utils::inTransaction()) {
            throw new \Exception('Transação nao iniciada');
        }

        $this->tipoDebito = $tipoDebito;
        $this->cadTipo = $cadTipo;
        $this->debitos = $iniciais;
        $this->dataUsuario = \DBDate::createFromTimestamp(db_getsession("DB_datausu"));
    }

    /**
     * Processamento do serviço
     * @return array
     * @throws \Exception
     * @throws \ParameterException
     */
    public function processar()
    {
        $regraEmissao = new \regraEmissao($this->tipoDebito, 19, db_getsession('DB_instit'), $this->dataUsuario->getDate(), db_getsession('DB_ip'), true, false);

        $numpres = array();

        foreach ($this->debitos as $debito) {
            $numpres[$debito->numpre]['numpares'][$debito->numpar] = $debito;
        }

        $recibos = array();

        foreach ($numpres as $debito) {
            $recibo = $this->processarCustas($debito["numpares"], $regraEmissao);
            $recibos[] = $recibo;
        }

        return $recibos;
    }

    /**
     * @param $debitos
     * @param \regraEmissao $regraEmissao
     *
     * @return \recibo
     *
     * @throws \Exception
     */
    private function processarCustas($debitos, \regraEmissao $regraEmissao)
    {
        $recibo = $this->emissaoRecibo($debitos, $regraEmissao);

        $reciboService = new Custas\Service\Recibo($this->cadTipo);
        $reciboService->validaUsoDeCustas();
        $reciboService->setRecibo($recibo);
        $reciboService->setRegraEmissao($regraEmissao);
        $reciboService->processar();

        return $recibo;
    }

    /**
     * @param $debitos
     * @param \regraEmissao $regraEmissao
     * @return \recibo
     * @throws \Exception
     */
    private function emissaoRecibo($debitos, \regraEmissao $regraEmissao)
    {
        $dataVencimento = $this->getDataVencimento($debitos);

        $recibo = new \recibo(2, null, 1);

        foreach ($debitos as $debito) {
            $recibo->addNumpre($debito->numpre, $debito->numpar);
        }

        $recibo->setNumBco($regraEmissao->getCodConvenioCobranca());
        $recibo->setDataRecibo($dataVencimento->getDate());
        $recibo->setDataVencimentoRecibo($dataVencimento->getDate());
        $recibo->setExercicioRecibo(substr($dataVencimento->getDate(), 0, 4));
        $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($regraEmissao->getConvenio());
        $recibo->emiteRecibo($lConvenioCobrancaValido, true, $regraEmissao->getConvenio());

        return $recibo;
    }

    /**
     * @param $debitos
     * @return \DBDate|null
     * @throws \ParameterException
     */
    private function getDataVencimento($debitos)
    {
        $dataVencimento = null;

        foreach ($debitos as $debito) {
            $sSqlDebito = " select distinct arrecad.k00_numpre as numpre, ";
            $sSqlDebito .= "        arrecad.k00_numpar as numpar, ";
            $sSqlDebito .= "        arrecad.k00_dtvenc as data_vencimento ";
            $sSqlDebito .= "   from arrecad ";
            $sSqlDebito .= "  where k00_numpre = " . $debito->numpre;
            $sSqlDebito .= "    and k00_numpar = " . $debito->numpar;
            $rsDebito = db_query($sSqlDebito);

            $oDebito = \db_utils::fieldsMemory($rsDebito, 0);

            $novaData = new \DBDate($oDebito->data_vencimento);

            if ($dataVencimento === null || $dataVencimento->getTimeStamp() > $novaData->getTimeStamp()) {
                $dataVencimento = $novaData;
            }

            if ($this->dataUsuario->getTimeStamp() > $dataVencimento->getTimeStamp()) {
                $dataVencimento = $this->dataUsuario;
            }
        }

        return $dataVencimento;
    }
}
