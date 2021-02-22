<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Service\StageHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 *
 * @Route("/{_locale}/")
 */


class StageController extends AbstractController
{
    /**
     * Lister tous les stages.
     * @Route("/stage/", name="stage.list")
     * @param EntityManagerInterface $em
     * @param StageHistoryService $stageHistoryService
     * @return Response
     */
    public function list(EntityManagerInterface $em, StageHistoryService $stageHistoryService) : Response
    {
        $stages = $this->getDoctrine()->getRepository(Stage::class)->getStagesNonExpires();

        /*$query = $em->createQuery(
            'SELECT s FROM App:Stage s WHERE s.date_expiration > :date'
        )->setParameter('date', new \DateTime()); $stages = $query->getResult();
        $stages = $query->getResult();*/

        return $this->render('stage/list.html.twig', [
            'stages' => $stages,
            'historyStages' => $stageHistoryService->getStages(),
            ]);
    }

    /**
     * Chercher et afficher un stage.
     * @Route("/stage/{slug}", name="stage.show")
     * @param Stage $stage
     * @param StageHistoryService $stageHistoryService
     * @return Response
     */
    public function show(Stage $stage, StageHistoryService $stageHistoryService) : Response
    {
        $stageHistoryService->addStage($stage);

        return $this->render('stage/show.html.twig', [
            'stage' => $stage, ]);
    }

    /**
     * Créer un nouveau stage.
     * @Route("/nouveau-stage", name="stage.create") * @param Request $request
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $stage = new Stage();

        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stage);
            $em->flush();
            return $this->redirectToRoute('stage.list');
        }
        return $this->render('stage/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => $stage->getId() !== null,
            ]);
    }

    /**
     * Éditer un stage.
     * @Route("stage/{slug}/edit", name="stage.edit") * @param Request $request
     * @param Request $request
     * @param Stage $stage
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Stage $stage, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(StageType::class, $stage); $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('stage.list');
        }

        return $this->render('stage/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => $stage->getId() !== null,
            ]);
    }


    /**
     * Supprimer un stage.
     * @Route("stage/{slug}/delete", name="stage.delete") * @param Request $request
     * @param Request $request
     * @param Stage $stage
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request, Stage $stage, EntityManagerInterface $em) : Response
    {
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('stage.delete', ['slug' => $stage->getSlug()]))
                     ->getForm();
        $form->handleRequest($request);

        if ( ! $form->isSubmitted() || ! $form->isValid()) {

            return $this->render('stage/delete.html.twig', [ 'stage' => $stage,
                'form' => $form->createView(),
            ]);
        }
        $em = $this->getDoctrine()->getManager(); $em->remove($stage);
        $em->flush();

        return $this->redirectToRoute('stage.list');
    }
}
