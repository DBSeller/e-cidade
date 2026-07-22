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

use ECidade\Configuracao\Formulario\Identifier;

/**
 * Classe para controle de avaliacoes
 * @package Configuracao
 */
class Avaliacao
{
    /**
     * @var int
     */
    protected $iAvaliacao;
    /**
     * @var string
     */
    protected $sDescricao;
    /**
     * @var AvaliacaoGrupo[]
     */
    protected $aGrupos = array();
    /**
     * @var string
     */
    protected $sObservacao;
    /**
     * @var string
     */
    protected $sIdentificador;
    /**
     * @var int
     */
    protected $iTipo;
    /**
     * @var bool
     */
    protected $lAtivo = true;
    /**
     * @var string
     */
    protected $sSqlCargaDados;
    /**
     * @var bool
     */
    protected $lPermiteEdicao = true;
    /**
     * @var int
     */
    protected $iAvaliacaoGrupo;

    /**
     * Avaliacao constructor.
     * @param null $iAvaliacao
     * @throws BusinessException
     * @throws DBException
     */
    public function __construct($iAvaliacao = null)
    {
        if (empty($iAvaliacao)) {
            return;
        }

        $oDaoAvaliacao = new cl_avaliacao;
        $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file($iAvaliacao);
        $rsAvaliacao = db_query($sSqlAvaliacao);

        if (!$rsAvaliacao) {
            throw new DBException('Erro ao buscar avaliação.');
        }

        if (pg_num_rows($rsAvaliacao) == 0) {
            throw new BusinessException('Avaliação não encontrada.');
        }

        $oDadosAvaliacao = db_utils::fieldsMemory($rsAvaliacao, 0);
        $this->setAvaliacao($iAvaliacao)
            ->setDescricao($oDadosAvaliacao->db101_descricao)
            ->setObservacao($oDadosAvaliacao->db101_obs)
            ->setIdentificador($oDadosAvaliacao->db101_identificador)
            ->setPermiteEdicao($oDadosAvaliacao->db101_permiteedicao == 't')
            ->setSqlCargaDados($oDadosAvaliacao->db101_cargadados)
            ->setAtivo($oDadosAvaliacao->db101_ativo == 't')
            ->setTipoAvaliacao($oDadosAvaliacao->db101_avaliacaotipo);

    }

    /**
     * @return int
     * @deprecated
     * @see getCodigo
     */
    public function getAvaliacao()
    {
        return $this->iAvaliacao;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->iAvaliacao;
    }

    /**
     * @param $iCodigo
     * @return $this
     */
    public function setCodigo($iCodigo)
    {
        $this->iAvaliacao = $iCodigo;
        return $this;
    }

