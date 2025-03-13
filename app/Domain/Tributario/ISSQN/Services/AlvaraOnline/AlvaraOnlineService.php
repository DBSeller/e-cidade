<?php

namespace App\Domain\Tributario\ISSQN\Services\AlvaraOnline;

use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\GerarInscricao;
use ECidade\Tributario\Issqn\Acao\Transicao\Factory\AcaoFactory;
use ECidade\Tributario\Issqn\Model\ProcessoEletronicoGrauRisco;
use processoProtocolo;
use ECidade\V3\Extension\Registry;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\InscricaoInterface;

class AlvaraOnlineService
{
    /**
     * @var Processo
     */
    private $processo;

    /**
     * @var ineteger
     */
    private $inscricao;

    /**
     * @var \stdClass|null
     */
    private $camposAdicionais;

    /**
     * @var \stdClass
     */
    private $dados;

    /**
     * @param Processo $processo
     * @return AlvaraOnlineService
     */
    public function setProcesso($processo)
    {
        $this->processo = $processo;
        return $this;
    }

    /**
     * @param $inscricao
     * @return $this
     */
    public function setInscricao($inscricao)
    {
         $this->inscricao = $inscricao;
         return $this;
    }

    /**
     * @return ineteger
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * @param \stdClass|null $camposAdicionais
     * @return AlvaraOnlineService
     */
    public function setCamposAdicionais($camposAdicionais)
    {
        $this->camposAdicionais = $camposAdicionais;
        return $this;
    }

    /**
     * @param \stdClass $dados
     * @return AlvaraOnlineService
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
        return $this;
    }

    /**
     * Gera um alvara
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function gerar()
    {
        $processoEletronicoGrauRiscoRepository = Registry::get('app.container')
                                                         ->get('tributario.container')
                                                         ->get('ProcessoEletronicoGrauRiscoRepository');

        $processo = new processoProtocolo($this->processo->getCodigoProcesso());

        $acao = AcaoFactory::factory(AcaoFactory::ACAO_GERAR_INSCRICAO, $processo);

        $processoEletronicoGrauRisco = new ProcessoEletronicoGrauRisco();
        $processoEletronicoGrauRisco->fromState([
            "q151_processo" => $processo->getCodProcesso(),
            "q151_graurisco" => $acao->verificaCamposAdicionais(
                "atividades",
                "grauRisco",
                "B"
            )
        ]);
        $processoEletronicoGrauRiscoRepository->save($processoEletronicoGrauRisco);

        $acao->setCamposAdicionais($this->camposAdicionais);
        $acao->validate();
        $acao->run();

        $this->inscricao = $acao->getInscricao();

        $this->atualizaTabAtiv($acao);
    }

    public function alterar()
    {
        $processoEletronicoGrauRiscoRepository = Registry::get('app.container')
            ->get('tributario.container')
            ->get('ProcessoEletronicoGrauRiscoRepository');

        $processo = new processoProtocolo($this->processo->getCodigoProcesso());
        $processoEletronicoGrauRisco = new ProcessoEletronicoGrauRisco();
        $acao = AcaoFactory::factory(AcaoFactory::ACAO_ALTERAR_INSCRICAO, $processo);

        $processoEletronicoGrauRisco->fromState([
            "q151_processo" => $processo->getCodProcesso(),
            "q151_graurisco" => $acao->verificaCamposAdicionais(
                "atividades",
                "grauRisco",
                "B"
            )
        ]);
        $processoEletronicoGrauRiscoRepository->save($processoEletronicoGrauRisco);
        $acao->setInscricao($this->getInscricao());
        $acao->setCamposAdicionais($this->camposAdicionais);
        $acao->validate();
        $acao->run();

        $this->inscricao = $acao->getInscricao();

        $this->atualizaTabAtiv($acao);
    }

    private function atualizaTabAtiv(InscricaoInterface $acao)
    {
        $aCampoProvisorioPermanente = $acao->verificaCamposAdicionais(
            "atividades",
            "campoProvisorioPermanente",
            []
        );

        if (!empty($aCampoProvisorioPermanente)) {
            $cltabativ = new \cl_tabativ();

            foreach ($aCampoProvisorioPermanente as $oCampoProvisorioPermanente) {
                $oAtividade = $this->dados->atividades[$oCampoProvisorioPermanente->indice];

                $sSql = $cltabativ->sql_query_file(
                    null,
                    null,
                    "*",
                    null,
                    "q07_inscr = {$this->inscricao} and q07_ativ = {$oAtividade->atividade}"
                );

                $rResult = db_query($sSql);

                if (!$rResult) {
                    throw new \Exception("Erro ao buscar a atividade {$oAtividade->atividade}");
                }

                $oAtividade = \db_utils::fieldsMemory($rResult, 0);

                $cltabativ->q07_inscr = $oAtividade->q07_inscr;
                $cltabativ->q07_seq = $oAtividade->q07_seq;
                $cltabativ->q07_datafi = $oCampoProvisorioPermanente->valor;
                $cltabativ->q07_perman = !empty($oCampoProvisorioPermanente->valor) ? "false" : "true";
                $cltabativ->alterar($oAtividade->q07_inscr, $oAtividade->q07_seq);
            }
        }
    }
}
