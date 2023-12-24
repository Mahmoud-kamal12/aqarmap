<?php

// tests/Controller/PostControllerTest.php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;

class BlogPostTest extends WebTestCase
{
    public function testCreatePost()
    {
        $client = static::createClient();

        $jwtToken = $this->getJwtToken($client);
        $this->createPost($client, $jwtToken);

    }

    private function getJwtToken($client)
    {
        $client->request('POST', '/login_check', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'yrobel@example.com',
                'password' => 'password',
            ],
        ]);
        $response = $client->getResponse();
        dump($response->getStatusCode());
        if ($response->getStatusCode() === 200) {
            $data = $response->toArray();
            return $data['token'];
        }
        throw new \RuntimeException('Authentication failed.');
    }
    private function createPost($client, $jwtToken)
    {
        $client->request('GET', '/api/posts/create', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'title' => 'Test Post',
                'description' => 'This is a test post content.',
                'schedule_date' => '',
            ],
        ]);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testShowAllPost()
    {
        $client = static::createClient();

        $jwtToken = $this->getJwtToken($client);
        $this->showAllPost($client, $jwtToken);

    }

    private function showAllPost($client, $jwtToken)
    {
        $client->request('GET', '/api/posts/index', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Content-Type' => 'application/json',
            ]
        ]);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

}
