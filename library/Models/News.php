<?php

declare(strict_types=1);

namespace Niden\Models;

use Niden\Traits\TokenTrait;
use Niden\Mvc\Model\AbstractModel;
use Phalcon\Filter;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Class Users
 *
 * @package Niden\Models
 */
class News extends AbstractModel
{
    use TokenTrait;

    public $created_date;

    public $updated_date;


    const DELETED     = 'D';
    const NOT_DELETED = 'N';


    public $status;

    public function initialize()
    {
        $this->addBehavior(
            new SoftDelete(
                [
                    'field' => 'status',
                    'value' => News::DELETED,
                ]
            )
        );
    }

    /**
     * Let's set the datetime of the news creation
     */

    public function beforeCreate()
    {
        $this->created_date = date("Y-m-d H:i:s");
    }

    /**
     * Let's set the datetime of the news upddate
     */

    public function beforeUpdate()
    {

        $this->updated_date = date('Y-m-d H:i:s');
    }


    /**
     * Model filters
     *
     * @return array<string,string>
     */
    public function getModelFilters(): array
    {
        return [
            'id' => Filter::FILTER_ABSINT,
            'title' => Filter::FILTER_STRING,
            'content' => Filter::FILTER_STRING,
            'user_id' => Filter::FILTER_INT,
            'created_date' => Filter::FILTER_STRING,
            'updated_date' => Filter::FILTER_STRING,
            'status' => Filter::FILTER_STRING,

        ];
    }

    /**
     * Returns the source table from the database
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'news';
    }


}
