<?php
namespace Karser\MainSMSBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Karser\MainSMSBundle\Entity\SmsTask;

class SendSmsCommand extends BaseCommand
{
    use \Karser\MainSMSBundle\Model\Getter;

    protected function configure()
    {
        $this->setName('mainsms:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->MainSMS = $this->getMainSmsModel($this->getContainer());

        $this->output = $output;

        $this->checkBalance();
        $this->sendMessages();

        $this->output->writeln('Done.');
    }

    private function checkBalance()
    {
        $balance = $this->MainSMS->userBalance();
        $this->writeBalance($balance);
        if ($balance <= 0) {
            throw new \LogicException('Balance error');
        }
    }

    private function sendMessages()
    {
        $em = $this->getDoctrineMananger();
        /** @var \Doctrine\ORM\EntityRepository $SmsTaskRepository */
        $SmsTaskRepository = $em->getRepository('KarserMainSMSBundle:SmsTask');
        $tasks = $SmsTaskRepository->findBy(['isSent' => false]);
        $this->writelnFormatted(sprintf('Messages to send %d', count($tasks)));
        /** @var SmsTask $SmsTask */
        foreach ($tasks as $SmsTask)
        {
            $is_sent = $this->MainSMS->messageSend($SmsTask->getPhoneNumber(), $SmsTask->getMessage(), $SmsTask->getSender());
            if ($is_sent) {
                $SmsTask->setIsSent(true);
                $em->persist($SmsTask);
                $this->output->write('.');
            } else {
                $this->output->write('F');
            }
        }
        $em->flush();
        $this->output->writeln('');
    }
}
