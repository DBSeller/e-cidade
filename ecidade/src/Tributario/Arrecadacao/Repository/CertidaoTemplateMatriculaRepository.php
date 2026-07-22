<?php


namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\CertidaoTemplateMatriculaModel;

class CertidaoTemplateMatriculaRepository extends \BaseClassRepository
{
    /**
     *@var CertidaoTemplateMatriculaRepository
     */
    protected static $oInstance;

    /**
     * @param CertidaoTemplateMatriculaRepository $oCertidaoMatricula
     * @return int
     * @throws DBException
     */
    public function persist(CertidaoTemplateMatriculaModel $oCertidaoMatricula)
    {
        $oDaoCertidaoMatric = new \cl_certidaomatric();

        $sequencial = $oCertidaoMatricula->getSequencial();

        $oDaoCertidaoMatric->p47_sequencial = $sequencial;
        $oDaoCertidaoMatric->p47_matric     = $oCertidaoMatricula->getMatric();


        $lResult = $oDaoCertidaoMatric->incluir(null);


        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($sequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a certidão\n' . $oDaoCertidaoMatric->erro_msg;
            throw new \DBException($sMensagem);
        }
    }
}
