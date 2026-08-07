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
 * Classe para controle dos dados do grupo vinculado ao laboratório
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class GrupoLaboratorio
{
    /**
     * Código do GrupoLaboratorio
     * @var integer
     */
    private $codigo;

    /**
     * Código do Laboratório
     * @var integer
     */
    private $laboratorio;

    /**
     * Código do Grupo
     * @var integer
     */
    private $grupo;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_labgrupoexame_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do GrupoLaboratorio
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o GrupoLaboratorio
     *
     * @param  integer  $codigo Código do GrupoLaboratorio
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
        return $this->laboratorio;
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
     * Retorna o codigo do Grupo
     * @return int|null
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Seta o Grupo
     *
     * @param  integer  $codigo Código do Grupo
     *
     * @return  self
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * @param array $state
     * @return GrupoLaboratorio
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $grupoLaboratorio = new self();

        if (array_key_exists('la67_codigo', $state)) {
            $grupoLaboratorio->setCodigo((int)$state['la67_codigo']);
        }

        if (array_key_exists('la67_laboratorio', $state)) {
            $grupoLaboratorio->setLaboratorio($state['la67_laboratorio']);
        }

        if (array_key_exists('la67_grupo', $state)) {
            $grupoLaboratorio->setGrupo($state['la67_grupo']);
        }

        return $grupoLaboratorio;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la67_codigo' => $this->getCodigo(),
            'la67_laboratorio' => $this->getLaboratorio(),
            'la67_grupo' => $this->getGrupo()
        );

        return $retorno;
    }
}
