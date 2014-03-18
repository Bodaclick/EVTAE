<?php

namespace EVT\IntranetBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * LocaleListener
 *
 * @author    Alvaro Prudencio
 */
class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    /**
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        //Get first language
        $acceptLanguage = $request->headers->get('accept-language');
        $groupsAcceptLanguages = explode (';', $acceptLanguage);
        $firstLanguage = explode (',', $groupsAcceptLanguages[0]);
        $lang = explode ('-', $firstLanguage[0])[0];

        //Setting user locale
        $request->getSession()->set('_locale', $lang);
        $request->setLocale($lang);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}