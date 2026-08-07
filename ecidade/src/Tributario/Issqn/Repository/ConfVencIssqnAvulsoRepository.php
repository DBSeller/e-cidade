<?php
namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\ConfVencIssqnAvulso;

class ConfVencIssqnAvulsoRepository
{
    private $daoIssqnAvulso;

    public function __construct()
    {
        $this->daoIssqnAvulso = new \cl_confvencissqnavulso();
    }

    public function getByAnousu($iAnousu)
    {

        $sWhere = "j178_anousu = {$iAnousu}";

        $rRecord = db_query($this->daoIssqnAvulso->sql_query_file(null, "*", null, $sWhere));

        if (!$rRecord) {
            throw new \Exception("Erro ao buscar os parametros da nota avulsa. ".pg_last_error());
        }

        return \db_utils::fieldsMemory($rRecord, 0);
    }

    public function incluir(ConfVencIssqnAvulso $confVencIssqnAvulso)
    {

        $this->daoIssqnAvulso->j178_receita = $confVencIssqnAvulso->getIReceita();
        $this->daoIssqnAvulso->j178_histdebito = $confVencIssqnAvulso->getIHistDebito();
        $this->daoIssqnAvulso->j178_tipodebito = $confVencIssqnAvulso->getITipoDebito();
        $this->daoIssqnAvulso->j178_anousu = $confVencIssqnAvulso->getIAnousu();
        $this->daoIssqnAvulso->j178_diavenc = $confVencIssqnAvulso->getIDiaVenc();

        $this->daoIssqnAvulso->incluir();

        if ($this->daoIssqnAvulso->erro_status == "0") {
            throw new \Exception($this->daoIssqnAvulso->erro_msg);
        }
    }

    public function atualizar(ConfVencIssqnAvulso $confVencIssqnAvulso)
    {
        $this->daoIssqnAvulso->j178_receita = $confVencIssqnAvulso->getIReceita();
        $this->daoIssqnAvulso->j178_histdebito = $confVencIssqnAvulso->getIHistDebito();
        $this->daoIssqnAvulso->j178_tipodebito = $confVencIssqnAvulso->getITipoDebito();
        $this->daoIssqnAvulso->j178_anousu = $confVencIssqnAvulso->getIAnousu();
        $this->daoIssqnAvulso->j178_diavenc = $confVencIssqnAvulso->getIDiaVenc();

        $this->daoIssqnAvulso->alterar("j178_anousu = {$this->daoIssqnAvulso->j178_anousu}");

        if ($this->daoIssqnAvulso->erro_status == "0") {
            throw new \Exception($this->daoIssqnAvulso->erro_msg);
        }
    }
}
