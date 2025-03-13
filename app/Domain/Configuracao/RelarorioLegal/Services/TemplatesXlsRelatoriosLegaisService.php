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

namespace App\Domain\Configuracao\RelarorioLegal\Services;

use App\Domain\Configuracao\RelarorioLegal\Model\Periodo;
use App\Domain\Configuracao\RelarorioLegal\Model\Relatorio;
use App\Domain\Configuracao\RelarorioLegal\Model\Template;
use Illuminate\Filesystem\Filesystem;
use JSON;

class TemplatesXlsRelatoriosLegaisService
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * deleta o arquivo fisicamente do sistema
     * @param $file
     */
    public function deleteFile($file)
    {
        $this->filesystem->delete(base_path($file));
    }

    /**
     * move o arquivo do tmp para a pasta de template
     * @param $stringJsonFile
     * @return string
     */
    public function uploadTemplate($stringJsonFile)
    {
        $file = JSON::create()->parse($stringJsonFile);
        $tmpFile = base_path($file->path);

        //deixei fixo o path por enquanto
        $pathTemplates = 'app' . DIRECTORY_SEPARATOR . 'lrf'. DIRECTORY_SEPARATOR;
        $storagePathTemplate = storage_path($pathTemplates);

        if (!$this->filesystem->isDirectory($storagePathTemplate)) {
            $this->filesystem->makeDirectory($storagePathTemplate);
        }

        $physicalFile = new \Illuminate\Http\File($tmpFile);
        $storagePathTemplate .= $physicalFile->getBasename();

        $this->filesystem->move($tmpFile, $storagePathTemplate);
        $basePath = base_path() . DIRECTORY_SEPARATOR;
        return str_replace($basePath, '', $storagePathTemplate);
    }

    public function salvarTemplate(array $data)
    {
        $basePathTemplate = $this->uploadTemplate(str_replace('\"', '"', $data['file']));

        $relatorio = Relatorio::find($data['relatorio']);
        $modelo = $data['modelo'];


        $removidos = [];
        foreach ($data['periodo'] as $idPeriodo) {
            $template = Template::query()
                ->where('c138_orcparamrel', '=', $relatorio->o42_codparrel)
                ->where('c138_modelo', '=', $modelo)
                ->where('c138_periodo', '=', $idPeriodo)
                ->first();

            if (!is_null($template)) {
                $removidos[] = $template->c138_path;
                $template->c138_path = $basePathTemplate;
                $template->save();
            } else {
                $model = new Template();
                $model->relatorio()->associate($relatorio);
                $model->periodo()->associate(Periodo::find($idPeriodo));
                $model->c138_modelo = $modelo;
                $model->c138_path = $basePathTemplate;

                $model->save();
            }
        }

        foreach ($removidos as $fileTemplate) {
            $template = Template::query()
                ->where('c138_path', '=', $fileTemplate)
                ->first();
            if (is_null($template)) {
                $this->deleteFile($fileTemplate);
            }
        }
    }
}
