@extends('layouts.admin')

@section('content')
    <h1>All the projects</h1>

    <table class="table table-striped">
        <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Slug</th>
              <th scope="col">Client Name</th>
              <th scope="col">Created at</th>
              <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->slug }}</td>
                    <td>{{ $project->client_name }}</td>
                    <td>{{ $project->created_at }}</td>
                    <td>
                        <div>
                            <a href="{{ route('admin.projects.show', ['project' => $project->id]) }}">View</a>
                        </div>
                        <div>
                            <a href="{{ route('admin.projects.edit', ['project' => $project->id]) }}">Edit</a>
                        </div>
                        <div>
                            {{-- il delete non puP' essere un link, deve essere un form, perche' i link funzionano solo con method GET --}}
                            <form action="{{ route('admin.projects.destroy', ['project' => $project->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach 
        </tbody>
    </table>

@endsection