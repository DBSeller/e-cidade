<?php

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model;

use Cassandra\Date;
use DateTime;
use Instituicao;
use DBDepartamento as Departamento;
use Fornecedor;
use ECidade\Patrimonial\Compras\HistoricoEmpenho\Repository\HistoricoRepository;
use ECidade\Patrimonial\Compras\HistoricoEmpenho\Model\Historico;
use ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Model\TipoPrestacao;
use ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Repository\TipoPrestacaoRepository;
use ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Repository\ProcessoAdministrativoRepository;
use ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Model\ProcessoAdministrativo;
use ECidade\Patrimonial\Compras\ItemEmpenho\Model\Item;
use ECidade\Patrimonial\Compras\ItemEmpenho\Repository\ItemRepository;

class Autorizacao
{

    /**
     * @var int
     */
    private $codigoAutorizacao;

    /**
     * @var int
     */
    private $fornecedor;

    /**
     * @var int
     */
    private $login;

    /**
     * @var int
     */
    private $codigoTipoCompra;

    /**
     * @var string
     */
    private $destino;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var int
     */
    private $anousu;

    /**
     * @var string
     */
    private $tipoLicitacao;

    /**
     * @var string
     */
    private $numeroLicitacao;

    /**
     * @var int
     */
    private $instituicaoLicitacao;
    /**
     * @var string
     */
    private $praent;

    /**
     * @var string
     */
    private $entpar;

    /**
     * @var string
     */
    private $conpag;

    /**
     * @var string
     */
    private $codout;

    /**
     * @var string
     */
    private $contat;

    /**
     * @var string
     */
    private $telef;

    /**
     * @var Historico
     */
    private $historico;

    /**
     * @var int
     */
    private $numsol;

    /**
     * @var date
     */
    private $anulad;

    /**
     * @var DateTime
     */
    private $emiss;

    /**
     * @var string
     */
    private $resumo;

    /**
     * @var int
     */
    private $codigoTipoEmpenho;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Departamento
     */
    private $departamento;

    /**
     * @var TipoPrestacao
     */
    private $tipoPrestacao;

    /**
     * @var ProcessoAdministrativo
     */
    private $processoAdministrativo;

    /**
     * @var string
     */
    private $codigoCaracteristicaPeculiar;

    /**
     * @var array
     */
    private $itens;

    /**
     * @var \dotacao
     */
    private $dotacao;

