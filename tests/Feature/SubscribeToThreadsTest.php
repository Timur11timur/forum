<?php


namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->user()->id,
            'body' => 'Some reply body'
        ]);

        //$this->assertCount(1, $thread->subscriptions);
        //$this->assertCount(1, auth()->user()->notifications);
    }
}
