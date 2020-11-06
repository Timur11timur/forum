<div class="card" v-if="editing">
    <div class="card-header py-1">
        <span class="d-flex align-items-center">
            <input type="text" class="form-control" v-model="form.title">
        </span>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea rows="10" class="form-control" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center py-1">
        <div>
            <button class="btn border btn-default btn-sm" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn border btn-primary btn-sm" @click="update">Update</button>
            <button class="btn border btn-default btn-sm" @click="cancel">Cancel</button>
        </div>

        @can ('update', $thread)
            <form action="{{ $thread->path() }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
        @endcan
    </div>
</div>

<div class="card" v-else>
    <div class="card-header d-flex justify-content-between align-items-center py-1">
        <span class="d-flex align-items-center">
            <img src="{{ $thread->creator->avatar_path }}"
                 alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
            <span>
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                <span v-text="form.title"></span>
            </span>
        </span>
    </div>

    <div class="card-body" v-text="form.body">
    </div>

    <div class="card-footer py-1">
        <button class="btn border btn-default btn-sm" @click="editing = true">Edit</button>
    </div>
</div>
