<?php

namespace BooklistBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {

    public function mainMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Liste des livres', array('route' => 'book_list'));
        $menu->addChild('Ajouter un livre', array('route' => 'book_new'));

        return $menu;
    }

}
