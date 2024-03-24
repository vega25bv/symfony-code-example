<?php

namespace App\Command;

use App\Service\MailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\BankRatesService;
use App\Service\LocalCurrencyStorageService;
use App\Service\CurrencyComparer;

#[AsCommand(
    name: 'app:currency-check',
    description: 'Add a short description for your command',
)]
class CurrencyCheckCommand extends Command
{
    protected static $defaultName = 'app:currency-check';
    private $bankRatesService;
    private $localCurrencyStorageService;
    private $currencyComparer;
    private $mailService;

    public function __construct(
        BankRatesService $bankRatesService,
        LocalCurrencyStorageService $localCurrencyStorageService,
        CurrencyComparer $currencyComparer,
        MailService $mailService
    )
    {
        $this->bankRatesService = $bankRatesService;
        $this->localCurrencyStorageService = $localCurrencyStorageService;
        $this->currencyComparer = $currencyComparer;
        $this->mailService = $mailService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Check and compare currency rates from PrivatBank to local data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bankRatesService->setBankRatesUrls('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
        $changes = $this->currencyComparer->compare($this->bankRatesService, $this->localCurrencyStorageService);

        foreach ($changes as $change) {
            $body = sprintf('Currency has changed: %s', $change);
            $this->mailService->sendEmail('a@a.com', 'Currency has changed', $body);
            $output->writeln($body);
        }

        return Command::SUCCESS;
    }
}
