<?php

namespace ECidade\Financeiro\Contabilidade\Importacao\Siconfi;

use ECidade\Tributario\Configuracao\Entity\Repository\InstituicaoRepository;
use RecursoRepository;

/**
 * Clase para a exportação da Matriz de saldo contábeis para o SICONFI
 * @author Augusto  Oliveira <augusto.oliveira@dbseller.com.br>
 * @package ECidade\Financeiro\Contabilidade\Importacao\Siconfi
 *
 */
class TipoRecursos
{

    /**
     * @var array
     */
    private $erros = [];

    /**
     * @var integer
     */
    private $anoImportacao;

    /**
     * @var integer
     */
    private $anoServidor;

    /**
     *
     * Realiza importação do DEPARA tipo recurso siconfi
     *
     * @param $file
     */
    public function import($file)
    {
        $handle = fopen($file, 'r');

        // Ignora a primeira linha
        fgetcsv($handle, 0, ",");
        $arquivoPorAno = array();
        while (($data = fgetcsv($handle, 0, ",")) !== false) {
            $object = $this->parser($data);

            $repository = RecursoRepository::getInstance();

            $repository->resetScopes();
            $recursos = $repository->scopeFonteRecurso($object->recurso)->get();

            foreach ($recursos as $recurso) {
                $arquivoPorAno[] = implode(
                    '#',
                    [$recurso->getCodigo(), $recurso->getDescricao(), $object->codigosiconfi]
                );

                if ($this->anoServidor === $this->anoImportacao) {
                    $this->persist($recurso, $object);
                }
            }
        }

        $str = "config/financeiro/siconfi/recursos/recurso_{$this->anoImportacao}.csv";
        file_put_contents($str, implode("\n", $arquivoPorAno));
        fclose($handle);
    }

    /**
     * Retorna os códigos ou o código desejado de um recurso para o ano
     * @param $ano
     * @param $codigoRecurso
     * @return array|string|false
     */
    public static function getCodigoParaAno($ano, $codigoRecurso = null)
    {
        $arquivo = file_get_contents("config/financeiro/siconfi/recursos/recurso_{$ano}.csv");
        $retorno = array();
        foreach ($arquivo as $linha) {
            $dadosLinha = explode('#', $linha);
            $retorno[$dadosLinha[0]] = $dadosLinha[2];
        }

        if (!empty($codigoRecurso)) {
            if (!empty($retorno[$codigoRecurso])) {
                return $retorno[$codigoRecurso];
            }
            return false;
        }
        return $retorno;
    }


    /**
     *
     * @param array $data
     * @return \stdClass
     */
    public function parser(array $data)
    {
        $object = new \stdClass();

        $pad = \InstituicaoRepository::usaFonteRecursoUniao() ? 5 : 4;
        $object->recurso = str_pad($data[0], $pad, '0', STR_PAD_LEFT);
        $object->descricao = $data[1];
        $object->codigosiconfi = $data[2];

        return $object;
    }

    /**
     * @param \Recurso $recurso
     * @param \stdClass $object
     * @return bool
     */
    public function persist($recurso, $object)
    {
        try {
            $recurso->setCodigoSiconfi($object->codigosiconfi);
            $recurso->salvar();
            return true;
        } catch (\Exception $e) {
            $this->erros[$object->recurso] = $object->recurso ;
            return false;
        }
    }

    /**
     * @return array
     */
    public function getErros()
    {
        return $this->erros;
    }

    /**
     * @param integer $anoImportacao
     */
    public function setAnoImportacao($anoImportacao)
    {
        $this->anoImportacao = (int)$anoImportacao;
    }

    /**
     * @param integer $anoServidor
     */
    public function setAnoServidor($anoServidor)
    {
        $this->anoServidor = (int)$anoServidor;
    }
}
