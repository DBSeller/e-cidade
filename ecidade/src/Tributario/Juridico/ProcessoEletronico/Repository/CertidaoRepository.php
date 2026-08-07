<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Certidao;

/**
 * Class CertidaoRepository
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class CertidaoRepository 
{
    /**
     * Metodo busca as certidoes
     *
     * @param $dataCalcProcess
     * @param $anoCalc
     * @param $certidoes
     * @return array
     * @throws \BusinessException
     */
    public function getCertidoes($dataCalcProcess, $anoCalc , $certidoes) 
    {

         /**
         * @todo, mover para classe de processamento?
         * @rodar fc_calcula, agrupar por cda, ano e procedencia
         * Ajusta o valor de cada certidao
         */
        $dataCalculoProcesso = $dataCalcProcess;
        $anoCalculo = $anoCalc;
        $whereDadosCertidao = "v14_certid in({$certidoes})";
        $sqlDadosCertidao  = "select v13_certid as certidao, ";
        $sqlDadosCertidao .= "       v01_exerc as exercicio,";
        $sqlDadosCertidao .= "       db02_texto as base_legal,";
        $sqlDadosCertidao .= "       fc_calcula(v01_numpre, v01_numpar,0, '{$dataCalculoProcesso}', '{$dataCalculoProcesso}',{$anoCalculo}) as debito,";
        $sqlDadosCertidao .= "       v03_procedtipo as natureza";
        $sqlDadosCertidao .= "  from certid ";
        $sqlDadosCertidao .= "       inner join certdiv on v14_certid = v13_certid ";
        $sqlDadosCertidao .= "       inner join divida  on v14_coddiv = v01_coddiv";
        $sqlDadosCertidao .= "       inner join proced on v03_codigo = v01_proced";
        $sqlDadosCertidao .= "       left join procedparag on v80_proced = v03_codigo";
        $sqlDadosCertidao .= "       left join db_documento on v80_docum = db03_docum";
        $sqlDadosCertidao .= "       left join db_docparag on db04_docum = db03_docum";
        $sqlDadosCertidao .= "       left join db_paragrafo on db02_idparag = db04_idparag";
        $sqlDadosCertidao .= " where {$whereDadosCertidao}";
        $sqlDadosCertidao .= " order by v13_certid";

        $sqlCertidao  = "select certidao, ";
        $sqlCertidao .= "       exercicio,";
        $sqlCertidao .= "       array_to_string(array_accum(distinct base_legal), ',') as base_legal,";
        $sqlCertidao .= "       natureza,";
        $sqlCertidao .= "       sum(cast(substr(debito, 15, 13) as float) + cast(substr(debito, 28, 13) as float) + ";
        $sqlCertidao .= "       cast(substr(debito, 41, 13) as float) + cast(substr(debito, 54, 13) as float)) as valor";
        $sqlCertidao .= "  from ($sqlDadosCertidao) as dados_certidao";
        $sqlCertidao .= "  group by certidao, natureza, exercicio";

        $rsDadosCertidao = db_query($sqlCertidao);

        if (!$rsDadosCertidao) {
            throw new \BusinessException("Não foi possível calcular valores da Certidão");
        }

        $aCertidao    = pg_fetch_all($rsDadosCertidao);
        $ListCertidao = array();
                       
        foreach ($aCertidao as $item) {
            $oCertidao = new  Certidao(); 
            $oCertidao->setAnoExercicio($item['exercicio']); 
            $oCertidao->setNumeroCertidao($item['certidao']);
            $oCertidao->setValorDivida($item['valor']);
            $oCertidao->setBaseLegal($item['base_legal']);
            $oCertidao->setNaturezaDivida($item['natureza']);

            $ListCertidao[] =  $oCertidao;
        }

        return $ListCertidao;
    }


}