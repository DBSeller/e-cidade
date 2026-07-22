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

namespace ECidade\Financeiro\Contabilidade\Sigap;

use DBDepartamento;
use Periodo;

/**
 * Class SigapFiscal
 * @package ECidade\Financeiro\Orcamento\Sigap
 */
class SigapFiscal
{
    /**
     * @var Periodo
     */
    protected $periodo;

    /**
     * @var
     */
    protected $ano;

    /**
     * @var array
     */
    protected $codigoInstituicoes = [];

    /**
     * @var integer
     */
    protected $codigoTCE;

    /**
     * Arquivos gerados pelo sistema
     * @var array
     */
    protected $arquivos = [];

    /**
     * @var DBDepartamento
     */
    private $departamento;

    /**
     * SigapFiscal constructor.
     * @param Periodo $periodo
     * @param DBDepartamento $departamento
     * @param array $instituicoes
     * @param integer $ano
     * @param $codigoTCE
     */
    public function __construct(Periodo $periodo, DBDepartamento $departamento, array $instituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->departamento = $departamento;
        $this->ano = $ano;
        $this->codigoInstituicoes = $instituicoes;
        $this->codigoTCE = $codigoTCE;
    }

    public function processarArquivos($arquivos)
    {
        $arquivoFactory = AnoFactory::get($this->ano);

        foreach ($arquivos as $arquivo) {
            $arquivo = $arquivoFactory->get(
                $arquivo,
                $this->periodo,
                $this->codigoInstituicoes,
                $this->codigoTCE
            );

            $this->arquivos[$arquivo::TAG] = $arquivo->emitirXML();
        }

        $this->emitirNotasExplicativas($arquivoFactory);
        $this->emitirArquivoFonte($arquivoFactory);
        $this->emitirArquivoPublicidade($arquivoFactory);

        if (in_array($this->periodo->getCodigo(), [7, 9, 11])) {
            $this->emitirOutrosArquivos($arquivoFactory);
        }
    }

    /**
     * Comprime os arquivos e retora o path do arquivo comprimido
     * @return string
     */
    public function comprimir()
    {
        $pathZip = "tmp/SIGAP{$this->codigoTCE}.zip";
        if (file_exists($pathZip)) {
            unlink($pathZip);
        }

        $zip = new \ZipArchive();
        if ($zip->open($pathZip, \ZipArchive::CREATE) === true) {
            foreach ($this->arquivos as $key => $path) {
                $zip->addFile($path, "{$key}.xml");
            }
        }

        $zip->close();
        return $pathZip;
    }

    /**
     * Array com os arquivos emitidos
     * @return array
     */
    public function getArquivosEmitidos()
    {
        $arquivos = [];
        foreach ($this->arquivos as $key => $path) {
            $arquivos[] = [
                'filePath' => $path,
                'fileName' => "{$key}.xml",
            ];
        }

        return $arquivos;
    }

    /**
     * @param V2020\ArquivosFactory $arquivoFactory
     */
    private function emitirNotasExplicativas(V2020\ArquivosFactory $arquivoFactory)
    {
        $notaExplicativa = $arquivoFactory->getNotasExplicativas(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$notaExplicativa::TAG] = $notaExplicativa->emitirXML();
    }

    /**
     * @param V2020\ArquivosFactory $arquivoFactory
     */
    private function emitirArquivoFonte(V2020\ArquivosFactory $arquivoFactory)
    {
        $arquivodeFonte = $arquivoFactory->getArquivodeFonte(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );
        $arquivodeFonte->setDepartamento($this->departamento);
        $this->arquivos[$arquivodeFonte::TAG] = $arquivodeFonte->emitirXML();
    }

    private function emitirArquivoPublicidade(V2020\ArquivosFactory $arquivoFactory)
    {
        $arquivodeFonte = $arquivoFactory->getArquivodePublicidade(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );
        $this->arquivos[$arquivodeFonte::TAG] = $arquivodeFonte->emitirXML();
    }

    private function emitirOutrosArquivos(V2020\ArquivosFactory $arquivoFactory)
    {
        $retornoAoLimiteDivida = $arquivoFactory->getArquivoRetornoAoLimiteDivida(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$retornoAoLimiteDivida::TAG] = $retornoAoLimiteDivida->emitirXML();

        $acompanhamentoRetornoAoLimiteDivida = $arquivoFactory->getArquivoAcompanhamentoRetornoLimiteDivida(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$acompanhamentoRetornoAoLimiteDivida::TAG] = $acompanhamentoRetornoAoLimiteDivida->emitirXML();

        $arquivo = $arquivoFactory->getArquivoRetornoAoLimitePessoal(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$arquivo::TAG] = $arquivo->emitirXML();

        $arquivo = $arquivoFactory->getArquivoAcompRetornoLimitePessoal(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$arquivo::TAG] = $arquivo->emitirXML();

        $arquivo = $arquivoFactory->getArquivoAcompRetornoLimitePessoalExtendido(
            $this->periodo,
            $this->codigoInstituicoes,
            $this->codigoTCE
        );

        $this->arquivos[$arquivo::TAG] = $arquivo->emitirXML();
    }
}
