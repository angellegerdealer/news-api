<?php

declare(strict_types=1);

namespace Niden\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;


class UsersValidator extends Validation
{
    public function initialize()
    {
        $this->add(
            'username',
            new PresenceOf(
                [
                    'message' => 'The username is required',
                ]
            )
        );

        $this->add(
            'password',
            new PresenceOf(
                [
                    'message' => 'The password is required',
                ]
            )
        );

    }
}
