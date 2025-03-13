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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service;

use ECidade\Educacao\Escola\Censo\Censo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro20Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro20;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\TurmaCensoVo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository\Registro20Repository;
use Escola;
use Exception;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Validators\Registro20Validator;

class Registro20Service
{

    /**
     * @var Escola
     */
    private $escola;

    /**
     * @var Registro20[]
     */
    private $registros = array();
    /**
     * @var Censo
     */
    private $censo;

    /**
     * @var TurmaCensoVo[]
     */
    protected static $turmasProcessadas = array();

    /**
     * @var TurmaCensoVo[]
     */
    protected static $turmasVinculadas = array();

    /**
     * @var Registro00Service
     */
    private $registro00Service;

    /**
     * @var Registro10Service
     */
    private $registro10Service;

    protected $camposTurmasRegulares = array(
        "ed57_i_codigoinep as inep_turma",
        "ed57_i_codigo as turma",
        "ed57_i_escola as escola",
        "ed57_i_turno as turno",
        "ed57_i_base as base",
        "trim(ed57_c_descr) as nome",
        "fc_nomeetapaturma(ed57_i_codigo) as nome_etapa_turma",
        "ed36_i_codigo as tipo_ensino",
        "ed57_i_censocursoprofiss as codigo_curso_profissional",
        "ed132_censoetapa as censo_etapa",
        "ed57_i_tipoturma as tipo_turma",
        "ed57_censoprogramamaiseducacao as programa_mais_educacao",
        "ed10_mediacaodidaticopedagogica as mediacao_didatico_pedagogica",
        "true as regular",
        "exists(select 1 from turmaatividadecomplementar where ed146_turma = ed57_i_codigo) as atividade_complementar",
        "case
            when ed16_c_pertence = 'S' then 0
            else ed16_local_funcionamento
        end as local_funcionamento"
    );

