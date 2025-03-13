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
 * Classe para controle dos dados do Laboratório
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class Grupo
{
    /**
     * Código do Grupo
     * @var integer
     */
    private $codigo;

    /**
     * Descricao do Grupo
     * @var integer
     */
    private $descricao;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_grupo_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do Grupo
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o código do Grupo
     *
     * @param  integer  $codigo Código do Grupo
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna a descrição do Grupo
     * @return int|null
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Seta o descrição do Grupo
     *
     * @param  integer  $descricao Descrição do Grupo
     *
     * @return  self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }


        /**
     * @param array $state
     * @return Laboratorio
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $laboratorio = new self();

        if (array_key_exists('la66_codigo', $state)) {
            $laboratorio->setCodigo((int)$state['la66_codigo']);
        }

        if (array_key_exists('la66_descricao', $state)) {
            $laboratorio->setDescricao($state['la66_descricao']);
        }

        return $laboratorio;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la66_codigo' => $this->getCodigo(),
            'la66_descricao' => $this->getDescricao(),
        );

        return $retorno;
    }
}
