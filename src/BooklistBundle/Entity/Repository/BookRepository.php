<?php

namespace BooklistBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository {

    public function findAll() {
        return $this->findBy(array(), array('editor' => 'ASC', 'writer' => 'ASC', 'title' => 'ASC'));
    }

}
