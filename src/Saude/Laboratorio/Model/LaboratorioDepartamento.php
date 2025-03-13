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

namespace ECidade\Saude\Laboratorio\Model;

/**
 * Classe para controle dos dados do grupo de um laboratorio vinculado ao exame
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class LaboratorioDepartamento
{
    /**
     * Código do LaboratorioDepartamento
     * @var integer
     */
    private $codigo;

    /**
     * Código do laboratorio
     * @var integer
     */
    private $laboratorio;

    /**
     * Código do Departamento
     * @var integer
     */
    private $departamento;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_labdepart_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do LaboratorioDepartamento
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o LaboratorioDepartamento
     *
     * @param  integer  $codigo Código do LaboratorioDepartamento
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna o codigo do Laboratorio
     * @return int|null
     */
    public function getLaboratorio()
    {
        return $this->grupoLaboratorio;
    }

    /**
     * Seta o Laboratorio
     *
     * @param  integer  $codigo Código do Laboratorio
     *
     * @return  self
     */
    public function setLaboratorio($laboratorio)
    {
        $this->laboratorio = $laboratorio;

        return $this;
    }

    /**
     * Retorna o codigo do Departamento
     * @return int|null
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Seta o Departamento
     *
     * @param  integer  $codigo Código do Departamento
     *
     * @return  self
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * @param array $state
     * @return LaboratorioDepartamento
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $laboratorioDepartamento = new self();

        if (array_key_exists('la03_i_codigo', $state)) {
            $laboratorioDepartamento->setCodigo((int)$state['la03_i_codigo']);
        }

        if (array_key_exists('la03_i_laboratorio', $state)) {
            $laboratorioDepartamento->setLaboratorio($state['la03_i_laboratorio']);
        }

        if (array_key_exists('la03_i_departamento', $state)) {
            $laboratorioDepartamento->setDepartamento($state['la03_i_departamento']);
        }

        return $laboratorioDepartamento;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la03_i_codigo' => $this->getCodigo(),
            'la03_i_laboratorio' => $this->getLaboratorio(),
            'la03_i_departamento' => $this->getDepartamento()
        );

        return $retorno;
    }
}
