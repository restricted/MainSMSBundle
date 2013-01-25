<?php
namespace Karser\MainSMSBundle\Manager;

use Karser\MainSMSBundle\Model\MainSMS;
use Karser\MainSMSBundle\Entity\SmsTask;
use Doctrine\Common\Persistence\ObjectManager;

class MainSMSManager
{
    /** @var MainSMS */
    private $MainSMS;

    public function __construct(MainSMS $MainSMS, ObjectManager $em)
    {
        $this->MainSMS = $MainSMS;
        $this->em = $em;
    }

    public function schedule($phone_number, $message, $sender)
    {
        $SmsTask = new SmsTask();
        $SmsTask->setPhoneNumber($phone_number);
        $SmsTask->setMessage($message);
        $SmsTask->setSender($sender);
        $SmsTask->setIsSent(false);
        $this->em->persist($SmsTask);
        $this->em->flush();
        return true;
    }
}
