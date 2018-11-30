<?php

declare(strict_types=1);

namespace Niden\Api\Controllers\News;

use Niden\Api\Controllers\BaseController;
use Niden\Constants\Relationships;
use Niden\Models\News;
use Niden\Transformers\BaseTransformer;

/**
 * Class GetController
 *
 * @package Niden\Api\Controllers\Users
 */
class GetController extends BaseController
{
    /** @var string */
    protected $model       = News::class;

    /** @var string */
    protected $resource    = Relationships::USERS;

    /** @var string */
    protected $transformer = BaseTransformer::class;

    /** @var boolean */
    protected $pagination = true;

    /** @var array<string,bool> */
    protected $sortFields  = [
        'id'            => false,
        'title'        => true,
        'content'      => true,
        'user_id'      => true,
        'created_date'        => true,
        'updated_date' => false,
        'status'       => false,
        'views'       => false,
    ];

    /** @var string */
    protected $orderBy     = 'created_date';
}
