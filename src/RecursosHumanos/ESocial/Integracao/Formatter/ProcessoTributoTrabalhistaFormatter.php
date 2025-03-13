<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Entity\Servidor;
use ECidade\RecursosHumanos\ESocial\Service\ServidorService;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use stdClass;
use CgmJuridico;
use DBPessoal;

/**
 * Class ProcessoTributoTrabalhistaFormatter
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class ProcessoTributoTrabalhistaFormatter extends Formatter
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

        foreach ($dados as $processoTrabalhista) {
            $dadoProcesso = new stdClass();
            $dadoProcesso->inscricao_empregador = $this->getEmpregador()->getCnpj();
            $dadoProcesso->referencia =
                $processoTrabalhista->getMatricula() . '-' . $processoTrabalhista->getNumeroProcesso();
            $dadoProcesso->infoProcesso->origem = (int) $processoTrabalhista->getOrigem();
            $dadoProcesso->infoProcesso->nrProcTrab = $processoTrabalhista->getNumeroProcesso();
            $dadoProcesso->infoProcesso->obsProcTrab =
                str_replace(array("\r\n", "\r", "\n"), "", $processoTrabalhista->getObservacaoProcesso());
            if ($dadoProcesso->infoProcesso->origem == 1) {
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->dtSent = $processoTrabalhista->getDataSentenca();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->ufVara = $processoTrabalhista->getUfVara();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->codMunic =
                    $processoTrabalhista->getCodigoMunicipio();
                $dadoProcesso->infoProcesso->dadosCompl->infoProcJud->idVara =
                    $processoTrabalhista->getIdentificacaoVara();
            }
            if ($dadoProcesso->infoProcesso->origem == 2) {
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP->dtCCP =
                    $processoTrabalhista->getDataCelebracaoAcordo();
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP->tpCCP =
                    (int) $processoTrabalhista->getAmbitoCelebracaoAcordo();
                $dadoProcesso->infoProcesso->dadosCompl->infoCCP->cnpjCCP =
                    $processoTrabalhista->getCnpjSindicato();
            }
            $dadoProcesso->ideTrab->cpfTrab = $processoTrabalhista->getCpfServidor();

            $dadoProcesso->ideTrab->nmTrab = $processoTrabalhista->getNomeServidor();
            $dadoProcesso->ideTrab->dtNascto = $processoTrabalhista->getDataNascimento();

            $dadoProcesso->ideTrab->infoContr = $processoTrabalhista->getInformacaoContratoTrabalho();
            $this->validaEmpregadorContrato($dadoProcesso);
            $dadosProcessoTrabalhista[] = $dadoProcesso;
        }

        return $dadosProcessoTrabalhista;
    }

 
    /**
     * @param $dadoProcesso
     */
    private function validaEmpregadorContrato(&$dadoProcesso)
    {
        foreach ($dadoProcesso->ideTrab->infoContr as $contrato) {
            if (empty($contrato->ideEstab->tpInsc)) {
                $contrato->ideEstab->tpInsc = 1;
            }
            if (empty($contrato->ideEstab->nrInsc)) {
                $contrato->ideEstab->nrInsc = $this->getEmpregador()->getCnpj();
            }
        }
    }
}
