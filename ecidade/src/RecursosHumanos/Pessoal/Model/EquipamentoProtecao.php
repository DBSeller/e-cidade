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
namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\Configuracao\Instituicao\Model\Instituicao;

class EquipamentoProtecao
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $codigoAgenteNocivo;

    /**
     * @var string
     */
    private $utilizaEpc;

    /**
     * @var integer
     */
    private $eficaciaEpc;

    /**
     * @var string
     */
    private $utilizaEpi;

    /**
     * @var string
     */
    private $eficaciaEpi;

    /**
     * @var string
     */
    private $medidaProtecaoEpi;

    /**
     * @var string
     */
    private $funcionamentoEpi;

    /**
     * @var string
     */
    private $usoInterruptoEpi;

    /**
     * @var string
     */
    private $validadeEpi;

    /**
     * @var string
     */
    private $periodicidadeEpi;

    /**
     * @var string
     */
    private $higienizacaoEpi;

    /**
     * @var array EquipamentoProtecaoEpi
     */
    private $epis = [];

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");
            $sql = "
                select
                    *
                from
                    pessoal.rhlocaltrabequipamentoprotecao
                where
                    rh257_sequencial = {$codigo}";
            $rs = \db_query($sql);

            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar o equipamento de proteção código {$codigo}.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new \BusinessException("Equipamento de proteção código {$codigo} não encontrado.");
            }

            $equipamento = \db_utils::fieldsMemory($rs, 0);
            $this->setCodigo($equipamento->rh257_sequencial);
            $this->setCodigoAgenteNocivo($equipamento->rh257_rhlocaltrabagentesnocivos);
            $this->setUtilizaEpc($equipamento->rh257_utilizaepc);
            $this->setEficaciaEpc($equipamento->rh257_eficaciaepc);
            $this->setUtilizaEpi($equipamento->rh257_utilizaepi);
            $this->setEficaciaEpi($equipamento->rh257_eficaciaepi);
            $this->setMedidaProtecaoEpi($equipamento->rh257_medidaprotecaoepi);
            $this->setFuncionamentoEpi($equipamento->rh257_funcionamentoepi);
            $this->setUsoInterruptoEpi($equipamento->rh257_usoininterruptoepi);
            $this->setValidadeEpi($equipamento->rh257_validadeepi);
            $this->setPeriodicidadeEpi($equipamento->rh257_periodicidadeepi);
            $this->setHigienizacaoEpi($equipamento->rh257_higienizacaoepi);
            $this->epis = EquipamentoProtecaoEpi::getEpisByEquipamento($this->getCodigo());
        }
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigoAgenteNocivo()
    {
        return $this->codigoAgenteNocivo;
    }

    /**
     * @param int $codigoAgenteNocivo
     */
    public function setCodigoAgenteNocivo($codigoAgenteNocivo)
    {
        $this->codigoAgenteNocivo = $codigoAgenteNocivo;
    }

    /**
     * @return string
     */
    public function getUtilizaEpc()
    {
        return $this->utilizaEpc;
    }

    /**
     * @param string $utilizaEpc
     */
    public function setUtilizaEpc($utilizaEpc)
    {
        $this->utilizaEpc = $utilizaEpc;
    }

    /**
     * @return int
     */
    public function getEficaciaEpc()
    {
        return $this->eficaciaEpc;
    }

    /**
     * @param int $eficaciaEpc
     */
    public function setEficaciaEpc($eficaciaEpc)
    {
        $this->eficaciaEpc = $eficaciaEpc;
    }

    /**
     * @return string
     */
    public function getUtilizaEpi()
    {
        return $this->utilizaEpi;
    }

    /**
     * @param string $utilizaEpi
     */
    public function setUtilizaEpi($utilizaEpi)
    {
        $this->utilizaEpi = $utilizaEpi;
    }

    /**
     * @return string
     */
    public function getEficaciaEpi()
    {
        return $this->eficaciaEpi;
    }

    /**
     * @param string $eficaciaEpi
     */
    public function setEficaciaEpi($eficaciaEpi)
    {
        $this->eficaciaEpi = $eficaciaEpi;
    }

    /**
     * @return string
     */
    public function getMedidaProtecaoEpi()
    {
        return $this->medidaProtecaoEpi;
    }

    /**
     * @param string $medidaProtecaoEpi
     */
    public function setMedidaProtecaoEpi($medidaProtecaoEpi)
    {
        $this->medidaProtecaoEpi = $medidaProtecaoEpi;
    }

    /**
     * @return string
     */
    public function getFuncionamentoEpi()
    {
        return $this->funcionamentoEpi;
    }

    /**
     * @param string $funcionamentoEpi
     */
    public function setFuncionamentoEpi($funcionamentoEpi)
    {
        $this->funcionamentoEpi = $funcionamentoEpi;
    }

    /**
     * @return string
     */
    public function getUsoInterruptoEpi()
    {
        return $this->usoInterruptoEpi;
    }

    /**
     * @param string $usoInterruptoEpi
     */
    public function setUsoInterruptoEpi($usoInterruptoEpi)
    {
        $this->usoInterruptoEpi = $usoInterruptoEpi;
    }

    /**
     * @return string
     */
    public function getValidadeEpi()
    {
        return $this->validadeEpi;
    }

    /**
     * @param string $validadeEpi
     */
    public function setValidadeEpi($validadeEpi)
    {
        $this->validadeEpi = $validadeEpi;
    }

    /**
     * @return string
     */
    public function getPeriodicidadeEpi()
    {
        return $this->periodicidadeEpi;
    }

    /**
     * @param string $periodicidadeEpi
     */
    public function setPeriodicidadeEpi($periodicidadeEpi)
    {
        $this->periodicidadeEpi = $periodicidadeEpi;
    }

    /**
     * @return string
     */
    public function getHigienizacaoEpi()
    {
        return $this->higienizacaoEpi;
    }

    /**
     * @param string $higienizacaoEpi
     */
    public function setHigienizacaoEpi($higienizacaoEpi)
    {
        $this->higienizacaoEpi = $higienizacaoEpi;
    }

    public static function getByAgenteNocivo($codigoAgente)
    {
        if (!empty($codigoAgente)) {
            $instituicao = db_getsession("DB_instit");
            $sql = "
                select
                    rh257_sequencial
                from
                    pessoal.rhlocaltrabequipamentoprotecao
                where
                    rh257_rhlocaltrabagentesnocivos = {$codigoAgente}";
            $rs = \db_query($sql);

            if (!$rs) {
                $msg = "Houve um erro ao buscar o equipamento de proteção do agente código {$codigoAgente}.";
                throw new \DBException($msg);
            }

            if (pg_num_rows($rs) == 0) {
                $msg = "Equipamento de proteção do agente código {$codigoAgente} não encontrado.";
                throw new \BusinessException($msg);
            }

            $equipamento = \db_utils::fieldsMemory($rs, 0);
            return new EquipamentoProtecao($equipamento->rh257_sequencial);
        }
    }

    /**
     * @return array
     */
    public function getEpis()
    {
        return $this->epis;
    }

    /**
     * @param array $epis
     */
    public function setEpis($epis)
    {
        $this->epis = $epis;
    }
}
