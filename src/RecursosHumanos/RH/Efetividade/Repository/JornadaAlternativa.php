<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 27/11/17
 * Time: 17:16
 */

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;


use ECidade\RecursosHumanos\RH\Efetividade\Model\JornadaAlternativa as JornadaAlternativaModel;

class JornadaAlternativa extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     */
    protected static $oInstance;

    protected $jornadas = array();

    /**
     * Retorna uma instância de Jornada
     * @param  $iCodigo
     * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada|null
     * @throws \DBException
     */
    protected function make($iCodigo) {

    }

    /**
     * @param \Servidor $servidor
     * @param \DateTime $dataInicial
     * @param \DateTime|null $dataFinal
     * @return JornadaAlternativaModel[]
     * @throws \BusinessException
     */
    public static function getMaiorqueDataPorServidor(\Servidor $servidor, \DBDate $data)
    {

        if (!empty(self::getInstance()->jornadas[$servidor->getMatricula()])) {
            return self::getInstance()->jornadas[$servidor->getMatricula()];
        }

        $whereDatas = "rh212_data >= '{$data->getDate()}'";
        $where = array(
            "rh212_matricula = {$servidor->getMatricula()}",
            $whereDatas
        );
        $sSqlDadosJornada = "select * from jornadaservidor where ".implode(" and ", $where);
        $rsDadosJornada = db_query($sSqlDadosJornada);
        if (!$rsDadosJornada) {
            throw new \BusinessException("Erro ao pesquisar as jornadas alternativas do servidor.\n".pg_last_error());
        }

        $jornadas = \db_utils::makeCollectionFromRecord($rsDadosJornada, function($dados) use ($servidor){

            $jornadaAlternativa = new JornadaAlternativaModel();
            $jornadaAlternativa->setData(new \DateTime($dados->rh212_data));
            $jornadaAlternativa->setServidor($servidor);
            $jornadaAlternativa->setJornada($dados->rh212_jornada);
            return $jornadaAlternativa;
        });
        self::getInstance()->jornadas[$servidor->getMatricula()] = $jornadas;
        return $jornadas;
    }

}