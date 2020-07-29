@extends('layouts.app')

@section('botones')
    <a class="btn btn-primary mr-2 text-white" href="{{ route('recetas.index') }}">Volver</a>
@endsection

@section('content')

    <h2 class="text-center mb-5">Crear nueva recetas</h2>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form method="POST" action="{{ route('recetas.store') }}" novalidate>

                <div class="form-group">
                    @csrf
                    <label for="titulo">Titulo Receta</label>
                    <input type="text"
                        name="titulo"
                        class="form-control @error('titulo') is-invalid @enderror"
                        id="titulo"
                        placeholder="Titulo Receta"
                        value="{{ old('titulo') }}"
                        required
                    />
                    @error('titulo')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary text-white" value="Agregar Receta">
                </div>
            </form>
        </div>
    </div>

@endsection