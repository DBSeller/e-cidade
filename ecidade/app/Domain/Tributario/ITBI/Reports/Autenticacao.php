<?php

namespace App\Domain\Tributario\ITBI\Reports;

use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;

abstract class Autenticacao
{
    /**
     * @var integer
     */
    protected $numeroGuia;

    /**
     * @var \DBDate
     */
    protected $dataPagamento;

    /**
     * @var \stdClass
     */
    protected $dadosItbi;

    /**
     * @var string
     */
    private $arquivo;

    /**
     * @var bool
     */
    protected $migrado = false;

    /**
     * @var string
     */
    protected $numeroDam;

    /**
     * Seta o número da guia de ITBI
     * @param int $numeroGuia
     * @return Autenticacao
     */
    public function setNumeroGuia($numeroGuia)
    {
        $this->numeroGuia = $numeroGuia;
        return $this;
    }

    /**
     * Seta a data de pagamento
     * @param string $dataPagamento
     * @return Autenticacao
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = new \DBDate($dataPagamento);
        return $this;
    }

    /**
     * Seta os dados necessários para geração do arquivo
     * @param string $label
     * @param \stdClass $valor
     * @return Autenticacao
     */
    protected function setDadosItbi($label, $valor)
    {
        if (!isset($this->dadosItbi)) {
            $this->dadosItbi = new \stdClass();
        }

        $this->dadosItbi->$label = $valor;
        return $this;
    }

    /**
     * Retorna o base64 do arquivo
     * @return string
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * Seta o base64 do arquivo
     * @param string $arquivo
     * @return Autenticacao
     */
    private function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    /**
     * @param bool $migrado
     * @return Autenticacao
     */
    public function setMigrado($migrado)
    {
        $this->migrado = $migrado;
        return $this;
    }

    /**
     * @param string $numeroDam
     * @return Autenticacao
     */
    public function setNumeroDam($numeroDam)
    {
        $this->numeroDam = $numeroDam;
        return $this;
    }

    /**
     * Faz a geração do PDF da autenticação
     */
    protected function gerarArquivoAutenticacao()
    {
        $sNomeArquivo = md5($this->numeroGuia.$this->dataPagamento);
        $sCaminhoArquivo = ECIDADE_PATH."tmp/";
        $sLocalArquivoTemplate = "{$sCaminhoArquivo}{$sNomeArquivo}.doc";
        $sLocalArquivoSaida = "{$sCaminhoArquivo}{$sNomeArquivo}.pdf";

        $this->ajustaTemplate()->saveAs($sLocalArquivoTemplate);

        convertToPdf($sLocalArquivoTemplate, $sCaminhoArquivo);

        unlink($sLocalArquivoTemplate);

        $sArquivo = base64_encode(file_get_contents($sLocalArquivoSaida));

        unlink($sLocalArquivoSaida);

        $this->setArquivo($sArquivo);
    }

