<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use \DateTime;
use \DBException;
use \db_utils;
use \cl_arquivoautoatendimento;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Filtro;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter\HeaderConverter;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Layout\Header as LayoutHeader;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Header as HeaderEntity;
use ECidade\Tributario\Arrecadacao\Repository\Convenio as ConvenioRepository;

final class HeaderService extends Service
{
    private $dataBase;
    private $codigoArquivoautoatendimento;

    public function __construct(DataBase $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function execute(Filtro $filtro)
    {
        $layoutHeader    = new LayoutHeader();
        $headerConverter = new HeaderConverter($layoutHeader);
        $header          = new HeaderEntity();
        $convenio        = ConvenioRepository::getInstanciaPorCodigo($filtro->getCodigoConvenio());

        $dataGeracao  = new DateTime();
        $tipo         = $filtro->getProducao();
        if($tipo == null) {
            $tipo = 'T';
        }
        $ano          = $dataGeracao->format('Y');
        $numero       = $this->getNumero($tipo, $ano);

        $header->setConvenio($convenio);
        $header->setDataGeracao($dataGeracao);
        $header->setTipoRegistro($tipo);
        $header->setAnoRemessa($ano);
        $header->setNumero($numero);
        $header->setDataInicioVigencia($filtro->getDataVigenciaInicial());
        $header->setDataFimVigencia($filtro->getDataVigenciaFinal());

        $dao = $this->persist($ano, $numero, $tipo, $dataGeracao, $filtro);
        $this->setCodigoArquivoautoatendimento($dao->k182_codigo);

        return $headerConverter->build($header);
    }

    public function getNumero($tipo, $ano)
    {
        $where = array(
             "k182_tipo = '{$tipo}'"
            ,"k182_ano  = {$ano}"
        );

        $daoArquivoautoatendimento = new cl_arquivoautoatendimento();
        $sql = $daoArquivoautoatendimento->sql_query_file(
             null
            ,"k182_ano, k182_numero"
            ,'k182_ano desc, k182_numero desc --limit 1'
            ,implode(' AND ', $where)
        );
        $rs = $this->dataBase->execute($sql);

        if(!$rs) {
            throw new DBException("Ocorreu um erro ao consultar a base de dados.\n". pg_last_error());
        }

        if(pg_num_rows($rs) == 0) {
            return 1;
        }

        return (db_utils::fieldsMemory($rs, 0)->k182_numero + 1);
    }

    public function persist($ano, $numero, $tipo, $dataGeracao, $filtro)
    {
        $daoArquivoautoatendimento = new cl_arquivoautoatendimento();
        $daoArquivoautoatendimento->k182_ano                 = $ano;
        $daoArquivoautoatendimento->k182_numero              = $numero;
        $daoArquivoautoatendimento->k182_tipo                = $tipo;
        $daoArquivoautoatendimento->k182_dataemissao         = $dataGeracao->format('Y-m-d');
        $daoArquivoautoatendimento->k182_datavigenciainicial = $filtro->getDataVigenciaInicial()->format('Y-m-d');
        $daoArquivoautoatendimento->k182_datavigenciafinal   = $filtro->getDataVigenciaFinal()->format('Y-m-d');

        if(!$daoArquivoautoatendimento->incluir(null)) {
            throw new DBException($daoArquivoautoatendimento->erro_msg);
        }

        return $daoArquivoautoatendimento;
    }

    public function setCodigoArquivoautoatendimento($codigoArquivoautoatendimento)
    {
        $this->codigoArquivoautoatendimento = $codigoArquivoautoatendimento;
    }

    public function getCodigoArquivoautoatendimento()
    {
        return $this->codigoArquivoautoatendimento;
    }
}
