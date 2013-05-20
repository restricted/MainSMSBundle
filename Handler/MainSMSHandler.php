<?php
namespace Karser\MainSMSBundle\Handler;


use Karser\MainSMSBundle\Model\MainSMS;
use Karser\SMSBundle\Entity\SMSTaskInterface;
use Karser\SMSBundle\Handler\AbstractHandler;
use Karser\SMSBundle\Handler\HandlerInterface;

class MainSMSHandler extends AbstractHandler implements HandlerInterface
{
    private $MainSMS;

    public function __construct(MainSMS $MainSMS)
    {
        $this->MainSMS = $MainSMS;
    }

    public function supports($number)
    {
        return true;
    }

    public function getBalance()
    {
        return $this->MainSMS->userBalance();
    }

    public function send(SMSTaskInterface $SMSTask)
    {
        return $this->MainSMS->messageSend($SMSTask->getPhoneNumber(), $SMSTask->getMessage(), $SMSTask->getSender());
    }
}