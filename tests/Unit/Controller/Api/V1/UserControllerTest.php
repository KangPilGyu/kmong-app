<?php

namespace Tests\Unit\Controller;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testCreate()
    {
        /** Validation Failed */
         $params = [
             'email' => 'eiffeltop01.com',
             'password' => '11'
         ];
         $response = $this->postJson('/api/v1/users', $params);
         $response->assertStatus(422);

        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '11'
        ];
        $response = $this->postJson('/api/v1/users', $params);
        $response->assertStatus(422);

        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '1235251aaA'
        ];
        $response = $this->postJson('/api/v1/users', $params);
        $response->assertStatus(422);

        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '1235251aaA!aa'
        ];
        $response = $this->postJson('/api/v1/users', $params);
        $response->assertStatus(200);
    }
}
