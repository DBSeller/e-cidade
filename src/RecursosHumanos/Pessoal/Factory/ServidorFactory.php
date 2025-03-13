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

/**
 * Factory que retorna o método adequado do ServidorRepository
 * @author Yuri Goulart
 * @revision $Author: dbandrio.costa $
 * @version $Revision: 1.6 $
 */
namespace ECidade\RecursosHumanos\Pessoal\Factory;

use DBPessoal;
use Exception;
use ServidorRepository;
use stdClass;

class ServidorFactory
{

    const POR_PADRAO = 0;
    const POR_ORGAO = 1;
    const POR_LOTACAO = 2;
    const POR_MATRICULAS = 3;
    const POR_LOCALDETRABALHO = 4;
    const POR_CARGO = 5;
    const POR_RECURSO = 6;

    /**
     * Retorna a chamada do método adequado do Servidor Repository
     *
     * @param $iInfo
     * @param  integer $iTipo -- 1 Orgão
     *                         -- 2 Lotação
     *                         -- 3 Matrículas
     *                         -- 4 Local de Trabalho
     *                         -- 5 Cargo
     *                         -- 6 Recurso
     * @param null $intervalo
     * @param null $iAnoFolha
     * @param null $iMesFolha
     * @param null $iInstituicao
     * @return object
     * @throws Exception
     */
    public function getInstance(
        $iInfo,
        $iTipo = 0,
        $intervalo = null,
        $iAnoFolha = null,
        $iMesFolha = null,
        $iInstituicao = null
    )
    {
        if (empty($iAnoFolha)) {
            $iAnoFolha = DBPessoal::getAnoFolha();
        }
        if (empty($iMesFolha)) {
            $iMesFolha = DBPessoal::getMesFolha();
        }
        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }
        //Adicionado para tratar os casos que não tem implementação do in e between
        $iInfoArray = $iInfo;
        if (is_string($iInfoArray)) {
            $iInfoArray = explode(",", $iInfoArray);
        }

        $retorno = array();
        switch ($iTipo) {//Fazer constante para os valores
            case self::POR_PADRAO:
                //Falta implementação do between e in. Então mas é utilizado o foreach dentro da função
                $retorno = ServidorRepository::getServidoresByMatriculas($iAnoFolha, $iMesFolha, $iInfoArray,
                    $iInstituicao);
                break;
            case self::POR_ORGAO:
                if ($intervalo) {
                    if (!empty($intervalo->inicial) && !empty($intervalo->final)) {
                        $retorno = ServidorRepository::getServidoresByOrgao($iAnoFolha, $iMesFolha, $intervalo->inicial,
                            $intervalo->final);
                        break;
                    }
                }
                $retorno = ServidorRepository::getServidoresByOrgao($iAnoFolha, $iMesFolha, $iInfo);
                break;
            case self::POR_LOTACAO:
                if ($intervalo) {
                    if (!empty($intervalo->inicial) && !empty($intervalo->final)) {
                        $retorno = ServidorRepository::getServidoresByLotacao($iAnoFolha, $iMesFolha,
                            "{$intervalo->inicial},{$intervalo->final}");
                        break;
                    }
                }
                $retorno = ServidorRepository::getServidoresByLotacao($iAnoFolha, $iMesFolha, $iInfo);
                break;
            case self::POR_MATRICULAS:
                //Falta implementação do between e in. Então mas é utilizado o foreach dentro da função
                $retorno = ServidorRepository::getServidoresByMatriculas($iAnoFolha, $iMesFolha, $iInfoArray,
                    $iInstituicao);
                break;
            case self::POR_LOCALDETRABALHO:
                //Falta implementação do between e in. Então foi utilizado o foreach
                $retorno = array();
                foreach ($iInfoArray as $localTrabalho) {
                    $retorno = array_merge($retorno,
                        ServidorRepository::getServidoresByLocalTrabalho(
                            $iAnoFolha,
                            $iMesFolha,
                            $localTrabalho,
                            $iInstituicao
                        )
                    );
                }
                break;
            case self::POR_CARGO:
                //Cargo
                throw new Exception("Falta implementação da função no ServidorRepository");
                break;
            case self::POR_RECURSO:
                //Falta implementação do between e in. Então foi utilizado o foreach
                foreach ($iInfoArray as $recurso) {
                    $retorno = array_merge($retorno,
                        ServidorRepository::getServidoresByRecurso($iAnoFolha, $iMesFolha, $recurso, $iInstituicao));
                }
                break;
            default:
                throw new Exception("Não existe tipo informado");
                break;
        }

        return $retorno;

    }

    /**
     * @param $parametros
     * @return array
     * @throws Exception
     */
    public function getInstanceByFiltroFolha($parametros)
    {
        $dados = new stdClass();
        $dados->iTipo = $parametros->oCboTipoRelatorio;
        $dados->intervalo = new stdClass();
        $dados->intervalo->inicial = null;
        $dados->intervalo->final = null;
        if ($parametros->oCboTipoFiltro == "1") {
            $dados->dados = range($parametros->InputIntervaloInicial, $parametros->InputIntervaloFinal);
            $dados->intervalo->inicial = $parametros->InputIntervaloInicial;
            $dados->intervalo->final = $parametros->InputIntervaloFinal;
        } else {
            if ($parametros->oCboTipoFiltro == "2") {
                $dados->dados = explode(",", $parametros->dados);
            }
        }
        $result = $this->getInstance($dados->dados, $dados->iTipo, $dados->intervalo);
        return array_filter($result);
    }
}

?>
