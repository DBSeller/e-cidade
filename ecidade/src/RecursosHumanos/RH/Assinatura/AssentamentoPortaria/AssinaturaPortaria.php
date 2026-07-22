<?php

namespace ECidade\RecursosHumanos\RH\Assinatura\AssetamentoPortaria;

class AssinaturaPortaria
{
    private $paramsSigner;

    private $paramsPortaria;

    /**
     * @param array $params
     * @return $this
     */
    public function setParamsSigner(array $params)
    {
        $this->paramsSigner = $params;
        return $this;
    }

    /**
     * @param array $paramsPortaria
     * @return $this
     */
    public function setParamsPortaria(array $paramsPortaria)
    {
        $this->paramsPortaria = $paramsPortaria;
        return $this;
    }

    /**
     * Persiste a assinatura da portaria na base
     *
     * @return $this
     */
    public function persist()
    {
        $error = false;

        db_inicio_transacao();
        $oAssinaturaPortaria  = new \cl_assinaturaportaria();
        $oAssinaturaDocumento = new \cl_assinaturadocumento();


        $oidDcoumento = $this->salvarArquivoBanco();
        $oAssinaturaDocumento->assinatura_documento  = $oidDcoumento;
        $oAssinaturaDocumento->assinatura_status     = 'false';
        $oAssinaturaDocumento->assinatura_hash       = md5($this->paramsSigner['content']);
        $oAssinaturaDocumento->assinatura_versao     = 0;
        $oAssinaturaDocumento->assinatura_data       = date('Y-m-d');

        $oAssinaturaDocumento->incluir();

        if ($oAssinaturaDocumento->erro_status == 0) {
            $error =  true;
            $msg = "Problema ao salvar a assinatura do documento.".$oAssinaturaDocumento->erro_msg;
            throw new \BusinessException($msg);
        }

        $oAssinaturaPortaria->h15_portaria   = $this->paramsPortaria['sId'];
        $oAssinaturaPortaria->h15_assinatura = $oAssinaturaDocumento->assinatura_sequencial;

        $oAssinaturaPortaria->incluir();

        if ($oAssinaturaPortaria->erro_status == 0) {
            $error =  true;
            throw new \BusinessException("Problema ao vincular assinatura a portaria.".$oAssinaturaPortaria->erro_msg);
        }

        db_fim_transacao($error);

        return $this;
    }

    /**
     * Salva arquivo no banco
     * - gera OID
     *
     * @access private
     * @return int
     */
    private function salvarArquivoBanco()
    {

        $iOid = \DBLargeObject::criaOID(true);
        $lEscreveuArquivo = \DBLargeObject::writeContent($this->paramsSigner['content'], $iOid);

        if (!$lEscreveuArquivo) {
            throw new \BusinessException("Problema ao salvar assinatura na base.");
        }

        return $iOid;
    }

    /**
     * Retorna a portaria assinada
     *
     */
    public function getPortariaAssinada()
    {
        $error = false;
        $numeroPortaria = $this->paramsPortaria['portaria'];

        $sSql = "SELECT assinaturadocumento.* FROM assinaturaportaria
                   INNER  JOIN portaria ON  h31_sequencial = h15_portaria
                   INNER  JOIN  assinaturadocumento  ON h15_assinaturaportaria_sequencial = h15_assinatura

                   WHERE   h31_numero = '{$numeroPortaria}' AND  h31_anousu = 2018;";


        $rsDocumentoAssinado = db_query($sSql);

        if (!$rsDocumentoAssinado) {
            throw new \BusinessException("Problema ao buscar portaria assinada.");
        }

        $oPortariaAss =  pg_fetch_object($rsDocumentoAssinado);

        if (!empty($oPortariaAss)) {
            $sNomeArquivo = 'portaria_' . $oPortariaAss->assinatura_hash . '.pdf';
            $sCaminhoArquivo = 'tmp/' . $sNomeArquivo;
            db_inicio_transacao();
            $lEscreveuArquivo = \DBLargeObject::leitura($oPortariaAss->assinatura_documento, $sCaminhoArquivo);

            if (!$lEscreveuArquivo) {
                $error = true;
                throw new \BusinessException("Problema ao exportar portaria assinada da base.");
            }

            db_fim_transacao($error);
            return $sCaminhoArquivo;
        }
    }
}
