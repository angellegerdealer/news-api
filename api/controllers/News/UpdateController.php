<?php

declare(strict_types=1);

namespace Niden\Api\Controllers\News;

use Niden\Exception\ModelException;
use Niden\Http\Response;
use Niden\Models\News;
use Niden\Traits\FractalTrait;
use Niden\Transformers\BaseTransformer;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Niden\Traits\TokenTrait;
use Niden\Traits\QueryTrait;


/**
 * Class AddController
 *
 * @package Niden\Api\Controllers\News
 *
 * @property Response $response
 */
class UpdateController extends Controller
{
    use FractalTrait;
    use TokenTrait;
    use QueryTrait;


    /**
     * Adds a record in the database
     *
     * @throws ModelException
     */
    public function callAction($id = 0)
    {

        $parameters = $this->checkIdParameter($id);

        $new = News::findFirstById($parameters['id']);

        $title = $this->request->getPut('title', Filter::FILTER_STRING, $new->title);
        $content = $this->request->getPut('content', Filter::FILTER_STRING, $new->content);

        if (false !== $new) {
            $result = $new
                ->set('title', $title)
                ->set('content', $content)
                ->save();

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
                ->setPayloadErrors($this->request->getPut());
        }
    }

    /**
     * Checks the passed id parameter and returns the relevant array back
     *
     * @param int $recordId
     *
     * @return array
     */
    private function checkIdParameter($recordId = 0): array
    {
        $parameters = [];

        /** @var int $localId */
        $localId = $this->filter->sanitize($recordId, Filter::FILTER_ABSINT);

        if ($localId > 0) {
            $parameters['id'] = $localId;
        }

        return $parameters;
    }
}
