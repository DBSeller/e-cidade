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

namespace ECidade\Configuracao\Consistencia;

/**
 * Class ConsistenciaSistema
 * @package ECidade\Configuracao\Consistencia\Database
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class ConsistenciaSistema
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $sistema;

    /**
     * @var mixed
     */
    private $configuracao;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * @param integer $sistema
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;
    }

    /**
     * @return mixed
     */
    public function getConfiguracao()
    {
        return $this->configuracao;
    }

    /**
     * @param mixed $configuracao
     */
    public function setConfiguracao($configuracao)
    {
        $this->configuracao = $configuracao;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function validar()
    {
        if (empty($this->configuracao)) {
            throw new \Exception("Objeto de configuração não definido.");
        }
        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCamposFormulario()
    {
        $this->validar();
        return $this->configuracao->formulario->campos;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getSqlCorrecao()
    {
        $this->validar();
        return $this->configuracao->sql->correcao;
    }

    /**
     * @param array $registros
     * @return bool
     * @throws \Exception
     */
    public function processar($registros = array())
    {
        if (empty($registros)) {
            throw new \Exception("Não foi informado os dados.");
        }

        $sqlCorrecao = $this->getSqlCorrecao();
        $camposFormulario = $this->getCamposFormulario();

        foreach ($registros as $linha) {
            $sqlAux = $sqlCorrecao;

            foreach ($linha as $campo) {
                foreach ($camposFormulario as $campoFormulario) {
                    if (isset($campoFormulario->chave_primaria) && $campoFormulario->chave_primaria
                        && $campo->propriedade === $campoFormulario->propriedade) {
                        $sqlAux = str_replace("[$campo->propriedade]", $campo->valor, $sqlAux);
                    }

                    if (isset($campoFormulario->tipo) && $campo->propriedade === $campoFormulario->propriedade) {
                        $sqlAux = str_replace("[$campo->propriedade]", $campo->valor, $sqlAux);
                    }
                }
            }

            $rs = db_query($sqlAux);
            if (!$rs) {
                throw new \Exception("Erro ao executar sql de correção.\n" . pg_last_error());
            }
        }

        return true;
    }
}
