<?php

namespace EVT\EAE\Communication;

/**
 * Class Mailer
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
interface Mailer
{
    public function send($data, $template);
}