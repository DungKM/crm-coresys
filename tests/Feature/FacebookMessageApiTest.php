<?php

namespace Tests\Feature;

use App\Models\FacebookMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacebookMessageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_facebook_message(): void
    {
        $payload = [
            'thread_id'    => '12345',
            'sender_id'    => 'sender-1',
            'recipient_id' => 'page-1',
            'sender_name'  => 'John Doe',
            'message'      => 'Hello from Facebook!',
            'direction'    => 'inbound',
            'status'       => 'received',
        ];

        $response = $this->postJson('/api/facebook/messages', $payload);

        $response->assertCreated();

        $this->assertDatabaseHas('facebook_messages', [
            'thread_id' => '12345',
            'message'   => 'Hello from Facebook!',
        ]);
    }

    public function test_it_lists_filtered_messages(): void
    {
        FacebookMessage::factory()->create([
            'thread_id' => 'thread-a',
            'direction' => 'inbound',
            'status'    => 'received',
        ]);

        FacebookMessage::factory()->create([
            'thread_id' => 'thread-b',
            'direction' => 'outbound',
            'status'    => 'sent',
        ]);

        $response = $this->getJson('/api/facebook/messages?direction=inbound');

        $response->assertOk();
        $response->assertJsonPath('data.0.thread_id', 'thread-a');
        $response->assertJsonMissingPath('data.1.thread_id', 'thread-b');
    }
}
