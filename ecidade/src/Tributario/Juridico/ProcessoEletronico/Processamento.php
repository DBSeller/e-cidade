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

namespace ECidade\Tributario\Juridico\ProcessoEletronico;

use ECidade\Tributario\Divida\Certidao\Repository\Certidao as CertidaoRepository;
use ECidade\Tributario\Juridico\Inicial\Emissao;
use ECidade\Tributario\Juridico\Inicial\Inicial;
use ECidade\Tributario\Juridico\Inicial\MandatoCitacao;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;
use ECidade\Tributario\Juridico\Parametro;
use ECidade\Tributario\Juridico\ProcessoEletronico\Documento\DocumentoInicial;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\ProcessoEletronico as ProcessoEletronicoRepository;

class Processamento
{

    /**
     * Código da lista
     * @var int
     */
    private $lista;

    protected $iniciais = array();

    /**
     * Agrupar iniciais por cgm
     */
    const AGRUPAR_CGM = 1;

    /**
     * Agrupar iniciais por matricula
     */
    const AGRUPAR_MATRICULA = 2;

    /**
     * Agrupar iniciais por inscrição
     */
    const AGRUPAR_INSCRICAO = 3;


    /**
     * Agrupar por auto de infração
     */
    const AGRUPAR_AUTO_INFRACAO = 4;


    /**
     * @var \DateTime
     */
    private $dataProcessamento;

    /**
     * @var \UsuarioSistema
     */
    private $usuario;

    /**
     * @var integer
     */
    private $agrupar;

    /**
     * @var integer
     */
    private $matricula;

    /**
     * @var integer.
     */
    private $inscricao;

    /**
     * @var integer
     */
    private $cgm;


    /**
     * Auto de infração
     * @var int
     */
    private $auto;

    /**
     * @var \Instituicao
     */
    private $instituicao;

    /**
     * @var Parametro
     */
    private $parametrosJuridico;


    /**
     * Processamento constructor.
     * @param $lista
     */
    public function __construct(
        $lista,
        \DateTime $dataProcessamento,
        \UsuarioSistema $usuario,
        \Instituicao $instituicao
    ) {
        $this->lista = $lista;
        $this->dataProcessamento = $dataProcessamento;
        $this->usuario = $usuario;
        $this->instituicao = $instituicao;
    }


    /**
     *
     * Retorna todas as iniciais geradas na lista agrupadas por cgm
     *
     * @return \Inicial[]
     * @throws \DBException
     */
    protected function getIniciaisAgrupadas()
    {

        $rsIniciais = db_query($this->getQuery());
        if (!$rsIniciais) {
            throw new \DBException("Erro ao pesquisar iniciais da lista {$this->lista}");
        }
        $InicialRepository = InicialRepository::getInstance();
        $InicialRepository->setReturnFullItem(true);
        $listaIniciais = array();
        \db_utils::makeCollectionFromRecord($rsIniciais, function ($dados) use ($InicialRepository, &$listaIniciais) {

            $inicial = $InicialRepository->getByCode($dados->inicial);
            $listaIniciais[$dados->chave][] = $inicial;

        });

        return $listaIniciais;
    }

    /**
     * @return \stdClass
     */
    private function getDadosParaConsultaAgrupado()
    {
        $dados = new \stdClass();
        $dados->campo = '';
        $dados->filtro = '';
        $dados->join = '';
        $dados->where = '';

        switch ($this->agrupar) {

            case self::AGRUPAR_CGM:
                $dados->campo = ' arrenumcgm.k00_numcgm';
                $dados->join = ' inner join arrenumcgm on k61_numpre = arrenumcgm.k00_numpre';
                if (!empty($this->cgm)) {
                    $dados->where = 'arrenumcgm.k00_numcgm = ' . $this->cgm;
                }
                break;

            case self::AGRUPAR_MATRICULA:
                $dados->campo = 'arrematric.k00_matric';
                $dados->join = ' inner join arrematric on k61_numpre = arrematric.k00_numpre';
                if (!empty($this->matricula)) {
                    $dados->where = 'arrematric.k00_matric = ' . $this->matricula;
                }
                break;

            case self::AGRUPAR_INSCRICAO:
                $dados->campo = 'arreinscr.k00_inscr';
                $dados->join = ' inner join arreinscr on k61_numpre = arreinscr.k00_numpre';
                if (!empty($this->inscricao)) {
                    $dados->where = 'arreinscr.k00_inscr = ' . $this->inscricao;
                }
                break;

            case self::AGRUPAR_AUTO_INFRACAO:

                $dados->campo = 'k00_inscr';
                $dados->join = ' inner join arreinscr on k61_numpre = arreinscr.k00_numpre';
                $dados->join .= ' inner join arreauto  on k61_numpre = arreauto.k00_numpre';
                if (!empty($this->auto)) {
                    $dados->where = 'k00_auto = ' . $this->auto;
                }
                break;
        }
        return $dados;
    }

