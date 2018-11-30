<?php

declare(strict_types=1);

namespace Niden\Api\Controllers\Users;

use Phalcon\Crypt;
use Niden\Exception\ModelException;
use Niden\Http\Response;
use Niden\Models\Users;
use Niden\Traits\FractalTrait;
use Niden\Transformers\BaseTransformer;
use Niden\Validation\UsersValidator;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use function Niden\Core\envValue;

/**
 * Class AddController
 *
 * @package Niden\Api\Controllers\Users
 *
 * @property Response $response
 */
class AddController extends Controller
{
    use FractalTrait;

    /**
     * Adds a record in the database
     *
     * @throws ModelException
     */
    public function callAction()
    {
        $validator = new UsersValidator();
        $messages  = $validator->validate($this->request->getPost());

        // Calling security instances
        $crypt = new Crypt();
        $random = new \Phalcon\Security\Random();

        $crypt->setKey(getenv('APP_ENCRYPT_KEY'));

        /**
         * If no messages are returned, go ahead with the query
         */
        if (0 === count($messages)) {
            $username    = $this->request->getPost('username', Filter::FILTER_STRING);
            $password = $this->request->getPost('password', Filter::FILTER_STRING, '');

            $user = new Users();
            $result  = $user
                ->set('username', $username)
                ->set('password', $crypt->encryptBase64($password))
                ->set('status', 'N')
                ->set('issuer', envValue('TOKEN_AUDIENCE'))
                ->set('tokenId', $random->hex(11))
                ->set('tokenPassword', $random->base64Safe())
                ->save();

            if (false !== $result) {
                $data = $this->format('item', $user, BaseTransformer::class, 'users');
                $this
                    ->response
                    ->setJsonContent($data)
                    ->setStatusCode($this->response::CREATED)
                ;
            } else {
                /**
                 * Errors happened store them
                 */
                $this
                    ->response
                    ->setPayloadErrors($user->getMessages());
            }
        } else {
            /**
             * Set the errors in the payload
             */
            $this
                ->response
                ->setPayloadErrors($messages);
        }
    }
}
