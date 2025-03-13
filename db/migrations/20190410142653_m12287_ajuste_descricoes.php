<?php

use Classes\PostgresMigration;

class M12287AjusteDescricoes extends PostgresMigration
{
    public function up()
    {
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do município (Conforme tabela do IBGE)' where db103_sequencial = 3000664");
        $this->execute("update avaliacaopergunta set db103_descricao = 'UF (Informe a sigla do estado)' where db103_sequencial = 3000665");
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do país de nascimento (Conforme tabela 06 - Países)' where db103_sequencial = 3000666");
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do país de nacionalidade (Conforme tabela 06 - Países)' where db103_sequencial = 3000667");
        $this->execute("update avaliacaogrupopergunta set db102_descricao = 'Informações de afastamento do trabalhador' where db102_sequencial = 3000189");
    }

    public function down()
    {
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do município, conforme tabela do IBGE' where db103_sequencial = 3000664");
        $this->execute("update avaliacaopergunta set db103_descricao = 'UF' where db103_sequencial = 3000665");
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do país de nascimento (Conforme tabela 06 - Paises)' where db103_sequencial = 3000666");
        $this->execute("update avaliacaopergunta set db103_descricao = 'Código do país de nacionalidade (Conforme tabela 06 - Paises)' where db103_sequencial = 3000667");
        $this->execute("update avaliacaogrupopergunta set db102_descricao = 'Afastamento' where db102_sequencial = 3000189");
    }
}
