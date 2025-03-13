<?php

use Classes\PostgresMigration;

class M10911FundamentacaoLicitaCon extends PostgresMigration
{
    private $codigoFundamentacao = 18;

    private function getCodigoFundamentacao()
    {
        $linha = (object)$this->fetchRow("select * from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao';");
        if ((int)$linha->db109_sequencial != 18) {
            $this->codigoFundamentacao = $linha->db109_sequencial;
        }
    }

    public function up()
    {
        $this->getCodigoFundamentacao();
        $this->inserirNovasOpcoes();
    }

    public function down()
    {
        $this->getCodigoFundamentacao();
        $this->excluirNovasOpcoes();
    }

    private function inserirNovasOpcoes()
    {
        $this->execute(<<<SQL
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24III'   , 'Art. 24, inc. III, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XVII'  , 'Art. 24, inc. XVII, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXIII' , 'Art. 24, inc. XXIII, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXIV'  , 'Art. 24, inc. XXIV, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXVI'  , 'Art. 24, inc. XXVI, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXVII' , 'Art. 24, inc. XXVII, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXX'   , 'Art. 24, inc. XXX, da lei no 8.666/93');
            insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao}, 'A24XXXIV' , 'Art. 24, inc. XXXIV, da lei no 8.666/93');
SQL
        );
    }

    private function excluirNovasOpcoes()
    {
        $this->execute(<<<SQL
            delete from db_cadattdinamicoatributosopcoes 
                  where db18_opcao in ('A24III', 'A24XVII', 'A24XXIII', 'A24XXIV', 'A24XXVI', 'A24XXVII', 'A24XXX', 'A24XXXIV') 
                    and db18_cadattdinamicoatributos = {$this->codigoFundamentacao};
SQL
        );
    }
}
