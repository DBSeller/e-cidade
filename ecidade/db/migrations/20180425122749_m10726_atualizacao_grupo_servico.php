<?php

use Classes\PostgresMigration;

class M10726AtualizacaoGrupoServico extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            update db_estruturavalor
            set db121_descricao = 'Processamento, armazenamento ou hospedagem de dados, textos, imagens, vídeos, páginas eletrônicas, aplicativos e sistemas de informação, entre outros formatos, e congêneres.'
            where db121_estrutural  = '01.03';
            
            update db_estruturavalor
            set db121_descricao = 'Elaboração de programas de computadores, inclusive de jogos eletrônicos, independentemente da arquitetura construtiva da máquina em que o programa será executado, incluindo tablets, smartphones e congêneres.'
            where db121_estrutural = '01.04';
            
            update db_estruturavalor
            set db121_descricao = 'Disponibilização, sem cessão definitiva, de conteúdos de áudio, vídeo, imagem e texto por meio da internet, respeitada a imunidade de livros, jornais e periódicos (exceto a distribuição de conteúdos pelas prestadoras de Serviço de Acesso Condicionado, de que trata a Lei no 12.485, de 12 de setembro de 2011, sujeita ao ICMS).'
            where db121_estrutural = '01.09';
            
            update db_estruturavalor
            set db121_descricao = 'Aplicação de tatuagens, piercings e congêneres.'
            where db121_estrutural = '06.06';
            
            update db_estruturavalor
            set db121_descricao = 'Florestamento, reflorestamento, semeadura, adubação, reparação de solo, plantio, silagem, colheita, corte e descascamento de árvores, silvicultura, exploração florestal e dos serviços congêneres indissociáveis da formação, manutenção e colheita de florestas, para quaisquer fins e por quaisquer meios.'
            where db121_estrutural = '07.16';
            
            update db_estruturavalor
            set db121_descricao = 'Vigilância, segurança ou monitoramento de bens, pessoas e semoventes.'
            where db121_estrutural = '11.02';
            
            update db_estruturavalor
            set db121_descricao = 'Composição gráfica, inclusive confecção de impressos gráficos, fotocomposição, clicheria, zincografia, litografia e fotolitografia, exceto se destinados a posterior operação de comercialização ou industrialização, ainda que incorporados, de qualquer forma, a outra mercadoria que deva ser objeto de posterior circulação, tais como bulas, rótulos, etiquetas, caixas, cartuchos, embalagens e manuais técnicos e de instrução, quando ficarão sujeitos ao ICMS.'
            where db121_estrutural = '13.05';
            
            update db_estruturavalor
            set db121_descricao = 'Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodização, corte, recorte, plastificação, costura, acabamento, polimento e congêneres de objetos quaisquer.'
            where db121_estrutural = '14.05';
            
            update db_estruturavalor
            set db121_descricao = 'Guincho intramunicipal, guindaste e içamento.'
            where db121_estrutural = '14.14';
            
            update db_estruturavalor
            set db121_descricao = 'Inserção de textos, desenhos e outros materiais de propaganda e publicidade, em qualquer meio (exceto em livros, jornais, periódicos e nas modalidades de serviços de radiodifusão sonora e de sons e imagens de recepção livre e gratuita).'
            where db121_estrutural = '17.25';
            
            update db_estruturavalor
            set db121_descricao = 'Translado intramunicipal e cremação de corpos e partes de corpos cadavéricos.'
            where db121_estrutural = '25.02';
            
            update db_estruturavalor
            set db121_descricao = 'Cessão de uso de espaços em cemitérios para sepultamento.'
            where db121_estrutural = '25.05';
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
            update db_estruturavalor
            set db121_descricao = 'Elaboração de programas de computadores, inclusive de jogos eletrônicos.'
            where db121_estrutural = '01.04';
            
            update db_estruturavalor
            set db121_descricao = 'Processamento de dados e congêneres.'
            where db121_estrutural = '01.03';
            
            update db_estruturavalor
            set db121_descricao = 'Florestamento, reflorestamento, semeadura, adubação e congêneres.'
            where db121_estrutural = '07.16';
            
            update db_estruturavalor
            set db121_descricao = 'Vigilância, segurança ou monitoramento de bens e pessoas.'
            where db121_estrutural = '11.02';
            
            update db_estruturavalor
            set db121_descricao = 'Composição gráfica, fotocomposição, clicheria, zincografia, litografia, fotolitografia.'
            where db121_estrutural = '13.05';
            
            update db_estruturavalor
            set db121_descricao = 'Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodização, corte, recorte, polimento, plastificação e congêneres, de objetos quaisquer.'
            where db121_estrutural = '14.05';
            
            update db_estruturavalor
            set db121_descricao = 'Cremação de corpos e partes de corpos cadavéricos.'
            where db121_estrutural = '25.02';
            
            update db_estruturavalor
            set db121_descricao = 'Guincho intramunicipal, guindastes e içamento.'
            where db121_estrutural = '14.14';
            
            update db_estruturavalor
            set db121_descricao = 'Disponibilização, sem cessão definitiva, de conteúdo de áudio, vídeo, imagem e texto por meio de internet, respeitada a imunidade de livros, jornais e periódicos (exceto a distribuição de conteúdos pelas prestadoras de Serviço de Acesso Condicionado, de que trata a Lei nº 12.485 de 12 de setembro de 2011, sujeita ao ICMS)'
            where db121_estrutural = '01.09';
            
            update db_estruturavalor
            set db121_descricao = 'Aplicação de tatuagens, piercings e congêneres'
            where db121_estrutural = '06.06';
            
            update db_estruturavalor
            set db121_descricao = 'Inserção de textos, desenhos e outras materiais de propaganda e publicidade, em qualquer meio (exceto em livros, jornais, periódicos e nas modalidades de serviços de radiodifusão sonora e de sons e imagens de recepção livre gratuita)'
            where db121_estrutural = '17.25';
            
            update db_estruturavalor
            set db121_descricao = 'Cessão de uso de espaços em cemitérios para sepultamento'
            where db121_estrutural = '25.05';
        ";
        $this->execute($sSql);
    }
}
