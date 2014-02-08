<?php
namespace Karser\MainSMSBundle\Handler;

use Karser\MainSMSBundle\Model\MainSMS;
use Karser\SMSBundle\Entity\SMSTaskInterface;
use Karser\SMSBundle\Handler\AbstractHandler;

class MainSMSHandler extends AbstractHandler
{
    protected $name = 'karser.handler.main_sms';

    private $MainSMS;

    public function __construct(MainSMS $MainSMS)
    {
        $this->MainSMS = $MainSMS;
    }

    public function getBalance()
    {
        return $this->MainSMS->userBalance();
    }

    public function send(SMSTaskInterface $SMSTask)
    {
        if (!$this->MainSMS->messageSend($SMSTask->getPhoneNumber(), $SMSTask->getMessage(), $SMSTask->getSender())) {
            throw new \Exception('error');
        }
        $response = $this->MainSMS->getResponse();
        return current($response['messages_id']);
    }

    public function checkStatus($message_id, $phone = null)
    {
        $status = $this->MainSMS->messageStatus($message_id);
        switch ($status[$message_id]) {
            case 'enqueued':
                return SMSTaskInterface::STATUS_PROCESSING;
            case 'accepted':
            case 'delivered':
                return SMSTaskInterface::STATUS_SENT;
            default:
                return SMSTaskInterface::STATUS_FAIL;
        }
    }
}