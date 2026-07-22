<?php

namespace ECidade\Integracao\Sped\Common\Avaliacao;

use Avaliacao;
use AvaliacaoAdapter;
use AvaliacaoGrupo;
use AvaliacaoPergunta;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

/**
 * Class AvaliacaoSpedAdapter
 * @package ECidade\Integracao\Sped\Common\Avaliacao
 */
class AvaliacaoSpedAdapter extends AvaliacaoAdapter
{
    /**
     * @var EventoAbstract
     */
    private $evento;
    /**
     * @var bool
     */
    private $usaSugestoes = false;

    /**
     * AvaliacaoSpedAdapter constructor.
     * @param Avaliacao $avaliacao
     * @param EventoAbstract $evento
     */
    public function __construct(Avaliacao $avaliacao, EventoAbstract $evento)
    {
        parent::__construct($avaliacao);

        $this->evento = $evento;
    }

    /**
     * @return bool
     */
    public function usaSugestoes()
    {
        return $this->usaSugestoes;
    }

    /**
     * @param bool $usaSugestoes
     */
    public function setUsaSugestoes($usaSugestoes)
    {
        $this->usaSugestoes = $usaSugestoes;
    }

    /**
     * @param stdClass $parametros
     */
    public function definirCodigoGrupoResposta(stdClass $parametros)
    {
        parent::setCodigoGrupoResposta($this->evento->buscarCodigoPreenchimento($parametros));
    }

    /**
     * @param AvaliacaoGrupo $avaliacaoGrupo
     * @return array
     * @throws Exception
     */
    protected function getPerguntas(AvaliacaoGrupo $avaliacaoGrupo)
    {
        $perguntas = array();

        foreach ($avaliacaoGrupo->getPerguntas() as $avaliacaoPergunta) {
            $avaliacaoPergunta->getRespostas();
            $respostas = $this->consultaRespostasPergunta($avaliacaoPergunta);
            $avaliacaoPergunta->setResposta($respostas);

            $pergunta = new stdClass();
            $pergunta->codigo = $avaliacaoPergunta->getCodigo();
            $pergunta->id = $avaliacaoPergunta->getIdentificador();
            $pergunta->label = $avaliacaoPergunta->getDescricao();
            $pergunta->tipo_resposta = $avaliacaoPergunta->getTipo();
            $pergunta->tipo = $avaliacaoPergunta->getTipoComponente();
            $pergunta->ordem = 1;
            $pergunta->obrigatoria = $avaliacaoPergunta->isObrigatoria();
            $pergunta->ativo = $avaliacaoPergunta->isAtivo();
            $pergunta->formato = $avaliacaoPergunta->getCodigoFormula();
            $pergunta->mascara = $avaliacaoPergunta->getMascara();
            $pergunta->respostas = $this->getRespostas($avaliacaoPergunta);
            $pergunta->identificador_campo = $avaliacaoPergunta->getIdentificadorCampo();

            $perguntas[] = $pergunta;
        }
        return $perguntas;
    }

    /**
     * @param AvaliacaoPergunta $avaliacaoPergunta
     * @return array
     * @throws Exception
     */
    protected function consultaRespostasPergunta(AvaliacaoPergunta $avaliacaoPergunta)
    {
        $respostas = $this->evento->getRespostasPergunta(
            $avaliacaoPergunta,
            $this->iCodigoGrupoResposta,
            $this->camposParaConsultaDasRespostas($avaliacaoPergunta)
        );

        if (empty($respostas) && $this->usaSugestoes) {
            $respostas = $this->evento->getSugestaoRespostaDaPergunta($avaliacaoPergunta);
        }

        return $respostas ?: array();
    }
}