    private function ajustaTemplate()
    {
        $template = $this->carregaTemplate();

        $template->setValue("numeroGuia", $this->numeroGuia);
        $iMatricula = isset($this->dadosItbi->oItbiMatric->it06_matric)
                        ?
                      $this->dadosItbi->oItbiMatric->it06_matric : "";

        $template->setValue("matricula", $iMatricula);

        $it22_compl = isset($this->dadosItbi->oDadosImovel->it22_compl)
            ? $this->dadosItbi->oDadosImovel->it22_compl : "";
        $template->setValue("complemento", trim($it22_compl));

        $it22_descrlograd = isset($this->dadosItbi->oDadosImovel->it22_descrlograd)
            ? $this->dadosItbi->oDadosImovel->it22_descrlograd : "";
        $template->setValue("logradouro", trim($it22_descrlograd));

        $it22_numero = isset($this->dadosItbi->oDadosImovel->it22_numero)
            ? $this->dadosItbi->oDadosImovel->it22_numero : "";
        $template->setValue("numero", $it22_numero);

        $bairro = isset($this->dadosItbi->oIptuender->j43_bairro)
        ? trim($this->dadosItbi->oIptuender->j43_bairro) : $this->dadosItbi->oGuia->j13_descr;

        $template->setValue("bairro", $bairro);

        $template->setValue("dataPagamento", $this->dataPagamento->getDate(\DBDate::DATA_PTBR));

        $it22_setor = isset($this->dadosItbi->oDadosImovel->it22_setor)
            ? $this->dadosItbi->oDadosImovel->it22_setor : "";
        $template->setValue("setor", $it22_setor);

        $it22_quadra = isset($this->dadosItbi->oDadosImovel->it22_quadra)
            ? $this->dadosItbi->oDadosImovel->it22_quadra : "";
        $template->setValue("quadra", $it22_quadra);

        $it22_lote = isset($this->dadosItbi->oDadosImovel->it22_lote) ? $this->dadosItbi->oDadosImovel->it22_lote : "";
        $template->setValue("lote", $it22_lote);

        $template->setValue("numpre", $this->numeroDam);
        $template->setValue(
            "transmitentePrincipal",
            str_replace("&", "&amp;", $this->dadosItbi->oTransmitente->it03_nome)
        );
        $template->setValue("outrosTansmitentes", $this->dadosItbi->aTransSecond);
        $template->setValue(
            "adquirentePrincipal",
            str_replace("&", "&amp;", $this->dadosItbi->oAdquirente->it03_nome)
        );
        $template->setValue("outrosAdquirentes", $this->dadosItbi->aAdquiSecond);
        $template->setValue("transacao", $this->dadosItbi->oGuia->it04_descr);
        $template->setValue("valorTotal", $this->fmatMoeda($this->dadosItbi->oItbiavalia->it14_valoraval));
        $template->setValue("valorTerreno", $this->fmatMoeda($this->dadosItbi->oItbiavalia->it14_valoravalter));
        $template->setValue("valorBenfeitoria", $this->fmatMoeda($this->dadosItbi->oItbiavalia->it14_valoravalconstr));
        $template->setValue("observacaoItbi", $this->dadosItbi->oGuia->it01_obs);
        $template->setValue("matriculaRi", $this->dadosItbi->oGuia->it22_matricri);

        $template = $this->ajustaTipoItbi($template);

        $aTableFonts = ["name" => "Times New Roman", "size" => 10];
        $template = $this->formaPagamento($template, $aTableFonts);
        $template = $this->dadosBancarios($template, $aTableFonts);

        $template->setValue("setorFiscal", $this->dadosItbi->oGuia->j90_descr);

        $template->setValue("setorLocalizacao", $this->dadosItbi->oGuia->j05_descr);
        $template->setValue("quadraLocalizacao", $this->dadosItbi->oGuia->j06_quadraloc);
        $template->setValue("loteLocalizacao", $this->dadosItbi->oGuia->j06_lote);

        $aTableConfig = ['borderSize' => 1, 'width' => 100 * 50, 'unit' => TblWidth::PERCENT];
        $template = $this->ajustaCaracteristicasDistribuicaoRural($template, $aTableFonts, $aTableConfig);
        $template = $this->ajustaCaracteriscaUtilizacaoRural($template, $aTableFonts, $aTableConfig);

        $template = $this->ajustaEnderecoTransmitentePri($template, $aTableFonts, $aTableConfig);
        $template = $this->ajustaEnderecoAdquirentePri($template, $aTableFonts, $aTableConfig);

        return $template;
    }

    private function carregaTemplate()
    {
        $oDocumentoTemplate = new \documentoTemplate(63, null, "", false, "docx");

        if (empty($oDocumentoTemplate->getArquivoTemplate())) {
            throw new \Exception("Não foi configurado template para o tipo de template 63.");
        }

        $template = new TemplateProcessor($oDocumentoTemplate->getArquivoTemplate());

        unlink($oDocumentoTemplate->getArquivoTemplate());

        return $template;
    }

    private function fmatMoeda($valor)
    {
        return number_format($valor, 2, ",", ".");
    }

    private function formaPagamento($template, $aTableFonts)
    {
        $tabelaFormasPagamento = new Table(['borderSize' => 1, 'width' => 100 * 50, 'unit' => TblWidth::PERCENT]);
        $tabelaFormasPagamento->addRow();
        $tabelaFormasPagamento->addCell(150)->addText('Formas de Pagamento', $aTableFonts);

        $valorTotal = 0;
        $textoFormasPagamento = [];

        foreach ($this->dadosItbi->aPagamentos as $key => $oPagamento) {
            if ($key > 0) {
                $tabelaFormasPagamento->addRow();
            }

            $valorFormatado = $this->fmatMoeda($oPagamento->k00_valor);
            $descricao = trim($oPagamento->k02_drecei);

            $valorTotal += $oPagamento->k00_valor;
            $textoFormasPagamento[] = "{$descricao}: {$valorFormatado}";

            $tabelaFormasPagamento->addCell(150)->addText($descricao, $aTableFonts);
            $tabelaFormasPagamento->addCell(150)->addText($valorFormatado, $aTableFonts);
        }

        $template->setComplexBlock('tabelaFormasPagamento', $tabelaFormasPagamento);
        $template->setValue("textoFormasPagamento", implode("; ", $textoFormasPagamento));
        $template->setValue("valorTotalFormasPagamento", $this->fmatMoeda($valorTotal));

        return $template;
    }

