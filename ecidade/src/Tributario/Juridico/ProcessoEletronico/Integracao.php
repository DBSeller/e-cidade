<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/06/18
 * Time: 09:56
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico;


use db_utils;

/**
 * Class Integracao
 * @package ECidade\Tributario\Juridico\ProcessoEletronico
 */
class Integracao
{
    /**
     * @var  SITUACAO_PROCESSADO integer
     */
    const SITUACAO_PROCESSADO = 1;

    /**
     * @var SITUACAO_ASSINADO integer
     */
    const SITUACAO_ASSINADO = 2;

    /**
     * @var SITUACAO_ENVIADO integer
     */
    const SITUACAO_ENVIADO = 3;

    /**
     * @var SITUACAO_RETORNO_ERRO integer
     */
    const SITUACAO_RETORNO_ERRO = 4;

    /**
     * @var SITUACAO_COM_RECIBO integer
     */
    const SITUACAO_COM_RECIBO = 5;

    /**
     * Codigo da lista
     * @var integer
     */
    private $lista;

    /**
     * @var Configuracao;
     */
    private $configuracao;

    /**
     * @var integer
     */
    private $matricula;

    /**
     * @var integer.
     */
    private $inscricao;

    /**
     * @var integer
     */
    private $cgm;


    /**
     * Auto de infração
     * @var int
     */
    private $auto;

    /**
     * @var Tipo da Lista
     */
    private $tipoFiltro;


    /**
     * Integracao constructor.
     * @param $lista
     * @param Configuracao $configuracao
     */
    public function __construct($lista, Configuracao $configuracao)
    {
        $this->lista = $lista;
        $this->configuracao = $configuracao;
    }


    /**
     * Retorna as iniciais que devem ser enviadas
     * @return \stdClass[]
     * @throws \Exception
     */
    public function getIniciaisParaEnvio(array $situacao = null, array $processos = null)
    {

        $dadosLista = "select k60_tipo from lista where k60_codigo = {$this->lista}";
        $rsLista = db_query($dadosLista);
        if (pg_num_rows($rsLista) == 0) {
            throw new \BusinessException("Lista {$this->lista} não encontrada no sistema.");
        }
        $this->tipoFiltro = db_utils::fieldsMemory($rsLista, 0)->k60_tipo;
        $where = array(" k61_codigo = {$this->lista}");
        if (!empty($situacao)) {
            $where[] = "v38_situacao in(".implode(",", $situacao).")";
        }
        if (!empty($processos)) {
            $where[] = "v38_sequencial in(".implode(",", $processos).")";
        }
        $origem = "z01_numcgm";
        $dadosFiltro = $this->getDadosParaConsultaAgrupado();

        if (!empty($dadosFiltro->campo)) {
            $origem = $dadosFiltro->campo;
        }
        if (!empty($dadosFiltro->where)) {
            $where[] = $dadosFiltro->where;
        }


        $sqlDados = "select ";
        $sqlDados .= "       distinct v38_sequencial as codigo_processo_eletronico,";
        $sqlDados .= "       inicial.v50_inicial as inicial,                                                               ";
        $sqlDados .= "       inicial.v50_data as emissao,                                                                  ";
        $sqlDados .= "       extract(year from v50_data) as ano_inicial,                                                 ";
        $sqlDados .= "       extract(year from v50_data) as ano_final ,                                                  ";
        $sqlDados .= "       v50_data as dtufir,                                                                  ";
        $sqlDados .= "       inicial.v50_codlocal     as local,                                       ";
        $sqlDados .= "       v38_parte as parte,                                                              ";
        $sqlDados .= "       k60_tipo as tipo ,                                                             ";
        $sqlDados .= "       {$origem} as origem, ";
        $sqlDados .= "       z01_nome as nome,                                                             ";
        $sqlDados .= "       v38_situacao as situacao,                                                      ";
        $sqlDados .= "       v38_datacalculo as data_calculo, ";
        $sqlDados .= "       array_to_string(array_accum(distinct k61_numpre), ', ')  as debitos, ";
        $sqlDados .= "       array_to_string(array_accum(distinct v13_certid), ', ')  as certidoes, ";
        $sqlDados .= "       v42_assunto as assunto , v42_codigo_assunto_local as codigo_assunto, 
        v42_codigo_pai_nacional  as codigo_assunto_pai_nacional , v42_codigo_pai_local as  codigo_assunto_pai_local ";

        $sqlDados .= "  from integracaoprocessoeletronico                                                       ";
        $sqlDados .= "       inner join inicial        on inicial.v50_inicial  = v38_inicial         ";
        $sqlDados .= "                                and inicial.v50_situacao = 1                           ";
        $sqlDados .= "       inner join juridico.inicialnumpre on v59_inicial  =  inicial.v50_inicial ";
        $sqlDados .= "       inner join listadeb               on v59_numpre   = k61_numpre";
        $sqlDados .= "       inner join lista                  on k60_codigo   = k61_codigo";

        if (!empty($dadosFiltro->join)) {
            $sqlDados .= $dadosFiltro->join;
        }
        $sqlDados .= "       inner join inicialcert    on inicial.v50_inicial     = inicialcert.v51_inicial     ";
        $sqlDados .= "       inner join certid         on inicialcert.v51_certidao = certid.v13_certid           ";
        $sqlDados .= "       inner join certdiv        on v14_certid = certid.v13_certid  ";
        $sqlDados .= "       inner join divida         on v01_coddiv = v14_coddiv ";

        $sqlDados .= "       inner join proced         on v03_codigo = v01_proced ";

        $sqlDados .= "       left  join procedassuntovinculo     on v43_proced = v03_codigo ";
        $sqlDados .= "       left  join procedassunto            on v42_sequencial = v43_procedassunto ";

        $sqlDados .= "       inner join cgm            on  v38_parte = z01_numcgm         ";
        $sqlDados .= " where ".implode(" and ", $where);
        $sqlDados .= " group by v38_sequencial, ";
        $sqlDados .= "        inicial.v50_inicial,";
        $sqlDados .= "        inicial.v50_data, ";
        $sqlDados .= "        v38_parte, ";
        $sqlDados .= "        inicial.v50_codlocal,";
        $sqlDados .= "        k60_tipo,";
        $sqlDados .= "        {$origem},";
        $sqlDados .= "        z01_nome,";
        $sqlDados .= "        v38_datacalculo,";
        $sqlDados .= "        v38_situacao , v42_assunto , v42_codigo_assunto_local , v42_codigo_pai_nacional , v42_codigo_pai_local ";
        $sqlDados .= "    order by inicial.v50_inicial";

    
        $rsDados  = db_query($sqlDados);

        $iniciais = db_utils::getColectionByRecord($rsDados);

        if (count($iniciais) == 0) {
            throw new \Exception(_M('tributario.juridico.jur4_certidarqremessa.nao_existe_cda'));
        }
        return $iniciais;
    }

