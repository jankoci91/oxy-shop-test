<?php declare(strict_types=1);

namespace App\Dto;

class User
{
    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const ROLES = 'roles';

    public $id;
    public $name;
    public $email;
    public $password;
    public $roles;
}
