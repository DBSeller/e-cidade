<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\MatriculaRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ArquivoLayoutService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ArquivoTxtService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\LinhaService;
use ECidade\Tributario\Cadastro\Repository\CfiptuRepository;

final class EmissaoService extends Service
{
    private $arquivoTxtService;

    private $linhaService;

    private $matriculaRepository;

    private $cfiptuRepository;

    private $arquivoLayoutService;

    private $bar;

    private $dataBase;

    private $instituicao;

    public function __construct(
        $dataBase,
        ArquivoTxtService $arquivoTxtService,
        LinhaService $linhaService,
        MatriculaRepository $matriculaRepository,
        CfiptuRepository $cfiptuRepository,
        ArquivoLayoutService $arquivoLayoutService
    ) {
        $this->dataBase = $dataBase;
        $this->arquivoTxtService = $arquivoTxtService;
        $this->linhaService = $linhaService;
        $this->matriculaRepository = $matriculaRepository;
        $this->cfiptuRepository = $cfiptuRepository;
        $this->arquivoLayoutService = $arquivoLayoutService;
    }

    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return \Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Instituicao $instituicao
     */
    public function setInstituicao(\Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }



    private function barSetMessageLog($m)
    {
        if (!empty($this->bar)) {
            $this->bar->setMessageLog($m);
        }
    }

    private function barUpdateMaxProgress($m)
    {
        if (!empty($this->bar)) {
            $this->bar->updateMaxProgress($m);
        }
    }

    private function barUpdatePercentual($p)
    {
        if (!empty($this->bar)) {
            $this->bar->updatePercentual($p);
        }
    }

    public function execute(Filtro $filtro, $session, $path)
    {
        $timeInicio = microtime(true);

        $this->barSetMessageLog("Consultando matriculas na base de dados.");

        $matriculas =
            $this->matriculaRepository->findAll(
                $filtro->getAno(),
                $filtro->getQuantidade(),
                $filtro->getMatriculas(),
                $filtro->getQuantidadeParcela()
            );

        $total = $matriculas->count();

        $this->barUpdateMaxProgress($total);

        $this->barSetMessageLog("Carregando configurações do IPTU.");

        $irregular = 0;
        $bloco = 100;
        $sequencial = 1;

        $this->barSetMessageLog("Processando geração do arquivo de um total de ".$total." matriculas.");

        $this->barSetMessageLog("Criando arquivo em disco.");

        $this->arquivoTxtService->create($path);

        $this->barSetMessageLog("Procesando dados das matriculas.");

        foreach ($matriculas as $matricula) {
            $linha = $this->linhaService->execute($sequencial, $matricula, $filtro, $this->getInstituicao());

            if (empty($linha)) {
                $irregular++;
                $this->barUpdatePercentual($sequencial + $irregular);
                continue;
            }

            $this->arquivoTxtService->addline($linha);

            if ($sequencial % $bloco === 0) {
                $this->barSetMessageLog(
                    "Escrevendo no arquivo em disco um bloco de ".$bloco." matriculas. Restante no processamento "
                    .($total - $sequencial)."."
                );

                $microtimePrevisao = ((microtime(true) - $timeInicio) * $total) / $sequencial;

                $previsao = DateTime::createFromFormat('U.u', $timeInicio + $microtimePrevisao);

                $this->barSetMessageLog("Previsão de termino do processamento: ".$previsao->format("H:i:s d/m/Y"));

                $this->arquivoTxtService->write();
            }

            $this->barUpdatePercentual($sequencial + $irregular);

            $sequencial++;

            $this->dataBase->commit();
            $this->dataBase->begin();
        }

        $this->barSetMessageLog("Matriculas irregulares não incluidas no arquivo {$irregular}.");
        $this->arquivoTxtService->write();
        $this->barSetMessageLog("Finalizando escrita de arquivo em disco.");

        $this->barSetMessageLog("Criando arquivo de layout.");
        $this->arquivoLayoutService->execute()->getArquivo()->write();
        $this->barSetMessageLog("Finalizando escrita do arquivo de layout.");

        $this->barSetMessageLog("Processamento concluido com sucesso!");

        $inicio = DateTime::createFromFormat('U.u', $timeInicio);
        $fim = DateTime::createFromFormat('U.u', microtime(true));

        $this->barSetMessageLog("Hora de inicio: ".$inicio->format("H:i:s m/d/Y"));
        $this->barSetMessageLog("Hora do termino: ".$fim->format("H:i:s m/d/Y"));

        $this->dataBase->commit();

        return true;
    }
}
