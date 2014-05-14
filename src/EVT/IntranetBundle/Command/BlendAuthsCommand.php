<?php

namespace EVT\IntranetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlendAuthsCommand
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class BlendAuthsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('evt:auth:blend:user')
            ->setDescription('Generate the auths in Redis given username.')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                ]
            )
            ->setHelp(
                <<<EOT
    The <info>evt:auth:blend</info> Generate the auths in Redis given username:

  <info>php app/console evt:auth:blend:user username</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $userData = $this->getContainer()->get('evt.core.client')->get('/api/users/' . $username);

        $arrayUser = $userData->getBody();
        if ('200' == $userData->getStatusCode()) {
            $authBlender = $this->getContainer()->get('evt_auth_manager');
            $authBlender->blendForUser($username, $arrayUser['roles'][0]);

            $output->writeln(sprintf('User: <comment>%s</comment> blended', $username));
        } else {
            $output->writeln(sprintf('Err:  <comment>%s</comment> not found', $username));
        }
    }
}
