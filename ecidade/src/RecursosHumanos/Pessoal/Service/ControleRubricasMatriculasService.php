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

namespace ECidade\RecursosHumanos\Pessoal\Service;

use BusinessException;
use DateInterval;
use DatePeriod;
use DateTime;
use DBException;
use ECidade\Core\Helpers\HourHelper;
use ECidade\RecursosHumanos\Pessoal\Builders\ControleRubricasMatriculasBuilder;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasMatriculas;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasMatriculasRepository;
use Exception;
use Instituicao;
use Selecao;
use Servidor;
use ServidorRepository;

/**
 * Class ControleHorasExtrasMatriculasService
 * @package ECidade\RecursosHumanos\Pessoal\Service
 */
class ControleRubricasMatriculasService
{
    /**
     * @var ControleRubricasMatriculasRepository l
     */
    private $repository;

    /**
     * @var ControleRubricasMatriculasBuilder
     */
    private $builder;

    /**
     * ControleHorasExtrasMatriculasService constructor.
     * @param ControleRubricasMatriculasRepository $repository
     * @param ControleRubricasMatriculasBuilder $builder
     */
    public function __construct(
        ControleRubricasMatriculasRepository $repository,
        ControleRubricasMatriculasBuilder $builder
    ) {
        $this->repository = $repository;
        $this->builder = $builder;
    }

    /**
     * @param $ano
     * @param $mes
     * @param Selecao $selecao
     * @param Instituicao $instituicao
     * @return Servidor[]
     * @throws DBException
     */
    public function buscaServidorSelecao($ano, $mes, Selecao $selecao, Instituicao $instituicao)
    {
        return ServidorRepository::getServidoresBySelecao(
            $ano,
            $mes,
            $selecao->getCodigo(),
            $instituicao->getCodigo()
        );
    }

    /**
     * @param Instituicao $instituicao
     * @param HourHelper $hourHelper
     * @param Selecao $selecao
     * @param $matricula
     * @param $ano
     * @param $mes
     * @return string
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    public function getHorasLiberadasParaServidor(
        Instituicao $instituicao,
        HourHelper $hourHelper,
        Selecao $selecao,
        $matricula,
        $ano,
        $mes
    ) {
        if (empty($matricula)) {
            throw new Exception('É necessário informar a matrícula.');
        }
        if (empty($ano)) {
            throw new Exception('É necessário informar o ano.');
        }
        if (empty($mes)) {
            throw new Exception('É necessário informar o mes.');
        }

        $servidores = $this->buscaServidorSelecao($ano, $mes, $selecao, $instituicao);

        $servidor = $this->buscaServidor($instituicao, $matricula, $ano, $mes);

        $permiteCargaMensalElevada = false;
        if (array_key_exists($servidor->getMatricula(), $servidores)) {
            $permiteCargaMensalElevada = true;
        }

        $horasLiberadas = '80:00';

        if (!$permiteCargaMensalElevada) {
            $horasMensais = $servidor->getPorcentagemHorasMensais();
            $horasLiberadas = $hourHelper->convertFloatToHour($horasMensais);
        }

        return $horasLiberadas;
    }

    /**
     * @param Instituicao $instituicao
     * @param $matricula
     * @param $ano
     * @param $mes
     * @return Servidor
     * @throws BusinessException
     */
    public function buscaServidor(Instituicao $instituicao, $matricula, $ano, $mes)
    {
        return ServidorRepository::getInstanciaByCodigo($matricula, $ano, $mes, $instituicao->getCodigo());
    }

    /**
     * @param Instituicao $instituicao
     * @param HourHelper $hourHelper
     * @param Selecao $selecao
     * @param int $matricula
     * @param int $ano
     * @param int $mes
     * @return ControleRubricasMatriculas
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    public function buscaDadosMatricula(
        Instituicao $instituicao,
        HourHelper $hourHelper,
        Selecao $selecao,
        $matricula,
        $ano,
        $mes
    ) {
        if (empty($matricula)) {
            throw new Exception('É necessário informar a matrícula.');
        }
        if (empty($ano)) {
            throw new Exception('É necessário informar o ano.');
        }
        if (empty($mes)) {
            throw new Exception('É necessário informar o mes.');
        }

        $controleHorasExtrasMatriculas = $this->buscaMatriculasConfiguradas($instituicao, $ano, $mes, $matricula);

        /**
         * Retorna o registro configurado caso exista
         */
        if (count($controleHorasExtrasMatriculas) > 0) {
            return array_pop($controleHorasExtrasMatriculas);
        }

        $servidor = $this->buscaServidor($instituicao, $matricula, $ano, $mes);
        $horasLiberadas = $this->getHorasLiberadasParaServidor(
            $instituicao,
            $hourHelper,
            $selecao,
            $matricula,
            $ano,
            $mes
        );

