<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_currency_list()
    {
        $response = $this->get('/api/currency');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'currencies' => [
                    'TWD',
                    'USD',
                    'JPY',
                ],
            ]
        ]);
    }

    public function test_exchange_basic_case()
    {
        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'TWD',
            'currency' => 'USD',
            'amount' => 1234567
        ]);

        // 40,506.14

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => "40,506.14"
            ]
        ]);
    }

    public function test_exchange_with_float_amount()
    {
        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'TWD',
            'currency' => 'USD',
            'amount' => 1234567.00
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => "40,506.14"
            ]
        ]);


        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'USD',
            'currency' => 'JPY',
            'amount' => 1234567.00
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => "138,025,825.17"
            ]
        ]);

        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'USD',
            'currency' => 'JPY',
            'amount' => 123
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => "13,751.52"
            ]
        ]);
    }

    public function test_exchange_invalid_currency()
    {
        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'TWD',
            'currency' => 'CNY',
            'amount' => 1234567
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'errors'
        ]);
    }

    public function test_exchange_invalid_amount()
    {
        $response = $this->postJson('/api/currency/exchange', [
            'payment' => 'TWD',
            'currency' => 'USD',
            'amount' => -100,
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'errors'
        ]);
    }
}
