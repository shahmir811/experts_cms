@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>Customer Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-person-fill me-1"></i>Name:</div>
                        <div class="col-8">{{ $customer->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-envelope-fill me-1"></i>Email:</div>
                        <div class="col-8">{{ $customer->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-telephone-fill me-1"></i>Phone:</div>
                        <div class="col-8">{{ $customer->phone }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-building me-1"></i>City:</div>
                        <div class="col-8">{{ $customer->city }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-flag-fill me-1"></i>State:</div>
                        <div class="col-8">{{ $customer->state }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted"><i class="bi bi-geo-alt-fill me-1"></i>Address:</div>
                        <div class="col-8">{{ $customer->address }}</div>
                    </div>
                </div>
                <div class="card-footer text-end bg-light rounded-bottom-4">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left-circle me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
