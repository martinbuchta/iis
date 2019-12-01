<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Form\UserChangePasswordType;
use App\Security\PasswordChanger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, PasswordChanger $passwordChanger)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Váš profil byl upraven.');
            return new RedirectResponse($request->getUri());
        }

        $passwordForm = $this->createForm(UserChangePasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $passwordChanger->changePassword($user, $passwordForm->get('plainPassword')->getData());
            $this->addFlash('success', 'Vaše heslo bylo změněno.');
            return new RedirectResponse($request->getUri());
        }

        return $this->render('profile.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'passwdForm' => $passwordForm->createView(),
        ]);
    }
}
