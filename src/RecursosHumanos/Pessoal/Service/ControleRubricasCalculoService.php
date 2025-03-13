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
use DBCompetencia;
use ECidade\Core\Helpers\HourHelper;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasCalculoParametros;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametrosRubricas;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasMatriculasRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRubricasRepository;
use Exception;
use Instituicao;
use RubricaRepository;
use Servidor;
use ServidorRepository;

class ControleRubricasCalculoService
{
    /**
     * @var ControleRubricasMatriculasRepository
     */
    private $repositoryMatriculas;
    /**
     * @var ControleRubricasParametrosRepository
     */
    private $repositoryParametros;
    /**
     * @var ControleRubricasParametrosRubricasRepository
     */
    private $repositoryRubricas;
    /**
     * @var HourHelper
     */
    private $hourHelper;

    /**
     * ControleHorasExtrasCalculo constructor.
     * @param ControleRubricasMatriculasRepository $repositoryMatriculas
     * @param ControleRubricasParametrosRepository $repositoryParametros
     * @param ControleRubricasParametrosRubricasRepository $repositoryRubricas
     * @param HourHelper $hourHelper
     */
    public function __construct(
        ControleRubricasMatriculasRepository $repositoryMatriculas,
        ControleRubricasParametrosRepository $repositoryParametros,
        ControleRubricasParametrosRubricasRepository $repositoryRubricas,
        HourHelper $hourHelper
    ) {
        $this->repositoryMatriculas = $repositoryMatriculas;
        $this->repositoryParametros = $repositoryParametros;
        $this->repositoryRubricas = $repositoryRubricas;
        $this->hourHelper = $hourHelper;
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @param $rubrica
     * @return bool|ControleRubricasParametrosRubricas
     * @throws BusinessException
     * @throws Exception
     */
    public function buscaConfiguracaoRubrica(Instituicao $instituicao, DBCompetencia $competencia, $rubrica)
    {
        $rubrica = RubricaRepository::getInstanciaByCodigo($rubrica);
        return $this->repositoryRubricas->findOneByParams($instituicao, $competencia, $rubrica);
    }

    /**
     * @param $matricula
     * @return Servidor
     * @throws BusinessException
     */
    public function buscaServidorComRubricasPonto($matricula)
    {
        return ServidorRepository::getInstanciaByCodigo($matricula)->withRubricasPonto();
    }

    /**
     * @param ControleRubricasCalculoParametros $calculoParametros
     * @return boolean
     * @throws BusinessException
     * @throws Exception
     */
    public function verificaInclusaoRubricaServidor(ControleRubricasCalculoParametros $calculoParametros)
    {
        $instituicao = $calculoParametros->getInstituicao();
        $matricula = $calculoParametros->getMatriculaServidor();
        $competencia = $calculoParametros->getCompetencia();
        $rubrica = $calculoParametros->getCodigoRubrica();
        $quantidadeAdicionada = $calculoParametros->getQuantidadeAdicionada();
        $tabela = $calculoParametros->getTabela();
        $isAlteracao = $calculoParametros->isAlteracao();

        $controleRubrica = $this->buscaConfiguracaoRubrica($instituicao, $competencia, $rubrica);

        /**
         * Caso a rubrica não possua configuração para a instituição/competência
         * libera a inclusão normalmente de rubricas para o servidor
         */
        if (empty($controleRubrica)) {
            return true;
        }

        $controleHorasExtrasMatricula = $this->repositoryMatriculas
            ->buscaConfiguracoesMatricula(
                $instituicao,
                $competencia->getAno(),
                $competencia->getMes(),
                $matricula
            );

        /**
         * Caso o servidor não possua configuração para a instituição/competência
         * bloqueia a inclusão
         */
        if (empty($controleHorasExtrasMatricula)) {
            $mensagem = "Servidor(a) [{$matricula}] não possuí liberação para essa rubrica.\n";
            $mensagem .= "Verifique a rotina: ";
            $mensagem .= "DB:RECURSOSHUMANOS > Pessoal > Procedimentos > Controle de Rubricas > Manutenção";
            throw new Exception($mensagem);
        }

        $servidor = $this->buscaServidorComRubricasPonto($matricula);

        $parametrosHorasExtras = $this->repositoryParametros
            ->buscarPorInstituicaoECompetencia($instituicao, $competencia);

        $rubricasServidor = $servidor->getRubricasPonto();
        $rubricasConfiguradas = $parametrosHorasExtras->getControleHorasExtrasRubricas();

        $totalHorasExtrasServidor = (float) $quantidadeAdicionada;
        foreach ($rubricasServidor as $rubricaServidor) {
            foreach ($rubricasConfiguradas as $rubricaConfigurada) {
                if ($rubricaServidor->getCodigo() === $rubricaConfigurada->getRubrica()->getCodigo()) {
                    if ($isAlteracao
                        && $controleRubrica->getRubrica()->getCodigo() == $rubricaServidor->getCodigo()
                        && $tabela == $rubricaServidor->getTabelaServidor()
                    ) {
                        continue;
                    }

                    $totalHorasExtrasServidor += $rubricaServidor->getQuantidadeAtualServidor();
                    continue;
                }
            }
        }

        $horasLiberadasServidor = $controleHorasExtrasMatricula->getHorasLiberadas();
        $totalHorasLiberadasServidor = $this->hourHelper->convertHourToFloat($horasLiberadasServidor);

        if ($totalHorasExtrasServidor > $totalHorasLiberadasServidor) {
            return false;
        }

        return true;
    }
}
