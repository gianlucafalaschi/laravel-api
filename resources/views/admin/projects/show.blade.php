@extends('layouts.admin')

@section('content')
    <h1>{{ $project->name }}</h1>
    {{-- messaggio di creazione progetto o modifica progetto --}}
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <strong>ID:</strong> {{ $project->id }}
    </div>
    
    <div>
        <strong>Slug:</strong> {{ $project->slug }}
    </div>

    <div>
        {{-- type è il nome della funzione nel model Project per prendere la categoria dal project  --}}
        <strong>Type:</strong> {{ $project->type ? $project->type->name : 'No Type' }}  
    </div>
    
    <div>
        {{-- technologies è il nome della funzione nel model project per prendere le technologie dal project --}}
        <strong>Technologies:</strong>
        @if (count($project->technologies) > 0)
            @foreach ($project->technologies as $technology)
                {{ $technology->name }} @if (!$loop->last), @endif  {{-- mette la virgola solo se non è nell'ultima iterazione --}}
            @endforeach
        @else
            No Technology yet
        @endif  
    </div>
    
    <div>
        <strong>Client name:</strong></strong> {{ $project->client_name }}
    </div>
    
    <div>
        <strong>Created on:</strong></strong> {{ $project->created_at }}
    </div>
   
    <div>
        <strong>Modified on:</strong></strong> {{ $project->updated_at }}
    </div>

    @if ($project->cover_image) 
        <div>
            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{$project->name}}">
        </div>
    @endif

    @if ($project->summary)
        <p class="mt-3"><strong>Description: <br></strong>{{ $project->summary }}</p>
    @endif

    <div class="d-flex gap-4">
        <div>
            <a class="btn btn-primary" href="{{ route('admin.projects.edit', ['project' => $project->id]) }}">Edit</a>
        </div>
        <div>
            <form action="{{ route('admin.projects.destroy', ['project' => $project->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
    

@endsection