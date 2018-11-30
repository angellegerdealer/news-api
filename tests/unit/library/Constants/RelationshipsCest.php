<?php

namespace Niden\Tests\unit\library\Constants;

use CliTester;
use Niden\Constants\Relationships;

class RelationshipsCest
{
    public function checkConstants(CliTester $I)
    {
        $I->assertEquals('news', Relationships::NEWS);
        $I->assertEquals('users', Relationships::USERS);
    }
}
