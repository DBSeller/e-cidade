<?php
namespace ECidade\Tributario\Divida\Termo\Services;

use DateTime;
use Exception;
use BusinessException;

use ECidade\Tributario\Arrecadacao\Model\Arrecad;
use \ECidade\Tributario\Arrecadacao\Repository\ArreforoRepository;
use \ECidade\Tributario\Arrecadacao\Repository\Arrecad as ArrecadRepository;
use \ECidade\Tributario\Arrecadacao\Repository\Arreold as ArreoldRepository;
use \ECidade\Tributario\Divida\Certidao\ACertidao;
use \ECidade\Tributario\Divida\Certidao\ACertidaoTermo;
use \ECidade\Tributario\Divida\Certidao\ACertidaoDivida;
use \ECidade\Tributario\Divida\Certidao\Repository\Certidao as CertidaoRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\CertidaoDivida as CertidaoDividaRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\CertidaoTermo as CertidaoTermoRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\ACertidao as ACertidaoRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\ACertidaoTermo as ACertidaoTermoRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\ACertidaoDivida as ACertidaoDividaRepository;
use \ECidade\Tributario\Divida\Certidao\Repository\ListaCDA as ListaCDARepository;
use \ECidade\Tributario\Divida\Termo\TermoDivida;
use \ECidade\Tributario\Divida\Termo\TermoReparcelamento;
use \ECidade\Tributario\Divida\Termo\Repository\Termo as TermoRepository;
use \ECidade\Tributario\Divida\Termo\Repository\TermoInicial as TermoInicialRepository;
use \ECidade\Tributario\Divida\Termo\Repository\TermoDivida as TermoDividaRepository;
use \ECidade\Tributario\Divida\Termo\Repository\TermoReparcelamento as TermoReparcelamentoRepository;
use \ECidade\Tributario\Juridico\Inicial\InicialMov;
use \ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;
use \ECidade\Tributario\Juridico\Inicial\Repository\InicialMov as InicialMovRepository;
use \ECidade\Tributario\Juridico\Inicial\Repository\InicialCert as InicialCertRepository;
use \ECidade\Tributario\Juridico\Inicial\Repository\InicialNumpreRepository;
use \ECidade\Tributario\Divida\Repository\Divida as DividaRepository;

/**
 * Service responsável por passar um parcelamento do foro para parcelamento de divida
 *
 * @author Matheus Lino <matheus.lino@dbseller.com.br>
 */
class ParcelamentoForoParaDivida
{
    /*
     * Tipos de divida para qual será configurado o parcelamento.
     * Por enquanto atende os clientes que utilizam a rotina, talvez seja necesssário
     * futuramente criar rotina de configuração ou buscar o tipo de outra forma.
     */
    const TIPO_PARCELAMENTO_DIVIDA = 6;
    const TIPO_DIVIDA_ATIVA = 5;

    /**
     * @var ArreforoRepository
     */
    private $arreforoRepository;

    /**
     * @var ArrecadRepository
     */
    private $arrecadRepository;

    /**
     * @var ArreoldRepository
     */
    private $arreoldRepository;

    /**
     * @var CertidaoRepository;
     */
    private $certidaoRepository;

    /**
     * @var CertidaoDividaRepository
     */
    private $certidaoDividaRepository;

    /**
     * @var CertidaoTermoRepository
     */
    private $certidaoTermoRepository;

    /**
     * @var ACertidaoRepository
     */
    private $aCertidaoRepository;

    /**
     * @var ACertidaoTermoRepository
     */
    private $aCertidaoTermoRepository;

    /**
     * @var ACertidaoDividaRepository
     */
    private $aCertidaoDividaRepository;

    /**
     * @var ListaCDARepository
     */
    private $listaCDARepository;

    /**
     * @var TermoRepository
     */
    private $termoRepository;

    /**
     * @var TermoInicialRepository
     */
    private $termoInicialRepository;

    /**
     * @var TermoDividaRepository
     */
    private $termoDividaRepository;

    /**
     * @var TermoReparcelamentoRepository
     */
    private $termoReparcelamentoRepository;

    /**
     * @var InicialRepository
     */
    private $inicialRepository;

    /**
     * @var InicialMovRepository
     */
    private $inicialMovRepository;

    /**
     * @var InicialCertRepository
     */
    private $inicialCertRepository;

