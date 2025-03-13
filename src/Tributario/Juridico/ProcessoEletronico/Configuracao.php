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

namespace ECidade\Tributario\Juridico\ProcessoEletronico;

/**
 * Configurações da integração com o processo eletronico
 * Class Configuracao
 * @package ECidade\Tributario\Juridico\ProcessoEletronico
 */
class Configuracao
{

    /**
     * @var string
     */
    protected $senha;

    /**
     * @var string
     */
    protected $codigo;

    /**
     * @var string
     */
    protected $usuario;

    /**
     * @var string
     */
    protected $localidade;

    /**
     * @var int
     */
    protected $urlAmbiente;

    /**
     * @var string
     */
    const URL_PRODUCAO    = 'https://webserverseguro.tjrj.jus.br/MNI/Servico.svc?wsdl';

    const URL_HOMOLOGACAO = 'http://wwwh1.tjrj.jus.br/HMNI/Servico.svc?wsdl';

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getLocalidade()
    {
        return $this->localidade;
    }

    /**
     * @param mixed $localidade
     */
    public function setLocalidade($localidade)
    {
        $this->localidade = $localidade;
    }


    /**
     * @return mixed
     */
    public function getUrlParaRequisicao()
    {
        return ($this->urlAmbiente == 2 ? self::URL_HOMOLOGACAO : self::URL_PRODUCAO);
    }
    
    public function getUrlAmbiente(){
        return $this->urlAmbiente;

    }
    
    /**
     * @param mixed $urlAmbiente
     */
    public function setUrlAmbiente($urlAmbiente)
    {
        $this->urlAmbiente = $urlAmbiente;
    }


}
