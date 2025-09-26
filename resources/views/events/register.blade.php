@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <livewire:EventRegistration :event="$event" />
@endsection

