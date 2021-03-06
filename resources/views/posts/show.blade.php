@extends('main')

@section('title', '| View Post')

@section('content')
  <div class="row">
    <div class="col-md-8">
      <h1>{{ $post->title }}</h1>

      <p class="lead">{{ $post->body }}</p>

      <hr>

      <div class="tags">
        @foreach($post->tags as $tag)
          <label for="" class="label label-default">{{ $tag->name }}</label>
        @endforeach
      </div>
    </div>
    <div class="col-md-4">
      <div class="well">
        <dl class="dl-horizontal">
          <label>Url Slug:</label>
          <p><a href="/blog/{{ $post->slug }}">{{ url('blog', $post->slug) }}</a></p>
        </dl>
        <dl class="dl-horizontal">
          <label>Category:</label>
          <p>{{ $post->category->name }}</p>
        </dl>
        <dl class="dl-horizontal">
          <label>Created At:</label>
          <p>{{ date('M j, Y h:ia', strtotime($post->created_at)) }}</p>
        </dl>
        <dl class="dl-horizontal">
          <label>Last Updated:</label>
          <p>{{ date('M j, Y h:ia', strtotime($post->updated_at)) }}</p>
        </dl>
        <hr>
        <div class="row">
          <div class="col-sm-6">
            <a href="{{ route('posts.edit', $post->id, 'Edit') }}" class="btn btn-primary btn-block">Edit</a>
          </div>
          <div class="col-sm-6">
            {!! Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'DELETE']) !!}

            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-block']) !!}

            {!! Form::close() !!}
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            {{ Html::linkROute('posts.index', '<< See All Posts', [], array('class' => 'btn btn-default btn-block', 'style' => 'margin-top: 25px;')) }}
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection