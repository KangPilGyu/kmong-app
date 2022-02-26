<?php

namespace Tests\Unit\Controller;

use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    public function testLogin()
    {
        /** PrePare Seed */
        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '1235251aaA!aa'
        ];
        $response = $this->postJson('/api/v1/users', $params);
        $response->assertStatus(200);

        /** Validation Failed */
         $params = [
             'email' => 'eiffeltop01.com',
             'password' => '11'
         ];
         $response = $this->postJson('/api/v1/auth/token', $params);
         $response->assertStatus(422);

        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '11'
        ];
        $response = $this->postJson('/api/v1/auth/token', $params);
        $response->assertStatus(401);

        $params = [
            'email' => 'eiffeltop01@gmail.com',
            'password' => '1235251aaA!aa'
        ];
        $response = $this->postJson('/api/v1/auth/token', $params);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user',
            'access_token',
            'token_type'
        ]);

    }
}
