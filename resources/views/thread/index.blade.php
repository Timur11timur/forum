@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('thread.layouts.list')

                {{ $threads->render() }}
            </div>
        </div>
    </div>
@endsection
