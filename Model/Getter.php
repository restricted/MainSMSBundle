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
    protected function getMainSmsModel(ContainerInterface $container)
    {
        if (!$container->has('karser.main_sms.model')) {
            throw new \LogicException('The MainSms bundle is not registered in your application.');
        }
        return $container->get('karser.main_sms.model');
    }

    /**
     * @deprecated
     * @param ContainerInterface $container
     * @return MainSMS
     * @throws \LogicException
     */
    protected function getMainSmsService(ContainerInterface $container)
    {
        return $this->getMainSmsModel($container);
    }
}
