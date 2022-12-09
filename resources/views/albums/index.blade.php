@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('message'))
            <div id="back-message" class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>Oh yeah!</strong> {{session('message')}}
             </div>
        @endif
        <div class="card">
            <div class="card-header">Manage Albums</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $('body').on('click', '.edit', function(e) {
            e.preventDefault()
            var id = $(this).closest('tr').attr('id')
            if(id !== undefined) {
                window.location.href = '/albums/' + id + '/edit'
            }
        })
    </script>
@endpush
