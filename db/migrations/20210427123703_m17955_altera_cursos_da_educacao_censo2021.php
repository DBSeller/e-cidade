<?php

use Classes\PostgresMigration;

class M17955AlteraCursosDaEducacaoCenso2021 extends PostgresMigration
{

    public function up()
    {
        $this->exclusaoUp();
        $this->inclusaoUp();
        $this->alteracaoUp();

    }

    public function down()
    {
        $this->inclusaoDown();
        $this->alteracaoDown();
        $this->exclusaoDown();
    }

    /**
     * UP
     */

    private function inclusaoUp()
    {
      $sql = "insert into censocursoprofiss values(1031,upper('Optometria'),1);
              insert into censocursoprofiss values(1032,upper('Terapias Holísticas'),1);
              insert into censocursoprofiss values(1033,upper('Veterinária'),1);
              insert into censocursoprofiss values (2040,upper('Arquivo'),2);
              insert into censocursoprofiss values(3061,upper('Ferramentaria'),3);
              insert into censocursoprofiss values(3062,upper('Fundição'),3);
              insert into censocursoprofiss values(3063,upper('Instrumentação Industrial'),3);
              insert into censocursoprofiss values(3064,upper('Fabricação Mecânica'),3);
              insert into censocursoprofiss values(10158,upper('Design de Moda'),10);
              insert into censocursoprofiss values(10159,upper('Estilismo e Coordenação de Moda'),10);
              insert into censocursoprofiss values(10160,upper('Produção Cultural'),10);
              insert into censocursoprofiss values(11176,upper('Planejamento e Controle da Produção'),11);
              insert into censocursoprofiss values(11177,upper('Vidros'),11);
              insert into censocursoprofiss values(11178,upper('Processamento da Madeira'),11);
              insert into censocursoprofiss values(12186,upper('Apicultura'),12);
              insert into censocursoprofiss values(13183,upper('Prevenção e Combate a Incêndios'),13);";

      $this->execute($sql);

    }

    private function alteracaoUp()
    {
      $sql = "update censocursoprofiss set ed247_c_descr = upper('Dependência química') where ed247_i_codigo = 1024;
            update censocursoprofiss set ed247_c_descr = upper('Brinquedoteca') where ed247_i_codigo = 2035;
            update censocursoprofiss set ed247_c_descr = upper('Desenvolvimento Comunitário') where ed247_i_codigo = 2033;
            update censocursoprofiss set ed247_c_descr = upper('Manutenção Aeronáutica em Aviônicos') where ed247_i_codigo = 3050;
            update censocursoprofiss set ed247_c_descr = upper('Manutenção Aeronáutica em Célula') where ed247_i_codigo = 3051;
            update censocursoprofiss set ed247_c_descr = upper('Manutenção Aeronáutica em Grupo Motopropulsor') where ed247_i_codigo = 3052;
            update censocursoprofiss set ed247_c_descr = upper('Gastronomia') where ed247_i_codigo = 5067;
            update censocursoprofiss set ed247_c_descr = upper('Serviços de Restaurante e Bar') where ed247_i_codigo = 5072;
            update censocursoprofiss set ed247_c_descr = upper('Design Gráfico') where ed247_i_codigo = 10134;
            update censocursoprofiss set ed247_c_descr = upper('Instrumento Musical (nome do instrumento)') where ed247_i_codigo = 10144;
            update turma set ed57_i_censocursoprofiss = 11178 where ed57_i_censocursoprofiss = 3057;
            update turma set ed57_i_censocursoprofiss = 12186 where ed57_i_censocursoprofiss = 9122;
            update turma set ed57_i_censocursoprofiss = 11161 where ed57_i_censocursoprofiss = 3064;
            update ensino set ed10_censocursoprofiss = 11178 where ed10_censocursoprofiss = 3057;
            update ensino set ed10_censocursoprofiss = 12186 where ed10_censocursoprofiss = 9122;
            update ensino set ed10_censocursoprofiss = 11161 where ed10_censocursoprofiss = 3064;";





      $this->execute($sql);

    }

