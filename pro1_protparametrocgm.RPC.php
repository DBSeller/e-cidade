<?php

header('Content-Type: application/json');

require_once modification("libs/db_stdlib.php");
require_once modification("std/db_stdClass.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification('dbforms/db_funcoes.php');

require_once modification("classes/db_cgm_campos_obrigatorios_classe.php");

$aParametros = !empty($_POST) ? filter_input_array(INPUT_POST) : filter_input_array(INPUT_GET);

try {
    $oRetorno = new stdClass();
    $oRetorno->erro = false;

    switch ($aParametros['exec']) {

        case 'salvaCamposObrigatorios':
            $cl_cgmCamposObrigatorios =  new cl_cgmcamposobrigatorios();

            $oPostgresResource = db_query($cl_cgmCamposObrigatorios->sql_query_file());
            $aQueries = array();

            while ($row = pg_fetch_assoc($oPostgresResource)) {
                $htmlId = $row['p73_html_id'] . '-' . $row['p73_tipo_pessoa'];

                if (!array_key_exists($htmlId, $aParametros) && $row['p73_obrigatorio'] === 'f') {
                    continue;
                }

                if (array_key_exists($htmlId, $aParametros) && $row['p73_obrigatorio'] === 't') {
                    continue;
                }

                $sUpdateTo = $row['p73_obrigatorio'] === 't' ? 'f' : 't';
                $aQueries[] = "
                    UPDATE cgm_campos_obrigatorios
                        SET p73_obrigatorio = '{$sUpdateTo}' WHERE p73_sequencial = {$row['p73_sequencial']}
                ";
            }
            
            array_walk($aQueries, function (&$value, $key) {
                $value = trim(str_replace(array("\r", "\n"), '', $value));
            });
    
            db_inicio_transacao();
    
            $cl_cgmCamposObrigatorios->query_exec(implode(';', $aQueries));
            
            db_fim_transacao();

            break;
        
        case 'buscaCamposObrigatorios':
            $sTipoPessoa = $aParametros['tipo_pessoa'];
            
            $oRetorno->camposObrigatorios = getCamposObrigatorios($aParametros['tipo_pessoa']);
        
            break;
        
        case 'validaCamposCgm':
            $cl_cgm = new cl_cgm();
            $aCgm = null;

            if (!$aParametros['cpf_cnpj'] && $aParametros['idCgm']) {
                $sSqlCgm = $cl_cgm->sql_query_file($aParametros['idCgm'], 'z01_cgccpf');
                $aCgm = pg_fetch_assoc(db_query($sSqlCgm));
            }

            $sCpfCnpj = $aCgm ? $aCgm['z01_cgccpf'] : $aParametros['cpf_cnpj'];
            if (strlen($sCpfCnpj) != 14 && strlen($sCpfCnpj) != 11) {
                $oRetorno->validado = false;
                break;
            }

            $tipoPessoa = strlen($sCpfCnpj) == 14 ? 'juridica' : 'fisica';

            $camposObrigatorios = getCamposObrigatorios($tipoPessoa);

            $camposConsulta = array('z01_cgccpf');
            $mapCampos = array();

            $mapCamposEndereco = array(
                'txtDescrBairropri' => 'db73_descricao',
                'txtDescrRuapri' => 'db74_descricao',
                'txtCepEndpri' => 'db74_cep',
                'txtDescrPontoReferenciapri' => 'db74_cep'
            );

            foreach ($camposObrigatorios as $campo) {
                $camposConsulta[] = $mapCamposEndereco[$campo['p73_html_id']] ? $mapCamposEndereco[$campo['p73_html_id']] :
                    $campo['p73_html_id'];

                $mapCampos[$campo['p73_html_id']]['label'] = $campo['p73_label'];
                $mapCampos[$campo['p73_html_id']]['preenchido'] = true;
            }

            $sSql = $cl_cgm->sqlQueryCamposCadastroCgm($aParametros['idCgm'], $camposConsulta);
            
            $oPostgresResource = db_query($sSql);

            $oRetorno->validado = true;

            while ($row = pg_fetch_assoc($oPostgresResource)) {
                foreach ($row as $column => $value) {
                    if ($mapCampos[$column]) {
                        if (empty($value)) {
                            $oRetorno->validado = false;
                            $mapCampos[$column]['preenchido'] = false;
                        }
                    }
                }
            }

            $oRetorno->campos = $mapCampos;
            
            break;
    }

} catch (Exception $e) {
    if(db_utils::inTransaction()) {
        db_fim_transacao(true);
    }

    $oRetorno->mensagem = $e->getMessage();
    $oRetorno->erro = true;
}


function getCamposObrigatorios($sTipoPessoa)
{
    $cl_cgmCamposObrigatorios =  new cl_cgmcamposobrigatorios();

    $oPostgresResource = db_query(
        $cl_cgmCamposObrigatorios->sql_query_file(
            null,
            null,
            null,
            "p73_tipo_pessoa = '{$sTipoPessoa}' AND p73_obrigatorio = 't'"
        )
    );
    
    $camposObrigatorios = array();
    
    while ($row = pg_fetch_assoc($oPostgresResource)) {
        $camposObrigatorios[] = db_utils::utf8ize($row);
    }

    return $camposObrigatorios;
}

echo json_encode($oRetorno);
