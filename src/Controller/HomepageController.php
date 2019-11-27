<?php

namespace App\Controller;

use App\Security\SecurityVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     */
    public function admin(SecurityVoter $securityVoter)
    {
        $securityVoter->onlyRedaktorOrPokladni();

        return $this->render('admin_dashboard.html.twig', [

        ]);
    }
}
