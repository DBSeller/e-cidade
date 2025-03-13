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

/**
 * Classe para controle da Finalidade de Pagamento do FUNDEB
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.6 $
 */
class FinalidadePagamentoFundeb
{

    /**
     * Quando empenho ou slip nao for de cgm publico  essa deve ser descricao finalidade
     *
     * @var FINALIDADE_DESCRICAO_CNAB_NAO_PUBLICO string
     */
    const FINALIDADE_DESCRICAO_CNAB_NAO_PUBLICO = 'PAGAMENTO A FORNECEDOR';

    /**
     * @var array
     */
    public static $FINALIDADE_DESCRICAO_CNAB = array(
        '01' => 'Remuneração Magistério',
        '02' => 'Obrigações Patronais Magistério',
        '03' => 'Remuneração Pessoal Técnico Administrativo',
        '04' => 'Obrigações Patronais Pessoais',
        '91' => 'Ressarcimento por escola municipalizada',
        '92' => 'Transferências para transporte escolar municipal',
        '00' => 'Não se aplica',
        '50' => 'Aplicação em Poupança',
        '90' => 'Migração Saldo Portaria 3992-FNS',
        '93' => 'Transf Município sem Gestão Plena',
        '94' => 'Folha Pagam SUS',
        '95' => 'Pagamento Prestador Municipal',
        '96' => 'Pagamento Prestador Estadual',
        '98' => 'Transferência Tributos Retidos'
    );

    /**
     * Código sequencial da finalidade
     * @var integer
     */
    private $iCodigoSequencial;

    /**
     * Código da finalidade de acordo com a portaria STN/FNDE
     * @var string
     */
    private $sCodigo;

    /**
     * Descrição da finalidade
     * @var string
     */
    private $sDescricao;

    /**
     * Constrói um objeto com as propriedades já setadas
     * @param string $iCodigoSequencial
     * @throws BusinessException
     */
    public function __construct($iCodigoSequencial = null)
    {

        $this->iCodigoSequencial = $iCodigoSequencial;
        if (!empty($this->iCodigoSequencial) || $this->iCodigoSequencial === "0") {

            $oDaoFinalidadePagamento = new cl_finalidadepagamentofundeb();
            $sSqlBuscaFinalidade = $oDaoFinalidadePagamento->sql_query_file($iCodigoSequencial);
            $rsBuscaFinalidade = $oDaoFinalidadePagamento->sql_record($sSqlBuscaFinalidade);
            if ($oDaoFinalidadePagamento->erro_status == "0") {
                throw new BusinessException("Não foi localizado a finalidade com sequencial {$iCodigoSequencial}.");
            }

            $sStdFinalidade = db_utils::fieldsMemory($rsBuscaFinalidade, 0);
            $this->sCodigo = $sStdFinalidade->e151_codigo;
            $this->sDescricao = $sStdFinalidade->e151_descricao;
            unset($sStdFinalidade);
        }
    }


    /**
     * Retorna uma instancia de FinalidadePagamentoFundeb de acordo com o código informado via parâmetro
     *
     * @param string $sCodigoFinalidade
     *
     * @return FinalidadePagamentoFundeb
     * @throws BusinessException
     */
    public static function getInstanciaPorCodigo($sCodigoFinalidade)
    {

        $oDaoFinalidadePagamento = new cl_finalidadepagamentofundeb();
        $sWhere = "e151_codigo = '{$sCodigoFinalidade}'";
        $sSqlBuscaFinalidade = $oDaoFinalidadePagamento->sql_query_file(null, "e151_sequencial", null, $sWhere);
        $rsBuscaFinalidade = $oDaoFinalidadePagamento->sql_record($sSqlBuscaFinalidade);
        if ($oDaoFinalidadePagamento->erro_status == "0") {
            throw new BusinessException("Não foi localizado a finalidade com o código informado {$sCodigoFinalidade}.");
        }

        return new FinalidadePagamentoFundeb(db_utils::fieldsMemory($rsBuscaFinalidade, 0)->e151_sequencial);
    }

    /**
     * Retorna o código sequencial da finalidade
     * @return integer
     */
    public function getCodigoSequencial()
    {
        return $this->iCodigoSequencial;
    }

    /**
     * Retorna o código de acordo com a portaria STN/FNDE
     * @return string
     */
    public function getCodigo()
    {
        return $this->sCodigo;
    }

    /**
     * Retorna descrição da finalidade
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }
}
