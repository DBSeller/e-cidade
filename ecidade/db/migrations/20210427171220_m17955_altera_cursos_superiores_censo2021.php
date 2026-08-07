<?php

use Classes\PostgresMigration;

class M17955AlteraCursosSuperioresCenso2021 extends PostgresMigration
{

    public function up()
    {
        $this->excluiCódigosExistentes();
        $sql = "insert into cursoformacao values (623,'Gestão fiscal e tributária - Tecnológico',4,'0411G013','Gestão fiscal e tributária - Tecnológico', 1, True);
        insert into cursoformacao values (624,'Serviços jurídicos e cartoriais - Tecnológico',4,'0421S013','Serviços jurídicos e cartoriais - Tecnológico', 1, True);
        insert into cursoformacao values (625,'Serviços jurídicos e cartoriais - Sequencial',4,'0421S014','Serviços jurídicos e cartoriais - Sequencial', 4, True);
        insert into cursoformacao values (626,'Análises clínicas e toxicológicas - Tecnológico',9,'0914A013','Análises clínicas e toxicológicas - Tecnológico', 1, True);
        insert into cursoformacao values (627,'Análises clínicas e toxicológicas - Sequencial',9,'0914A014','Análises clínicas e toxicológicas - Sequencial', 4, True);";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "delete from cursoformacao where ed94_i_codigo in (623,624,625,626,627);";
        $this->execute($sql);
    }


    // Caso tiver o código cadastrado, vamos excluir primeiro e depois adicionar o novo registro conforme o censo 2021.
    // todas as bases devem seguir o padrão do censo.
    private function excluiCódigosExistentes()
    {
        $sql = "delete from cursoformacao where ed94_i_codigo in (623,624,625,626,627)";
        $this->execute($sql);
    }


}