    private function dadosBancarios($template, $aTableFonts)
    {
        $oDisbanco = $this->dadosItbi->oDisbanco;

        if (isset($oDisbanco->db90_descr) and !empty($oDisbanco->db90_descr)) {
            $sBanco = $oDisbanco->db90_descr;
        } else {
            $sBanco = "Caixa {$oDisbanco->caixa} da prefeitura.";
        }

        $tabelaBancoAgenciaConta = new Table(['borderSize' => 1, 'width' => 100 * 50, 'unit' => TblWidth::PERCENT]);
        $tabelaBancoAgenciaConta->addRow();
        $tabelaBancoAgenciaConta->addCell(150)->addText("Banco: {$sBanco}", $aTableFonts);
        $tabelaBancoAgenciaConta->addCell(150)->addText("Agência: {$oDisbanco->agencia}", $aTableFonts);
        $tabelaBancoAgenciaConta->addCell(150)->addText("Conta: {$oDisbanco->conta}", $aTableFonts);

        $template->setComplexBlock('tabelaBancoAgenciaConta', $tabelaBancoAgenciaConta);
        $template->setValue("banco", $sBanco);
        $template->setValue("agencia", $oDisbanco->agencia);
        $template->setValue("conta", $oDisbanco->conta);

        return $template;
    }

    private function ajustaTipoItbi($template)
    {
        if (empty($this->dadosItbi->oGuia->it05_guia)) {
            $sTipo = "Rural";
        } else {
            if (empty($this->dadosItbi->oGuia->it18_guia)) {
                $sTipo = "Urbano";
            } else {
                $sTipo = "Não Definido";
            }
        }

        $template->setValue("tipoItbi", $sTipo);

        return $template;
    }

    private function ajustaCaracteristicasDistribuicaoRural($template, $aTableFonts, $aTableConfig)
    {
        if (count($this->dadosItbi->aCaracterDistrib) == 0) {
            $template->setValue("tabelaCaracteristicasItbiRuralDistribuicao", "");
            $template->setValue("caracteristicasItbiRuralDistribuicao", "");

            return  $template;
        }

        $tabelaCaracteristicas = new Table($aTableConfig);
        $tabelaCaracteristicas->addRow();
        $tabelaCaracteristicas->addCell(150)->addText("Descrição", $aTableFonts);
        $tabelaCaracteristicas->addCell(150)->addText("Valor:", $aTableFonts);

        $aTextoCaracterUtilizacao = [];

        foreach ($this->dadosItbi->aCaracterDistrib as $aCaracterUtilizacao) {
            $tabelaCaracteristicas->addRow();
            $tabelaCaracteristicas->addCell(150)->addText($aCaracterUtilizacao["j31_descr"], $aTableFonts);
            $tabelaCaracteristicas->addCell(150)->addText($aCaracterUtilizacao["it19_valor"], $aTableFonts);

            $aTextoCaracterUtilizacao[] = "{$aCaracterUtilizacao["j31_descr"]}: {$aCaracterUtilizacao["it19_valor"]}";
        }

        $template->setComplexBlock('tabelaCaracteristicasItbiRuralDistribuicao', $tabelaCaracteristicas);
        $template->setValue("caracteristicasItbiRuralDistribuicao", implode(", ", $aTextoCaracterUtilizacao));

        return $template;
    }

