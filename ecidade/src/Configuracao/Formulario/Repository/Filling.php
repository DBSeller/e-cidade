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

namespace ECidade\Configuracao\Formulario\Repository;

use ECidade\Configuracao\Formulario\Factory\IdentifiersTablesFactory;
use ECidade\Configuracao\Formulario\Model\Preenchimento;
use ECidade\Configuracao\Formulario\Model\Resposta;

/**
 * Class Filling
 * Classe que vai criar o preenchimento
 * @package ECidade\Configuracao\Formulario\Repository
 */
class Filling
{

    private $usesDataLoad = false;
    private $idForm = false;

    public function create(Preenchimento $fill)
    {
        if ($this->usesDataLoad) {
            $this->validateFilling($fill);
        }

        $idFill = $this->saveFill();
        $fill->setId($idFill);
        $questions = $fill->getPerguntas();
        foreach ($questions as $question) {
            $this->saveAnswer($idFill, $question->getRespostas());
        }

        $this->saveIdentifiers($idFill, $fill->getIdentificadores());
    }

    private function saveFill()
    {
        $dao = new \cl_avaliacaogruporesposta();
        $dao->db107_sequencial = null;
        $dao->db107_usuario = 1;
        $dao->db107_datalancamento = date('Y-m-d');
        $dao->db107_hora = date('H:i');
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao salvar preenchimento. \n".pg_last_error());
        }

        return $dao->db107_sequencial;
    }

    /**
     * @param $idFill
     * @param Resposta[] $answers
     */
    private function saveAnswer($idFill, array $answers)
    {
        foreach ($answers as $answer) {
            $daoResposta = new \cl_avaliacaoresposta();
            $daoResposta->db106_avaliacaoperguntaopcao = $answer->getOption()->getCodigo();
            $daoResposta->db106_resposta = '';
            if (!is_null($answer->getData())) {
                $daoResposta->db106_resposta = $answer->getData();
            }

            $daoResposta->incluir(null);
            if ($daoResposta->erro_status == 0) {
                throw new \Exception("Erro ao salvar respostas.\n".pg_last_error());
            }

            $answer->setId($daoResposta->db106_sequencial);
            $daoVinculo = new \cl_avaliacaogrupoperguntaresposta();
            $daoVinculo->db108_avaliacaogruporesposta = $idFill;
            $daoVinculo->db108_avaliacaoresposta = $answer->getId();
            $daoVinculo->incluir(null);
            if ($daoVinculo->erro_status == 0) {
                throw new \Exception("Erro ao salvar vínculo da resposta com o preenchimento.\n".pg_last_error());
            }
        }
    }

    /**
     * @param $idFill
     * @param $identifiers
     * @throws \Exception
     */
    private function saveIdentifiers($idFill, $identifiers)
    {
        foreach ($identifiers as $table => $fields) {
            $dao = IdentifiersTablesFactory::getDao($table);

            if (IdentifiersTablesFactory::onlyOne($table)) {
                $this->deleteOldLink($dao, $fields);
            }

            $fkFill = IdentifiersTablesFactory::getFieldForeignKeyFill($table);

            $dao->{$fkFill} = $idFill;
            foreach ($fields as $field) {
                $dao->{$field->campo} = $field->valor;
            }

            $dao->incluir(null);
        }
    }

    /**
     * Deleta os vinculos
     * @todo fazer deletar tb as respostas e preenchimentos
     *
     * @param $dao
     * @param $fields
     * @throws \Exception
     */
    private function deleteOldLink($dao, $fields)
    {
        $where = array();
        foreach ($fields as $field) {
            $where[] = "{$field->campo} = '{$field->valor}'";
        }

        if (empty($where)) {
            throw new \Exception("Não foi informado nenhum filtro para deletar os identificadores.");
        }

        $dao->excluir(null, implode(' and ', $where));
    }


    /**
     * Deleta os vinculos
     *
     * @param $dao
     * @param $fields
     * @throws \Exception
     */
    private function deleteOldFill($idFilling)
    {
        $sql = "
            create temp table w_avaliacaogrupoperguntaresposta as 
            select * from avaliacaogrupoperguntaresposta 
             where db108_avaliacaogruporesposta = {$idFilling}
        ";
        db_query($sql);

        $sql = "
            delete from avaliacaogrupoperguntaresposta 
            using w_avaliacaogrupoperguntaresposta 
            where w_avaliacaogrupoperguntaresposta.db108_sequencial = avaliacaogrupoperguntaresposta.db108_sequencial
        ";
        db_query($sql);

        $sql = "delete from avaliacaoresposta using w_avaliacaogrupoperguntaresposta where db106_sequencial = db108_avaliacaoresposta";
        db_query($sql);

        $sql = "delete from avaliacaogruporesposta where db107_sequencial = {$idFilling}";
        db_query($sql);

        $sql = "drop table w_avaliacaogrupoperguntaresposta";
        db_query($sql);
    }


    public function usesDataLoad($use = false)
    {
        $this->usesDataLoad = $use;
    }

    public function setIdForm($idForm)
    {
        $this->idForm = $idForm;
    }

    /**
     * @param Preenchimento $fill
     */
    private function validateFilling(Preenchimento $fill)
    {
        $questions = $fill->getPerguntas();

        $filterQuestionsAndAnswers = array();
        foreach ($questions as $question) {
            if (!$question->isPerguntaIdentificadora()) {
                continue;
            }

            foreach ($question->getRespostas() as $answer) {
                $resporta = $answer->getOption()->getCodigo();

                if (!is_null($answer->getData())) {
                    $resporta = $answer->getData();
                }

                $filterQuestionsAndAnswers[] = array("pergunta" => $question, "resposta" => $resporta);
            }
        }
        $form = \ECidade\Configuracao\Formulario\Repository\Formulario::getById($this->idForm);
        $oldAnswers = \ECidade\Configuracao\Formulario\Resposta\Repository\Resposta::getPorFormularioECampos($form, $filterQuestionsAndAnswers);
        if (!empty($oldAnswers)) {
            foreach ($oldAnswers as $oldAnswer) {
                $this->deleteOldFill($oldAnswer->getCodigo());
            }
        }
    }
}
