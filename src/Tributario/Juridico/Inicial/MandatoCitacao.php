<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 03/10/18
 * Time: 17:25
 */

namespace ECidade\Tributario\Juridico\Inicial;


class MandatoCitacao
{


    /**
     * @var Inicial
     */
    private $inicial;
    /**
     * @var \Pdf3
     */
    private $pdf;


    /**
     * @var \Instituicao
     */
    private $instituicao;

    private $listaCertidoes = array();
    /**
     * @var \DateTime
     */
    private $dataCorrecao;

    /**
     * MandatoCitacao constructor.
     * @param Inicial $inicial
     * @param \Pdf3|null $pdf
     */
    public function __construct(Inicial $inicial, \Pdf3 $pdf = null, \DateTime $dataCorrecao)
    {

        $this->inicial = $inicial;
        $this->pdf = $pdf;
        $this->dataCorrecao = $dataCorrecao;
    }

    /**
     * Emite o Documento da Inicial
     * @param bool $anexar
     * @throws \DBException
     */
    public function emitir($anexar = false)
    {

        $pdf = $this->pdf;
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak('on', 10);
        $CertidoesDaInicial = $this->inicial->getCertidoes();
        foreach ($CertidoesDaInicial as $certidao) {
            $this->listaCertidoes[] = $certidao->getCodigo();
        }

        $nomesRepository = \ECidade\Tributario\Juridico\Inicial\Repository\InicialNome::getInstance();
        $nomes = $nomesRepository->getByInitial($this->inicial->getCodigo());
        $procedencias = implode(", ", $this->getProcedenciasDaInicial());
        $valorAtualizado = $this->inicial->getValorAtualizadoAte($this->dataCorrecao);
        foreach ($nomes as $inicialNome) {

            $cgm = \CgmRepository::getByCodigo($inicialNome->getCgm());
            $pdf->AddPage();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 4, 'MANDADO DE CITAÇÃO, PENHORA E AVALIAÇÃO', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(5);
            $pdf->Cell(90, 4, 'Processo nº:	', 0, 0, 'L');
            $pdf->Cell(90, 4, 'Distribuído em:', 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->Cell(180, 4, "Ação: Execução Fiscal - Cobrança de Tributo/ Dívida Ativa / {$procedencias}", 0, 1,
                'L');

            $pdf->Ln(5);
            $pdf->Cell(90, 4, "Exequente: {$this->getInstituicao()->getDescricao()}", 0, 1, 'L');
            $pdf->Cell(90, 4, "Executado: {$cgm->getNome()}", 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(35, 4, "Local da Diligência: ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(90, 4, $this->getEnderecoCgm($cgm), 0, 1, 'L');
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 4, "Valor da Dívida Referida: R$ ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(90, 4, trim(db_formatar($valorAtualizado, "f")), 0, 1, 'L');

            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(5);
            foreach ($this->getParagrafos() as $paragrafo) {

                if (!empty($paragrafo["quebra"])) {
                    $pdf->Ln($paragrafo["quebra"]);
                }
                $pdf->MultiCell(185, 5, $paragrafo["texto"], 0, $paragrafo["alinhamento"], '', $paragrafo["recuo"]);
            }

        }
        return;
    }

    /**
     * Retorna todas as procedências da inicial
     * @return array
     * @throws \DBException
     */
    private function getProcedenciasDaInicial()
    {

        $sqlProcedenciaInicial = "select distinct v28_descricao ";
        $sqlProcedenciaInicial .= "  from inicialcert ";
        $sqlProcedenciaInicial .= "       inner join certid on v13_certid = v51_certidao";
        $sqlProcedenciaInicial .= "       inner join certdiv on v14_certid = v13_certid";
        $sqlProcedenciaInicial .= "       inner join divida on v01_coddiv = v14_coddiv";
        $sqlProcedenciaInicial .= "       inner join proced on v03_codigo = v01_proced";
        $sqlProcedenciaInicial .= "       inner join procedtipo on v28_sequencial =  v03_procedtipo";
        $sqlProcedenciaInicial .= "       where v51_inicial = {$this->inicial->getCodigo()}";

        $rsProcedenciaInicial = db_query($sqlProcedenciaInicial);
        if (!$rsProcedenciaInicial) {
            throw new \DBException("Não possível pesquisar procedências da inicial {$this->inicial}");
        }
        return \db_utils::makeCollectionFromRecord($rsProcedenciaInicial, function ($dados) {
            return $dados->v28_descricao;
        });
    }

    /**
     * @return \Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * Retorna o Ccgm Formatado para o documento
     * @param \CgmBase $cgm
     * @return string
     */
    private function getEnderecoCgm(\CgmBase $cgm)
    {

        $endereco = array($cgm->getLogradouro() . ", Nº" . $cgm->getNumero());
        if ($cgm->getBairro() != '') {
            $endereco[] = ", Bairro " . $cgm->getBairro();
        }

        $endereco[] = $cgm->getMunicipio() . " - " . $cgm->getUf();
        if ($cgm->getCep() != '') {
            $endereco[] = ", CEP: " . $cgm->getCep();
        }
        if ($cgm->getCaixaPostal() != '') {
            $endereco[] = ", Caixa Postal: " . $cgm->getCaixaPostal();
        }

        return implode(" ", $endereco);
    }

    /**
     * Paragrafos do mandato de citacao
     */
    public function getParagrafos()
    {
        $listaCda = implode(", ", $this->listaCertidoes);
        $paragrafo = array();
        $paragrafo[] = array(

            "texto" => "MM Juiz de Direito Dr(ª) da Central de Dívida Ativa de Niteroi, MANDA ao Oficial de Justiça deste Juizo, em cumprimento e a requerimento do EXEQUENTE, CITE o devedor acima indicado, ou a quem de direito, para que em obediência ao presente mandato, pague em 05 (cinco) dias, a dívida com juros e correção monetária até seu efetivo pagamento ou garanta a execução, tudo de conformidade com o requerido no processo de execução fiscal acima referido, com assento na(s) certidão(ões) de dívida ativa nº {$listaCda} sob pena de penhora de bens suficientes para satisfazer a execução. Não ocorrendo o pagamento, nem a garantia da execução, proceda a penhora ou arresto, e os respectivos registros, independentemente do pagamento de custas e outras despesas, e a avaliação dos bens penhorados ou arrestados (Art 7º, III, IV e V da Lei 6.830 de 22/09/1980).",
            "alinhamento" => "J",
            "recuo" => 25
        );
        $paragrafo[] = array(
            "texto" => "Cientifique, ainda, ao devedor, que tem o prazo de 30 (trinta) dias para opor Embargos. O que se cumpre na forma e sob as penas da Lei. Em caso de penhora, sendo o bem imóvel, determina este Juizo, que o Oficial do Cartório de Registro de Imóveis faça a anotação da penhora, em obediência ao Art 14 da Lei 6.830. Dado e passado nesta cidade de Niterói. ",
            "alinhamento" => "J",
            "recuo" => 25
        );
        $paragrafo[] = array(
            "texto" => "Eu________________ Cristiane Leal Quadros Lima - Responsável pelo expediente - Mat. 01/22056, digitei e conferi. E eu ________________________ o subscrevo e assino por ordem do MM Juiz de Direito.\nAVISO:  PAGAMENTO - 1º) Dirija-se eu endereço do exequente para fazer o pagamento ou parcelamento. (Rua Visconde de Sepetiba, 519 - 7º Andar - Niteroi/RJ)",
            "alinhamento" => "J",
            "recuo" => 0
        );
        $paragrafo[] = array(
            "texto" => "Cristiane Leal Quadros Lima - Responsável pelo expediente - Mat. 01/22056\nAssino por ordem do MM Juiz de Direito",
            "alinhamento" => "C",
            "recuo" => 0,
            "quebra" => 10
        );
        $paragrafo[] = array(
            "texto" => "Código de Autenticação",
            "alinhamento" => "C",
            "recuo" => 0,
            "quebra" => 10
        );
        return $paragrafo;
    }

}