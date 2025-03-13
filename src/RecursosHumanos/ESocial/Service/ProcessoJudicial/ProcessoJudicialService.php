<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
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

namespace ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\ProcessoJudicial;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use BusinessException;
use stdClass;
use DBDate;

class ProcessoJudicialService
{
    /**
     * @var
     */
    private $processoJudicialRepository;

      /**
     * ProcessoJudicialService constructor.
     */
    public function __construct()
    {
        $this->processoJudicialRepository = new ProcessoJudicialRepository();
    }

    /**
     * @param stdClass $parametros
     * @return Processos
     * @throws BusinessException
     */
    public function salvar(stdClass $parametros)
    {
        //Informações do processo judicial ou de demanda submetida à Comissão de Conciliação Prévia (CCP) ou ao Núcleo
        //Intersindical de Conciliação Trabalhista (NINTER).
        $complemento = ' em "Informações Principais do Processo"';
        if (empty($parametros->origem)) {
            throw new BusinessException('É necessário informar a "Origem" ' . $complemento . '. Favor revisar.');
        }

        if (empty($parametros->numeroProcesso)) {
            throw new BusinessException('É necessário informar o "Número do Processo" ' . $complemento .
                '. Favor revisar.');
        }

        if (strlen($parametros->numeroProcesso) <= 14) {
            throw new BusinessException('É necessário informar o "Número do Processo" com 15 ou 20 caracteres ' .
                $complemento . ' Favor revisar.');
        }

        if (strlen($parametros->numeroProcesso) != 15 && strlen($parametros->numeroProcesso) <= 19) {
            throw new BusinessException('É necessário informar o "Número do Processo" com 15 ou 20 caracteres ' .
            $complemento . '. Favor revisar.');
        }

        if (!is_numeric($parametros->numeroProcesso)) {
            throw new BusinessException('É necessário informar o "Número do Processo" com algarismos númericos ' .
            'somente ' . $complemento . '. Favor revisar.');
        }

        if (!is_numeric($parametros->origem)) {
            throw new BusinessException('É necessário informar o "Origem" com algarismos númericos somente ' .
            $complemento . '. Favor revisar.');
        }

        if ((int) $parametros->origem == 1) {
            if (strlen($parametros->numeroProcesso) != 20) {
                throw new BusinessException('É necessário informar o "Número do Processo" com 20 caracteres ' .
                $complemento . '. Favor revisar.');
            }
        }

        if ((int) $parametros->origem == 2) {
            if (strlen($parametros->numeroProcesso) != 15) {
                throw new BusinessException('É necessário informar o "Número do Processo" com 15 caracteres ' .
                $complemento . '. Favor revisar.');
            }
        }

        if (((int) $parametros->origem > 2)) {
            throw new BusinessException('Valor inválido para "Origem" ' . $complemento .
            '. Favor revisar.');
        }

        if (((int) $parametros->origem < 0)) {
            throw new BusinessException('Valor inválido para "Origem" ' . $complemento .
            '. Favor revisar.');
        }

        if ((int) $parametros->numeroProcesso < 0) {
            throw new BusinessException('Valor inválido para "Número do Processo" ' . $complemento .
            '. Favor revisar.');
        }

        
        //Informações complementares do processo judicial.
        $complemento = ' em "Informações Complementares do Processo ou da Demanda"';
        if (!empty($parametros->dataSentenca) ||
            !empty($parametros->UFVara) ||
            !empty($parametros->codigoMunicipio) ||
            !empty($parametros->codigoVara)) {
            if (empty($parametros->dataSentenca)) {
                throw new BusinessException('É necessário informar a "Data da Sentença"'.  $complemento .
                    '. Favor revisar.');
            }

            if (empty($parametros->UFVara)) {
                throw new BusinessException('É necessário informar o "Sigla do Estado da Vara"'. $complemento .
                    '. Favor revisar.');
            }

            if (empty($parametros->codigoMunicipio)) {
                throw new BusinessException('É necessário informar o "Cód. Município (IBGE)"' . $complemento .
                    '. Favor revisar.');
            }

            if (strlen(trim($parametros->codigoMunicipio)) != 7) {
                throw new BusinessException('É necessário informar o "Cód. Município (IBGE)" com 7 caracteres ' .
                    $complemento . '. Favor revisar.');
            }

            if (empty($parametros->codigoVara)) {
                throw new BusinessException('É necessário informar a "Código Identificação da Vara"' . $complemento .
                    '. Favor revisar.');
            }

            $dataSentenca = explode('-', $parametros->dataSentenca);
            if (!checkdate($dataSentenca[1], $dataSentenca[2], $dataSentenca[0])) {
                throw new BusinessException('Data de Sentença(' . $parametros->dataSentenca . ') em formato errado em' .
                    $complemento . '. Favor revisar.');
            }

            $dataAtual =  date('Y-m-d');
            if (strtotime($dataAtual) < strtotime($parametros->dataSentenca)) {
                throw new BusinessException('Data de Sentença(' . $parametros->dataSentenca . ') ' .
                'é maior que a data atual(' . $dataAtual . ') em' . $complemento . '. Favor revisar.');
            }

            $codigosMunicipiosIBGE = $this->processoJudicialRepository->getListaCodigoMunicipioIBGE();

            if (!in_array($parametros->codigoMunicipio, $codigosMunicipiosIBGE)) {
                throw new BusinessException('O "Cód. Município (IBGE)" não é válido ' . $complemento .
                    '. Favor revisar.');
            }
        }

        //Informações complementares da demanda submetida à CCP ou ao NINTER.
        $complemento = ' em "Informações Complementares da Demanda Submetida à CPP ou a NINTER"';
        if (!empty($parametros->dataAcordo) ||
            !empty($parametros->tipoAcordo)) {
            if (empty($parametros->dataAcordo)) {
                throw new BusinessException('É necessário informar a "Data da Celebração do Acordo" ' . $complemento .
                    '. Favor revisar.');
            }

            if (empty($parametros->tipoAcordo)) {
                throw new BusinessException('É necessário informar a indicação ' .
                '"Tipo do Âmbito de Celebração de Acordo" ' . $complemento . '. Favor revisar.');
            }

            if (!empty($parametros->cnpjCCP)) {
                if (strlen(trim($parametros->cnpjCCP)) != 14) {
                    throw new BusinessException('É necessário informar a indicação "CNPJ do Sindicato Representativo" '
                        . ' com 14 dígitos e somente números ' . $complemento . '. Favor revisar.');
                }

                if (!$this->validaCnpj($parametros->cnpjCCP)) {
                    throw new BusinessException('O "CNPJ do Sindicato Representativo" é inválido' .
                        $complemento . '. Favor revisar.');
                }
            }

            $dataAcordo = explode('-', $parametros->dataAcordo);
            if (!checkdate($dataAcordo[1], $dataAcordo[2], $dataAcordo[0])) {
                throw new BusinessException('Data de Acordo(' . $parametros->dataAcordo .') em formato errado em' .
                    $complemento . '. Favor revisar.');
            }

            $dataAtual =  date('Y-m-d');
            if (strtotime($dataAtual) < strtotime($parametros->dataAcordo)) {
                throw new BusinessException('Data de Acordo(' . $parametros->dataAcordo .
                ') é maior que a data atual(' . $dataAtual . ') em' . $complemento . '. Favor revisar.');
            }
        }

        $processo = new ProcessoJudicial();
        $parametros->sequencial = (int) $parametros->sequencial;
        if (empty($parametros->sequencial)) {
            $parametros->sequencial = null;
        }
        if ((int)$parametros->origem == 1) {
            $parametros->dataAcordo = "";
            $parametros->tipoAcordo = "";
            $parametros->cnpjCCP = "";
        }
        if ((int)$parametros->origem == 2) {
            $parametros->dataSentenca = "";
            $parametros->UFVara = "";
            $parametros->codigoMunicipio = "";
            $parametros->codigoVara = "";
        }

        $processo->setSequencial($parametros->sequencial);
        $processo->setOrigem($parametros->origem);
        $processo->setNumeroProcesso($parametros->numeroProcesso);
        $processo->setObservacaoProcesso($parametros->observacao);
        $processo->setDataSentenca($parametros->dataSentenca);
        $processo->setUfVara($parametros->UFVara);
        $processo->setCodigoMunicipio($parametros->codigoMunicipio);
        $processo->setIdentificacaoVara($parametros->codigoVara);
        $processo->setDataCelebracaoAcordo($parametros->dataAcordo);
        $processo->setAmbitoCelebracaoAcordo($parametros->tipoAcordo);
        $processo->setCnpjSindicato($parametros->cnpjCCP);

        return $this->processoJudicialRepository->save($processo);
    }

    /**
     * @return Processos
     * @throws BusinessException
     */
    public function listaProcesso()
    {
        $retorno = $this->processoJudicialRepository->allOrderBy(['*'], 'rh270_sequencial desc');
        return $retorno;
    }

    /**
     * @return Processos
     * @throws BusinessException
     */
    public function retornaProcesso($sequencial = null)
    {
        $processos = $this->processoJudicialRepository->find($sequencial);
        return $processos;
    }

    /**
     * Valida CPNJ
     * @param string $document
     * @return bool
     */
    private function validaCnpj($document)
    {
        // Extrai os números
        $cnpj = preg_replace('/[^0-9]/is', '', $document);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica sequência de digitos repetidos. Ex: 11.111.111/111-11
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida dígitos verificadores
        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $m = ($t - 7), $i = 0; $i < $t; $i++) {
                $d += $cnpj[$i] * $m;
                $m = ($m == 2 ? 9 : --$m);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$i] != $d) {
                return false;
            }
        }

        return true;
    }
}
