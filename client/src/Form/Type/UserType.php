<?php declare(strict_types=1);

namespace App\Form\Type;

use App\Dto\User;
use App\Manager\UserManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(User::NAME, TextType::class, [
                'required' => false,
            ])
            ->add(User::EMAIL, TextType::class, [
                'required' => false,
            ])
            ->add(User::PASSWORD, PasswordType::class, [
                'required' => false,
            ])
            ->add(User::ROLES, ChoiceType::class, [
                'choices' => array_flip($this->userManager->getRoles()),
                'multiple' => true,
                'required' => false,
            ]);
    }
}
