<?php

use Classes\PostgresMigration;

class M18528AtualizacaoTabelaIrrfEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            update avaliacaoperguntaopcao set db104_descricao = '83: Demandas judiciais - Compensação judicial de anos anteriores' where db104_sequencial = 3003807;
            update avaliacaoperguntaopcao set db104_descricao = '82: Demandas judiciais - Compensação judicial do ano calendário' where db104_sequencial = 3003808;
            update avaliacaoperguntaopcao set db104_descricao = '81: Demandas judiciais - Depósito judicial' where db104_sequencial = 3003809;
            update avaliacaoperguntaopcao set db104_descricao = '79: Rendimento não tributável-Outras isenções' where db104_sequencial = 3003810;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001336 ,3000948 ,'701: Rendimento não tributável - Parte não tributável do valor de serviço de transporte de passageiros ou cargas' ,'rendimento-nao-tributavel-transp-passageiros-carga' ,'false' ,701 ,'701' ,'codIncIRRF_701' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001337 ,3000948 ,'700: Rendimento não tributável - Auxílio moradia' ,'rendimento-nao-tributavel-auxilio-moradia' ,'false' ,700 ,'700' ,'codIncIRRF_700' );
            update avaliacaoperguntaopcao set db104_descricao = '78: Rendimento não tributável - Valores pagos a titular ou sócio de microempresa ou empresa de pequeno porte, exceto pró-labore e alugueis' where db104_sequencial = 3003811;
            update avaliacaoperguntaopcao set db104_descricao = '77: Rendimento não tributável-Rendimento de beneficiário com moléstia grave ou acidente em serviço-13º salário' where db104_sequencial = 3003812;
            update avaliacaoperguntaopcao set db104_descricao = '76: Rendimento não tributável-Rendimento de beneficiário com moléstia grave ou acidente em serviço-Remuneração mensal' where db104_sequencial = 3003813;
            update avaliacaoperguntaopcao set db104_descricao = '75: Rendimento não tributável - Abono pecuniário' where db104_sequencial = 3003814;
            update avaliacaoperguntaopcao set db104_descricao = '74: Rendimento não tributável - Indenização e rescisão de contrato, inclusive a título de PDV e acidentes de trabalho' where db104_sequencial = 3003815;
            update avaliacaoperguntaopcao set db104_descricao = '73: Rendimento não tributável - Ajuda de custo' where db104_sequencial = 3003816;
            update avaliacaoperguntaopcao set db104_descricao = '72: Rendimento não tributável - Diárias' where db104_sequencial = 3003817;
            update avaliacaoperguntaopcao set db104_descricao = '71: Rendimento não tributável - Parcela Isenta 65 anos - 13° salário' where db104_sequencial = 3003818;
            update avaliacaoperguntaopcao set db104_descricao = '70: Rendimento não tributável - Parcela Isenta 65 anos - Remuneração mensal' where db104_sequencial = 3003819;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001338 ,3000948 ,'67: Deduções IRRF - Plano privado coletivo de assistência à saúde' ,'deducoes-irrf-plano-privado-coletivo-assist-saude' ,'false' ,67 ,'67' ,'codIncIRRF_67' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001339 ,3000948 ,'66: Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - Férias' ,'deducoes-irrf-fundo-de-aponsent-indiv-fapi-ferias' ,'false' ,66 ,'66' ,'codIncIRRF_66' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001340 ,3000948 ,'65: Deduções IRRF - Fundação de previdência complementar do servidor público - Férias' ,'deducoes-irrf-fundacao-de-previd-compl-ferias' ,'false' ,65 ,'65' ,'codIncIRRF_65' );
            update avaliacaoperguntaopcao set db104_descricao = '64: Deduções IRRF - Fundação de Previdência Complementar do Servidor Público - 13° salário' where db104_sequencial = 3003820;
            update avaliacaoperguntaopcao set db104_descricao = '63: Deduções IRRF - Fundação de Previdência Complementar do Servidor Público - Remuneração mensal' where db104_sequencial = 3003821;
            update avaliacaoperguntaopcao set db104_descricao = '62: Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - 13° salário' where db104_sequencial = 3003822;
            update avaliacaoperguntaopcao set db104_descricao = '61: Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - Remuneração Mensal' where db104_sequencial = 3003823;
            update avaliacaoperguntaopcao set db104_descricao = '55: Deduções IRRF - Pensão Alimentícia - RRA' where db104_sequencial = 3003824;
            update avaliacaoperguntaopcao set db104_descricao = '54: Deduções IRRF - Pensão Alimentícia - PLR' where db104_sequencial = 3003825;
            update avaliacaoperguntaopcao set db104_descricao = '53: Deduções IRRF - Pensão Alimentícia - Férias' where db104_sequencial = 3003826;
            update avaliacaoperguntaopcao set db104_descricao = '52: Deduções IRRF - Pensão Alimentícia - 13° salário' where db104_sequencial = 3003827;
            update avaliacaoperguntaopcao set db104_descricao = '51: Deduções IRRF - Pensão Alimentícia - Remuneração mensal' where db104_sequencial = 3003828;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001341 ,3000948 ,'48: Deduções IRRF - Previdência privada - Férias' ,'deducoes-irrf-previdencia-privada-ferias' ,'false' ,48 ,'48' ,'codIncIRRF_48' );
            update avaliacaoperguntaopcao set db104_descricao = '47: Deduções IRRF - Previdência Privada - 13° salário' where db104_sequencial = 3003829;
            update avaliacaoperguntaopcao set db104_descricao = '46: Deduções IRRF - Previdência Privada - salário mensal' where db104_sequencial = 3003830;
            update avaliacaoperguntaopcao set db104_descricao = '44: Deduções IRRF - PSO - RRA' where db104_sequencial = 3003831;
            update avaliacaoperguntaopcao set db104_descricao = '43: Deduções IRRF - PSO - Férias' where db104_sequencial = 3003832;
            update avaliacaoperguntaopcao set db104_descricao = '42: Deduções IRRF - PSO - 13° salário' where db104_sequencial = 3003833;
            update avaliacaoperguntaopcao set db104_descricao = '41: Deduções IRRF - Previdência Social Oficial - PSO - Remuner. mensal' where db104_sequencial = 3003834;
            update avaliacaoperguntaopcao set db104_descricao = '35: Retenções do IRRF efetuadas sobre - RRA' where db104_sequencial = 3003835;
            update avaliacaoperguntaopcao set db104_descricao = '34: Retenções do IRRF efetuadas sobre - PLR' where db104_sequencial = 3003836;
            update avaliacaoperguntaopcao set db104_descricao = '33: Retenções do IRRF efetuadas sobre - Férias' where db104_sequencial = 3003837;
            update avaliacaoperguntaopcao set db104_descricao = '32: Retenções do IRRF efetuadas sobre - 13o Salário' where db104_sequencial = 3003838;
            update avaliacaoperguntaopcao set db104_descricao = '31: Retenções do IRRF efetuadas sobre - Remuneração mensal' where db104_sequencial = 3003839;
            update avaliacaoperguntaopcao set db104_descricao = '15: Rendimentos tributáveis base de IRRF - RRA' where db104_sequencial = 3003840;
            update avaliacaoperguntaopcao set db104_descricao = '14: Rendimentos tributáveis base de IRRF - PLR' where db104_sequencial = 3003841;
            update avaliacaoperguntaopcao set db104_descricao = '13: Rendimentos tributáveis base de IRRF - Férias' where db104_sequencial = 3003842;
            update avaliacaoperguntaopcao set db104_descricao = '12: Rendimentos tributáveis base de IRRF - 13o Salário' where db104_sequencial = 3003843;
            update avaliacaoperguntaopcao set db104_descricao = '11: Rendimentos tributáveis base de IRRF - Remuneração mensal' where db104_sequencial = 3003844;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001342 ,3000948 ,'9: Verba transitada pela folha de pagamento de natureza diversa de rendimento ou retenção/isenção/dedução de IR ' ,'verba-transitada-folha-natureza-diversa' ,'false' ,9 ,'9' ,'codIncIRRF_9' );
            update avaliacaoperguntaopcao set db104_descricao = '1: Rendimento não tributável em função de acordos internacionais de bitributação' where db104_sequencial = 3003845;
            update avaliacaoperguntaopcao set db104_descricao = '0: Rendimento não tributável' where db104_sequencial = 3003846;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001343 ,3000948 ,'9011: Exigibilidade suspensa - Rendimento tributável (base de cálculo do IR) - Remuneração mensal' ,'exigibilidade-suspensa-rend-trib-remuneracao-men' ,'false' ,9011 ,'9011' ,'codIncIRRF_9011' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001344 ,3000948 ,'9012: Exigibilidade suspensa - Rendimento tributável (base de cálculo do IR) - 13º salário' ,'exigibilidade-suspensa-rend-trib-13-salario' ,'false' ,9012 ,'9012' ,'codIncIRRF_9012' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001345 ,3000948 ,'9013: Exigibilidade suspensa - Rendimento tributável (base de cálculo do IR) - Férias' ,'exigibilidade-suspensa-rendimento-trib-ferias' ,'false' ,9013 ,'9013' ,'codIncIRRF_9013' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001346 ,3000948 ,'9014: Exigibilidade suspensa - Rendimento tributável (base de cálculo do IR) - PLR' ,'exigibilidade-suspensa-rendimento-tributavel-plr' ,'false' ,9014 ,'9014' ,'codIncIRRF_9014' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001347 ,3000948 ,'9031: Exigibilidade suspensa - Retenção do IRRF: Remuneração mensal' ,'exigibilidade-suspensa-ret-irrf-remuneracao-mensal' ,'false' ,9031 ,'9031' ,'codIncIRRF_9031' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001348 ,3000948 ,'9032: Exigibilidade suspensa - Retenção do IRRF: 13º salário' ,'exigibilidade-suspensa-retencao-irrf-13-salrio' ,'false' ,9032 ,'9032' ,'codIncIRRF_9032' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001349 ,3000948 ,'9033: Exigibilidade suspensa - Retenção do IRRF: Férias' ,'exigibilidade-suspensa-retencao-irrf-ferias' ,'false' ,9033 ,'9033' ,'codIncIRRF_9033' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001350 ,3000948 ,'9034: Exigibilidade suspensa - Retenção do IRRF efetuada sobre: PLR' ,'exigibilidade-suspensa-retencao-irrf-plr' ,'false' ,9034 ,'9034' ,'codIncIRRF_9034' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001351 ,3000948 ,'9831: Exigibilidade suspensa - Retenção do IRRF: Depósito judicial - Remuneração mensal' ,'exigibilidade-susp-ret-irrf-remun-dep-jud-mensal' ,'false' ,9831 ,'9831' ,'codIncIRRF_9831' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001352 ,3000948 ,'9832: Exigibilidade suspensa - Retenção do IRRF: Depósito judicial - 13º salário' ,'exigibilidade-suspensa-ret-irrf-dep-jud-13-salrio' ,'false' ,9832 ,'9832' ,'codIncIRRF_9832' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001353 ,3000948 ,'9833: Exigibilidade suspensa - Retenção do IRRF: Depósito judicial - Férias' ,'exigibilidade-suspensa-ret-irrf-dep-jud-ferias' ,'false' ,9833 ,'9833' ,'codIncIRRF_9833' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001354 ,3000948 ,'9834: Exigibilidade suspensa - Retenção do IRRF: Depósito judicial - PLR' ,'exigibilidade-suspensa-ret-irrf-dep-jud-plr' ,'false' ,9834 ,'9834' ,'codIncIRRF_9834' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001355 ,3000948 ,'9041: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Previdência Social Oficial - PSO - Remuneração mensal' ,'exigibilidade-susp-ded-calc-irrf-pso-remun-mensal' ,'false' ,9041 ,'9041' ,'codIncIRRF_9041' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001356 ,3000948 ,'9042: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: PSO - 13º salário' ,'exigibilidade-susp-ded-calc-irrf-pso-13-salario' ,'false' ,9042 ,'9042' ,'codIncIRRF_9042' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001357 ,3000948 ,'9043: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: PSO - Férias' ,'exigibilidade-susp-ded-calc-irrf-pso-ferias' ,'false' ,9043 ,'9043' ,'codIncIRRF_9043' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001358 ,3000948 ,'9046: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Previdência privada - Salário mensal' ,'exigibilidade-susp-ded-calc-irrf-prev-priv-sal-men' ,'false' ,9046 ,'9046' ,'codIncIRRF_9046' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001359 ,3000948 ,'9047: Exigibilidade suspensa-Dedução da base de cálculo do IRRF-Previdência privada-13º salário' ,'exigibilidade-susp-ded-calc-irrf-prev-priv-13-sal' ,'false' ,9047 ,'9047' ,'codIncIRRF_9047' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001360 ,3000948 ,'9048: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Previdência privada - Férias' ,'exigibilidade-susp-ded-calc-irrf-prev-priv-ferias' ,'false' ,9048 ,'9048' ,'codIncIRRF_9048' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001361 ,3000948 ,'9051: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Pensão alimentícia - Remuneração mensal' ,'exig-susp-ded-calc-irrf-pen-alimen-remun-men' ,'false' ,9051 ,'9051' ,'codIncIRRF_9051' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001362 ,3000948 ,'9052: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Pensão alimentícia - 13º salário' ,'exigibilidade-susp-ded-calc-irrf-pen-alimen-13-sal' ,'false' ,9052 ,'9052' ,'codIncIRRF_9052' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001363 ,3000948 ,'9053: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Pensão alimentícia - Férias' ,'exigibilidade-susp-ded-calc-irrf-pen-alimen-ferias' ,'false' ,9053 ,'9053' ,'codIncIRRF_9053' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001364 ,3000948 ,'9054: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Pensão alimentícia - PLR' ,'exigibilidade-susp-ded-calculo-irrf-pen-alimen-plr' ,'false' ,9054 ,'9054' ,'codIncIRRF_9054' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001365 ,3000948 ,'9061: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundo de Aposentadoria Programada Individual - FAPI - Remuneração mensal' ,'exig-susp-ded-calc-irrf-fundo-fapi-remun-men' ,'false' ,9061 ,'9061' ,'codIncIRRF_9061' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001366 ,3000948 ,'9062: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundo de Aposentadoria Programada Individual - FAPI - 13º salário' ,'exigibilidade-susp-ded-calc-irrf-fapi-13-salario' ,'false' ,9062 ,'' ,'codIncIRRF_9062' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001367 ,3000948 ,'9063: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundação de previdência complementar do servidor público - Remuneração mensal' ,'exigibilidade-susp-ded-calc-irrf-fpcsp-remun-men' ,'false' ,9063 ,'9063' ,'codIncIRRF_9063' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001368 ,3000948 ,'9064: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundação de previdência complementar do servidor público - 13º salário' ,'exigibilidade-susp-ded-calc-irrf-fpcsp-13-salario' ,'false' ,9064 ,'9064' ,'codIncIRRF_9064' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001369 ,3000948 ,'9065: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundação de previdência complementar do servidor público - Férias' ,'exigibilidade-susp-ded-calc-irrf-fpcsp-ferias' ,'false' ,9065 ,'9065' ,'codIncIRRF_9065' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001370 ,3000948 ,'9066: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Fundo de Aposentadoria Programada Individual - FAPI - Férias' ,'exigibilidade-susp-ded-calc-irrf-fapi-ferias' ,'false' ,9066 ,'9066' ,'codIncIRRF_9066' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001371 ,3000948 ,'9067: Exigibilidade suspensa - Dedução da base de cálculo do IRRF: Plano privado coletivo de assistência à saúde' ,'exigibilidade-susp-ded-calc-irrf-assist-saude' ,'false' ,9067 ,'9067' ,'codIncIRRF_9067' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001372 ,3000948 ,'9082: Compensação judicial do ano-calendário' ,'compensacao-judicial-ano-calendario' ,'false' ,9082 ,'9082' ,'codIncIRRF_9082' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001373 ,3000948 ,'9083: Compensação judicial de anos anterires' ,'compensacao-judicial-anos-anteriores' ,'false' ,9083 ,'9083' ,'codIncIRRF_9083' );
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $opcoes = [4001336, 4001337, 4001338, 4001339, 4001340, 4001341, 4001342, 4001343, 4001344, 4001345, 4001346, 4001347, 4001348, 4001349, 4001350, 4001351, 4001352, 4001353, 4001354, 4001355, 4001356, 4001357, 4001358, 4001359, 4001360, 4001361, 4001362, 4001363, 4001364, 4001365, 4001366, 4001367, 4001368, 4001369, 4001370, 4001371, 4001372, 4001373];
        foreach ($opcoes as $opcao) {
            $this->deletaOpcao($opcao);
        }

        $sql = <<<SQL
            update avaliacaoperguntaopcao set db104_descricao = 'Demandas judiciais - Compensação judicial de anos anteriores' where db104_sequencial = 3003807;
            update avaliacaoperguntaopcao set db104_descricao = 'Demandas judiciais - Compensação judicial do ano calendário' where db104_sequencial = 3003808;
            update avaliacaoperguntaopcao set db104_descricao = 'Demandas judiciais - Depósito judicial' where db104_sequencial = 3003809;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Outras isenções (o nome da rubrica deve ser claro para identificação da natureza dos valores) Demandas Judiciais' where db104_sequencial = 3003810;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Valores pagos a titular ou sócio de microempresa ou empresa de pequeno porte, exceto pró-labore e alugueis' where db104_sequencial = 3003811;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Pensão, aposentadoria ou reforma por moléstia grave ou acidente em serviço - 13° salário' where db104_sequencial = 3003812;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Pensão, aposentadoria ou reforma por moléstia grave ou acidente em serviço - Remuneração Mensal' where db104_sequencial = 3003813;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Abono pecuniário' where db104_sequencial = 3003814;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Indenização e rescisão de contrato, inclusive a título de PDV e acidentes de trabalho' where db104_sequencial = 3003815;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Ajuda de custo' where db104_sequencial = 3003816;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Diárias' where db104_sequencial = 3003817;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Parcela Isenta 65 anos - 13° salário' where db104_sequencial = 3003818;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável - Parcela Isenta 65 anos - Remuneração mensal' where db104_sequencial = 3003819;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Fundação de Previdência Complementar do Servidor Público Federal - Funpresp - 13° salário Isenções do IRRF' where db104_sequencial = 3003820;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Fundação de Previdência Complementar do Servidor Público Federal - Funpresp - Remuneração mensal' where db104_sequencial = 3003821;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - 13° salário' where db104_sequencial = 3003822;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - Remuneração Mensal' where db104_sequencial = 3003823;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Pensão Alimentícia - RRA' where db104_sequencial = 3003824;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Pensão Alimentícia - PLR' where db104_sequencial = 3003825;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Pensão Alimentícia - Férias' where db104_sequencial = 3003826;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Pensão Alimentícia - 13° salário' where db104_sequencial = 3003827;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Pensão Alimentícia - Remuneração mensal' where db104_sequencial = 3003828;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Previdência Privada - 13° salário' where db104_sequencial = 3003829;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Previdência Privada - salário mensal' where db104_sequencial = 3003830;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - PSO - RRA' where db104_sequencial = 3003831;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - PSO - Férias' where db104_sequencial = 3003832;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - PSO - 13° salário' where db104_sequencial = 3003833;
            update avaliacaoperguntaopcao set db104_descricao = 'Deduções IRRF - Previdência Social Oficial - PSO - Remuner. mensal' where db104_sequencial = 3003834;
            update avaliacaoperguntaopcao set db104_descricao = 'Retenções do IRRF efetuadas sobre - RRA' where db104_sequencial = 3003835;
            update avaliacaoperguntaopcao set db104_descricao = 'Retenções do IRRF efetuadas sobre - PLR' where db104_sequencial = 3003836;
            update avaliacaoperguntaopcao set db104_descricao = 'Retenções do IRRF efetuadas sobre - Férias' where db104_sequencial = 3003837;
            update avaliacaoperguntaopcao set db104_descricao = 'Retenções do IRRF efetuadas sobre - 13o Salário' where db104_sequencial = 3003838;
            update avaliacaoperguntaopcao set db104_descricao = 'Retenções do IRRF efetuadas sobre - Remuneração mensal' where db104_sequencial = 3003839;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimentos tributáveis base de IRRF - RRA' where db104_sequencial = 3003840;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimentos tributáveis base de IRRF - PLR' where db104_sequencial = 3003841;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimentos tributáveis base de IRRF - Férias' where db104_sequencial = 3003842;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimentos tributáveis base de IRRF - 13o Salário' where db104_sequencial = 3003843;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimentos tributáveis base de IRRF - Remuneração mensal' where db104_sequencial = 3003844;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável em função de acordos internacionais de bitributação' where db104_sequencial = 3003845;
            update avaliacaoperguntaopcao set db104_descricao = 'Rendimento não tributável' where db104_sequencial = 3003846;
SQL;
        $this->execute($sql);
    }

    private function deletaOpcao($opcao)
    {
        $sql = <<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in({$opcao}));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in({$opcao});
            delete from habitacao.avaliacaoperguntaopcao where db104_sequencial in ({$opcao});

SQL;
        $this->execute($sql);
    }
}
