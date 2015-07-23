<?php

namespace BooklistBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BooklistExtension extends \Twig_Extension {

    protected $bundle, $webpathResolver, $translator;

    public function __construct(ContainerInterface $container) {
        $this->bundle = $container->get('kernel')->getBundle('BooklistBundle');
        $this->webpathResolver = $container->get('booklist.webpath_resolver');
        $this->translator = $container->get('translator');
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('bool2human', array($this, 'bool2humanFilter')),
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
        return '<img src="' . $asset_url . '" alt="' . $alt . '" height="' . $height . '" />&nbsp;';
    }

    /**
     * Filter pour remplacer automatiquement les éditeur par leur image / logo
     * 
     * @param String $name Texte d'origine
     * @return String Code HTML
     */
    public function editorFilter($name) {
        $code = str_replace(array(
            'Pocket',
            'Folio',
            'Livre de poche'
                ), array(
            $this->constructImage('editor/pocket.png', 'Pocket'),
            $this->constructImage('editor/folio.png', 'Folio'),
            $this->constructImage('editor/livre_de_poche.png', 'Livre de poche')
                ), $name);

        switch ($name) {
            case 'GF Flammarion':
                $code = $this->constructImage('editor/gf_flammarion.png', 'GF Flammarion');
                break;

            case 'Folio plus classique':
                $code = $this->constructImage('editor/folio_plus_classique.png', 'Folio plus classique');
                break;

            case 'Biblio collège':
                $code = $this->constructImage('editor/hachette.png');
                $code .= ' <span style = "background-color:#00B8E6;padding:2px;color:#FFFFFF;font-family:serif">bibliocollège</span>';
                break;

            case 'Biblio lycée':
                $code = $this->constructImage('editor/hachette.png');
                $code .= ' <span style = "background-color:#db0c5e;padding:2px;color:#FFFFFF;font-family:serif">bibliolycée</span>';
                break;

            default:
                break;
        }

        return '<span style = "font-family:serif">' . $code . '</span>';
    }

    public function bool2humanFilter($bool) {
        if ($bool) {
            return $this->translator->trans('Yes');
        } else {
            return $this->translator->trans('No');
        }
    }

    public function getName() {
        return 'booklist_extension';
    }

}
