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

class Cargo
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
     * @var integer
     */
    private $vagas;

    /**
     * @var string
     */
    private $codigoCbo;

    /**
     * @var string
     */
    private $lei;

    /**
     * @var string
     */
    private $class;

    /**
     * @var boolean
     */
    private $ativo;

    /**
     * @var integer
     */
    private $funcaoGrupo;

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
    private $descricaoCompleta;

    /**
     * @var integer
     */
    private $codigoInstrucao;

    /**
     * @var string
     */
    private $descricaoAtividade;

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");
            // No sistema esta invertido o cargo/funcao
            $sql = "select * from pessoal.rhfuncao where rh37_funcao = {$codigo} and rh37_instit = {$instituicao}";
            $rs = \db_query($sql);

            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar o cargo código {$codigo}.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new \BusinessException("Cargo código {$codigo} não encontrado.");
            }

            $cargo = \db_utils::fieldsMemory($rs, 0);
            $this->setCodigo($cargo->rh37_funcao);
            $this->setCodigoInstituicao($cargo->rh37_instit);
            $this->setDescricao($cargo->rh37_descr);
            $this->setVagas($cargo->rh37_vagas);
            $this->setCodigoCbo($cargo->rh37_cbo);
            $this->setLei($cargo->rh37_lei);
            $this->setClass($cargo->rh37_class);
            $this->setAtivo($cargo->rh37_ativo);
            $this->setFuncaoGrupo($cargo->rh37_funcaogrupo);
            if (!empty($cargo->rh37_datainicial)) {
                $this->setDataInicio(new \DBDate($cargo->rh37_datainicial));
            }
            if (!empty($cargo->rh37_datafinal)) {
                $this->setDataFim(new \DBDate($cargo->rh37_datafinal));
            }
            $this->setDescricaoCompleta($cargo->rh37_descricaocompleta);
            $this->setCodigoInstrucao($cargo->rh37_rhinstrucao);
            $this->setDescricaoAtividade($cargo->rh37_descricaoatividades);
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
     * @return int
     */
    public function getVagas()
    {
        return $this->vagas;
    }

    /**
     * @param int $vagas
     */
    public function setVagas($vagas)
    {
        $this->vagas = $vagas;
    }

    /**
     * @return string
     */
    public function getCodigoCbo()
    {
        return $this->codigoCbo;
    }

    /**
     * @param string $codigoCbo
     */
    public function setCodigoCbo($codigoCbo)
    {
        $this->codigoCbo = $codigoCbo;
    }

    /**
     * @return string
     */
    public function getLei()
    {
        return $this->lei;
    }

    /**
     * @param string $lei
     */
    public function setLei($lei)
    {
        $this->lei = $lei;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return int
     */
    public function getFuncaoGrupo()
    {
        return $this->funcaoGrupo;
    }

    /**
     * @param int $funcaoGrupo
     */
    public function setFuncaoGrupo($funcaoGrupo)
    {
        $this->funcaoGrupo = $funcaoGrupo;
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
    public function getDescricaoCompleta()
    {
        return $this->descricaoCompleta;
    }

    /**
     * @param string $descricaoCompleta
     */
    public function setDescricaoCompleta($descricaoCompleta)
    {
        $this->descricaoCompleta = $descricaoCompleta;
    }

    /**
     * @return int
     */
    public function getCodigoInstrucao()
    {
        return $this->codigoInstrucao;
    }

    /**
     * @param int $codigoInstrucao
     */
    public function setCodigoInstrucao($codigoInstrucao)
    {
        $this->codigoInstrucao = $codigoInstrucao;
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
