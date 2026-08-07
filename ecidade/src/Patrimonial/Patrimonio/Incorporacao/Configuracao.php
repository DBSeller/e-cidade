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
namespace ECidade\Patrimonial\Patrimonio\Incorporacao;

class Configuracao
{
    const ARQUIVO = 'config/patrimonio/incorporacao_bem.ini';
    const INDEX_UTILIZA_INCORPORACAO = 'utiliza_incorporacao';
    const INDEX_DATA_IMPLANTACAO = 'data_implantacao';

    private $dadosArquivo = array();

    public function __construct()
    {
        if (!is_file(self::ARQUIVO)) {
            throw new Exception("Arquivo de configuração não encontrado.");
        }

        if (!is_writable(self::ARQUIVO)) {
            throw new \Exception("Arquivo " . self::ARQUIVO . " esta sem permissão de escrita.\nContate o suporte.");
        }

        $this->dadosArquivo = parse_ini_file(self::ARQUIVO, true);
    }

    public function utilizaIncorporacao()
    {
        return $this->dadosArquivo[self::INDEX_UTILIZA_INCORPORACAO] == 1;
    }

    public function dataImplantacao()
    {
        return $this->dadosArquivo[self::INDEX_DATA_IMPLANTACAO];
    }

    public function implantar()
    {
        $dao = new \cl_db_itensmenu();
        $dao->id_item = 10536;
        $dao->libcliente = 't';
        $dao->alterar(10536);
        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao liberar menu de Incorporação de Bens.");
        }

        $dao->id_item = 10540;
        $dao->libcliente = 't';
        $dao->alterar(10540);
        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao liberar menu de Incorporação de Bens.");
        }

        $content = self::INDEX_UTILIZA_INCORPORACAO."=true\n";
        $content .= self::INDEX_DATA_IMPLANTACAO."=".date('Y-m-d');

        return (boolean) file_put_contents(self::ARQUIVO, $content);
    }
}