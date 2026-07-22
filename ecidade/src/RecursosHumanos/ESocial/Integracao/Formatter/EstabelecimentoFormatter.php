<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados do Empregador
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class EstabelecimentoFormatter extends Formatter
{
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
        foreach ($dadosFormatado as $dadoEstabelecimento) {
            if (!empty($dadoEstabelecimento->dadosEstab->infoTrab->infoApr) &&
                !empty($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->contEntEd)) {
                if ($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->contEntEd == 'N') {
                    unset($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->infoEntEduc);
                }
            }

            if (isset($dadoEstabelecimento->ideEstab->tpInsc)
                && $dadoEstabelecimento->ideEstab->tpInsc != 3) {
                unset($dadoEstabelecimento->dadosEstab->infoCaepf);
            }

            if (isset($dadoEstabelecimento->dadosEstab->aliqGilrat->aliqRat)) {
                str_replace(",", ".", $dadoEstabelecimento->dadosEstab->aliqGilrat->aliqRat);
            }

            if (isset($dadoEstabelecimento->dadosEstab->aliqGilrat->fap)) {
                $dadoEstabelecimento->dadosEstab->aliqGilrat->fap = (float) str_replace(
                    ",",
                    ".",
                    $dadoEstabelecimento->dadosEstab->aliqGilrat->fap
                );
            }

            if (isset($dadoEstabelecimento->dadosEstab->aliqGilrat->aliqRatAjust)) {
                $dadoEstabelecimento->dadosEstab->aliqGilrat->aliqRatAjust = (float) str_replace(
                    ",",
                    ".",
                    $dadoEstabelecimento->dadosEstab->aliqGilrat->aliqRatAjust
                );
            }

            if (empty($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudRat->nrProc) &&
                empty($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudRat->codSusp)) {
                unset($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudRat);
            }

            if (empty($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudFap->nrProc) &&
                empty($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudFap->codSusp)) {
                unset($dadoEstabelecimento->dadosEstab->aliqGilrat->procAdmJudFap);
            }

            if ($this->isEmpty($dadoEstabelecimento->dadosEstab->infoObra)) {
                unset($dadoEstabelecimento->dadosEstab->infoObra);
            }

            if (isset($dadoEstabelecimento->ideEstab->fimValid) &&
                empty($dadoEstabelecimento->ideEstab->fimValid)) {
                $dadoEstabelecimento->ideEstab->fimValid = null;
            }

            if (isset($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->infoEntEduc[0])
                && (empty($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->infoEntEduc[0]->nrInsc))) {
                unset($dadoEstabelecimento->dadosEstab->infoTrab->infoApr->infoEntEduc);
            }

            if (isset($dadoEstabelecimento->dadosEstab->cnaePrep)) {
                $dadoEstabelecimento->dadosEstab->cnaePrep = str_replace(
                    array("/", "-"),
                    array("",""),
                    $dadoEstabelecimento->dadosEstab->cnaePrep
                );

                if (!empty($dadoEstabelecimento->dadosEstab->cnaePrep)) {
                    $dadoEstabelecimento->dadosEstab->cnaePrep = (int) $dadoEstabelecimento->dadosEstab->cnaePrep;
                }
            }

            if (empty($dadoEstabelecimento->dadosEstab->aliqGilrat->fap)) {
                $dadoEstabelecimento->dadosEstab->aliqGilrat->fap = null;
            }

            if (isset($dadoEstabelecimento->dadosEstab->infoTrab->infoApr)) {
                if (empty($dadoEstabelecimento->dadosEstab->infoTrab->infoApr)) {
                    unset($dadoEstabelecimento->dadosEstab->infoTrab->infoApr);
                }
            }

            if (isset($dadoEstabelecimento->dadosEstab->infoTrab->infoPCD)) {
                if (empty($dadoEstabelecimento->dadosEstab->infoTrab->infoPCD->nrProcJud)) {
                    unset($dadoEstabelecimento->dadosEstab->infoTrab->infoPCD);
                }
            }

            if (empty($dadoEstabelecimento->dadosEstab->infoTrab)) {
                unset($dadoEstabelecimento->dadosEstab->infoTrab);
            }
        }

        return $dadosFormatado;
    }
}
