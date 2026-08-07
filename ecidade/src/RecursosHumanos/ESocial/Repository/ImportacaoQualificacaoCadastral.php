<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_importacaoqualificacaocadastral;
use db_utils;
use Exception;
use ECidade\RecursosHumanos\ESocial\Entity\ImportacaoQualificacaoCadastral as Entity;
use ECidade\File\Csv\Dumper\Dumper as ArquivoCSV;
use ECidade\RecursosHumanos\ESocial\Model\QualificacaoCadastral;

/**
 * Class ImportacaoQualificacaoCadastral
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class ImportacaoQualificacaoCadastral
{
    /**
     * @param $id
     * @return Entity
     * @throws Exception
     */
    public function getById($id)
    {
        $dao = new cl_importacaoqualificacaocadastral();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Erro ao buscar importação da qualificação cadastral.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Não foi encontrado importação da qualificação cadastral com o codigo {$id}.");
        }

        return db_utils::makeFromRecord($rs, function ($dado) {
            $qualificacao = new Entity();
            $qualificacao->setId($dado->eso11_sequencial)
                ->setData(new \DateTime($dado->eso11_data))
                ->setInstituicao(new \Instituicao($dado->eso11_instituicao))
                ->setNomeArquivo($dado->eso11_nomearquivo)
                ->setProcessado($dado->eso11_processado == 't')
                ->setArquivoOid($dado->eso11_arquivo);

            return $qualificacao;
        });
    }

    /**
     * @param Entity $qualificacaoCadastral
     * @return bool
     * @throws Exception
     */
    public function save(Entity $qualificacaoCadastral)
    {
        $this->inTransaction();

        $dao = new cl_importacaoqualificacaocadastral();
        $dao->eso11_sequencial = $qualificacaoCadastral->getId();
        $dao->eso11_data = $qualificacaoCadastral->getData()->format('Y-m-d H:i:s');
        $dao->eso11_instituicao = $qualificacaoCadastral->getInstituicao()->getCodigo();
        $dao->eso11_nomearquivo = $qualificacaoCadastral->getNomeArquivo();
        $dao->eso11_processado = $qualificacaoCadastral->isProcessado() ? "true" : "false";
        $dao->eso11_arquivo = $qualificacaoCadastral->getArquivoOid();

        if (empty($dao->eso11_sequencial)) {
            $dao->incluir();
        } else {
            $dao->alterar($dao->eso11_sequencial);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar importação da qualificação cadastral.");
        }

        return true;
    }

    /**
     * @param Entity $qualificacaoCadastral
     * @return bool
     * @throws Exception
     */
    public function delete(Entity $qualificacaoCadastral)
    {
        $this->deleteFromId($qualificacaoCadastral->getId());
        unset($qualificacaoCadastral);

        return true;
    }

    public function deleteFromId($id)
    {
        $this->inTransaction();

        $dao = new cl_importacaoqualificacaocadastral();
        $dao->excluir($id);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir importação da qualificação cadastral.");
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function inTransaction()
    {
        if (!db_utils::inTransaction()) {
            throw new Exception("Sem transação ativa com o banco de dados.");
        }

        return true;
    }

    /**
     * Busca todos os arquivos que foram importados na instituição
     * @param integer $instituicao
     * @return Entity[]
     * @throws Exception
     */
    public function getByInstituicao($instituicao)
    {
        $dao = new cl_importacaoqualificacaocadastral();
        $sql = $dao->sql_query_file(null, "*", "eso11_data", "eso11_instituicao = {$instituicao}");
        $rs = \db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar importação da qualificação cadastral.");
        }

        $dados = \db_utils::makeCollectionFromRecord($rs, function ($data) {
            $ImportacaoQualificacaoCadastral = new Entity();
            $ImportacaoQualificacaoCadastral->setId($data->eso11_sequencial);
            $ImportacaoQualificacaoCadastral->setData(new \DateTime($data->eso11_data));
            $ImportacaoQualificacaoCadastral->setInstituicao($data->eso11_instituicao);
            $ImportacaoQualificacaoCadastral->setNomeArquivo($data->eso11_nomearquivo);
            $ImportacaoQualificacaoCadastral->setProcessado($data->eso11_processado);
            $ImportacaoQualificacaoCadastral->setArquivoOid($data->eso11_arquivo);
            return $ImportacaoQualificacaoCadastral;
        });

        return $dados;
    }

    /**
     * Retorna os dados formatados para o relatório, agrupados por CPF do servidor.
     * @param integer $id
     * @param integer|null $filtroCargo
     * @param integer|null $filtroLotacao
     * @param integer|null $listaServidores
     * @return array
     */
    public function getDadosRelatorio($id, $filtroCargo = null, $filtroLotacao = null, $listaServidores = null)
    {
        $daoRhPessoal = new \cl_rhpessoal();
        $arquivoQualificao = $this->lerArquivoQualificao($id, $listaServidores);

        $campos = "z01_cgccpf as cpf, rh01_regist as matricula, z01_nome as nome, r70_descr as lotacao";
        $where  = '(' . implode(" or ", $arquivoQualificao->cpfs) . ')';
        $where .= 'and not exists (select 1 from rhpesrescisao where rhpesrescisao.rh05_seqpes = rh02_seqpes limit 1)';

        if (!empty($filtroCargo)) {
            $where .= " and rh02_funcao = {$filtroCargo}";
        }

        if (!empty($filtroLotacao)) {
            $where .= " and rh02_lota = {$filtroLotacao}";
        }

        $sql = $daoRhPessoal->sql_query_cgm(null, $campos, "z01_nome, rh01_regist", $where);
        $rs  = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os dados do CGM.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Nenhuma matrícula encontrada para os filtros selecionados");
        }

        $dadosRelatorio = array();
        \db_utils::makeCollectionFromRecord($rs, function ($retorno) use ($arquivoQualificao, &$dadosRelatorio) {

            $qualificacaoCadastral = new QualificacaoCadastral();
            $qualificacaoCadastral->setCpf($retorno->cpf);
            $qualificacaoCadastral->setMatricula($retorno->matricula);
            $qualificacaoCadastral->setNome($retorno->nome);
            $qualificacaoCadastral->setDescricaoLotacao($retorno->lotacao);
            if (!empty($arquivoQualificao->erros[$retorno->cpf])) {
                $qualificacaoCadastral->setInconsistencias($arquivoQualificao->erros[$retorno->cpf]);
            }
            $dadosRelatorio[$retorno->cpf] = $qualificacaoCadastral;
        });

        return $dadosRelatorio;
    }

    /**
     * Retorna os erros e os CPFs do arquivo de retorno do e-social
     * @param int $id
     * @return \stdClass
     */
    private function lerArquivoQualificao($id, $listaServidores = null)
    {
        $arquivoQualificao = new \stdClass();
        $csv = new ArquivoCSV();
        $arquivo = $this->getById($id);
        $csv->setCsvControl();
        $linhasArquivo = $csv->ler($arquivo->getPathArquivo());

        if (empty($linhasArquivo)) {
            throw new Exception("Arquivo importado está em branco.");
        }

        $arquivoQualificao->cpfs = array();
        $arquivoQualificao->erros = array();

        //Remove cabeçalho
        array_shift($linhasArquivo);
        //Remove linha com total de registros processados
        array_pop($linhasArquivo);
        //Remove linha em branco
        array_pop($linhasArquivo);

        $codigoErroRejeitado = 0;
        if (!$arquivo->isProcessado()) {
            $codigoErroRejeitado = 1000;
        }

        foreach ($linhasArquivo as $key => $linha) {
            $cpf = $linha[0];
          
            for ($i = 4; $i < 21; $i++) {
                $codigoErro = $i + 1 + $codigoErroRejeitado;

                if (!empty($linha[$i])) {
                    if ($codigoErro == 21 && $linha[$i] == 2) {
                        $arquivoQualificao->erros[$cpf][-1] = -1;
                    } else {
                        $descricao = $codigoErro;

                        if ($codigoErro == 19) {
                            $nome = explode(' - ', $linha[$i]);
                            $descricao = $nome[1];
                        }

                        $arquivoQualificao->erros[$cpf][$codigoErro] = $descricao;
                    }
                }
            }

            if (empty($listaServidores)) {
                $arquivoQualificao->cpfs[] = "z01_cgccpf = '{$cpf}'";
            }

            if ($listaServidores == 1 && !empty($arquivoQualificao->erros[$cpf])) {
                $arquivoQualificao->cpfs[] = "z01_cgccpf = '{$cpf}'";
            }

            if ($listaServidores == 2 && empty($arquivoQualificao->erros[$cpf])) {
                $arquivoQualificao->cpfs[] = "z01_cgccpf = '{$cpf}'";
            }
        }

        return $arquivoQualificao;
    }
}
