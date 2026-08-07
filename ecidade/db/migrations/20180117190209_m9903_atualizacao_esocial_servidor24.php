<?php

use Classes\PostgresMigration;

/**
 * Class M9903AtualizacaoEsocialServidor24
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 * @author Fábio Egidio <fabio.egidio@dbseller.com.br>
 */
class M9903AtualizacaoEsocialServidor24 extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL

            -- versão 2.3 para 2.4
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004025 ,3000800 ,'Transferência do empregado doméstico para outro representante da mesma unidade familiar.' ,'transferencia-do-empregado-domestico-para-outro-re' ,'false' ,0 ,'5' ,'tpAdmissao_5' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004026 ,3000802 ,'Teletrabalho, previsto no Inciso III do Art. 62 da CLT.' ,'teletrabalho-previsto-no-inciso-iii-do-art-62-da-c' ,'false' ,0 ,'4' ,'tpRegJor_4' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004028 ,3000850 ,'Não é contrato em tempo parcial' ,'nao-e-contrato-em-tempo-parcial' ,'false' ,0 ,'1' ,'tmpParc_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004029 ,3000850 ,'Limitado a 25 horas semanais' ,'limitado-a-25-horas-semanais' ,'false' ,0 ,'2' ,'tmpParc_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004030 ,3000850 ,'Limitado a 30 horas semanais' ,'limitado-a-30-horas-semanais' ,'false' ,0 ,'3' ,'tmpParc_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004031 ,3000850 ,'Limitado a 26 horas semanais' ,'limitado-a-26-horas-semanais' ,'false' ,0 ,'4' ,'tmpParc_4' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001027 ,2 ,3000188 ,'Data da transferência do empregado para o empregador declarante' ,'data-da-transferencia-do-empregado-para-o-empregad' ,'true' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'sucessaoVinc_dtTransf' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001028 ,2 ,3000188 ,'Observação' ,'observacao5a60bc1f86024' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'sucessaoVinc_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004033 ,3001028 ,'' ,'5a60bc1f873df' ,'true' ,0 ,'' ,'sucessaoVinc_observacao' );

            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000241 ,3000013 ,'Informações do empregado doméstico transferido' ,'informacoes-do-empregado-domestico-transferido' ,'transfDom' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001024 ,2 ,3000241 ,'Matrícula do trabalhador no representante anterior da unidade familiar' ,'mat-da-transferencia-do-vinculo-ao-novo-represent' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'transfDom_matricAnt' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004022 ,3001024 ,'' ,'5a60bc1d60e33' ,'true' ,0 ,'' ,'transfDom_matricAnt' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001023 ,2 ,3000241 ,'Preencher com o número do CPF do representante anterior da unidade familiar' ,'preencher-com-o-numero-do-cpf-do-representante-ant' ,'true' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfSubstituido' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004021 ,3001023 ,'' ,'5a60bc1d5ea5b' ,'true' ,0 ,'' ,'cpfSubstituido' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001025 ,2 ,3000241 ,'Data da transferência do vínculo ao novo representante da unidade familiar' ,'data-da-transferencia-do-vinculo-ao-n5a60bc1d61aaa' ,'true' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'dtTransf' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004032 ,3001027 ,'' ,'5a60bc1f8019d' ,'true' ,0 ,'' ,'sucessaoVinc_dtTransf' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004023 ,3001025 ,'' ,'5a60bc1d62b42' ,'true' ,0 ,'' ,'dtTransf' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000242 ,3000013 ,'Observações do contrato de trabalho' ,'observacoescontratotrabalho' ,'observacoes_1' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001026 ,2 ,3000242 ,'Observação relacionada ao contrato de trabalho' ,'observacao-relacionada-ao-contrato-de-trabalho' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'observacao_1_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004024 ,3001026 ,'' ,'5a60bc1d657e1' ,'true' ,0 ,'' ,'observacao_1_observacao' );


            update avaliacaoperguntaopcao set db104_sequencial = 3003153 , db104_avaliacaopergunta = 3000708 , db104_descricao = 'Residente fora do Brasil' , db104_identificador = 'residente-em-pais-fronteirico-ao-brasil' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '6' , db104_identificadorcampo = 'classTrabEstrang_6' where db104_sequencial = 3003153;
            update avaliacaopergunta set db103_sequencial = 3000796 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000172 , db103_descricao = 'Regime Previdenciário' , db103_identificador = '1293' , db103_obrigatoria = 'true' , db103_ativo = 'false' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'tpRegPrev' where db103_sequencial = 3000796; -- adiciona o identificador do campo
            update avaliacaopergunta set db103_sequencial = 3000832 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000182 , db103_descricao = 'Descrição do salário por tarefa ou variável e como este é calculado. Ex.: Comissões pagas no percentual de 10% sobre as vendas' , db103_identificador = 'dscSalVar' , db103_obrigatoria = 'false' , db103_ativo = 'false' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'dscSalVar' where db103_sequencial = 3000832;
            update avaliacaopergunta set db103_sequencial = 3000835 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000183 , db103_descricao = 'Contrato por prazo determinado contém cláusula assecuratória do direito recíproco de rescisão' , db103_identificador = 'clauAssec' , db103_obrigatoria = 'false' , db103_ativo = 'false' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'clauAssec' where db103_sequencial = 3000835;
            update avaliacaoperguntaopcao set db104_sequencial = 3003487 , db104_avaliacaopergunta = 3000835 , db104_descricao = 'Sim' , db104_identificador = 'sim59f21998e9e8d' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = 'S' , db104_identificadorcampo = 'clauAssec_S' where db104_sequencial = 3003487;
            update avaliacaoperguntaopcao set db104_sequencial = 3003488 , db104_avaliacaopergunta = 3000835 , db104_descricao = 'Não' , db104_identificador = 'nao59f21998ec3c2' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = 'N' , db104_identificadorcampo = 'clauAssec_N' where db104_sequencial = 3003488;

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003420);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003420;
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3003420;
            delete from avaliacaoperguntaopcao where db104_sequencial = 3003420;

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003508);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003508;
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3003508;
            delete from avaliacaoperguntaopcao where db104_sequencial = 3003508;

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003509);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003509;
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3003509;
            delete from avaliacaoperguntaopcao where db104_sequencial = 3003509;

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003521);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003521;
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3003521;
            delete from avaliacaoperguntaopcao where db104_sequencial = 3003521;

            delete from avaliacaopergunta where db103_sequencial = 3000855;

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003522);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3003522;
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3003522;
            delete from avaliacaoperguntaopcao where db104_sequencial = 3003522;

            -- versão 2.4 para 2.4.1
            update avaliacaopergunta set db103_identificadorcampo = 'clauAssec'   where db103_sequencial = 3000835;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'clauAssec_S' where db104_sequencial = 3003487;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'clauAssec_N' where db104_sequencial = 3003488;
            update avaliacaoperguntaopcao set db104_descricao = 'Residente fora do Brasil' where db104_sequencial = 3003153;
            update avaliacaogrupopergunta set db102_descricao = 'Informações do Documento Nacional de Identidade - DNI' where db102_sequencial = 3000151;
            update avaliacaopergunta set db103_descricao = 'Número do Documento Nacional de Identidade - DNI' where db103_sequencial = 3000673;

            update avaliacaopergunta set db103_sequencial = 3000853 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000188 , db103_descricao = 'CNPJ do Empregador Anterior' , db103_identificador = '1280' , db103_obrigatoria = 't' , db103_ativo = 'f' , db103_ordem = 1 , db103_tipo = 3 , db103_mascara = '' , db103_dblayoutcampo = 0 , db103_perguntaidentificadora = 'false' , db103_camposql = '' where db103_sequencial = 3000853;

            update avaliacaogrupopergunta set db102_descricao = 'Preenchimento exclusivo em caso de trabalhador doméstico e trabalhador temporário' where db102_sequencial = 3000185;

            update db_formulas set db148_formula = 'select case when z01_nomecomple = \'\' or z01_nomecomple is null then z01_nome else z01_nomecomple  end as z01_nome  from cgm inner join rhpessoal on rhpessoal.rh01_numcgm = cgm.z01_numcgm where rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR] ' where db148_nome = 'ESOCIAL_NOME_SERVIDOR';
            
            update avaliacaopergunta set db103_obrigatoria = 'true' where db103_sequencial IN(3000857,3000850);
            update avaliacaopergunta set db103_obrigatoria = 'false' where db103_sequencial IN(3000847);
            
            update avaliacaopergunta set db103_tipo = 2 where db103_sequencial = 3000844;
            
            /*RETRABALHOS*/
            -- Adiciona Grupo FGTS
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000243 ,3000013 ,'FGTS' ,'fgts2341241' ,'FGTS' ,1 );
            -- Move perguntas para o grupo FGTS
            update avaliacaopergunta set db103_avaliacaogrupopergunta = 3000243 where db103_sequencial in (3000806, 3000807);
            update avaliacaopergunta set db103_obrigatoria = 'f' where db103_sequencial = 3000661;
            update avaliacaopergunta set db103_tipo = 2 where db103_sequencial = 3000697;
            
            -- Adiciona Grupo Observacao 2 e pergunta para o mesmo grupo
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000244 ,3000013 ,'Observações do contrato de trabalho 2' ,'observacoescontratotrabalho2' ,'observacoes_2' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001029 ,2 ,3000244 ,'Observação relacionada ao contrato de trabalho 2' ,'observacao_relacionada_ao_contrato_de_trabalho_2' ,'t' ,'t' ,1 ,1 ,'' ,0 ,'false' ,'' ,'observacoes_2_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004038 ,3001029 ,'' ,'observacao_relacionada_ao_contrato_de_trabalho_2_2' ,'true' ,0 ,'' ,'observacoes_2_observacao' );
           
            -- Adiciona Grupo Observacao 3 e pergunta para o mesmo grupo
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000245 ,3000013 ,'Observações do contrato de trabalho 3' ,'observacoescontratotrabalho3' ,'observacoes_3' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001030 ,2 ,3000245 ,'Observação relacionada ao contrato de trabalho 3' ,'observacao_relacionada_ao_contrato_de_trabalho_3' ,'t' ,'t' ,1 ,1 ,'' ,0 ,'false' ,'' ,'observacoes_3_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004039 ,3001030 ,'' ,'observacao_relacionada_ao_contrato_de_trabalho_3_2' ,'true' ,0 ,'' ,'observacoes_3_observacao' );
            
            -- Atualiza mascara para integer da pergunta Mês relativo à data base da categoria profissional
            update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000804;
            
            -- Adiciona Grupo filiacao sindical 2 e pergunta para o mesmo grupo
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000246 ,3000013 ,'Filiação Sindical 2' ,'filiacao_sindical_2' ,'filiacaoSindical_2' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001031 ,2 ,3000246 ,'CNPJ do sindicato 2' ,'cnpj_do_sindicato_2' ,'t' ,'t' ,1 ,3 ,'' ,0 ,'false' ,'' ,'filiacaoSindical_2_cnpjSindTrab' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004041 ,3001031 ,'' ,'cnpj_do_sindicato_2_2' ,'true' ,0 ,'' ,'filiacaoSindical_2_cnpjSindTrab' );

            -- Atualiza formula dos cargos para buscar o codigo
            update db_formulas set db148_formula = 'select rh37_funcao as cargo from rhpessoalmov inner join rhfuncao on rh37_funcao = rh02_funcao and rh37_instit = rh02_instit where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) and rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) and rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]' where db148_nome = 'ESOCIAL_CARGO_SERVIDOR';
            -- Atualiza formula das funcoes para buscar o codigo
            update db_formulas set db148_formula = 'select rh04_codigo as funcao from rhpessoalmov inner join rhpescargo on rh20_seqpes = rh02_seqpes and rh20_instit = rh02_instit inner join rhcargo on rh04_codigo = rh20_cargo and rh04_instit = rh20_instit where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) and rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) and rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]' where db148_nome = 'ESOCIAL_FUNCAO_SERVIDOR';

            -- Ajusta identificador campo do grupo filiacao sindical
            update avaliacaogrupopergunta set db102_identificadorcampo = 'filiacaoSindical_1' where db102_sequencial = 3000187;
            update avaliacaopergunta set db103_identificadorcampo = 'filiacaoSindical_1_cnpjSindTrab' where db103_sequencial = 3000852;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'filiacaoSindical_1_cnpjSindTrab' where db104_sequencial = 3003518;

            --  Ordem
            update avaliacaogrupopergunta set db102_ordem =  1 where db102_sequencial = 3000148;
            update avaliacaogrupopergunta set db102_ordem =  2 where db102_sequencial = 3000149;
            update avaliacaogrupopergunta set db102_ordem =  3 where db102_sequencial = 3000150;
            update avaliacaogrupopergunta set db102_ordem =  4 where db102_sequencial = 3000151;
            update avaliacaogrupopergunta set db102_ordem =  5 where db102_sequencial = 3000152;
            update avaliacaogrupopergunta set db102_ordem =  6 where db102_sequencial = 3000153;
            update avaliacaogrupopergunta set db102_ordem =  7 where db102_sequencial = 3000154;
            update avaliacaogrupopergunta set db102_ordem =  8 where db102_sequencial = 3000155;
            update avaliacaogrupopergunta set db102_ordem =  9 where db102_sequencial = 3000156;
            update avaliacaogrupopergunta set db102_ordem = 10 where db102_sequencial = 3000157;
            update avaliacaogrupopergunta set db102_ordem = 11 where db102_sequencial = 3000158;
            update avaliacaogrupopergunta set db102_ordem = 12 where db102_sequencial = 3000159;
            update avaliacaogrupopergunta set db102_ordem = 13 where db102_sequencial = 3000160;
            update avaliacaogrupopergunta set db102_ordem = 14 where db102_sequencial = 3000161;
            update avaliacaogrupopergunta set db102_ordem = 15 where db102_sequencial = 3000162;
            update avaliacaogrupopergunta set db102_ordem = 16 where db102_sequencial = 3000163;
            update avaliacaogrupopergunta set db102_ordem = 17 where db102_sequencial = 3000164;
            update avaliacaogrupopergunta set db102_ordem = 18 where db102_sequencial = 3000165;
            update avaliacaogrupopergunta set db102_ordem = 19 where db102_sequencial = 3000166;
            update avaliacaogrupopergunta set db102_ordem = 20 where db102_sequencial = 3000167;
            update avaliacaogrupopergunta set db102_ordem = 21 where db102_sequencial = 3000168;
            update avaliacaogrupopergunta set db102_ordem = 22 where db102_sequencial = 3000169;
            update avaliacaogrupopergunta set db102_ordem = 23 where db102_sequencial = 3000170;
            update avaliacaogrupopergunta set db102_ordem = 24 where db102_sequencial = 3000171;
            update avaliacaogrupopergunta set db102_ordem = 25 where db102_sequencial = 3000172;
            update avaliacaogrupopergunta set db102_ordem = 26 where db102_sequencial = 3000243;
            update avaliacaogrupopergunta set db102_ordem = 27 where db102_sequencial = 3000173;
            update avaliacaogrupopergunta set db102_ordem = 28 where db102_sequencial = 3000174;
            update avaliacaogrupopergunta set db102_ordem = 29 where db102_sequencial = 3000175;
            update avaliacaogrupopergunta set db102_ordem = 30 where db102_sequencial = 3000176;
            update avaliacaogrupopergunta set db102_ordem = 31 where db102_sequencial = 3000177;
            update avaliacaogrupopergunta set db102_ordem = 32 where db102_sequencial = 3000178;
            update avaliacaogrupopergunta set db102_ordem = 33 where db102_sequencial = 3000179;
            update avaliacaogrupopergunta set db102_ordem = 34 where db102_sequencial = 3000180;
            update avaliacaogrupopergunta set db102_ordem = 35 where db102_sequencial = 3000181;
            update avaliacaogrupopergunta set db102_ordem = 36 where db102_sequencial = 3000182;
            update avaliacaogrupopergunta set db102_ordem = 37 where db102_sequencial = 3000183;
            update avaliacaogrupopergunta set db102_ordem = 38 where db102_sequencial = 3000184;
            update avaliacaogrupopergunta set db102_ordem = 39 where db102_sequencial = 3000185;
            update avaliacaogrupopergunta set db102_ordem = 40 where db102_sequencial = 3000186;
            update avaliacaogrupopergunta set db102_ordem = 41 where db102_sequencial = 3000187;
            update avaliacaogrupopergunta set db102_ordem = 42 where db102_sequencial = 3000246;
            update avaliacaogrupopergunta set db102_ordem = 43 where db102_sequencial = 3000242;
            update avaliacaogrupopergunta set db102_ordem = 44 where db102_sequencial = 3000244;
            update avaliacaogrupopergunta set db102_ordem = 45 where db102_sequencial = 3000245;
            update avaliacaogrupopergunta set db102_ordem = 46 where db102_sequencial = 3000188;
            update avaliacaogrupopergunta set db102_ordem = 47 where db102_sequencial = 3000241;
            update avaliacaogrupopergunta set db102_ordem = 48 where db102_sequencial = 3000189;
            update avaliacaogrupopergunta set db102_ordem = 49 where db102_sequencial = 3000190;

