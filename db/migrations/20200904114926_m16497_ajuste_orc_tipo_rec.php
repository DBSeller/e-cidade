<?php

use Classes\PostgresMigration;

class M16497AjusteOrcTipoRec extends PostgresMigration
{
    private $tableOrctiporec;
    private $camposOrctiporec = [
        'o15_codigo',
        'o15_descr',
        'o15_codtri',
        'o15_finali',
        'o15_tipo',
        'o15_datalimite',
        'o15_db_estruturavalor',
        'o15_codigosiconfi',
        'o15_loaidentificadoruso',
        'o15_loatipo',
        'o15_loagrupo',
        'o15_loaespecificacao',
        'o15_complemento'
    ];

    public function up()
    {
        $this->tableOrctiporec = $this->table('orctiporec', array('schema'=>'orcamento'));
        $this->ajustaLancamentoComplemento();
        $this->removeRecursosDuplicados();
    }

    public function down() {}

    private function retornaValoresInsert($idRecurso, $dado)
    {
        $dado['o15_codigo'] = $idRecurso;
        $dado['o15_complemento'] = $dado['o206_complementorecurso'];
        unset($dado['o206_complementorecurso']);
        unset($dado['o206_sequencial']);
        return array_values($dado);
    }

    /**
     * @return mixed
     */
    public function ajustaLancamentoComplemento()
    {
        // busca todos lançamentos de complemento onde o complemento esta diferente do complemento do recurso (orctiporec)
        $stmt = $this->query("
            select orctiporec.*, o206_complementorecurso, o206_sequencial
              from origemcomplementorecurso
              join orctiporec on orctiporec.o15_codigo = o206_recurso
             where o206_complementorecurso != o15_complemento
        ");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dbh = $this->getAdapter()->getConnection();

        // query para buscar o recurso compativel com o complemento informado
        $sth = $dbh->prepare("
            select * from orctiporec
             where o15_loaespecificacao = :loa and o15_complemento = :compl order by o15_codigo limit 1
        ");

        foreach ($dados as $dado) {
            $sth->execute([":loa" => $dado['o15_loaespecificacao'], ':compl' => $dado['o206_complementorecurso']]);
            $recursoCerto = $sth->fetch(PDO::FETCH_ASSOC);

            if ($recursoCerto) {
                $this->aleraRecursoComplementoLancado($dado['o206_sequencial'], $recursoCerto['o15_codigo']);
            } else {
                // insere novo recurso
                $newId = $this->query('select max(o15_codigo) +1 as id from orctiporec');
                $idRecurso = $newId->fetch()['id'];
                echo sprintf(
                    "inserindo recurso: %s - especificacao: %s - complemento: %s\n",
                    $idRecurso,
                    $dado['o15_loaespecificacao'],
                    $dado['o206_complementorecurso']
                );

                $this->tableOrctiporec->insert($this->camposOrctiporec, [
                    $this->retornaValoresInsert($idRecurso, $dado)
                ]);
                $this->tableOrctiporec->saveData();

                $this->aleraRecursoComplementoLancado($dado['o206_sequencial'], $idRecurso);
            }
        }
    }

    /**
     * ajusta lançamento do complemento
     * @param $id
     * @param $codigoRecurso
     */
    private function aleraRecursoComplementoLancado($id, $codigoRecurso)
    {
        $this->execute("
            update origemcomplementorecurso set o206_recurso = {$codigoRecurso}
             where o206_sequencial = {$id}"
        );
    }

    private function removeRecursosDuplicados()
    {
        $this->execute("
        create temp table w_orctiporec_duplicados as
        select orctiporec.o15_codigo,
                x.o15_loaespecificacao,
                x.o15_complemento
           from (
            select count(*), o15_loaespecificacao, o15_complemento
              from orctiporec
             group by 2, 3
            having count(*) > 1
          ) as x
          join orctiporec on orctiporec.o15_loaespecificacao = x.o15_loaespecificacao
                         and orctiporec.o15_complemento = x.o15_complemento
        order by 2, 3, 1;

        create temp table w_orctiporec_manter as
           select min(o15_codigo) as id, o15_loaespecificacao, o15_complemento
             from w_orctiporec_duplicados
            group by 2, 3;

        create temp table w_orctiporec_deletar as
           select o15_codigo, o15_loaespecificacao, o15_complemento
             from w_orctiporec_duplicados
            where o15_codigo not in (select id from w_orctiporec_manter);

        create temp table w_orctiporec_manutencao as
        select w_orctiporec_manter.id as manter,
               w_orctiporec_deletar.o15_codigo as deletar,
               w_orctiporec_manter.o15_loaespecificacao,
               w_orctiporec_manter.o15_complemento
         from w_orctiporec_manter
         join w_orctiporec_deletar on w_orctiporec_deletar.o15_loaespecificacao = w_orctiporec_manter.o15_loaespecificacao
              and w_orctiporec_deletar.o15_complemento = w_orctiporec_manter.o15_complemento;

        update origemcomplementorecurso set o206_recurso = manter
          from w_orctiporec_manutencao
         where w_orctiporec_manutencao.deletar = origemcomplementorecurso.o206_recurso;

        update caiparametro set k29_orctiporecfundeb = manter
        from w_orctiporec_manutencao
        where caiparametro.k29_orctiporecfundeb = w_orctiporec_manutencao.deletar;

        update placaixarec set k81_codigo = manter
        from w_orctiporec_manutencao
        where placaixarec.k81_codigo = w_orctiporec_manutencao.deletar;

        update sliprecurso set k29_recurso = manter
        from w_orctiporec_manutencao
        where sliprecurso.k29_recurso = w_orctiporec_manutencao.deletar;

        update sliprecursocontas set k181_recursocredito = manter
        from w_orctiporec_manutencao
        where sliprecursocontas.k181_recursocredito = w_orctiporec_manutencao.deletar;

        update sliprecursocontas set k181_recursodebito = manter
        from w_orctiporec_manutencao
        where sliprecursocontas.k181_recursodebito = w_orctiporec_manutencao.deletar;

        update tabplansaldorecurso set k111_recurso = manter
        from w_orctiporec_manutencao
        where tabplansaldorecurso.k111_recurso = w_orctiporec_manutencao.deletar;

        update tabplansaldorecursomov set k113_recurso = manter
        from w_orctiporec_manutencao
        where tabplansaldorecursomov.k113_recurso = w_orctiporec_manutencao.deletar;

        update conlancamrecurso set c130_orctiporec = manter
        from w_orctiporec_manutencao
        where conlancamrecurso.c130_orctiporec = w_orctiporec_manutencao.deletar;

        update conplanoexe set c62_codrec = manter
        from w_orctiporec_manutencao
        where conplanoexe.c62_codrec = w_orctiporec_manutencao.deletar;

        update conplanoexerecurso set c89_recurso = manter
        from w_orctiporec_manutencao
        where conplanoexerecurso.c89_recurso = w_orctiporec_manutencao.deletar;

        update conplanoorcamentoanalitica set c61_codigo = manter
        from w_orctiporec_manutencao
        where conplanoorcamentoanalitica.c61_codigo = w_orctiporec_manutencao.deletar;

        update conplanoreduz set c61_codigo = manter
        from w_orctiporec_manutencao
        where conplanoreduz.c61_codigo = w_orctiporec_manutencao.deletar;

        update contacorrentedetalhe set c19_orctiporec = manter
        from w_orctiporec_manutencao
        where contacorrentedetalhe.c19_orctiporec = w_orctiporec_manutencao.deletar;

        update classificacaocredoresrecurso set cc33_orctiporec = manter
        from w_orctiporec_manutencao
        where classificacaocredoresrecurso.cc33_orctiporec = w_orctiporec_manutencao.deletar;

        update empautidot set e56_orctiporec = manter
        from w_orctiporec_manutencao
        where empautidot.e56_orctiporec = w_orctiporec_manutencao.deletar;

        update empresto set e91_recurso = manter
        from w_orctiporec_manutencao
        where empresto.e91_recurso = w_orctiporec_manutencao.deletar;

        update orcdotacao set o58_codigo = manter
        from w_orctiporec_manutencao
        where orcdotacao.o58_codigo = w_orctiporec_manutencao.deletar;

        update orcdotacaocontr set o61_codigo = manter
        from w_orctiporec_manutencao
        where orcdotacaocontr.o61_codigo = w_orctiporec_manutencao.deletar;

        update orcimpactomovtiporec set o67_codigo = manter
        from w_orctiporec_manutencao
        where orcimpactomovtiporec.o67_codigo = w_orctiporec_manutencao.deletar;

        update orcimpactorecmov set o69_codigo = manter
        from w_orctiporec_manutencao
        where orcimpactorecmov.o69_codigo = w_orctiporec_manutencao.deletar;

        update orcimpactotiporec set o93_codigo = manter
        from w_orctiporec_manutencao
        where orcimpactotiporec.o93_codigo = w_orctiporec_manutencao.deletar;

        update orcparamrecurso set o44_codrec = manter
        from w_orctiporec_manutencao
        where orcparamrecurso.o44_codrec = w_orctiporec_manutencao.deletar;

        update orcparamrecursoval set o48_codrec = manter
        from w_orctiporec_manutencao
        where orcparamrecursoval.o48_codrec = w_orctiporec_manutencao.deletar;

        update orcppatiporec set o26_codigo = manter
        from w_orctiporec_manutencao
        where orcppatiporec.o26_codigo = w_orctiporec_manutencao.deletar;

        update orcprevdesp set o35_codigo = manter
        from w_orctiporec_manutencao
        where orcprevdesp.o35_codigo = w_orctiporec_manutencao.deletar;

        update orcreceita set o70_codigo = manter
        from w_orctiporec_manutencao
        where orcreceita.o70_codigo = w_orctiporec_manutencao.deletar;

        update orcreserprev set o33_codigo = manter
        from w_orctiporec_manutencao
        where orcreserprev.o33_codigo = w_orctiporec_manutencao.deletar;

        update orctiporecconvenio set o16_orctiporec = manter
        from w_orctiporec_manutencao
        where orctiporecconvenio.o16_orctiporec = w_orctiporec_manutencao.deletar;

        update origemcomplementorecurso set o206_recurso = manter
        from w_orctiporec_manutencao
        where origemcomplementorecurso.o206_recurso = w_orctiporec_manutencao.deletar;

        update rhcontasrec set rh41_codigo = manter
        from w_orctiporec_manutencao
        where rhcontasrec.rh41_codigo = w_orctiporec_manutencao.deletar;

        update rhdevolucaofolha set rh69_recurso = manter
        from w_orctiporec_manutencao
        where rhdevolucaofolha.rh69_recurso = w_orctiporec_manutencao.deletar;

        update rhempenhofolha set rh72_recurso = manter
        from w_orctiporec_manutencao
        where rhempenhofolha.rh72_recurso = w_orctiporec_manutencao.deletar;

        update rhempenhofolhaexcecaorubrica set rh74_recurso = manter
        from w_orctiporec_manutencao
        where rhempenhofolhaexcecaorubrica.rh74_recurso = w_orctiporec_manutencao.deletar;

        update rhempfolha set rh40_recurso = manter
        from w_orctiporec_manutencao
        where rhempfolha.rh40_recurso = w_orctiporec_manutencao.deletar;

        update rhlotavinc set rh25_recurso = manter
        from w_orctiporec_manutencao
        where rhlotavinc.rh25_recurso = w_orctiporec_manutencao.deletar;

        update rhlotavincrec set rh43_recurso = manter
        from w_orctiporec_manutencao
        where rhlotavincrec.rh43_recurso = w_orctiporec_manutencao.deletar;

        update rhslipfolha set rh79_recurso = manter
        from w_orctiporec_manutencao
        where rhslipfolha.rh79_recurso = w_orctiporec_manutencao.deletar;

        delete from orctiporec
         using w_orctiporec_deletar
         where orctiporec.o15_codigo = w_orctiporec_deletar.o15_codigo;

        ");
    }
}
