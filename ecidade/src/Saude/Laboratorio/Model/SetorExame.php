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
 * Classe para controle dos setores dos exames
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class SetorExame
{
    /**
     * Código do vinculo de setor com exame
     * @var integer
     */
    private $codigo;

    /**
     * Vinculo de laboratorio com setor
     * @var integer
     */
    private $laboratorioSetor;

    /**
     * Código do Exame
     * @var integer
     */
    private $exame;

    /**
     * Se o setor exame esta ativo
     * @var integer
     */
    private $ativo;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_setorexame_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o código do vinculo de setor com exame
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o código do vinculo de setor com exame
     *
     * @param  integer  $codigo Código do SetorExame
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna o vinculo de setor com laboratório
     * @return int|null
     */
    public function getLaboratorioSetor()
    {
        return $this->laboratorioSetor;
    }

    /**
     * Seta o vinculo de setor com laboratório
     *
     * @param  integer  $laboratorioSetor LaboratórioSetor
     *
     * @return  self
     */
    public function setLaboratorioSetor($laboratorioSetor)
    {
        $this->laboratorioSetor = $laboratorioSetor;

        return $this;
    }

    /**
     * Retorna código do exame
     * @return int|null
     */
    public function getExame()
    {
        return $this->exame;
    }

    /**
     * Seta o código do exame
     *
     * @param  integer  $exame Código do Exame
     *
     * @return  self
     */
    public function setExame($exame)
    {
        $this->exame = $exame;

        return $this;
    }

    /**
     * Retorna se o registro esta ativo ou não
     * @return int|null
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Seta se o registro esta ativo ou não
     *
     * @param  integer  $ativo Ativo
     *
     * @return  self
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @param array $state
     * @return Laboratorio
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $setorExame = new self();

        if (array_key_exists('la09_i_codigo', $state)) {
            $setorExame->setCodigo((int)$state['la09_i_codigo']);
        }

        if (array_key_exists('la09_i_labsetor', $state)) {
            $setorExame->setLaboratorioSetor((int)$state['la09_i_labsetor']);
        }

        if (array_key_exists('la09_i_exame', $state)) {
            $setorExame->setExame((int)$state['la09_i_exame']);
        }

        if (array_key_exists('la09_i_ativo', $state)) {
            $setorExame->setExame((int)$state['la09_i_ativo']);
        }

        return $setorExame;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la09_i_codigo' => $this->getCodigo(),
            'la09_i_labsetor' => $this->getLaboratorioSetor(),
            'la09_i_exame' => $this->getExame(),
            'la09_i_ativo' => $this->getAtivo(),
        );

        return $retorno;
    }
}