    /**
     * Retorna a query que deve ser executada
     * @return string
     */
    private function getQuery()
    {

        $dadosDinamicos = $this->getDadosParaConsultaAgrupado();
        $where = array(
            "k61_codigo = {$this->lista}",
            "v38_inicial is null"
        );
        if (!empty($dadosDinamicos->where)) {
            $where[] = $dadosDinamicos->where;
        }
        $clausulaWhere = implode(" and ", $where);
        $sqlIniciais = "select distinct                                        
                               v50_data as data,                                       
                               v51_inicial as inicial,                                                                             
                               {$dadosDinamicos->campo} as chave                                      
                          from listadeb
                               {$dadosDinamicos->join}
                               inner join juridico.inicialnumpre on v59_numpre = k61_numpre 
                               inner join inicialcert            on v51_inicial = v59_inicial
                               inner join inicial                on v51_inicial = v50_inicial  
                               inner join certid                 on v13_certid = v51_certidao 
                               inner join certdiv                on certdiv.v14_certid = v13_certid 
                               inner join divida                 on v01_coddiv  = certdiv.v14_coddiv
                                                                and v01_numpre  = k61_numpre                                           
                                                                and v01_numpar  = k61_numpar                                           
                               left  join integracaoprocessoeletronico on v38_inicial = v51_inicial
                        where {$clausulaWhere}                                                                                             
                        order by {$dadosDinamicos->campo},
                                 v50_data desc,
                                 inicial desc";


        return $sqlIniciais;
    }

    /**
     * @return string
     * @throws \BusinessException
     * @throws \DBException
     * @throws \FileException
     * @throws \ParameterException
     */
    public function processarIniciais()
    {
        $listaDeDocumentosParaAssinar = array();
        $iniciaisAgrupadas = $this->getIniciaisAgrupadas();
        if (count($iniciaisAgrupadas) == 0) {
            throw new \BusinessException("Sem iniciais para processar na lista {$this->lista}.");
        }
        /**
         * processa as iniciais que estão agrupadas conforme agrupamento da lista e opção escolhida pelo usuario.
         * deixa apenas a inicial mais nova, e agrupa debitos e certidoes na mesma.
         */
        foreach ($iniciaisAgrupadas as $chave => $iniciais) {

            $inicialPrincipalDoCgm = $iniciais[0];
            if (count($iniciais) > 1) {
                $inicialPrincipalDoCgm = $this->agruparIniciais($iniciais, $chave);
            }

            $cgmPrincipal = $this->ajustarEnvolvidos($inicialPrincipalDoCgm, $chave);

            $processoEletronico = new ProcessoEletronico();
            $processoEletronico->setInicial($inicialPrincipalDoCgm);
            $processoEletronico->setSituacao(Integracao::SITUACAO_PROCESSADO);
            $processoEletronico->setParte(\CgmRepository::getByCodigo($cgmPrincipal));
            $processoEletronico->setDataCalculo(new \DateTime());
            $documentos = $this->gerarDocumentoDaInicial($inicialPrincipalDoCgm);

            /**
             * persiste todos os documentos
             */
            foreach ($documentos as $documento) {

                $processoEletronico->adicionarDocumento($documento);
                $listaDeDocumentosParaAssinar[] = $documento;
            }
            ProcessoEletronicoRepository::persist($processoEletronico);
            ProcessoEletronicoRepository::persistirDocumentos($processoEletronico, $documentos);
        }
        return true;
    }

    /**
     * Retorna uma inicial Agrupada do Cgm
     * @param Inicial[] $iniciais
     * @return Inicial
     * @throws \BusinessException
     * @throws \DBException
     */
    protected function agruparIniciais(array $iniciais, $origem)
    {

        /**
         * Selecionamos a primeira inicial, como base e movemos as certidoes das outras iniciais para essa primeira inicial, e cancelamos  mesma;.
         */
        $inicialBase = array_slice($iniciais, 0, 1);
        unset($iniciais[0]);
        $inicialBase = $inicialBase[0];
        foreach ($iniciais as $inicial) {

            $certidoes = $inicial->getCertidoes();
            $this->moverCertidoesDaInicialParaInicial($inicial, $inicialBase);
            $this->moverDebitosDaInicialParaInicial($inicial, $inicialBase);
            foreach ($certidoes as $certidao) {
                $inicialBase->addCertidao($certidao);
            }
            $observacao = "Inicial anulada para processo eletrônico. Certidões dessa inicial agrupadas na inicial {$inicialBase->getCodigo()}";
            $this->anularInicial($inicial, $observacao);
        }
        /**
         * Ajustar os nomes das iniciais conforme regra do modulo Juridico ;         *
         */
        return $inicialBase;
    }

