<?php

use Classes\PostgresMigration;

class M17094AjusteConplanoAtributoSaldo extends PostgresMigration
{
    public function change()
    {
        $this->criaBkpTabela();

        $sql =  "
        SELECT c125_sequencial,
               c125_hashcontaatributos
        FROM contabilidade.conplanoatributosaldo
        WHERE c125_anousu <2020
          AND c125_tiposaldo = 2
          and c125_hashcontaatributos like '%#FR%'
          AND c125_conplanosistema = 1";

        $stmt = $this->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbh = $this->getAdapter()->getConnection();
        $sthRecurso = $dbh->prepare("select * from orctiporec where o15_codigo = :codigo");
        $sthFonteRecurso = $dbh->prepare("select * from orctiporec where o15_recurso = :codigo");

        foreach ($dados as $dado) {
            $hashAtributos = explode('|', $dado->c125_hashcontaatributos);

            $hashAtributos = array_map(function ($atributo) use ($sthRecurso, $sthFonteRecurso) {
                if (strpos($atributo, '#FR')) {
                    $dados = explode('#', $atributo);
                    $codigo = $dados[0];

                    $sthRecurso->execute([':codigo' => $codigo]);
                    $recurso = $sthRecurso->fetch(PDO::FETCH_OBJ);

                    if (!$recurso) {
                        $sthFonteRecurso->execute([':codigo' => $codigo]);
                        $recurso = $sthFonteRecurso->fetch(PDO::FETCH_OBJ);
                    }

                    $hashRecurso = "{$codigo}#$dados[1]";
                    $novoHashRecurso = "{$recurso->o15_recurso}#$dados[1]";
                    $atributo = str_replace($hashRecurso, $novoHashRecurso, $atributo);
                }
                return $atributo;
            }, $hashAtributos);

            $novoHash = implode('|', $hashAtributos);

            if ($dado->c125_hashcontaatributos != $novoHash) {
                echo sprintf("Alterando hash %s para %s\n", $dado->c125_hashcontaatributos, $novoHash);
                $this->aleraHash($dado->c125_sequencial, $novoHash);
            }
        }
    }

    /**
     * ajusta lançamento do complemento
     * @param $id
     * @param $hash
     */
    private function aleraHash($id, $hash)
    {
        $this->execute("
            update contabilidade.conplanoatributosaldo
                  set c125_hashcontaatributos = '{$hash}'
            where c125_sequencial = {$id}"
        );
    }

    private function criaBkpTabela()
    {
        $this->execute("drop table if exists bkp_conplanoatributosaldo_20202112;");
        $this->execute("
        create table bkp_conplanoatributosaldo_20202112 as SELECT * FROM contabilidade.conplanoatributosaldo;
        ");
    }
}
