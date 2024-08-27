<?php

namespace Tests\Feature;

use \App\Models\User;
use App\Models\AccountClassification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AccountClassificationApiTest extends TestCase
{
    private $token = '1816|ev1HbGg4tnA77qO73VVtJkYssoy2ox43C8E6HqeJ40f9e39e';
    private $created = null;
    private $user = null;
    private $data = null;
    private $account_classification = null;
    private $url = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Prepare a user and related data
        $this->user = User::firstWhere(['email' => 'davedeloper@gmail.com']);
        $this->account_classification = AccountClassification::firstWhere('company_id', $this->user->company_id);

        $this->url = '/api/admin/account-classification';

        $this->created = AccountClassification::create([
            'class' => fake()->words(2, true),
            'company_id' => $this->user->company_id,
        ]);

        $this->data = [
            'class' => fake()->words(2, true),
            'company_id' => $this->user->company_id,
        ];
    }

    protected function tearDown(): void
    {
        // Clean up the created resource
        if ($this->created) {
            $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->deleteJson("{$this->url}/{$this->created['id']}");
            
            $this->assertDatabaseMissing('account_classifications', ['id' => $this->created['id']]);
        }

        parent::tearDown();
    }

    /**
     * Test fetching account classifications without authentication.
     */
    public function test_list_account_classifications_without_authentication(): void
    {
        $response = $this->getJson($this->url);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }


    /**
     * Test fetching account classifications authentication.
     */
    public function test_list_account_classifications_with_authentication(): void
    {
        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)->getJson("{$this->url}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Success', 'data' => []]);
    }


    /**
     * Test show a single resource without authentication.
     */
    public function test_show_account_classification_without_authentication(): void
    {
        $response = $this->getJson("{$this->url}/{$this->account_classification['id']}");

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Test show a single resource with authentication with validation error.
     */
    public function test_show_account_classification_with_authentication_with_validation_error(): void
    {

        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->getJson("{$this->url}/{fake()->numberBetween(1111111, 2147483647)}");

        $response->assertStatus(404)
        ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('message', 'string')
        );
    }

    /**
     * Test show a single resource with authentication without validation error.
     */
    public function test_show_account_classification_with_authentication_without_validation_error(): void
    {

        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->getJson("{$this->url}/{$this->account_classification['id']}");

        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('message', 'string')
                ->where('data', fn (Collection $data) => is_array($data->toArray()))
        );
    }

    /**
     * Test modify a single resource without authentication.
     */
    public function test_modify_account_classification_without_authentication(): void
    {
        $response = $this->getJson("api/admin/modify-account-category/{$this->account_classification['id']}");

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }


    /**
     * Test create resource without authentication.
     */
    public function test_create_account_classification_without_authentication(): void
    {
        $data = [];
        $response = $this->postJson($this->url, $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }


    /**
     * Test create new resource with authentication with validation error.
     */
    public function test_create_account_classification_with_authentication_with_validation_error(): void
    {
        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->postJson($this->url, []);

        $response->assertStatus(412)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['error', 'data'])
            );
    }


    /**
     * Test create new resource with authentication without validation error.
     */
    public function test_create_account_classification_with_authentication_without_validation_error(): void
    {
        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->postJson($this->url, $this->data);

        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('message', 'string')
            ->where('data', fn (Collection $data) => is_array($data->toArray()))
        );
    
        $created = $response['data'];
        $response = $this->withCookie('token', $this->token)
        ->actingAs($this->user)
        ->deleteJson("{$this->url}/{$created['id']}");
    }

    /**
     * Test update resource without authentication.
     */
    public function test_update_account_classification_without_authentication(): void
    {
        $data = [];
        $response = $this->patchJson("{$this->url}/{$this->created['id']}", $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }


    /**
     * Test update resource with authentication with validation error.
     */
    public function test_update_account_classification_with_authentication_with_validation_error(): void
    {
        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->patchJson("{$this->url}/{$this->created['id']}", []);

        $response->assertStatus(412)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['error', 'data'])
            );
    }


    /**
     * Test update resource with authentication without validation error.
     */
    public function test_update_account_classification_with_authentication_without_validation_error(): void
    {
        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->patchJson("{$this->url}/{$this->created['id']}", $this->data);

    
        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('message', 'string')
            ->where('data', fn (Collection $data) => is_array($data->toArray()))
        );
    }


    /**
     * Test delete resource without authentication.
     */
    public function test_delete_account_classification_without_authentication(): void
    {        
        $response = $this->deleteJson("{$this->url}/{fake()->numberBetween(1111111, 2147483647)}");

        $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
    }


    /**
     * Test delete resource with authentication with validation error.
     */
    public function test_delete_account_classification_with_authentication_with_validation_error(): void
    {

        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->deleteJson("{$this->url}/{fake()->numberBetween(1111111, 2147483647)}");

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->whereType('message', 'string')
                    ->etc()
            );
    }

    /**
     * Test delete resource without authentication.
     */
    public function test_delete_account_classification_with_authentication_without_validation_error(): void
    {

        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->postJson($this->url, $this->data);

        $created = $response['data'];

        $response = $this->withCookie('token', $this->token)
            ->actingAs($this->user)
            ->deleteJson("$this->url/{$created['id']}");

        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('message', 'string')
                ->etc()
        );

        $this->assertDatabaseMissing('account_classifications', ['id' => $created['id']]);
    }
}