SQL;

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sql = <<<SQL

            -- versão 2.4.1 para 2.4
            update avaliacaopergunta set db103_identificadorcampo = 'clauAsseg' where db103_sequencial = 3000835;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'clauAsseg_S' where db104_sequencial = 3003487;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'clauAsseg_N' where db104_sequencial = 3003488;
            update avaliacaoperguntaopcao set db104_descricao = 'Residente em país fronteiriço ao Brasil' where db104_sequencial = 3003153;
            update avaliacaogrupopergunta set db102_descricao = 'Registro de Identificação Civil (RIC)' where db102_sequencial = 3000151;
            update avaliacaopergunta set db103_descricao = 'Número' where db103_sequencial = 3000673;


            -- versão 2.4 para 2.3
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004025,3004026,3004028,3004029,3004030,3004031,3004033,3004022,3004021,3004032,3004023,3004024));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004025,3004026,3004028,3004029,3004030,3004031,3004033,3004022,3004021,3004032,3004023,3004024);
            
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN(3004025,3004026,3004028,3004029,3004030,3004031,3004033);
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN(3004022,3004021,3004032,3004023,3004024);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN(3001027, 3001028);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN(3001023, 3001024, 3001025, 3001026);
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial IN(3000241, 3000242);

            UPDATE avaliacaoperguntaopcao SET db104_descricao = 'Residente em país fronteiriço ao Brasil' WHERE db104_sequencial = 3003153;
            UPDATE avaliacaopergunta SET db103_descricao = 'Descrição do salário variável e como este é calculado. Ex.: Comissões pagas no percentual de 10% sobre as vendas', db103_identificador = '1341' WHERE db103_sequencial = 3000832;
            UPDATE avaliacaopergunta SET db103_identificador = '1345', db103_identificadorcampo = 'clauAsseg', db103_descricao = 'Contrato por prazo determinado contém cláusula asseguratória do direito recíproco de rescisão' WHERE db103_sequencial = 3000835;
            UPDATE avaliacaoperguntaopcao SET db104_identificadorcampo = 'clauAsseg_S' WHERE db104_sequencial = 3003487;
            UPDATE avaliacaoperguntaopcao SET db104_identificadorcampo = 'clauAsseg_N' WHERE db104_sequencial = 3003488;

            INSERT INTO avaliacaoperguntaopcao VALUES (3003420, 3000818, 'Tomou posse mas não entrou exercício', false, 'tomou-posse-mas-nao-entrou-exercicio', 0, '3', 'indProvim_3');
            INSERT INTO avaliacaoperguntaopcao VALUES (3003508, 3000850, 'Sim', false, 'sim59f219994bad1', 0, 'S', 'tmpParc_S');
            INSERT INTO avaliacaoperguntaopcao VALUES (3003509, 3000850, 'Não', false, 'nao59f219994db82', 0, 'N', 'tmpParc_N');
            INSERT INTO avaliacaopergunta VALUES (3000855, 2, 3000188, 'Data de início do cinculo trabalhista no Empregador Anterior', true, false, 3, 1282, 5, '', 0, false, '', 'dtIniVinculo');
            INSERT INTO avaliacaoperguntaopcao VALUES (3003521, 3000855, '', true, '59f2199974ce2', 0, '', 'dtIniVinculo');

            INSERT INTO avaliacaoperguntaopcao VALUES (3003522, 3000856, '', true, '59f21999795ef', 0, '', 'sucessaoVinc_observacao');

            -- ordem
            update avaliacaogrupopergunta set db102_ordem =  1 where db102_avaliacao = 3000013;
            update avaliacaogrupopergunta set db102_descricao = '* Preenchimento exclusivo em caso de trabalhador doméstico e trabalhador temporário' where db102_sequencial = 3000185;
            -- formula
            update db_formulas set db148_formula = 'select z01_nome from cgm inner join rhpessoal on rhpessoal.rh01_numcgm = cgm.z01_numcgm where rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR] ' where db148_nome = 'ESOCIAL_NOME_SERVIDOR';

            /*Retrabalhos*/
            -- Volta perguntas para o grupo anterior
            update avaliacaopergunta set db103_avaliacaogrupopergunta = 3000173 where db103_sequencial in (3000806, 3000807);
            -- Deleta Grupo FGTS
            delete from avaliacaogrupopergunta where db102_sequencial = 3000243;
            update avaliacaopergunta set db103_obrigatoria = 't' where db103_sequencial = 3000661;
            update avaliacaopergunta set db103_tipo = 1 where db103_sequencial = 3000697;


            -- Deleta Respostas da pergunta Observacao 2
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004038));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004038);
            -- Deleta opcao da pergunta Observacao 2
            delete from avaliacaoperguntaopcao where db104_sequencial = 3004038;
            -- Deleta pergunta Observacao 2
            delete from avaliacaopergunta where db103_sequencial = 3001029;
            -- Deleta Grupo Observacao 2
            delete from avaliacaogrupopergunta where db102_sequencial = 3000244;

            -- Deleta Respostas da pergunta Observacao 3
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004039));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004039);
            -- Deleta opcao da pergunta Observacao 3
            delete from avaliacaoperguntaopcao where db104_sequencial = 3004039;
            -- Deleta pergunta Observacao 3
            delete from avaliacaopergunta where db103_sequencial = 3001030;
            -- Deleta Grupo Observacao 3
            delete from avaliacaogrupopergunta where db102_sequencial = 3000245;

            -- Deleta Respostas da pergunta Filiacao Sindical 2
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004041));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao IN(3004041);
            -- Deleta opcao da pergunta Filiacao Sindical 2
            delete from avaliacaoperguntaopcao where db104_sequencial = 3004041;
            -- Deleta pergunta Filiacao Sindical 2
            delete from avaliacaopergunta where db103_sequencial = 3001031;
            -- Deleta Grupo Filiacao Sindical 2
            delete from avaliacaogrupopergunta where db102_sequencial = 3000246;

            -- Volta mascara para string da pergunta Mês relativo à data base da categoria profissional
            update avaliacaopergunta set db103_tipo = 1 where db103_sequencial = 3000804;

            -- Retorna formula dos cargos para buscar a descricao
            update db_formulas set db148_formula = 'select rh37_descr as cargo from rhpessoalmov inner join rhfuncao on rh37_funcao = rh02_funcao and rh37_instit = rh02_instit where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) and rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) and rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]' where db148_nome = 'ESOCIAL_CARGO_SERVIDOR';
            -- Retorna formula das funcoes para buscar a descricao
            update db_formulas set db148_formula = 'select rh04_descr as funcao from rhpessoalmov inner join rhpescargo on rh20_seqpes = rh02_seqpes and rh20_instit = rh02_instit inner join rhcargo on rh04_codigo = rh20_cargo and rh04_instit = rh20_instit where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) and rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) and rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]' where db148_nome = 'ESOCIAL_FUNCAO_SERVIDOR';

            -- Retorna identificador campo do grupo filiacao sindical
            update avaliacaogrupopergunta set db102_identificadorcampo = 'filiacaoSindical' where db102_sequencial = 3000187;
            update avaliacaopergunta set db103_identificadorcampo = 'cnpjSindTrab' where db103_sequencial = 3000852;
            update avaliacaoperguntaopcao set db104_identificadorcampo = 'cnpjSindTrab' where db104_sequencial = 3003518;


SQL;

        $this->execute($sql);
    }
}
