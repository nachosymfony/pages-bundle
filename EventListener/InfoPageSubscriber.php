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
        $entity = $args->getEntity();

        //if ($entity instanceof \Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation) {
        //    print_R($entity);
        //    return;
        //    $em = $args->getEntityManager();

        //    $translationEntity = $entity;
        //    if ($entity->getObjectClass() == $this->postClass) {
        //        if (@$entity->__toBePersisted) {
        //            return;
        //        }
        //        print_R($entity);

        //        if ($entity->getField() == 'name') {
        //            $slugTrans = new \Gedmo\Translatable\Entity\Translation;
        //            $slugTrans->setLocale('en3');
        //            $slugTrans->setObjectClass($this->postClass);
        //            $slugTrans->setField('slug');
        //            $slugTrans->setForeignKey($translationEntity->getForeignKey());

        //            $slug = $this->slugger->slugify($entity->getContent());
        //            $slugTrans->setContent($slug);
        //            $slugTrans->__toBePersisted = 1;
        //            $em->persist($slugTrans);
        //            $em->flush();
        //        }
        //    }
        //    //print_R($translationEntity);
        //    //echo 'persist translation';
        //    //exit;
        //}

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof $this->postClass) {
            $post = $entity;
            //if (@$post->__translated) {
            //    return;
            //}
            $post->setDateModified(new \Datetime());
            $em = $args->getEntityManager();

            //if (!$post->getStatic()) {
            //    $slug = $this->slugger->slugify($post->getName());
            //    $post->setSlug($slug);
            //}

            //if ($post instanceof \Gedmo\Translatable\Translatable) {
            //    $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
            //    $repository->translate($post, 'slug', 'en', $slug);
            //    $post->__translated = 1;
            //    $em->persist($post);
            //    $em->flush();
            //}

            // ... do something with the Product
        }
    }
}
