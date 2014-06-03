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
 * Class PublishShowroomCommand
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class PublishShowroomCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('evt:showroom:publish')
            ->setDescription(
                'Get the given showroom, send an email to the notification email and push the result to BDKBack'
            )
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username of an employee'),
                    new InputArgument('name', InputArgument::REQUIRED, 'The name of the showroom'),
                    new InputArgument('url', InputArgument::REQUIRED, 'The full url of the showroom'),
                    new InputArgument('urlback', InputArgument::REQUIRED, 'Url called after the mail is sent'),
                ]
            )
            ->setHelp(
                <<<EOT
    The <info>evt:showroom:publish</info> Get the given showroom,
    send an email to the notification email and push the result to BDKBack:

  <info>php app/console evt:showroom:publish employee 'Hotel California' www.vertical.com/hotel-california</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $name = $input->getArgument('name');
        $url = $input->getArgument('url');
        $urlBack = $input->getArgument('urlback');

        $domain = explode('/', str_replace(['http://', 'https://', 'www.'], [''], $url))[0];

        // Find the showroom
        $showroom = $this->getContainer()->get('evt.core.client')
            ->get('/api/showrooms?canView='. $username. '&name='. urlencode($name). '&vertical='. urlencode($domain));

        if ($showroom->getStatusCode() == 200 && $showroom->getBody()['pagination']['total_items'] == 1) {
            $data['showroom'] = $showroom->getBody()['items'][0];

            $this->getContainer()->get('translator')->setLocale($data['showroom']['provider']['lang']);

            $data['vertical'] = $data['showroom']['vertical'];
            $domain = $data['vertical']['domain'];

            $data['mailing']['subject'] = $this->getContainer()->get('translator')
                ->trans('title.published.showroom.manager', [], 'messages', $data['showroom']['provider']['lang']);
            $data['mailing']['to'] = $data['showroom']['provider']['notification_emails'];
            $data['mailing']['cc'] = 'support@'. $domain;

            $data['mailSent'] = 'true';
            try {
                $this->getContainer()->get('evt.mailer')
                    ->send($data, 'EVTEAEBundle:Email:Showroom.Published.Manager.html.twig');
            } catch (\Exception $e) {
                $data['mailSent'] = 'false';
                $output->writeln(sprintf(
                    'ERR No mail: <comment>%s</comment> in <comment>%s</comment> with error: %s',
                    $name,
                    $domain,
                    $e->getMessage()
                ));
            }

            $request = $this->getContainer()->get('evt.guzzle.client')->post(
                $urlBack,
                ['content-type' => 'application/x-www-form-urlencoded'],
                $data
            );

            try {
                $request->send();
                $output->writeln(sprintf('OK: <comment>%s</comment> in <comment>%s</comment>', $name, $domain));
            } catch (\Exception $e) {
                $output->writeln(sprintf(
                    'ERR No back: <comment>%s</comment> in <comment>%s</comment> with error: %s',
                    $name,
                    $domain,
                    $e->getMessage()
                ));
            }

        } else {
            $output->writeln(sprintf('ERR Not Found: <comment>%s</comment> in <comment>%s</comment>', $name, $domain));
        }
    }
}
