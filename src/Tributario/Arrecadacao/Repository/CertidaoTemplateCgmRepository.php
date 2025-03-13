<?php


namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\CertidaoTemplateCgmModel;

class CertidaoTemplateCgmRepository extends \BaseClassRepository
{
    /**
     *@var CertidaoTemplateCgmRepository
     */
    protected static $oInstance;

    /**
     * @param CertidaoTemplateCgmModel $oCertidaoCgm
     * @return int
     * @throws DBException
     */
    public function persist(CertidaoTemplateCgmModel $oCertidaoCgm)
    {
        $oDaoCertidaoCgm = new \cl_certidaocgm();

        $sequencial = $oCertidaoCgm->getSequencial();

        $oDaoCertidaoCgm->p49_sequencial = $sequencial;
        $oDaoCertidaoCgm->p49_numcgm     = $oCertidaoCgm->getNumcgm();

        $lResult = $oDaoCertidaoCgm->incluir(null);

        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($sequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a certidão\n' . $oDaoCertidaoCgm->erro_msg;
            throw new \DBException($sMensagem);
        }
    }
}
