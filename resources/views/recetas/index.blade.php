@extends('layouts.app')

@section('botones')
    <a class="btn btn-primary mr-2 text-white" href="{{ route('recetas.create') }}">Crear Receta</a>
@endsection

@section('content')

<h2 class="text-center mb-5">Administra tus recetas</h2>

<div class="col-md-10 mx-auto bg-white p-3">
    <table class="table">
        <thead class="bg-primary text-light">
            <tr>
                <th scope="col">Titulo</th>
                <th scope="col">Categoría</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recetas as $receta)
            <tr>
                <td>{{ $receta->titulo }}</td>
            <td>{{ $receta->categoria->nombre /* categoria->nombre es por el nombre del metodo creado en el modelo de receta*/}}</td>
                <td>
                    <a href="{{ route('recetas.show', ['receta' => $receta->id]) }}" class="btn btn-success d-block mb-2">Ver</a>
                    <a href="{{ route('recetas.edit', ['receta' => $receta->id]) }}" class="btn btn-dark d-block mb-2">Editar</a>
                    {{--<form action="{{ route('recetas.destroy', ['receta' => $receta->id]) }}" method="POST">
                        @csrf 
                        @method('DELETE')
                    </form> --}}
                    <eliminar-receta receta-id={{ $receta->id }}></eliminar-receta>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection