<?php
namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Formata os dados do Processo Administrativo
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class ProcessoAdministrativoFormatter extends Formatter
{
    const TIPO_PROCESSO_ADMINISTRATIVO        = 1;
    const TIPO_PROCESSO_JUDICIAL              = 2;
    const TIPO_PROCESSO_PROCESSO_FAP          = 4;
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $dadosFormatado = parent::formatar($dados);
        return $this->posProcessamento($dadosFormatado);
    }

    /**
     * Realiza uma consistencia nos dados enviados
     *
     * @param array  $dadosFormatado
     * @return array
     */
    private function posProcessamento($dadosFormatado)
    {
        foreach ($dadosFormatado as $dadoProcesso) {
            if (empty($dadoProcesso->ideProcesso->fimValid)) {
                $dadoProcesso->ideProcesso->fimValid = null;
            }
            /**
             * Formata o numero de processo conforme o tipo informado
             */
            $remover = ['.', '/', '\\', ',', ';', '-'];
            $dadoProcesso->ideProcesso->nrProc = str_replace($remover, "", $dadoProcesso->ideProcesso->nrProc);
            switch ($dadoProcesso->ideProcesso->tpProc) {
                case ProcessoAdministrativoFormatter::TIPO_PROCESSO_ADMINISTRATIVO:
                    if (strlen($dadoProcesso->ideProcesso->nrProc) <= 17) {
                        $dadoProcesso->ideProcesso->nrProc = str_pad(
                            $dadoProcesso->ideProcesso->nrProc,
                            17,
                            '0',
                            STR_PAD_LEFT
                        );
                    } elseif (strlen($dadoProcesso->ideProcesso->nrProc) < 21) {
                        $dadoProcesso->ideProcesso->nrProc = str_pad(
                            $dadoProcesso->ideProcesso->nrProc,
                            21,
                            '0',
                            STR_PAD_LEFT
                        );
                    }

                    foreach ($dadoProcesso->dadosProc->infoSusp as $infoSusp) {
                        if (!empty($infoSusp->indSusp)) {
                            $this->validarCampo(
                                'indSusp',
                                $infoSusp->indSusp,
                                $dadoProcesso->ideProcesso->tpProc,
                                $dadoProcesso->ideProcesso->nrProc
                            );
                        }
                    }
                    break;
                case ProcessoAdministrativoFormatter::TIPO_PROCESSO_JUDICIAL:
                    if (strlen($dadoProcesso->ideProcesso->nrProc) < 20) {
                        $dadoProcesso->ideProcesso->nrProc = str_pad(
                            $dadoProcesso->ideProcesso->nrProc,
                            20,
                            '0',
                            STR_PAD_LEFT
                        );
                    }

                    foreach ($dadoProcesso->dadosProc->infoSusp as $infoSusp) {
                        if (!empty($infoSusp->indSusp)) {
                            $this->validarCampo(
                                'indSusp',
                                $infoSusp->indSusp,
                                $dadoProcesso->ideProcesso->tpProc,
                                $dadoProcesso->ideProcesso->nrProc
                            );
                        }
                    }
                    break;
                case ProcessoAdministrativoFormatter::TIPO_PROCESSO_PROCESSO_FAP:
                        $dadoProcesso->ideProcesso->nrProc = str_pad(
                            $dadoProcesso->ideProcesso->nrProc,
                            16,
                            '0',
                            STR_PAD_LEFT
                        );
                        $dadoProcesso->ideProcesso->nrProc = substr($dadoProcesso->ideProcesso->nrProc, 0, 16);

                    foreach ($dadoProcesso->dadosProc->infoSusp as $infoSusp) {
                        if (!empty($infoSusp->indSusp)) {
                            $this->validarCampo(
                                'indSusp',
                                $infoSusp->indSusp,
                                $dadoProcesso->ideProcesso->tpProc,
                                $dadoProcesso->ideProcesso->nrProc
                            );
                        }
                    }
                    break;
            }

            /* Se for administrativo, não envia dados de processo juridico */
            if ($dadoProcesso->ideProcesso->tpProc == '1') {
                unset($dadoProcesso->dadosProc->dadosProcJud);
            } else {
                if (empty($dadoProcesso->dadosProc->dadosProcJud->ufVara)
                    && empty($dadoProcesso->dadosProc->dadosProcJud->codMunic)
                    && empty($dadoProcesso->dadosProc->dadosProcJud->idVara)
                ) {
                    unset($dadoProcesso->dadosProc->dadosProcJud);
                }
            }

            /**
             * Valida infoSusp
             * Se indMatProc ==  1, é obrigatório
             * Caso Contrario, não vai
             */
            if (!empty($dadoProcesso->dadosProc)) {
                if ($dadoProcesso->dadosProc->indMatProc == '1') {
                    if (isset($dadoProcesso->dadosProc->infoSusp)) {
                        foreach ($dadoProcesso->dadosProc->infoSusp as $key => $infoSusp) {
                            if (!$this->validaSeGrupoFoiPreenchido(get_object_vars($infoSusp))) {
                                unset($dadoProcesso->dadosProc->infoSusp[$key]);
                            }
                        }
                    }
                } else {
                    unset($dadoProcesso->dadosProc->infoSusp);
                }
            }
        }
        return $dadosFormatado;
    }

    private function validarCampo($nomeCampo, $valorCampo, $valorCampoCondicional, $numeroProcesso)
    {
        switch ($nomeCampo) {
            case 'indSusp':
                $valoresValidos = array('01', '02', '03', '04', '05', '08', '09', '10', '11', '12', '13', '14', '90',
                    '92');

                switch ($valorCampoCondicional) {
                    case ProcessoAdministrativoFormatter::TIPO_PROCESSO_ADMINISTRATIVO:
                    case ProcessoAdministrativoFormatter::TIPO_PROCESSO_PROCESSO_FAP:
                        $valoresValidos = array('03', '14', '90', '92');
                        break;
                    case ProcessoAdministrativoFormatter::TIPO_PROCESSO_JUDICIAL:
                        $valoresValidos = array('01', '02', '04', '05', '08', '09', '10', '11', '12', '13', '90', '92');
                        break;
                }
                break;
        }
    }
}
