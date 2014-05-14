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
 * Class BlendAllAuthsCommand
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class BlendAllAuthsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('evt:auth:blend')
            ->setDescription('Generate the auths in Redis for all managers and employees.')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username of an employee'),
                ]
            )
            ->setHelp(
                <<<EOT
    The <info>evt:auth:blend</info> Generate the auths in Redis for all managers and employees:

  <info>php app/console evt:auth:blend adminUsername</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adminUsername = $input->getArgument('username');
        $this->blendUsersByType($adminUsername, $output, 'employees');
        $this->blendUsersByType($adminUsername, $output, 'managers');
    }

    protected function blendUsersByType($adminUsername, $output, $type)
    {
        $output->writeln(sprintf('Blending: <comment>%s</comment>', $type));

        $userData = $this->getUsersByType($type, $adminUsername);
        if ('200' != $userData->getStatusCode()) {
            return;
        }

        $arrayUser = $userData->getBody();

        $command = $this->getApplication()->find('evt:auth:blend:user');

        $progress = $this->getHelperSet()->get('progress');

        $progress->start($output, $arrayUser['pagination']['total_items']);

        for ($page = 1; $page <= $arrayUser['pagination']['total_pages']; $page++) {
            $userData = $this->getUsersByType($type, $adminUsername, $page);
            if ('200' != $userData->getStatusCode()) {
                return;
            }

            $arrayUser = $userData->getBody();

            foreach ($arrayUser['items'] as $user) {
                $arguments = array(
                    'command' => 'evt:auth:blend:user',
                    'username'    => $user['username'],
                );

                $input = new ArrayInput($arguments);
                $command->run($input, new NullOutput());
                $progress->advance();
            }

        }
        $progress->finish();
    }

    protected function getUsersByType($type, $adminUsername, $page = 1)
    {
        return $this->getContainer()->get('evt.core.client')
            ->get('/api/'. $type. '?canView='. $adminUsername. '&page='. $page);
    }
}
