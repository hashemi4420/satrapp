@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('شما به این صفحه دسترسی ندارید.'))
{{--@section('message', __($exception->getMessage() ?: 'Forbidden'))--}}
