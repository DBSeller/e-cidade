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

/**
 * Model responsável pelos tipos de prestação de contas.
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.4 $
 */
class TipoPrestacaoConta
{

    /**
     * Código sequencial
     * @var integer
     */
    private $iCodigo;

    /**
     * Descrição da prestação de contas
     * @var string
     */
    private $sDescricao;

    /**
     * Tipo de Obrigação da Prestação de Contas
     * @var integer
     */
    private $iCodigoObrigacao;

    /**
     * TipoPrestacaoConta constructor.
     * @param null $iCodigo
     * @throws BusinessException
     */
    public function __construct($iCodigo = null)
    {

        $this->iCodigo = $iCodigo;
        if (!empty($this->iCodigo)) {

            $oDaoEmpPrestaTip = db_utils::getDao("empprestatip");
            $sSqlBuscaTipo = $oDaoEmpPrestaTip->sql_query_file($this->iCodigo);
            $rsBuscaTipo = $oDaoEmpPrestaTip->sql_record($sSqlBuscaTipo);
            if ($oDaoEmpPrestaTip->erro_status == "0") {

                $sCaminhoMensagem = "financeiro.empenho.TipoPrestacaoConta.tipo_prestacao_nao_encontrado";
                throw new BusinessException(_M($sCaminhoMensagem));
            }

            $oStdDado = db_utils::fieldsMemory($rsBuscaTipo, 0);
            $this->sDescricao = $oStdDado->e44_descr;
            $this->iCodigoObrigacao = $oStdDado->e44_obriga;
        }
    }

    /**
     * Retorna o Código Sequencial do tipo da prestação de contas
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * Retorna a descrição da prestação de contas
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    /**
     * Retorna o tipo da obrigacao
     * @return string
     */
    public function getTipoObrigacao()
    {
        return $this->iCodigoObrigacao;
    }
}
