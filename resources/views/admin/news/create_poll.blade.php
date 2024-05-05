@extends('admin.layout')

@section('admin-title') News @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'News' => 'admin/news', 'Create Poll' => 'admin/news/createpoll']) !!}

<h1>Create Poll</h1>

{!! Form::open(['url' => 'admin/news/createpoll', 'files' => true]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title (required)') !!}
            {!! Form::text('title', '', ['class' => 'form-control', 'required']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Closing Time (required)') !!} {!! add_help('This is the time the poll will close. When closed, users will not be able to vote any more and results will be displayed.') !!}
            {!! Form::text('closing_time', '', ['class' => 'form-control', 'id' => 'datepicker', 'required']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Question (required)') !!}
    {!! Form::text('question', '', ['class' => 'form-control', 'required']) !!}
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 1 (required)') !!}
        {!! Form::text('option_1', '', ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 2 (required)') !!}
        {!! Form::text('option_2', '', ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 3') !!}
        {!! Form::text('option_3', '', ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 4') !!}
        {!! Form::text('option_4', '', ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 5') !!}
        {!! Form::text('option_5', '', ['class' => 'form-control']) !!}
    </div>
</div>

<div class="text-right">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {    
    $( "#datepicker" ).datetimepicker({
        dateFormat: "yy-mm-dd",
        timeFormat: 'HH:mm:ss',
    });
});
    
</script>
@endsection
