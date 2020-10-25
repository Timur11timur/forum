<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $thread = factory(Thread::class)->make();

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('login'));

        $this->get('/threads/create')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function new_user_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(User::class)->state('uncomfirmed')->create();

        $this->signIn($user);

        $thread = factory(Thread::class)->make();

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['title' => 'Foo title', 'slug' => 'foo-title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::where('slug','foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::where('slug','foo-title-3')->exists());
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');

        $this->signIn();

        $response = $this->delete($thread->path());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->user()->id]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
                'subject_id' => $thread->id,
                'subject_type' => get_class($thread),
            ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply),
        ]);

        $this->assertEquals(0, Activity::count());
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = factory(Thread::class)->make($overrides);

        return $this->post(route('threads'), $thread->toArray());
    }
}
