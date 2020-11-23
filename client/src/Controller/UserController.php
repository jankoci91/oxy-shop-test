<?php declare(strict_types=1);

namespace App\Controller;

use App\Dto\User;
use App\Form\Type\UserType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function __invoke(?int $id, Request $request, UserManager $userManager): Response
    {
        // todo: delete
        $user = $id ? $userManager->getById($id) : null;
        if ($id && empty($user)) {
            throw $this->createNotFoundException("User #$id not found");
        }
        $user = $user ?? new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $userManager->save($user);
            // todo: validation
            return $this->redirectToRoute('users');
        }
        return $this->render('users.html.twig', [
            'users' => $this->isGranted('ROLE_ADMIN')
                ? $userManager->getAll()
                : $userManager->getByEmail($this->getUser()->getUsername()),
            'userForm' => $form->createView(),
        ]);
    }
}
