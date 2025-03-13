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

namespace ECidade\Configuracao\Formulario\Importacao;

use ECidade\Configuracao\Formulario\Mapper\FormsFields;
use ECidade\Configuracao\Formulario\Model\FieldLayoutTxt;
use ECidade\Configuracao\Formulario\Model\Pergunta;
use ECidade\Configuracao\Formulario\Model\Preenchimento;
use ECidade\Configuracao\Formulario\Model\Resposta;
use ECidade\Configuracao\Formulario\Repository\Filling;

class LayoutTxt
{
    private $idForm;
    private $idLayout;
    private $filePath;
    private $formUsesDataLoad = false;
    private $log = array();

    public function setIdForm($idForm)
    {
        $this->idForm = $idForm;
    }

    public function setPathFile($filePath)
    {
        $this->filePath = $filePath;
    }

    private function getIdLayout()
    {
        if (empty($this->idLayout)) {
            $sql = "select * from configuracoes.deparalayoutavaliacao where avaliacao = {$this->idForm}";
            $rs = db_query($sql);
            if (!$rs) {
                throw new \Exception("Erro ao buscar id do layout vinculado ao formulário.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new \Exception("Não existe um layout vinculado ao formulário.");
            }

            if (pg_num_rows($rs) > 1) {
                throw new \Exception("Existe mais de um layout vinculado ao formulário.");
            }

            $this->idLayout = \db_utils::fieldsMemory($rs, 0)->layout;
        }
        return $this->idLayout;
    }

    /**
     * exporta os dados do layout
     * @return array
     * @throws \Exception
     */
    private function parseFile()
    {
        $layout = new \DBLayoutReader($this->getIdLayout(), $this->filePath, true, true);
        $lines = $layout->getLines();
        $keys = $this->mapKeys();

        $dataParse = array();
        foreach ($lines as $i => $line) {
            $propriedades = $line->getProperties();

            foreach ($propriedades as $campo => $propriedade) {
                if ($line->getNomeLinha() == $campo) {
                    continue;
                }

                $value = trim($line->{$campo});
                if (array_key_exists($propriedade['id'], $keys)) {
                    $this->addIdentifiers($dataParse, $i, $keys[$propriedade['id']], $value);
                    continue;
                }

                $dataParse[$i][] = (object)array(
                    'id_campo' => $propriedade['id'],
                    'nome_campo' => $propriedade[6],
                    'identificador' => $campo,
                    'resposta' => $value
                );
            }
        }

        return $dataParse;
    }

    private function mapKeys()
    {
        $sql = "
            select layoutcampo, 
                   tabela,      
                   campo  
              from configuracoes.deparalayoutavaliacao
              join deparalayoutavaliacaoidentificadores 
                on deparalayoutavaliacaoidentificadores.deparalayoutavaliacao = deparalayoutavaliacao.id 
             where avaliacao = {$this->idForm}
        ";

        $rs = db_query($sql);
        if (!$rs) {
            throw new \Exception("Erro ao buscar campos identificadores da avaliação.");
        }

        if (pg_num_rows($rs) > 0) {
            $keys = array();
            \db_utils::makeCollectionFromRecord($rs, function ($data) use (&$keys) {
                $keys[$data->layoutcampo] = (object) array(
                    'layoutcampo' =>$data->layoutcampo,
                    'tabela' =>$data->tabela,
                    'campo' =>$data->campo,
                );
            });

            return $keys;
        }

        return array();
    }


    /**
     * Retorna as perguntas e opcões de resposta do formulário que estão vinculada ao layout
     * @return FieldLayoutTxt[]
     */
    private function getFormFieldsMap()
    {
        $questionForm = new FormsFields();
        return $questionForm->getFromForms($this->idForm);
    }


    private function log($msg)
    {
        $this->log[] = $msg;
    }

    /**
     * @param FieldLayoutTxt[] $fromToFormFields
     * @param array $dataParse
     */
    private function preparingFillForm($fromToFormFields, array $dataParse)
    {
        $fills = array();


        // cada objeto $dataToFill representará um preenchimento
        foreach ($dataParse as $dataToFill) {
            $newFill = new Preenchimento();
            // valida se existe identificadores. Se houver atribui ao preenchimento e remove do array
            if (array_key_exists('identifiers', $dataToFill)) {
                $newFill->setIdentificadores($dataToFill['identifiers']);
                unset($dataToFill['identifiers']);
            }

            foreach ($dataToFill as $dataFill) {
                // @todo verificar se deve lançar erro/ ignorar...
                if (!array_key_exists($dataFill->id_campo, $fromToFormFields)) {
                    continue;
                }

                $formField = $fromToFormFields[$dataFill->id_campo];
                $question = $formField->getQuestion();
                if ($question->isPerguntaIdentificadora()) {
                    $this->formUsesDataLoad = true;
                }

                $questionToAwser = clone $question;

                $answer = new Resposta();
                switch ($question->getTipoResposta()) {
                    case Pergunta::OBJETIVA:
                        // @todo analisar necessidade de valirdar se necessita validar se a opção existe
                        foreach ($question->getOpcoes() as $option) {
                            if ($option->getValorOpcao() == $dataFill->resposta) {
                                $answer->addOption($option);
                                $questionToAwser->addResposta($answer);
                            }
                        }
                        break;
                    case Pergunta::DISSERTATIVA:
                        $option = array_shift($question->getOpcoes());
                        $answer->addOption($option);
                        $answer->setData($dataFill->resposta);
                        $questionToAwser->addResposta($answer);
                        break;
                    default:
                        throw new \Exception("Não foi implementado opção de responder perguntas de multipla escolha.");
                        break;
                }

                $newFill->addPergunta($questionToAwser);
            }

            $fills[] = $newFill;
        }

        return $fills;
    }

    /**
     * @param Preenchimento[] $fills
     */
    private function createFill($fills)
    {
        foreach ($fills as $fill) {
            $filling = new Filling();
            $filling->usesDataLoad($this->formUsesDataLoad);
            $filling->setIdForm($this->idForm);
            $filling->create($fill);
        }
    }

    /**
     * @param $dataParse array com os dados do csv
     * @param $line      linha do csv
     * @param $object    objeto com a tabela e o campo que deve ser salvo como identificador
     * @param $value     valor do identificador
     */
    private function addIdentifiers(&$dataParse, $line, $object, $value)
    {
        $dataParse[$line]['identifiers'][$object->tabela][] = (object) array (
            'campo' => $object->campo,
            'valor' => $value
        );
    }

    public function fazAMao()
    {
        $dataParse = $this->parseFile();
        $fromToFormFields = $this->getFormFieldsMap();

        $fills = $this->preparingFillForm($fromToFormFields, $dataParse);
        $this->createFill($fills);
    }
}
