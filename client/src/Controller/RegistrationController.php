<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    public function __invoke(): Response
    {
        return new Response('TODO');
    }
}
