<?php

use Classes\PostgresMigration;

class M18472NovaLeiLicitacoes extends PostgresMigration
{
    private $codigoFundamentacao = 18;
    private $modalidades = [
        'CCP' => 'Concorrência Lei 14.133 Presencial',
        'CCE' => 'Concorrência Lei 14.133 Eletrônica',
        'PCE' => 'Pregão Lei 14.133 Eletrônico',
        'PCP' => 'Pregão Lei 14.133 Presencial'
    ];

    private function getCodigoFundamentacao()
    {
        $linha = (object) $this->fetchRow("select * from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao';");
        if ((int) $linha->db109_sequencial != 18) {
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
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75I', 'Art. 75, inc. I, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75II',	'Art. 75, inc. II, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IIIA', 'Art. 75, inc. III, alínea "a" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IIIB', 'Art. 75, inc. III, alínea "b" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVA', 'Art. 75, inc. IV, alínea "a" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVE', 'Art. 75, inc. IV, alínea "e" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVJ', 'Art. 75, inc. IV, alínea "j" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVK', 'Art. 75, inc. IV, alínea "k" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVL', 'Art. 75, inc. IV, alínea "l" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IVM', 'Art. 75, inc. IV, alínea "m" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75IX',	'Art. 75, inc. IX, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75VII', 'Art. 75, inc. VII, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75VIII', 'Art. 75, inc. VIII, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75XI',	'Art. 75, inc. XI, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75XIII', 'Art. 75, inc. XIII, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75XIV', 'Art. 75, inc. XIV, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75XV',	'Art. 75, inc. XV, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A75XVI', 'Art. 75, inc. XVI, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A30CAPT', 'Art. 30, "caput", da Lei no 13.303/2016');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74CAPT', 'Art. 74, "caput", da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74I', 'Art. 74, inc. I, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74II', 'Art. 74, inc. II, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIA', 'Art. 74, inc. III, alínea "a" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIB', 'Art. 74, inc. III, alínea "b" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIC', 'Art. 74, inc. III, alínea "c" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIID', 'Art. 74, inc. III, alínea "d" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIE', 'Art. 74, inc. III, alínea "e" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIF', 'Art. 74, inc. III, alínea "f" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIG', 'Art. 74, inc. III, alínea "g" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IIIH', 'Art. 74, inc. III, alínea "h" da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74IV', 'Art. 74, inc. IV, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'A74V', 'Art. 74, inc. V, da Lei no 14.133/21');
            INSERT INTO db_cadattdinamicoatributosopcoes VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$this->codigoFundamentacao},'OUT', 'Outra');

            ALTER TABLE pctipocompra ALTER COLUMN pc50_descr TYPE VARCHAR(120);
SQL
        );

        $inserts = [];

        // Verifica se modalidades já estão no banco
        $rsModalidades = (object) $this->fetchAll("
            SELECT
                l44_sigla
            FROM
                pctipocompratribunal
            WHERE
                l44_sigla IN ('CCP', 'CCE', 'PCE', 'PCP')
        ");

        $modalidadesExistem = [
            'CCP' => false,
            'CCE' => false,
            'PCE' => false,
            'PCP' => false
        ];
        
        foreach ($rsModalidades as $mod) {
            $modalidadesExistem[$mod->l44_sigla] = true;
        }
        
        foreach ($this->modalidades as $sigla => $descricao) {
            if (!$modalidadesExistem[$sigla]) {
                $inserts[] = "INSERT INTO pctipocompratribunal VALUES(nextval('pctipocompratribunal_l44_sequencial_seq'), '99', '$descricao', 'RS', '$sigla', 't')";  
            }
        }
       
        // Vincula modalidades ao tribunal se ainda não foram vinculadas
        $modalidadesNaoVinculadas = (object) $this->fetchAll("
            SELECT
                l44_sequencial,
                l44_sigla,
                l44_descricao
            FROM
                pctipocompratribunal
            WHERE
                l44_uf = 'RS'
                AND l44_sequencial NOT IN (
                    SELECT pc50_pctipocompratribunal
                    FROM pctipocompra
                )
        ");

        $rsUltimoId = (object) $this->fetchRow('SELECT MAX(pc50_codcom) AS ultimo_id FROM pctipocompra;');
        $proximoId = $rsUltimoId->ultimo_id + 1;

        foreach ($modalidadesNaoVinculadas as $mod) {
            $modalidade = (object) $mod;
            $descricao = "{$modalidade->l44_sigla} - {$modalidade->l44_descricao}";

            $inserts[] = "INSERT INTO pctipocompra VALUES($proximoId, '{$descricao}', $modalidade->l44_sequencial, 't')";

            $proximoId++;
        }
        
        if (!empty($inserts)) {
            $this->execute(implode(';', $inserts));
        }
    }

    private function excluirNovasOpcoes()
    {
        $this->execute(<<<SQL
            DELETE FROM db_cadattdinamicoatributosopcoes 
                  WHERE db18_opcao IN (
                    'A75I',
                    'A75II',
                    'A75IIIA',
                    'A75IIIB',
                    'A75IVA',
                    'A75IVE',
                    'A75IVJ',
                    'A75IVK',
                    'A75IVL',
                    'A75IVM',
                    'A75IX',
                    'A75VII',
                    'A75VIII',
                    'A75XI',
                    'A75XIII',
                    'A75XIV',
                    'A75XV',
                    'A75XVI',
                    'A30CAPT',
                    'A74CAPT',
                    'A74I',
                    'A74II',
                    'A74IIIA',
                    'A74IIIB',
                    'A74IIIC',
                    'A74IIID',
                    'A74IIIE',
                    'A74IIIF',
                    'A74IIIG',
                    'A74IIIH',
                    'A74IV',
                    'A74V',
                    'A75I',
                    'A75II',
                    'A75IIIA',
                    'A75IIIB',
                    'A75IVA',
                    'A75IVE',
                    'A75IVJ',
                    'A75IVK',
                    'A75IVL',
                    'A75IVM',
                    'A75IX',
                    'A75VII',
                    'A75VIII',
                    'A75XI',
                    'A75XIII',
                    'A75XIV',
                    'A75XV',
                    'A75XVI'
                  ) 
                    and db18_cadattdinamicoatributos = {$this->codigoFundamentacao};
SQL
        );
    }
}
