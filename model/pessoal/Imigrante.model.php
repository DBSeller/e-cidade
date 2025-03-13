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

class Imigrante
{
    /**
     * @var integer
     */
    private $codigo;

    public function getCodigo()
    {
        return $this->codigo;
    }

    // Condicoes
    const REFUGIADO = 1;
    const SOLICITANTE_REFUGIADO = 2;
    const PERMANENCIA_BRASIL = 3;
    const BENEFICIADO_MERCOSUL = 4;
    const DEPENDENTE_AGENTE_DIPLOMATICO = 5;
    const BENEFICIADO_TRATADO_AMIZADE = 6;
    const OUTRA_CONDICAO = 7;

    // Residencia
    const NAO_INFORMADO = 0;
    const PRAZO_INDETERMINDADO = 1;
    const PRAZO_DETERMINADO = 2;

    /**
     * @var array
     */
    const DESCRICOES_CONDICOES = [
        self::REFUGIADO => self::REFUGIADO . " - Refugiado",
        self::SOLICITANTE_REFUGIADO => self::SOLICITANTE_REFUGIADO . " - Solicitante de refúgio",
        self::PERMANENCIA_BRASIL => self::PERMANENCIA_BRASIL . " - Permanência no Brasil em razão de reunião familiar",
        self::BENEFICIADO_MERCOSUL => self::BENEFICIADO_MERCOSUL . " - Beneficiado pelo acordo entre países do Mercosul",
        self::DEPENDENTE_AGENTE_DIPLOMATICO => self::DEPENDENTE_AGENTE_DIPLOMATICO . " - Dependente de agente diplomático e/ou consular de países que mantêm acordo de reciprocidade para o exercício de atividade remunerada no Brasil",
        self::BENEFICIADO_TRATADO_AMIZADE => self::BENEFICIADO_TRATADO_AMIZADE . " - Beneficiado pelo Tratado de Amizade, Cooperação e Consulta entre a República Federativa do Brasil e a República Portuguesa",
        self::OUTRA_CONDICAO => self::OUTRA_CONDICAO . " - Outra condição"
    ];

    /**
     * @var array
     */
    const DESCRICOES_RESIDENCIA = [
        self::NAO_INFORMADO => "Não Informado",
        self::PRAZO_INDETERMINDADO => self::PRAZO_INDETERMINDADO . " - Prazo indeterminado",
        self::PRAZO_DETERMINADO => self::PRAZO_DETERMINADO . " - Prazo determinado"
    ];

    public static function getDescricoesCondicoes()
    {
        return Imigrante::DESCRICOES_CONDICOES;
    }

    public static function getDescricoesResidencia()
    {
        return Imigrante::DESCRICOES_RESIDENCIA;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * tipo de Residencia do Imigrante
     * @var integer
     */
    private $codigoResidencia;

    /**
     * @return int
     */
    public function getCodigoResidencia()
    {
        return $this->codigoResidencia;
    }

    /**
     * @param int $codigoResidencia
     */
    public function setCodigoResidencia($codigoResidencia)
    {
        $this->codigoResidencia = $codigoResidencia;
    }

    /**
     * @return int
     */
    public function getCodigoCondicao()
    {
        return $this->codigoCondicao;
    }

    /**
     * @param int $codigoCondicao
     */
    public function setCodigoCondicao($codigoCondicao)
    {
        $this->codigoCondicao = $codigoCondicao;
    }

    /**
     * Tipo de condição do imigrante
     * @var integer
     */
    private $codigoCondicao;

    /**
     * matricula do imigrante
     * @var integer
     */
    private $matricula;

    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * instituicao do imigrante
     * @var integer
     */
    private $instituicao;

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * Construtor
     */
    public function __construct($matricula = null, $instituicao = null)
    {
        if (!empty($matricula)) {
            $this->setMatricula($matricula);
            $this->setInstituicao(db_getsession('DB_instit'));
            if (!empty($instituicao)) {
                $this->setInstituicao($instituicao);
            }

            $dao = new cl_rhimigrante();
            $where = "rh252_matricula = {$this->getMatricula()} and rh252_instituicao = {$this->getInstituicao()} ";
            $sql = $dao->sql_query(null, "rhimigrante.*", null, $where);
            $rs = db_query($sql);

            if (!$rs) {
                throw new \BusinessException(
                    "Erro ao buscar informações de imigrante da matricula {$this->getMatricula()}."
                );
            }

            $imigrante = db_utils::fieldsMemory($rs, 0);

            $this->setCodigoResidencia($imigrante->rh252_residencia);
            $this->setCodigoCondicao($imigrante->rh252_condicao);
            $this->setCodigo($imigrante->rh252_sequencial);
        }
    }

    public function save()
    {
        $rhimigrante = new cl_rhimigrante();
        $rhimigrante->rh252_residencia = $this->getCodigoResidencia();
        $rhimigrante->rh252_condicao = $this->getCodigoCondicao();
        $rhimigrante->rh252_matricula = $this->getMatricula();
        $rhimigrante->rh252_instituicao = $this->getMatricula();
        if (empty($this->getCodigo())) {
            $rhimigrante->incluir(null);
        } else {
            $rhimigrante->alterar($this->getCodigo());
        }
    }
}
