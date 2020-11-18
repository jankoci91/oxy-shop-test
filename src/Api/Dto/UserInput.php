<?php declare(strict_types=1);

namespace App\Api\Dto;

use App\Api\NullObject;
use App\Entity\User as UserEntity;
use Symfony\Component\Validator\Constraints as Assert;

class UserInput
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=180)
     */
    public $name;
    /**
     * @var string
     *
     * @see \App\Api\UserInputTransformer::transform with additional constraints
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=180)
     * @Assert\Email()
     */
    public $email;
    /**
     * @var string
     *
     * @see \App\Api\UserInputTransformer::transform with additional constraints
     *
     * @Assert\Length(min=5)
     */
    public $password;
    /**
     * @var array
     *
     * @Assert\Choice(multiple=true, choices=UserEntity::ALL_ROLES)
     */
    public $roles;

    public function __construct()
    {
        $this->name = NullObject::getInstance();
        $this->email = NullObject::getInstance();
        // password is omitted intentionally
        $this->roles = NullObject::getInstance();
    }
}
