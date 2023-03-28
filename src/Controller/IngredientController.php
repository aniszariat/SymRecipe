<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * this function display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name:'ingredient.index', methods:['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        // $ingredients = $repository->findAll();
        $ingredients =  $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        // dd($ingredients);
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients'=>$ingredients
        ]);
    }

    /**
     * function which creates a new ingredient and add to DB
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/ingredient/new", name:"ingredient.new", methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            // dd($form->getData());
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'successfully added ingredient !'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }


    #[Route("/ingredient/edit/{id}", name:"ingredient.edit", methods:['GET','POST'])]
    public function edit(
        Request $request,
        //! IngredientRepository $repository,
        //! int $id,
        Ingredient $ingredient,
        EntityManagerInterface $manager
    ): Response {
        //! $ingredient = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            // dd($form->getData());
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'successfully edited ingredient !'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }


    #[Route("/ingredient/delete/{id}", name :'ingredient.delete', methods:['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {
        if (! $ingredient) {
            $this->addFlash(
                'success',
                'ingredient not found !'
            );
        } else {
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'successfully deletd ingredient !'
            );
        }
        return $this->redirectToRoute('ingredient.index');
    }
}