    /**
     * @param string $codigoAutorizacao
     */
    public function __construct($codigoAutorizacao = null)
    {
        if ($codigoAutorizacao) {
            $dao = db_utils::getDao("db_empautoriza_classe");
            $sql = $dao->sql_query_file($codigoAutorizacao);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * @return int
     */
    public function getCodigoAutorizacao()
    {
        return $this->codigoAutorizacao;
    }

    /**
     * @param int $codigoAutorizacao
     */
    public function setCodigoAutorizacao($codigoAutorizacao)
    {
        $this->codigoAutorizacao = $codigoAutorizacao;
    }

    /**
     * @return Fornecedor
     */
    public function getFornecedor()
    {
        return $this->fornecedor;
    }

    /**
     * @param Fornecedor $fornecedor
     */
    public function setFornecedor(Fornecedor $fornecedor)
    {
        $this->fornecedor = $fornecedor;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return int
     */
    public function getCodigoTipoCompra()
    {
        return $this->codigoTipoCompra;
    }

    /**
     * @param int $codigoTipoCompra
     */
    public function setCodigoTipoCompra($codigoTipoCompra)
    {
        $this->codigoTipoCompra = $codigoTipoCompra;
    }

    /**
     * @return string
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * @param string $destino
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getAnousu()
    {
        return $this->anousu;
    }

    /**
     * @param int $anousu
     */
    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    /**
     * @return string
     */
    public function getTipoLicitacao()
    {
        return $this->tipoLicitacao;
    }

    /**
     * @param string $tipoLicitacao
     */
    public function setTipoLicitacao($tipoLicitacao)
    {
        $this->tipoLicitacao = $tipoLicitacao;
    }

    /**
     * @return string
     */
    public function getNumeroLicitacao()
    {
        return $this->numeroLicitacao;
    }

    /**
     * @param string $numeroLicitacao
     */
    public function setNumeroLicitacao($numeroLicitacao)
    {
        $this->numeroLicitacao = $numeroLicitacao;
    }

    /**
     * @return string
     */
    public function getPraent()
    {
        return $this->praent;
    }

    /**
     * @param string $praent
     */
    public function setPraent($praent)
    {
        $this->praent = $praent;
    }

    /**
     * @return string
     */
    public function getEntpar()
    {
        return $this->entpar;
    }

    /**
     * @param string $entpar
     */
    public function setEntpar($entpar)
    {
        $this->entpar = $entpar;
    }

    /**
     * @return string
     */
    public function getConpag()
    {
        return $this->conpag;
    }

    /**
     * @param string $conpag
     */
    public function setConpag($conpag)
    {
        $this->conpag = $conpag;
    }

    /**
     * @return string
     */
    public function getCodout()
    {
        return $this->codout;
    }

    /**
     * @param string $codout
     */
    public function setCodout($codout)
    {
        $this->codout = $codout;
    }

    /**
     * @return string
     */
    public function getContat()
    {
        return $this->contat;
    }

    /**
     * @param string $contat
     */
    public function setContat($contat)
    {
        $this->contat = $contat;
    }

    /**
     * @return string
     */
    public function getTelef()
    {
        return $this->telef;
    }

    /**
     * @param string $telef
     */
    public function setTelef($telef)
    {
        $this->telef = $telef;
    }

    /**
     * @return int
     */
    public function getNumsol()
    {
        return $this->numsol;
    }

    /**
     * @param int $numsol
     */
    public function setNumsol($numsol)
    {
        $this->numsol = $numsol;
    }

    /**
     * @return date
     */
    public function getAnulad()
    {
        return $this->anulad;
    }

    /**
     * @param date $anulad
     */
    public function setAnulad($anulad)
    {
        $this->anulad = $anulad;
    }

    /**
     * @return DateTime
     */
    public function getEmiss()
    {
        return $this->emiss;
    }

    /**
     * @param DateTime $emiss
     */
    public function setEmiss($emiss)
    {
        $this->emiss = $emiss;
    }

    /**
     * @return string
     */
    public function getResumo()
    {
        return $this->resumo;
    }

    /**
     * @param string $resumo
     */
    public function setResumo($resumo)
    {
        $this->resumo = $resumo;
    }

    /**
     * @return int
     */
    public function getCodigoTipoEmpenho()
    {
        return $this->codigoTipoEmpenho;
    }

    /**
     * @param int $codigoTipoEmpenho
     */
    public function setCodigoTipoEmpenho($codigoTipoEmpenho)
    {
        $this->codigoTipoEmpenho = $codigoTipoEmpenho;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param Departamento $departamento
     */
    public function setDepartamento(Departamento $departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return Historico
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param Historico $historico
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
    }

    /**
     * @return ProcessoAdministrativo
     */
    public function getProcessoAdministrativo()
    {
        return $this->processoAdministrativo;
    }

    /**
     * @param ProcessoAdministrativo $processoAdministrativo
     */
    public function setProcessoAdministrativo($processoAdministrativo)
    {
        $this->processoAdministrativo = $processoAdministrativo;
    }

    /**
     * @return string
     */
    public function getCodigoCaracteristicaPeculiar()
    {
        return $this->codigoCaracteristicaPeculiar;
    }

    /**
     * @param string $codigoCaracteristicaPeculiar
     */
    public function setCodigoCaracteristicaPeculiar($codigoCaracteristicaPeculiar)
    {
        $this->codigoCaracteristicaPeculiar = $codigoCaracteristicaPeculiar;
    }

    /**
     * @return TipoPrestacao
     */
    public function getTipoPrestacao()
    {
        return $this->tipoPrestacao;
    }

    /**
     * @param TipoPrestacao $tipoPrestacao
     */
    public function setTipoPrestacao($tipoPrestacao)
    {
        $this->tipoPrestacao = $tipoPrestacao;
    }

    /**
     * @return self
     * @throws \Exception
     */
    public function withHistorico()
    {
        if (empty($this->historico)) {
            $repository = new HistoricoRepository(new \cl_emphist);
            $historico = $repository->getHistoricoPorAutorizacao(new \cl_empauthist(), $this);
            $this->setHistorico($historico);
        }
        return $this;
    }

    /**
     * @return self
     */
    public function withPrestacao()
    {
        if (empty($this->prestacao)) {
            $repository = new TipoPrestacaoRepository(new \cl_empprestatip);
            $tipoPrestacao = $repository->getTipoPrestacaoPorAutorizacao(new \cl_empautpresta(), $this);
            $this->setTipoPrestacao($tipoPrestacao);
        }
        return $this;
    }

    /**
     * @return self
     */
    public function withProcessoAdministrativo()
    {
        if (empty($this->processoAdministrativo)) {
            $repository = new ProcessoAdministrativoRepository(new \cl_empautorizaprocesso);
            $processoAdministrativo = $repository->getProcessoAdministrativoPorAutorizacao($this);
            $this->setProcessoAdministrativo($processoAdministrativo);
        }
        return $this;
    }

    /**
     * @return self
     * @throws \Exception
     */
    public function withDotacao()
    {
        if (empty($this->dotacao)) {
            $repository = \DotacaoRepository::getInstance();
            $dotacao = $repository->getDotacaoPorAutorizacao(new \cl_empautidot(), $this);
            $this->setDotacao($dotacao);
        }
        return $this;
    }

    /**
     * @return self
     */
    public function withItens()
    {
        if (empty($this->itens)) {
            $repository = new ItemRepository(new \cl_pcmater());
            $itens = $repository->getItensPorAutorizacao(new \cl_empautitem(), $this);
            $this->setItens($itens);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * @param array $itens
     */
    public function setItens($itens)
    {
        $this->itens = $itens;
    }

    /**
     * @return \Dotacao
     */
    public function getDotacao()
    {
        return $this->dotacao;
    }

    /**
     * @param \Dotacao $dotacao
     */
    public function setDotacao($dotacao)
    {
        $this->dotacao = $dotacao;
    }

    /**
     * @param $instituicaoLicitacao
     */
    public function setInstituicaoLicitacao($instituicaoLicitacao)
    {
        $this->instituicaoLicitacao = $instituicaoLicitacao;
    }

    /**
     * @return int
     */
    public function getInstituicaoLicitacao()
    {
        return $this->instituicaoLicitacao;
    }

    /**
     * @param array $state
     * @return Autorizacao
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $autorizacao = new self();

        if (array_key_exists('e54_autori', $state)) {
            $autorizacao->setCodigoAutorizacao((int)$state['e54_autori']);
        }

        if (array_key_exists('e54_numcgm', $state)) {
            $autorizacao->setFornecedor(new \fornecedor((int)$state['e54_numcgm']));
        }

        if (array_key_exists('e54_login', $state)) {
            $autorizacao->setLogin((int)$state['e54_login']);
        }

        if (array_key_exists('e54_codcom', $state)) {
            $autorizacao->setCodigoTipoCompra((int)$state['e54_codcom']);
        }

        if (array_key_exists('e54_destin', $state)) {
            $autorizacao->setDestino($state['e54_destin']);
        }

        if (array_key_exists('e54_valor', $state)) {
            $autorizacao->setValor((float)($state['e54_valor']));
        }

        if (array_key_exists('e54_tipol', $state)) {
            $autorizacao->setTipoLicitacao($state['e54_tipol']);
        }

        if (array_key_exists('e54_anousu', $state)) {
            $autorizacao->setAnousu((int)$state['e54_anousu']);
        }

        if (array_key_exists('e54_numerl', $state)) {
            $autorizacao->setNumeroLicitacao($state['e54_numerl']);
        }

        if (array_key_exists('e54_praent', $state)) {
            $autorizacao->setPraent($state['e54_praent']);
        }

        if (array_key_exists('e54_entpar', $state)) {
            $autorizacao->setEntpar($state['e54_entpar']);
        }

        if (array_key_exists('e54_entpar', $state)) {
            $autorizacao->setConpag($state['e54_entpar']);
        }

        if (array_key_exists('e54_codout', $state)) {
            $autorizacao->setCodout($state['e54_codout']);
        }

        if (array_key_exists('e54_contat', $state)) {
            $autorizacao->setContat($state['e54_contat']);
        }

        if (array_key_exists('e54_telef', $state)) {
            $autorizacao->setContat($state['e54_telef']);
        }

        if (array_key_exists('e54_numsol', $state)) {
            $autorizacao->setNumsol((int)$state['e54_numsol']);
        }

        if (array_key_exists('e54_anulad', $state)) {
            $autorizacao->setAnulad($state['e54_anulad']);
        }

        if (array_key_exists('e54_emiss', $state)) {
            $autorizacao->setEmiss(new DateTime($state['e54_emiss']));
        }

        if (array_key_exists('e54_resumo', $state)) {
            $autorizacao->setResumo($state['e54_resumo']);
        }

        if (array_key_exists('e54_codtipo', $state)) {
            $autorizacao->setCodigoTipoEmpenho((int)$state['e54_codtipo']);
        }

        if (array_key_exists('e54_instit', $state)) {
            $autorizacao->setInstituicao(\InstituicaoRepository::getInstituicaoByCodigo((int)$state['e54_instit']));
        }

        if (array_key_exists('e54_depto', $state)) {
            $autorizacao->setDepartamento(
                \DBDepartamentoRepository::getDBDepartamentoByCodigo((int)$state['e54_depto'])
            );
        }

        if (array_key_exists('e54_concarpeculiar', $state)) {
            $autorizacao->setCodigoCaracteristicaPeculiar($state['e54_concarpeculiar']);
        }

        if (array_key_exists('e54_institlic', $state)) {
            $autorizacao->setInstituicaoLicitacao($state['e54_institlic']);
        }

        return $autorizacao;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $processoAdministrativo = $this->getProcessoAdministrativo();
        $dotacao = $this->getDotacao();

        $itens= array();

        foreach ((array) $this->getItens() as $item) {
            $itens[] = $item->toArray();
        }

        $dadosLicitacao = explode('/', $this->getNumeroLicitacao());

        $numeroLicitacao = '';
        $anoLicitacao = '';

        if (!empty($dadosLicitacao) && count($dadosLicitacao) == 2) {
            $numeroLicitacao = $dadosLicitacao[0];
            $anoLicitacao = $dadosLicitacao[1];
        }
        $retorno = array(
            'e54_autori' => $this->getCodigoAutorizacao(),
            'e54_numcgm' => $this->getFornecedor()->toArray(),
            'e54_login' => $this->getLogin(),
            'e54_codcom' => $this->getCodigoTipoCompra(),
            'e54_destin' => $this->getDestino(),
            'e54_valor' => $this->getValor(),
            'e54_tipol' => $this->getTipoLicitacao(),
            'e54_numerl' => $this->getNumeroLicitacao(),
            'numeroLicitacao' => $numeroLicitacao,
            'anoLicitacao' => $anoLicitacao,
            'e54_praent' => $this->getPraent(),
            'e54_entpar' => $this->getEntpar(),
            'e54_codout' => $this->getCodout(),
            'e54_contat' => $this->getContat(),
            'e54_telef' => $this->getTelef(),
            'e54_anulad' => $this->getAnulad(),
            'e54_emiss' => !is_null($this->getEmiss()) ? $this->getEmiss()->format('Y-m-d') : null,
            'e54_resumo' => $this->getResumo(),
            'e54_codtipo' => $this->getCodigoTipoEmpenho(),
            'e54_instit' => $this->getInstituicao()->toArray(),
            'e54_depto' => $this->getDepartamento(),
            'e54_concarpeculiar' => $this->getCodigoCaracteristicaPeculiar(),
            'historico' => !is_null($this->getHistorico()) ? $this->getHistorico()->toArray() : null,
            'tipoPrestacao' => !is_null($this->getTipoPrestacao()) ? $this->getTipoPrestacao()->toArray() : null,
            'processoAdministrativo' => !is_null($processoAdministrativo) ? $processoAdministrativo->toArray() : null,
            'dotacao' => !is_null($dotacao) ? $dotacao->toArray() : null,
            'itens' => $itens,
            'instituicaoLicitacao' => $this->getInstituicaoLicitacao()
        );

        return $retorno;
    }
}
