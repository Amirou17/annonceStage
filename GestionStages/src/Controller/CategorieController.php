<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function list(): Response
    {

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->getCategoriesAvecStagesNonExpires();

        return $this->render('categorie/list.html.twig', [
            'categories' => $categorie,
        ]);
    }

}
