<?php

namespace BooklistBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BooklistExtension extends \Twig_Extension {

    protected $bundle, $webpathResolver;

    public function __construct(ContainerInterface $container) {
        $this->bundle = $container->get('kernel')->getBundle('BooklistBundle');
        $this->webpathResolver = $container->get('booklist.webpath_resolver');
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('editor', array($this, 'editorFilter')),
        );
    }

    /**
     * Fonction pour rapidement construire une image
     * 
     * @param String $img Chemain de l'iage dans le dossier images
     * @param String $alt Texte alternatif
     * @param String $height Hauteur de l'image
     * @return String HTML
     */
    private function constructImage($img, $alt = '', $height = '16') {
        $asset_url = $this->webpathResolver->getPath($this->bundle, 'images', $img);
        return '<img src="' . $asset_url . '" alt="' . $alt . '" height="' . $height . '" />';
    }

    /**
     * Filter pour remplacer automatiquement les éditeur par leur image / logo
     * 
     * @param String $name Texte d'origine
     * @return String Code HTML
     */
    public function editorFilter($name) {
        switch ($name) {
            case 'GF Flammarion':
                $name = $this->constructImage('editor/gf_flammarion.png', 'GF Flammarion');
                break;

            case 'Folio':
                $name = $this->constructImage('editor/folio.png', 'Folio');
                break;

            case 'Folio classique':
                $name = $this->constructImage('editor/folio_classique.png', 'Folio classique');
                break;

            case 'Folio plus classique':
                $name = $this->constructImage('editor/folio_plus_classique.png', 'Folio plus classique');
                break;

            case 'Pocket':
                $name = $this->constructImage('editor/pocket.png', 'Pocket');
                break;

            case 'Biblio collège':
                $name = $this->constructImage('editor/hachette.png');
                $name .= ' <span style = "background-color:#00B8E6;padding:2px;color:#FFFFFF;font-family:serif">bibliocollège</span>';
                break;

            case 'Biblio lycée':
                $name = $this->constructImage('editor/hachette.png');
                $name .= ' <span style = "background-color:#db0c5e;padding:2px;color:#FFFFFF;font-family:serif">bibliolycée</span>';
                break;

            default:
                break;
        }

        return $name;
    }

    public function getName() {
        return 'booklist_extension';
    }

}
