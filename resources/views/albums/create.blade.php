@extends('layouts.app')

@section('content')
<div class="container">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form class="form-horizontal" action="{{ route('albums.store') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="mb-3">
        <h5 for="title" class="form-label">Album Title</h5>
        <input type="text" name="title" class="form-control" id="title" required>
    </div>
    <div class="row align-items-center">
        <div class="col-md-10 dynamic-field mb-3" id="dynamic-field-1">
            <div class="row" >
                <div class="col-md-3">
                    <div class="staresd">
                        <div class="imgup">
                            <h5><i class="fa fa-image me-1"></i>Upload Photo</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="staresd">
                        <div class="imgup">
                            <input type="file" name="images[]" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <input type="text" id="field" class="form-control" name="names[]" placeholder="Name*" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mt-30 append-buttons">
            <div class="clearfix">
                <button type="button" id="add-button" class="btn btn-secondary btn-sm float-left text-uppercase shadow-sm"><i class="fa fa-plus fa-fw"></i>
                </button>
                <button type="button" id="remove-button" class="btn btn-secondary btn-sm float-left text-uppercase ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
    </form>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    var buttonAdd = $("#add-button");
    var buttonRemove = $("#remove-button");
    var className = ".dynamic-field";
    var count = 0;
    var field = "";
    var maxFields =50;

    function totalFields() {
        return $(className).length;
    }

    function addNewField() {
        count = totalFields() + 1;
        field = $("#dynamic-field-1").clone();
        field.attr("id", "dynamic-field-" + count);
        field.children("label").text("Field " + count);
        field.find("input").val("");
        $(className + ":last").after($(field));
    }

    function removeLastField() {
        if (totalFields() > 1) {
        $(className + ":last").remove();
        }
    }

    function enableButtonRemove() {
        if (totalFields() === 2) {
        buttonRemove.removeAttr("disabled");
        buttonRemove.addClass("shadow-sm");
        }
    }

    function disableButtonRemove() {
        if (totalFields() === 1) {
        buttonRemove.attr("disabled", "disabled");
        buttonRemove.removeClass("shadow-sm");
        }
    }

    function disableButtonAdd() {
        if (totalFields() === maxFields) {
        buttonAdd.attr("disabled", "disabled");
        buttonAdd.removeClass("shadow-sm");
        }
    }

    function enableButtonAdd() {
        if (totalFields() === (maxFields - 1)) {
        buttonAdd.removeAttr("disabled");
        buttonAdd.addClass("shadow-sm");
        }
    }

    buttonAdd.click(function() {
        addNewField();
        enableButtonRemove();
        disableButtonAdd();
    });

    buttonRemove.click(function() {
        removeLastField();
        disableButtonRemove();
        enableButtonAdd();
    });
});

</script>
@endpush
