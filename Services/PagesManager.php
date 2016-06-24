<?php

namespace nacholibre\PagesBundle\Services;

class PagesManager {
    function __construct($em, $config) {
        $this->className = $config['entity_class'];
        $this->em = $em;
    }

    public function getEntityClass() {
        return $this->className;
    }

    public function createPage() {
        return new $this->className;
    }

    public function getRepo() {
        $repo = $this->em->getRepository($this->className);
        return $repo;
    }

    public function getPages() {
        $repo = $this->em->getRepository($this->className);

        $pages = $repo->findBy();

        return $pages;
    }

    public function getPageBySlug($slug) {
        $repo = $this->em->getRepository($this->className);

        $page = $repo->findOnyBy([
            'slug' => $slug
        ]);

        return $pages;
    }
}
