@extends('adminlte::page')
@section('title', 'Items')

@section('content_header')
    <h1>Items</h1>
@stop

@section('content')
    <a href="/items/create" class="btn btn-primary">Create New</a>
    <br><br>
    @if(count($items)>0)
    <div class="box">
            <div class="box-header">
                <h3 class="box-title">Items</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Available Quantity</th>
                            <th>Created At</th>
                            <th>Category</th>
                            <th colspan="2">Actions</th>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->item_name}}</td>
                                <?php $setting = App\Setting::first(); ?>
                                <td>{{$setting->currency}} {{$item->item_price}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                <?php $category = App\Category::where('id', $item->item_category)->first(); ?>
                                <td>{{$category->category}}</td>
                                <td>
                                    <a href="/items/{{$item->id}}/edit" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                                <td>
                                    {!!Form::open(['action' => ['ItemController@destroy', $item->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::submit('Delete', ['class' => 'btn btn-danger btn-sm'])}}
                                    {!!Form::close()!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                {{$items->links()}}
            </div>
        </div>
    @else
        <p>No items Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop