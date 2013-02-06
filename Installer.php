<?php
namespace Karser\MainSMSBundle;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    public function getInstallPath(PackageInterface $package)
    {
        $prettyName = $package->getPrettyName();
        list($vendor, $name) = explode('/', $prettyName);

        if (strtolower($vendor) === 'karser') {
            $vendor = ucfirst($vendor);
            $name = ucfirst($name);

            return 'package/' . $vendor . '.' . $name . '/';
        }

        return parent::getInstallPath($package);
    }

    public function supports($packageType)
    {
        return true;
    }
}
