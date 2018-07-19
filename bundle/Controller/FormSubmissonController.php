<?php

namespace Novactive\Bundle\FormBuilderBundle\Controller;

use Doctrine\ORM\EntityManager;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FormSubmissonsController.
 *
 * @Route("/form-builder/sumbissions")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Controller
 */
class FormSubmissonController extends Controller
{
    const RESULTS_PER_PAGE = 10;

    /**
     * @Route("/list/{page}", name="form_builder_submission_list", requirements={"page" = "\d+"})
     *
     * @param EntityManager $em
     * @param int           $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(EntityManager $em, $page = 1)
    {
        $queryBuilder = $em->createQueryBuilder()
            ->select('f')
            ->from(FormSubmission::class, 'f');

        $paginator = new Pagerfanta(
            new DoctrineORMAdapter($queryBuilder)
        );
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        return $this->render('@FormBuilder/form_builder/submission/list.html.twig', [
            'totalCount'  => $paginator->getNbResults(),
            'submissions' => $paginator,
        ]);
    }

    /**
     * @Route("/{id}", name="form_builder_submission_show")
     *
     * @param FormSubmission $formSubmission
     */
    public function showAction(FormSubmission $formSubmission)
    {
        return $this->render('@FormBuilder/form_builder/submission/show.html.twig', [
            'formSubmission' => $formSubmission,
        ]);
    }
}
