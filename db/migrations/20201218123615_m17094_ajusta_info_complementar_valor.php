<?php

use Classes\PostgresMigration;

class M17094AjustaInfoComplementarValor extends PostgresMigration
{
    public function up()
    {
        $this->ajustaFonteDestinacaoDeRecursos();
        $this->ajustaRecursoVinculado();
    }

    public function down()
    {

    }


    private function ajustaFonteDestinacaoDeRecursos()
    {
        $this->update(3);
    }
    private function ajustaRecursoVinculado()
    {
        $this->update(52);
    }

    private function registrosAjustar($idInfoComplementar)
    {
        return <<<SQL
            with registros_ajustar as (
                select c123_valor from (
                    select distinct c123_valor from infocomplementarvalor
                     where c123_infocomplementar = $idInfoComplementar
                       and c123_valor not in ('SI', 'NI')
                ) as x
                where not exists (select * from orctiporec where o15_recurso = c123_valor)
            )
SQL;
    }

    private function update($idInfoComplementar)
    {
        $sql = $this->registrosAjustar($idInfoComplementar);
        $this->execute(<<<SQL
            {$sql}, fonte_correta as (
             select c123_valor as old, o15_recurso
                from registros_ajustar
                join orctiporec on o15_codigo = c123_valor::int
            ) update infocomplementarvalor set c123_valor = o15_recurso
                from fonte_correta
               where c123_valor = fonte_correta.old
                 and c123_infocomplementar = $idInfoComplementar;
SQL
        );

        $this->execute(<<<SQL
            {$sql}, ajustar_pelo_reduzido as (
                select infocomplementarvalor.c123_valor as old, o15_recurso
                  from registros_ajustar
                  join infocomplementarvalor on infocomplementarvalor.c123_valor = registros_ajustar.c123_valor
                  join conplanoatributolancamentos on c124_sequencial = infocomplementarvalor.c123_conplanoatributolancamentos
                  join conlancam on conlancam.c70_codlan = c124_lancamento
                  join conlancamdoc on conlancamdoc.c71_codlan = c124_lancamento
                  join conplanoreduz on conplanoreduz.c61_reduz = infocomplementarvalor.c123_reduzido
                                    and conplanoreduz.c61_anousu = conlancam.c70_anousu
                  join orctiporec on o15_codigo = c61_codigo
                 where c123_infocomplementar = $idInfoComplementar
                   and c71_coddoc = 3000
            ) update infocomplementarvalor set c123_valor = o15_recurso
                from ajustar_pelo_reduzido
               where c123_valor = ajustar_pelo_reduzido.old
                 and c123_infocomplementar = $idInfoComplementar
SQL
        );
    }

}
