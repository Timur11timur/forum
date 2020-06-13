@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </span>

                            @can ('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>

                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>

                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

    {{--                <div class="mt-2">--}}
    {{--                    {{ $replies->links() }}--}}
    {{--                </div>--}}


                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>This thread was published {{ $thread->created_at->diffForHumans() }}
                                by <a href="#">{{ $thread->creator->name }}</a>, and currently
                                has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