    /**
     * @param $iAvaliacao
     * @return $this
     * @deprecated
     * @see setCodigo
     */
    public function setAvaliacao($iAvaliacao)
    {
        $this->iAvaliacao = $iAvaliacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    /**
     * @param $sDescricao
     * @return $this
     */
    public function setDescricao($sDescricao)
    {
        $this->sDescricao = $sDescricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->sObservacao;
    }

    /**
     * @param $sObservacao
     * @return $this
     */
    public function setObservacao($sObservacao)
    {
        $this->sObservacao = $sObservacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentificador()
    {
        return $this->sIdentificador;
    }

    /**
     * @param $sIdentificador
     * @return $this
     */
    public function setIdentificador($sIdentificador)
    {
        $this->sIdentificador = $sIdentificador;
        return $this;
    }

    /**
     * @return AvaliacaoGrupo[]
     * @throws BusinessException
     * @throws DBException
     */
    public function getGruposPerguntas()
    {
        if (count($this->aGrupos) == 0 && !empty($this->iAvaliacao)) {

            $oDaoGrupoPerguntas = new cl_avaliacaogrupopergunta;
            $sSqlGrupos = $oDaoGrupoPerguntas->sql_query_file(null,
                "*",
                "db102_ordem, db102_sequencial",
                "db102_avaliacao = {$this->iAvaliacao}"
            );
            $rsGrupos = $oDaoGrupoPerguntas->sql_record($sSqlGrupos);
            if ($oDaoGrupoPerguntas->numrows > 0) {

                $aGrupos = db_utils::getCollectionByRecord($rsGrupos);
                foreach ($aGrupos as $oGrupo) {

                    $oGrupo = AvaliacaoGrupo::make($oGrupo);
                    $oGrupo->setAvaliacao($this);
                    $this->aGrupos[] = $oGrupo;

                }
            }
        }

        return $this->aGrupos;
    }

    /**
     * @return AvaliacaoGrupo[]
     * @throws Exception
     */
    public function getGrupos()
    {
        return $this->getGruposPerguntas();
    }

    /**
     * @param null $iGrupo
     * @return AvaliacaoPergunta[]
     * @throws Exception
     */
    public function getPerguntas($iGrupo = null)
    {

        $aPerguntas = array();
        $aPerguntasDoGrupo = $this->getGruposPerguntas();
        foreach ($aPerguntasDoGrupo as $oGrupo) {

            $aPerguntasGrupo = $oGrupo->getPerguntas();
            if (!empty($iGrupo)) {

                if ($iGrupo == $oGrupo->getGrupo()) {

                    foreach ($aPerguntasGrupo as $oPergunta) {

                        $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
                        $aPerguntas[] = $oPergunta;
                    }
                    break;
                }
            } else {

                foreach ($aPerguntasGrupo as $oPergunta) {

                    $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
                    $aPerguntas[] = $oPergunta;
                }
            }
        }

        return $aPerguntas;
    }

    /**
     * @param null $iAvaliacao
     * @return $this
     * @throws Exception
     */
    public function setAvaliacaoGrupo($iAvaliacao = null, $altera = false)
    {
        $oDaoAvaliacaoGrupo = db_utils::getDao("avaliacaogruporesposta");
        if (empty($iAvaliacao)) {

            $oDaoAvaliacaoGrupo->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
            $oDaoAvaliacaoGrupo->db107_hora = db_hora();
            $oDaoAvaliacaoGrupo->db107_usuario = db_getsession("DB_id_usuario");
            $oDaoAvaliacaoGrupo->incluir(null);
            if ($oDaoAvaliacaoGrupo->erro_status == 0) {

                $sErrorMessage = "Erro ao incluir nova avaliação para usuário.\n";
                $sErrorMessage .= "\n{$oDaoAvaliacaoGrupo->erro_msg}";
                throw new Exception($sErrorMessage);
            }
            $this->iAvaliacaoGrupo = $oDaoAvaliacaoGrupo->db107_sequencial;
        } else {

            if ($altera) {
                $oDaoAvaliacaoGrupo->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoAvaliacaoGrupo->db107_hora = db_hora();
                $oDaoAvaliacaoGrupo->db107_usuario = db_getsession("DB_id_usuario");
                $oDaoAvaliacaoGrupo->db107_sequencial = $iAvaliacao;
                $oDaoAvaliacaoGrupo->alterar($iAvaliacao);
                if ($oDaoAvaliacaoGrupo->erro_status == 0) {
                    $sErrorMessage = "Erro ao alterar a avaliação para usuário.\n";
                    $sErrorMessage .= "\n{$oDaoAvaliacaoGrupo->erro_msg}";
                    throw new Exception($sErrorMessage);
                }
                $this->iAvaliacaoGrupo = $oDaoAvaliacaoGrupo->db107_sequencial;
            }
            $this->iAvaliacaoGrupo = $iAvaliacao;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getAvaliacaoGrupo()
    {
        return $this->iAvaliacaoGrupo;
    }

    /**
     * @param $iCodigoPergunta
     * @return array|stdClass[]
     * @throws Exception
     */
    public function getRespostasDaPerguntaPoCodigo($iCodigoPergunta)
    {
        $aRespostas = array();
        if ($this->iAvaliacaoGrupo != "") {

            $oPergunta = new AvaliacaoPergunta($iCodigoPergunta);
            $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
            $aRespostas = $oPergunta->getRespostas();
        }
        return $aRespostas;
    }

    /**
     * @param $sIdentificador
     * @return array
     * @throws Exception
     */
    public function getRespostasDaPerguntaPorIdentificador($sIdentificador)
    {
        $aRespostas = array();
        $oDaoAvaliacaoPergunta = new cl_avaliacaopergunta;
        $sSqlPergunta = $oDaoAvaliacaoPergunta->sql_query_file(null,
            "db103_sequencial",
            null,
            "db103_identificador='{$sIdentificador}'"
        );
        $rsPergunta = $oDaoAvaliacaoPergunta->sql_record($sSqlPergunta);
        if ($oDaoAvaliacaoPergunta->numrows > 0 && $this->iAvaliacaoGrupo != "") {

            $oPergunta = new AvaliacaoPergunta(db_utils::fieldsMemory($rsPergunta, 0)->db103_sequencial);
            $oPergunta->setAvaliacao($this->iAvaliacaoGrupo);
            $aRespostasPergunta = $oPergunta->getRespostas();
            foreach ($aRespostasPergunta as $oResposta) {
                if ($oResposta->marcada == true) {
                    $aRespostas[] = $oResposta;
                }
            }
        }

        return $aRespostas;
    }

    /**
     * @param $sIdentificadorPergunta
     * @param $iCodigoOpcaoResposta
     * @return bool
     * @throws Exception
     */
    public function verificaSeRespostaEstaMarcada($sIdentificadorPergunta, $iCodigoOpcaoResposta)
    {
        /**
         * Buscamos as respostas da pergunta pelo identificador
         */
        if (is_int($sIdentificadorPergunta)) {
            $aRespostas = $this->getRespostasDaPerguntaPoCodigo($sIdentificadorPergunta);
        } else {
            $aRespostas = $this->getRespostasDaPerguntaPorIdentificador($sIdentificadorPergunta);
        }
        $iRespostas = count($aRespostas);

        if ($iRespostas == 0) {
            return false;
        }

        /**
         * Iteramos sobre as respostas da pergunta procurando a opcao identificada pelo parametro {$iCodigoOpcaoResposta}
         */
        foreach ($aRespostas as $oResposta) {

            if ($oResposta->codigoresposta == $iCodigoOpcaoResposta && $oResposta->marcada == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna o valor da Resposta de uma pergunta
     * OBS.: Só retorna o valor de perguntas onde:
     *       -> A pergunta for do tipo dissertativa
     *       -> A pergunta ter opcao aceita texto SI
     * @param $sIdentificadorPergunta
     * @param $iCodigoOpcaoResposta
     * @return null
     * @throws Exception
     */
    public function retornaValorRespostaMarcada($sIdentificadorPergunta, $iCodigoOpcaoResposta)
    {
        /**
         * Buscamos as respostas da pergunta pelo identificador
         */
        $aRespostas = $this->getRespostasDaPerguntaPorIdentificador($sIdentificadorPergunta);
        $iRespostas = count($aRespostas);

        if ($iRespostas == 0) {
            return null;
        }

        /**
         * Iteramos sobre as respostas da pergunta procurando a opcao identificada pelo parametro {$iCodigoOpcaoResposta}
         */
        foreach ($aRespostas as $oResposta) {

            if ($oResposta->texto == 1 || $oResposta->aceitatexto == 1) {

                if ($oResposta->codigoresposta == $iCodigoOpcaoResposta && !empty($oResposta->textoresposta)) {
                    return $oResposta->textoresposta;
                }
            }
        }
        return null;
    }

    /**
     * @param $lPermiteEdicao
     * @return $this
     */
    public function setPermiteEdicao($lPermiteEdicao)
    {
        $this->lPermiteEdicao = $lPermiteEdicao;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPermiteEdicao()
    {
        return $this->lPermiteEdicao;
    }

    /**
     * @param $sSqlCargaDados
     * @return $this
     */
    public function setSqlCargaDados($sSqlCargaDados)
    {
        $this->sSqlCargaDados = $sSqlCargaDados;
        return $this;
    }

    /**
     * @return string
     */
    public function getSqlCargaDados()
    {
        return $this->sSqlCargaDados;
    }

    /**
     * @param $lAtivo
     * @return $this
     */
    public function setAtivo($lAtivo)
    {
        $this->lAtivo = $lAtivo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->lAtivo;
    }

    /**
     * @param $iTipo
     * @return $this
     */
    public function setTipoAvaliacao($iTipo)
    {
        $this->iTipo = $iTipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoAvaliacao()
    {
        return $this->iTipo;
    }

    /**
     * @param AvaliacaoGrupo $oGrupo
     * @return $this
     */
    public function addGrupo(AvaliacaoGrupo $oGrupo)
    {
        $this->aGrupos[] = $oGrupo;
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function salvar()
    {
        $identifier = new Identifier('avaliacao', $this->iAvaliacao);

        if (empty($this->sIdentificador)) {
            $this->sIdentificador = $identifier->slugify($this->sDescricao);
        } else if (!$identifier->validate($this->sIdentificador)) {
            throw new \DBException("Identificador da avaliação já cadastrado: '$this->sIdentificador'");
        }

        $oDao = new cl_avaliacao;
        $oDao->db101_sequencial = $this->iAvaliacao;
        $oDao->db101_avaliacaotipo = $this->iTipo;
        $oDao->db101_descricao = pg_escape_string($this->sDescricao);
        $oDao->db101_obs = pg_escape_string($this->sObservacao);
        $oDao->db101_ativo = $this->lAtivo ? "true" : "false";
        $oDao->db101_identificador = $this->sIdentificador;
        $oDao->db101_cargadados = pg_escape_string($this->sSqlCargaDados);
        $oDao->db101_permiteedicao = $this->lPermiteEdicao ? "true" : "false";

        if (empty($this->iAvaliacao)) {
            $oDao->incluir(null);
        } else {
            $oDao->alterar($this->iAvaliacao);
        }

        if ($oDao->erro_status == 0) {
            throw new \Exception("Não foi possível salvar a avaliação.");
        }

        $this->iAvaliacao = $oDao->db101_sequencial;

        foreach ($this->aGrupos as $oGrupo) {

            $oGrupo->setAvaliacao($this);
            $oGrupo->salvar();
        }

        return true;
    }

    /**
     * Exclui avaliacao e suas dependencias
     * @return bool
     * @throws Exception
     */
    public function excluir()
    {
        if (empty($this->iAvaliacao)) {
            return false;
        }

        foreach ($this->getGrupos() as $oGrupo) {
            $oGrupo->excluir();
        }

        $oDao = new cl_avaliacao();
        $oDao->excluir($this->iAvaliacao);
        if ($oDao->erro_status == 0) {
            throw new DBException("Erro ao excluir avaliação.");
        }

        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toJson()
    {
        $copy = clone $this;
        $copy->organizaDados();

        $copy->aGrupos = array_map(function ($data) {
            return $data->jsonSerialize();
        }, $copy->aGrupos);

        foreach ($copy->aGrupos as &$grupo) {
            $grupo['oAvaliacao'] = array();
            $grupo['aPerguntas'] = array_map(function ($data) {
                return $data->jsonSerialize();
            }, $grupo['aPerguntas']);
        }

        return JSON::create()->stringify($copy->jsonSerialize());
    }

    /**
     * @return array
     */
    private function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function organizaDados()
    {
        $this->codigo = $this->getCodigo();
        foreach ($this->getGrupos() as $grupo) {
            $grupo->codigo = $grupo->getCodigo();
            foreach ($grupo->getPerguntas() as $pergunta) {
                $pergunta->codigo = $pergunta->getCodigo();
                $pergunta->getRespostas();
                $pergunta->getRespostaAvaliacao();
            }
        }

        return $this;
    }
}
