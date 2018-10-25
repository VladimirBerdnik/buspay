@extends('layouts.error')

@section('title'){{ trans('errors.access_denied') }} @endsection

@section('message')

    {{ $exception->getMessage() ?: trans('errors.no_access') }}

@endsection
