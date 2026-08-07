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
class AvaliacaoAdapter
{
    private $oAvaliacao;

    protected $iCodigoGrupoResposta;

    public function __construct(\Avaliacao $oAvaliacao)
    {
        $this->oAvaliacao = $oAvaliacao;
    }

    public function getObject()
    {
        $oFormulario = new \stdClass();
        $oFormulario->codigo = $this->oAvaliacao->getCodigo();
        $oFormulario->id = $this->oAvaliacao->getIdentificador();
        $oFormulario->label = $this->oAvaliacao->getDescricao();
        $oFormulario->observacao = $this->oAvaliacao->getObservacao();
        $oFormulario->grupos = $this->getGrupos();
        return (object) $oFormulario;
    }

    private function getGrupos()
    {
        $aGruposPerguntas = $this->oAvaliacao->getGruposPerguntas();
        $aDadosGruposPerguntas = array();
        foreach ($aGruposPerguntas as $oGrupoPergunta) {
            $oGrupo = new \stdClass();
            $oGrupo->codigo = $oGrupoPergunta->getCodigo();
            $oGrupo->id = $oGrupoPergunta->getIdentificador();
            $oGrupo->label = $oGrupoPergunta->getDescricao();
            $oGrupo->identificador_campo = $oGrupoPergunta->getIdentificadorCampo();
            $oGrupo->perguntas = $this->getPerguntas($oGrupoPergunta);
            $aDadosGruposPerguntas[] = $oGrupo;
        }
        return $aDadosGruposPerguntas;
    }

    protected function getPerguntas(\AvaliacaoGrupo $avaliacaoGrupo)
    {
        $aPerguntas = array();

        foreach ($avaliacaoGrupo->getPerguntas() as $pergunta) {
            $pergunta->setAvaliacao($this->getCodigoGrupoResposta());
            $oPerguntaRetorno = new \StdClass();
            $oPerguntaRetorno->codigo = $pergunta->getCodigo();
            $oPerguntaRetorno->id = $pergunta->getIdentificador();
            $oPerguntaRetorno->label = $pergunta->getDescricao();
            $oPerguntaRetorno->tipo_resposta = $pergunta->getTipo();
            $oPerguntaRetorno->tipo = $pergunta->getTipoComponente(); //$pergunta->getCodigo();
            $oPerguntaRetorno->ordem = 1; //$pergunta->getCodigo();
            $oPerguntaRetorno->obrigatoria = $pergunta->isObrigatoria();
            $oPerguntaRetorno->ativo = $pergunta->isAtivo();
            $oPerguntaRetorno->formato = $pergunta->getCodigoFormula();
            $oPerguntaRetorno->mascara = $pergunta->getMascara();
            $oPerguntaRetorno->respostas = $this->getRespostas($pergunta);
            $oPerguntaRetorno->identificador_campo = $pergunta->getIdentificadorCampo();
            $oPerguntaRetorno->somente_leitura = $pergunta->somenteLeitura();
            $aPerguntas[] = $oPerguntaRetorno;
        }

        return $aPerguntas;
    }

    protected function getRespostas(\AvaliacaoPergunta $avaliacaoPergunta)
    {
        $respostas = array();

        foreach ($avaliacaoPergunta->getRespostas() as $resposta) {
            $oResposta = new \stdClass();
            $oResposta->codigo = $resposta->codigoresposta;
            $oResposta->id = $resposta->identificador;
            $oResposta->identificador_campo = $resposta->identificadorcampo;
            $oResposta->peso = $resposta->peso;
            $oResposta->valor = $resposta->textoresposta;
            $oResposta->valorresposta = $resposta->valorresposta;
            $oResposta->permiteTexto = $resposta->texto;
            $oResposta->label = $resposta->descricaoresposta;

            $respostas[] = $oResposta;
        }

        return $respostas;
    }

    /**
     * @param AvaliacaoPergunta $oPergunta
     * @return string
     */
    protected function camposParaConsultaDasRespostas(AvaliacaoPergunta $oPergunta)
    {
        $sCamposRespostas = "db106_avaliacaoperguntaopcao,";
        $sCamposRespostas .= "  case when db106_resposta = ''and db103_avaliacaotiporesposta <> 2 ";
        $sCamposRespostas .= "       then ";
        $sCamposRespostas .= "          case when db103_avaliacaotiporesposta = 1 and db104_sequencial in (select db104_sequencial from avaliacaopergunta inner join avaliacaoperguntaopcao on db104_avaliacaopergunta = db103_sequencial inner join avaliacaoresposta on db106_avaliacaoperguntaopcao = db104_sequencial where db103_sequencial = {$oPergunta->getCodigo()} order by db106_sequencial desc limit 1)";
        $sCamposRespostas .= "               then '1'";
        $sCamposRespostas .= "               else '1'";
        $sCamposRespostas .= "          end";
        $sCamposRespostas .= "       else db106_resposta ";
        $sCamposRespostas .= "   end as db106_resposta";
        return $sCamposRespostas;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoResposta()
    {
        return $this->iCodigoGrupoResposta;
    }

    /**
     * Representa o preenchimento
     * @param mixed $iCodigoGrupoResposta
     */
    public function setCodigoGrupoResposta($iCodigoGrupoResposta)
    {
        $this->iCodigoGrupoResposta = $iCodigoGrupoResposta;
    }
}
