@extends('layouts.app')

@section('title') Polls @endsection

@section('content')
{!! breadcrumbs(['Polls' => 'polls']) !!}
<h1>Polls</h1>
@if(count($polls))
    @foreach($polls as $poll)
        <br>
            <small>
                Posted {!! pretty_date($poll->created_at) !!}
            </small>
            {{ PollWriter::draw(Inani\Larapoll\Poll::find($poll->id)) }}
        </br>
    @endforeach
@else
    <div>No polls yet.</div>
@endif
@endsection
