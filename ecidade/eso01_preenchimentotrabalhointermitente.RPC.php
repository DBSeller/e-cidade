<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification("dbforms/db_funcoes.php");

use ECidade\RecursosHumanos\ESocial\Entity\TrabalhoIntermitente;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

$parametros = JSON::requestParameters();

try {
    db_inicio_transacao();

    $retorno = new stdClass();
    $retorno->erro = false;

    switch ($parametros->acao) {
        case 'buscar':
            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(TrabalhoIntermitente::AVALIACAO);

            $avaliacaoAdapter = new AvaliacaoEsocialAdapter($avaliacao);
            $avaliacaoAdapter->setTrabalhoIntermitente(true);

            if (!empty($parametros->preenchimento)) {
                $avaliacao->setAvaliacaoGrupo($parametros->preenchimento);
                $avaliacaoAdapter->setCodigoGrupoResposta($parametros->preenchimento);
            }

            $retorno->formulario = $avaliacaoAdapter->getObject();
            break;
        case 'salvar':
            $where = array(
                "eso19_codigoconvocacao = '{$parametros->codConv}'"
            );

            $dao = new cl_avaliacaogruporespostatrabintermitente();
            $sql = $dao->sql_query_file(null, 'eso19_avaliacaogruporesposta', null, implode(' AND ', $where));
            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception("Não foi possível verificar se há um preenchimento referente à convocação {$parametros->codConv} do servidor {$parametros->matricula}.");
            }

            $preenchimento = pg_num_rows($rs) > 0 ? pg_fetch_object($rs)->eso19_avaliacaogruporesposta : null;

            $perguntasRespostas = JSON::create()->parse($parametros->perguntasRespostas);
            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(TrabalhoIntermitente::AVALIACAO);
            $avaliacao->setAvaliacaoGrupo($preenchimento);

            $parametrosAvaliacao = (array)$parametros;
            $parametrosAvaliacao['iCodigoPreenchimento'] = $avaliacao->getAvaliacaoGrupo();

            $avaliacaoESocial = new AvaliacaoESocial();
            $avaliacaoESocial->setAvaliacao($avaliacao);
            $avaliacaoESocial->setPerguntasRespostas($perguntasRespostas);
            $avaliacaoESocial->salvar(null, Tipo::TRABALHO_INTERMITENTE, $parametrosAvaliacao);

            $retorno->mensagem = 'Formulário salvo com sucesso!';
            break;
        case 'buscarEmpregador':
            $codigoInstituicao = db_getsession('DB_instit');

            $sqlCgm = "
                SELECT DISTINCT
                  z01_numcgm                      AS cgm,
                  z01_cgccpf || ' - ' || z01_nome AS empregador
                FROM rhlota
                  INNER JOIN cgm ON rhlota.r70_numcgm = cgm.z01_numcgm
                WHERE r70_instit = {$codigoInstituicao}
                ORDER BY z01_numcgm 
            ";

            $resultadoSqlCgm = db_query($sqlCgm);

            if (!$resultadoSqlCgm) {
                throw new DBException("Não foi possível buscar os empregadores da instituição {$codigoInstituicao}.");
            }

            if (pg_num_rows($resultadoSqlCgm) === 0) {
                throw new DBException("Não há empregadores cadastrados para a instituição {$codigoInstituicao}.");
            }

            $retorno->empregadores = db_utils::getCollectionByRecord($resultadoSqlCgm);
            break;
        case 'verificarSeExisteConvocacao':
            $where = array(
                "eso19_codigoconvocacao = '{$parametros->codConv}'"
            );

            $dao = new cl_avaliacaogruporespostatrabintermitente();
            $sql = $dao->sql_query_file(null, 'eso19_matricula', null, implode(' AND ', $where));
            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception("Não foi possível verificar se há um preenchimento referente à convocação {$parametros->codConv} do servidor {$parametros->matricula}.");
            }

            $retorno->existe = false;

            if (pg_num_rows($rs) > 0) {
                $matricula = pg_fetch_object($rs)->eso19_matricula;
                $retorno->mensagem = "Já existe um preenchimento contendo o código de convocação {$parametros->codConv} para o servidor {$matricula}. Os dados do preenchimento serão alterados, deseja continuar?";
                $retorno->existe = true;
            }
            break;
        case 'consultarServidores':
            $where = array();

            if ($parametros->cpf !== '') {
                $where[] = "z01_cgccpf = '{$parametros->cpf}'";
            }
            if ($parametros->matricula !== '') {
                $where[] = "rh01_regist = {$parametros->matricula}";
            }
            if ($parametros->cgm !== '') {
                $where[] = "rh01_numcgm = {$parametros->cgm}";
            }
            if ($parametros->nome !== '') {
                $where[] = "z01_nome ILIKE '%{$parametros->nome}%'";
            }

            if (count($where) === 0) {
                throw new Exception('É necessário informar ao menos um filtro.');
            }

            if ($parametros->empregador !== '') {
                $where[] = "rh01_instit in (select codigo from db_config where db_config.numcgm = {$parametros->empregador})";
            }


            $dao = new cl_rhpessoal();
            $campos = "rh01_regist AS matricula, z01_cgccpf AS cpf, rh01_numcgm AS cgm, z01_nome AS nome, z01_pis AS nis";
            $sql = $dao->sql_query(null, $campos, "z01_nome", implode(' AND ', $where));
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Erro ao buscar os dados do servidor.");
            }

            if (pg_num_rows($rs) === 0) {
                throw new Exception('Nenhum registro encontrado para os filtros informados.');
            }

            $retorno->resultados = array();

            while ($servidor = pg_fetch_object($rs)) {
                $formato = strlen($servidor->cpf) === 11 ? 'CPF' : 'cnpj';
                $servidor->cpf = db_formatar($servidor->cpf, $formato);
                $retorno->resultados[] = $servidor;
            }

            break;
        case 'consultarPreenchimentos':
            $campos = array(
                'cgm.z01_nome AS nome',
                'cgm.z01_cgccpf AS cpf',
                'rhpessoal.rh01_numcgm AS cgm',
                'rhpessoal.rh01_regist AS matricula',
                'avaliacaogruporespostatrabintermitente.eso19_avaliacaogruporesposta AS preenchimento',
                'avaliacaogruporespostatrabintermitente.eso19_codigoconvocacao AS codigo_convocacao',
                'avaliacaoresposta.db106_resposta :: date AS data_inicio'
            );

            $where = array();

            if ($parametros->cpf !== '') {
                $where[] = "cpf = '{$parametros->cpf}'";
            }
            if ($parametros->matricula !== '') {
                $where[] = "matricula = {$parametros->matricula}";
            }
            if ($parametros->cgm !== '') {
                $where[] = "cgm = {$parametros->cgm}";
            }
            if ($parametros->nome !== '') {
                $where[] = "nome ILIKE '%{$parametros->nome}%'";
            }
            if ($parametros->codigoConvocacao !== '') {
                $where[] = "codigo_convocacao = '{$parametros->codigoConvocacao}'";
            }
            if (!empty($parametros->dataInicio)) {
                $where[] = "data_inicio >= to_date('{$parametros->dataInicio}', 'DD/MM/YYYY')";
            }
            if (!empty($parametros->dataFinal)) {
                $where[] = "data_inicio <= to_date('{$parametros->dataFinal}', 'DD/MM/YYYY')";
            }

            if (count($where) === 0) {
                throw new Exception('É necessário informar ao menos um filtro.');
            }

            $dao = new cl_avaliacaogruporespostatrabintermitente();
            $sql = $dao->sqlPreenchimentos($parametros->empregador, $campos, $where);
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Erro ao buscar os preenchimentos.");
            }

            if (pg_num_rows($rs) === 0) {
                throw new Exception('Nenhum registro encontrado para os filtros informados.');
            }

            $retorno->resultados = array();

            while ($preenchimento = pg_fetch_object($rs)) {
                $formato = strlen($preenchimento->cpf) === 11 ? 'CPF' : 'cnpj';
                $preenchimento->cpf = db_formatar($preenchimento->cpf, $formato);
                $data = new DateTime($preenchimento->data_inicio);
                $preenchimento->data_inicio = $data->format('d/m/Y');
                $retorno->resultados[] = $preenchimento;
            }

            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
