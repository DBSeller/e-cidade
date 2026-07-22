<?php

require_once 'fpdf151/pdf.php';

abstract class GenericPdf extends PDF
{
    /**
     * @var bool
     */
    private $mostraArquivo = false;

    /**
     * @var string
     */
    private $arquivo;

    /**
     * @var boolean
     */
    private $retornaBase64 = true;

    /**
     * @param $mostraArquivo
     * @return $this
     */
    public function setMostraArquivo($mostraArquivo)
    {
        $this->mostraArquivo = $mostraArquivo;
        return $this;
    }

    /**
     * @return string
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @param $arquivo
     * @return $this
     */
    protected function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    /**
     * @param $retornaBase64
     * @return $this
     */
    public function setRetornaBase64($retornaBase64)
    {
        $this->retornaBase64 = $retornaBase64;
        return $this;
    }

    /**
     * Gera um PDF
     */
    protected function generate()
    {
        $sNomeArquivo = md5(uniqid().date("Y-m-d H:i:s")).".pdf";
        $sLocalArquivo = ECIDADE_PATH."tmp/{$sNomeArquivo}";

        if ($this->mostraArquivo) {
            $this->Output($sLocalArquivo);
        }

        $this->Output($sLocalArquivo, false, true);

        $sArquivo = ECIDADE_REQUEST_PATH."tmp/{$sNomeArquivo}";

        if ($this->retornaBase64) {
            $sArquivo = base64_encode(file_get_contents($sLocalArquivo));

            unlink($sLocalArquivo);
        }

        $this->setArquivo($sArquivo);
    }
}
