<?php

namespace App\Controller\Administrator;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserAdminType;
use App\Form\UserChangePasswordType;
use App\Repository\UserRepository;
use App\Security\PasswordChanger;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users/{id}/remove", name="administrator_remove_user")
     */
    public function remove(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->getId() == $this->getUser()->getId()) {
            $this->addFlash('danger', 'Nemůžete smazat svůj vlastní účet. Přidělte práva někomu jinému a požádejte ho, aby váš účet smazal.');
            return new RedirectResponse($this->generateUrl('administrator_manage_user', ['id' => $user->getId()]));
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Uživatel byl odstraněn.');
        return new RedirectResponse($this->generateUrl('administrator_manage_users'));
    }

    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users/add", name="administrator_add_user")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, PasswordChanger $passwordChanger)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordChanger->changePassword($user, $form->get('plainPassword')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Nový uživatel byl vytvořen.');
            return new RedirectResponse($this->generateUrl('administrator_manage_users'));
        }

        return $this->render('administrator/user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users/{id}", name="administrator_manage_user")
     */
    public function manageUser(User $user, Request $request, EntityManagerInterface $entityManager, PasswordChanger $passwordChanger)
    {
        $editForm = $this->createForm(UserAdminType::class, $user);
        $editForm->handleRequest($request);

        $passwordForm = $this->createForm(UserChangePasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Uživatel byl upraven.');
            return new RedirectResponse($this->generateUrl('administrator_manage_user', ['id' => $user->getId()]));
        }

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $passwordChanger->changePassword($user, $passwordForm->get('plainPassword')->getData());
            $this->addFlash('success', 'Heslo bylo změněno.');
            return new RedirectResponse($this->generateUrl('administrator_manage_user', ['id' => $user->getId()]));
        }

        return $this->render('administrator/user/edit.html.twig', [
            'user' => $user,
            'editForm' => $editForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
