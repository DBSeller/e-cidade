<?php

use Classes\PostgresMigration;
use PhpOffice\PhpWord\Exception\Exception;

class M13291AlteraAbaInfraestrutura extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
            update avaliacao set db101_sequencial = 3000000 , db101_avaliacaotipo = 3 , db101_descricao = 'CENSO ESCOLA' , db101_identificador = 'censo-escola' , db101_ativo = 'true' , db101_permiteedicao = 'false' where db101_sequencial = 3000000;
            update avaliacaogrupopergunta set db102_sequencial = 3000001 , db102_avaliacao = 3000000 , db102_descricao = 'INFRA-ESTRUTURA' , db102_identificador = 'infraestrutura' , db102_identificadorcampo = 'infra-estrutura' , db102_ordem = 1 where db102_sequencial = 3000001;
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000229 ,3 ,3000001 ,'Instrumentos, materiais socioculturais e/ou pedagógicos em uso na escola para o desenvolvimento de atividades de ensino/aprendizagem' ,'instrumentos-materiais-socioculturais-eou-pedagogi' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'0' ,'material_ensino' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000229;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001195 ,4000229 ,'Acervo multimídia' ,'acervo-multimidia' ,'false' ,0 ,'' ,'acervo_multimidia' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001196 ,4000229 ,'Brinquedos para educação infantil' ,'brinquedos-para-educacao-infantil' ,'false' ,0 ,'' ,'brinquedos_educacao_infantil' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001197 ,4000229 ,'Conjunto de materiais científicos' ,'conjunto-de-materiais-cientificos' ,'false' ,0 ,'' ,'conjunto_materiais_cientificos' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001198 ,4000229 ,'Equipamento para amplificação e difusão de som/áudio' ,'equipamento-para-amplificacao-e-difusao-de-somaudi' ,'false' ,0 ,'' ,'equipamento_amplificacao_ou_difusao_audio' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001199 ,4000229 ,'Instrumentos musicais para conjunto, banda/fanfarra e/ou aulas de música' ,'instrumentos-musicais-para-conjunto-bandafanfarra-' ,'false' ,0 ,'' ,'instrumentos_musicais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001200 ,4000229 ,'Jogos educativos' ,'jogos-educativos' ,'false' ,0 ,'' ,'jogos_educativos' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001201 ,4000229 ,'Materiais para atividades culturais e artísticas' ,'materiais-para-atividades-culturais-e-artisticas' ,'false' ,0 ,'' ,'materiais_atividades_culturais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001202 ,4000229 ,'Materiais para prática desportiva e recreação' ,'materiais-para-pratica-desportiva-e-recreacao' ,'false' ,0 ,'' ,'materiais_esportivos_ou_recreacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001203 ,4000229 ,'Materiais pedagógicos para a educação escolar indígena' ,'materiais-pedagogicos-para-a-educacao-escolar-indi' ,'false' ,0 ,'' ,'material_educacao_indigena' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001204 ,4000229 ,'Materiais pedagógicos para a educação das relações étnicos raciais' ,'materiais-pedagogicos-para-a-educacao-das-relacoes' ,'false' ,0 ,'' ,'material_educacao_etnico_racial' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001205 ,4000229 ,'Materiais pedagógicos para a educação do campo' ,'materiais-pedagogicos-para-a-educacao-do-campo' ,'false' ,0 ,'' ,'material_educacao_campo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000230 ,3 ,3000001 ,'Rede local de interligação de computadores' ,'rede-local-de-interligacao-de-computadores' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'0' ,'rede_local_computadores' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000230;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001206 ,4000230 ,'A cabo' ,'a-cabo' ,'false' ,0 ,'' ,'rede_cabo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001207 ,4000230 ,'Wireless' ,'wireless' ,'false' ,0 ,'' ,'rede_wireless' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001208 ,4000230 ,'Não há rede local interligando computadores' ,'nao-ha-rede-local-interligando-computadores' ,'false' ,0 ,'' ,'rede_nada' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000231 ,3 ,3000001 ,'Equipamentos que os aluno(a)s usam para acessar a internet da escola' ,'equipamentos-que-os-alunoas-usam-para-acessar-a-in' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'0' ,'equipamentos_alunos_usam_acessar_internet' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000231;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001209 ,4000231 ,'Computadores de mesa, portáteis e tablets da escola (no laboratório de informática, biblioteca, sala de aula etc.)' ,'computadores-de-mesa-portateis-e-tablets-da-escola' ,'false' ,0 ,'' ,'equipamentos_da_escola' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001210 ,4000231 ,'Dispositivos pessoais (computadores portáteis, celulares, tablets etc.)' ,'dispositivos-pessoais-computadores-portateis-celul' ,'false' ,0 ,'' ,'equipamentos_pessoais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001211 ,4000231 ,'Internet banda larga' ,'internet-banda-larga' ,'false' ,0 ,'' ,'internet_banda_larga' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000232 ,2 ,3000001 ,'Número de salas de aula utilizadas na escola dentro do prédio escolar' ,'numero-de-salas-de-aula-utilizadas-na-escola-dentr' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'0' ,'numero_salas_utilizadas_dentro_do_predio' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000232;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001212 ,4000232 ,'' ,'5cb8afcc92b6f' ,'true' ,0 ,'' ,'numero_salas_utilizadas_dentro_do_predio' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000233 ,2 ,3000001 ,'Número de salas de aula utilizadas na escola fora do prédio escolar' ,'numero-de-salas-de-aula-utilizadas-na-escola-fora-' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'0' ,'numero_salas_utilizadas_fora_do_predio' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000233;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001213 ,4000233 ,'' ,'5cb8afcc96297' ,'true' ,0 ,'' ,'numero_salas_utilizadas_fora_do_predio' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000234 ,2 ,3000001 ,'Número de salas de aula climatizadas (ar condicionado, aquecedor ou climatizador)' ,'numero-de-salas-de-aula-climatizadas-ar-condiciona' ,'false' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'0' ,'numero_salas_climatizadas' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000234;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001214 ,4000234 ,'' ,'5cb8afcc980f9' ,'true' ,0 ,'' ,'numero_salas_climatizadas' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000235 ,2 ,3000001 ,'Número de salas de aula com acessibilidade para pessoas com deficiência ou mobilidade reduzida' ,'numero-de-salas-de-aula-com-acessibilidade-para-pe' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'0' ,'numero_salas_acessibilidade' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000235;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001215 ,4000235 ,'' ,'5cb8afcc994e9' ,'true' ,0 ,'' ,'numero_salas_acessibilidade' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000236 ,3 ,3000001 ,'Recursos de acessibilidade para pessoas com deficiência ou mobilidade reduzida nas vias de circulação internas na escola' ,'recursos-de-acessibilidade-para-pessoas-com-defici' ,'true' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'0' ,'recursos_acessibilidade_deficiencia' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000236;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001216 ,4000236 ,'Corrimão e guarda-corpos' ,'corrimao-e-guardacorpos' ,'false' ,0 ,'' ,'corrimao_e_guarda_copos' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001217 ,4000236 ,'Elevador' ,'elevador' ,'false' ,0 ,'' ,'elevador' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001218 ,4000236 ,'Pisos táteis' ,'pisos-tateis' ,'false' ,0 ,'' ,'pisos_tateis' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001219 ,4000236 ,'Portas com vão livre de no mínimo 80 cm' ,'portas-com-vao-livre-de-no-minimo-80-cm' ,'false' ,0 ,'' ,'portas_vao_livre' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001220 ,4000236 ,'Rampas' ,'rampas' ,'false' ,0 ,'' ,'rampas' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001221 ,4000236 ,'Sinalização sonora' ,'sinalizacao-sonora' ,'false' ,0 ,'' ,'sinalizacao_sonora' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001222 ,4000236 ,'Sinalização tátil' ,'sinalizacao-tatil' ,'false' ,0 ,'' ,'sinalizacao_tatil' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001223 ,4000236 ,'Sinalização visual (piso/paredes)' ,'sinalizacao-visual-pisoparedes' ,'false' ,0 ,'' ,'sinalizacao_visual' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001224 ,4000236 ,'Nenhum dos recursos de acessibilidade listados' ,'nenhum-dos-recursos-de-acessibilidade-listados' ,'false' ,0 ,'' ,'nenhum_recurso_acessibilidade' );
            update avaliacaopergunta set db103_sequencial = 3000000 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000001 , db103_descricao = 'Dependências Existentes na Escola' , db103_identificador = 'dependencias-existentes-na-escola' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 9 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'dependencias_existentes_na_escola' where db103_sequencial = 3000000;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000000;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001225 ,3000000 ,'Banheiro exclusivo para os funcionários' ,'banheiro-exclusivo-para-os-funcionarios' ,'false' ,0 ,'' ,'banheiro_exclusivo_para_os_funcionarios' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001226 ,3000000 ,'Dormitório de aluno(a)' ,'dormitorio-de-alunoa' ,'false' ,0 ,'' ,'dormitorio_aluno' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001227 ,3000000 ,'Dormitório de professor(a)' ,'dormitorio-de-professora' ,'false' ,0 ,'' ,'dormitorio_professor' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001228 ,3000000 ,'Piscina' ,'piscina' ,'false' ,0 ,'' ,'piscina' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001229 ,3000000 ,'Sala de repouso para aluno(a)' ,'sala-de-repouso-para-alunoa' ,'false' ,0 ,'' ,'sala_repouso_aluno' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001230 ,3000000 ,'Sala/ateliê de artes' ,'salaatelie-de-artes' ,'false' ,0 ,'' ,'atelie_artes' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001231 ,3000000 ,'Sala de música/coral' ,'sala-de-musicacoral' ,'false' ,0 ,'' ,'sala_musica' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001232 ,3000000 ,'Sala/estúdio de dança' ,'salaestudio-de-danca' ,'false' ,0 ,'' ,'estudio_danca' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001233 ,3000000 ,'Sala multiuso (música, dança e artes)' ,'sala-multiuso-musica-danca-e-artes' ,'false' ,0 ,'' ,'sala_multiuso' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001234 ,3000000 ,'Terreirão (área para prática desportiva e recreação sem cobertura, sem piso e sem edificações)' ,'terreirao-area-para-pratica-desportiva-e-recreacao' ,'false' ,0 ,'' ,'terreirao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001235 ,3000000 ,'Viveiro/criação de animais' ,'viveirocriacao-de-animais' ,'false' ,0 ,'' ,'viveiro' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001236 ,3000000 ,'Nenhuma das dependências relacionadas' ,'nenhuma-das-dependencias-relacionadas5cb8a474d9085' ,'false' ,0 ,'' ,'nenhuma_dependencias_relacionadas' );
            update avaliacaoperguntaopcao set db104_sequencial = 3000000 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Sala de diretoria' , db104_identificador = 'sala-de-diretoria' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'sala_de_diretoria' where db104_sequencial = 3000000;
            update avaliacaoperguntaopcao set db104_sequencial = 3000001 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Sala de professores' , db104_identificador = 'sala-de-professores' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'sala_de_professores' where db104_sequencial = 3000001;
            update avaliacaoperguntaopcao set db104_sequencial = 3000002 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Laboratório de informática' , db104_identificador = 'laboratorio-de-informatica' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'laboratorio_de_informatica' where db104_sequencial = 3000002;
            update avaliacaoperguntaopcao set db104_sequencial = 3000003 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Laboratório de ciências' , db104_identificador = 'laboratorio-de-ciencias' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'laboratorio_de_ciencias' where db104_sequencial = 3000003;
            update avaliacaoperguntaopcao set db104_sequencial = 3000004 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Sala de recursos multifuncionais para Atendimento Educacional Especializado (AEE)' , db104_identificador = 'sala-de-recursos-multifuncionais-para-atendimento-' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'sala_de_recursos_multifuncionais_para_atendimento_educacional_especializado_aee' where db104_sequencial = 3000004;
            update avaliacaoperguntaopcao set db104_sequencial = 3000005 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Quadra de esportes coberta' , db104_identificador = 'quadra-de-esportes-coberta' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'quadra_de_esportes_coberta' where db104_sequencial = 3000005;
            update avaliacaoperguntaopcao set db104_sequencial = 3000006 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Quadra de esportes descoberta' , db104_identificador = 'quadra-de-esportes-descoberta' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'quadra_de_esportes_descoberta' where db104_sequencial = 3000006;
            update avaliacaoperguntaopcao set db104_sequencial = 3000007 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Cozinha' , db104_identificador = 'cozinha' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'cozinha' where db104_sequencial = 3000007;
            update avaliacaoperguntaopcao set db104_sequencial = 3000008 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Biblioteca' , db104_identificador = 'biblioteca' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'biblioteca' where db104_sequencial = 3000008;
            update avaliacaoperguntaopcao set db104_sequencial = 3000009 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Sala de leitura' , db104_identificador = 'sala-de-leitura' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'sala_de_leitura' where db104_sequencial = 3000009;
            update avaliacaoperguntaopcao set db104_sequencial = 3000010 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Parque infantil' , db104_identificador = 'parque-infantil' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'parque_infantil' where db104_sequencial = 3000010;
            update avaliacaoperguntaopcao set db104_sequencial = 3000011 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Berçário' , db104_identificador = 'bercario' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'bercario' where db104_sequencial = 3000011;
            update avaliacaoperguntaopcao set db104_sequencial = 3000012 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Banheiro fora do prédio' , db104_identificador = 'banheiro-fora-do-predio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banheiro_fora_do_predio' where db104_sequencial = 3000012;
            update avaliacaoperguntaopcao set db104_sequencial = 3000013 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Banheiro dentro do prédio' , db104_identificador = 'banheiro-dentro-do-predio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banheiro_dentro_do_predio' where db104_sequencial = 3000013;
            update avaliacaoperguntaopcao set db104_sequencial = 3000014 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Banheiro adequado à educação infantil' , db104_identificador = 'banheiro-adequado-a-educacao-infantil' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banheiro_adequado_a_educacao_infantil' where db104_sequencial = 3000014;
            update avaliacaoperguntaopcao set db104_sequencial = 3000015 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Banheiro adequado a alunos com deficiência ou mobilidade reduzida' , db104_identificador = 'banheiro-adequado-a-alunos-com-deficiencia-ou-mobi' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banheiro_adequado_a_alunos_com_deficiencia_ou_mobilidade_reduzida' where db104_sequencial = 3000015;
            update avaliacaoperguntaopcao set db104_sequencial = 3000016 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Nenhuma das dependências relacionadas' , db104_identificador = 'nenhuma-das-dependencias-relacionadas' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'nenhuma_das_dependencias_relacionadas' where db104_sequencial = 3000016;
            update avaliacaoperguntaopcao set db104_sequencial = 3000017 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Sala de secretaria' , db104_identificador = 'sala-de-secretaria' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'sala_de_secretaria' where db104_sequencial = 3000017;
            update avaliacaoperguntaopcao set db104_sequencial = 3000018 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Banheiro ou vestiário com chuveiro' , db104_identificador = 'banheiro-ou-vestuario-com-chuveiro' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banheiro_com_chuveiro' where db104_sequencial = 3000018;
            update avaliacaoperguntaopcao set db104_sequencial = 3000019 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Refeitório' , db104_identificador = 'refeitorio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'refeitorio' where db104_sequencial = 3000019;
            update avaliacaoperguntaopcao set db104_sequencial = 3000020 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Despensa' , db104_identificador = 'despensa' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'despensa' where db104_sequencial = 3000020;
            update avaliacaoperguntaopcao set db104_sequencial = 3000021 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Almoxarifado' , db104_identificador = 'almoxarifado' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'almoxarifado' where db104_sequencial = 3000021;
            update avaliacaoperguntaopcao set db104_sequencial = 3000022 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Auditório' , db104_identificador = 'auditorio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'auditorio' where db104_sequencial = 3000022;
            update avaliacaoperguntaopcao set db104_sequencial = 3000023 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Pátio coberto' , db104_identificador = 'patio-coberto' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'patio_coberto' where db104_sequencial = 3000023;
            update avaliacaoperguntaopcao set db104_sequencial = 3000024 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Pátio descoberto' , db104_identificador = 'patio-descoberto' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'patio_descoberto' where db104_sequencial = 3000024;
            update avaliacaoperguntaopcao set db104_sequencial = 3000025 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Alojamento de aluno' , db104_identificador = 'alojamento-de-aluno' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'alojamento_de_aluno' where db104_sequencial = 3000025;
            update avaliacaoperguntaopcao set db104_sequencial = 3000026 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Alojamento de professor' , db104_identificador = 'alojamento-de-professor' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'alojamento_de_professor' where db104_sequencial = 3000026;
            update avaliacaoperguntaopcao set db104_sequencial = 3000027 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Área verde' , db104_identificador = 'area-verde' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'area_verde' where db104_sequencial = 3000027;
            update avaliacaoperguntaopcao set db104_sequencial = 3000028 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Lavanderia' , db104_identificador = 'lavanderia' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'lavanderia' where db104_sequencial = 3000028;
            update avaliacaoperguntaopcao set db104_sequencial = 3000126 , db104_avaliacaopergunta = 3000000 , db104_descricao = 'Dependências e vias adequadas a alunos com deficiência ou mobilidade reduzida' , db104_identificador = 'dependencias-e-vias-adequadas-a-alunos-com-deficie' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'dependencias_e_vias_adequadas_a_alunos_com_deficiencia_ou_mobilidade_reduzida' where db104_sequencial = 3000126;
            update avaliacaopergunta set db103_sequencial = 3000001 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000001 , db103_descricao = 'Possui computadores:' , db103_identificador = 'possui-computadores' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 10 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'possui_computadores' where db103_sequencial = 3000001;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000001;
            update avaliacaoperguntaopcao set db104_sequencial = 3000030 , db104_avaliacaopergunta = 3000001 , db104_descricao = 'SIM' , db104_identificador = 'sim5cb8a22f5b1da' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'possui_computadores_sim' where db104_sequencial = 3000030;
            update avaliacaoperguntaopcao set db104_sequencial = 3000031 , db104_avaliacaopergunta = 3000001 , db104_descricao = 'NÃO' , db104_identificador = 'nao5cb8a22f5b7a5' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'possui_computadores_nao' where db104_sequencial = 3000031;
            update avaliacaopergunta set db103_sequencial = 3000004 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000001 , db103_descricao = 'Acesso à Internet:' , db103_identificador = 'acesso-a-internet' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 11 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'acesso_a_internet' where db103_sequencial = 3000004;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000004;
            update avaliacaoperguntaopcao set db104_sequencial = 3000035, db104_avaliacaopergunta = 3000004,db104_descricao = 'Para uso administrativo',db104_identificador = 'para-uso-administrativo', db104_aceitatexto = 'false', db104_peso = 0,db104_valorresposta = '',db104_identificadorcampo = 'para_uso_administrativo' where db104_sequencial = 3000035;
            update avaliacaoperguntaopcao set db104_sequencial = 3000036, db104_avaliacaopergunta = 3000004,db104_descricao = 'Para uso no processo de ensino e aprendizagem', db104_identificador = 'para-uso-no-processo-de-ensino-e-aprendizagem', db104_aceitatexto = 'false', db104_peso = 0,db104_valorresposta = '',db104_identificadorcampo = 'para_uso_no_ensino' where db104_sequencial = 3000036;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001239 ,3000004 ,'Para uso dos aluno(a)s' ,'para-uso-dos-alunoas' ,'false' ,0 ,'' ,'para_uso_dos_alunos' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001240 ,3000004 ,'Para uso da comunidade' ,'para-uso-da-comunidade' ,'false' ,0 ,'' ,'para_uso_da_comunidade' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001241 ,3000004 ,'Não possui acesso à internet' ,'nao-possui-acesso-a-internet' ,'false' ,0 ,'' ,'nao_possui_acesso_internet' );
            update avaliacaopergunta set db103_sequencial = 3000005 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000001 , db103_descricao = 'Banda Larga:' , db103_identificador = 'banda-larga' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 12 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'banda_larga' where db103_sequencial = 3000005;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000005;
            update avaliacaoperguntaopcao set db104_sequencial = 3000037 , db104_avaliacaopergunta = 3000005 , db104_descricao = 'Possui' , db104_identificador = 'possui' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banda_larga_possui' where db104_sequencial = 3000037;
            update avaliacaoperguntaopcao set db104_sequencial = 3000038 , db104_avaliacaopergunta = 3000005 , db104_descricao = 'Não Possui' , db104_identificador = 'nao-possui' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'banda_larga_nao_possui' where db104_sequencial = 3000038;
            update avaliacaopergunta set db103_sequencial = 3000006 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000001 , db103_descricao = 'Local de Funcionamento:' , db103_identificador = 'local-de-funcionamento' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 13 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'local_de_funcionamento' where db103_sequencial = 3000006;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000006;
            update avaliacaoperguntaopcao set db104_sequencial = 3000039 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Prédio Escolar' , db104_identificador = 'predio-escolar' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'predio_escolar' where db104_sequencial = 3000039;
            update avaliacaoperguntaopcao set db104_sequencial = 3000040 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Templo / Igreja' , db104_identificador = 'templo-igreja' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'templo_igreja' where db104_sequencial = 3000040;
            update avaliacaoperguntaopcao set db104_sequencial = 3000041 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Salas de Empresa' , db104_identificador = 'salas-de-empresa' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'salas_de_empresa' where db104_sequencial = 3000041;
            update avaliacaoperguntaopcao set db104_sequencial = 3000042 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Casa do Professor' , db104_identificador = 'casa-do-professor' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'casa_do_professor' where db104_sequencial = 3000042;
            update avaliacaoperguntaopcao set db104_sequencial = 3000043 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Salas em Outra Escola' , db104_identificador = 'salas-em-outra-escola' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'salas_em_outra_escola' where db104_sequencial = 3000043;
            update avaliacaoperguntaopcao set db104_sequencial = 3000044 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Galpão / Rancho / Paiol' , db104_identificador = 'galpao-rancho-paiol' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'galpao_rancho_paiol' where db104_sequencial = 3000044;
            update avaliacaoperguntaopcao set db104_sequencial = 3000045 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Unidade de Internação' , db104_identificador = 'LocalFuncionamentoUnidadeInternacao' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_identificadorcampo = 'unidade_de_internacao' where db104_sequencial = 3000045;
            update avaliacaoperguntaopcao set db104_sequencial = 3000046 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'OUTROS' , db104_identificador = 'outros5cb8a22f60e01' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'local_de_funcionamento_outros' where db104_sequencial = 3000046;
            update avaliacaoperguntaopcao set db104_sequencial = 3000560 , db104_avaliacaopergunta = 3000006 , db104_descricao = 'Unidade Prisional' , db104_identificador = 'LocalFuncionamentoUnidadePrisional' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_identificadorcampo = 'unidade_prisional' where db104_sequencial = 3000560;
            update avaliacaogrupopergunta set db102_sequencial = 3000003 , db102_avaliacao = 3000000 , db102_descricao = 'COMPUTADORES' , db102_identificador = 'computadores' , db102_identificadorcampo = 'computadores' , db102_ordem = 2 where db102_sequencial = 3000003;
            update avaliacaopergunta set db103_sequencial = 3000003 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000003 , db103_descricao = 'Qtde. de Computadores Uso Administrativo:' , db103_identificador = 'qtde-de-computadores-uso-administrativo' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'qtde_de_computadores_uso_administrativo' where db103_sequencial = 3000003;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000003;
            update avaliacaoperguntaopcao set db104_sequencial = 3000033 , db104_avaliacaopergunta = 3000003 , db104_identificador = '5cb8a22f62624' , db104_aceitatexto = 'true' , db104_identificadorcampo = 'resposta_qtde_de_computadores_uso_administrativo' where db104_sequencial = 3000033;
            update avaliacaopergunta set db103_sequencial = 3000024 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000003 , db103_descricao = 'Qtde. de Computadores Uso de Alunos:' , db103_identificador = 'qtde-de-computadores-uso-de-alunos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'qtde_de_computadores_uso_de_alunos' where db103_sequencial = 3000024;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000024;
            update avaliacaoperguntaopcao set db104_sequencial = 3000113 , db104_avaliacaopergunta = 3000024 , db104_identificador = '5cb8a22f63572' , db104_aceitatexto = 'true' , db104_identificadorcampo = 'resposta_qtde_de_computadores_uso_de_alunos' where db104_sequencial = 3000113;
            update avaliacaogrupopergunta set db102_sequencial = 3000004 , db102_avaliacao = 3000000 , db102_descricao = 'FORMA DE OCUPAÇÃO DO PRÉDIO' , db102_identificador = 'forma-de-ocupacao-do-predio' , db102_identificadorcampo = 'forma_de_ocupacao_do_predio' , db102_ordem = 3 where db102_sequencial = 3000004;
            update avaliacaopergunta set db103_sequencial = 3000007 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000004 , db103_descricao = 'Forma de Ocupação do Prédio:' , db103_identificador = 'forma-de-ocupacao-do-predio' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_forma_de_ocupacao_do_predio' where db103_sequencial = 3000007;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000007;
            update avaliacaoperguntaopcao set db104_sequencial = 3000047 , db104_avaliacaopergunta = 3000007 , db104_descricao = 'Próprio' , db104_identificador = 'proprio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'proprio' where db104_sequencial = 3000047;
            update avaliacaoperguntaopcao set db104_sequencial = 3000048 , db104_avaliacaopergunta = 3000007 , db104_descricao = 'Alugado' , db104_identificador = 'alugado' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'alugado' where db104_sequencial = 3000048;
            update avaliacaoperguntaopcao set db104_sequencial = 3000049 , db104_avaliacaopergunta = 3000007 , db104_descricao = 'Cedido' , db104_identificador = 'cedido' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'cedido' where db104_sequencial = 3000049;
            update avaliacaogrupopergunta set db102_sequencial = 3000005 , db102_avaliacao = 3000000 , db102_descricao = 'ESGOTO SANITÁRIO' , db102_identificador = 'esgoto-sanitario' , db102_identificadorcampo = 'esgoto_sanitario' , db102_ordem = 4 where db102_sequencial = 3000005;
            update avaliacaopergunta set db103_sequencial = 3000008 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000005 , db103_descricao = 'Esgoto Sanitario:' , db103_identificador = 'esgoto-sanitario' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_esgoto_sanitario' where db103_sequencial = 3000008;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000008;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001242 ,3000008 ,'Fossa séptica' ,'fossa-septica' ,'false' ,0 ,'' ,'fossa_septica' );
            update avaliacaoperguntaopcao set db104_sequencial = 3000050 , db104_avaliacaopergunta = 3000008 , db104_descricao = 'Rede Pública' , db104_identificador = 'rede-publica' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'esgoto_rede_publica' where db104_sequencial = 3000050;
            update avaliacaoperguntaopcao set db104_sequencial = 3000051 , db104_avaliacaopergunta = 3000008 , db104_descricao = 'Fossa rudimentar/comum' , db104_identificador = 'fossa-comum' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'fossa_comum' where db104_sequencial = 3000051;
            update avaliacaoperguntaopcao set db104_sequencial = 3000052 , db104_avaliacaopergunta = 3000008 , db104_descricao = 'Inexistente' , db104_identificador = 'inexistente' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'esgoto_inexistente' where db104_sequencial = 3000052;
            update avaliacaogrupopergunta set db102_sequencial = 3000006 , db102_avaliacao = 3000000 , db102_descricao = 'MATERIAIS DIDÁTICOS ESPECÍFICOS' , db102_identificador = 'materiais-didaticos-especificos' , db102_identificadorcampo = 'materiais_didaticos_especificos' , db102_ordem = 5 where db102_sequencial = 3000006;
            update avaliacaopergunta set db103_sequencial = 3000009 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000006 , db103_descricao = 'Materais Didáticos Específicos:' , db103_identificador = 'materais-didaticos-especificos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'materais_didaticos_especificos' where db103_sequencial = 3000009;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000009;
            update avaliacaoperguntaopcao set db104_sequencial = 3000053 , db104_avaliacaopergunta = 3000009 , db104_descricao = 'Não Utiliza' , db104_identificador = 'nao-utiliza5cb8a22f67e78' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'nao_utiliza' where db104_sequencial = 3000053;
            update avaliacaoperguntaopcao set db104_sequencial = 3000054 , db104_avaliacaopergunta = 3000009 , db104_descricao = 'Quilombola' , db104_identificador = 'quilombola' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'quilombola' where db104_sequencial = 3000054;
            update avaliacaoperguntaopcao set db104_sequencial = 3000055 , db104_avaliacaopergunta = 3000009 , db104_descricao = 'Indígena' , db104_identificador = 'indigena5cb8a22f6897c' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'indigena' where db104_sequencial = 3000055;
            update avaliacaogrupopergunta set db102_sequencial = 3000007 , db102_avaliacao = 3000000 , db102_descricao = 'EQUIPAMENTOS EXISTENTES' , db102_identificador = 'equipamentos-existentes' , db102_identificadorcampo = 'equipamentos_existentes' , db102_ordem = 6 where db102_sequencial = 3000007;
            update avaliacaopergunta set db103_sequencial = 3000010 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000007 , db103_descricao = 'Equipamentos Existentes:' , db103_identificador = 'equipamentos-existentes' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_equipamentos_existentes' where db103_sequencial = 3000010;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000010;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001243 ,3000010 ,'Scanner' ,'scanner' ,'true' ,0 ,'' ,'scanner' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001244 ,3000010 ,'Lousa Digital' ,'lousa-digital' ,'true' ,0 ,'' ,'lousa_digital' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001245 ,3000010 ,'Computadores portáteis' ,'computadores-portateis' ,'true' ,0 ,'' ,'computadores_portateis' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001246 ,3000010 ,'Tablets' ,'tablets' ,'true' ,0 ,'' ,'tablets' );
            update avaliacaoperguntaopcao set db104_sequencial = 3000032 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Computadores De Mesa' , db104_identificador = 'equipamentos_computadores_mesa' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'equipamentos_computadores_mesa' where db104_sequencial = 3000032;
            update avaliacaoperguntaopcao set db104_sequencial = 3000056 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Aparelho de Televisão' , db104_identificador = 'aparelho_televisao' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'aparelho_de_televisao' where db104_sequencial = 3000056;
            update avaliacaoperguntaopcao set db104_sequencial = 3000057 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Videocassete' , db104_identificador = 'videocassete' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'videocassete' where db104_sequencial = 3000057;
            update avaliacaoperguntaopcao set db104_sequencial = 3000058 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Aparelho de DVD/Blu-ray' , db104_identificador = 'aparelho-de-dvd' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'dvd' where db104_sequencial = 3000058;
            update avaliacaoperguntaopcao set db104_sequencial = 3000059 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Antena Parabólica' , db104_identificador = 'antena_parabolica' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'antena_parabolica' where db104_sequencial = 3000059;
            update avaliacaoperguntaopcao set db104_sequencial = 3000060 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Copiadora' , db104_identificador = 'copiadora' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'copiadora' where db104_sequencial = 3000060;
            update avaliacaoperguntaopcao set db104_sequencial = 3000061 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Retroprojetor' , db104_identificador = 'retroprojetor' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'retroprojetor' where db104_sequencial = 3000061;
            update avaliacaoperguntaopcao set db104_sequencial = 3000062 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Impressora' , db104_identificador = 'impressora' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'impressora' where db104_sequencial = 3000062;
            update avaliacaoperguntaopcao set db104_sequencial = 3000063 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Aparelho de som' , db104_identificador = 'aparelho_som' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'aparelho_de_som' where db104_sequencial = 3000063;
            update avaliacaoperguntaopcao set db104_sequencial = 3000064 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Projetor Multimídia (Data show)' , db104_identificador = 'projetor_multimidia' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'projetor_multimidia_data_show' where db104_sequencial = 3000064;
            update avaliacaoperguntaopcao set db104_sequencial = 3000065 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Fax' , db104_identificador = 'fax' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'fax' where db104_sequencial = 3000065;
            update avaliacaoperguntaopcao set db104_sequencial = 3000066 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Máquina Fotográfica/Filmadora' , db104_identificador = 'maquina_fotografica' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'maquina_fotografica_filmadora' where db104_sequencial = 3000066;
            update avaliacaoperguntaopcao set db104_sequencial = 3000579 , db104_avaliacaopergunta = 3000010 , db104_descricao = 'Impressora Multifuncional' , db104_identificador = 'equipamentos_impressora_multifuncional' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'impressora_multifuncional' where db104_sequencial = 3000579;
            update avaliacaogrupopergunta set db102_sequencial = 3000008 , db102_avaliacao = 3000000 , db102_descricao = 'DESTINAÇÃO DO LIXO' , db102_identificador = 'destinacao-do-lixo' , db102_identificadorcampo = 'destinacao_do_lixo' , db102_ordem = 7 where db102_sequencial = 3000008;
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000237 ,3 ,3000008 ,'Tratamento do lixo/resíduos que a escola realiza:' ,'tratamento-do-lixoresiduos-que-a-escola-realiza' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'pergunta_destinacao_do_lixo' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000237;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001247 ,4000237 ,'Separação do lixo/resíduos' ,'separacao-do-lixoresiduos' ,'false' ,0 ,'' ,'separacao_lixo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001248 ,4000237 ,'Reaproveitamento/reutilização' ,'reaproveitamentoreutilizacao' ,'false' ,0 ,'' ,'reaproveitamento' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001249 ,4000237 ,'Reciclagem' ,'reciclagem' ,'false' ,0 ,'' ,'reciclagem' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001250 ,4000237 ,'Não faz tratamento' ,'nao-faz-tratamento' ,'false' ,0 ,'' ,'nao_faz_tratamento' );
            update avaliacaopergunta set db103_sequencial = 3000011 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000008 , db103_descricao = 'Destinação do Lixo:' , db103_identificador = 'destinacao-do-lixo' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_destinacao_do_lixo' where db103_sequencial = 3000011;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000011;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001251 ,3000011 ,'Leva a uma destinação final licenciada pelo poder público' ,'leva-a-uma-destinacao-final-licenciada-pelo-poder-' ,'false' ,0 ,'' ,'leva_a_uma_destinaçcao_final_licenciada_pelo_poder_publico' );
            update avaliacaoperguntaopcao set db104_sequencial = 3000067 , db104_avaliacaopergunta = 3000011 , db104_descricao = 'Serviço de coleta' , db104_identificador = 'servico-de-coleta' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'servico_de_coleta' where db104_sequencial = 3000067;
            update avaliacaoperguntaopcao set db104_sequencial = 3000068 , db104_avaliacaopergunta = 3000011 , db104_descricao = 'Queima' , db104_identificador = 'queima' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'queima' where db104_sequencial = 3000068;
            update avaliacaoperguntaopcao set db104_sequencial = 3000069 , db104_avaliacaopergunta = 3000011 , db104_descricao = 'Descarta em outra área' , db104_identificador = 'descarta-em-outra-area' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'descarta_em_outra_area' where db104_sequencial = 3000069;
            update avaliacaoperguntaopcao set db104_sequencial = 3000071 , db104_avaliacaopergunta = 3000011 , db104_descricao = 'Enterra' , db104_identificador = 'enterra' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'enterra' where db104_sequencial = 3000071;
            update avaliacaogrupopergunta set db102_sequencial = 3000009 , db102_avaliacao = 3000000 , db102_descricao = 'ABASTECIMENTO DE ÁGUA' , db102_identificador = 'abastecimento-de-agua' , db102_identificadorcampo = 'abastecimento_de_agua' , db102_ordem = 8 where db102_sequencial = 3000009;
            update avaliacaopergunta set db103_sequencial = 3000014 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000009 , db103_descricao = 'Abastecimento de Água' , db103_identificador = 'abastecimento-de-agua' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_abastecimento_de_agua' where db103_sequencial = 3000014;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000014;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001252 ,3000014 ,'Fornece água potável para o consumo humano' ,'fornece-agua-potavel-para-o-consumo-humano' ,'false' ,0 ,'' ,'fornece_agua_potavel_para_o_consumo_humano' );
            update avaliacaoperguntaopcao set db104_sequencial = 3000088 , db104_avaliacaopergunta = 3000014 , db104_descricao = 'Rede Pública' , db104_identificador = 'rede-publica5cb8a22f7144b' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'abastecimento_de_agua_rede_publica' where db104_sequencial = 3000088;
            update avaliacaoperguntaopcao set db104_sequencial = 3000089 , db104_avaliacaopergunta = 3000014 , db104_descricao = 'Poço Artesiano' , db104_identificador = 'poco-artesiano' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'poco_artesiano' where db104_sequencial = 3000089;
            update avaliacaoperguntaopcao set db104_sequencial = 3000090 , db104_avaliacaopergunta = 3000014 , db104_descricao = 'Cacimba/Cisterna/Poço' , db104_identificador = 'cacimbacisternapoco' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'cacimba_cisterna_poco' where db104_sequencial = 3000090;
            update avaliacaoperguntaopcao set db104_sequencial = 3000091 , db104_avaliacaopergunta = 3000014 , db104_descricao = 'Fonte/Rio/Igarapé/Riacho' , db104_identificador = 'fonterioigaraperiacho' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'fonte_rio_igarape_riacho' where db104_sequencial = 3000091;
            update avaliacaoperguntaopcao set db104_sequencial = 3000092 , db104_avaliacaopergunta = 3000014 , db104_descricao = 'Inexistente' , db104_identificador = 'inexistente5cb8a22f72932' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'abastecimento_de_agua_inexistente' where db104_sequencial = 3000092;
            update avaliacaogrupopergunta set db102_sequencial = 3000011 , db102_avaliacao = 3000000 , db102_descricao = 'ABASTECIMENTO DE ENERGIA' , db102_identificador = 'abastecimento-de-energia' , db102_identificadorcampo = 'abastecimento_de_energia' , db102_ordem = 9 where db102_sequencial = 3000011;
            update avaliacaopergunta set db103_sequencial = 3000015 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000011 , db103_descricao = 'Abastecimento de Energia:' , db103_identificador = 'abastecimento-de-energia' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_abastecimento_de_energia' where db103_sequencial = 3000015;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000015;
            update avaliacaoperguntaopcao set db104_sequencial = 3000093 , db104_avaliacaopergunta = 3000015 , db104_descricao = 'Rede Pública' , db104_identificador = 'rede-publica5cb8a22f73c3e' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'abastecimento_de_energia_rede_publica' where db104_sequencial = 3000093;
            update avaliacaoperguntaopcao set db104_sequencial = 3000094 , db104_avaliacaopergunta = 3000015 , db104_descricao = 'Gerador movido a combustível fóssil' , db104_identificador = 'gerador-movido-a-combustivel-fossil' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'gerador_movido_a_combustivel_fossil' where db104_sequencial = 3000094;
            update avaliacaoperguntaopcao set db104_sequencial = 3000095 , db104_avaliacaopergunta = 3000015 , db104_descricao = 'Outros(Enegria Alternativa)' , db104_identificador = 'outrosenegria-alternativa' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'abastecimento_de_energia_outrosenegria_alternativa' where db104_sequencial = 3000095;
            update avaliacaoperguntaopcao set db104_sequencial = 3000096 , db104_avaliacaopergunta = 3000015 , db104_descricao = 'Inexistente' , db104_identificador = 'inexistente5cb8a22f74dde' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'abastecimento_de_energia_inexistente' where db104_sequencial = 3000096;
            update avaliacaogrupopergunta set db102_sequencial = 3000012 , db102_avaliacao = 3000000 , db102_descricao = 'PREDIO COMPARTILHADO' , db102_identificador = 'predio-compartilhado' , db102_identificadorcampo = 'predio_compartilhado' , db102_ordem = 10 where db102_sequencial = 3000012;
            update avaliacaopergunta set db103_sequencial = 3000016 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Predio Compartilhado' , db103_identificador = 'predio-compartilhado' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pergunta_predio_compartilhado' where db103_sequencial = 3000016;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000016;
            update avaliacaoperguntaopcao set db104_sequencial = 3000097 , db104_avaliacaopergunta = 3000016 , db104_descricao = 'SIM' , db104_identificador = 'sim5cb8a22f760da' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'predio_compartilhado_sim' where db104_sequencial = 3000097;
            update avaliacaoperguntaopcao set db104_sequencial = 3000098 , db104_avaliacaopergunta = 3000016 , db104_descricao = 'NÃO' , db104_identificador = 'nao5cb8a22f766a7' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'predio_compartilhado_nao' where db104_sequencial = 3000098;
            update avaliacaopergunta set db103_sequencial = 3000154 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 1' , db103_identificador = 'PredioCompartilhadoInep1' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_1' where db103_sequencial = 3000154;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000154;
            update avaliacaoperguntaopcao set db104_sequencial = 3000571 , db104_avaliacaopergunta = 3000154 , db104_identificador = 'PredioCompartilhadoInep1_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_1' where db104_sequencial = 3000571;
            update avaliacaopergunta set db103_sequencial = 3000155 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 2' , db103_identificador = 'PredioCompartilhadoInep2' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_2' where db103_sequencial = 3000155;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000155;
            update avaliacaoperguntaopcao set db104_sequencial = 3000572 , db104_avaliacaopergunta = 3000155 , db104_identificador = 'PredioCompartilhadoInep2_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_2' where db104_sequencial = 3000572;
            update avaliacaopergunta set db103_sequencial = 3000156 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 3' , db103_identificador = 'PredioCompartilhadoInep3' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 4 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_3' where db103_sequencial = 3000156;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000156;
            update avaliacaoperguntaopcao set db104_sequencial = 3000576 , db104_avaliacaopergunta = 3000156 , db104_identificador = 'PredioCompartilhadoInep3_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_3' where db104_sequencial = 3000576;
            update avaliacaopergunta set db103_sequencial = 3000157 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 4' , db103_identificador = 'PredioCompartilhadoInep4' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 5 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_4' where db103_sequencial = 3000157;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000157;
            update avaliacaoperguntaopcao set db104_sequencial = 3000573 , db104_avaliacaopergunta = 3000157 , db104_identificador = 'PredioCompartilhadoInep4_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_4' where db104_sequencial = 3000573;
            update avaliacaopergunta set db103_sequencial = 3000158 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 5' , db103_identificador = 'PredioCompartilhadoInep5' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 6 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_5' where db103_sequencial = 3000158;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000158;
            update avaliacaoperguntaopcao set db104_sequencial = 3000574 , db104_avaliacaopergunta = 3000158 , db104_identificador = 'PredioCompartilhadoInep5_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_5' where db104_sequencial = 3000574;
            update avaliacaopergunta set db103_sequencial = 3000159 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000012 , db103_descricao = 'Código INEP do prédio compartilhado 6' , db103_identificador = 'PredioCompartilhadoInep6' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 7 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'codigo_inep_do_predio_compartilhado_6' where db103_sequencial = 3000159;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000159;
            update avaliacaoperguntaopcao set db104_sequencial = 3000575 , db104_avaliacaopergunta = 3000159 , db104_identificador = 'PredioCompartilhadoInep6_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'resposta_codigo_inep_do_predio_compartilhado_6' where db104_sequencial = 3000575;
            update avaliacaogrupopergunta set db102_sequencial = 3000013 , db102_avaliacao = 3000000 , db102_descricao = 'OUTRAS INFORMAÇÕES' , db102_identificador = 'outras-informacoes' , db102_identificadorcampo = 'outras_informacoes' , db102_ordem = 11 where db102_sequencial = 3000013;
            update avaliacaopergunta set db103_sequencial = 3000017 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Água consumida pelos Alunos:' , db103_identificador = 'agua-consumida-pelos-alunos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'agua_consumida_pelos_alunos' where db103_sequencial = 3000017;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000017;
            update avaliacaoperguntaopcao set db104_sequencial = 3000099 , db104_avaliacaopergunta = 3000017 , db104_descricao = 'NÃO FILTRADA' , db104_identificador = 'nao-filtrada' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'nao_filtrada' where db104_sequencial = 3000099;
            update avaliacaoperguntaopcao set db104_sequencial = 3000100 , db104_avaliacaopergunta = 3000017 , db104_descricao = 'FILTRADA' , db104_identificador = 'filtrada' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'filtrada' where db104_sequencial = 3000100;
            update avaliacaopergunta set db103_sequencial = 3000018 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Alimentação Escolar para os Alunos' , db103_identificador = 'alimentacao-escolar-para-os-alunos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'alimentacao_escolar_para_os_alunos' where db103_sequencial = 3000018;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000018;
            update avaliacaoperguntaopcao set db104_sequencial = 3000101 , db104_avaliacaopergunta = 3000018 , db104_descricao = 'OFERECE' , db104_identificador = 'oferece' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'alimentacao_escolar_para_os_alunos_oferece' where db104_sequencial = 3000101;
            update avaliacaoperguntaopcao set db104_sequencial = 3000102 , db104_avaliacaopergunta = 3000018 , db104_descricao = 'NÃO OFERECE' , db104_identificador = 'nao-oferece' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'alimentacao_escolar_para_os_alunos_nao_oferece' where db104_sequencial = 3000102;
            update avaliacaopergunta set db103_sequencial = 3000019 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'N° de Sala de Aula Existentes na Escola:' , db103_identificador = 'n-de-sala-de-aula-existentes-na-escola' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'n_de_sala_de_aula_existentes_na_escola' where db103_sequencial = 3000019;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000019;
            update avaliacaoperguntaopcao set db104_sequencial = 3000103 , db104_avaliacaopergunta = 3000019 , db104_identificador = '5cb8a22f7e6ed' , db104_aceitatexto = 'true' , db104_identificadorcampo = 'resposta_n_de_sala_de_aula_existentes_na_escola' where db104_sequencial = 3000103;
            update avaliacaopergunta set db103_sequencial = 3000020 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'N° de Salas Utilizadas como Sala de Aula:' , db103_identificador = 'n-de-salas-utilizadas-como-sala-de-aula' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 4 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'n_de_salas_utilizadas_como_sala_de_aula' where db103_sequencial = 3000020;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000020;
            update avaliacaoperguntaopcao set db104_sequencial = 3000104 , db104_avaliacaopergunta = 3000020 , db104_identificador = '5cb8a22f7f604' , db104_aceitatexto = 'true' , db104_identificadorcampo = 'resposta_n_de_salas_utilizadas_como_sala_de_aula' where db104_sequencial = 3000104;
            update avaliacaopergunta set db103_sequencial = 3000021 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Atividade Complementar' , db103_identificador = 'atividade-complementar' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 5 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'atividade_complementar' where db103_sequencial = 3000021;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000021;
            update avaliacaoperguntaopcao set db104_sequencial = 3000105 , db104_avaliacaopergunta = 3000021 , db104_descricao = 'NÃO EXCLUSIVAMENTE' , db104_identificador = 'nao-exclusivamente' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atividade_complementar_nao_exclusivamente' where db104_sequencial = 3000105;
            update avaliacaoperguntaopcao set db104_sequencial = 3000106 , db104_avaliacaopergunta = 3000021 , db104_descricao = 'NÃO OFERECE' , db104_identificador = 'nao-oferece5cb8a22f808aa' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atividade_complementar_nao_oferece' where db104_sequencial = 3000106;
            update avaliacaoperguntaopcao set db104_sequencial = 3000107 , db104_avaliacaopergunta = 3000021 , db104_descricao = 'EXCLUSIVAMENTE' , db104_identificador = 'exclusivamente' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atividade_complementar_exclusivamente' where db104_sequencial = 3000107;
            update avaliacaopergunta set db103_sequencial = 3000022 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Atendimento Educ. Especializado AEE:' , db103_identificador = 'atendimento-educ-especializado-aee' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 6 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'atendimento_educ_especializado_aee' where db103_sequencial = 3000022;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000022;
            update avaliacaoperguntaopcao set db104_sequencial = 3000108 , db104_avaliacaopergunta = 3000022 , db104_descricao = 'NÃO EXCLUSIVAMENTE' , db104_identificador = 'nao-exclusivamente5cb8a22f81b6f' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atendimento_educ_especializado_aee_nao_exclusivamente' where db104_sequencial = 3000108;
            update avaliacaoperguntaopcao set db104_sequencial = 3000109 , db104_avaliacaopergunta = 3000022 , db104_descricao = 'EXCLUSIVAMENTE' , db104_identificador = 'exclusivamente5cb8a22f82192' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atendimento_educ_especializado_aee_exclusivamente' where db104_sequencial = 3000109;
            update avaliacaoperguntaopcao set db104_sequencial = 3000110 , db104_avaliacaopergunta = 3000022 , db104_descricao = 'NÃO OFERECE' , db104_identificador = 'nao-oferece5cb8a22f828c3' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'atendimento_educ_especializado_aee_nao_oferece' where db104_sequencial = 3000110;
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000238 ,3 ,3000013 ,'Total de profissionais que atuam nas seguintes funções na escola:' ,'total-de-profissionais-que-atuam-nas-seguintes-fun' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'total_profissionais_atuam_funcoes' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000238;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001253 ,4000238 ,'Auxiliares de secretaria ou auxiliares administrativos, atendentes' ,'auxiliares-de-secretaria-ou-auxiliares-administrat' ,'true' ,0 ,'' ,'auxiliar_secretaria' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001254 ,4000238 ,'Auxiliar de serviços gerais, porteiro(a), zelador(a), faxineiro(a), horticultor(a), jardineiro(a)' ,'auxiliar-de-servicos-gerais-porteiroa-zeladora-fax' ,'true' ,0 ,'' ,'auxiliar_servicos_gerais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001255 ,4000238 ,'Bibliotecário(a), auxiliar de biblioteca ou monitor(a) da sala de leitura' ,'bibliotecarioa-auxiliar-de-biblioteca-ou-monitora-' ,'true' ,0 ,'' ,'bibliotecario' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001256 ,4000238 ,'Bombeiro(a) brigadista, profissionais de assistência a saúde (urgência e emergência), enfermeiro(a), técnico(a) de enfermagem e socorrista' ,'bombeiroa-brigadista-profissionais-de-assistencia-' ,'true' ,0 ,'' ,'bombeiro_ou_saude' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001257 ,4000238 ,'Coordenador(a) de turno/disciplinar' ,'coordenadora-de-turnodisciplinar' ,'true' ,0 ,'' ,'coordenador_de_turno' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001258 ,4000238 ,'Fonoaudiólogo(a)' ,'fonoaudiologoa' ,'true' ,0 ,'' ,'fonoaudiologo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001259 ,4000238 ,'Nutricionista' ,'nutricionista' ,'true' ,0 ,'' ,'nutricionista' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001260 ,4000238 ,'Psicólogo(a) escolar' ,'psicologoa-escolar' ,'true' ,0 ,'' ,'psicologo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001261 ,4000238 ,'Profissionais de preparação e segurança alimentar, cozinheiro(a), merendeira e auxiliar de cozinha' ,'profissionais-de-preparacao-e-seguranca-alimentar-' ,'true' ,0 ,'' ,'cozinheiro' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001262 ,4000238 ,'Profissionais de apoio e supervisão pedagógica: (pedagogo(a), coordenador(a) pedagógico(a), orientador(a) educacional, supervisor(a) escolar e coordenador(a) de área de ensino' ,'profissionais-de-apoio-e-supervisao-pedagogica-ped' ,'true' ,0 ,'' ,'pedagogo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001263 ,4000238 ,'Secretário(a) escolar' ,'secretarioa-escolar' ,'true' ,0 ,'' ,'secretario' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001264 ,4000238 ,'Segurança, guarda ou segurança patrimonial' ,'seguranca-guarda-ou-seguranca-patrimonial' ,'true' ,0 ,'' ,'seguranca' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001265 ,4000238 ,'Técnicos(as), monitores(as) ou auxiliares de laboratório(s)' ,'tecnicosas-monitoresas-ou-auxiliares-de-laboratori' ,'true' ,0 ,'' ,'auxiliar_laboratorio' );
            update avaliacaopergunta set db103_sequencial = 3000023 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Ensino Fundamental em ciclos:' , db103_identificador = 'ensino-fundamental-em-ciclos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 8 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'ensino_fundamental_em_ciclos' where db103_sequencial = 3000023;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000023;
            update avaliacaoperguntaopcao set db104_sequencial = 3000111 , db104_avaliacaopergunta = 3000023 , db104_descricao = 'NÃO' , db104_identificador = 'nao5cb8a22f836ca' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'ensino_fundamental_em_ciclos_nao' where db104_sequencial = 3000111;
            update avaliacaoperguntaopcao set db104_sequencial = 3000112 , db104_avaliacaopergunta = 3000023 , db104_descricao = 'SIM' , db104_identificador = 'sim5cb8a22f83ccf' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'ensino_fundamental_em_ciclos_sim' where db104_sequencial = 3000112;
            update avaliacaopergunta set db103_sequencial = 3000153 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000013 , db103_descricao = 'Escola com proposta pedagogica de formação por alternância' , db103_identificador = 'EscolaFormacaoAlternancia' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 9 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'escola_com_proposta_pedagogica_de_formacao_por_alternancia' where db103_sequencial = 3000153;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000153;
            update avaliacaoperguntaopcao set db104_sequencial = 3000561 , db104_avaliacaopergunta = 3000153 , db104_descricao = 'Sim' , db104_identificador = 'PossuiFormacaoPorAlternancia' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_identificadorcampo = 'sim' where db104_sequencial = 3000561;
            update avaliacaoperguntaopcao set db104_sequencial = 3000562 , db104_avaliacaopergunta = 3000153 , db104_descricao = 'Não' , db104_identificador = 'NaoPossuiFormacaoPorAlternancia' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_identificadorcampo = 'nao' where db104_sequencial = 3000562;
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000239 ,3 ,3000013 ,'Forma(s) de organização do ensino' ,'formas-de-organizacao-do-ensino' ,'true' ,'true' ,10 ,1 ,'' ,0 ,'false' ,'0' ,'formas_organizacao_ensino' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000239;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001266 ,4000239 ,'Série/Ano (séries anuais)' ,'serieano-series-anuais' ,'false' ,0 ,'' ,'serie_ou_ano' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001267 ,4000239 ,'Períodos semestrais' ,'periodos-semestrais' ,'false' ,0 ,'' ,'periodos_semestrais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001268 ,4000239 ,'Ciclo(s) do ensino fundamental' ,'ciclos-do-ensino-fundamental' ,'false' ,0 ,'' ,'ciclos_ensino_fundamental' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001269 ,4000239 ,'Grupos não seriados com base na idade ou competência (art. 23 LDB)' ,'grupos-nao-seriados-com-base-na-idade-ou-competenc' ,'false' ,0 ,'' ,'grupos_nao_seriados' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001270 ,4000239 ,'Módulos' ,'modulos' ,'false' ,0 ,'' ,'modulos' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001271 ,4000239 ,'Alternância regular de períodos de estudos (proposta pedagógica de formação por alternância: tempo-escola e tempo-comunidade)' ,'alternancia-regular-de-periodos-de-estudos-propost' ,'false' ,0 ,'' ,'alterancia_regular_de_periodos' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000240 ,1 ,3000013 ,'A escola faz exame de seleção para ingresso de seus aluno(a)s (avaliação por prova e /ou analise curricular)' ,'a-escola-faz-exame-de-selecao-para-ingresso-de-seu' ,'true' ,'true' ,11 ,1 ,'' ,0 ,'false' ,'0' ,'exame_selecao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000240;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001272 ,4000240 ,'Sim' ,'sim5cb8cba1f0206' ,'false' ,0 ,'1' ,'exame_selecao_sim' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001273 ,4000240 ,'Não' ,'nao5cb8cba1f2a86' ,'false' ,0 ,'0' ,'exame_selecao_nao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000241 ,3 ,3000013 ,'Reserva de vagas por sistema de cotas para grupos específicos de aluno(a)s' ,'reserva-de-vagas-por-sistema-de-cotas-para-grupos-' ,'true' ,'true' ,12 ,1 ,'' ,0 ,'false' ,'0' ,'reserva_de_vagas' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000241;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001274 ,4000241 ,'Autodeclarado preto, pardo ou indígena (PPI)' ,'autodeclarado-preto-pardo-ou-indigena-ppi' ,'false' ,0 ,'' ,'preto_pardo_indigena' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001275 ,4000241 ,'Condição de renda' ,'condicao-de-renda' ,'false' ,0 ,'' ,'renda' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001276 ,4000241 ,'Oriundo de escola pública' ,'oriundo-de-escola-publica' ,'false' ,0 ,'' ,'escola_publica' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001277 ,4000241 ,'Pessoa com deficiência (PCD)' ,'pessoa-com-deficiencia-pcd' ,'false' ,0 ,'' ,'deficiencia' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001278 ,4000241 ,'Outros grupos que não os listados' ,'outros-grupos-que-nao-os-listados' ,'false' ,0 ,'' ,'cotas_outros' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001279 ,4000241 ,'Sem reservas de vagas para sistema de cotas (ampla concorrência)' ,'sem-reservas-de-vagas-para-sistema-de-cotas-ampla-' ,'false' ,0 ,'' ,'cotas_nenhum' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000242 ,1 ,3000013 ,'A escola possui site ou blog ou página em redes sociais para comunicação institucional' ,'a-escola-possui-site-ou-blog-ou-pagina-em-redes-so' ,'true' ,'true' ,13 ,1 ,'' ,0 ,'false' ,'0' ,'escola_possui_site' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000242;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001280 ,4000242 ,'Sim' ,'sim5cb8d1ca05349' ,'false' ,0 ,'' ,'escola_possui_site_sim' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001281 ,4000242 ,'Não' ,'nao5cb8d1ca0a671' ,'false' ,0 ,'' ,'escola_possui_site_nao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000243 ,1 ,3000013 ,'A escola usa espaços e equipamentos do entorno escolar para atividades regulares com os aluno(a)s' ,'a-escola-usa-espacos-e-equipamentos-do-entorno-esc' ,'true' ,'true' ,14 ,1 ,'' ,0 ,'false' ,'0' ,'escola_usa_entorno' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000243;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001282 ,4000243 ,'Sim' ,'sim5cb8d1ca0b9a4' ,'false' ,0 ,'' ,'escola_usa_entorno_sim' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001283 ,4000243 ,'Não' ,'nao5cb8d1ca0c0fa' ,'false' ,0 ,'' ,'escola_usa_entorno_nao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000244 ,3 ,3000013 ,'Órgãos colegiados em funcionamento na escola' ,'orgaos-colegiados-em-funcionamento-na-escola' ,'true' ,'true' ,15 ,1 ,'' ,0 ,'false' ,'0' ,'orgaos_colegiados' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000244;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001284 ,4000244 ,'Associacao de Pais' ,'associacao-de-pais' ,'false' ,0 ,'' ,'associacao_pais' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001285 ,4000244 ,'Associação de pais e mestres' ,'associacao-de-pais-e-mestres' ,'false' ,0 ,'' ,'associacao_pais_e_mestres' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001286 ,4000244 ,'Conselho escolar' ,'conselho-escolar' ,'false' ,0 ,'' ,'conselho_escolar' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001287 ,4000244 ,'Grêmio estudantil' ,'gremio-estudantil' ,'false' ,0 ,'' ,'gremio_estudantil' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001288 ,4000244 ,'Outros' ,'outros5cb8d368c1d04' ,'false' ,0 ,'' ,'orgaos_colegiados_outros' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001289 ,4000244 ,'Não há órgãos colegiados em funcionamento' ,'nao-ha-orgaos-colegiados-em-funcionamento' ,'false' ,0 ,'' ,'orgaos_colegiados_nenhum' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000245 ,1 ,3000013 ,'Projeto político pedagógico ou a proposta pedagógica da escola (conforme art. 12 da LDB) atualizado nos últimos 12 meses até a data de referência' ,'projeto-politico-pedagogico-ou-a-proposta-pedagogi' ,'true' ,'true' ,16 ,1 ,'' ,0 ,'false' ,'0' ,'projeto_pedagogico_atualizado' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000245;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001290 ,4000245 ,'Não' ,'nao5cb8d46199918' ,'false' ,0 ,'' ,'projeto_pedagogico_atualizado_nao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001291 ,4000245 ,'Sim' ,'sim5cb8d4619ec6c' ,'false' ,0 ,'' ,'projeto_pedagogico_atualizado_sim' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001292 ,4000245 ,'A escola não possui projeto político pedagógico/proposta pedagógica' ,'a-escola-nao-possui-projeto-politico-pedagogicopro' ,'false' ,0 ,'' ,'projeto_pedagogico_atualizado_nao_possui' );
            update avaliacaogrupopergunta set db102_sequencial = 3000014 , db102_avaliacao = 3000000 , db102_descricao = 'Escola cede espaço para turmas do Brasil Alfabetiz' , db102_identificador = 'escola-cede-espaco-para-turmas-do-brasil-alfabetiz' , db102_identificadorcampo = 'escola_cede_espaco_para_turmas_do_brasil_alfabetiz' , db102_ordem = 12 where db102_sequencial = 3000014;
            update avaliacaopergunta set db103_sequencial = 3000025 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000014 , db103_descricao = 'Escola cede espaço para turmas do Brasil Alfabetizado' , db103_identificador = 'escola-cede-espaco-para-turmas-do-brasil-alfabetiz' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'escola_cede_espaco_para_turmas_do_brasil_alfabetizado' where db103_sequencial = 3000025;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000025;
            update avaliacaoperguntaopcao set db104_sequencial = 3000122 , db104_avaliacaopergunta = 3000025 , db104_descricao = 'SIM' , db104_identificador = 'sim5cb8a22f861e9' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'escola_cede_espaco_para_turmas_do_brasil_alfabetizado_sim' where db104_sequencial = 3000122;
            update avaliacaoperguntaopcao set db104_sequencial = 3000123 , db104_avaliacaopergunta = 3000025 , db104_descricao = 'NÃO' , db104_identificador = 'nao5cb8a22f867a0' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'escola_cede_espaco_para_turmas_do_brasil_alfabetizado_nao' where db104_sequencial = 3000123;
            update avaliacaogrupopergunta set db102_sequencial = 3000015 , db102_avaliacao = 3000000 , db102_descricao = 'Escola abre aos finais de semana para a comunidade' , db102_identificador = 'escola-abre-aos-finais-de-semana-para-a-comunidade' , db102_identificadorcampo = 'escola_abre_aos_finais_de_semana_para_a_comunidade' , db102_ordem = 13 where db102_sequencial = 3000015;
            update avaliacaopergunta set db103_sequencial = 3000026 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000015 , db103_descricao = 'Escola abre aos finais de semana para a comunidade' , db103_identificador = 'escola-abre-aos-finais-de-semana-para-a-comunidade' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pregunta_escola_abre_aos_finais_de_semana_para_a_comunidade' where db103_sequencial = 3000026;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3000026;
            update avaliacaoperguntaopcao set db104_sequencial = 3000124 , db104_avaliacaopergunta = 3000026 , db104_descricao = 'SIM' , db104_identificador = 'sim5cb8a22f87a45' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'escola_abre_aos_finais_de_semana_para_a_comunidade_sim' where db104_sequencial = 3000124;
            update avaliacaoperguntaopcao set db104_sequencial = 3000125 , db104_avaliacaopergunta = 3000026 , db104_descricao = 'NÃO' , db104_identificador = 'nao5cb8a22f88014' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'escola_abre_aos_finais_de_semana_para_a_comunidade_nao' where db104_sequencial = 3000125;
            delete from avaliacaoperguntaopcao where db104_sequencial = 4001236;

            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao in (3000070, 3000072);
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3000070, 3000072));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3000070, 3000072);
            delete from avaliacaoperguntaopcao where db104_sequencial in (3000070, 3000072);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (
                select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (
                    4001195,
                    4001196,
                    4001197,
                    4001198,
                    4001199,
                    4001200,
                    4001201,
                    4001202,
                    4001203,
                    4001204,
                    4001205,

                    4001206,
                    4001207,
                    4001208,

                    4001209,
                    4001210,
                    4001211,

                    4001212,

                    4001213,

                    4001214,

                    4001215,

                    4001216,
                    4001217,
                    4001218,
                    4001219,
                    4001220,
                    4001221,
                    4001222,
                    4001223,
                    4001224,

                    4001225,
                    4001226,
                    4001227,
                    4001228,
                    4001229,
                    4001230,
                    4001231,
                    4001232,
                    4001233,
                    4001234,
                    4001235,
                    4001236,

                    4001237,
                    4001238,
                    4001239,
                    4001240,
                    4001241,

                    4001242,

                    4001243,
                    4001244,
                    4001245,
                    4001246,

                    4001247,
                    4001248,
                    4001249,
                    4001250,

                    4001251,

                    4001252,

                    4001253,
                    4001254,
                    4001255,
                    4001256,
                    4001257,
                    4001258,
                    4001259,
                    4001260,
                    4001261,
                    4001262,
                    4001263,
                    4001264,
                    4001265,

                    4001266,
                    4001267,
                    4001268,
                    4001269,
                    4001270,
                    4001271,

                    4001272,
                    4001273,

                    4001274,
                    4001275,
                    4001276,
                    4001277,
                    4001278,
                    4001279,

                    4001280,
                    4001281,

                    4001282,
                    4001283,

                    4001284,
                    4001285,
                    4001286,
                    4001287,
                    4001288,
                    4001289,

                    4001290,
                    4001291,
                    4001292
                )
            );

            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (
                4001195,
                4001196,
                4001197,
                4001198,
                4001199,
                4001200,
                4001201,
                4001202,
                4001203,
                4001204,
                4001205,

                4001206,
                4001207,
                4001208,

                4001209,
                4001210,
                4001211,

                4001212,

                4001213,

                4001214,

                4001215,

                4001216,
                4001217,
                4001218,
                4001219,
                4001220,
                4001221,
                4001222,
                4001223,
                4001224,

                4001225,
                4001226,
                4001227,
                4001228,
                4001229,
                4001230,
                4001231,
                4001232,
                4001233,
                4001234,
                4001235,
                4001236,

                4001237,
                4001238,
                4001239,
                4001240,
                4001241,

                4001242,

                4001243,
                4001244,
                4001245,
                4001246,

                4001247,
                4001248,
                4001249,
                4001250,

                4001251,

                4001252,

                4001253,
                4001254,
                4001255,
                4001256,
                4001257,
                4001258,
                4001259,
                4001260,
                4001261,
                4001262,
                4001263,
                4001264,
                4001265,

                4001266,
                4001267,
                4001268,
                4001269,
                4001270,
                4001271,

                4001272,
                4001273,

                4001274,
                4001275,
                4001276,
                4001277,
                4001278,
                4001279,

                4001280,
                4001281,

                4001282,
                4001283,

                4001284,
                4001285,
                4001286,
                4001287,
                4001288,
                4001289,

                4001290,
                4001291,
                4001292
            );

            delete from avaliacaoperguntaopcao where db104_sequencial in (
                4001195,
                4001196,
                4001197,
                4001198,
                4001199,
                4001200,
                4001201,
                4001202,
                4001203,
                4001204,
                4001205,

                4001206,
                4001207,
                4001208,

                4001209,
                4001210,
                4001211,

                4001212,

                4001213,

                4001214,

                4001215,

                4001216,
                4001217,
                4001218,
                4001219,
                4001220,
                4001221,
                4001222,
                4001223,
                4001224,

                4001225,
                4001226,
                4001227,
                4001228,
                4001229,
                4001230,
                4001231,
                4001232,
                4001233,
                4001234,
                4001235,
                4001236,

                4001239,
                4001240,
                4001241,

                4001242,

                4001243,
                4001244,
                4001245,
                4001246,

                4001247,
                4001248,
                4001249,
                4001250,

                4001251,

                4001252,

                4001253,
                4001254,
                4001255,
                4001256,
                4001257,
                4001258,
                4001259,
                4001260,
                4001261,
                4001262,
                4001263,
                4001264,
                4001265,

                4001266,
                4001267,
                4001268,
                4001269,
                4001270,
                4001271,

                4001272,
                4001273,

                4001274,
                4001275,
                4001276,
                4001277,
                4001278,
                4001279,

                4001280,
                4001281,

                4001282,
                4001283,

                4001284,
                4001285,
                4001286,
                4001287,
                4001288,
                4001289,

                4001290,
                4001291,
                4001292
            );

            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta in (
                4000229,
                4000230,
                4000231,
                4000232,
                4000233,
                4000234,
                4000235,
                4000236,
                4000237,
                4000238,
                4000239,
                4000240,
                4000241,
                4000242,
                4000243,
                4000244,
                4000245
            );

            delete from avaliacaopergunta where db103_sequencial in (
                4000229,
                4000230,
                4000231,
                4000232,
                4000233,
                4000234,
                4000235,
                4000236,
                4000237,
                4000238,
                4000239,
                4000240,
                4000241,
                4000242,
                4000243,
                4000244,
                4000245
            );
SQL
        );

        /*

         */
    }
}
