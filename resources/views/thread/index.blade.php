@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <a href="{{ $thread->path() }}">
                                    @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                        <span class="text-dark">{{ $thread->title }}</span>
                                    @else
                                        {{ $thread->title }}
                                @endif
                                </a></h4>
                            <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
                        </div>

                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                @empty
                    <p>There are no relevant results at this time</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