        return $this->builder
            ->instituicao($instituicao)
            ->servidor($servidor)
            ->horasLiberadas($horasLiberadas)
            ->ano($ano)
            ->mes($mes)
            ->build();
    }

    /**
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @param null|int $matricula
     * @return array|ControleRubricasMatriculas[]
     * @throws Exception
     */
    public function buscaMatriculasConfiguradas(Instituicao $instituicao, $ano, $mes, $matricula = null)
    {
        return $this->repository->buscaMatriculasConfiguradas($instituicao, $ano, $mes, $matricula);
    }

    /**
     * @param Instituicao $instituicao
     * @param HourHelper $hourHelper
     * @param Selecao $selecao
     * @param $matricula
     * @param $horasLiberadas
     * @param $ano
     * @param $mes
     * @return ControleRubricasMatriculas
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    private function preparaRegistrosParaSalvar(
        Instituicao $instituicao,
        HourHelper $hourHelper,
        Selecao $selecao,
        $matricula,
        $horasLiberadas,
        $ano,
        $mes
    ) {
        $servidor = $this->buscaServidor($instituicao, $matricula, $ano, $mes);

        $horasExtrasLiberadasServidor = $this->getHorasLiberadasParaServidor(
            $instituicao,
            $hourHelper,
            $selecao,
            $matricula,
            $ano,
            $mes
        );

        $maximoHorasExtrasServidor = $hourHelper->convertHourToFloat($horasExtrasLiberadasServidor);
        $horasExtrasDesejadas = $hourHelper->convertHourToFloat($horasLiberadas);

        if ($horasExtrasDesejadas > $maximoHorasExtrasServidor) {
            throw new Exception(
                "O limite máximo de horas para esse servidor é de {$horasExtrasLiberadasServidor}."
            );
        }

        $controleHorasExtrasMatriculas = $this->builder
            ->instituicao($instituicao)
            ->servidor($servidor)
            ->ano($ano)
            ->mes($mes)
            ->horasLiberadas($horasLiberadas)
            ->build();

        if (!$controleHorasExtrasMatriculas->validaHorasLiberadas()) {
            throw new Exception('Verifique o formato das horas liberadas.');
        }

        $this->removerControleHorasExtrasMatricula(
            $controleHorasExtrasMatriculas->getInstituicao(),
            $controleHorasExtrasMatriculas->getServidor()->getMatricula(),
            $ano,
            $mes
        );

        return $controleHorasExtrasMatriculas;
    }

    /**
     * @param Instituicao $instituicao
     * @param HourHelper $hourHelper
     * @param Selecao $selecao
     * @param int $matricula
     * @param int $ano
     * @param int $mes
     * @param string $horasLiberadas
     * @return ControleRubricasMatriculas
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    public function salvarControleHorasExtrasMatricula(
        Instituicao $instituicao,
        HourHelper $hourHelper,
        Selecao $selecao,
        $matricula,
        $ano,
        $mes,
        $horasLiberadas
    ) {
        $controleHorasExtrasMatriculas = $this->preparaRegistrosParaSalvar(
            $instituicao,
            $hourHelper,
            $selecao,
            $matricula,
            $horasLiberadas,
            $ano,
            $mes
        );

        return $this->repository->save($controleHorasExtrasMatriculas);
    }

    /**
     * @param Instituicao $instituicao
     * @param int $matricula
     * @param int $ano
     * @param int $mes
     * @return int
     * @throws Exception
     */
    public function removerControleHorasExtrasMatricula(Instituicao $instituicao, $matricula, $ano, $mes)
    {
        if (empty($matricula)) {
            throw new Exception('É necessário informar a matrícula para exclusão.');
        }
        if (empty($ano)) {
            throw new Exception('É necessário informar o ano para exclusão.');
        }
        if (empty($mes)) {
            throw new Exception('É necessário informar o mês para exclusão.');
        }

        $servidor = $this->buscaServidor($instituicao, $matricula, $ano, $matricula);

        return $this->repository->scopeServidor($servidor)
            ->scopeInstituicao($instituicao)
            ->scopeQuery('ano_mes', "
                (
                    (rh234_ano > {$ano}) OR
                    (rh234_ano = {$ano} AND rh234_mes >= {$mes})
                )
            ")
            ->destroy();
    }

    /**
     * @param Instituicao $instituicao
     * @param HourHelper $hourHelper
     * @param Selecao $selecao
     * @param int $matricula
     * @param string $horasLiberadas
     * @param int $anoAtual
     * @param int $mesAtual
     * @param int $anoDestino
     * @param int $mesDestino
     * @return ControleRubricasMatriculas
     * @throws BusinessException
     * @throws DBException
     * @throws Exception
     */
    public function salvaPropagaCompetencia(
        Instituicao $instituicao,
        HourHelper $hourHelper,
        Selecao $selecao,
        $matricula,
        $horasLiberadas,
        $anoAtual,
        $mesAtual,
        $anoDestino,
        $mesDestino
    ) {
        if (empty($matricula)) {
            throw new Exception('É necessário informar a matricula.');
        }
        if (empty($horasLiberadas)) {
            throw new Exception('É necessário informar a quantidade de horas liberadas.');
        }
        if (empty($anoAtual)) {
            throw new Exception('É necessário informar o ano atual.');
        }
        if (empty($mesAtual)) {
            throw new Exception('É necessário informar o mes atual.');
        }
        if (empty($anoDestino)) {
            throw new Exception('É necessário informar o ano de destino.');
        }
        if (empty($mesDestino)) {
            throw new Exception('É necessário informar o mes de destino.');
        }

        $controleHorasExtrasMatriculas = $this->preparaRegistrosParaSalvar(
            $instituicao,
            $hourHelper,
            $selecao,
            $matricula,
            $horasLiberadas,
            $anoAtual,
            $mesAtual
        );

        /**
         * Faz um loop entre a competencia atual e destino
         * para salvar todos os registros
         */
        $competenciaAtual = new DateTime("{$anoAtual}-{$mesAtual}-01");
        $competenciaDestino = new DateTime("{$anoDestino}-{$mesDestino}-02");
        $intervaloMensal = DateInterval::createFromDateString('1 month');
        $competencias = new DatePeriod($competenciaAtual, $intervaloMensal, $competenciaDestino);

        /**
         * @var DateTime $competencia
         */
        foreach ($competencias as $competencia) {
            $controleHorasExtrasMatriculas->setSequencial(null);
            $controleHorasExtrasMatriculas->setAno((int)$competencia->format('Y'));
            $controleHorasExtrasMatriculas->setMes((int)$competencia->format('m'));
            $this->repository->save($controleHorasExtrasMatriculas);
        }

        return $controleHorasExtrasMatriculas;
    }
}