    /**
     * @var InicialNumpreRepository
     */
    private $inicialNumpreRepository;

    /**
     * @var Integer
     */
    private $parcelamento;

    /**
     * @var Termo
     */
    private $termo;

    /**
     * @var TermoInicial[]
     */
    private $termoini;

    /**
     * @var Inicial[]
     */
    private $inicial;

    /**
     * @var InicialCert[]
     */
    private $inicialCert;

    /**
     * @var InicialNumpre[]
     */
    private $inicialNumpre;

    /**
     * @var Certidao[]
     */
    private $certidao;

    /**
     * @var CertidaoDivida[]
     */
    private $certidaoDivida;

    /**
     * @var CertidaoTermo[]
     */
    private $certidaoTermo;

    /**
     * @var boolean
     */
    private $filtrarDividas;

    /**
     * @var String
     */
    private $receitas;

    /**
     * @var String
     */
    private $dividas;

    /**
     * @var String
     */
    private $exercicioInicio;

    /**
     * @var String
     */
    private $exercicioFim;

    /**
     * @var String
     */
    private $where;

    /**
     * @var Number
     */
    private $tipoDebito;

    /**
     * @return ParcelamentoForoParaDivida
     */
    public function __construct(
        ArreforoRepository $arreforoRepository,
        ArrecadRepository $arrecadRepository,
        ArreoldRepository $arreoldRepository,
        CertidaoRepository $certidaoRepository,
        CertidaoDividaRepository $certidaoDividaRepository,
        CertidaoTermoRepository $certidaoTermoRepository,
        ACertidaoRepository $aCertidaoRepository,
        ACertidaoTermoRepository $aCertidaoTermoRepository,
        ACertidaoDividaRepository $aCertidaoDividaRepository,
        ListaCDARepository $listaCDARepository,
        TermoRepository $termoRepository,
        TermoInicialRepository $termoInicialRepository,
        TermoDividaRepository $termoDividaRepository,
        TermoReparcelamentoRepository $termoReparcelamentoRepository,
        InicialRepository $inicialRepository,
        InicialMovRepository $inicialMovRepository,
        InicialCertRepository $inicialCertRepository,
        InicialNumpreRepository $inicialNumpreRepository,
        DividaRepository $dividaRepository
    ) {
        $this->arreforoRepository = $arreforoRepository;
        $this->arrecadRepository = $arrecadRepository;
        $this->arreoldRepository = $arreoldRepository;
        $this->certidaoRepository = $certidaoRepository;
        $this->certidaoDividaRepository = $certidaoDividaRepository;
        $this->certidaoTermoRepository = $certidaoTermoRepository;
        $this->aCertidaoRepository = $aCertidaoRepository;
        $this->aCertidaoTermoRepository = $aCertidaoTermoRepository;
        $this->aCertidaoDividaRepository = $aCertidaoDividaRepository;
        $this->listaCDARepository = $listaCDARepository;
        $this->termoRepository = $termoRepository;
        $this->termoInicialRepository = $termoInicialRepository;
        $this->termoDividaRepository = $termoDividaRepository;
        $this->termoReparcelamentoRepository = $termoReparcelamentoRepository;
        $this->inicialRepository = $inicialRepository;
        $this->inicialMovRepository = $inicialMovRepository;
        $this->inicialCertRepository = $inicialCertRepository;
        $this->inicialNumpreRepository = $inicialNumpreRepository;
        $this->dividaRepository = $dividaRepository;
    }

    public function getParcelamento()
    {
        return $this->parcelamento;
    }

    public function setParcelamento($parcelamento)
    {
        $this->parcelamento = $parcelamento;
        return $this;
    }

    public function setFiltrarDividas($filtrarDividas)
    {
        $this->filtrarDividas = $filtrarDividas;
        return $this;
    }

    public function setReceitas($receitas)
    {
        $this->receitas = $receitas;
        return $this;
    }

    public function setDividas($dividas)
    {
        $this->dividas = $dividas;
        return $this;
    }

    public function setExercicioInicio($exercicioInicio)
    {
        $this->exercicioInicio = $exercicioInicio;
        return $this;
    }

    public function setExercicioFim($exercicioFim)
    {
        $this->exercicioFim = $exercicioFim;
        return $this;
    }

    public function setTipoDebito($tipoDebito)
    {
        $this->tipoDebito = $tipoDebito;
        return $this;
    }

