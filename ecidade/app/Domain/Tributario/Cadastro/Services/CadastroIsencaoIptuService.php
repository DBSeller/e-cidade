<?php

namespace App\Domain\Tributario\Cadastro\Services;

final class CadastroIsencaoIptuService
{
    public function getDadosIsencao($codIsen)
    {
        
        $cliptuisen = new \cl_iptuisen;
        $clcfiptu = new \cl_cfiptu;
        $oInstit = new \Instituicao(db_getsession("DB_instit"));
        $logo = $oInstit->getImagemLogo();
        $nomeInstit = $oInstit->getDescricao();

        $rsCfiptu  = $clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'), "*", null, ""));

        db_fieldsmemory($rsCfiptu, 0);

        $sWhereTipoPromitente = '';
        if (isset($j18_dadoscertisen) && $j18_dadoscertisen == 1) {
            $sWhereTipoPromitente = " and (j41_tipopro is true or j41_tipopro is null) ";
        }

        $sSqlDadosIsencao = $cliptuisen->sql_query_isen(
            null,
            " proprietario as nomepropri,
              j41_tipopro,
              j46_codigo,
              j46_matric, 
              j46_tipo,
              j45_descr,
              j46_dtini,
              j46_dtfim,
              j45_obscertidao,
              j34_setor,
              j34_quadra,
              j34_lote,
              j107_nome",
            null,
            " j46_codigo = {$codIsen} {$sWhereTipoPromitente}"
        );
        
        
        $rsDadosIsencao = $cliptuisen->sql_record($sSqlDadosIsencao);
        $oDadosIsencao = \db_utils::fieldsMemory($rsDadosIsencao, 0);
        
        $aDadosIsencao = [
        "codigo_isencao" => $oDadosIsencao->j46_codigo,
        "matricula" => $oDadosIsencao->j46_matric,
        "setor_quadra_lote" => $oDadosIsencao->j34_setor.'/'.$oDadosIsencao->j34_quadra.'/'.$oDadosIsencao->j34_lote,
        "nome_contribuinte" => $oDadosIsencao->nomepropri,
        "tipo_isencao" => $oDadosIsencao->j46_tipo.'/'.$oDadosIsencao->j45_descr,
        "data_inicial" => date("d/m/Y", strtotime($oDadosIsencao->j46_dtini)),
        "data_final" => date("d/m/Y", strtotime($oDadosIsencao->j46_dtfim)),
        "observacao" => $oDadosIsencao->j45_obscertidao,
        "logo" => ECIDADE_REQUEST_PATH .'imagens/files/'.$logo,
        "nome_instituicao" => $nomeInstit
        ];

        if ($oDadosIsencao->j107_nome != null) {
            $aDadosIsencao["condominio"] = $oDadosIsencao->j107_nome;
        }

        return $aDadosIsencao;
    }
}
