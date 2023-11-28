<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleType;  //permet de donner des types de roles
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserControllerPhpController extends AbstractController
{
    private $managerRegistry;
    private $requestStack;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
    }

    #[Route('/user/{id}/edit-role', name: 'user_edit_role')]
    #[IsGranted("ROLE_ADMIN")]
    public function editRole($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas.');
        }

        $form = $this->createForm(UserRoleType::class, $user);  // Utilisez UserRoleType ici
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le rôle de l\'utilisateur a été modifié avec succès.');
            return $this->redirectToRoute('listusers'); // Remplacez par la route où vous affichez la liste des utilisateurs
        }

        return $this->render('user/edit_role.html.twig', [
            'form' => $form->createView(),
            'user' => $user,

        ]);
    }


}
