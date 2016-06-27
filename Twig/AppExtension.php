<?php
namespace nacholibre\PagesBundle\Twig;

class AppExtension extends \Twig_Extension {
    public function __construct($container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('nacholibre_info_page_link', [$this, 'pageLink']),
        ];
    }

    public function pageLink($slug) {
        $router = $this->container->get('router');

        $parameters = $this->container->getParameter('nacholibre_pages');
        $type = $parameters['urls']['type'];

        switch($type) {
        case "slug":
            $url = $router->generate('nacholibre.info_page.show', [
                'slug' => $slug,
            ]);
            break;
        //case "slug_id":
        //    $url = $router->generate('nacholibre.news.show', [
        //        'slug' => $post->getSlug(),
        //        'id' => $post->getID(),
        //    ]);
        //    break;
        }
        return $url;
    }

    public function getName()
    {
        return 'pages_extension';
    }
}
