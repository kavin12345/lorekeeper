@extends('admin.layout')

@section('admin-title') News @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'News' => 'admin/news', ($poll->id ? 'Edit' : 'Create').' Poll' => $poll->id ? 'admin/news/editpoll/'.$poll->id : 'admin/news/createpoll']) !!}

<h1>{{ $poll->id ? 'Edit' : 'Create' }} Poll
    @if($poll->id)
        <a href="#" class="btn btn-danger float-right delete-poll-button">Delete Poll</a>
    @endif
</h1>

{!! Form::open(['url' => $poll->id ? 'admin/news/editpoll/'.$poll->id : 'admin/news/createpoll', 'files' => true]) !!}

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Question') !!}
        {!! Form::text('question', $poll->question, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 1') !!}
        {!! Form::text('option_1', '', ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('Option 2') !!}
        {!! Form::text('option_2', '', ['class' => 'form-control']) !!}
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
    {!! Form::submit($poll->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

@endsection