    public function execute()
    {
        if (!\db_utils::inTransaction()) {
            throw new Exception('Necessário estar em transação para processar!');
        }

        $this->carregarDados();
        $this->desfazerParcelamento();
        $this->atualizarTipos();
        $this->vincularOrigens();
        $this->filtrarDividas();
    }

    private function carregarDados()
    {
        if (empty($this->parcelamento)) {
            throw new BusinessException('Nenhum parcelamento informado!');
        }

        $this->termo = $this->termoRepository->find($this->parcelamento);

        if (empty($this->termo)) {
            throw new Exception('Parcelamento não encontrado!');
        }

        //Verifica se o parcelamento se trata de um parcelamento do foro
        if ($this->arrecadRepository->getCadTipoNumpre($this->termo->getNumpre()) != 13) {
            throw new Exception('Parcelamento não é um parcelamento do foro!');
        }

        // 1 - Pega os dados da "termoini" e cria bkp
        $this->termoini = $this->termoInicialRepository->getByTermo($this->parcelamento);

        // 3 - Pega os dados da "inicial" e cria bkp
        $this->inicial = $this->getIniciais();

        // 4 - Pega os dados da inicialcert
        $this->inicialCert = $this->inicialCertRepository->findAll(
            "v51_inicial in ({$this->mapAndImplodeModel($this->inicial, 'getCodigo')})"
        );

        // 5 - Pega os dados da "certid" e cria bkp
        $this->certidao = $this->certidaoRepository->findAll(
            "certid.v13_certid in ({$this->mapAndImplodeModel($this->inicialCert, 'getCertidao')})"
        );

        // 6 - Pega os dados da "certdiv" e cria bkp
        $this->certidaoDivida = $this->certidaoDividaRepository->findAll(
            "certdiv.v14_certid in ({$this->mapAndImplodeModel($this->inicialCert, 'getCertidao')})"
        );

        // 7 - Pega os dados da "certter" e cria bkp
        $this->certidaoTermo = $this->certidaoTermoRepository->findAll(
            "certter.v14_certid in ({$this->mapAndImplodeModel($this->inicialCert, 'getCertidao')})"
        );
    }

    private function getIniciais()
    {
        $strIniciais = $this->mapAndImplodeModel($this->termoini, 'getInicial');

        $this->inicialRepository->scopeInicial("({$strIniciais})", "in");

        return $this->inicialRepository->get();
    }

    private function mapAndImplodeModel($arrModel, $keyFuncName)
    {
        $array = array_map(function ($model) use ($keyFuncName) {
            return $model->$keyFuncName();
        }, $arrModel);

        return implode(',', $array);
    }

    private function desfazerParcelamento()
    {
        $this->cancelarIniciais();
        $this->gerarHistoricoCertidoes();
        $this->excluirVinculosOrigensCertidaoInicial();
    }

    private function cancelarIniciais()
    {
        foreach ($this->inicial as $inicial) {
            // 2 - Insere na "inicialmov" movimentação da "inicial" para 9 (cancelamento)
            $inicialMov = new InicialMov();
            $data = new \DBDate(date("d/m/Y"));

            $inicialMov->setInicial($inicial->getCodigo())
                       ->setSituacao(InicialMov::SITUACAO_CANCELADA)
                       ->setObservacao("Parcelamento de foro para divida")
                       ->setData($data)
                       ->setLogin(db_getsession("DB_id_usuario"));

            $this->inicialMovRepository->persist($inicialMov);

            // 4 - Atualiza situação da inicial para 9 (cancelada)
            $inicial->setSituacao(InicialMov::SITUACAO_CANCELADA);
            $this->inicialRepository->persist($inicial);
        }
    }

