<?php

namespace App;

use Encriptacao;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * @var string
     */
    protected $table = 'configuracoes.db_usuarios';

    /**
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getAttribute('senha');
    }

    /**
     * @param string $login
     * @return User|null
     */
    public function findForPassport($login)
    {
        return $this->where('login', $login)->first();
    }

    /**
     * @param string $password
     * @return boolean
     */
    public function validateForPassportPasswordGrant($password)
    {
        return app()->environment('local') || Encriptacao::encriptaSenha($password) === $this->getAuthPassword();
    }

    /**
     * Retorna o usuário do e-cidade pelo CPF ou CNPJ
     * @param $sCpfCnpj
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUserEcidadeByCpfCnpj($sCpfCnpj)
    {
        $aUser = $this->join(
            "configuracoes.db_usuacgm",
            "db_usuacgm.id_usuario",
            "=",
            "db_usuarios.id_usuario"
        )->join(
            "cgm",
            "z01_numcgm",
            "=",
            "cgmlogin"
        )->where(
            "z01_cgccpf",
            "=",
            $sCpfCnpj
        )->where(
            "usuarioativo",
            "=",
            "1"
        )->where(
            "usuext",
            "!=",
            "2"
        )->get([
            "db_usuarios.id_usuario",
            "login",
            "usuext",
            "z01_nome",
            "z01_nomecomple",
            "z01_ender",
            "z01_numero",
            "z01_compl",
            "z01_bairro",
            "z01_munic",
            "z01_uf",
            "z01_cep",
            "z01_nasc"
        ]);

        return $aUser;
    }
}
