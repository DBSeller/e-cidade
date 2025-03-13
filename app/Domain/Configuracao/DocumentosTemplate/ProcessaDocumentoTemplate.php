<?php
namespace App\Domain\Configuracao\DocumentosTemplate;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;
use NcJoes\OfficeConverter\OfficeConverter;

use \documentoTemplate;

abstract class ProcessaDocumentoTemplate
{
  
    private $documentoTemplate;
    private $dadosVariaveis = [];
    private $nomeDocumento  = "DocTemplate";
    private $extensaoArquivo;
    public function __construct(
        $iTipo = '',
        $iCodDocumento = null,
        $sCaminhoArquivo = '',
        $lTemTransacaoAtiva = false,
        $extensaoArquivo = "docx"
    ) {

        $this->extensaoArquivo = $extensaoArquivo;
        Settings::setTempDir("tmp/");
        if (!is_dir(Settings::getTempDir())) {
            throw new \Exception("Pasta temporária não encontrada");
        }

        $this->documentoTemplate = new documentotemplate(
            $iTipo,
            $iCodDocumento,
            $sCaminhoArquivo,
            $lTemTransacaoAtiva,
            $extensaoArquivo
        );
    }

    public function processaTemplate()
    {
    
        $this->nomeDocumento      = $this->nomeDocumento.time();
        $templateProcessor        = new TemplateProcessor($this->documentoTemplate->getArquivoTemplate());
        $caminhoDocumento         = Settings::getTempDir()."{$this->getNomeDocumento()}.{$this->extensaoArquivo}";
        $caminhoPdf               = Settings::getTempDir()."{$this->getNomeDocumento()}.pdf";
        $variaveisNaoConfiguradas = [];
        $dadosVariaveisTemplate   = $this->configuraDadosVariaveis();
        if (count($dadosVariaveisTemplate)  == 0) {
            throw new \Exception("Não existe dados das variáveis para o template");
        }
        foreach ($templateProcessor->getVariables() as $variavel) {
            if (!array_key_exists($variavel, $dadosVariaveisTemplate)) {
                $templateProcessor->setValue($variavel, "");
                $variaveisNaoConfiguradas[] = $variavel;
                continue;
            }

            if (is_array($dadosVariaveisTemplate[$variavel])) {
                $templateProcessor->cloneBlock($variavel, 0, true, false, $dadosVariaveisTemplate[$variavel]);
            }

            $templateProcessor->setValue($variavel, $dadosVariaveisTemplate[$variavel]);
        }
    
        $templateProcessor->saveAs($caminhoDocumento);
        if (count($variaveisNaoConfiguradas) > 0) {
            $configurarVariaveis = implode(", ", $variaveisNaoConfiguradas);
            $mensagem = "As variáveis {$configurarVariaveis} não foram configuradas";
            if (count($variaveisNaoConfiguradas) == 1) {
                $mensagem = "A variável {$configurarVariaveis} não foi configurada";
            }
      
            throw new \Exception($mensagem);
        }
      // classe para converter o documento docx
        $converter = new OfficeConverter($caminhoDocumento);
        $converter->convertTo($caminhoPdf);
        if (!file_exists($caminhoPdf)) {
            throw new \Exception("Não foi possível emitir documento. Provável causa: template formato inválido");
        }

        unlink($caminhoDocumento);
        unlink($this->documentoTemplate->getArquivoTemplate());
      // caminho do PDF convertido
        return $caminhoPdf;
    }
    
    /**
     * @return array
     */
    abstract public function configuraDadosVariaveis();
  
    public function getNomeDocumento()
    {
        return $this->nomeDocumento;
    }

    public function setNomeDocumento($nomeDocumento)
    {
        $this->nomeDocumento = $nomeDocumento;
    }
}
