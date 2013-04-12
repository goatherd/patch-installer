<?php

namespace Goatherd\Patch;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * Install directly within vendor directory.
 *
 * !!EXPERIMENTAL FEATURE!!
 *
 * See README.md for usage and limitations.
 */
class Installer extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
        $this->applyPatch($package);
    }
    
    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);
        $this->applyPatch($target);
    }
    
    /**
     * @todo allow to remove patch
     * @todo document properly
     */
    public function applyPatch(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (!isset($extra['patch-path']) || !isset($extra['patch-files'])) {
            throw new \RuntimeException('Need extra `patch-path` and `patch-files` to be defined.');
        }
        
        $sourcePath = $this->getInstallPath($package);
        $targetPath = $this->vendorDir . DIRECTORY_SEPARATOR . $extra['patch-path'];
        if (!is_dir($targetPath)) {
            throw new \RuntimeException('Patched packages must be installed first. Try to adjust your composer.json.');
        }
        
        // TODO test source files
        foreach ($extra['patch-files'] as $fn) {
            $source = $sourcePath . DIRECTORY_SEPARATOR . $fn;
            $target = $targetPath . DIRECTORY_SEPARATOR . $fn;
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0644, true);
            }
            // will replace as needed
            copy($source, $target);
        }
    }
    
    /** {@inheritDoc} */
    public function supports($packageType)
    {
        return 'patch' === $packageType;
    }    
}