    private function exclusaoUp()
    {
      $sql = "update ensino set ed10_censocursoprofiss = null where ed10_censocursoprofiss in (3057,8120,8121,8122,8123,8124,8125,8127,8128,8129,8131,8132,9122,11161,11168,12177,12186);
              update turma set ed57_i_censocursoprofiss = null where ed57_i_censocursoprofiss in (3057,8120,8121,8122,8123,8124,8125,8127,8128,8129,8131,8132,9122,11161,11168,12177,12186);
              delete from censocursoprofiss where ed247_i_codigo in (3057,8120,8121,8122,8123,8124,8125,8127,8128,8129,8131,8132,9122,11161,11168,12177,12186);";
              ;

      $this->execute($sql);

    }

    /**
     * DOWN
     */

    private function inclusaoDown()
    {
       $sql = "delete from censocursoprofiss where ed247_i_codigo in (1031,1032,1033,2040,3061,3062,3063,3064,10158,10159,10160,11176,11177,11178,12186,13183);";
        $this->execute($sql);
    }

    private function alteracaoDown()
    {
        $sql = "update censocursoprofiss set ed247_c_descr = 'REABILITACAO DE DEPENDENTES QUIMICOS' where ed247_i_codigo = 1024;
                update censocursoprofiss set ed247_c_descr = 'LUDOTECA' where ed247_i_codigo = 2035;
                update censocursoprofiss set ed247_c_descr = 'ORIENTACAO COMUNITARIA' where ed247_i_codigo = 2033;
                update censocursoprofiss set ed247_c_descr = 'MANUTENCAO DE AERONAVES EM AVIONICOS' where ed247_i_codigo = 3050;
                update censocursoprofiss set ed247_c_descr = 'MANUTENCAO DE AERONAVES EM CELULA' where ed247_i_codigo = 3051;
                update censocursoprofiss set ed247_c_descr = 'MANUTENCAO DE AERONAVES EM GRUPO MOTOPROPULSOR' where ed247_i_codigo = 3052;
                update censocursoprofiss set ed247_c_descr = 'COZINHA' where ed247_i_codigo = 5067;
                update censocursoprofiss set ed247_c_descr = 'RESTAURANTE E BAR' where ed247_i_codigo = 5072;
                update censocursoprofiss set ed247_c_descr = 'COMUNICACAO VISUAL' where ed247_i_codigo = 10134;
                update censocursoprofiss set ed247_c_descr = 'INSTRUMENTO MUSICAL' where ed247_i_codigo = 10144;
                update turma set ed57_i_censocursoprofiss = 3057 where ed57_i_censocursoprofiss = 11178;
                update turma set ed57_i_censocursoprofiss = 9122 where ed57_i_censocursoprofiss = 12186;
                update turma set ed57_i_censocursoprofiss = 3064 where ed57_i_censocursoprofiss = 11161;
                update ensino set ed10_censocursoprofiss = 3057 where ed10_censocursoprofiss = 11178;
                update ensino set ed10_censocursoprofiss = 9122 where ed10_censocursoprofiss = 12186;
                update ensino set ed10_censocursoprofiss = 3064 where ed10_censocursoprofiss = 11161;";

        $this->execute($sql);

    }

    private function exclusaoDown()
    {
        $sql = "insert into censocursoprofiss values(3057,upper('Processamento da Madeira'),3);
                insert into censocursoprofiss values(8120,upper('Ações de Comandos'),8);
                insert into censocursoprofiss values(8121,upper('Armamento de Aeronaves'),8);
                insert into censocursoprofiss values(8122,upper('Artilharia'),8);
                insert into censocursoprofiss values(8123,upper('Artilharia Antiaérea'),8);
                insert into censocursoprofiss values(8124,upper('Cavalaria'),8);
                insert into censocursoprofiss values(8125,upper('Combate a Incêndio, Resgate e Prevenção de Acidentes de Aviação'),8);
                insert into censocursoprofiss values(8127,upper('Equipamento de Engenharia'),8);
                insert into censocursoprofiss values(8128,upper('Forças Especiais'),8);
                insert into censocursoprofiss values(8129,upper('Infantaria'),8);
                insert into censocursoprofiss values(8131,upper('Montanhismo'),8);
                insert into censocursoprofiss values(8132,upper('Navegação Fluvial'),8);
                insert into censocursoprofiss values(9122,upper('Apicultura'),9);
                insert into censocursoprofiss values(11161,upper('Fabricação Mecânica'),11);
                insert into censocursoprofiss values(11168,upper('Pré-Impressão Gráfica'),11);
                insert into censocursoprofiss values(12177,upper('Equipamentos Pesqueiros'),12);
                insert into censocursoprofiss values(12186,upper('Grãos'),12);";

        $this->execute($sql);
    }
}
