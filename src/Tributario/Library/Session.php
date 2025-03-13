<?php 

namespace ECidade\Tributario\Library;

use \DateTime;

final class Session
{
    public function get($value)
    {
        return db_getsession($value);
    }

    public function set($name, $value)
    {
        db_putsession($name, $value);
    }

    public function getAno()
    {
        return date("Y", $this->get('DB_datausu'));
    }

    public function getData()
    {
        return new DateTime(date("Y-m-d", db_getsession("DB_datausu")));
    }

    public function getHora()
    {
        return date("H:i:s", db_getsession("DB_datausu"));
    }

    public function getUsuarioId()
    {
        return db_getsession("DB_id_usuario");
    }

    public function getInstituicao()
    {
        return db_getsession("DB_instit");
    }

    public function getIp()
    {
        return db_getsession('DB_ip');
    }
}
