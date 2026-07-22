<?php namespace ECidade\RecursosHumanos\RH\Recadastramento;

use ECidade\RecursosHumanos\RH\Recadastramento\conversorJson\Formatter;
use processoOuvidoria;

class Processar
{

    private $atendimentoNumero;
    private $atendimentoAno;
    private $matricula;
    private $instituicao;
    private $instituicaoMatricula;

    /**
     * @return mixed
     */
    public function getAtendimentoNumero()
    {
        return $this->atendimentoNumero;
    }

    /**
     * @param mixed $atendimentoNumero
     */
    public function setAtendimentoNumero($atendimentoNumero)
    {
        $this->atendimentoNumero = $atendimentoNumero;
    }

    /**
     * @return mixed
     */
    public function getAtendimentoAno()
    {
        return $this->atendimentoAno;
    }

    /**
     * @param mixed $atendimentoAno
     */
    public function setAtendimentoAno($atendimentoAno)
    {
        $this->atendimentoAno = $atendimentoAno;
    }

    /**
     * @return mixed
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param mixed $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return mixed
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param mixed $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return mixed
     */
    public function getInstituicaoMatricula()
    {
        return $this->instituicaoMatricula;
    }

    /**
     * @param mixed $instituicaoMatricula
     */
    public function setInstituicaoMatricula($instituicaoMatricula)
    {
        $this->instituicaoMatricula = $instituicaoMatricula;
    }


    private function validate()
    {
        if (empty($this->getAtendimentoNumero())) {
            throw new \Exception("Número do atendimento não informado!");
        }

        if (empty($this->getAtendimentoAno())) {
            throw new \Exception("Ano do atendimento não informado!");
        }

        if (empty($this->getMatricula())) {
            throw new \Exception("Matrícula não infomada");
        }

        if (empty($this->getInstituicao())) {
            throw new \Exception("Instituição não infomada");
        }

        require_once(modification(ECIDADE_PATH . 'model/recursosHumanos/ProcessamentoRecadastramento.model.php'));

        $processamento = \ProcessamentoRecadastramento::findByMatriculaAprovada($this->getMatricula());
        if ($processamento instanceof \ProcessamentoRecadastramento) {
            $data = new \DateTime($processamento->getData());
            throw new \Exception(
                "A matricula {$this->getMatricula()} já foi processada com sucesso no dia "
                . " {$data->format('d/m/Y H:i:s')}"
            );
        }
    }

    public function run()
    {
        require_once(modification(ECIDADE_PATH . 'model/ouvidoria/AtendimentoOuvidoria.model.php'));
        $this->validate();

        $ouvidoriaAtendimento = \AtendimentoOuvidoria::findByNumeroAnoInstituicao(
            $this->getAtendimentoNumero(),
            $this->getAtendimentoAno(),
            $this->getInstituicao()
        );

        if (!$ouvidoriaAtendimento) {
            throw new \Exception(
                "Atendimento não encontrado! {$this->getAtendimentoNumero()}/{$this->getAtendimentoAno()}"
            );
        }

        $ouvidoriaAtendimentoAtendimento = $ouvidoriaAtendimento->atendimentoProcessoEletronico();

        if (!$ouvidoriaAtendimentoAtendimento) {
            throw new \Exception("Atendimento não é eletrônico");
        }

        $jsonAtendimento = $ouvidoriaAtendimentoAtendimento->getInformacoesProcesso();

        db_inicio_transacao();
        try {
            $formObject = new Formatter($jsonAtendimento);
            $secao = $formObject->getSecao("aposentado_pensionista");

            if (!$secao) {
                new ServidorAtivo(
                    $this->getMatricula(),
                    $formObject,
                    $ouvidoriaAtendimento,
                    $this->getInstituicaoMatricula()
                );
            } else {
                new ServidorAposentado(
                    $this->getMatricula(),
                    $formObject,
                    $ouvidoriaAtendimento,
                    $this->getInstituicaoMatricula()
                );
            }
            $this->saveProcessamentoAtendimento($ouvidoriaAtendimento->getId());
            db_fim_transacao(false);
        } catch (\Exception $e) {
            db_fim_transacao(true);
            $this->saveProcessamentoAtendimento(
                $ouvidoriaAtendimento->getId(),
                false,
                $e->getMessage()
            );
            throw new \Exception($e->getMessage());
        }
    }

    protected function saveProcessamentoAtendimento(
        $idAtendimento = null,
        $erro_status = true,
        $erro = ""
    ) {
        if (empty($idAtendimento)) {
            return;
        }

        $processoOuvidoria = processoOuvidoria::findByAtendimento($idAtendimento);
        $processo = $processoOuvidoria->getProcesso()->getCodProcesso();

        $processamento = new \ProcessamentoRecadastramento();
        $processamento->setMatricula($this->getMatricula());
        $processamento->setProcesso($processo);
        $processamento->setStatus($erro_status == true ? "t" : "f");
        $processamento->setErro(addslashes($erro));
        $processamento->setInstituicao($this->getInstituicao());
        $processamento->setUsuario(db_getsession('DB_id_usuario'));
        $processamento->setData(date('Y-m-d H:i:s'));
        $processamento->save();
    }
}
