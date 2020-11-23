<?php declare(strict_types=1);

namespace App\Controller;

use App\Dto\User;
use App\Form\Type\DeleteUserType;
use App\Form\Type\UserType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function users(?int $id, Request $request): Response
    {
        $user = $id ? $this->getUserById($id) : new User();
        $form = $this->createForm(UserType::class, $user, ['action' => $this->generateUrl('users', [
            'id' => $user->id,
        ])]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $validation = $this->userManager->save($user);
            if (! isset($validation['violations'])) {
                return $this->redirectToRoute('users');
            }
            foreach ($validation['violations'] as $violation) {
                $form->get($violation->propertyPath)->addError(new FormError($violation->message));
            }
        }
        return $this->render('users.html.twig', [
            'userId' => $user->id,
            'users' => $this->userManager->getAll(),
            'userForm' => $form->createView(),
            'deleteUserForm' => $this->createDeleteUserForm($user)->createView(),
        ]);
    }

    public function deleteUser(Request $request)
    {
        $user = new User();
        $form = $this->createDeleteUserForm($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUserById($user->id);
            $this->userManager->delete($user->id);
        }
        return $this->redirectToRoute('users');
    }

    private function createDeleteUserForm(User $user): FormInterface
    {
        return $this->createForm(DeleteUserType::class, $user, [
            'action' => $this->generateUrl('delete_user'),
        ]);
    }

    private function getUserById($id): User
    {
        $user = $this->userManager->getById($id);
        if ($user) {
            return $user;
        }
        throw $this->createNotFoundException("User #$id not found");
    }
}
