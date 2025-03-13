<?php


namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\CertidaoTemplateInscricaoModel;

class CertidaoTemplateInscricaoRepository extends \BaseClassRepository
{
    /**
     *@var CertidaoTemplateInscricaoRepository
     */
    protected static $oInstance;

    /**
     * @param CertidaoTemplateInscricaoRepository $oCertidaoInscricao
     * @return int
     * @throws DBException
     */
    public function persist(CertidaoTemplateInscricaoModel $oCertidaoInscricao)
    {
        $oDaoCertidaoInscr = new \cl_certidaoinscr();

        $sequencial = $oCertidaoInscricao->getSequencial();

        $oDaoCertidaoInscr->p48_sequencial = $sequencial;
        $oDaoCertidaoInscr->p48_inscr      = $oCertidaoInscricao->getInscr();

        $lResult = $oDaoCertidaoInscr->incluir(null);

        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($sequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a certidão\n' . $oDaoCertidaoInscr->erro_msg;
            throw new \DBException($sMensagem);
        }
    }
}
