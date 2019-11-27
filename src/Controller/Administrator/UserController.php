<?php

namespace App\Controller\Administrator;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users", name="administrator_manage_users")
     */
    public function manage(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('administrator/user/manage.html.twig', [
            'users' => $users,
        ]);
    }
}
