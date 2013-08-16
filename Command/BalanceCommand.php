<?php
namespace Karser\MainSMSBundle\Command;

use Karser\MainSMSBundle\Model\MainSMS;
use Karser\SMSBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends BaseCommand
{
    use \Karser\MainSMSBundle\Model\Getter;

    /** @var MainSMS */
    protected $MainSMS;

    protected function configure()
    {
        $this->setName('mainsms:balance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->MainSMS = $this->getMainSmsModel($this->getContainer());

        $this->output = $output;

        $balance = $this->MainSMS->userBalance();
        $this->writeBalance($balance);
    }
}
