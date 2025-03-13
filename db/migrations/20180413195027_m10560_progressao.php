<?php

use Classes\PostgresMigration;

class M10560Progressao extends PostgresMigration
{
    public function up()
    {
        $this->upDDL();
        $this->upDicionarioDados();
        $this->upFormulasSQL();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
        $this->downFormulasSQL();
    }

    public function upDDL()
    {
        $this->table('padroes', array('schema'=>'pessoal'))
             ->addColumn('r02_nivel',               'string',  array('null' => true, 'limit' => 250))
             ->addColumn('r02_classe',              'string',  array('null' => true, 'limit' => 250))
             ->addColumn('r02_padraopai_regime',    'integer', array('null' => true))
             ->addColumn('r02_padraopai_codigo',    'string',  array('null' => true, 'limit' => 10))
             ->addColumn('r02_padraopai_instit',    'integer', array('null' => true))
             ->save();
    }

    public function upDicionarioDados()
    {
        $sqls   = array();
        $sqls[] = "INSERT INTO db_syscampo VALUES (1009703,'r02_nivel','varchar(250)','Campo destinado para cadastrar o nível do padrão','', 'Nível',250,'f','t','f',0,'text','Nível');";
        $sqls[] = "INSERT INTO db_syscampo VALUES (1009704,'r02_classe','varchar(250)','Campo destinado para cadastrar a classe do padrão','', 'Classe',250,'f','t','f',0,'text','Classe');";
        $sqls[] = "INSERT INTO db_syscampo VALUES (1009705,'r02_padraopai','int4','Padrão ao qual está vinculado','0', 'Padrão vinculado',19,'t','f','f',1,'text','Padrão vinculado');";
        $sqls[] = "INSERT INTO db_syscampo VALUES (1009706,'r02_padraopai_codigo','int4','Regime do padrão vinculado.','0', 'Padrão vinculado',19,'t','f','f',1,'text','Padrão vinculado');";
        $sqls[] = "INSERT INTO db_syscampo VALUES (1009707,'r02_padraopai_instit','int4','Instituição à qual o padrão está vinculado','0', 'Padrão vinculado',19,'t','f','f',1,'text','Padrão vinculado');";
        $sqls[] = "DELETE FROM db_sysarqcamp WHERE codarq = 567 AND codcam IN (1009703, 1009704, 1009705);";
        $sqls[] = "INSERT INTO db_sysarqcamp VALUES (567,1009703,13,0);";
        $sqls[] = "INSERT INTO db_sysarqcamp VALUES (567,1009704,14,0);";
        $sqls[] = "INSERT INTO db_sysarqcamp VALUES (567,1009705,15,0);";
        $sqls[] = "INSERT INTO db_sysarqcamp VALUES (567,1009706,16,0);";
        $sqls[] = "INSERT INTO db_sysarqcamp VALUES (567,1009707,17,0);";
        $sqls[] = "DELETE FROM db_syscampodep WHERE codcam = 1009703;";
        $sqls[] = "DELETE FROM db_syscampodef WHERE codcam = 1009703;";
        $sqls[] = "DELETE FROM db_syscampodep WHERE codcam = 1009704;";
        $sqls[] = "DELETE FROM db_syscampodef WHERE codcam = 1009704;";
        $sqls[] = "INSERT INTO db_sysforkey values (567,1009705,1,567,0);";
        $sqls[] = "INSERT INTO db_sysforkey values (567,1009706,2,567,0);";
        $sqls[] = "INSERT INTO db_sysforkey values (567,1009707,3,567,0);";
        $sqls[] = "UPDATE db_syscampo SET   nomecam = 'r02_padraopai_regime',
                                           conteudo = 'int4',
                                          descricao = 'Regime ao qual o padrão está vinculado.', valorinicial = '0',
                                             rotulo = 'Padrão vinculado',
                                               nulo = 't',
                                            tamanho = 19,
                                          maiusculo = 'f',
                                          autocompl = 'f',
                                         aceitatipo = 1,
                                            tipoobj = 'text',
                                          rotulorel = 'Padrão vinculado'
                                   WHERE codcam = 1009705;";


        $sqls[] = "insert into db_sysarquivo values (1010276, ' db_formulasdb_cadattdinamicoatributos', 'Tabela de vinculo entre o atributo dinamico eo tipo de retorno para o atributo .', '', '2018-04-17', '', 0, 'f', 'f', 'f', 'f' );";
        $sqls[] = "insert into db_sysarqmod values (7,1010276);";

        $sqls[] = "insert into db_syscampo values(1009708,'db1101_formula','int8','sequencial da tabela formula','0', 'db1101_formula',11,'f','f','f',1,'text','db1101_formula');";
        $sqls[] = "insert into db_syscampo values(1009709,'db1101_cadattdinamicoatributos','int8','Campo vinculo com tabela db1101_db_cadattdinamicoatributos','0', 'db1101_cadattdinamicoatributos',11,'f','f','f',1,'text','db1101_cadattdinamicoatributos');";
        $sqls[] = "insert into db_syscampo values(1009710,'db1101_tipo','char(1)','Campo que define o tipo de retorno para o atributo dinamico','', 'db1101_tipo',1,'f','t','f',0,'text','db1101_tipo');";
        $sqls[] = "insert into db_syscampo values(1009712,'db1101_sequencial','int8','sequencial da tabela','0', 'db1101_sequencial',11,'f','f','f',1,'text','db1101_sequencial');";

        $sqls[] = "insert into db_sysarqcamp values(1010276,1009708,1,0);";
        $sqls[] = "insert into db_sysarqcamp values(1010276,1009709,2,0);";
        $sqls[] = "insert into db_sysarqcamp values(1010276,1009710,3,0);";
        $sqls[] = "insert into db_sysarqcamp values(1010276,1009712,4,0);";


        $sqls[] = "insert into db_sysforkey values(1010276,1009708,1,3820,0);";
        $sqls[] = "insert into db_sysforkey values(1010276,1009709,1,3163,0);";

        $sqls[] = "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010276,1009712,1,1009708);";

        foreach ($sqls as $sql) {
            $this->execute($sql);
        }
    }

