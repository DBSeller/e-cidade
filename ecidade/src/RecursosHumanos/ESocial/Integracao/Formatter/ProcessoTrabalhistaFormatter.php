<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Entity\Servidor;
use ECidade\RecursosHumanos\ESocial\Service\ServidorService;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use stdClass;
use CgmJuridico;
use DBPessoal;

/**
 * Class ProcessoTrabalhistaFormatter
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class ProcessoTrabalhistaFormatter extends Formatter
{

    /**
     * @param  array $dados
     * @return mixed|stdClass[]
     * @throws \BusinessException
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $dadosProcessoTrabalhista = null;

        foreach ($dados as $indice => $processoTrabalhista) {
            $dadoProcesso = new stdClass();
            $dadoProcesso->inscricao_empregador = $this->getEmpregador()->getCnpj();
            $dadoProcesso->referencia =
                $processoTrabalhista->getMatricula() . '-' . $processoTrabalhista->getNumeroProcesso();
            $dadoProcesso->infoProcesso = new stdClass();
            $dadoProcesso->infoProcesso->origem = (int) $processoTrabalhista->getOrigem();
            $dadoProcesso->infoProcesso->nrProcTrab = $processoTrabalhista->getNumeroProcesso();
            $dadoProcesso->infoProcesso->obsProcTrab =
                str_replace(array("\r\n", "\r", "\n"), "", $processoTrabalhista->getObservacaoProcesso());
            $dadoProcesso->infoProcesso->dadosCompl = new stdClass();
            if ($dadoProcesso->infoProcesso->origem == 1) {
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud = new stdClass();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->dtSent = $processoTrabalhista->getDataSentenca();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->ufVara = $processoTrabalhista->getUfVara();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->codMunic =
                    $processoTrabalhista->getCodigoMunicipio();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->idVara =
                    $processoTrabalhista->getIdentificacaoVara();
            }
            if ($dadoProcesso->infoProcesso->origem == 2) {
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP = new stdClass();
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP->dtCCP =
                    $processoTrabalhista->getDataCelebracaoAcordo();
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP->tpCCP =
                    (int) $processoTrabalhista->getAmbitoCelebracaoAcordo();
                if ((int) $processoTrabalhista->getAmbitoCelebracaoAcordo() != 1) {
                    $dadoProcesso->infoProcesso->dadosCompl->infoCCP->cnpjCCP =
                        $processoTrabalhista->getCnpjSindicato();
                }
            }
            $dadoProcesso->ideTrab = new stdClass();
            $dadoProcesso->ideTrab->cpfTrab = $processoTrabalhista->getCpfServidor();

            $dadoProcesso->ideTrab->nmTrab = $processoTrabalhista->getNomeServidor();
            $dadoProcesso->ideTrab->dtNascto = $processoTrabalhista->getDataNascimento();
            $dadoProcesso->ideTrab->infoContr = $processoTrabalhista->getInformacaoContratoTrabalho();
            if (empty($dadoProcesso->ideTrab->infoContr[0]->ideEstab->tpInsc)) {
                $dadoProcesso->ideTrab->infoContr[0]->ideEstab->tpInsc = 1;
            }
            if (empty($dadoProcesso->ideTrab->infoContr[0]->ideEstab->nrInsc)) {
                $dadoProcesso->ideTrab->infoContr[0]->ideEstab->nrInsc = $this->getEmpregador()->getCnpj();
            }
            $dadosProcessoTrabalhista[] = $dadoProcesso;
        }
        return $dadosProcessoTrabalhista;
    }
}
