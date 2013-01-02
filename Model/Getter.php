<?php
namespace Karser\MainSMSBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait Getter
{
    /**
     * @param ContainerInterface $container
     * @return MainSMS
     * @throws \LogicException
     */
    protected function getMainSmsService(ContainerInterface $container)
    {
        if (!$container->has('karser_main_sms.main_sms')) {
            throw new \LogicException('The MainSms bundle is not registered in your application.');
        }
        return $container->get('karser_main_sms.main_sms');
    }
}