    public function upFormulasSQL()
    {
        $sqls   = array();
        // Fórmula para retornar os dados do padrão atual
        $sqlInsertFormula  = "INSERT INTO db_formulas VALUES ( ";
        $sqlInsertFormula .= "     6677,  ";
        $sqlInsertFormula .= "     'DADOS_PADRAO_SALARIO_ATUAL',  ";
        $sqlInsertFormula .= "     'Retorna os dados do padrão de salário atual do servidor.',  ";
        $sqlInsertFormula .= "     'SELECT  rh02_regist as matricula,
                                            r02_regime as regime,
                                            r02_codigo as codigo,
                                            r02_descr as descricao,
                                            r02_valor as valor,
                                            r02_instit as instituicao,
                                            r02_nivel as nivel,
                                            r02_classe as classe,
                                            r02_padraopai_regime as padraopai_regime,
                                            r02_padraopai_codigo as padraopai_codigo
                                        FROM padroes
                                        INNER JOIN rhpespadrao
                                                ON rh03_anousu = r02_anousu
                                               AND rh03_mesusu = r02_mesusu
                                               AND rh03_padrao = r02_codigo
                                               AND rh03_regime = r02_regime
                                        INNER JOIN rhpessoalmov
                                                ON rh02_seqpes = rh03_seqpes
                                               AND rh02_anousu = rh03_anousu
                                               AND rh02_mesusu = rh03_mesusu
                                               AND rh02_instit = r02_instit
                                        WHERE rh02_regist = [H16_REGIST]
                                          AND rh02_anousu = fc_anofolha(fc_getsession(\'DB_instit\')::int)
                                          AND rh02_mesusu = fc_mesfolha(fc_getsession(\'DB_instit\')::int)
                                          AND rh02_instit = fc_getsession(\'DB_instit\')::int',  ";
        $sqlInsertFormula .= "      false); ";
        $sqls[] = $sqlInsertFormula;
        
        // Fórmula para retornar os dados do próximo padrão
        $sqlInsertFormula  = "INSERT INTO db_formulas VALUES ( ";
        $sqlInsertFormula .= "     6678,  ";
        $sqlInsertFormula .= "     'DADOS_PADRAO_SALARIO_APTO',  ";
        $sqlInsertFormula .= "     'Retorna os dados do padrão de salário ao qual o servidor está apto.',  ";
        $sqlInsertFormula .= "     'SELECT  dados.rh02_regist as matricula,
                                            padroes.r02_regime as regime,
                                            padroes.r02_codigo as codigo,
                                            padroes.r02_descr as descricao,
                                            padroes.r02_valor as valor,
                                            padroes.r02_instit as instituicao,
                                            padroes.r02_nivel as nivel,
                                            padroes.r02_classe as classe,
                                            padroes.r02_padraopai_regime as padraopai_regime,
                                            padroes.r02_padraopai_codigo as padraopai_codigo
                                        FROM padroes,
                                            (  SELECT
                                                    rh02_regist,
                                                    r02_regime,
                                                    r02_codigo,
                                                    r02_descr,
                                                    r02_valor,
                                                    r02_instit,
                                                    r02_nivel,
                                                    r02_classe,
                                                    r02_padraopai_regime,
                                                    r02_padraopai_codigo,
                                                    r02_anousu,
                                                    r02_mesusu
                                                FROM padroes
                                                INNER JOIN rhpespadrao
                                                        ON rh03_anousu = r02_anousu
                                                       AND rh03_mesusu = r02_mesusu
                                                       AND rh03_padrao = r02_codigo
                                                       AND rh03_regime = r02_regime
                                                INNER JOIN rhpessoalmov
                                                        ON rh02_seqpes = rh03_seqpes
                                                       AND rh02_anousu = rh03_anousu
                                                       AND rh02_mesusu = rh03_mesusu
                                                       AND rh02_instit = r02_instit
                                                WHERE rh02_regist = [H16_REGIST]
                                                  AND rh02_anousu = fc_anofolha(fc_getsession(\'DB_instit\')::int)
                                                  AND rh02_mesusu = fc_mesfolha(fc_getsession(\'DB_instit\')::int)
                                                  AND rh02_instit = fc_getsession(\'DB_instit\')::int
                                            ) AS dados
                                        WHERE padroes.r02_anousu = dados.r02_anousu
                                          AND padroes.r02_mesusu = dados.r02_mesusu
                                          AND padroes.r02_instit = dados.r02_instit
                                          AND padroes.r02_regime = dados.r02_padraopai_regime
                                          AND padroes.r02_padraopai_codigo = dados.r02_codigo',  ";
        $sqlInsertFormula .= "      false); ";
        $sqls[] = $sqlInsertFormula;

        // Fórmulas para retornar individualmente nivel, classe e valor padrão ao qual o servidor está atualmente
        $sqls[] = " INSERT INTO db_formulas VALUES (6679, 'NIVEL_PADRAO_ATUAL',     'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT nivel     FROM ([DADOS_PADRAO_SALARIO_ATUAL]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6680, 'CLASSE_PADRAO_ATUAL',    'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT classe    FROM ([DADOS_PADRAO_SALARIO_ATUAL]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6681, 'VALOR_PADRAO_ATUAL',     'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT valor     FROM ([DADOS_PADRAO_SALARIO_ATUAL]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6682, 'DESCRICAO_PADRAO_ATUAL', 'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT descricao FROM ([DADOS_PADRAO_SALARIO_APTO]) as valor',  false); ";

        // Fórmulas para retornar individualmente nivel, classe e valor padrão ao qual o servidor está apto
        $sqls[] = " INSERT INTO db_formulas VALUES (6683, 'NIVEL_PADRAO_APTO',     'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT nivel     FROM ([DADOS_PADRAO_SALARIO_APTO]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6684, 'CLASSE_PADRAO_APTO',    'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT classe    FROM ([DADOS_PADRAO_SALARIO_APTO]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6685, 'VALOR_PADRAO_APTO',     'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT valor     FROM ([DADOS_PADRAO_SALARIO_APTO]) as valor',  false); ";
        $sqls[] = " INSERT INTO db_formulas VALUES (6686, 'DESCRICAO_PADRAO_APTO', 'Retorna o nivel do padrão de salário ao qual o servidor está apto.', 'SELECT descricao FROM ([DADOS_PADRAO_SALARIO_APTO]) as valor',  false); ";

        $sqls[] = " INSERT INTO  db_cadattdinamico  VALUES (170, 'Grupo de Atributos Dinâmicos')";
        $sqls[] = " INSERT INTO db_cadattdinamicoatributos VALUES (4024,170, null, 'Nivel APTO',null,1,null,false,true,false , 6683); ";
        $sqls[] = " INSERT INTO db_cadattdinamicoatributos VALUES (4025,170, null, 'Nivel ',null,1,null,false,true,false , 6679); ";


        $sqls[] = " INSERT INTO db_cadattdinamicoatributos VALUES (4026,170, null, 'VALOR PADRAO ATUAL',null,1,null,false,true,false , 6682); ";
        $sqls[] = " INSERT INTO db_cadattdinamicoatributos VALUES (4027,170, null, 'VALOR PADRAO APTO',null,1,null,false,true,false , 6685); ";

        $sqls[] = " INSERT INTO db_cadattdinamicoatributos VALUES (4023,170, null, 'Classe APTO',null,1,null,false,true,false , 6684); ";



        foreach ($sqls as $sql) {
            $this->execute($sql);
        }
    }

    public function downFormulasSQL()
    {
        $sqls   = array();
        $sqls[] = "DELETE FROM db_formulas  WHERE db148_sequencial IN (6677, 6678); ";

        foreach ($sqls as $sql) {
            $this->execute($sql);
        }
    }

    public function downDicionarioDados()
    {
        $sqls   = array();
        $sqls[] = "DELETE FROM db_sysforkey WHERE codarq = 567 AND codcam IN (1009705, 1009706, 1009707, 1009708,1009709);";
        $sqls[] = "DELETE FROM db_sysarqcamp WHERE codarq = 567 AND codcam IN (1009703, 1009704, 1009705, 1009706, 1009707, 1009708, 1009709);";
        $sqls[] = "DELETE FROM db_syscampo WHERE codcam IN (1009703, 1009704, 1009705, 1009706, 1009707, 1009708, 1009709, 1009710, 1009712);";
        $sqls[] = "DELETE FROM db_syscampodep WHERE codcam IN (1009703, 1009704, 1009706, 1009707, 1009708);";
        $sqls[] = "DELETE FROM db_syscampodef WHERE codcam IN (1009703, 1009704, 1009706, 1009707, 1009708);";

        $sqls[] = "delete from db_sysforkey where codarq = 1010276 ";
        $sqls[] = "delete from db_sysarqmod where codmod = 7  and  codarq = 1010276";
        $sqls[] = "delete from db_sysarqcamp where codarq = 1010276;";

        $sqls[] = "delete from db_syscampo where codcam = 1009711;";
        $sqls[] = "delete from db_sysprikey where codarq = 1010276;";


        $sqls[] = "delete from db_sysarquivo where codarq = 1010276";


        $sqls[] = "DELETE FROM  db_cadattdinamicoatributos WHERE  db109_sequencial IN(4024, 4025, 4026, 4027, 4023);";


        foreach ($sqls as $sql) {
            $this->execute($sql);
        }
    }
    
    public function downDDL()
    {
        $this->table('padroes', array('schema'=>'pessoal'))
             ->removeColumn('r02_nivel')
             ->removeColumn('r02_classe')
             ->removeColumn('r02_padraopai_regime')
             ->removeColumn('r02_padraopai_codigo')
             ->removeColumn('r02_padraopai_instit')
             ->save();
    }
}
