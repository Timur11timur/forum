@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h1>{{ $profileUser->name }}
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small></h1>
                </div>
                <hr />
                @foreach($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <span>
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </span>

                            <span>
                                {{ $thread->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                @endforeach

                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection