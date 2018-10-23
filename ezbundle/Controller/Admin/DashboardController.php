<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\eZFormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Novactive\Bundle\FormBuilderBundle\Core\FormFactory;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DashboardController
{
    const RESULTS_PER_PAGE = 10;

    /**
     * @Route("/edit/{id}", name="novaezformbuilder_dashboard_edit")
     * @Route("/create", name="novaezformbuilder_dashboard_create")
     * @Template("@ezdesign/novaezformbuilder/edit.html.twig")
     */
    public function edit(
        ?Form $formData,
        RouterInterface $router,
        Request $request,
        FormFactory $factory,
        EntityManagerInterface $entityManager
    ) {
        $originalFields = new ArrayCollection();
        if (null === $formData) {
            $formData = new Form();
        } else {
            foreach ($formData->getFields() as $field) {
                $originalFields->add($field);
            }
        }
        $form = $factory->createEditForm($formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalFields as $field) {
                /** @var Field $field */
                if (!$formData->getFields()->contains($field)) {
                    $field->setForm(null);
                    $entityManager->persist($field);
                    $entityManager->remove($field);
                }
            }
            $entityManager->persist($formData);
            $entityManager->flush();

            return new RedirectResponse($router->generate('novaezformbuilder_dashboard_index'));
        }

        return [
            'title' => 'novaezformbuilder.title.edit_form',
            'form'  => $form->createView(),
        ];
    }

    /**
     * @Route("/{page}", name="novaezformbuilder_dashboard_index", requirements={"page" = "\d+"})
     * @Template("@ezdesign/novaezformbuilder/index.html.twig")
     */
    public function index(EntityManagerInterface $entityManager, int $page = 1): array
    {
        $queryBuilder = $entityManager->createQueryBuilder()->select('f')->from(Form::class, 'f');

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        return [
            'totalCount' => $paginator->getNbResults(),
            'forms'      => $paginator,
        ];
    }
}
