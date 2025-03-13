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
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametros;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametrosRubricas;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\RubricasUsuarioRepository;
use Instituicao;
use Exception;
use RubricaRepository;
use Selecao;

/**
 * Class ControleHorasExtrasService
 * @package ECidade\RecursosHumanos\Pessoal\Service
 */
class ControleRubricasParametrosService
{
    private $repositorio;

    /**
     * ControleHorasExtrasService constructor.
     * @param ControleRubricasParametrosRepository $repositorio
     */
    public function __construct(ControleRubricasParametrosRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * @param $sequencial
     * @return bool|ControleRubricasParametros
     * @throws Exception
     */
    public function buscaControleHorasExtras($sequencial)
    {
        return $this->repositorio->find($sequencial);
    }

    /**
     * @param $parametros
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @return ControleRubricasParametros
     * @throws BusinessException
     * @throws Exception
     */
    public function salvar($parametros, Instituicao $instituicao, DBCompetencia $competencia)
    {
        if (!$parametros->codigoSelecao) {
            throw new Exception("Selecione uma seleção.");
        }

        $controleHorasExtras = new ControleRubricasParametros();
        $controleHorasExtras->setSequencial($parametros->sequencial);
        $controleHorasExtras->setInstituicao($instituicao);
        $controleHorasExtras->setSelecao(new Selecao($parametros->codigoSelecao));
        $controleHorasExtras->setAno($competencia->getAno());
        $controleHorasExtras->setMes($competencia->getMes());

        if (!empty($parametros->rubricas)) {
            $codigosRubricas =  explode(",", $parametros->rubricas);

            foreach ($codigosRubricas as $codigo) {
                $controleHorasExtrasRubricas = new ControleRubricasParametrosRubricas();
                $controleHorasExtrasRubricas->setInstituicao($instituicao);
                $controleHorasExtrasRubricas->setRubrica(RubricaRepository::getInstanciaByCodigo($codigo));
                $controleHorasExtras->addControleHorasExtrasRubricas($controleHorasExtrasRubricas);
            }
        }

        try {
            $controleHorasExtras = $this->repositorio->salvar($controleHorasExtras);
        } catch (Exception $e) {
            throw new Exception('Não foi possível salvar o controle de horas extras.');
        }

        return $controleHorasExtras;
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @return bool|ControleRubricasParametros
     * @throws Exception
     */
    public function buscarPorInstituicaoECompetencia(Instituicao $instituicao, DBCompetencia $competencia)
    {
        return $this->repositorio->buscarPorInstituicaoECompetencia($instituicao, $competencia);
    }

    /**
     * @param $parametros
     * @throws Exception
     */
    public function remover($parametros)
    {
        if (!$parametros->sequencial) {
            throw new Exception("Não foi encontrado o sequencial do controle de horas extras.");
        }

        $controleHorasExtras = new ControleRubricasParametros($parametros->sequencial);
        if (!$this->permitirExclusao($controleHorasExtras)) {
            $mensagem = "Não é possível realizar a exclusão pois existem rubricas já vinculadas há algum servidor.";
            throw new Exception($mensagem);
        }
        $this->repositorio->remover($controleHorasExtras);
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @return bool
     * @throws Exception
     */
    private function permitirExclusao(ControleRubricasParametros $controleHorasExtras)
    {
        $controleHorasExtras->withRubricas();
        if ($controleHorasExtras->getControleHorasExtrasRubricas()) {
            foreach ($controleHorasExtras->getControleHorasExtrasRubricas() as $controleHorasExtrasRubrica) {
                if (!$controleHorasExtrasRubrica->isPermiteExclusao()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @throws Exception
     */
    public function atualizaCompetenciaControleHoraExtra(Instituicao $instituicao, DBCompetencia $competencia)
    {
        $ano = $competencia->getAno();
        $mes = $competencia->getMes();

        $controleHoraExtra = $this->repositorio->buscarPorInstituicaoECompetencia($instituicao, $competencia);

        if (!$controleHoraExtra) {
            return;
        }
        if ($mes == 12) {
            $mes = 1;
            $ano += 1;
        } else {
            $mes += 1;
        }

        $controleHoraExtra->setSequencial(null);
        $controleHoraExtra->setAno($ano);
        $controleHoraExtra->setMes($mes);

        foreach ($controleHoraExtra->getControleHorasExtrasRubricas() as $rubrica) {
            $rubrica->setSequencial(null);
        }

        $this->repositorio->salvar($controleHoraExtra);
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @throws Exception
     */
    public function excluirCompetenciaControleHoraExtra(Instituicao $instituicao, DBCompetencia $competencia)
    {
        $controleHoraExtra = $this->repositorio->buscarPorInstituicaoECompetencia($instituicao, $competencia);

        if ($controleHoraExtra) {
            $this->repositorio->remover($controleHoraExtra);
        }
    }
}
