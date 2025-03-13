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

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use CgmBase;
use cl_avaliacaogruporespostaaltercontratual;
use Exception;

/**
 * Class S2206
 * @package ECidade\RecursosHumanos\ESocial\Transformer
 */
class S2206 extends Sugestao
{
    /**
     * @var array
     */
    protected $deParaESocial = array(
        'tpregprev',
        'matricula',
        'cpftrab',
        'tpregprev',
        'tpregjor',
        'natatividade',
        'dtbase',
        'cnpjsindcategprof',
        'codcargo',
        'codfuncao',
        'codcateg',
        'vrsalfx',
        'undsalfixo',
        'desccomp',
        'tpregtrab',
        'codcargo',
        'codfuncao',
        'localtrabgeral_desccomp'
    );

    /**
     * @var array
     */
    protected $deParaCamposSimples = array(
        'codcargo' => 'codCargo',
        'codfuncao' => 'codFuncao',
        'tpregtrab' => 'tpRegTrab',
        'matricula' => 'matricula',
        'cpftrab' => 'cpfTrab',
        'nistrab' => 'nisTrab',
        'tpregprev' => 'tpRegPrev',
        'tpregjor' => 'tpRegJor',
        'natatividade' => 'natAtividade',
        'dtbase' => 'dtBase',
        'cnpjsindcategprof' => 'cnpjSindCategProf',
        'codcargo' => 'codCargo',
        'codfuncao' => 'codFuncao',
        'codcateg' => 'codCateg',
        'vrsalfx' => 'vrSalFx',
        'desccomp' => 'descComp',
        'localtrabgeral_desccomp' => 'localTrabGeral_descComp'
    );

    /**
     * @var array
     */
    protected $deParaCamposComplexos = array(
        'tpregprev' => array(
            'tpRegPrev' => array(
                1 => 'tpRegPrev_1',
                2 => 'tpRegPrev_2',
                3 => 'tpRegPrev_3'
            )
        ),
        'undsalfixo' => array(
            'undSalFixo' => array(
                "M" => 'undSalFixo_5',
                "Q" => 'undSalFixo_4',
                "D" => 'undSalFixo_2',
                "H" => 'undSalFixo_1',
            )
        )
    );

    /**
     * @var int
     */
    private $matricula;

    /**
     * @var CgmBase
     */
    private $cgmEmpregador;

    /**
     * S2206 constructor.
     * @param int $matricula
     * @param CgmBase $cgmEmpregador
     */
    public function __construct($matricula, $cgmEmpregador)
    {
        $this->matricula = $matricula;
        $this->cgmEmpregador = $cgmEmpregador;
    }

    /**
     * Essa função tem o objetivo de fazer um depara com os dados do e-cidade para com os dados do eSocial
     * Os campos que precisam desse depara devem ser informados no array $deParaESocial
     *
     * @param $nomeCampo
     * @param $valor
     * @return mixed $valor O valor retornado deve ser o correspondente/equivalente no eSocial
     */
    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
        return $valor;
    }

    /**
     * Realiza algum tratamento nos dados após o parse
     */
    protected function posProcessamento()
    {
        $this->dados['tpRegJor'] = array("option" => "tpRegJor_1");
        $this->dados['natAtividade'] = array("option" => "natAtividade_1");
        $this->dados['tpContr'] = array("option" => "tpContr_1");

        if ($this->dados['tpRegTrab'] !== "2") {
            unset($this->dados['tpRegJor']);
            unset($this->dados['natAtividade']);
            unset($this->dados['cnpjSindCategProf']);
        }
    }

    /**
     * Deve retornar um resource com os dados
     * @return null|resource
     * @throws Exception
     */
    protected function buscarDados()
    {
        $dao = new cl_avaliacaogruporespostaaltercontratual();
        $sql = $dao->sqlDadosAlteracaoCadastral($this->matricula);
        return db_query($sql);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function possuiPreenchimento()
    {
        $dao = new cl_avaliacaogruporespostaaltercontratual();
        $where = " eso20_cgm = {$this->cgmEmpregador->getCodigo()} AND eso20_rhpessoal = {$this->matricula}";
        $sql = $dao->sql_query_file(null, "*", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception(
                "Não foi possível buscar os preenchimentos anteriores do formulário de alteração cadastral.
                Contate o suporte."
            );
        }

        if (pg_numrows($rs) > 0) {
            return true;
        }

        return false;
    }
}
