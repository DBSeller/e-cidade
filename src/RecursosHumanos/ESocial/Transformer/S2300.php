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

use cl_rhpessoal;
use ECidade\RecursosHumanos\ESocial\Factory\CamposS2300;
use Exception;

/**
 * Class S2300
 * Essa classe transforma os dados salvos no e-Cidade de uma forma que possa ser preenchido na avaliação
 *
 * @package ECidade\RecursosHumanos\ESocial\Transformer
 */
class S2300 extends Sugestao
{
    /**
     * @var array
     */
    protected $deParaESocial = array(
        'paisnac',
        'paisnascto',
        'racacor',
        'estciv',
        'grauinstr',
        'categoriacnh',
        'tpdep',
        'undsalfixo',
        'tplograd',
        'infonus',
        'sexo'
    );

    /**
     * @var array
     */
    protected $deParaCamposSimples = array(
        'cpftrab' => 'cpfTrab',
        'nistrab' => 'nisTrab',
        'nmtrab' => 'nmTrab',
        'nrctps' => 'nrCtps',
        'seriectps' => 'serieCtps',
        'ufctps' => 'ufCtps',
        'nrrg' => 'nrRg',
        'orgaoemissor_rg' => 'orgaoEmissor_RG',
        'dtexped_rg' => 'dtExped_RG',
        'nrregcnh' => 'nrRegCnh',
        'dtvalid_cnh' => 'dtValid_CNH',
        'paisnascto' => 'paisNascto',
        'paisnac' => 'paisNac',
        'dtnascto' => 'dtNascto',
        'uf' => 'uf',
        'nmpai' => 'nmPai',
        'nmmae' => 'nmMae',
        'nrrne' => 'nrRne',
        'tplograd' => 'tpLograd_brasil',
        'dsclograd' => 'dscLograd_brasil',
        'nrlograd' => 'nrLograd_brasil',
        'complemento' => 'complemento_brasil',
        'bairro' => 'bairro_brasil',
        'cep' => 'cep_brasil',
        'codmunic' => 'codMunic_brasil',
        'uf' => 'uf_brasil',
        'foneprinc' =>'fonePrinc',
        'fonealternat' =>'foneAlternat',
        'emailprinc' =>'emailPrinc',
        'codcargo' => 'codCargo',
        'codfuncao' => 'codFuncao',
        'dtopcfgts' => 'dtOpcFGTS',
        'vrsalfx' => 'vrSalFx',
        'cnpjcednt' =>' cnpjCednt',
        'rh02_cedencia' => 'tipo_cedencia',
        'cnpjcednt' => 'cnpjCednt'
    );

    /**
     * @var array
     */
    protected $deParaCamposComplexos = array (
        'racacor' => array(
            'racaCor' =>array(
                1 => 'racaCor_1',
                2 => 'racaCor_2',
                3 => 'racaCor_3',
                4 => 'racaCor_4',
                5 => 'racaCor_5',
                6 => 'racaCor_6'
            )
        ),
        'estciv' => array(
            'estCiv' => array(
                1 => 'estCiv_1',
                2 => 'estCiv_2',
                3 => 'estCiv_3',
                4 => 'estCiv_4',
                5 => 'estCiv_5'
            )
        ),
        'categoriacnh' => array (
            'categoriaCnh' => array (
                'A' => 'categoriaCnh_A',
                'B' => 'categoriaCnh_B',
                'C' => 'categoriaCnh_C',
                'D' => 'categoriaCnh_D',
                'E' => 'categoriaCnh_E',
                'AB' => 'categoriaCnh_AB',
                'AC' => 'categoriaCnh_AC',
                'AD' => 'categoriaCnh_AD',
                'AE' => 'categoriaCnh_AE',
            )
        ),
        'grauinstr' => array(
            'grauInstr'=> array (
                '01' => 'grauInstr_01',
                '02' => 'grauInstr_02',
                '03' => 'grauInstr_03',
                '04' => 'grauInstr_04',
                '05' => 'grauInstr_05',
                '06' => 'grauInstr_06',
                '07' => 'grauInstr_07',
                '08' => 'grauInstr_08',
                '09' => 'grauInstr_09',
                '10' => 'grauInstr_10',
                '11' => 'grauInstr_11',
                '12' => 'grauInstr_12',
            )
        ),
        'undsalfixo' => array(
            'undSalFixo'=> array(
                1 => 'undSalFixo_1',
                2 => 'undSalFixo_2',
                3 => 'undSalFixo_3',
                4 => 'undSalFixo_4',
                5 => 'undSalFixo_5',
                6 => 'undSalFixo_6',
                7 => 'undSalFixo_7',
            )
        ),
        'opcfgts' => array(
            'opcFGTS' => array(
                1 => 'opcFGTS_1',
                2 => 'opcFGTS_2',
            )
        ),
        'tpregtrab' => array(
            'tpRegTrab' => array(
                1 => 'tpRegTrab_1',
                2 => 'tpRegTrab_2'
            )
        ),
        'tpregprev' => array(
            'tpRegPrev' => array(
                1 => 'tpRegPrev_1',
                2 => 'tpRegPrev_2',
                3 => 'tpRegPrev_3',
            )
        ),
        'infonus' => array(
            'infOnus' => array(
                1 => 'infOnus_1',
                2 => 'infOnus_2',
                3 => 'infOnus_3',
            )
        ),
        'sexo' => array(
            'sexo' => array(
                'M' => 'sexo_M',
                'F' => 'sexo_F',
            )
        )
    );