    /**
     * Gera os documentos fisicos da Inicial
     * @param Inicial $inicial
     * @return Documento[]
     * @throws \BusinessException
     * @throws \DBException
     */
    public function gerarDocumentoDaInicial(Inicial $inicial)
    {
        $arquivos = array();
        $pdfProcesso = new DocumentoInicial();
        $nomeArquivoMandadoCitacao = 'mandado_citacao_'.$inicial->getCodigo() . ".pdf";
        $caminhoArquivo = 'tmp/' .$nomeArquivoMandadoCitacao;

        $mandadoDeCitacao = new MandatoCitacao($inicial, $pdfProcesso, new \DateTime());
        $mandadoDeCitacao->setInstituicao($this->instituicao);
        $pdfProcesso->setTipoImpressao(DocumentoInicial::IMPRESSAO_CITACAO);
        $mandadoDeCitacao->emitir();
        $pdfProcesso->Output($caminhoArquivo, false, true);
        $documentoCitacao = new Documento();
        $documentoCitacao->setNome($nomeArquivoMandadoCitacao);
        $documentoCitacao->setData($inicial->getData());
        $documentoCitacao->setCaminho($caminhoArquivo);
        $documentoCitacao->setTipo(Documento::MANDADO_CITACAO);
        $documentoCitacao->setConteudo(base64_encode(file_get_contents($caminhoArquivo)));

        $arquivos[] = $documentoCitacao;


        $pdfProcesso = new DocumentoInicial();
        $emissaoCda = new Emissao($inicial->getCodigo());
        $pdfProcesso->setTipoImpressao(DocumentoInicial::IMPRESSAO_INICIAL);
        $emissaoCda->setPdfInstance($pdfProcesso);

        $nomeArquivoInicial = "inicial_" . $inicial->getCodigo() . ".pdf";
        $emissaoCda->setArquivo('tmp/' . $nomeArquivoInicial);
        $emissaoCda->setAtualizarValores(true);
        $emissaoCda->emitir(false);
        $pdfProcesso->setTipoImpressao(DocumentoInicial::IMPRESSAO_CDA);
        foreach ($inicial->getCertidoes() as $certidao) {

            $geradorCda = new \GeradorCDA($pdfProcesso);
            $geradorCda->gerar('2', $certidao->getCodigo(), $certidao->getCodigo(), false, 'v14_certid', 'f', '0');
        }

        $caminhoArquivo = 'tmp/' . $nomeArquivoInicial;
        $pdfProcesso->Output($caminhoArquivo, false, true);
        $pades = new \Pades();
        $pades->build(file_get_contents($caminhoArquivo));
        $documentoInicial = new Documento();
        $documentoInicial->setNome($nomeArquivoInicial);
        $documentoInicial->setData($inicial->getData());
        $documentoInicial->setTipo(Documento::INICIAL);
        $documentoInicial->setCaminho($caminhoArquivo);
        $documentoInicial->setConteudo(base64_encode($pades->render()));
        $arquivos[] = $documentoInicial;
        return $arquivos;
    }

    /**
     * @param Inicial $inicialOrigem
     * @param Inicial $inicialNova
     * @throws \BusinessException
     */
    private function moverCertidoesDaInicialParaInicial(Inicial $inicialOrigem, Inicial $inicialNova)
    {
        $certidaoRepostitory = CertidaoRepository::getInstance();
        foreach ($inicialOrigem->getCertidoes() as $certidao) {
            $certidaoRepostitory->moverCertidaoParaInicial($certidao, $inicialOrigem, $inicialNova);
        }
    }

    /**
     * @param Inicial $inicialOrigem
     * @param Inicial $inicialNova
     * @throws \BusinessException
     */
    private function moverDebitosDaInicialParaInicial(Inicial $inicialOrigem, Inicial $inicialNova)
    {
        $daoInicialNumpre = new \cl_inicialnumpre();
        $sqlDebitos = $daoInicialNumpre->sql_query_file(null, "*", null, "v59_inicial = {$inicialOrigem->getCodigo()}");
        $rsDebitos = db_query($sqlDebitos);
        if (!$rsDebitos) {
            throw new \BusinessException('Erro ao pesquisar debitos da Inicial ' . $inicialOrigem->getCodigo());
        }
        $debitos = \db_utils::getCollectionByRecord($rsDebitos);
        $daoInicialNumpre->excluir(null, "v59_inicial = {$inicialOrigem->getCodigo()}");
        if ($daoInicialNumpre->erro_status == 0) {
            throw new \BusinessException('Erro ao remover debitos da Inicial ' . $inicialOrigem->getCodigo());
        }
        foreach ($debitos as $debito) {
            $daoInicialNumpre->v59_inicial = $inicialNova->getCodigo();
            $daoInicialNumpre->v59_numpre = $debito->v59_numpre;
            $daoInicialNumpre->incluir();
            if ($daoInicialNumpre->erro_status == 0) {
                throw new \BusinessException('Erro ao vincular débitos da Inicial ' . $inicialNova->getCodigo());
            }
        }
    }

