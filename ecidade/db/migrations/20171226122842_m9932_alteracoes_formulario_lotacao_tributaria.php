<?php

use Classes\PostgresMigration;

class M9932AlteracoesFormularioLotacaoTributaria extends PostgresMigration
{
    public function up()
    {
        $this->execute("update avaliacaopergunta set db103_tipo = 6 where db103_sequencial in (3000865, 3000867, 3000868);");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '09' where db104_sequencial = 3003566;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '08' where db104_sequencial = 3003565;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '07' where db104_sequencial = 3003564;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '06' where db104_sequencial = 3003563;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '05' where db104_sequencial = 3003562;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '04' where db104_sequencial = 3003561;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '03' where db104_sequencial = 3003560;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '02' where db104_sequencial = 3003559;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '01' where db104_sequencial = 3003558;");


    }

    public function down()
    {
        $this->execute("update avaliacaopergunta set db103_tipo = 1 where db103_sequencial in (3000865, 3000867, 3000868);");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '9' where db104_sequencial = 3003566;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '8' where db104_sequencial = 3003565;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '7' where db104_sequencial = 3003564;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '6' where db104_sequencial = 3003563;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '5' where db104_sequencial = 3003562;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '4' where db104_sequencial = 3003561;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '3' where db104_sequencial = 3003560;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '2' where db104_sequencial = 3003559;");
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = '1' where db104_sequencial = 3003558;");
    }
}
