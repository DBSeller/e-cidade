<?php
namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_rhpesrescisao;
use DBCompetencia;
use DBException;
use db_utils;
use Servidor;

class ServidorMatriculas extends \BaseClassRepository
{

    /**
     * @var ServidorMatriculas
     */
    protected static $oInstance;


    public function __construct($numCGM, $instituicao = null)
    {
        $this->numCGM = $numCGM;
        $this->instituicao = (int)$instituicao ? $instituicao
        : \InstituicaoRepository::getInstituicaoSessao()->getCodigo();
    }

    /**
     * Retorno das matriculas por codigo do cmg para consulta do esocial
     * @param $codigoRescisao
     * @throws \DBException
     */
    public function getMatriculasByMovimentacao($mes, $ano, $tipoPagamento)
    {

        $rescisaoExtract = "AND extract(month from rh05_recis) = {$mes}";
        $servidorAtivo = "AND rh02_mesusu = {$mes} AND rhpesrescisao.rh05_seqpes is null";

        $movSql = in_array((int)$tipoPagamento, [2, 3]) ? $rescisaoExtract : $servidorAtivo;

        $sql = "SELECT  distinct rh01_regist
        FROM rhpessoal
        INNER JOIN rhpessoalmov
        ON rh01_regist = rh02_regist
        INNER JOIN cgm
        ON z01_numcgm = rh01_numcgm

        LEFT JOIN rhpesrescisao
        ON rh05_seqpes = rh02_seqpes

        where z01_numcgm = {$this->numCGM}
        AND rh02_anousu = {$ano}
        AND rh02_instit = {$this->instituicao}

        {$movSql}
        ;";

        $result = \db_query($sql);

        if (pg_num_rows($result) > 0) {
            $result = pg_fetch_all($result);
            $matriculas = '';
            foreach ($result as $value) {
                $servidor = new \Servidor($value['rh01_regist']);

                if ($servidor->isRgps()) {
                    $matriculas .= $value['rh01_regist']." , ";
                }
            }

            $matriculas = substr($matriculas, 0, -2);
            return $matriculas;
        }
    }

    /**
     * Retorno das matriculas em qualquer contexto
     * @throws \DBException
     */
    public function getMatriculas()
    {

        $sql = "SELECT  distinct rh01_regist
        FROM rhpessoal
        INNER JOIN rhpessoalmov
        ON rh01_regist = rh02_regist
        INNER JOIN cgm
        ON z01_numcgm = rh01_numcgm

        LEFT JOIN rhpesrescisao
        ON rh05_seqpes = rh02_seqpes

        where z01_numcgm = {$this->numCGM}
        AND rh02_instit = {$this->instituicao}
        ;";

        $result = \db_query($sql);

        if (pg_num_rows($result) > 0) {
            $result = pg_fetch_all($result);
            $matriculas = '';
            foreach ($result as $value) {
                $matriculas .= $value['rh01_regist']." , ";
            }

            $matriculas = substr($matriculas, 0, -2);
            return $matriculas;
        }
    }
}
