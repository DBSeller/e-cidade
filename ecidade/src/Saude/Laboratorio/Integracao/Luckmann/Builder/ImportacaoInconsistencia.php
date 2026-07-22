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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Builder;

use AtributoExame;
use RequisicaoExame;
use RequisicaoLaboratorial;
use stdClass;

/**
 * Class ImportacaoInconsistencia
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Builder
 */
class ImportacaoInconsistencia
{
    /**
     * @var ImportacaoInconsistencia
     */
    protected static $instance;

    /**
     * @var array
     */
    private $inconsistencias = array();

    /**
     * @var int
     */
    private $codigoRequisicao;

    /**
     * @var int
     */
    private $codigoExame;

    public function __construct()
    {
    }

    /**
     * @return ImportacaoInconsistencia
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param RequisicaoLaboratorial $requisicaoLaboratorial
     * @param RequisicaoExame $requisicaoExame
     * @param AtributoExame $atributoExame
     * @throws \BusinessException
     */
    public function addInconsistencia(
        RequisicaoLaboratorial $requisicaoLaboratorial,
        RequisicaoExame $requisicaoExame,
        AtributoExame $atributoExame
    ) {
        $this->codigoRequisicao = $requisicaoLaboratorial->getCodigo();
        $this->codigoExame = $requisicaoExame->getExame()->getCodigo();

        $this->addRequisicao();
        $this->addExame($requisicaoExame);
        $this->addAtributo($atributoExame);

        $this->limparPropriedades();
    }

    private function addRequisicao()
    {
        if (!isset($this->inconsistencias[$this->codigoRequisicao])) {
            $this->inconsistencias[$this->codigoRequisicao] = new stdClass();
            $this->inconsistencias[$this->codigoRequisicao]->requisicao = $this->codigoRequisicao;
            $this->inconsistencias[$this->codigoRequisicao]->exames = array();
        }
    }

    /**
     * @param RequisicaoExame $requisicaoExame
     * @throws \BusinessException
     */
    private function addExame(RequisicaoExame $requisicaoExame)
    {
        $exame = $requisicaoExame->getExame();
        $setor = $requisicaoExame->getLaboratorioSetor();
        $laboratorio = $requisicaoExame->getLaboratorio();
        $inconsistenciaRequisicao = $this->inconsistencias[$this->codigoRequisicao];

        if (!isset($inconsistenciaRequisicao->exames[$exame->getCodigo()])) {
            $dadosExame = new stdClass();
            $dadosExame->codigoExame = $exame->getCodigo();
            $dadosExame->nomeExame = $exame->getNome();
            $dadosExame->codigoLaboratorio = $laboratorio->getCodigo();
            $dadosExame->nomeLaboratorio = $laboratorio->getDescricao();
            $dadosExame->codigoSetor = $setor->getCodigo();
            $dadosExame->nomeSetor = $setor->getDescricao();
            $dadosExame->nome = $exame->getNome();
            $dadosExame->atributos = array();

            $inconsistenciaRequisicao->exames[$exame->getCodigo()] = $dadosExame;
        }

        $this->inconsistencias[$this->codigoRequisicao] = $inconsistenciaRequisicao;
    }

    /**
     * @param AtributoExame $atributoExame
     */
    private function addAtributo(AtributoExame $atributoExame)
    {
        $inconsistenciaExame = $this->inconsistencias[$this->codigoRequisicao]->exames[$this->codigoExame];

        if (!isset($inconsistenciaExame->atributos[$atributoExame->getCodigo()])) {
            $inconsistenciaExame->atributos[$atributoExame->getCodigo()] = new stdClass();
            $inconsistenciaExame->atributos[$atributoExame->getCodigo()]->codigo = $atributoExame->getCodigo();
            $inconsistenciaExame->atributos[$atributoExame->getCodigo()]->nome = $atributoExame->getNome();
        }

        $this->inconsistencias[$this->codigoRequisicao]->exames[$this->codigoExame] = $inconsistenciaExame;
    }

    /**
     * @return array
     */
    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    private function limparPropriedades()
    {
        $this->codigoRequisicao = null;
        $this->codigoExame = null;
    }

    /**
     * @return bool
     */
    public function temInconsistencias()
    {
        return !empty($this->inconsistencias);
    }
}
