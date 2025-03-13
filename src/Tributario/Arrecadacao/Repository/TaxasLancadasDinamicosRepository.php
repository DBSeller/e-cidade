<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasDinamicos;

class TaxasLancadasDinamicosRepository extends \BaseClassRepository
{
    public function persist(TaxasLancadasDinamicos $entity)
    {
        $dao = new \cl_taxaslancadasdinamicos;

        $dao->ar47_sequencial = $entity->getSequencial();
        $dao->ar47_taxaslancadas = $entity->getTaxaslancadas();
        $dao->ar47_codcam = $entity->getCodcam();
        $dao->ar47_obrigatorio = $entity->getObrigatorio();
        $dao->ar47_tipocampo = $entity->getTipocampo();
        $dao->ar47_valordefault = $entity->getValordefault();

        if (!empty($dao->ar47_sequencial)) {
            $dao->alterar($dao->ar47_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function delete(TaxasLancadasDinamicos $entity)
    {
        $dao = new \cl_taxaslancadasdinamicos();

        $sWhere = $this->getCondicao($entity);

        $dao->excluir("", $sWhere);

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function getCampos(TaxasLancadasDinamicos $entity)
    {
        $dao = new \cl_taxaslancadasdinamicos();

        $sWhere = $this->getCondicao($entity);

        $result = db_query(
            $dao->sql_query(
                "",
                "ar47_sequencial,
                 ar47_codcam,
                 ar47_obrigatorio,
                 ar47_tipocampo,
                 ar47_valordefault,
                 nomecam,
                 conteudo,
                 descricao,
                 rotulo,
                 tamanho,
                 nulo,
                 maiusculo,
                 (CASE WHEN ar47_tipocampo = 2 THEN 7
                       WHEN ar47_tipocampo = 4 THEN 5
                       WHEN ar47_tipocampo = 5 THEN 2
                       WHEN ar47_tipocampo = 6 THEN 4
                       WHEN ar47_tipocampo = 7 THEN 800
                       ELSE ar47_tipocampo
                    END) AS tipoGrm",
                "ar47_codcam",
                $sWhere
            )
        );

        if (!$result) {
            throw new \Exception("Erro ao buscar os os campos dinâmicos. ");
        }

        $aCampos = \db_utils::getColectionByRecord($result);

        foreach ($aCampos as $key => $oCampo) {
            $valorDefault = $this->getValorDefaultCampo($oCampo);

            $aCampos[$key]->valorDefault = $valorDefault;
        }

        return $aCampos;
    }

    private function getCondicao(TaxasLancadasDinamicos $entity)
    {
        $sWhere = "";

        if (!empty($entity->getSequencial())) {
            $sWhere = " ar47_sequencial = ".$entity->getSequencial();
        }

        if (!empty($entity->getTaxaslancadas())) {
            (trim($sWhere) != "" ? $sWhere .= " AND" : "");

            $sWhere = $sWhere." ar47_taxaslancadas = ".$entity->getTaxaslancadas();
        }

        if (!empty($entity->getCodcam())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." ar47_codcam = ".$entity->getCodcam();
        }

        if (!empty($entity->getObrigatorio())) {
            (trim($sWhere) != "" ? $sWhere .= " AND " : "");

            $sWhere = $sWhere." ar47_obrigatorio = ".$entity->getObrigatorio();
        }

        return $sWhere;
    }

    private function getValorDefaultCampo($oCampo)
    {
        $valor = "";

        if ($oCampo->ar47_tipocampo == 3) {
            if (trim(strrpos($oCampo->ar47_valordefault, 'now')) != "") {
                $data = date("d/m/Y");
                if (trim(strrpos($oCampo->ar47_valordefault, '#')) != "") {
                    $dias = explode("#", $oCampo->ar47_valordefault)[1];
    
                    $data = date("d/m/Y", strtotime("+{$dias} days"));
                } else {
                    if (trim(strrpos($oCampo->ar47_valordefault, '|')) != "") {
                        $dias = explode("|", $oCampo->ar47_valordefault)[1];
    
                        $data = date("d/m/Y", strtotime("-{$dias} days"));
                    }
                }
            }

            $valor = $data;
        } elseif ($oCampo->ar47_tipocampo == 7) {
            $aOptions = explode("|", $oCampo->ar47_valordefault);
            $aOptions2 = array();

            if (!empty($aOptions)) {
                foreach ($aOptions as $option) {
                    $oOptions = new \stdClass();
                    $aOption = explode("#", $option);

                    $oOptions->label = $aOption[0];
                    $oOptions->value = $aOption[1];

                    $aOptions2[] = $oOptions;
                }
            }

            $valor = $aOptions2;
        } else {
            $valor = $oCampo->ar47_valordefault;
        }

        return $valor;
    }
}
