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

namespace ECidade\Tributario\Divida\Termo;

use DateTime;
use ECidade\Tributario\Caixa\Model\Arrepaga;
use ECidade\Tributario\Divida\Model\Disbanco;
use ECidade\Tributario\Divida\Repository\DisbancoRepository;
use ECidade\Tributario\Divida\Termo\Repository\Termo as TermoRepository;
use Exception;

/**
 * Entidade para representação da tabela termo.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Termo
{
    /**
     * @var integer parcel
     */
    private $codigo;

    /**
     * @var DateTime dtlanc
     */
    private $dataLancamento;

    /**
     * @var float valor
     */
    private $valor;

    /**
     * @var integer numpre
     */
    private $numpre;

    /**
     * @var integer totpar
     */
    private $totalParcelas;

    /**
     * @var float vlrpar
     */
    private $valorParcela;

    /**
     * @var DateTime dtvenc
     */
    private $dataVencimento;

    /**
     * @var float vlrent
     */
    private $valorEntrada;

    /**
     * @var DateTime datpri
     */
    private $dataPrimeiraParcela;

    /**
     * @var float vlrmul
     */
    private $valorMulta;

    /**
     * @var float vlrjur
     */
    private $valorJuros;

    /**
     * @var float perjur
     */
    private $percentualJuros;

    /**
     * @var float permul
     */
    private $percentualMulta;

    /**
     * @var integer login
     */
    private $login;

    /**
     * @var string mtermo
     */
    private $textoTermo;

    /**
     * @var integer numcgm
     */
    private $cgm;

    /**
     * @var string hist
     */
    private $historico;

    /**
     * @var float ultpar
     */
    private $valorUltimaParcela;

    /**
     * @var integer desconto
     */
    private $desconto;

    /**
     * @var float descjur
     */
    private $descontoJuros;

    /**
     * @var float descmul
     */
    private $descontoMulta;

    /**
     * @var integer situacao
     */
    private $situacao;

    /**
     * @var integer instit
     */
    private $instituicao;

    /**
     * @var float vlrhist
     */
    private $valorHistorico;

    /**
     * @var float vlrcor
     */
    private $valorCorrigido;

    /**
     * @var float vlrdes
     */
    private $valorDesconto;

    /**
     * @var float desccor
     */
    private $descontoCorrigido;

    /**
     * @var TermoInicial[]
     */
    private $termoIniciais;

    /**
     * @var Arrepaga[]
     */
    private $pagamentos = array();

    /**
     * @var Disbanco
     */
    private $ultimoPagamentoEfetivo;

    /**
     * @param array $state
     * @return Termo
     */
    public static function fromState($state)
    {
        $termo = new Termo();

        if (array_key_exists('v07_parcel', $state)) {
            $termo->setCodigo((int)$state['v07_parcel']);
        }
        if (array_key_exists('v07_dtlanc', $state)) {
            $termo->setDataLancamento(new DateTime($state['v07_dtlanc']));
        }
        if (array_key_exists('v07_valor', $state)) {
            $termo->setValor((float)$state['v07_valor']);
        }
        if (array_key_exists('v07_numpre', $state)) {
            $termo->setNumpre((int)$state['v07_numpre']);
        }
        if (array_key_exists('v07_totpar', $state)) {
            $termo->setTotalParcelas((int)$state['v07_totpar']);
        }
        if (array_key_exists('v07_vlrpar', $state)) {
            $termo->setValorParcela((float)$state['v07_vlrpar']);
        }
        if (array_key_exists('v07_dtvenc', $state)) {
            $termo->setDataVencimento(new DateTime($state['v07_dtvenc']));
        }
        if (array_key_exists('v07_vlrent', $state)) {
            $termo->setValorEntrada((float)$state['v07_vlrent']);
        }
        if (array_key_exists('v07_datpri', $state)) {
            $termo->setDataPrimeiraParcela(new DateTime($state['v07_datpri']));
        }
        if (array_key_exists('v07_vlrmul', $state)) {
            $termo->setValorMulta((float)$state['v07_vlrmul']);
        }
        if (array_key_exists('v07_vlrjur', $state)) {
            $termo->setValorJuros((float)$state['v07_vlrjur']);
        }
        if (array_key_exists('v07_perjur', $state)) {
            $termo->setPercentualJuros((float)$state['v07_perjur']);
        }
        if (array_key_exists('v07_permul', $state)) {
            $termo->setPercentualMulta((float)$state['v07_permul']);
        }
        if (array_key_exists('v07_login', $state)) {
            $termo->setLogin((int)$state['v07_login']);
        }
        if (array_key_exists('v07_mtermo', $state)) {
            $termo->setTextoTermo($state['v07_mtermo']);
        }
        if (array_key_exists('v07_numcgm', $state)) {
            $termo->setCgm((int)$state['v07_numcgm']);
        }
        if (array_key_exists('v07_hist', $state)) {
            $termo->setHistorico($state['v07_hist']);
        }
        if (array_key_exists('v07_ultpar', $state)) {
            $termo->setValorUltimaParcela((float)$state['v07_ultpar']);
        }
        if (array_key_exists('v07_desconto', $state)) {
            $termo->setDesconto((float)$state['v07_desconto']);
        }
        if (array_key_exists('v07_descjur', $state)) {
            $termo->setDescontoJuros((float)$state['v07_descjur']);
        }
        if (array_key_exists('v07_descmul', $state)) {
            $termo->setDescontoMulta((float)$state['v07_descmul']);
        }
        if (array_key_exists('v07_situacao', $state)) {
            $termo->setSituacao((int)$state['v07_situacao']);
        }
        if (array_key_exists('v07_instit', $state)) {
            $termo->setInstituicao((int)$state['v07_instit']);
        }
        if (array_key_exists('v07_vlrhis', $state)) {
            $termo->setValorHistorico((float)$state['v07_vlrhis']);
        }
        if (array_key_exists('v07_vlrcor', $state)) {
            $termo->setValorCorrigido((float)$state['v07_vlrcor']);
        }
        if (array_key_exists('v07_vlrdes', $state)) {
            $termo->setValorDesconto((float)$state['v07_vlrdes']);
        }
        if (array_key_exists('v07_desccor', $state)) {
            $termo->setDescontoCorrigido((float)$state['v07_desccor']);
        }

        return $termo;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param  int $codigo
     * @return Termo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataLancamento()
    {
        return $this->dataLancamento;
    }

    /**
     * @param  DateTime $dataLancamento
     * @return Termo
     */
    public function setDataLancamento($dataLancamento)
    {
        $this->dataLancamento = $dataLancamento;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param  float $valor
     * @return Termo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param  int $numpre
     * @return Termo
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalParcelas()
    {
        return $this->totalParcelas;
    }

    /**
     * @param  int $totalParcelas
     * @return Termo
     */
    public function setTotalParcelas($totalParcelas)
    {
        $this->totalParcelas = $totalParcelas;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorParcela()
    {
        return $this->valorParcela;
    }

    /**
     * @param  float $valorParcela
     * @return Termo
     */
    public function setValorParcela($valorParcela)
    {
        $this->valorParcela = $valorParcela;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param  DateTime $dataVencimento
     * @return Termo
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorEntrada()
    {
        return $this->valorEntrada;
    }

    /**
     * @param  float $valorEntrada
     * @return Termo
     */
    public function setValorEntrada($valorEntrada)
    {
        $this->valorEntrada = $valorEntrada;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataPrimeiraParcela()
    {
        return $this->dataPrimeiraParcela;
    }

    /**
     * @param  DateTime $dataPrimeiraParcela
     * @return Termo
     */
    public function setDataPrimeiraParcela($dataPrimeiraParcela)
    {
        $this->dataPrimeiraParcela = $dataPrimeiraParcela;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorMulta()
    {
        return $this->valorMulta;
    }

    /**
     * @param  float $valorMulta
     * @return Termo
     */
    public function setValorMulta($valorMulta)
    {
        $this->valorMulta = $valorMulta;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorJuros()
    {
        return $this->valorJuros;
    }

    /**
     * @param  float $valorJuros
     * @return Termo
     */
    public function setValorJuros($valorJuros)
    {
        $this->valorJuros = $valorJuros;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentualJuros()
    {
        return $this->percentualJuros;
    }

    /**
     * @param  float $percentualJuros
     * @return Termo
     */
    public function setPercentualJuros($percentualJuros)
    {
        $this->percentualJuros = $percentualJuros;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentualMulta()
    {
        return $this->percentualMulta;
    }

    /**
     * @param  float $percentualMulta
     * @return Termo
     */
    public function setPercentualMulta($percentualMulta)
    {
        $this->percentualMulta = $percentualMulta;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param  int $login
     * @return Termo
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextoTermo()
    {
        return $this->textoTermo;
    }

    /**
     * @param  string $textoTermo
     * @return Termo
     */
    public function setTextoTermo($textoTermo)
    {
        $this->textoTermo = $textoTermo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param  int $cgm
     * @return Termo
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
        return $this;
    }

    /**
     * @return string
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param  string $historico
     * @return Termo
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorUltimaParcela()
    {
        return $this->valorUltimaParcela;
    }

    /**
     * @param  float $valorUltimaParcela
     * @return Termo
     */
    public function setValorUltimaParcela($valorUltimaParcela)
    {
        $this->valorUltimaParcela = $valorUltimaParcela;
        return $this;
    }

    /**
     * @return int
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @param  int $desconto
     * @return Termo
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescontoJuros()
    {
        return $this->descontoJuros;
    }

    /**
     * @param  float $descontoJuros
     * @return Termo
     */
    public function setDescontoJuros($descontoJuros)
    {
        $this->descontoJuros = $descontoJuros;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescontoMulta()
    {
        return $this->descontoMulta;
    }

    /**
     * @param  float $descontoMulta
     * @return Termo
     */
    public function setDescontoMulta($descontoMulta)
    {
        $this->descontoMulta = $descontoMulta;
        return $this;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param  int $situacao
     * @return Termo
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param  int $instituicao
     * @return Termo
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorHistorico()
    {
        return $this->valorHistorico;
    }

    /**
     * @param  float $valorHistorico
     * @return Termo
     */
    public function setValorHistorico($valorHistorico)
    {
        $this->valorHistorico = $valorHistorico;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorCorrigido()
    {
        return $this->valorCorrigido;
    }

    /**
     * @param  float $valorCorrigido
     * @return Termo
     */
    public function setValorCorrigido($valorCorrigido)
    {
        $this->valorCorrigido = $valorCorrigido;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorDesconto()
    {
        return $this->valorDesconto;
    }

    /**
     * @param  float $valorDesconto
     * @return Termo
     */
    public function setValorDesconto($valorDesconto)
    {
        $this->valorDesconto = $valorDesconto;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescontoCorrigido()
    {
        return $this->descontoCorrigido;
    }

    /**
     * @param  float $descontoCorrigido
     * @return Termo
     */
    public function setDescontoCorrigido($descontoCorrigido)
    {
        $this->descontoCorrigido = $descontoCorrigido;
        return $this;
    }

    /**
     * @return TermoInicial[]
     */
    public function getTermoIniciais()
    {
        return $this->termoIniciais;
    }

    /**
     * @param  TermoInicial[] $termoIniciais
     * @return Termo
     */
    public function setTermoIniciais($termoIniciais)
    {
        $this->termoIniciais = $termoIniciais;
        return $this;
    }

    /**
     * @param  TermoInicial $termoInicial
     * @return Termo
     */
    public function addTermoInicial($termoInicial)
    {
        $this->termoIniciais[] = $termoInicial;
        return $this;
    }

    /**
     * @return Arrepaga[]
     */
    public function getPagamentos()
    {
        return $this->pagamentos;
    }

    /**
     * @param Arrepaga[] $pagamentos
     */
    public function setPagamentos($pagamentos)
    {
        $this->pagamentos = $pagamentos;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function withPagamentos()
    {
        if (empty($this->pagamentos)) {
            $pagamentos = TermoRepository::getInstance()->getPagamentosPorTermo($this);
            $this->setPagamentos($pagamentos);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function withUltimoPagamentoEfetivo()
    {
        if (empty($this->ultimoPagamentoEfetivo)) {
            $this->ultimoPagamentoEfetivo = DisbancoRepository::getInstance()
                ->disbancoMaisRecentePorParcelamento($this);
        }

        return $this;
    }

    /**
     * @return Arrepaga|null
     */
    public function getUltimoArrepaga()
    {
        if (!empty($this->pagamentos)) {
            return $this->pagamentos[count($this->pagamentos) - 1];
        }

        return null;
    }

    /**
     * funcao para retornar o termo de origem do parcelamento
     * @param integer $iTermo
     * @return integer iTermo de origem
     */
    public static function getOrigemTermo($iTermo)
    {

        $dao  = new \cl_termoreparc();
        $sSql = $dao->sql_query_file(null, "v08_parcelorigem", null, "v08_parcel =  $iTermo ");

        $rs   = $dao->sql_record($sSql);
        if ($dao->numrows > 0) {
            $iTermo = \db_utils::fieldsMemory($rs, 0)->v08_parcelorigem;
            return static::getOrigemTermo($iTermo);
        }
        return $iTermo;
    }

    /**
     * funcao recursiva para retornar um array de termos de origem do parcelamento passado por parâmetro
     * ou caso não existe passa o próprio termo
     * @param integer $iTermo
     * @return array aTermo de origem
     */
    public static function getOrigemMultiplaTermo($iTermo)
    {

        $dao  = new \cl_termoreparc();
        $sSql = $dao->sql_query_file(null, "v08_parcelorigem", null, "v08_parcel =  $iTermo ");

        $aTermo = array();

        $rs   = $dao->sql_record($sSql);
        if ($dao->numrows > 0) {
            for ($i = 0; $i < $dao->numrows; $i++) {
                $iTermo = \db_utils::fieldsMemory($rs, $i)->v08_parcelorigem;

                $aTermo = array_merge($aTermo, static::getOrigemMultiplaTermo($iTermo));
            }
        } else {
            $aTermo[] = $iTermo;
        }

        return $aTermo;
    }

    /**
     * @return DateTime|null
     */
    public function getDataUltimoPagamento()
    {
        if (empty($this->pagamentos)) {
            return null;
        }

        $dataUltimoPagamentoEfetivo = null;

        foreach ($this->pagamentos as $pagamento) {
            $dataPagamentoArrepaga = $pagamento->getDataPagamentoEfetivo();

            if ($dataUltimoPagamentoEfetivo == null) {
                $dataUltimoPagamentoEfetivo = $dataPagamentoArrepaga;
                continue;
            }

            if ($dataPagamentoArrepaga->getTimestamp() > $dataUltimoPagamentoEfetivo->getTimestamp()) {
                $dataUltimoPagamentoEfetivo = $dataPagamentoArrepaga;
            }
        }

        return $dataUltimoPagamentoEfetivo;
    }
}
