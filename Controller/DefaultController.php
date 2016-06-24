<?php

namespace nacholibre\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function slugAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $pagesManager = $this->get('nacholibre.pages.manager');
        $repo = $pagesManager->getRepo();

        $page = $repo->findOneBy([
            'slug' => $slug
        ]);

        if (!$page) {
            throw $this->createNotFoundException('Page not found!');
        }

        return $this->render('nacholibrePagesBundle:Default:show.html.twig', [
            'page' => $page,
        ]);
    }
}
