<div id="post-{{ $post->sequence }}"
    class="post card mb-2 {{ $post->trashed() ? 'deleted' : '' }}"
    :class="{ 'border-primary': selectedPosts.includes({{ $post->id }}) }">
    <div class="card-header">
        @if (! isset($single) || ! $single)
            <span class="float-right">
                <a href="{{ Forum::route('thread.show', $post) }}">#{{ $post->sequence }}</a>
                @if (! $post->isFirst)
                    @can ('deletePosts', $post->thread)
                        <input type="checkbox" name="posts[]" :value="{{ $post->id }}" v-model="selectedPosts">
                    @endcan
                @endif
            </span>
        @endif

        {{ $post->authorName }}

        <span class="text-muted">
            {{ $post->posted }}
            @if ($post->hasBeenUpdated())
                ({{ trans('forum::general.last_updated') }} {{ $post->updated }})
            @endif
        </span>
    </div>
    <div class="card-body">
        @if (! is_null($post->parent))
            @include ('forum::post.partials.quote', ['post' => $post->parent])
        @endif

        @if ($post->trashed())
            @can ('viewTrashedPosts')
                {!! Forum::render($post->content) !!}
                <br>
            @endcan
            <span class="badge badge-pill badge-danger">{{ trans('forum::general.deleted') }}</span>
        @else
            {!! Forum::render($post->content) !!}
        @endif

        @if (! isset($single) || ! $single)
            <div class="text-right">
                @if (! $post->trashed())
                    <a href="{{ Forum::route('post.show', $post) }}" class="card-link text-muted">{{ trans('forum::general.permalink') }}</a>
                    @if (! $post->isFirst)
                        @can ('delete', $post)
                            <a href="{{ Forum::route('post.confirm-delete', $post) }}" class="card-link text-danger">{{ trans('forum::general.delete') }}</a>
                        @endcan
                    @endif
                    @can ('edit', $post)
                        <a href="{{ Forum::route('post.edit', $post) }}" class="card-link">{{ trans('forum::general.edit') }}</a>
                    @endcan
                    @can('reply', $post->thread)
                        <a href="{{ Forum::route('post.create', $post) }}" class="card-link">{{ trans('forum::general.reply') }}</a>
                    @endcan
                @else
                    @can ('restore', $post)
                        <a href="{{ Forum::route('post.confirm-restore', $post) }}" class="card-link">{{ trans('forum::general.restore') }}</a>
                    @endcan
                @endif
            </div>
        @endif
    </div>
</div>