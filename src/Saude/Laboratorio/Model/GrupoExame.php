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
class GrupoExame
{
    /**
     * Código do GrupoExame
     * @var integer
     */
    private $codigo;

    /**
     * Código do vinculo de grupo com laboratorio
     * @var integer
     */
    private $grupoLaboratorio;

    /**
     * Código do Exame
     * @var integer
     */
    private $exame;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_grupoexame_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do GrupoExame
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o GrupoExame
     *
     * @param  integer  $codigo Código do GrupoExame
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna o codigo do GrupoLaboratorio
     * @return int|null
     */
    public function getGrupoLaboratorio()
    {
        return $this->grupoLaboratorio;
    }

    /**
     * Seta o GrupoLaboratorio
     *
     * @param  integer  $codigo Código do GrupoLaboratorio
     *
     * @return  self
     */
    public function setGrupoLaboratorio($grupoLaboratorio)
    {
        $this->grupoLaboratorio = $grupoLaboratorio;

        return $this;
    }

    /**
     * Retorna o codigo do Exame
     * @return int|null
     */
    public function getExame()
    {
        return $this->exame;
    }

    /**
     * Seta o Exame
     *
     * @param  integer  $codigo Código do Exame
     *
     * @return  self
     */
    public function setExame($exame)
    {
        $this->exame = $exame;

        return $this;
    }

    /**
     * @param array $state
     * @return GrupoExame
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $grupoExame = new self();

        if (array_key_exists('la68_codigo', $state)) {
            $grupoExame->setCodigo((int)$state['la68_codigo']);
        }

        if (array_key_exists('la68_labgrupoexame', $state)) {
            $grupoExame->setGrupoLaboratorio($state['la68_labgrupoexame']);
        }

        if (array_key_exists('la68_labgrupoexame', $state)) {
            $grupoExame->setExame($state['la68_labgrupoexame']);
        }

        return $grupoExame;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la68_codigo' => $this->getCodigo(),
            'la68_labgrupoexame' => $this->getGrupoLaboratorio(),
            'la68_exame' => $this->getExame()
        );

        return $retorno;
    }
}