    private $camposTurmasEspeciais = array(
        "ed268_i_codigoinep as inep_turma",
        "ed268_i_codigo as turma",
        "ed268_i_escola as escola",
        "ed268_i_turno as turno",
        "trim(ed268_c_descr) as nome",
        "(SELECT ed346_horainicial
            from turmaachorarioprofissional
           where ed346_turmaac = turmaac.ed268_i_codigo
           order by ed346_diasemana
           limit 1) as hora_inicio",
        "(SELECT ed346_horafinal
           from turmaachorarioprofissional
          where ed346_turmaac = turmaac.ed268_i_codigo
          order by ed346_diasemana
           limit 1) as hora_fim",
        "case when ed268_i_tipoatend = 4 then '' else ed268_c_aee end as atividades_apoio",
        "ed268_programamaiseducacao as programa_mais_educacao",
        "1 as mediacao_didatico_pedagogica",
        "case when ed268_i_tipoatend = 4 then true else false end as atividade_complementar",
        "case when ed268_i_tipoatend = 5 then true else false end as atendimento_aee",
    );
    /**
     * @var Registro20Repository
     */
    private $registro20Reposiroty;

    /**
     * @var Registro50Service
     */
    private $registro50Service;

    /**
     * @var Registro60Service
     */
    private $registro60Service;

    public function __construct()
    {
        $this->registro20Reposiroty = new Registro20Repository();
    }

    /**
     * @param Escola $escola
     * @return Registro20Service
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
        return $this;
    }

    public function setRegistro00Service(Registro00Service $registro00Service)
    {
        $this->registro00Service = $registro00Service;
    }

    public function setRegistro10Service(Registro10Service $registro10Service)
    {
        $this->registro10Service = $registro10Service;
    }

    /**
     * @param Censo $censo
     * @return Registro20Service
     */
    public function setCenso(Censo $censo)
    {
        $this->censo = $censo;
        return $this;
    }

    /**
     * @return Registro20[]
     */
    public function getRegistros()
    {
        return $this->registros;
    }

    /**
     * @throws Exception
     */
    public function buscarDados()
    {
        $diasLetivo = $this->buscarDiasLetivo();
        $this->buscarTurmasRegulares($diasLetivo);
        $this->buscarTurmasEspeciais();
    }

    /**
     * @return array
     * @throws Exception
     */
    private function buscarDiasLetivo()
    {
        return $this->registro20Reposiroty->getDiasLetivo($this->escola);
    }

    /**
     * @param TurmaCensoVo $turmaRegular
     * @throws Exception
     */
    private function calcularHorarioTurma(TurmaCensoVo $turmaRegular)
    {
        $horarios = $this->registro20Reposiroty->getHorariosInicioFimTurnoTurma($turmaRegular);
        $turmaRegular->setHoraInicio($horarios['hora_inicio']);
        $turmaRegular->setHoraFim($horarios['hora_fim']);
    }

    /**
     * @param array $diasLetivo
     * @throws Exception
     */
    private function buscarTurmasRegulares(array $diasLetivo)
    {
        $this->registro20Reposiroty->resetScopes();
        $turmasUnificadas = $this->registro20Reposiroty->getTurmasMultietapaEnsinoDiferente(
            $this->escola,
            $this->censo->getDataCenso()
        );
        $turmasPrincipais = array();
        $turmasDescartar = array();

        // Arrays para vincular turmas multietapa de ensino diferente
        $turmasCodCenso = array();
        $unirTurmas = array();

        foreach ($turmasUnificadas as $turmaUnificada) {
            if ($turmaUnificada['ed343_principal'] === 't') {
                $turmasPrincipais[$turmaUnificada['ed343_turma']] = $turmaUnificada;
                $turmasCodCenso[$turmaUnificada['ed343_turma']] = $turmaUnificada['ed342_sequencial'];
            } else {
                $unirTurmas[$turmaUnificada['ed342_sequencial']][] = $turmaUnificada['ed343_turma'];
                $turmasDescartar[] = $turmaUnificada['ed343_turma'];
            }
        }

        $turmasRegulares = $this->registro20Reposiroty->scopeEscola($this->escola)
            ->scopeAnoCalendario($this->censo->getAno())
            ->scopeExistsMatricula($this->censo->getDataCenso())
            ->getTurmasRegulares($this->camposTurmasRegulares);

        foreach ($turmasRegulares as $index => $turmaRegular) {
            if (array_key_exists($turmaRegular->getCodigoTurma(), $turmasPrincipais)) {
                $dadosTurmaUnificada = $turmasPrincipais[$turmaRegular->getCodigoTurma()];

                $turmaRegular->setTurmaUnificada(true);
                $turmaRegular->setEtapaCenso($dadosTurmaUnificada['ed134_censoetapa']);
                $turmaRegular->setNomeTurma($dadosTurmaUnificada['ed342_nome']);
                $turmaRegular->setCodigoTurmaUnificada($dadosTurmaUnificada['ed342_sequencial']);
            }

            $turmaRegular->setDisciplinas($this->registro20Reposiroty->getDisciplinasCensoTurma(
                $turmaRegular->getCodigoTurma(),
                $this->censo->getDataCenso()
            ));
            $turmaRegular->setAtividadesComplementar(
                $this->registro20Reposiroty->getAtividadesComplementaresTurmasRegulares($turmaRegular->getCodigoTurma())
            );

            if (!empty($turmasCodCenso[$turmaRegular->getCodigoTurma()])) {
                $codTurmaCenso = $turmasCodCenso[$turmaRegular->getCodigoTurma()];
                if (!empty($unirTurmas[$codTurmaCenso])) {
                    $turmaRegular->setCodigosTurmasVinculadas($unirTurmas[$codTurmaCenso]);
                }
            }

            if (in_array($turmaRegular->getCodigoTurma(), $turmasDescartar)) {
                self::$turmasVinculadas[] = $turmaRegular;
                unset($turmasRegulares[$index]);
                continue;
            }

            $this->addTurma($turmaRegular);
            $this->calcularHorarioTurma($turmaRegular);

            $registro20Builder = new Registro20Builder();
            $registro20Builder->setDadosTurma($turmaRegular)->setDiasLetivo($diasLetivo);
            $this->addRegistro($registro20Builder->build());
        }
    }

    /**
     * @param Registro20 $registro
     */
    private function addRegistro(Registro20 $registro)
    {
        $this->registros[] = $registro;
    }

    /**
     * Turmas do tipo atividade complementar e nee (turmaac)
     * @throws Exception
     */
    private function buscarTurmasEspeciais()
    {
        $this->registro20Reposiroty->resetScopes();
        $turmasEspeciais = $this->registro20Reposiroty->scopeAnoCalendario($this->censo->getAno())
            ->scopeEscola($this->escola, '=', 'ed268_i_escola')
            ->scopeAlunoMatriculadoTurmaEspecial($this->censo->getDataCenso(), "<=")
            ->getTurmasEspeciais($this->camposTurmasEspeciais);

        foreach ($turmasEspeciais as $index => $turmaEspecial) {
            if ($turmaEspecial->isAtividadeComplementar()) {
                if (!$this->registro20Reposiroty->hasAlunosMatriculadosNaEscola($turmaEspecial, $this->censo)) {
                    unset($turmasEspeciais[$index]);
                    continue;
                }
            }

            $turmaEspecial->setAtividadesComplementar(
                $this->registro20Reposiroty->getAtividadesComplementarTurmaEspecial($turmaEspecial)
            );
            $this->addTurma($turmaEspecial);

            $registro20Builder = new Registro20Builder();
            $diasLetivo = $this->registro20Reposiroty->getDiasLetivoTurmaEspecial($turmaEspecial);
            $registro20Builder->setDadosTurma($turmaEspecial)->setDiasLetivo($diasLetivo);
            $this->addRegistro($registro20Builder->build());
        }
    }

    public function validar()
    {
        foreach ($this->registros as $registro) {
            $validator = new Registro20Validator();

            $validator->setRegistro($registro);
            $validator->setRegistro00($this->registro00Service->getRegistro());
            $validator->setRegistro10($this->registro10Service->getRegistro());
            $validator->setRegistros50($this->registro50Service->getRegistros());
            $validator->setRegistros60($this->registro60Service->getRegistros());

            $validator->validar();
        }
    }

    /**
     * @param TurmaCensoVo $turma
     */
    private function addTurma(TurmaCensoVo $turma)
    {
        self::$turmasProcessadas[] = $turma;
    }

    /**
     * @return TurmaCensoVo[]
     */
    public static function getTurmasProcessadas()
    {
        return self::$turmasProcessadas;
    }

    public static function getTurmaVinculada($codTurma)
    {
        foreach (self::$turmasVinculadas as $turma) {
            if ($turma->getCodigoTurma() == $codTurma) {
                return $turma;
            }
        }

        return null;
    }

    /**
     * @param Registro50Service $registro50
     */
    public function setRegistro50Service(Registro50Service $registro50)
    {
        $this->registro50Service = $registro50;
    }

    /**
     * @param Registro60Service $registro60
     */
    public function setRegistro60Service(Registro60Service $registro60)
    {
        $this->registro60Service = $registro60;
    }
}
