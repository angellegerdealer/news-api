<?php

declare(strict_types=1);

namespace Niden\Api\Controllers;

use Niden\Exception\ModelException;
use Niden\Http\Request;
use Niden\Http\Response;
use Niden\Models\Users;
use Niden\Traits\QueryTrait;
use Niden\Traits\TokenTrait;
use Phalcon\Cache\Backend\Libmemcached;
use Phalcon\Config;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Phalcon\Crypt;

/**
 * Class LoginController
 *
 * @package Niden\Api\Controllers
 *
 * @property Libmemcached $cache
 * @property Config $config
 * @property Request $request
 * @property Response $response
 */
class LoginController extends Controller
{
    use TokenTrait;
    use QueryTrait;


    /**
     * Default action logging in
     *
     * @return array
     * @throws ModelException
     */
    public function callAction()
    {

        // Create an instance
        $crypt = new Crypt();

        $crypt->setKey(getenv('APP_ENCRYPT_KEY'));

        $username = $this->request->getPost('username', Filter::FILTER_STRING);
        $password = $this->request->getPost('password', Filter::FILTER_STRING);


        /** @var Users|false $user */
        $user = $this->getUserByUsernameAndPassword($this->config, $this->cache, $username);

        $dbpassword = false;
        if ($user->password) {
            $dbpassword =  $crypt->decryptBase64($user->password);
        }

        if (false !== $user && $dbpassword == $password) {
            $this
                ->response
                ->setPayloadSuccess(['token' => $user->getToken()]);
        } else {
            $this
                ->response
                ->setPayloadError('Incorrect credentials');
        }
    }
}

