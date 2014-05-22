<?php

namespace EVT\IntranetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportPublishedShowroomsCommand
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ImportPublishedShowroomsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('evt:showroom:import')
            ->setDescription('Import published showroom CSV')
            ->setDefinition(
                [
                    new InputArgument('file', InputArgument::REQUIRED, 'CSV file with fields name, url'),
                    new InputArgument('username', InputArgument::REQUIRED, 'The username of an employee'),
                    new InputArgument('urlback', InputArgument::REQUIRED, 'Url called after the mail is sent'),
                ]
            )
            ->setHelp(
                <<<EOT
    The <info>evt:showroom:import</info> Import published showroom CSV:

  <info>php app/console evt:showroom:import importedCSV.csv employee www.vertical.com/hotel-california</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adminUsername = $input->getArgument('username');
        $urlBack = $input->getArgument('urlback');

        $command = $this->getApplication()->find('evt:showroom:publish');

        $fh = fopen($input->getArgument('file'), 'r');
        while ($line = fgetcsv($fh)) {
            $arguments = array(
                'command' => 'evt:showroom:publish',
                'username'    => $adminUsername,
                'name' => $line[0],
                'url' => $line[1],
                'urlback' => $urlBack
            );

            $input = new ArrayInput($arguments);
            $command->run($input, $output);
        }
    }
}
