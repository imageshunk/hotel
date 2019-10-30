@extends('adminlte::page')

@section('title', 'Room Types List')

@section('content_header')
    <h1>Room Types</h1>
@stop

@section('content')
    @if(count($packages) > 0)
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Room Types</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 30px">Id</th>
                            <th>Room Type</th>
                            <th colspan="2">Actions</th>
                        </tr>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{$package->id}}</td>
                                <td>{{$package->package_name}}</td>
                                <?php $setting = App\Setting::first(); ?>
                                <td><a href="/room-types/{{$package->id}}/edit" class="btn btn-primary">Edit</a></td>
                                <td>
                                    {!!Form::open(['action' => ['PackagesController@destroy', $package->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                    {!!Form::close()!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No Packages Found.</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop