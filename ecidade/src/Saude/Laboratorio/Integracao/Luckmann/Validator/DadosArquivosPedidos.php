<?php
/**
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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Validator;

/**
 * Class DadosArquivosPedidos
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Validator
 */
class DadosArquivosPedidos
{
    /**
     * @var array
     */
    private $dados;

    /**
     * DadosArquivosPedidos constructor.
     * @param array $dados
     */
    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    /**
     * @throws \Exception
     */
    public function validar()
    {
        if (!isset($this->dados['CodigoLis']) || empty($this->dados['CodigoLis'])) {
            throw new \Exception('CodigoLis não informado.');
        }

        if (!isset($this->dados['Nome']) || empty($this->dados['Nome'])) {
            throw new \Exception('Nome não informado.');
        }

        if (!isset($this->dados['Sexo']) || empty($this->dados['Sexo'])) {
            throw new \Exception('Sexo não informado.');
        }

        if (!isset($this->dados['CodPac']) || empty($this->dados['CodPac'])) {
            throw new \Exception('CodPac não informado.');
        }

        if (!isset($this->dados['Nascimento']) || empty($this->dados['Nascimento'])) {
            throw new \Exception('Nascimento não informado.');
        }

        if (!isset($this->dados['Idade']) || empty($this->dados['Idade'])) {
            throw new \Exception('Idade não informado.');
        }

        if (!isset($this->dados['Medico']) || empty($this->dados['Medico'])) {
            throw new \Exception('Medico não informado.');
        }

        if (!isset($this->dados['Data']) || empty($this->dados['Data'])) {
            throw new \Exception('Data não informado.');
        }

        if (!isset($this->dados['Hora']) || empty($this->dados['Hora'])) {
            throw new \Exception('Hora não informado.');
        }

        if (!isset($this->dados['Unidade']) || empty($this->dados['Unidade'])) {
            throw new \Exception('Unidade não informado.');
        }

        if (!isset($this->dados['OrigemMaterial']) || empty($this->dados['OrigemMaterial'])) {
            throw new \Exception('OrigemMaterial não informado.');
        }

        if (!isset($this->dados['DadosClinicos'])) {
            throw new \Exception('DadosClinicos não informado.');
        }

        if (!isset($this->dados['Exames']) || empty($this->dados['Exames'])) {
            throw new \Exception('Exames não informado.');
        }
    }
}