    /**
     * Verifica se existe alguma inicial da lista que está sem processoeletronico
     */
    public function temIniciaisDaListaSemProcessoEletronico()
    {
        $sqlIniciais = "select  count(distinct v59_inicial) as total
                   from lista
                        inner join listadeb on k61_codigo = k60_codigo
                        inner join juridico.inicialnumpre on v59_numpre = k61_numpre
                        inner join inicial   on inicial.v50_inicial  = v59_inicial
                        left join  integracaoprocessoeletronico on inicial.v50_inicial = v38_inicial
                 where  k61_codigo = {$this->lista} 
                   and v38_sequencial is null
                   /*and not exists ( select 1 
                                     from inicialcert                                                  
                                          left outer join certdiv on certdiv.v14_certid = certid.v13_certid
                                          left outer join certter on certter.v14_certid = certid.v13_certid                                                  
                                    where v59_inicial = v51_inicial 
                                      and certter.v14_certid is null 
                                      and certdiv.v14_certid is null )
                                                           */
                   
                   
                   ";

        $rsIniciais = db_query($sqlIniciais);
        if (!$rsIniciais) {
            throw new \Exception('Não foi possível verificar iniciais sem processamento.');
        }
        return db_utils::fieldsMemory($rsIniciais, 0)->total > 0;
    }

    /**
     * @return int
     */
    public function getLista()
    {
        return $this->lista;
    }

    /**
     * @return string
     */
    public function getTipoLista()
    {
        return $this->tipoFiltro;
    }

    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return int
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * @param int $inscricao
     */
    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param int $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return int
     */
    public function getAutoDeInfracao()
    {
        return $this->auto;
    }

    /**
     * @param int $auto
     */
    public function setAutoDeInfracao($auto)
    {
        $this->auto = $auto;
    }

    private function getDadosParaConsultaAgrupado()
    {
        $dados = new \stdClass();
        $dados->campo = '';
        $dados->filtro = '';
        $dados->join = '';
        $dados->where = '';

        switch ($this->tipoFiltro) {

            case 'N':
            case 'C':

                $dados->campo = ' arrenumcgm.k00_numcgm';
                $dados->join = ' inner join arrenumcgm on k61_numpre = arrenumcgm.k00_numpre';
                if (!empty($this->cgm)) {
                    $dados->where = 'arrenumcgm.k00_numcgm = ' . $this->cgm;
                }
                break;


            case 'M':

                $dados->campo = 'arrematric.k00_matric';
                $dados->join = ' inner join arrematric on k61_numpre = arrematric.k00_numpre';
                if (!empty($this->matricula)) {
                    $dados->where = 'arrematric.k00_matric = ' . $this->matricula;
                }
                break;

            case 'I':
                $dados->campo = 'arreinscr.k00_inscr';
                $dados->join = ' inner join arreinscr on k61_numpre = arreinscr.k00_numpre';
                if (!empty($this->inscricao)) {
                    $dados->where = 'arreinscr.k00_inscr = ' . $this->inscricao;
                }
                break;
            case 'A':

                $dados->campo = 'k00_auto';
                //$dados->join  = ' inner join arreinscr on k61_numpre = arreinscr.k00_numpre';
                $dados->join .= ' inner join arreauto on k61_numpre = arreauto.k00_numpre';
                if (!empty($this->auto)) {
                    $dados->where = 'k00_auto = ' . $this->auto;
                }
                break;
        }
        return $dados;
    }

}
