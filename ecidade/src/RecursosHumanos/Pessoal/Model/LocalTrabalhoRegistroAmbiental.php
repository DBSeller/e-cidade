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

class LocalTrabalhoRegistroAmbiental
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $codigoLocalTrabalho;

    /**
     * @var integer
     */
    private $codigoInstituicao;

    /**
     * @var string
     */
    private $cpf;

    /**
     * integer
     */
    private $identificacaoOrgao;

    /**
     * @var string
     */
    private $numeroInscricaoOrgao;

    /**
     * @var string
     */
    private $descricaoOrgao;

    /**
     * @var string
     */
    private $ufOrgao;

    /**
     * @var null|\DBDate
     */
    private $periodoInicial = null;

    /**
     * @var null|\DBDate
     */
    private $periodoFinal = null;


    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");
            $sql = "
                select
                   *
                from
                    pessoal.rhlocaltrabregistroambiental
                where
                    rh258_sequencial = {$codigo}
                    and rh258_instituicao = {$instituicao}
                     ";
            $rs = \db_query($sql);

            if (!$rs) {
                $msg = "Houve um erro ao buscar informações do registro ambiental código {$codigo}.";
                throw new \DBException();
            }

            if (pg_num_rows($rs) == 0) {
                throw new \BusinessException("Registro ambiental código {$codigo} não encontrado.");
            }

            $registroAmbiental = \db_utils::fieldsMemory($rs, 0);
            $this->setCodigo($registroAmbiental->rh258_sequencial);
            $this->setCodigoLocalTrabalho($registroAmbiental->rh258_rhlocaltrab);
            $this->setCpf($registroAmbiental->rh258_cpfresponsavel);
            $this->setIdentificacaoOrgao($registroAmbiental->rh258_identificacaoorgao);
            $this->setNumeroInscricaoOrgao($registroAmbiental->rh258_numeroinscricaoorgao);
            $this->setDescricaoOrgao($registroAmbiental->rh258_descricaoorgao);
            $this->setUfOrgao($registroAmbiental->rh258_uforgao);
            if (!empty($registroAmbiental->rh258_periodoinicial)) {
                $this->setPeriodoInicial(new \DBDate($registroAmbiental->rh258_periodoinicial));
            }
            if (!empty($registroAmbiental->rh258_periodofinal)) {
                $this->setPeriodoFinal(new \DBDate($registroAmbiental->rh258_periodofinal));
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
     * @return int
     */
    public function getCodigoLocalTrabalho()
    {
        return $this->codigoLocalTrabalho;
    }

    /**
     * @param int $codigoLocalTrabalho
     */
    public function setCodigoLocalTrabalho($codigoLocalTrabalho)
    {
        $this->codigoLocalTrabalho = $codigoLocalTrabalho;
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
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getIdentificacaoOrgao()
    {
        return $this->identificacaoOrgao;
    }

    /**
     * @param mixed $identificacaoOrgao
     */
    public function setIdentificacaoOrgao($identificacaoOrgao)
    {
        $this->identificacaoOrgao = $identificacaoOrgao;
    }

    /**
     * @return string
     */
    public function getNumeroInscricaoOrgao()
    {
        return $this->numeroInscricaoOrgao;
    }

    /**
     * @param string $numeroInscricaoOrgao
     */
    public function setNumeroInscricaoOrgao($numeroInscricaoOrgao)
    {
        $this->numeroInscricaoOrgao = $numeroInscricaoOrgao;
    }

    /**
     * @return string
     */
    public function getDescricaoOrgao()
    {
        return $this->descricaoOrgao;
    }

    /**
     * @param string $descricaoOrgao
     */
    public function setDescricaoOrgao($descricaoOrgao)
    {
        $this->descricaoOrgao = $descricaoOrgao;
    }

    /**
     * @return string
     */
    public function getUfOrgao()
    {
        return $this->ufOrgao;
    }

    /**
     * @param string $ufOrgao
     */
    public function setUfOrgao($ufOrgao)
    {
        $this->ufOrgao = $ufOrgao;
    }

    /**
     * @return \DBDate|null
     */
    public function getPeriodoInicial()
    {
        return $this->periodoInicial;
    }

    /**
     * @param \DBDate|null $periodoInicial
     */
    public function setPeriodoInicial($periodoInicial)
    {
        $this->periodoInicial = $periodoInicial;
    }

    /**
     * @return \DBDate|null
     */
    public function getPeriodoFinal()
    {
        return $this->periodoFinal;
    }

    /**
     * @param \DBDate|null $periodoFinal
     */
    public function setPeriodoFinal($periodoFinal)
    {
        $this->periodoFinal = $periodoFinal;
    }

    public static function getRegistrosByLocalTrabalho($codigoLocalTrabalho)
    {
        $registrosAmbientais = [];

        if (!empty($codigoLocalTrabalho)) {
            $instituicao = db_getsession("DB_instit");
            $sql = "
                select
                    rh258_sequencial
                from
                    pessoal.rhlocaltrabregistroambiental
                where
                    rh258_rhlocaltrab = {$codigoLocalTrabalho}
                    and rh258_instituicao = {$instituicao}";
            $rs = \db_query($sql);
            if (!$rs) {
                $msg = "Houve um erro ao buscar o local de trabalho código {$codigoLocalTrabalho}.";
                throw new \DBException($msg);
            }

            $totalRegistro = pg_num_rows($rs);
            for ($i = 0; $i < $totalRegistro; $i++) {
                $registroAmbiental = \db_utils::fieldsMemory($rs, $i);
                $registrosAmbientais[] = new LocalTrabalhoRegistroAmbiental($registroAmbiental->rh258_sequencial);
            }
        } else {
            throw new \BusinessException("Código do local de trabalho não informado.");
        }
        return $registrosAmbientais;
    }
}
