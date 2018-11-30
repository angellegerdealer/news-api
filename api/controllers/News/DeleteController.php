<?php

declare(strict_types=1);

namespace Niden\Api\Controllers\News;

use Niden\Exception\ModelException;
use Niden\Http\Response;
use Niden\Models\News;
use Niden\Traits\FractalTrait;
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
class DeleteController extends Controller
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

        $request = new Request();

        $delete = News::findFirst($parameters['id'])->delete();
        $results = News::findFirst($parameters['id']);


        if (count($parameters) > 0 && true === $delete) {
            $this
                ->response
                ->setPayloadSuccess($results);
        } else {
            $this->sendError($this->response::NOT_FOUND);
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


    /**
     * Sets the response with an error code
     *
     * @param int $code
     */
    private function sendError(int $code)
    {
        $this
            ->response
            ->setPayloadError($this->response->getHttpCodeDescription($code))
            ->setStatusCode($code)
        ;
    }
}
