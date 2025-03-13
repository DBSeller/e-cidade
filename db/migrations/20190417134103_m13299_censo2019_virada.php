<?php

use Classes\PostgresMigration;

class M13299Censo2019Virada extends PostgresMigration
{

    public function up()
    {
        // para clientes que ainda não virou as tabelas do censo
        $this->virarCenso();

        // vira as tabelas do censo
        $this->viraCensoRegraDisc();
        $this->removeEtapasCenso();
        $this->censoDisciplina();
        $this->atualizaCensoRegraDisc();
    }

    public function down()
    {
        $this->execute("
            update censodisciplina set ed265_c_descr = 'Disciplinas profissionalizantes' where ed265_i_codigo = 17;
            update censodisciplina set ed265_c_descr = 'Disciplinas pedagógicas' where ed265_i_codigo = 25;
            update censodisciplina set ed265_c_descr = 'Outras Disciplinas' where ed265_i_codigo = 99;
        ");

        $this->revertCensoEtapa();
        $this->revertSerieCensoEtapa();
        $this->revertCensoEtapaMediacaoDidatico();

        $this->execute("delete from censoregradisc where ed272_ano = 2019;");
        $this->execute("delete from censodisciplina where ed265_i_codigo in(31, 32);");
    }

    private function virarCenso()
    {
        if (!$this->virou()) {
            $sql = "
                INSERT INTO censoetapa
                    SELECT 
                        ed266_i_codigo,
                        ed266_c_descr,
                        ed266_c_regular,
                        ed266_c_especial,
                        ed266_c_eja,
                        2019 AS ed266_ano
                    FROM censoetapa WHERE ed266_ano = 2018
            ";

            $this->execute($sql);

            $sql = "
                INSERT INTO seriecensoetapa
                    SELECT 
                        nextval('seriecensoetapa_ed133_codigo_seq') AS ed133_codigo,
                        ed133_serie,     
                        ed133_censoetapa,
                        2019
                    FROM seriecensoetapa WHERE ed133_ano = 2018
            ";

            $this->execute($sql);

            $sql = "
                INSERT INTO censoetapamediacaodidaticopedagogica 
                    SELECT 
                        nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq') AS ed131_codigo,
                        ed131_mediacaodidaticopedagogica,
                        ed131_censoetapa,                
                        2019,                       
                        ed131_regular,                   
                        ed131_especial,                  
                        ed131_eja,                       
                        ed131_profissional
                    FROM censoetapamediacaodidaticopedagogica WHERE ed131_ano = 2018
            ";

            $this->execute($sql);

            $sql = "
                INSERT INTO db_virada
                VALUES (nextval('db_virada_c30_sequencial_seq'), 2018, 2019, 1, now(), to_char(now(), 'HH:MM'), 1);
                
                INSERT INTO db_viradaitem
                VALUES (nextval('db_viradaitem_c31_sequencial_seq'), currval('db_virada_c30_sequencial_seq'), 35, 1);            
            ";

            $this->execute($sql);
        }
    }

    private function virou()
    {
        $sSql = "
            SELECT *
            FROM db_virada
              JOIN db_viradaitem ON db_viradaitem.c31_db_virada = db_virada.c30_sequencial
              JOIN db_viradacaditem ON db_viradacaditem.c33_sequencial = db_viradaitem.c31_db_viradacaditem
            WHERE c33_sequencial = 35 AND c30_anodestino = 2019
        ";

        return count($this->fetchAll($sSql)) > 0;
    }

    private function censoDisciplina()
    {
        // atualizacao
        $this->execute("
            update censodisciplina set ed265_c_descr = 'Áreas do conhecimento profissionalizantes' where ed265_i_codigo = 17;
            update censodisciplina set ed265_c_descr = 'Áreas do conhecimento pedagógicas' where ed265_i_codigo = 25;
            update censodisciplina set ed265_c_descr = 'Outras Áreas do conhecimento' where ed265_i_codigo = 99;
        ");

        // novas
        $this->execute("
            insert into censodisciplina 
            values (31, 'Língua Portuguesa como Segunda Língua'),
                   (32, 'Estágio curricular supervisionado');
        ");
    }

    private function revertCensoEtapaMediacaoDidatico()
    {
        $this->execute("
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 4, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 5, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 6, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 7, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 8, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 9, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 10, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 11, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 13, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 1, 24, 2019, 'S', 'S', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 4, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 5, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 6, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 7, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 8, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 9, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 10, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 11, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 13, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 2, 24, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 4, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 5, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 6, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 7, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 8, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 9, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 10, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 11, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 13, 2019, 'N', 'N', 'N', 'N');
        INSERT INTO escola.censoetapamediacaodidaticopedagogica (ed131_codigo, ed131_mediacaodidaticopedagogica, ed131_censoetapa, ed131_ano, ed131_regular, ed131_especial, ed131_eja, ed131_profissional) VALUES (nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), 3, 24, 2019, 'N', 'N', 'N', 'N');
        ");
    }

    private function revertCensoEtapa()
    {
        $this->execute("
            INSERT INTO escola.censoetapa (ed266_i_codigo, ed266_c_descr, ed266_c_regular, ed266_c_especial, ed266_c_eja, ed266_ano) 
            VALUES (4, 'Ensino Fundamental de 8 anos - 1ª Série', ' ', ' ', ' ', 2019),
                   (5, 'Ensino Fundamental de 8 anos - 2ª Série', ' ', ' ', ' ', 2019),
                   (6, 'Ensino Fundamental de 8 anos - 3ª Série', ' ', ' ', ' ', 2019),
                   (7, 'Ensino Fundamental de 8 anos - 4ª Série', ' ', ' ', ' ', 2019),
                   (8, 'Ensino Fundamental de 8 anos - 5ª Série', ' ', ' ', ' ', 2019),
                   (9, 'Ensino Fundamental de 8 anos - 6ª Série', ' ', ' ', ' ', 2019),
                   (10, 'Ensino Fundamental de 8 anos - 7ª Série', ' ', ' ', ' ', 2019),
                   (11, 'Ensino Fundamental de 8 anos - 8ª Série', ' ', ' ', ' ', 2019),
                   (13, 'Ensino Fundamental de 8 anos - Correção de Fluxo', ' ', ' ', ' ', 2019),
                   (24, 'Ensino Fundamental de 8 e 9 anos - Multi 8 e 9 anos', ' ', ' ', ' ', 2019);
        ");
    }

    private function revertSerieCensoEtapa()
    {
        $this->execute("
        INSERT INTO escola.seriecensoetapa (ed133_codigo, ed133_serie, ed133_censoetapa, ed133_ano) 
        VALUES (nextval('seriecensoetapa_ed133_codigo_seq'), 10, 4, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 11, 5, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 12, 6, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 13, 7, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 14, 8, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 15, 9, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 16, 10, 2019),
               (nextval('seriecensoetapa_ed133_codigo_seq'), 17, 11, 2019);
        ");
    }

    private function removeEtapasCenso()
    {
        $this->execute("
            delete from censoetapamediacaodidaticopedagogica where ed131_ano = 2019 and ed131_censoetapa in (4,5,6,7, 8, 9, 10, 11, 13, 24);
            delete from censoetapaturmacenso where ed134_ano = 2019 and ed134_censoetapa in (4, 5, 6, 7, 8, 9, 10, 11, 13, 24);
            delete from censoregradisc where ed272_ano = 2019 and ed272_i_censoetapa in (4, 5, 6, 7, 8, 9, 10, 11, 13, 24);
            delete from seriecensoetapa where ed133_ano = 2019 and ed133_censoetapa in (4, 5, 6, 7, 8, 9, 10, 11, 13, 24);
            delete from turmacensoetapa where ed132_ano = 2019 and ed132_censoetapa in (4, 5, 6, 7, 8, 9, 10, 11, 13, 24);
            delete from censoetapa where ed266_ano = 2019 and ed266_i_codigo in (4, 5, 6, 7, 8, 9, 10, 11, 13, 24);
        ");
    }

    private function viraCensoRegraDisc()
    {
        $this->execute("
            INSERT INTO censoregradisc (ed272_i_codigo, ed272_i_censoetapa, ed272_i_censodisciplina, ed272_ano) SELECT
                nextval('censoregradisc_ed272_i_codigo_seq') AS ed272_i_codigo,
                ed272_i_censoetapa,
                ed272_i_censodisciplina,
                2019
              FROM censoregradisc
              WHERE ed272_ano = 2018;
        ");
    }

    private function atualizaCensoRegraDisc()
    {
        // insere novas disciplinas
        $this->execute("
        insert into censoregradisc
        values (nextval('censoregradisc_ed272_i_codigo_seq'), 14, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 15, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 16, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 17, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 18, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 69, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 19, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 20, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 21, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 41, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 23, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 22, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 56, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 70, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 72, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 73, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 73, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 25, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 26, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 27, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 28, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 29, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 71, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 30, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 30, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 31, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 31, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 32, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 32, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 33, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 33, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 34, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 34, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 74, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 74, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 67, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 67, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 35, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 35, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 36, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 36, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 37, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 37, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 38, 31, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 38, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 39, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 40, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 64, 32, 2019),
               (nextval('censoregradisc_ed272_i_codigo_seq'), 68, 32, 2019);
        ");

        // remove as que não deveriam ter vínculos
        $this->execute("
            delete from censoregradisc where ed272_i_censoetapa in (14,15,16,17,18) and ed272_i_censodisciplina in (1,2,4,17,20,21) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (69) and ed272_i_censodisciplina in (1,2,4,17,20,21,25,29) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (19,20,21,41) and ed272_i_censodisciplina in (17,20,21,25,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (23,22,56) and ed272_i_censodisciplina in (17,20,21,25) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (70) and ed272_i_censodisciplina in (17,20,21,25,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (72) and ed272_i_censodisciplina in (17,20,21,25) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (73) and ed272_i_censodisciplina in (20,21,25) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (25,26,27,28,29) and ed272_i_censodisciplina in (5,17,20,21,25,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (30,31,32,33,34) and ed272_i_censodisciplina in (5,20,21,25,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (74,67) and ed272_i_censodisciplina in (5,20,21,25,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (35,36,37,38) and ed272_i_censodisciplina in (17,28) and ed272_ano = 2019;
            delete from censoregradisc where ed272_i_censoetapa in (39,40,64,68) and ed272_i_censodisciplina in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,16,20,21,23,25,26,27,28,29,30,31,99) and ed272_ano = 2019;
        ");
    }
}
