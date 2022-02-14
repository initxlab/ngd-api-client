<?php
namespace NgdClient\Tests\Operations;

use Initxlab\Params\C;
use PHPUnit\Framework\TestCase;
use Initxlab\Request\Client;

class UserResourceTest extends TestCase
{

    private const DEFAULT_CLIENT_OPTIONS = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER=>[
            "Content-Type: application/ld+json"
        ]
    ];


    public function testCanUserHydraCollectionGet(): void
    {
        $client = new Client('http://localhost:8000','/api/users');

        $request = $client->createRequest(self::DEFAULT_CLIENT_OPTIONS);

        $client->sendRequest($request);

        $response = $client->getResponse();

        $responseToArray = null;

        try {
            $responseToArray = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {

        }

        $countMember = count($responseToArray[C::HYDRA_KEY_MEMBER]);

        $dice = 0;
        // Attempt to overwrite with a random pick from max count
        try {
            $dice = random_int( 0, $countMember - 1 );
        } catch (\Exception $e) {
        }

        $this->assertNotNull($response);
        $this->assertIsArray($responseToArray);
        $this->assertArrayHasKey(C::HYDRA_KEY_ID,$responseToArray);
        $this->assertArrayHasKey(C::HYDRA_KEY_MEMBER,$responseToArray);
        $this->assertIsArray($responseToArray[C::HYDRA_KEY_MEMBER]);
        $this->assertNotNull($responseToArray[C::HYDRA_KEY_TOTAL_ITEMS]);
        $this->assertGreaterThan(0,$responseToArray[C::HYDRA_KEY_TOTAL_ITEMS]);
        $this->assertArrayHasKey(C::HYDRA_KEY_ID,$responseToArray[C::HYDRA_KEY_MEMBER][$dice]);
        $this->assertArrayHasKey(C::USERNAME,$responseToArray[C::HYDRA_KEY_MEMBER][$dice]);
        $this->assertArrayHasKey(C::EMAIL,$responseToArray[C::HYDRA_KEY_MEMBER][$dice]);
        $this->assertIsString($responseToArray[C::HYDRA_KEY_MEMBER][$dice][C::EMAIL]);
        $this->assertStringContainsString('@',$responseToArray[C::HYDRA_KEY_MEMBER][$dice][C::EMAIL]);
    }
}