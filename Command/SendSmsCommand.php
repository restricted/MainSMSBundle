<?php
namespace Karser\MainSMSBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Karser\MainSMSBundle\Entity\SmsTask;
use Karser\MainSMSBundle\Model\MainSMS;
use Doctrine\Common\Persistence\ObjectManager;

class SendSmsCommand extends ContainerAwareCommand
{
    use \MM\ApplicationBundle\Repository\Getter;
    use \Karser\MainSMSBundle\Model\Getter;

    /** @var MainSMS */
    private $MainSMS;
    /** @var ObjectManager */
    private $em;
    /** @var OutputInterface */
    private $output;

    protected function configure()
    {
        $this->setName('mainsms:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->MainSMS = $this->getMainSmsModel($this->getContainer());
        /** @var \Doctrine\Common\Persistence\ManagerRegistry $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');
        $this->em = $doctrine->getManager();
        $this->output = $output;

        $this->checkBalance();
        $this->sendMessages();

        $this->output->writeln('Done.');
    }

    private function writelnFormatted($message)
    {
        $this->output->writeln(sprintf('<comment>></comment> <info>%s</info>', $message));
    }

    private function checkBalance()
    {
        $balance = $this->MainSMS->userBalance();
        if ($balance > 0) {
            $this->writelnFormatted(sprintf('Balance is %.2f', $balance));
        } else {
            throw new \LogicException('Balance');
        }
    }

    private function sendMessages()
    {
        /** @var \Doctrine\ORM\EntityRepository $SmsTaskRepository */
        $SmsTaskRepository = $this->em->getRepository('KarserMainSMSBundle:SmsTask');
        $tasks = $SmsTaskRepository->findBy(['isSent' => false]);
        $this->writelnFormatted(sprintf('Messages to send %d', count($tasks)));
        /** @var SmsTask $SmsTask */
        foreach ($tasks as $SmsTask)
        {
            $is_sent = $this->MainSMS->messageSend($SmsTask->getPhoneNumber(), $SmsTask->getMessage(), $SmsTask->getSender());
            if ($is_sent) {
                $SmsTask->setIsSent(true);
                $this->em->persist($SmsTask);
                $this->output->write('.');
            } else {
                $this->output->write('F');
            }
        }
        $this->em->flush();
        $this->output->writeln('');
    }
}
