<?php
namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    #[Route('/personnes', name: 'personne.list')]
    public function list(PersonneRepository $repository): Response
    {
        $personnes = $repository->findAll();
        return $this->render('list.html.twig', [
            'personnes' => $personnes
        ]);
    }

    #[Route('/personnes/ajouter', name: 'personne.add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();

        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'La personne a été ajoutée !');
            $personne = $form->getData();
            $entityManager->persist($personne);
            $entityManager->flush();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('personne.add');
        }

        return $this->renderForm('add.html.twig', [
            'form' => $form,
        ]);
    }
}
