<?php

declare(strict_types=1);

namespace CommissionTask\Command;

use CommissionTask\Service\IOHelpers\CommissionsOutput;
use CommissionTask\Service\IOHelpers\CSVFileReaderByLine;
use CommissionTask\Service\TransactionsProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CalculateCommissionsCommand extends Command
{
    protected static $defaultName = 'app:calculate-commissions';
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'CSV with transactions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        if (!$this->filesystem->exists($file)) {
            $output->writeln('Error! File not found: '.$file);

            return Command::FAILURE;
        }

        $csvFile = new CSVFileReaderByLine($file);
        $commissionsOutput = new CommissionsOutput();
        $transactionsProcessor = new TransactionsProcessor($csvFile, $commissionsOutput);

        $transactionsProcessor->process();

        return Command::SUCCESS;
    }
}
