@extends("layouts.app")

@section("content")
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card card-body bg-light p-3 mb-4">
            <h3><a href="/posts/{{$post->id}}"> {{$post->title}} </a></h3>
                <small>Written on {{$post->created_at}} by {{$post->user->name}} </small>
            </div>
        @endforeach
    {{ $posts->links() }}
    @else 
        <p>No posts found </p>
    @endif
@endsection

