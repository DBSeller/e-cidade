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
namespace ECidade\RecursosHumanos\Pessoal\Servidor\Model;

use ECidade\Configuracao\Instituicao\Model\Instituicao;

class Funcao
{
    /**
     * @var integer
     */
    private $codigoInstituicao;

    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var \DBDate|null
     */
    private $dataInicio;

    /**
     * @var \DBDate|null
     */
    private $dataFim;

    /**
     * @var string
     */
    private $descricaoAtividade;

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");
            // No sistema esta invertido o funcao/cargo
            $sql = "select * from pessoal.rhcargo where rh04_codigo = {$codigo} and rh04_instit = {$instituicao}";
            $rs = \db_query($sql);
            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar a função código {$codigo}.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new \BusinessException("Função código {$codigo} não encontrada.");
            }

            $cargo = \db_utils::fieldsMemory($rs, 0);
            $this->setCodigo($cargo->rh04_codigo);
            $this->setCodigoInstituicao($cargo->rh04_instit);
            $this->setDescricao($cargo->rh04_descr);
            if (!empty($cargo->rh04_datainicial)) {
                $this->setDataInicio(new \DBDate($cargo->rh04_datainicial));
            }
            if (!empty($cargo->rh04_datafinal)) {
                $this->setDataFim(new \DBDate($cargo->rh04_datafinal));
            }
            $this->setDescricaoAtividade($cargo->rh04_descricaoatividades);
        }
    }

    /**
     * @return int
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * @param int $codigoInstituicao
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
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
     * @return \DBDate|null
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param \DBDate|null $dataInicio
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return \DBDate|null
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * @param \DBDate|null $dataFim
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }

    /**
     * @return string
     */
    public function getDescricaoAtividade()
    {
        return $this->descricaoAtividade;
    }

    /**
     * @param string $descricaoAtividade
     */
    public function setDescricaoAtividade($descricaoAtividade)
    {
        $this->descricaoAtividade = $descricaoAtividade;
    }
}