    private $dependentes = array(
        'tpdep' => array(
            'tpDep' => array(
                '01' => 'tpDep_01',
                '02' => 'tpDep_02',
                '03' => 'tpDep_03',
                '04' => 'tpDep_04',
                '06' => 'tpDep_06',
                '09' => 'tpDep_09',
                '10' => 'tpDep_10',
                '11' => 'tpDep_11',
                '12' => 'tpDep_12',
                '99' => 'tpDep_99',
            )
        ),
        'nmdep' => 'nmDep',
        'dtnascto' => 'dtNascto',
        'cpfdep' => 'cpfDep',
        'sexo' => array(
            'sexo' => array(
                'M' => 'sexo_M',
                'F' => 'sexo_F',
            )
        )
    );

    private $matricula;

    public function __construct($matricula)
    {
        $this->matricula = $matricula;
    }

    public function parse()
    {
        if ($this->possuiPreenchimento()) {
            return null;
        }
        parent::parse();

        $this->processaDependentes();
        return $this->dados;
    }

    protected function buscarDados()
    {
        $dao = new cl_rhpessoal();
        $rs = db_query($dao->sqlServidorSemVinculoESocial($this->matricula));

        if (!$rs) {
            throw new Exception("Erro ao buscar dados do servidor.\n" . pg_last_error());
        }

        if (pg_num_rows($rs) == 0) {
            return null;
        }

        return $rs;
    }

    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
        $campo = CamposS2300::getCampo($nomeCampo);
        return $campo->getValue($valor);
    }

    /**
     * Verifica se o servidor possui preenchimento
     * @return bool
     * @throws Exception
     */
    protected function possuiPreenchimento()
    {
        $dao = new \cl_avaliacaogruporespostatsveinicial();
        $rs = db_query($dao->sql_query_file(null, 1, null, "eso16_rhpessoal = {$this->matricula}"));

        if (!$rs) {
            throw new Exception("Erro ao verificar se a matrícula possui preenchimento.");
        }

        return pg_num_rows($rs) > 0;
    }

    protected function posProcessamento()
    {
        if ($this->dados['tipo_cedencia'] != 'A') {
            unset($this->dados['infonus']);
            unset($this->dados['cnpjCednt']);
            unset($this->dados['tipo_cedencia']);
        }
    }

    private function buscarDependentes()
    {
        $dao = new cl_rhpessoal();
        $rs = db_query($dao->sqlDependentesServidorESocial($this->matricula));

        $dependentes = array();
        if ($rs && pg_num_rows($rs) > 0) {
            $dependentes = pg_fetch_all($rs);
        }

        return $dependentes;
    }

    /**
     * Processa a sugestão dos dependentes
     */
    private function processaDependentes()
    {
        $dependentes = $this->buscarDependentes();
        $numeroDependente = 1;
        foreach ($dependentes as $dependente) {
            foreach ($dependente as $indice => $valor) {
                if (in_array($indice, $this->deParaESocial)) {
                    $valor = $this->buscarValorCorrespondenteESocial($indice, $valor);
                }

                $identificador = $this->dependentes[$indice];
                if (is_array($identificador)) {
                    $identificadorPergunta = key($identificador);

                    if (!empty($identificador[$identificadorPergunta][$valor])) {
                        $identificadorResposta = $identificador[$identificadorPergunta][$valor];
                        $identificadorResposta = "dependente_{$numeroDependente}_$identificadorResposta";
                        $identificadorPergunta = "dependente_{$numeroDependente}_{$identificadorPergunta}";
                        $this->dados[$identificadorPergunta]['option'] = $identificadorResposta;
                    }
                } else {
                    $identificador = "dependente_{$numeroDependente}_{$identificador}";
                    $this->dados[$identificador] = $valor;
                }
            }

            $numeroDependente ++ ;
        }
    }
}
