<?php

namespace nacholibre\PagesBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use nacholibre\PagesBundle\Form\InfoPageType;

class PagesController extends Controller {
    /**
     * @Route("/", name="nacholibre.info_page.admin.index")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pagesManager = $this->get('nacholibre.pages.manager');
        $repo = $pagesManager->getRepo();

        $pages = $repo->findAll();

        $allowAdd = $this->getParameter('nacholibre_pages')['allow_add'];

        //$pagesManager = $this->get('nacholibre.pages.manager');

        //$repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        //$page = $pagesManager->createPage();
        //$page->setName('aaaaaadddddddd');
        //$page->setTranslatableLocale('en_en');

        //$repository->translate($page, 'name', 'bg', 'name bg');

        //$em->persist($page);
        //$em->flush();

        return $this->render('nacholibrePagesBundle:Admin:index.html.twig', [
            'pages' => $pages,
            'allowAdd' => $allowAdd,
        ]);
    }

    /**
     * @Route("/new", name="nacholibre.info_page.admin.new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $pagesManager = $this->get('nacholibre.pages.manager');
        $page = $pagesManager->createPage();

        $form = $this->createForm(InfoPageType::class, $page);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //$page = $form->getData();

            $page->setTranslatableLocale('en_en');

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('nacholibre.info_page.admin.index');
        }

        return $this->render('nacholibrePagesBundle:Admin:add_edit.html.twig', [
            'form' => $form->createView(),
            'headerTitle' => 'Add Page',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="nacholibre.info_page.admin.edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id) {
        $pagesManager = $this->get('nacholibre.pages.manager');
        $repo = $pagesManager->getRepo();
        $page = $repo->find($id);

        $form = $this->createForm(InfoPageType::class, $page);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //$page = $form->getData();

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('nacholibre.info_page.admin.edit', array('id' => $page->getId()));
        }

        return $this->render('nacholibrePagesBundle:Admin:add_edit.html.twig', [
            'form' => $form->createView(),
            'headerTitle' => 'Edit Page',
        ]);
    }

    /**
     * @Route("/delete/{id}", name="nacholibre.info_page.admin.delete")
     */
    public function deleteAction($id) {
        $pagesManager = $this->get('nacholibre.pages.manager');
        $repo = $pagesManager->getRepo();
        $page = $repo->find($id);

        if ($page->getStatic()) {
            return $this->redirectToRoute('admin.info_page');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        return $this->redirectToRoute('nacholibre.info_page.admin.index');
    }
}
