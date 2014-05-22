#!/bin/bash

cp src/EVT/EAEBundle/Resources/translations/messages.en.xlf translations/bdk-i18n.evtae/en.xlf

cp src/EVT/IntranetBundle/Resources/translations/messages.en.xlf translations/bdk-i18n.evtintranet/en.xlf

tx push -s