    private function ajustaCaracteriscaUtilizacaoRural($template, $aTableFonts, $aTableConfig)
    {
        if (count($this->dadosItbi->aCaracterUtil) == 0) {
            $template->setValue("tabelaCaracteristicasItbiRuralUtilizacao", "");
            $template->setValue("caracteristicasItbiRuralDistribuicao", "");

            return $template;
        }

        $tabelaCaracteristicas = new Table($aTableConfig);
        $tabelaCaracteristicas->addRow();
        $tabelaCaracteristicas->addCell(150)->addText("Descrição", $aTableFonts);
        $tabelaCaracteristicas->addCell(150)->addText("Valor:", $aTableFonts);

        $aTextoCaracterUtilizacao = [];

        foreach ($this->dadosItbi->aCaracterUtil as $aCaracterUtilizacao) {
            $tabelaCaracteristicas->addRow();
            $tabelaCaracteristicas->addCell(150)->addText($aCaracterUtilizacao["j31_descr"], $aTableFonts);
            $tabelaCaracteristicas->addCell(150)->addText($aCaracterUtilizacao["it19_valor"], $aTableFonts);

            $aTextoCaracterUtilizacao[] = "{$aCaracterUtilizacao["j31_descr"]}: {$aCaracterUtilizacao["it19_valor"]}";
        }

        $template->setComplexBlock('tabelaCaracteristicasItbiRuralUtilizacao', $tabelaCaracteristicas);
        $template->setValue("caracteristicasItbiRuralDistribuicao", implode(", ", $aTextoCaracterUtilizacao));

        return $template;
    }

    private function ajustaEnderecoTransmitentePri($template, $aTableFonts, $aTableConfig)
    {
        $oTransmitente = $this->dadosItbi->oTransmitente;

        $tabela = new Table($aTableConfig);
        $tabela->addRow();
        $tabela->addCell(150)->addText("Endereço: {$oTransmitente->it03_endereco}", $aTableFonts);
        $tabela->addCell(150)->addText("Número: {$oTransmitente->it03_numero}", $aTableFonts);

        $tabela->addRow();
        $tabela->addCell(150)->addText("Bairro: {$oTransmitente->it03_bairro}", $aTableFonts);
        $tabela->addCell(150)->addText("CEP: {$oTransmitente->it03_cep}", $aTableFonts);

        $tabela->addRow();
        $tabela->addCell(150)->addText("Município: {$oTransmitente->it03_munic}", $aTableFonts);
        $tabela->addCell(150)->addText("UF: {$oTransmitente->it03_uf}", $aTableFonts);

        $template->setComplexBlock('tabelaEnderecoTransmitentePrincipal', $tabela);

        $sEndereco = "{$oTransmitente->it03_endereco}, ";
        $sEndereco .= "{$oTransmitente->it03_numero}, ";
        $sEndereco .= "{$oTransmitente->it03_bairro}, ";
        $sEndereco .= "{$oTransmitente->it03_cep}, ";
        $sEndereco .= "{$oTransmitente->it03_munic}, ";
        $sEndereco .= "{$oTransmitente->it03_uf}";

        $template->setValue("enderecoTransmitentePrincipal", $sEndereco);

        return $template;
    }

    private function ajustaEnderecoAdquirentePri($template, $aTableFonts, $aTableConfig)
    {
        $oAdquirente = $this->dadosItbi->oAdquirente;

        $tabela = new Table($aTableConfig);
        $tabela->addRow();
        $tabela->addCell(150)->addText("Endereço: {$oAdquirente->it03_endereco}", $aTableFonts);
        $tabela->addCell(150)->addText("Número: {$oAdquirente->it03_numero}", $aTableFonts);

        $tabela->addRow();
        $tabela->addCell(150)->addText("Bairro: {$oAdquirente->it03_bairro}", $aTableFonts);
        $tabela->addCell(150)->addText("CEP: {$oAdquirente->it03_cep}", $aTableFonts);

        $tabela->addRow();
        $tabela->addCell(150)->addText("Município: {$oAdquirente->it03_munic}", $aTableFonts);
        $tabela->addCell(150)->addText("UF: {$oAdquirente->it03_uf}", $aTableFonts);

        $template->setComplexBlock('tabelaEnderecoAdquirentePrincipal', $tabela);

        $sEndereco = "{$oAdquirente->it03_endereco}, ";
        $sEndereco .= "{$oAdquirente->it03_numero}, ";
        $sEndereco .= "{$oAdquirente->it03_bairro}, ";
        $sEndereco .= "{$oAdquirente->it03_cep}, ";
        $sEndereco .= "{$oAdquirente->it03_munic}, ";
        $sEndereco .= "{$oAdquirente->it03_uf}";

        $template->setValue("enderecoAdquirentePrincipal", $sEndereco);

        return $template;
    }
}
