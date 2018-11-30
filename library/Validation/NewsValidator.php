<?php

declare(strict_types=1);

namespace Niden\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;


class NewsValidator extends Validation
{
    public function initialize()
    {
        $this->add(
            'title',
            new PresenceOf(
                [
                    'message' => 'The title is required',
                ]
            )
        );

        $this->add(
            'content',
            new PresenceOf(
                [
                    'message' => 'The content is required',
                ]
            )
        );

    }
}
