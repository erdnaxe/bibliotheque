<?php

namespace BooklistBundle;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class WebPathResolver {

    /**
     * Gets the prefix of the asset with the given bundle
     *
     * @param BundleInterface $bundle Bundle to fetch in
     *
     * @throws \InvalidArgumentException
     * @return string Prefix
     */
    public function getPrefix(BundleInterface $bundle) {
        if (!is_dir($bundle->getPath() . '/Resources/public')) {
            throw new \InvalidArgumentException(sprintf(
                    'Bundle %s does not have Resources/public folder', $bundle->getName()
            ));
        }

        return sprintf(
                '/bundles/%s', preg_replace('/bundle$/', '', strtolower($bundle->getName()))
        );
    }

    /**
     * Get path
     *
     * @param BundleInterface $bundle   Bundle to fetch in
     * @param string          $type     Which folder to fetch in (images, css..)
     * @param string          $resource Resource (image1.png)
     *
     * @return string Resolved path
     */
    public function getPath(BundleInterface $bundle, $type, $resource) {
        $prefix = $this->getPrefix($bundle);

        return sprintf('%s/%s/%s', $prefix, $type, $resource);
    }

}
