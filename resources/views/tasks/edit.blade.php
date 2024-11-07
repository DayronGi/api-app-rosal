@extends('layouts.plantillas')

@section('title', 'Tasks edit')

@section('content')
    <h1>En esta pagina puedes editar un curso</h1>
    <form action="{{route('tasks.update', $task)}}" method="post">
        @csrf
        @method('put')
        <label>
            Nombre:
            <br>
            <input type="text" name="name" value="{{$task->name}}">
        </label>
        <br>
        <label>
            Descripcion:
            <br>
            <textarea name="description" rows="5">{{$task->description}}</textarea>
        </label>
        <br>
        <label>
            Categoria:
            <br>
            <input type="text" name="category" value="{{$task->category}}">
        </label>
        <br>
        <button type="submit">Actualizar formulario</button>
    </form>
@endsection
