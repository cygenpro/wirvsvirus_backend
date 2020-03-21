<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Client;

class ClientsTest extends TestCase
{

    public function testShowClient() {
        $client = factory('App\Client')->create();
        $response = $this->call('GET', '/users/'.$client->username);
        $this->assertObjectHasAttribute('points',$response->getData());
        $this->assertObjectHasAttribute('seconds',$response->getData());
        $this->assertObjectHasAttribute('display_name',$response->getData());
        $this->assertObjectHasAttribute('username',$response->getData());
        $this->assertEquals($response->getStatusCode(), 200);
        $client->delete();
    }

    public function testShowClientDoesNotExist() {
        $client = factory('App\Client')->make();
        $response = $this->call('GET', '/users/'.$client->username);
        $this->assertEquals($response->getStatusCode(), 404);
        $client->delete();
    }

    public function testStoreClient() {
        $response = $this->call('POST', '/users', ['username' => 'johndoe', 'display_name' => 'John Doe']);
        $this->assertObjectHasAttribute('points',$response->getData());
        $this->assertObjectHasAttribute('seconds',$response->getData());
        $this->assertObjectHasAttribute('display_name',$response->getData());
        $this->assertObjectHasAttribute('username',$response->getData());
        $this->assertObjectHasAttribute('created_at',$response->getData());
        $this->assertObjectHasAttribute('updated_at',$response->getData());
        $this->assertEquals($response->getStatusCode(), 201);
        Client::where('username','johndoe')->delete();
    }

    public function testStoreClientExists() {
        $this->call('POST', '/users', ['username' => 'johndoe', 'display_name' => 'John Doe']);
        $response = $this->call('POST', '/users', ['username' => 'johndoe', 'display_name' => 'John Doe']);
        $this->assertEquals($response->getStatusCode(), 422);
        Client::where('username','johndoe')->delete();
    }

}
