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

    <div class="modal fade" id="deleteAlbum" tabindex="-1" role="dialog">
        <form id="myForm">
            <input type="hidden" id="deletedId" value="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Delete album images
                    </label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="2" onchange="toggleList()">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Move images to other album
                    </label>
                </div>
                    <select class="form-control" id="selectAlbum" style="width: 100%;display:none">
                        <option>Choose Album</option>
                        @foreach(\Auth::user()->albums()->get() as $album)
                        <option value="{{ $album->id }}">{{ $album->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a id="deleteItem" class="btn btn-primary">Delete</a>
                </div>
                </div>

            </div>
        </form>
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

    <script>
        $('body').on('click', '.delete', function(e) {
            e.preventDefault()
            var id = $(this).closest('tr').attr('id')
            if(id !== undefined) {
                $('#deletedId').val(id)
                $('#deleteAlbum').modal('show');
            }
        })

        $("#deleteItem").click(function(e) {
            e.preventDefault();
            const choice = $('input[name=flexRadioDefault]:checked', '#myForm').val()
            const album = $("#selectAlbum").val()
            const id = $('#deletedId').val()
            $.post("/albums", {
                '_method': 'delete',
                '_token': '{{ csrf_token() }}',
                'choice': choice,
                'album': album,
                'id': id

            })
        })

        function toggleList() {
            $('#selectAlbum').show();
        }
    </script>
@endpush
