<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\CertidaoTemplateModel;

class CertidaoTemplateRepository extends \BaseClassRepository
{
    /**
     *@var CertidaoTemplateRepository
     */
    protected static $oInstance;

    /**
     * @param CertidaoTemplateModel $oCertidao
     * @return int
     * @throws DBException
     */
    public function persist(CertidaoTemplateModel $oCertidao)
    {
        $oDaoCertidao = new \cl_certidao();
        $sequencial                            = $oCertidao->getSequencial();
        $oDaoCertidao->p50_idusuario           = $oCertidao->getIdUsuario();
        $oDaoCertidao->p50_tipo                = $oCertidao->getTipo();
        $oDaoCertidao->p50_data                = $oCertidao->getData();
        $oDaoCertidao->p50_hora                = $oCertidao->gethora();
        $oDaoCertidao->p50_ip                  = $oCertidao->getIp();
        $oDaoCertidao->p50_hist                = $oCertidao->getHistorico();
        $oDaoCertidao->p50_web                 = $oCertidao->isWeb();
        $oDaoCertidao->p50_codproc             = $oCertidao->getCodigoProcesso();
        $oDaoCertidao->p50_exerc               = $oCertidao->getExercicio();
        $oDaoCertidao->p50_codimpresso         = $oCertidao->getCodigoImpresso();
        $oDaoCertidao->p50_instit              = $oCertidao->getInstituicao();
        $oDaoCertidao->p50_arquivo             = $oCertidao->getArquivo();
        $oDaoCertidao->p50_diasvalidade        = $oCertidao->getDiasValidade();
        $oDaoCertidao->p50_nomeservico         = $oCertidao->getNomeServico();
        $oDaoCertidao->p50_resultadowebservice = $oCertidao->getResultadoWebservice();
        $oDaoCertidao->p50_datahoraconsulta    = $oCertidao->getDataHoraConsulta();

        if (!empty($sequencial)) {
            $oDaoCertidao->p50_sequencial = $sequencial;
            $lResult = $oDaoCertidao->alterar($sequencial);
        } else {
            $lResult = $oDaoCertidao->incluir(null);
        }

        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($sequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a certidão\n' . $oDaoCertidao->erro_msg;
            throw new \DBException($sMensagem);
        }
        $oCertidao->setSequencial($oDaoCertidao->p50_sequencial);
    }

    /**
     * @param $sCampo
     * @return mixed
     */
    public function getByDadosCertidao($sCampo)
    {
        $oDaoTemplate = new \cl_numpref();
        $sSql         = $oDaoTemplate->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), $sCampo);
        $sSqlTemplate = $oDaoTemplate->sql_record($sSql);
        $result       = \db_utils::fieldsMemory($sSqlTemplate, 0);

        return $result->{$sCampo};
    }
}
