<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function default()
    {
        return new Response("<html><body>homepage</body></html>");
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function admin()
    {
        return new Response("<html><body>admin</body></html>");
    }
}
