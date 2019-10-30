@extends('adminlte::page')

@section('title', 'Utilities')

@section('content_header')
    <h1>Utilities</h1>
@stop

@section('content')
    <a href="/utilities/create" class="btn btn-primary btn-sm">Create One</a><br><br>
    @if(count($utilities) > 0)
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Utilities</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <th>Utility</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th colspan="2">Actions</th>
                        </tr>
                        @foreach($utilities as $utility)
                            <tr>
                                <td>{{$utility->id}}</td>
                                <td>{{$utility->utility}}</td>
                                <td>{{$utility->description}}</td>
                                <?php $setting = App\Setting::first(); ?>
                                <td>{{$setting->currency}} {{$utility->price}}</td>
                                <td><a href="/utilities/{{$utility->id}}/edit" class="btn btn-primary">Edit</a></td>
                                <td>
                                    {!!Form::open(['action' => ['UtilityController@destroy', $utility->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::submit('Remove', ['class' => 'btn btn-danger'])}}
                                    {!!Form::close()!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No Utilities Found.</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop