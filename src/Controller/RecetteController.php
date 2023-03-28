<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'recette.index', methods:['GET'])]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            5
        );
        // $recettes = $repository->findAll();
        // dd($recettes);
        return $this->render('pages/recette/index.html.twig', [
            'recettes'=>$recettes
        ]);
    }

    #[Route("/recette/new", name:"recette.new", methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $recette  = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();
            return $this->redirectToRoute('recette.index');
        }

        return $this->render('pages/recette/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    #[Route(path: "/recette/edit/{id}", name: 'recette.edit', methods: ['GET','POST'])]
    public function edit(
        Request $request,
        Recette $recette,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();
            return $this->redirectToRoute('recette.index');
        }

        return $this->render('pages/recette/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}
