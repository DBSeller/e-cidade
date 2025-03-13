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

use BusinessException;
use DBException;
use ParameterException;
use db_utils;
use cl_cgm;
use DBCompetencia;
use InstituicaoRepository;

/**
 * Class RemuneracaoRGPS
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class MonitoramentoSaude extends \BaseClassRepository
{
    protected static $oInstance;

    /**
     * @param DBCompetencia $dbCompetencia
     * @return \stdClass[]
     * @throws DBException
     */
    public static function buscarTodosAssentamentosCompetencia(DBCompetencia $dbCompetencia, $servidores = null)
    {
        $instituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
        $matriculas = "";
        if (!empty($servidores)) {
            $matriculas = " and rh01_regist in(";
            $max = sizeof($servidores);
            for ($i = 0; $i < $max; $i++) {
                $matriculas .= $servidores[$i]->getMatricula();
                if ($i == ($max-1)) {
                    $matriculas .= ")";
                } else {
                    $matriculas .= ",";
                }
            }
        }

        $sql = "
            select
                h16_codigo
            from
                 recursoshumanos.assenta
                 inner join recursoshumanos.monitoramentosaude on h26_assenta = h16_codigo
                 inner join rhpessoal on h16_regist = rh01_regist
            where
                ((
                    extract(MONTH FROM h16_dtconc) = {$dbCompetencia->getMes()}
                    AND extract(YEAR FROM h16_dtconc) = {$dbCompetencia->getAno()}
                ) OR (
                    h16_dtterm is not null
                    AND  extract(month from h16_dtterm) = {$dbCompetencia->getMes()}
                    AND extract(year from h16_dtterm) = {$dbCompetencia->getAno()}
                ))
                and
                   rh01_instit = {$instituicao}
                {$matriculas}";

        $rs = \db_query($sql);


        if (!$rs) {
            throw new DBException("Erro ao buscar os Assentamentos de Controle médico da instituição logada.");
        }

        if (pg_num_rows($rs) == 0) {
            $mensagem = "Nenhum Assentamento de Controle Médico na instituição, encontrado na competência informada.";
            throw new DBException($mensagem);
        }

        return db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            return new \Assentamento($retorno->h16_codigo);
        });
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
}