    private function gerarHistoricoCertidoes()
    {
        $data = new \DBDate(date("d/m/Y"));
        $hora = date('h:i');
        $arrAcertids = array();

        // 10 - Insere na "acertid" dados de movimentação das certidoes  que tem registro na "certter"
        foreach ($this->certidao as $certidao) {
            $acertid = new ACertidao();
            $acertid->setCertidao($certidao->getCodigo())
                    ->setData($data)
                    ->setHora($hora)
                    ->setUsuario(db_getsession("DB_id_usuario"))
                    ->setParcial(false)
                    ->setInstituicao(db_getsession("DB_instit"))
                    ->setObservacao('Processo judicial extinto - Parcelamento do foro para divida');

            $arrAcertids[$acertid->getCertidao()] = $acertid;

            $this->aCertidaoRepository->persist($acertid);
        }

        // 11 - Insere na "acertter" os registros da "certter"
        foreach ($this->certidaoTermo as $certidaoTermo) {
            $aCertidaoTermo = new ACertidaoTermo();

            $aCertidaoTermo->setCodigoCertidao($certidaoTermo->getCodigoCertidao())
                           ->setParcelamento($certidaoTermo->getParcelamento())
                           ->setValorHistorico($certidaoTermo->getValorHistorico())
                           ->setValorCorrigido($certidaoTermo->getValorCorrigido())
                           ->setValorJuro($certidaoTermo->getValorJuro())
                           ->setValorMulta($certidaoTermo->getValorMulta())
                           ->setCodigoAcertid(
                               $arrAcertids[$certidaoTermo->getCodigoCertidao()]->getCodigo()
                           );


            $this->aCertidaoTermoRepository->persist($aCertidaoTermo);
        }

        // 12 - Insere na "acertdiv" os registros da "certdiv"
        foreach ($this->certidaoDivida as $certidaoDivida) {
            $aCertidaoDivida = new ACertidaoDivida();

            $aCertidaoDivida->setCodigoCertidao($certidaoDivida->getCodigoCertidao())
                            ->setDivida($certidaoDivida->getDivida())
                            ->setValorHistorico($certidaoDivida->getValorHistorico())
                            ->setValorCorrigido($certidaoDivida->getValorCorrigido())
                            ->setValorJuro($certidaoDivida->getValorJuro())
                            ->setValorMulta($certidaoDivida->getValorMulta())
                            ->setCodigoAcertid(
                                $arrAcertids[$certidaoDivida->getCodigoCertidao()]->getCodigo()
                            );

            $this->aCertidaoDividaRepository->persist($aCertidaoDivida);
        }
    }

    private function excluirVinculosOrigensCertidaoInicial()
    {
        $certidoes = $this->mapAndImplodeModel($this->certidao, 'getCodigo');
        $iniciais = $this->mapAndImplodeModel($this->inicial, 'getCodigo');

        // 12 - Deleta da "certdiv"
        $this->certidaoDividaRepository->delete("v14_certid in ($certidoes)");
        // 13 - Deleta da "listacda"
        $this->listaCDARepository->delete("v81_certid in ($certidoes)");
        // 17 - Deleta da "certter"
        $this->certidaoTermoRepository->delete("v14_certid in ($certidoes)");
        // 22 - Deleta da "arreforo"
        $this->arreforoRepository->delete("k00_certidao in ($certidoes)");
        // 14 - Deleta da "certid"
        $this->certidaoRepository->delete("v13_certid in ($certidoes)");
        // 19 - Deleta da "termoini"
        $this->termoInicialRepository->delete("inicial in ($iniciais)");
        // 20 - Deleta da "inicialcert"
        $this->inicialCertRepository->delete("v51_inicial in ($iniciais)");
        // 21 - Deleta da "inicialnumpre"
        $this->inicialNumpreRepository->delete("v59_inicial in ($iniciais)");
    }

    private function vincularOrigens()
    {
        // 16 - Insere na "termodiv" os valores da "certdiv"
        foreach ($this->certidaoDivida as $certidaoDivida) {
            $termoDivida = new TermoDivida();
            $divida = $this->dividaRepository->getByCode($certidaoDivida->getDivida());

            $termoDivida->setParcelamento($this->getParcelamento())
                        ->setCodigoDivida($certidaoDivida->getDivida())
                        ->setNumpreAnterior($divida->getNumpre())
                        ->setPercentual(sizeof($this->certidaoDivida))
                        ->setValor($certidaoDivida->getValorHistorico())
                        ->setJuros($certidaoDivida->getValorJuro())
                        ->setMulta($certidaoDivida->getValorMulta())
                        ->setValorCorrigido($certidaoDivida->getValorCorrigido())
                        ->setDesconto('0')
                        ->setValorDescontoJuros('0')
                        ->setValorDescontoMulta('0')
                        ->setValorDescontoCor('0')
                        ->setTotal(
                            $certidaoDivida->getValorCorrigido() +
                            $certidaoDivida->getValorJuro() +
                            $certidaoDivida->getValorMulta()
                        );

            // 25 - Update "termodiv" aplica correção
            $this->termoDividaRepository->corrigeValor(
                $termoDivida,
                $divida->getNumpre(),
                $divida->getNumpar(),
                $this->termo->getDataLancamento()
            );

            $this->termoDividaRepository->persist($termoDivida);
        }

        // 26 - Insert "termoreparc" o que tiver na certter
        foreach ($this->certidaoTermo as $certidaoTermo) {
            $termoReparc = new TermoReparcelamento();

            $termoReparc->setParcelamento($this->getParcelamento())
                        ->setParcelamentoOrigem($certidaoTermo->getParcelamento());

            $this->termoReparcelamentoRepository->persist($termoReparc);
        }
    }

