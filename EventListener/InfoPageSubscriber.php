<?php
namespace nacholibre\PagesBundle\EventListener;

use nacholibre\AdminBundle\Utils\Slugger;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class InfoPageSubscriber implements EventSubscriber {
    function __construct($container) {
        $this->postClass = $container->getParameter('nacholibre_pages')['entity_class'];
        $this->slugger = new Slugger();
    }

    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'prePersist',
        );
    }

    //public function postUpdate(LifecycleEventArgs $args)
    //{
    //    $this->index($args);
    //}

    //public function postPersist(LifecycleEventArgs $args)
    //{
    //    $this->index($args);
    //}

    public function preUpdate(LifecycleEventArgs $args) {
        $this->index($args);
        //$post->setModifiedAt(new \Datetime());
        //$slug = $this->slugger->slugify($post->getTitle());
        //$post->setSlug($slug);
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        // perhaps you only want to act on some "Product" entity
        if ($post instanceof $this->postClass) {
            $post->setDateModified(new \Datetime());
            $slug = $this->slugger->slugify($post->getName());
            $post->setSlug($slug);
            //$entityManager = $args->getEntityManager();
            // ... do something with the Product
        }
    }
}
