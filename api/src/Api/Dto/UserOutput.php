<?php declare(strict_types=1);

namespace App\Api\Dto;

class UserOutput
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;
    /**
     * @var array
     */
    public $roles;
}
