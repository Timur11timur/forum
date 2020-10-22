<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    /** @test */
    public function it_increments_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path());

        $trendings = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trendings);

        $this->assertEquals($thread->title, json_decode($trendings[0])->title);
    }
}
