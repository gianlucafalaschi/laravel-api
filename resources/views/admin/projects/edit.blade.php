@extends('layouts.admin')

@section('content')
    <h1>Edit project:<br> {{ $project->name }}</h1>

    <form action="{{ route('admin.projects.update', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label">Projects Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $project->name) }}">
        </div>
        @error('name')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="mb-3">
          <label for="cover_image" class="form-label">Image</label>
          <input class="form-control" type="file" id="cover_image" name="cover_image">
        </div>
        @error('cover_image')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- mostro l'immagine se presente nel database, altrimenti scrivo che nessuma immagine e' caricata  --}}
        @if ($project->cover_image)
            <img width="150" src="{{  asset('storage/' . $project->cover_image) }}" alt="{{ $project->name }}">
        @else
          <p><strong>No image uploaded</strong></p> 
        @endif

        <div class="mb-3 mt-3">
          <label for="type_id" class="form-label">Type</label>
          <select class="form-select" id="type_id" name="type_id">
            <option value="">Select a type</option>
            @foreach ($types as $type)
              <option @selected($type->id == old('type_id', $project->type_id)) value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        @error('type_id')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="mb-3">
          <h6>Technologies</h6>
          @foreach ($technologies as $technology)
          <div class="form-check">
              <input @checked($project->technologies->contains($technology)) class="form-check-input" type="checkbox" name="technologies[]" value="{{ $technology->id }}" id="technology-{{ $technology->id }}">
              <label class="form-check-label" for="technology-{{ $technology->id }}">
                {{ $technology->name}}
              </label>
          </div>
          @endforeach
      </div>
      @error('technologies')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror

        <div class="mb-3">
          <label for="client_name" class="form-label">Client name</label>
          <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}">
        </div>
        @error('client_name')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="mb-3">
            <label for="summary" class="form-label">Summary</label>
            <textarea class="form-control" id="summary" rows="3" name="summary">{{ old('summary', $project->summary) }}</textarea>
        </div>
        @error('summary')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>  
@endsection