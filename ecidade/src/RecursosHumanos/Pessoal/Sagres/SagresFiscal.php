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

namespace ECidade\RecursosHumanos\Pessoal\Sagres;

use DBDepartamento;
use Periodo;

/**
 * Class SagresFiscal
 * @package ECidade\Financeiro\Orcamento\Sagres
 */
class SagresFiscal
{
    /**
     * @var
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
     * SagresFiscal constructor.
     * @param object $params
     * @param DBDepartamento $departamento
     * @param array $instituicoes
     * @param integer $ano
     * @param $codigoTCE
     */
    public function __construct(
        $params,
        DBDepartamento $departamento,
        array $instituicoes,
        $ano,
        $codigoTCE
    ) {
        $this->params = $params;
        $this->departamento = $departamento;
        $this->ano = $ano;
        $this->codigoInstituicoes = $instituicoes;
        $this->codigoTCE = $codigoTCE;
    }

    public function processarArquivos($arquivos, $oParam)
    {
        $arquivoFactory = AnoFactory::get($this->ano);

        foreach ($arquivos as $arquivo) {
            $arquivo = $arquivoFactory->get(
                $arquivo,
                $this->params,
                $this->codigoInstituicoes,
                $this->codigoTCE
            );

            $files = $arquivo->emitirArquivos($oParam->formatos);
            $this->arquivos = array_merge($files, $this->arquivos);
        }
    }

    /**
     * Comprime os arquivos e retorna o path do arquivo comprimido
     * @return string
     */
    public function comprimir($param)
    {

        $pathZip = "tmp/Sagres{$this->codigoTCE}.zip";
        if (file_exists($pathZip)) {
            unlink($pathZip);
        }

        $zip = new \ZipArchive();
        if ($zip->open($pathZip, \ZipArchive::CREATE) === true) {
            foreach ($this->arquivos as $key => $path) {
                $zip->addFile($path, "{$param->folder}/{$key}");
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
            $aNomeArquivo = explode('.', $key);
            if (strlen($aNomeArquivo[0]) > 45) {
                $key = substr($aNomeArquivo[0], 0, 45).'.'.$aNomeArquivo[1];
            }

            $arquivos[] = [
                'filePath' => $path,
                'fileName' => "{$key}",
            ];
        }

        return $arquivos;
    }
}
