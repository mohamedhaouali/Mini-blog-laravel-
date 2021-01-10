@extends('layouts.app')

@section('extra-js')

    <script>
        function toggleReplyComment(id)
        {
            let element = document.getElementById('replyComment-' + id);
            element.classList.toggle('d-none');
        }
    </script>

@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-info">{{ $topic->title }}</h4>
                <p>{{ $topic->content }}</p>
                <div class="d-flex flex-column">
                    <small>Posté le {{ $topic->created_at->format('d/m/Y') }} par <span class="badge badge-primary">{{ $topic->user->name }}</span></small>
                </div>
                <div class="d-flex justify-content-between align-items-center ">

                    @can('update', $topic)
                    <a href="{{ route('topics.edit', $topic) }}" class="btn btn-warning">Editer ce topic</a>
                    @endcan
                     @can('delete', $topic)
                    <form action="{{ route('topics.destroy', $topic) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                        @endcan

                </div>
            </div>
        </div>
        <hr>
        <h5>Commentaires</h5>
        @forelse ($topic->comments as $comment)
            <div class="card mb-2">
            <div class="card-body">
                {{ $comment->content }}
                <div class="d-flex flex-column">

                    <small>Posté le {{ $comment->created_at->format('d/m/Y') }} par <span class="badge badge-primary">{{ $comment->user->name }}</span></small>
                </div>
            </div>
    </div>

            @foreach ($comment->comments as $replyComment)
                <div class="card mb-2 ml-5">
                    <div class="card-body">
                        {{ $replyComment->content }}
                        <div class="d-flex flex-column">
                            <small>Posté le {{ $comment->created_at->format('d/m/Y') }} par <span class="badge badge-primary">{{ $comment->user->name }}</span></small>
                        </div>
                    </div>
                </div>
            @endforeach



        @auth
            <button  class="btn btn-info mb-3" onclick="toggleReplyComment({{ $comment->id }})">Répondre </button>
        @csrf
                <form action="{{ route('comments.storeReply', $comment) }}" method="POST" class="mb-3 ml-5 d-none" id="replyComment-{{ $comment->id }}">
                    @csrf
                    <div class="form-group">
                        <label for="replyComment">Ma réponse</label>
                        <textarea name="replyComment" class="form-control @error('replyComment') is-invalid @enderror" id="replyComment" rows="5"></textarea>
                        @error('replyComment')
                        <div class="invalid-feedback">{{ $errors->first('replyComment') }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Répondre à ce commentaire</button>
                </form>
            @endauth
        @empty
        <div class="alert alert-info">Aucun commentaire pour ce topic</div>
    @endforelse

        <form action="{{ route('comments.store', $topic) }}" method="POST" class="mt-3">
            @csrf
            <div class="form-group">
                <label for="content">Votre commentaire</label>
                <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" rows="5"></textarea>
            </div>
            @error('content')
            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">Soumettre mon commentaire</button>
        </form>
    </div>
@endsection
