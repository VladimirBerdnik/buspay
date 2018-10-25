@extends('layouts.error')

@section('title') {{ trans('errors.not_found') }} @endsection

@section('message')

    {{ $exception->getMessage() ?: trans('errors.page_not_found')  }}

@endsection
