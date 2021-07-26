@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Update Project Version Guide</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_versions.update_guide', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('guide_id', $guide->id) }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Guide Title</label>
                                {{ Form::text('title', $guide->title, ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Guide Description</label>
                                <textarea name="description" class="form-control compulsory" required>{{ $guide->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class='input_fields_wrap'>
                        @php $steps = $guide->steps()->get(); $counter = 1; @endphp

                        @foreach($steps as $step)
                            {{ Form::hidden('original_step_ids[]', $step->id) }}
                            <div>
                                {{ Form::hidden('step_id[]', $step->id) }}
                                <hr>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <img src='{{ $step->images }}' width='600' height='250' alt='image' id='image_preview{{ $counter }}'>

                                        <br><br>

                                        <div class='custom-file'>
                                            <input type='file' name='step_image[]' class='custom-file-input chosen_image' id='{{ $counter }}' onchange='preview_image(this)'>
                                            <label class='custom-file-label' for='image_preview'>Choose image</label>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <h3>Step {{ $counter }}</h3>

                                        <div class='form-group'>
                                            <label>Step Description</label>
                                            <textarea name='step_description[]' class='form-control compulsory' required rows="7">{{ $step->description }}</textarea>
                                        </div>

                                        @if($counter > 1)
                                            <br><br>
                                            <a class='remove_field btn btn-danger btn-rounded btn-sm text-white'><i class='fa fa-trash'></i> Remove Step</a>
                                        @endif
                                    </div>
                                </div>
                                <br><br>
                            </div>
                            @php $counter++ @endphp
                        @endforeach
                    </div>

                    <br><br>

                    {{ Form::button('Submit',['type'=>'submit','class'=>'btn btn-success waves-effect waves-light m-r-10']) }}
                    {{ Form::button('Cancel',['type'=>'reset','class'=>'btn btn-default waves-effect waves-light']) }}
                    <a class="btn btn-primary waves-effect waves-light text-white add_step">Add Step</a>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        let wrapper = $(".input_fields_wrap");

        let x = <?php echo $counter - 1; ?>; // initial row count

        $(".add_step").click(function (e) {
            e.preventDefault();
            x++;
            $(wrapper).append("\
                <div><hr>\
                <input type='hidden' value='0' name='step_id[]'/> \
            <div class='row'>\
                    <div class='col-md-6'>\
                    <img src='http://placehold.jp/600x250.png' width='600' height='250' alt='image' id='image_preview" + x + "'>\
                    <br><br>\
                    <div class='custom-file'>\
                    <input type='file' name='step_image[]' class='custom-file-input chosen_image' id='" + x + "' onchange='preview_image(this)' required>\
                    <label class='custom-file-label' for='image_preview'>Choose image</label>\
            </div>\
            </div>\
            <div class='col-md-6'>\
            <h3>Step " + x + "</h3>\
            <div class='form-group'>\
            <label>Step Description</label>\
            <textarea name='step_description[]' class='form-control compulsory' required rows='7'></textarea>\
            </div>\
            <a class='remove_field btn btn-danger btn-rounded btn-sm text-white'><i class='fa fa-trash'></i> Remove Step</a>\
            </div>\
            </div></div>");
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').parent('div').parent('div').remove();
        });

        function preview_image(element) {
            if (element.files) {
                let current_id = element.id;
                var filesAmount = element.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $("#image_preview" + current_id).attr('src', event.target.result);
                    }

                    reader.readAsDataURL(element.files[i]);
                }
            }
        }
    </script>
@endpush