    /**
     * @todo mover para Repository da inicial? Não realiza a anulação dos débitos
     *
     * Realiza a anulação da CDA, sem anular os debitos
     * @param Inicial $inicial
     * @throws \DBException
     */
    private function anularInicial(Inicial $inicial, $observacao)
    {

        $daoInicial = new \cl_inicial();
        $daoInicialMovimentacao = new \cl_inicialmov();
        $daoInicialMovimentacao->v56_codsit = 9;
        $daoInicialMovimentacao->v56_data = $this->dataProcessamento->format('Y-m-d');
        $daoInicialMovimentacao->v56_id_login = $this->usuario->getCodigo();
        $daoInicialMovimentacao->v56_inicial = $inicial->getCodigo();
        $daoInicialMovimentacao->v56_obs = $observacao;
        $daoInicialMovimentacao->incluir(null);
        if ($daoInicialMovimentacao->erro_status == 0) {
            throw new \DBException("Houve um erro ao incluir movimentação para a inicial {$inicial->getCodigo()}");
        }
        $codigoMovimentacao = $daoInicialMovimentacao->v56_codmov;

        $daoInicial->v50_codmov = $codigoMovimentacao;
        $daoInicial->v50_inicial = $inicial->getCodigo();
        $daoInicial->v50_situacao = 2;
        $daoInicial->alterar($inicial->getCodigo());
        if ($daoInicialMovimentacao->erro_status == 0) {
            throw new \DBException("Houve um erro ao anulara inicial {$inicial->getCodigo()}");
        }
    }

    /**
     * @param int $agrupar
     */
    public function agruparPor($agrupar)
    {
        $this->agrupar = $agrupar;
    }

    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return int
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * @param int $inscricao
     */
    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param int $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return int
     */
    public function getAutoDeInfracao()
    {
        return $this->auto;
    }

    /**
     * @param int $auto
     */
    public function setAutoDeInfracao($auto)
    {
        $this->auto = $auto;
    }

    /**
     * @param Inicial $inicialBase
     * @param $origem
     * @return bool
     * @throws \DBException
     */
    private function ajustarEnvolvidos(Inicial $inicialBase, $origem)
    {

        /**
         * removemos todos os nomes envolvidos na inicial
         *
         */
        $daoInicialNomes = new \cl_inicialnomes();
        $daoInicialNomes->excluir($inicialBase->getCodigo());
        switch ($this->agrupar) {

            case self::AGRUPAR_MATRICULA;


                $tipo = 'M';
                $trazPrincipal = 'false';
                $regra         = $this->instituicao->getRegraDebitosIPTU();
                if (!empty($this->parametrosJuridico)) {
                    $trazPrincipal = $this->parametrosJuridico->isListarSomenteProprietarioPrincipal() ? "true" : "false";
                    $regra         = $this->parametrosJuridico->getRegraEnvolvidosInicialImovel();
                }
                break;

            case self::AGRUPAR_INSCRICAO;

                $tipo = 'I';
                $trazPrincipal = 'false';
                $regra         = $this->instituicao->getRegraDebitosISSQN();
                if (!empty($this->parametrosJuridico)) {
                    $regra = $this->parametrosJuridico->getRegraEnvolvidosInicialEmpresa();
                }
                break;

            default :
                $tipo = 'C';
                $trazPrincipal = 'false';
                $regra         = 0;
                break;
        }

        $buscaEnvolvidos = "select * from  fc_busca_envolvidos({$trazPrincipal}, {$regra}, '{$tipo}', {$origem})";
        $rsEnvolvidos = db_query($buscaEnvolvidos);
        $envolvidos= \db_utils::getCollectionByRecord($rsEnvolvidos);
        $cgmPrincipal =  $envolvidos[0]->rinumcgm;
        foreach ($envolvidos as $envolvido) {

            $daoInicialNomes->v58_inicial = $inicialBase->getCodigo();
            $daoInicialNomes->v58_numcgm  = $envolvido->rinumcgm;
            $daoInicialNomes->incluir($inicialBase->getCodigo(), $envolvido->rinumcgm);
            if ($daoInicialNomes->erro_status == 0) {
                throw  new \DBException("N]ao possível incluir dados do envolvido na Inicial.");
            }
        }

        return $cgmPrincipal;
    }

    /**
     * @return Parametro|null
     */
    public function getParametrosJuridico()
    {
        return $this->parametrosJuridico;
    }

    /**
     * @param Parametro $parametrosJuridico
     */
    public function setParametrosJuridico(Parametro $parametrosJuridico)
    {
        $this->parametrosJuridico = $parametrosJuridico;
    }
}