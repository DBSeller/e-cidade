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

class ContratoEmergencial
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $contratoEmergencial;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var date
     */
    private $dataInicio;

    /**
     * @var date
     */
    private $dataFim;

    /**
     * @var string
     */
    private $asseCuratoria;

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $sql = "SELECT
            emergencial.rh163_matricula,
            renovacao.rh164_contratoemergencial,
            renovacao.rh164_descricao,
            renovacao.rh164_datainicio,
            renovacao.rh164_datafim,
            renovacao.rh164_assecuratoria
            FROM
                pessoal.rhcontratoemergencialrenovacao AS renovacao
            INNER JOIN pessoal.rhcontratoemergencial AS emergencial ON
                renovacao.rh164_contratoemergencial = emergencial.rh163_sequencial
            WHERE
            emergencial.rh163_matricula = {$codigo}";

            $rs = \db_query($sql);

            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar o código {$codigo} do contrato emergencial.");
            }

            $contrato = \db_utils::fieldsMemory($rs, 0);

            $this->setCodigo($contrato->rh163_matricula);
            $this->setContratoEmergencial($contrato->rh164_contratoemergencial);
            $this->setDescricao($contrato->rh164_descricao);
            $this->setDataInicio($contrato->rh164_datainicio);
            $this->setDataFim($contrato->rh164_datafim);
            $this->setAsseCuratoria($contrato->rh164_assecuratoria);
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
    public function getContratoEmergencial()
    {
        return $this->contratoEmergencial;
    }

    /**
     * @param int $contratoEmergencial
     */
    public function setContratoEmergencial($contratoEmergencial)
    {
        $this->contratoEmergencial = $contratoEmergencial;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return date
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param date $dataIncio
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return date
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * @param date $dataFim
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }

    /**
     * @return string
     */
    public function getAsseCuratoria()
    {
        return $this->asseCuratoria;
    }

    /**
     * @param string $asseCuratoria
     */
    public function setAsseCuratoria($asseCuratoria)
    {
        $this->asseCuratoria = $asseCuratoria;
    }
}