    private function atualizarTipos()
    {
        // 23 - Update "arrecad" tipo para parcelamento de divida
        $this->arrecadRepository->alterarTipo(
            self::TIPO_PARCELAMENTO_DIVIDA,
            "k00_numpre = {$this->termo->getNumpre()}"
        );

        // 24 - Update "arreold" set tipo = 5 (divida ativa)
        $this->arreoldRepository->alterarTipo(
            self::TIPO_DIVIDA_ATIVA,
            "k00_numpre = {$this->termo->getNumpre()}"
        );
    }

    //Remove as dividas do termo e atualiza o valor
    private function filtrarDividas()
    {
        if ($this->filtrarDividas == true) {
            $this->setWhere("");

            if (!empty($this->receitas)) {
                $this->addWhere("arreold.k00_receit in ($this->receitas)", "or");
            }

            if (!empty($this->dividas)) {
                $this->addWhere("termodiv.coddiv in ($this->dividas)", "or");
            }

            if (!empty($this->exercicioInicio) && !empty($this->exercicioFim)) {
                $this->addWhere("divida.v01_exerc between $this->exercicioInicio and $this->exercicioFim", "or");
            }

            $this->setWhere("parcel in ($this->parcelamento) and ($this->where)");

            $termodivs = $this->termoDividaRepository->findWithArreoldJoin(
                $this->where,
                "termodiv.*",
                "coddiv, parcel"
            );

            $valor = array_reduce($termodivs, function ($acum, $model) {
                $acum += $model->getValor();
                return $acum;
            });

            $dividas = $this->mapAndImplodeModel($termodivs, 'getCodigoDivida');

            $this->termoDividaRepository->delete(null, null, "parcel = $this->parcelamento and coddiv in ($dividas)");

            $this->termo->setValor($this->termo->getValor() - $valor);

            $this->termoRepository->persist($this->termo);

            $dividas = $this->dividaRepository->findAll("v01_coddiv in ($dividas)");

            foreach ($dividas as $divida) {
                //Buscar arreold
                $arreolds = $this->arreoldRepository->findAll(
                    "k00_numpre = " . $divida->getNumpre() . " and k00_numpar = " . $divida->getNumpar()
                );

                //Excluir arreold
                $this->arreoldRepository->delete(
                    "k00_numpre = " . $divida->getNumpre() . " and k00_numpar = " . $divida->getNumpar()
                );

                //Incluir arrecad
                foreach ($arreolds as $arreold) {
                    $arrecad = new Arrecad();
                    $arrecad->setNumpre($arreold->getNumpre());
                    $arrecad->setNumpar($arreold->getNumpar());
                    $arrecad->setNumCgm($arreold->getNumCgm());
                    $arrecad->setDataOperacao($arreold->getDataOperacao());
                    $arrecad->setReceita($arreold->getReceita());
                    $arrecad->setHistorico($arreold->getHistorico());
                    $arrecad->setValor($arreold->getValor());
                    $arrecad->setDataVencimento($arreold->getDataVencimento());
                    $arrecad->setNumTot($arreold->getNumTot());
                    $arrecad->setNumDig($arreold->getNumDig());
                    $arrecad->setTipo($this->tipoDebito);
                    $arrecad->setTipoJM($arreold->getTipoJM());

                    $this->arrecadRepository->persist($arrecad);
                }
            }
        }
    }

    private function addWhere($where, $glue = 'and')
    {
        if ($this->where == null || $this->where == '') {
            $this->where = $where;
        } else {
            $this->where .= " $glue " . $where;
        }
    }

    private function setWhere($where)
    {
        $this->where = $where;
    }
}
