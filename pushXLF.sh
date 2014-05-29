#!/bin/bash

app/console translation:update --output-format=xlf --force en EVTEAEBundle

app/console translation:update --output-format=xlf --force en EVTIntranetBundle

cp src/EVT/EAEBundle/Resources/translations/messages.en.xlf translations/bdk-i18n.evtae/en.xlf

cp src/EVT/IntranetBundle/Resources/translations/messages.en.xlf translations/bdk-i18n.evtintranet/en.xlf

tx push -s
