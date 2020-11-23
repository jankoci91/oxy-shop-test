<?php declare(strict_types=1);

namespace App\Form\Type;

use App\Dto\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(User::ID, HiddenType::class, [
                'required' => true,
            ])
            ->get(User::ID)->addModelTransformer(new CallbackTransformer(
                function ($id) {
                    return strval($id);
                },
                function ($id) {
                    return intval($id);
                }
            ));
    }
}
