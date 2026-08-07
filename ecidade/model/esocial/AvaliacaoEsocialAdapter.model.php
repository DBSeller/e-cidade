<?php
/**
 *         E-cidade Software Publico para Gestao Municipal
 *      Copyright (C) 2009  DBSeller Servicos de Informatica
 *                       www.dbseller.com.br
 *                    e-cidade@dbseller.com.br
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

class AvaliacaoEsocialAdapter extends AvaliacaoAdapter
{
    /**
     * @var bool
     */
    private $previsaoReceita;
    /**
     * Servidor
     * @var Servidor
     */
    private $oServidor;

    /**
     * Servidor
     * @var Servidor
     */
    private $oServidorAlteracao;

    /**
     * Servidor
     * @var Servidor
     */
    private $oServidorSemVinculoTermino;

    /**
     * @var CgmFisico|CgmJuridico
     */
    private $oCgm;

    /**
     * Rotina deve trazer os dados Sugeridos.
     * @var bool
     */
    private $lTrazerSugestoes = false;

    /**
     * Se for lotacao busca o CgmFisico|CgmJuridico
     * Lotacao = CgmFisico|CgmJuridico
     * @var bool
     */
    private $lLotacao = false;

    /**
     * Se for Processo busca o CgmFisico|CgmJuridico
     * Processo = CgmFisico|CgmJuridico
     * @var bool
     */
    private $lProcesso = false;

    /**
     * Se for Obras busca o CgmFisico|CgmJuridico
     * @var bool
     */
    private $lObras = false;

    /**
     * Se for Cat busca o CgmFisico|CgmJuridico
     * @var bool
     */
    private $lCat = false;

    /**
     * Se for Admissão Preliminar busca a matricula
     * @var bool
     */
    private $lAdmissaoPreliminar = false;

    /**
     * Se estivermos buscando os dados do layout de Trabalho intermitente
     * @var bool
     */
    private $trabalhoIntermitente = false;

    /**
     * Se estivermos buscando os dados do layout de Aviso Previo
     * @var bool
     */
    private $avisoPrevio = false;

    /**
     * Se estivermos buscando os dados do layout de rescisao
     * @var bool
     */
    private $rescisao = false;
    private $reintegracao = false;
    private $alteracaoContratual = false;
    private $alteracaoTSVE = false;

    /**
     * Busca do layout s1200 Remuneracao RGPS
     * @var bool
     */
    private $remuneracaoRGPS = false;

    /**
     * Define o codigo do Servidor
     * @param integer
     */
    public function setServidor($oServidor)
    {
        $this->oServidor = $oServidor;
    }

    /**
     * Define o codigo do Servidor
     * @param integer
     */
    public function setServidorSemVinculoTermino($oServidor)
    {
        $this->oServidorSemVinculoTermino = $oServidor;
    }


    /**
     * Define o codigo do Servidor
     * @param integer
     */
    public function setAlteraServidor($oServidor)
    {
        $this->oServidorAlteracao = $oServidor;
    }

    public function trazerSugestoes($lTrazeSugestoes = false)
    {
        $this->lTrazerSugestoes = $lTrazeSugestoes;
    }

    /**
     * Define o Cgm
     * @param \CgmBase $oCgm
     */
    public function setCgm(CgmBase $oCgm)
    {
        $this->oCgm = $oCgm;
    }

    public function setReintegracao($reintegracao)
    {
        $this->reintegracao = $reintegracao;
    }


    public function setAlteracaoContratual($alteracaoContratual)
    {
        $this->alteracaoContratual = $alteracaoContratual;
    }

    public function setAlteracaoTSVE($alteracaoTSVE)
    {
        $this->alteracaoTSVE = $alteracaoTSVE;
    }

    /**
     * Retorna o codigo do Servidor
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->oServidor;
    }

    /**
     * Retorna o codigo do Servidor
     * @return Servidor
     */
    public function getAlteracaoServidor()
    {
        return $this->oServidorAlteracao;
    }

    /**
     * Retorna o codigo do Servidor
     * @return Servidor
     */
    public function getServidorSemVinculoTermino()
    {
        return $this->oServidorSemVinculoTermino;
    }

    /**
     * @return bool
     */
    public function isPrevisaoReceita()
    {
        return $this->previsaoReceita;
    }

    /**
     * @param bool $previsaoReceita
     */
    public function setPrevisaoReceita($previsaoReceita)
    {
        $this->previsaoReceita = $previsaoReceita;
    }

    protected function getPerguntas(\AvaliacaoGrupo $avaliacaoGrupo)
    {
        $aPerguntas = array();

        foreach ($avaliacaoGrupo->getPerguntas() as $pergunta) {
            $pergunta->getRespostas();

            $aRespostas = $this->consultaRespostasPergunta($pergunta);
            $pergunta->setResposta($aRespostas);

            $oPergunta = new \StdClass();
            $oPergunta->codigo = $pergunta->getCodigo();
            $oPergunta->id = $pergunta->getIdentificador();
            $oPergunta->label = $pergunta->getDescricao();
            $oPergunta->tipo_resposta = $pergunta->getTipo();
            $oPergunta->tipo = $pergunta->getTipoComponente();//$pergunta->getCodigo();
            $oPergunta->ordem = 1;
            $oPergunta->obrigatoria = $pergunta->isObrigatoria();
            $oPergunta->ativo = $pergunta->isAtivo();
            $oPergunta->formato = $pergunta->getCodigoFormula();
            $oPergunta->mascara = $pergunta->getMascara();
            $oPergunta->respostas = $this->getRespostas($pergunta);
            $oPergunta->identificador_campo = $pergunta->getIdentificadorCampo();
            $oPergunta->somente_leitura = $pergunta->somenteLeitura();
            $aPerguntas[] = $oPergunta;
        }
        return $aPerguntas;
    }

    /**
     * Consulta na base de dados as respostas
     * para o questionário por servidor
     */
    protected function consultaRespostasPergunta(AvaliacaoPergunta $oPergunta)
    {
        $sSqlRespostas = $this->getConsultaParaAsRespostasDaPergunta($oPergunta);

        $aResposta = array();
        if (!empty($sSqlRespostas)) {
            $rsRespostas = db_query($sSqlRespostas);

            if (!$rsRespostas) {
                throw new DBException("Ocorreu um erro ao consultar as respostas da pergunta: \n" . $oPergunta->getDescricao());
            }

            if (pg_num_rows($rsRespostas) > 0) {
                $aResposta = db_utils::makeCollectionFromRecord($rsRespostas, function ($oResposta) {
                    $oStdResposta = new StdClass();
                    $oStdResposta->codigoresposta = $oResposta->db106_avaliacaoperguntaopcao;
                    $oStdResposta->textoresposta = $oResposta->db106_resposta;
                    return $oStdResposta;
                });
            }
        }
        if ((count($aResposta) == 0 && $this->lTrazerSugestoes) || $oPergunta->somenteLeitura()) {
            $aResposta = $this->getSugestaoRespostaDaPergunta($oPergunta);
        }
        return $aResposta;
    }

    /**
     * Retorna as sugestoes das perguntas
     * @param \AvaliacaoPergunta $oPergunta
     * @return string
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getSugestaoRespostaDaPergunta(AvaliacaoPergunta $oPergunta)
    {
        $aRespostas = array();

        $sSqlFormulaPergunta = $oPergunta->getFormulaVinculada();
        if (empty($sSqlFormulaPergunta)) {
            return $aRespostas;
        }
        $oFormulaEsocialSugestaoResposta = $this->getContextoFormula();
        if (empty($oFormulaEsocialSugestaoResposta)) {
            return $aRespostas;
        }

        $sSqlFormulaPergunta = 'SELECT substring(ROW(sugestoes.*)::varchar, \'^\\\("*(.*?)"*\\\)$\') AS sugestao FROM [' . $sSqlFormulaPergunta . '] AS sugestoes';
        $sSqlRespostas = $oFormulaEsocialSugestaoResposta->parse($sSqlFormulaPergunta);
        $rsRespostas = db_query("{$sSqlRespostas}");
        if (!$rsRespostas) {
            throw new DBException("Erro ao excutar fórmula {$sSqlRespostas}, para a pergunta {$oPergunta->getDescricao()}.");
        }

        $aRespostas = db_utils::makeCollectionFromRecord($rsRespostas, function ($oResposta) use ($oPergunta) {

            $iCodigoResposta = '';
            $sTextoResposta = '';
            switch ($oPergunta->getTipo()) {
                case AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA:
                    $iCodigoResposta = $oResposta->sugestao;
                    $sTextoResposta = 1;
                    break;
                case AvaliacaoPergunta::TIPO_RESPOSTA_DISSERTATIVA:
                    $aRespostaPergunta = $oPergunta->getRespostas();
                    $iCodigoResposta = $aRespostaPergunta[0]->codigoresposta;
                    $sTextoResposta = $oResposta->sugestao;
                    break;
                case AvaliacaoPergunta::TIPO_RESPOSTA_MULTIPLA:
                    $sTextoResposta = 1;
                    $iCodigoResposta = $oResposta->sugestao;
                    break;
            }

            $oStdResposta = new \stdClass();
            $oStdResposta->codigoresposta = $iCodigoResposta;
            $oStdResposta->textoresposta = $sTextoResposta;
            return $oStdResposta;
        });
        return $aRespostas;
    }

    /**
     * @return \DBFormulaCGM|\DBFormulaMatricula|null
     */
    private function getContextoFormula()
    {
        $oContextoMatricula = $this->getContextoMatricula();
        if (!empty($oContextoMatricula)) {
            return $oContextoMatricula;
        }
        $oContextoCgm = $this->getContextoCgm();
        if (!empty($oContextoCgm)) {
            return $oContextoCgm;
        }
        return null;
    }

    /**
     * @return \DBFormulaMatricula|null
     */
    private function getContextoMatricula()
    {
        if (!empty($this->oServidor)) {
            $oFormulaEsocialSugestaoResposta = new DBFormulaMatricula($this->getServidor());
            $oFormulaEsocialSugestaoResposta->adicionarVariavelServidor('ESOCIAL_MATRICULA_SERVIDOR');
            $oFormulaEsocialSugestaoResposta->adicionar('ESOCIAL_INSTITUICAO', InstituicaoRepository::getInstituicaoSessao()->getCodigo());
            $oFormulaEsocialSugestaoResposta->adicionar('CODIGO_CGM', $this->getServidor()->getCgm()->getCodigo());
            return $oFormulaEsocialSugestaoResposta;
        }
        return null;
    }

    /**
     * @return \DBFormulaCGM|null
     */
    private function getContextoCgm()
    {
        if (!empty($this->oCgm)) {
            $oFormulaEsocialSugestaoResposta = new DBFormulaCGM($this->oCgm);
            $oFormulaEsocialSugestaoResposta->adicionar('CODIGO_CGM', $this->oCgm->getCodigo());
            return $oFormulaEsocialSugestaoResposta;
        }
        return null;
    }

    private function getConsultaParaAsRespostasDaPergunta(AvaliacaoPergunta $oPergunta)
    {
        if ($this->reintegracao) {
            return $this->getConsultaParaRespostaReintegracao($oPergunta);
        }
        if ($this->alteracaoContratual) {
            return $this->getConsultaParaRespostaAlteracaoContratual($oPergunta);
        }
        if ($this->alteracaoTSVE) {
            return $this->getConsultaParaRespostaAlteracaoTSVE($oPergunta);
        }
        if ($this->trabalhoIntermitente) {
            return $this->getConsultaParaRespostaTrabalhoIntermitente($oPergunta);
        }
        if ($this->rescisao) {
            return $this->getConsultaParaRespostaRescisao($oPergunta);
        }
        if ($this->avisoPrevio) {
            return $this->getConsultaParaRespostaAvisoPrevio($oPergunta);
        }
        if (!empty($this->oServidor)) {
            if ($this->oServidor->temVinculoEmpregaticio()) {
                return $this->getConsultaParaRespostasDaMatricula($oPergunta);
            } else {
                return $this->getConsultaParaRespostasDoTrabalhador($oPergunta);
            }
        }

        if (!empty($this->oServidorSemVinculoTermino)) {
            return $this->getConsultaParaRespostasDoTrabalhadorTermino($oPergunta);
        }

        if (!empty($this->oServidorAlteracao)) {
            return $this->getConsultaParaRespostasDaAlteracaoMatricula($oPergunta);
        }

        if (!empty($this->lLotacao)) {
            return $this->getConsultaParaRespostasDaLotacao($oPergunta);
        }
        if (!empty($this->lProcesso)) {
            return $this->getConsultaParaRespostasDoProcesso($oPergunta);
        }
        if (!empty($this->lObras)) {
            return $this->getConsultaParaRespostasObras($oPergunta);
        }
        if (!empty($this->lCat)) {
            return $this->getConsultaParaRespostasCat($oPergunta);
        }

        if($this->lAdmissaoPreliminar) {
            return $this->getConsultaParaRespostasAdmissaoPreliminar($oPergunta);
        }

        if (!empty($this->oCgm)) {
            return $this->getConsultaParaRespostasDoCgm($oPergunta);
        }

        if (!empty($this->previsaoReceita)) {
            return $this->getConsultaParaRespostaPrevisaoReceita($oPergunta);
        }

        if($this->remuneracaoRGPS) {
            return $this->getConsultaParaRespostaRemuneracaoRGPS($oPergunta);
        }
    }

    private function getConsultaParaRespostasDaMatricula(AvaliacaoPergunta $oPergunta)
    {
        $oDaoAvaliacaoGrupoRespostaServidor = new cl_avaliacaogruporespostarhpessoal();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaServidor->buscaRespostasPorPerguntaMatricula(
            $oPergunta->getCodigo(),
            $this->getServidor()->getMatricula(),
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlRespostas;
    }

    private function getConsultaParaRespostasDoCgm(AvaliacaoPergunta $oPergunta)
    {
        $oDaoAvaliacaoGrupoRespostaServidor = new cl_avaliacaogruporespostacgm();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaServidor->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->oCgm->getCodigo(),
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    public function setLotacao($lLotacao)
    {
        $this->lLotacao = $lLotacao;
    }

    public function getLotacao()
    {
        return $this->lLotacao;
    }

    private function getConsultaParaRespostasDaLotacao(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }        
        $oDaoAvaliacaoGrupoRespostaLotacao = new cl_avaliacaogruporespostalotacao();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaLotacao->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    public function setProcesso($lProcesso)
    {
        $this->lProcesso = $lProcesso;
    }

    public function getProcesso()
    {
        return $this->lProcesso;
    }

    public function setObras($lObras)
    {
        $this->lObras = $lObras;
    }

    public function getObras()
    {
        return $this->lObras;
    }

    public function setCat($lCat)
    {
        $this->lCat = $lCat;
    }

    public function getCat()
    {
        return $this->lCat;
    }

    private function getConsultaParaRespostasDoProcesso(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }
        $oDaoAvaliacaoGrupoRespostaProcesso = new cl_avaliacaogruporespostaprocesso();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaProcesso->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    private function getConsultaParaRespostasObras(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }
        $oDaoAvaliacaoGrupoRespostaObras = new cl_avaliacaogruporespostaobras();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaObras->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    private function getConsultaParaRespostasAdmissaoPreliminar(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }
        $oDaoAvaliacaoGrupoRespostaObras = new cl_avaliacaogruporespostaadmissaopreliminar();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaObras->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    private function getConsultaParaRespostasCat(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }
        $oDaoAvaliacaoGrupoRespostaCat = new cl_esoacidentetrabalho();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaCat->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );
        return $sSqlRespostas;
    }

    /**
     * Define se o estamos buscando os dados do trabalho intermitente
     * @param bool $trabalhoIntermitente
     */
    public function setTrabalhoIntermitente($trabalhoIntermitente)
    {
        $this->trabalhoIntermitente = $trabalhoIntermitente;
    }

    /**
     * @return bool
     */
    public function isTrabalhoIntermitente()
    {
        return $this->trabalhoIntermitente;
    }

    /**
     * Busca as respostas de uma pergunta
     * @param AvaliacaoPergunta $oPergunta
     * @return null|string
     */
    private function getConsultaParaRespostaTrabalhoIntermitente(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostatrabintermitente();
        $sql = $dao->buscarRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sql;
    }

    /**
     * Define se o estamos buscando os dados do Aviso Previo
     * @param $avisoPrevio
     */
    public function setAvisoPrevio($avisoPrevio)
    {
        $this->avisoPrevio = $avisoPrevio;
    }

    /**
     * @return bool
     */
    public function isAvisoPrevio()
    {
        return $this->avisoPrevio;
    }

    /**
     * Define se o estamos buscando os dados do Aviso Previo
     * @param $avisoPrevio
     */
    public function setRescisao($rescisao)
    {
        $this->rescisao = $rescisao;
    }

    /**
     * @return bool
     */
    public function isResisao()
    {
        return $this->rescisao;
    }

    public function setRemuneracaoRGPS($remuneracaoRGPS)
    {
        $this->remuneracaoRGPS = $remuneracaoRGPS;
    }

    public function setAdmissaoPreliminar($lAdmissaoPreliminar)
    {
        $this->lAdmissaoPreliminar = $lAdmissaoPreliminar;
    }

    /**
     * Busca as respostas de uma pergunta
     * @param AvaliacaoPergunta $oPergunta
     * @return null|string
     */
    private function getConsultaParaRespostaAvisoPrevio(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostaavisoprevio();
        $sSqlResposta = $dao->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlResposta;
    }

    /**
     * Busca as respostas de uma pergunta
     * @param AvaliacaoPergunta $oPergunta
     * @return null|string
     */
    private function getConsultaParaRespostaRescisao(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostarhpesrescisao();
        $sSqlResposta = $dao->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlResposta;
    }


    private function getConsultaParaRespostaPrevisaoReceita(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostaconta();
        $sSqlResposta = $dao->buscaRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlResposta;
    }

    /**
     * @param AvaliacaoPergunta $oPergunta
     */
    private function getConsultaParaRespostasDaAlteracaoMatricula(AvaliacaoPergunta $oPergunta)
    {
        $oDaoAvaliacaoGrupoRespostaServidor = new cl_avaliacaogruporespostarhpessoalalteracao();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaServidor->buscaRespostasPorPerguntaMatricula(
            $oPergunta->getCodigo(),
            $this->getAlteracaoServidor()->getMatricula(),
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlRespostas;
    }

    /**
     * @param $oPergunta
     * @return string
     */
    private function getConsultaParaRespostasDoTrabalhador($oPergunta)
    {
        $oDaoAvaliacaoGrupoRespostaTrabalhador = new cl_avaliacaogruporespostatsveinicial();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaTrabalhador->buscaRespostasPorPerguntaMatricula(
            $oPergunta->getCodigo(),
            $this->getServidor()->getMatricula(),
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlRespostas;
    }

    /**
     * @param $oPergunta
     * @return string
     */
    private function getConsultaParaRespostasDoTrabalhadorTermino($oPergunta)
    {
        $oDaoAvaliacaoGrupoRespostaTrabalhador = new cl_avaliacaogruporespostatertrabasemvinc();
        $sSqlRespostas = $oDaoAvaliacaoGrupoRespostaTrabalhador->buscaRespostasPorPerguntaMatricula(
            $oPergunta->getCodigo(),
            $this->getServidorSemVinculoTermino()->getMatricula(),
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sSqlRespostas;
    }

    /**
     * Busca as respostas de uma pergunta
     * @param AvaliacaoPergunta $oPergunta
     * @return null|string
     */
    private function getConsultaParaRespostaReintegracao(AvaliacaoPergunta $oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostareintegracao();
        $sql = $dao->buscarRespostasPorPergunta(
            $oPergunta->getCodigo(),
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sql;
    }


    private function getConsultaParaRespostaAlteracaoContratual($oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostaaltercontratual();
        $where = array();
        $where[] = " db103_sequencial = {$oPergunta->getCodigo()} ";
        $where[] = " db107_sequencial = {$this->iCodigoGrupoResposta} ";
        $sql = $dao->buscarRespostasPreenchimento(
            array($this->camposParaConsultaDasRespostas($oPergunta)),
            $where
        );

        return $sql;
    }

    private function getConsultaParaRespostaAlteracaoTSVE($oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostatsvealteracao();
        $where = array();
        $where[] = " db103_sequencial = {$oPergunta->getCodigo()} ";
        $where[] = " db107_sequencial = {$this->iCodigoGrupoResposta} ";
        $sql = $dao->buscarRespostasPreenchimento(
          array($this->camposParaConsultaDasRespostas($oPergunta)),
          $where
        );

        return $sql;
    }

    private function getConsultaParaRespostaRemuneracaoRGPS($oPergunta)
    {
        if (empty($this->iCodigoGrupoResposta)) {
            return null;
        }

        $daoRemuneracaoRGPS = new cl_avaliacaogruporespostaremuneracaorgps();
        $sqlRemuneracaoRGPS = $daoRemuneracaoRGPS->buscarRespostasPorPergunta(
          $oPergunta->getCodigo(),
          $this->iCodigoGrupoResposta,
          $this->camposParaConsultaDasRespostas($oPergunta)
        );

        return $sqlRemuneracaoRGPS;
    }
}
