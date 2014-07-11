<?php

/*
 * This file is part of the Fxp Composer Asset Plugin package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Composer\AssetPlugin\Installer;

use Composer\Package\PackageInterface;

/**
 * Installer for bower packages.
 *
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class BowerInstaller extends AssetInstaller
{
    /**
     * {@inheritdoc}
     */
    protected function installCode(PackageInterface $package)
    {
        parent::installCode($package);

        $this->deleteIgnoredFiles($package);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateCode(PackageInterface $initial, PackageInterface $target)
    {
        parent::updateCode($initial, $target);

        $this->deleteIgnoredFiles($target);
    }

    /**
     * Deletes files defined in bower.json in section "ignore".
     *
     * @param PackageInterface $package
     */
    protected function deleteIgnoredFiles(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (empty($extra['bower-asset-ignore'])) {
            return;
        }

        $ignorer = new BowerIgnoreManager();
        foreach ($extra['bower-asset-ignore'] as $pattern) {
            $ignorer->addPattern($pattern);
        }

        $ignorer->deleteInDir($this->getInstallPath($package), $this->filesystem);
    }
}