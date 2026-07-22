<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use \DateTime;
use \ProgressBar;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Filtro;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\HeaderService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\DetalheService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\TrailerService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Contribuinte as ContribuinteRCB;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Repository\TipoDebito as TipoDebitoRepository;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\DataBase;
use ECidade\Library\File\FileService;

final class EmissaoService extends Service
{
    private $dataBase;
    private $arquivoTxtService;
    private $headerService;

    public function __construct(
        DataBase $dataBase,
        FileService $arquivoTxtService,
        HeaderService $headerService,
        DetalheService $detalheService,
        TrailerService $trailerService,
        TipoDebitoRepository $tipoDebitoRepository
    ) {
        $this->dataBase             = $dataBase;
        $this->arquivoTxtService    = $arquivoTxtService;
        $this->headerService        = $headerService;
        $this->detalheService       = $detalheService;
        $this->trailerService       = $trailerService;
        $this->tipoDebitoRepository = $tipoDebitoRepository;
    }

    public function execute(Filtro $filtro, ProgressBar $progressBar, $nomeArquivo)
    {
        $timeInicio = microtime(true);

        $debitos      = $filtro->getLista()->getDebitos();
        $datLista     = $filtro->getLista()->getData()->format('Y-m-d');
        $datVigFinal  = $filtro->getDataVigenciaFinal()->format('Y-m-d');
        $totalDebitos = $debitos->count();
        $totalLinhas  = 0;
        $contLinhaArq = 0;

        $this->arquivoTxtService->create($nomeArquivo);
        $tiposDebitoNaoProcessados = array();

        foreach ($debitos as $debito) {
            $tipoDebito = $this->tipoDebitoRepository->findByDebito($debito);

            if (empty($tipoDebito)) {
                $tiposDebitoNaoProcessados[] = $debito->getTipo();
            }

            $totalParcelas = $debito->getParcelas()->count();

            if (empty($totalParcelas)) {
                $totalLinhas += $totalDebitos;
                break;
            }

            $totalLinhas += $totalParcelas;
        }
        
        $progressBar->updateMaxProgress($totalLinhas);
        $progressBar->setMessageLog("Iniciando emissão do arquivo.");
        
        $progressBar->setMessageLog("Escrevendo cabeçalho.");
        $headerLine = $this->headerService->execute($filtro);
        $this->arquivoTxtService->addline($headerLine);
        $this->arquivoTxtService->write();

        $progressBar->setMessageLog("Escrevendo linhas.");
        $percentual = 0;

        $this->detalheService->setCodigoArquivoautoatendimento($this->headerService->getCodigoArquivoautoatendimento());

        foreach ($debitos as $debito) {
            $tipoDebito = $this->tipoDebitoRepository->findByDebito($debito);
            if (empty($tipoDebito)) {
                continue;
            }
            $barper = $percentual+2;
            $linhaCollection = $this->detalheService->execute($debito, $barper, $tipoDebito, $datLista, $datVigFinal);

            foreach ($linhaCollection as $linha) {
                $contLinhaArq++;
                $this->arquivoTxtService->addline($linha);
                $percentual++;
                $progressBar->updatePercentual($percentual);
            }
        }

        $this->arquivoTxtService->write();

        $progressBar->setMessageLog("Escrevendo Trailer (Linha Final)");
        $trailerLine = $this->trailerService->execute($contLinhaArq);
        $this->arquivoTxtService->addline($trailerLine);
        $this->arquivoTxtService->write();

        $progressBar->updatePercentual(100);
        $progressBar->setMessageLog("Processamento concluido com sucesso!");

        $inicio = DateTime::createFromFormat('U.u', $timeInicio);
        $fim    = DateTime::createFromFormat('U.u', microtime(true));

        $progressBar->setMessageLog("Hora de inicio: ".  $inicio->format("H:i:s m/d/Y"));
        $progressBar->setMessageLog("Hora do termino: ". $fim->format("H:i:s m/d/Y"));

        return $tiposDebitoNaoProcessados;
    }
}
