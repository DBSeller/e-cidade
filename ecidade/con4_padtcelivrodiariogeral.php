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

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories\LivroDiarioGeralFactory;

class tce_4111
{
    /**
     * @var stdClass
     */
    private $cabecalho;
    /**
     * @var string
     */
    private $header;

    /**
     * Método construtor
     * @param string $linhaCabecalho
     */
    public function __construct($linhaCabecalho)
    {

        $this->header = $linhaCabecalho;
        $this->cabecalho = new stdClass();
        $this->cabecalho->cnpjsetorgoverno = substr($linhaCabecalho, 0, 14);
        $this->cabecalho->datainicialinformacao = substr($linhaCabecalho, 14, 8);
        $this->cabecalho->datafinalinformacao = substr($linhaCabecalho, 22, 8);
        $this->cabecalho->datageracaoarquivo = substr($linhaCabecalho, 30, 8);
        $this->cabecalho->nomesetorgoverno = substr($linhaCabecalho, 38);

    }

    /**
     * Gera o arquivo txt
     * @param int $codigosInstituicoes
     * @param $dataInicio
     * @param $dataFim
     * @param null $instituicaoTributaria
     * @param null $subelemento
     * @return bool
     * @throws Exception
     */
    public function processa(
        $codigosInstituicoes = 1,
        $dataInicio,
        $dataFim,
        $instituicaoTributaria = null,
        $subelemento = null
    ) {
        $anousu = db_getsession("DB_anousu");

        if ($anousu < 2022) {
            $tceLivroDiarioGeral = new tceLivroDiarioGeral ($dataInicio, $dataFim, $codigosInstituicoes, $this->cabecalho);
            $tceLivroDiarioGeral->geraArquivo();
        }

        $instituicoes = explode(',', $codigosInstituicoes);
        $service = LivroDiarioGeralFactory::getService($anousu, $dataInicio, $dataFim, $instituicoes);

        $service->setHeader($this->header);
        $service->processa();


        return true;
    }
}
