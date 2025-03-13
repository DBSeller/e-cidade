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

namespace ECidade\RecursosHumanos\ESocial\Mapeadores\Relatorios;

class TrabalhadorSemVinculoMapeador implements FormularioMapeador
{
    private $dadosEcidade = [];

    private $dadosFormulario = [];

    public function __construct()
    {
    }

    public function getPerguntas()
    {
        //os intentificadores devem estar em minusculo pois o sql coloca em minusculo os nomes das colunas
        return [
            0 => "codcateg",
            1 => "matricorig",
            2 => "matricced"
        ];
    }

    public function getConfiguracoesColunas()
    {
        return [
        ];
    }

    public function getCamposSistema()
    {
        return [
        ];
    }

    public function getIdentificador($original = false)
    {
        if ($original) {
            return "cpfTrab";
        }
        return "cpftrab";
    }

    public function getDePara()
    {
    }

    public function getSqlDePara($ano, $mes)
    {
        $sql = "";
        return $sql;
    }

    public function setDadosEcidade($dadosEcidade)
    {
        $this->dadosEcidade = $dadosEcidade;
    }

    public function getDadosEcidade()
    {
        return $this->dadosEcidade;
    }

    public function setDadosFormulario($dadosFormulario)
    {
        $this->dadosFormulario = $dadosFormulario;
    }

    public function getDadosFormulario()
    {
        return $this->dadosFormulario;
    }

    public function processarDadosEcidade($ano, $mes)
    {
        $sql = $this->getSqlDePara($ano, $mes);
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException("Erro ao buscar as informações do sistema.");
        }

        $this->dadosEcidade =\db_utils::makeCollectionFromRecord($rs, function ($dadoEcidade) {
            return (get_object_vars($dadoEcidade));
        });
    }

    public function getColunas()
    {
        $colunas = [];
        $configuracoes = $this->getConfiguracoesColunas();
        foreach ($this->getPerguntas() as $campo) {
            foreach ($this->getDadosFormulario()->fields as $perguntaFormulario) {
                if ($campo == $perguntaFormulario->identificador) {
                    foreach ($configuracoes as $configuracao => $dados) {
                        if ($configuracao == $perguntaFormulario->identificador) {
                            $dados['titulo'] = $perguntaFormulario->descricao;
                            $colunas[$campo] = $dados;
                            continue;
                        }
                    }
                }
            }
        }
        // Adicionando campo auxiliar
        $colunas["bases"] = $configuracoes['base'];
        $colunas["tiporubrica"] = $configuracoes['tiporubrica'];

        return $colunas;
    }
}
