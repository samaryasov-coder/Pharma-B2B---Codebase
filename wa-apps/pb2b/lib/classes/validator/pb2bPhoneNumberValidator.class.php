<?php

class pb2bPhoneNumberValidator extends waRegexValidator
{
    const REGEX_NUMBER = '/^[0-9]{7,15}$/';

    protected function init()
    {
        $this->setMessage('not_match', _ws('Incorrect phone number value'));
        $this->setPattern(self::REGEX_NUMBER);
    }
}
