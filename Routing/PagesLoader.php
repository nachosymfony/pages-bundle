<?php
namespace nacholibre\PagesBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PagesLoader extends Loader {
    private $loaded = false;

    public function __construct($container) {
        $this->container = $container;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "pages" loader twice');
        }

        $routes = new RouteCollection();

        $parameters = $this->container->getParameter('nacholibre_pages');
        $type = $parameters['urls']['type'];
        $prefix = $parameters['urls']['prefix'];

        switch($type) {
        case "slug":
            $path = $prefix . '/{slug}/';
            $defaults = array(
                '_controller' => 'nacholibrePagesBundle:Default:slug',
            );
            $requirements = array(
            );
            break;
        //case "slug_id":
        //    $path = $prefix . '/{slug}-{id}/';
        //    $defaults = array(
        //        '_controller' => 'nacholibreNewsBundle:Default:slugID',
        //    );
        //    $requirements = array(
        //        'id' => '\d+',
        //    );
        //    break;
        }

        $route = new Route($path, $defaults, $requirements);

        // add the new route to the route collection
        $routeName = 'nacholibre.info_page.show';
        $routes->add($routeName, $route);

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'pages' === $type;
    }
}
