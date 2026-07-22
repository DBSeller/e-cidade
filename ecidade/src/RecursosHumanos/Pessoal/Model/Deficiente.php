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
use ECidade\RecursosHumanos\Pessoal\Model\EquipamentoProtecao;

class Deficiente
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var bool
     */
    private $fisica;

    /**
     * @var bool
     */
    private $visual;

    /**
     * @var bool
     */
    private $auditiva;

    /**
     * @var bool
     */
    private $mental;

    /**
     * @var bool
     */
    private $intelectual;

    /**
     * @var bool
     */
    private $reabilitado;

     /**
     * @var bool
     */
    private $cota;

    /**
     * @var string
     */
    private $observacao;

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");

            $sql = " SELECT
                            rh253_matricula,
                            rh253_cota,
                            rh253_observacao,
                            rh253_fisica,
                            rh253_visual,
                            rh253_auditiva,
                            rh253_mental,
                            rh253_intelectual,
                            rh253_reabilitado
                    FROM recursoshumanos.rhdeficiente
                WHERE rh253_matricula={$codigo} AND rh253_instit={$instituicao}";
            $rs = \db_query($sql);

            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar o deficiênte código {$codigo}.");
            }

            if (pg_num_rows($rs) > 0) {
                $deficiencia = \db_utils::fieldsMemory($rs, 0);

                $this->setCodigo($deficiencia->rh253_matricula);
                $this->setFisica($deficiencia->rh253_fisica);
                $this->setVisual($deficiencia->rh253_visual);
                $this->setAuditiva($deficiencia->rh253_auditiva);
                $this->setMental($deficiencia->rh253_mental);
                $this->setIntelectual($deficiencia->rh253_intelectual);
                $this->setReabilitado($deficiencia->rh253_reabilitado);
                $this->setCota($deficiencia->rh253_cota);
                $this->setObservacao($deficiencia->rh253_observacao);
            }
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
     * @return bool
     */
    public function getFisica()
    {
        return $this->fisica;
    }

  /**
     * @param bool $fisica
     */
    public function setFisica($fisica)
    {
        $this->fisica = $fisica;
    }

   /**
     * @return bool
     */
    public function getAuditiva()
    {
        return $this->auditiva;
    }

   /**
     * @param bool $auditiva
     */
    public function setAuditiva($auditiva)
    {
        $this->auditiva = $auditiva;
    }

     /**
     * @return bool
     */
    public function getVisual()
    {
        return $this->visual;
    }

   /**
     * @param bool $visual
     */
    public function setVisual($visual)
    {
        $this->visual = $visual;
    }

  /**
     * @return bool
     */
    public function getIntelectual()
    {
        return $this->intelectual;
    }

    /**
     * @param bool $intelectual
     */
    public function setIntelectual($intelectual)
    {
        $this->intelectual = $intelectual;
    }

    /**
     * @return bool
     */
    public function getMental()
    {
        return $this->mental;
    }

    /**
     * @param bool $mental
     */
    public function setMental($mental)
    {
        $this->mental = $mental;
    }

    /**
     * @return bool
     */
    public function getReabilitado()
    {
        return $this->reabilitado;
    }

    /**
     * @param bool $reabilitado
     */
    public function setReabilitado($reabilitado)
    {
        $this->reabilitado = $reabilitado;
    }

     /**
     * @return bool
     */
    public function getCota()
    {
        return $this->cota;
    }

    /**
     * @param bool $cota
     */
    public function setCota($cota)
    {
        $this->cota = $cota;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }
}
