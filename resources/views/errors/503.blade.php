@extends('layouts.error')

@section('title') {{ trans('errors.service_not_available') }} @endsection

@section('message')

    <p>
        {{ trans('errors.service_not_available') }}
    </p>
    <p>
        {{ trans('errors.try_later') }}
    </p>

@endsection
