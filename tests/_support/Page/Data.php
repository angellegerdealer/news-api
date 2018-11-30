<?php

namespace Page;

use Niden\Constants\Relationships;
use function Niden\Core\envValue;
use Niden\Mvc\Model\AbstractModel;

class Data
{
    public static $newsSortUrl                     = '/news?sort=%s';
    public static $companiesUrl                     = '/news';
    public static $companiesRecordUrl               = '/news/%s';
    public static $loginUrl                         = '/login';
    public static $usersUrl                         = '/users';
    public static $wrongUrl                         = '/sommething';

    /**
     * @return array
     */
    public static function loginJson()
    {
        return [
            'username' => 'testuser',
            'password' => 'testpassword',
        ];
    }

    /**
     * @param        $name
     * @param string $address
     * @param string $city
     * @param string $phone
     *
     * @return array
     */
    public static function companyAddJson($name, $address = '', $city = '', $phone = '')
    {
        return [
            'name'    => $name,
            'address' => $address,
            'city'    => $city,
            'phone'   => $phone,
        ];
    }

    /**
     * @param AbstractModel $record
     *
     * @return array
     * @throws \Niden\Exception\ModelException
     */
    public static function companiesResponse(AbstractModel $record)
    {
        return [
            'id'         => $record->get('id'),
            'type'       => Relationships::COMPANIES,
            'attributes' => [
                'name'    => $record->get('name'),
                'address' => $record->get('address'),
                'city'    => $record->get('city'),
                'phone'   => $record->get('phone'),
            ],
            'links'      => [
                'self' => sprintf(
                    '%s/%s/%s',
                    envValue('APP_URL'),
                    Relationships::COMPANIES,
                    $record->get('id')
                ),
            ],
        ];
    }

    /**
     * @param AbstractModel $record
     *
     * @return array
     * @throws \Niden\Exception\ModelException
     */
    public static function individualResponse(AbstractModel $record)
    {
        return [
            'id'         => $record->get('id'),
            'type'       => Relationships::INDIVIDUALS,
            'attributes' => [
                'companyId' => $record->get('companyId'),
                'typeId'    => $record->get('typeId'),
                'prefix'    => $record->get('prefix'),
                'first'     => $record->get('first'),
                'middle'    => $record->get('middle'),
                'last'      => $record->get('last'),
                'suffix'    => $record->get('suffix'),
            ],
            'links'      => [
                'self' => sprintf(
                    '%s/%s/%s',
                    envValue('APP_URL'),
                    Relationships::INDIVIDUALS,
                    $record->get('id')
                ),
            ],
        ];
    }

    /**
     * @param AbstractModel $record
     *
     * @return array
     * @throws \Niden\Exception\ModelException
     */
    public static function userResponse(AbstractModel $record)
    {
        return [
            'data' => [
                'id'         => $record->get('id'),
                'status'        => $record->get('status'),
                'username'      => $record->get('username'),
                'password'      => $record->get('password'),
                'issuer'        => $record->get('issuer'),
                'tokenPassword' => $record->get('tokenPassword'),
                'tokenId'       => $record->get('tokenId')
            ],

        ];
    }
}
