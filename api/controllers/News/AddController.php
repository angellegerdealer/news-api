<?php

declare(strict_types=1);

namespace Niden\Api\Controllers\News;

use Niden\Exception\ModelException;
use Niden\Http\Response;
use Niden\Models\News;
use Niden\Traits\FractalTrait;
use Niden\Transformers\BaseTransformer;
use Niden\Validation\NewsValidator;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Niden\Traits\TokenTrait;
use Niden\Traits\QueryTrait;
use Niden\Http\Request;

/**
 * Class AddController
 *
 * @package Niden\Api\Controllers\News
 *
 * @property Response $response
 */
class AddController extends Controller
{
    use FractalTrait;
    use TokenTrait;
    use QueryTrait;


    /**
     * Adds a record in the database
     *
     * @throws ModelException
     */
    public function callAction()
    {
        $validator = new NewsValidator();
        $request = new Request();

        $messages = $validator->validate($this->request->getPost());

        /**
         * Check if there a message to save the data of the news
         */
        if (0 === count($messages)) {
            $token = $this->getToken($request->getBearerTokenFromHeader());
            $user = $this->getUserByToken($this->config, $this->cache, $token);

            $title = $this->request->getPost('title', Filter::FILTER_STRING);
            $content = $this->request->getPost('content', Filter::FILTER_STRING, '');

            $new = new News();
            $result = $new
                ->set('title', $title)
                ->set('content', $content)
                ->set('user_id', $user->id)
                ->set('status', 'N')
                ->save();



            if (false !== $result) {
                $data = $this->format('item', $new, BaseTransformer::class, 'news');
                $this
                    ->response
                    ->setJsonContent($data)
                    ->setStatusCode($this->response::CREATED);
            } else {
                /**
                 * Errors happened store them
                 */
                $this
                    ->response
                    ->setPayloadErrors($new->getMessages());
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
