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

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
