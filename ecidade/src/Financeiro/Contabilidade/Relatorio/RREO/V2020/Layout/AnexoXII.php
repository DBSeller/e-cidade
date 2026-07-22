<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoXII as DadosAnexoXII;
use ECidade\Library\SpreadSheet\Template\Parser as Parser;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout
 */
class AnexoXII
{
    /**
     * @var DadosAnexoXII
     */
    protected $anexo;

    private $parser;

    const PLANILHA_PADRAO = "config/templates/MDF/RREO/2020/AnexoXII.xlsx";
    const PLANILHA_KEY_DRIVE = "1D3tS4X1WZ3Nc8i-qGvJY9cZYaw92qfZ46SIVYssTeEM";
    const PLANILHA_KEY_GID = "1149677950";
    const PLANILHA_SAIDA = "tmp/AnexoXII_2020.xlsx";

    private $linhas = [];

    /**
     * AnexoXII constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @throws \ParameterException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function imprimir()
    {
        $nomeArquivoTemplate = $this->getTemplateFromDrive();
        $this->parser->findSectionsautomatically(true);
        $templateRelatorio = $this->anexo->getTemplateRelatorio();
        if (!empty($templateRelatorio)) {
            $nomeArquivoTemplate = $templateRelatorio;
        }
        $this->parser->loadXLS($nomeArquivoTemplate);
        $instituicaoSessao = \InstituicaoRepository::getInstituicaoSessao();
        $ente = DemonstrativoFiscal::getEnteFederativo($instituicaoSessao);
        if ($instituicaoSessao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $ente .= "\n" . $instituicaoSessao->getDescricao();
        }
        $assinaturas = $this->anexo->getTextoAssinaturas();
        $texto = $this->anexo->getTextoNotaExplicativa();
        $dadosEmissor = $this->getDadosEmissor();
        $this->parser->setData($this->linhas);
        $this->parser->addVariable('texto_lei_organica', $this->anexo->getLabelLeiOrganica());
        $this->parser->addVariable('ente_federecao', utf8_encode($ente));
        $this->parser->addVariable('periodo_referencia', utf8_encode($this->anexo->getTituloPeriodo()));
        $this->parser->addVariable('nota_explicativa', utf8_encode($texto));
        $this->parser->addVariable('assinatura_prefeito', utf8_encode($assinaturas["prefeito"]));
        $this->parser->addVariable('assinatura_contador', utf8_encode($assinaturas["contador"]));
        $this->parser->addVariable('assinatura_secretario', utf8_encode($assinaturas["secretario"]));
        $this->parser->addVariable('nota_explicativa', utf8_encode($texto));
        $this->parser->addVariable('ente_emissor', utf8_encode($dadosEmissor->nome));
        $this->parser->addVariable('endereco_ente', utf8_encode($dadosEmissor->endereco));
        $this->parser->addVariable('municipio', utf8_encode($dadosEmissor->municipio));
        $this->parser->addVariable('telefone', utf8_encode($dadosEmissor->telefone));
        $this->parser->addVariable('cnpj', utf8_encode($dadosEmissor->cnpj));
        $this->parser->addVariable('email', utf8_encode($dadosEmissor->email));
        $this->parser->addVariable('site', utf8_encode($dadosEmissor->url));
        $this->parser->addImage(
            $dadosEmissor->logo,
            'B1',
            ["width" => 100, "height" => 140, 'name' => 'Logo', 'description' => 'Logo municipio', "offsetx" => 20]
        );
        $this->parser->parse();
        $this->parser->save(self::PLANILHA_SAIDA);

        /**
         * limpamos o arquivo do template
         */
        if (!empty($templateRelatorio)) {
            unlink($templateRelatorio);
        }

        /**
         * Força o download do arquivo
         */
        header("Content-disposition: attachment; filename=" . basename(self::PLANILHA_SAIDA));
        header("Content-type: application/download");
        readfile(self::PLANILHA_SAIDA);
    }

    /**
     * @param $anexo
     * @throws \Exception
     */
    public function setAnexo($anexo)
    {
        $this->anexo = $anexo;
        $this->linhas = $this->anexo->getDados();
    }

    /**
     * Retorna o template atraves do google drive
     * @return string
     * @throws \Exception
     */
    private function getTemplateFromDrive()
    {

        $nomeArquivo = "tmp/template_anexo_xii_2020.xlsx";
        $urlDrive = "https://docs.google.com/feeds/download/spreadsheets/Export?";
        $urlDrive .= "key=" . self::PLANILHA_KEY_DRIVE . "&exportFormat=xlsx&gid=" . self::PLANILHA_KEY_GID;
        file_put_contents($nomeArquivo, file_get_contents($urlDrive));
        if (!is_file($nomeArquivo)) {
            $mensagem = 'Não foi possivel Realizar download do template. Verifica sua conexão com a internet.';
            throw new \Exception($mensagem);
        }
        return $nomeArquivo;
    }

    protected function getDadosEmissor()
    {
        $dados = db_query("select nomeinst,
                                   db21_compl,
                                   trim(ender)||',
                                   '||trim(cast(numero as text)) as ender,
                                   trim(ender) as rua,
                                   munic,
                                   numero,
                                   uf,
                                   cgc,
                                   telef,
                                   email,
                                   url,
                                   logo
                            from db_config where codigo = " . db_getsession("DB_instit"));

        $emissor = \db_utils::fieldsMemory($dados, 0);

        $dadosEmissor = new \stdClass();
        $dadosEmissor->nome = $emissor->nomeinst;
        $dadosEmissor->endereco = $emissor->rua . ", " . $emissor->numero;
        $dadosEmissor->municipio = $emissor->munic . " - " . $emissor->uf;
        $dadosEmissor->telefone = $emissor->telef;
        $dadosEmissor->cnpj = db_formatar($emissor->cgc, "cnpj");
        $dadosEmissor->email = $emissor->email;
        $dadosEmissor->url = $emissor->url;
        $dadosEmissor->logo = 'imagens/files/' . $emissor->logo;
        return $dadosEmissor;
    }
}
