<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

/**
 * Class ReplyController
 * @package App\Http\Controllers
 */
class ReplyController extends Controller
{
    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channel
     * @param Thread $thread
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channel, Thread $thread)
    {
        try {
            request()->validate([
                'body' => 'required|spamfree',
            ]);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->user()->id
            ]);

            return $reply->load('owner');
        } catch(\Exception $e) {
            return response('Sorry your reply could not be saved at this time.', 422);
        }
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate([
                'body' => 'required|spamfree',
            ]);

            $reply->update(['body' => request('body')]);
        } catch(\Exception $e) {
            return response('Sorry your reply could not be saved at this time.', 422);
        }

    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
