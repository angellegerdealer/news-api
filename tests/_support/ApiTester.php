<?php

use Codeception\Util\HttpCode;
use Niden\Http\Response;
use Niden\Models\Users;
use Page\Data as DataPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Checks if the response was successful
     */
    public function seeResponseIsSuccessful($code = HttpCode::OK)
    {
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType(
            [

//                'data'    => 'array',
//                'errors'  => 'array',

            ]
        );

    }

    /**
     * Checks if the response was successful
     */
    public function seeResponseIs400()
    {
        $this->checkErrorResponse(HttpCode::BAD_REQUEST);
    }

    /**
     * Checks if the response was successful
     */
    public function seeResponseIs404()
    {
        $this->checkErrorResponse(HttpCode::NOT_FOUND);
    }

    public function seeErrorJsonResponse(string $message)
    {
        $this->seeResponseContainsJson(
            [

                'errors' => [
                    $message,
                ],
            ]
        );
    }

    public function seeSuccessJsonResponse(string $key = 'data', array $data = [])
    {
        $this->seeResponseContainsJson([$key => $data]);
    }

    public function apiLogin()
    {
        $this->deleteHeader('Authorization');
        $this->sendPOST(DataPage::$loginUrl, DataPage::loginJson());
        $this->seeResponseIsSuccessful();

        $response = $this->grabResponse();
        $response  = json_decode($response, true);
        $data      = $response['data'];
        $token     = $data['token'];

        return $token;
    }

    public function addApiUserRecord()
    {
        return $this->haveRecordWithFields(
            Users::class,
            [
                'status'        => 1,
                'username'      => 'testuser'.rand(1, 9000),
                'password'      => 'testpassword',
                'issuer'        => 'https://niden.net',
                'tokenPassword' => '12345',
                'tokenId'       => '110011',
            ]
        );
    }



    private function checkErrorResponse(int $code)
    {
        $this->seeResponseMatchesJsonType(
            [


            ]
        );

        $response  = $this->grabResponse();
        $response  = json_decode($response, true);
        $timestamp = $response['meta']['timestamp'];
        $hash      = $response['meta']['hash'];
        $this->assertEquals(HttpCode::getDescription($code), $response['errors'][0]);
        unset($response['jsonapi'], $response['meta']);
      //  $this->assertEquals($hash, sha1($timestamp . json_encode($response)));


        $this->seeResponseIsJson();
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType(
            [


            ]
        );


    }
}
