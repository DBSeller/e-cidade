<?php
/**
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

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_cgm;
use cl_rhpessoalmov;
use BusinessException;
use DBException;
use ParameterException;
use db_utils;
use DBCompetencia;
use DBDate;
use DBPessoal;
use InstituicaoRepository;
use ServidorRepository;

/**
 * Class BeneficioTermino
 * Referente ao layout S-2420
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class BeneficioTermino extends \BaseClassRepository
{
    protected static $oInstance;

    /**
     * @param DBCompetencia $dbCompetencia
     * @return \CgmFisico[]
     * @throws DBException
     */
    public static function buscarBeneficiarios(DBCompetencia $competencia, $servidores = null, $selecao = null)
    {
        $retorno = array();
        $codigoInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();

        $dataInicio = $competencia->getDataDeInicio()->getDate();
        $dataFinal = $competencia->getDataDeTermino()->getDate();
        if (empty($servidores)) {
            $clRhPessoalmov = new \cl_rhpessoalmov();

            /*
             * Buscamos todas as matriculas que possuem a situacao do vinculo do regime aposentado/inativo
             * ou pensionista e que possuem rescisao
             */
            //validacao regime
            $where  = " rhregime.rh30_vinculo in ('I','P') ";
            //validacao rescisao
            $where .= " AND exists (select 1 from rhpesrescisao where rh05_seqpes = rh02_seqpes
                and rh05_recis between '{$dataInicio}' and '{$dataFinal}' ) ";

            if (!empty($selecao)) {
                $clselecao = new \cl_selecao();
                $condicaoSelecao = $clselecao->getCondicaoSelecao($selecao, $codigoInstituicao);
                $where .= " and {$condicaoSelecao} ";
            }

            $sqlServidores = $clRhPessoalmov->sql_query_baseServidores(
                DBPessoal::getMesFolha(),
                DBPessoal::getAnoFolha(),
                $codigoInstituicao,
                "distinct rh01_regist",
                $where,
                "rh01_regist",
                "rh01_regist"
            );
            $rsServidores = db_query($sqlServidores);
            if (!$rsServidores) {
                $msg = "Ocorreu um erro ao buscar os servidores.";
                throw new BusinessException($msg);
            }
            $qtdServidores = pg_num_rows($rsServidores);
            if ($qtdServidores == 0) {
                $mensagem = "Nenhum cadastro de beneficário encontrado na competência informada na instituição.";
                throw new DBException($mensagem);
            }

            for ($contador = 0; $contador < $qtdServidores; $contador++) {
                $matricula = db_utils::fieldsMemory($rsServidores, $contador)->rh01_regist;
                $retorno[] = ServidorRepository::getInstanciaByCodigo(
                    $matricula
                );
            }

            if (count($retorno) == 0) {
                $mensagem = "Nenhuma rescisão de beneficário encontrada na competência.";
                throw new DBException($mensagem);
            }
        } else {
            foreach ($servidores as $servidor) {
                /*
                 * Verificamos se a matricula informada é aposentado ou pensionista e não possui rescisao
                 */
                $clRegime = new \cl_rhregime();


                //validacao regime
                $where  = " rhregime.rh30_codreg = {$servidor->getCodigoRegime()} ";
                $where .= " AND rhregime.rh30_vinculo in ('I','P')";

                //validacao da rescisao
                $where .= " AND exists (select 1 from rhpesrescisao where
                    rh05_seqpes = {$servidor->getCodigoMovimentacao()}
                ) ";

                $rsRegime = db_query(
                    $clRegime->sql_query_file(null, "rh30_codreg", null, $where)
                );
                if (pg_num_rows($rsRegime) == 0) {
                    continue;
                }

                $retorno[] = $servidor;
            }

            if (count($retorno) == 0) {
                $mensagem  = "Nenhum cadastro de beneficário encontrado.\n";
                $mensagem .= "Verifique o vinculo do regime e se as matriculas informadas ";
                $mensagem .= "não estão rescindidas.";
                throw new DBException($mensagem);
            }
        }

        return $retorno;
    }

    /**
     * @param int $codigoCgm
     * @return string
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     */
    public static function buscarCNPJEmpregador($codigoCgm)
    {
        if (empty($codigoCgm)) {
            throw new ParameterException('CGM não informado.');
        }

        $daoCgm = new cl_cgm();
        $sqlCgm = $daoCgm->sql_query_file($codigoCgm, 'z01_cgccpf as cnpj');
        $rsCgm = db_query($sqlCgm);

        if (!$rsCgm) {
            throw new DBException('Erro ao buscar o CNPJ do CGM.');
        }

        if (pg_num_rows($rsCgm) == 0) {
            throw new DBException("CGM {$codigoCgm} não encontrado.");
        }

        $cnpj = db_utils::fieldsMemory($rsCgm, 0)->cnpj;

        if (empty($cnpj)) {
            throw new BusinessException("CNPJ não informado para o CGM {$codigoCgm}.");
        }

        return $cnpj;
    }

    /**
     * @param $codigoRubrica
     * @return string
     */
    public static function buscarIdentificadorRubrica($codigoRubrica)
    {
        $instituicao = db_getsession('DB_instit');
        $sql = "SELECT * FROM fc_rubrica_esocial('{$codigoRubrica}', {$instituicao})";
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) == 0) {
            throw new Exception('Não foi possível consultar os preenchimentos da rubrica {$item->codigo}.');
        }

        return pg_fetch_result($rs, 0, 'identificador');
    }
}
