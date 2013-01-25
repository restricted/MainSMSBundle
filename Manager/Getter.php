<?php
namespace Karser\MainSMSBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait Getter
{
    /**
     * @param ContainerInterface $container
     * @return MainSMSManager
     * @throws \LogicException
     */
    protected function getMainSmsManager(ContainerInterface $container)
    {
        if (!$container->has('karser.main_sms.manager')) {
            throw new \LogicException('The MainSms bundle is not registered in your application.');
        }
        return $container->get('karser.main_sms.manager');
    }
}
