@extends('layouts.app')

@push('styles')
    <style>
        .draggable {
            cursor: move;
            margin-bottom: 1rem;
            user-select: none;

            /* Center the content */
            align-items: center;
            display: flex;
            justify-content: center;

            /* Size */
            height: 4rem;
            width: 16rem;

            /* Misc */
            border: 1px solid #cbd5e0;
        }

        .placeholder {
            background-color: #edf2f7;
            border: 2px dashed #cbd5e0;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ $project_version->project_id }}">{{ get_name($project_version->project_id, 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $project_version->id }}">{{ $project_version->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Version Modules</li>
                </ol>
            </nav>

            {{ Form::hidden('current_module_title', '', ['id' => 'current_module_title']) }}
            {{ Form::hidden('current_module_parent_id', '', ['id' => 'current_module_parent_id']) }}
            {{ Form::hidden('parent_module_id', 0, ['id' => 'parent_module_id']) }}

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="row">
                                <div class="col-md-6"><h3>Modules</h3></div>
                                <div class="col-md-6">
                                    <a href="#" onclick="modules_back_button()" class="btn btn-outline-dark btn-rounded btn-sm">Back</a>
                                    <a href="#" class="btn btn-outline-success btn-rounded btn-sm" data-toggle="modal" data-target="#module_form">Add Module</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="modules_div">

                            @if(count($modules) > 0)
                                @foreach($modules as $module)
                                    <a href="#" onclick="select_module({{ $module->id }})">{{ $module->title }}</a>
                                    <br><br>
                                @endforeach
                            @else
                                <div class="text-center"><code>No modules available</code></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8"><h3 id="module_div_title">Module Print Order</h3></div>
                                <div class="col-md-4">
                                    <a href="/project_version_modules/set_manual_print_order_features/{{ $project_version->id }}" class="btn btn-outline-info btn-rounded">Set Features Print Order</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="text-center" style="color: maroon">Drag and Drop according to how you want to modules to appear when printing the manual</h3>
                            <hr>

                            {{ Form::open(['route' => 'project_version_modules.save_manual_print_order']) }}
                            {{ Form::hidden('version_id', $project_version->id, ['id' => 'version_id']) }}

                            <div id="module_details_div">
                                @include('flash::message')

                                @if(count($modules) > 0)
                                    <div id="list">
                                        @foreach($modules as $module)
                                            <div class="draggable">
                                                <input name="module_id[]" value="{{ $module->id }}" type="hidden">
                                                {{ $module->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center"><code>No modules available</code></div>
                                @endif
                            </div>
                            <br>

                            {{ Form::button('Save Module Print Order',['type'=>'submit','class'=>'btn btn-success waves-effect waves-light m-r-10']) }}
                            {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            load_draggable_lists();
        });

        function load_draggable_lists() {
            // Query the list element
            const list = document.getElementById('list');

            let draggingEle;
            let placeholder;
            let isDraggingStarted = false;

            // The current position of mouse relative to the dragging element
            let x = 0;
            let y = 0;

            // Swap two nodes
            const swap = function (nodeA, nodeB) {
                const parentA = nodeA.parentNode;
                const siblingA = nodeA.nextSibling === nodeB ? nodeA : nodeA.nextSibling;

                // Move `nodeA` to before the `nodeB`
                nodeB.parentNode.insertBefore(nodeA, nodeB);

                // Move `nodeB` to before the sibling of `nodeA`
                parentA.insertBefore(nodeB, siblingA);
            };

            // Check if `nodeA` is above `nodeB`
            const isAbove = function (nodeA, nodeB) {
                // Get the bounding rectangle of nodes
                const rectA = nodeA.getBoundingClientRect();
                const rectB = nodeB.getBoundingClientRect();

                return rectA.top + rectA.height / 2 < rectB.top + rectB.height / 2;
            };

            const mouseDownHandler = function (e) {
                draggingEle = e.target;

                // Calculate the mouse position
                const rect = draggingEle.getBoundingClientRect();
                x = e.pageX - rect.left;
                y = e.pageY - rect.top;

                // Attach the listeners to `document`
                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', mouseUpHandler);
            };

            const mouseMoveHandler = function (e) {
                const draggingRect = draggingEle.getBoundingClientRect();

                if (!isDraggingStarted) {
                    isDraggingStarted = true;

                    // Let the placeholder take the height of dragging element
                    // So the next element won't move up
                    placeholder = document.createElement('div');
                    placeholder.classList.add('placeholder');
                    draggingEle.parentNode.insertBefore(placeholder, draggingEle.nextSibling);
                    placeholder.style.height = `${draggingRect.height}px`;
                }

                // Set position for dragging element
                draggingEle.style.position = 'absolute';
                draggingEle.style.top = `${e.pageY - y}px`;
                draggingEle.style.left = `${e.pageX - x}px`;

                // The current order
                // prevEle
                // draggingEle
                // placeholder
                // nextEle
                const prevEle = draggingEle.previousElementSibling;
                const nextEle = placeholder.nextElementSibling;

                // The dragging element is above the previous element
                // User moves the dragging element to the top
                if (prevEle && isAbove(draggingEle, prevEle)) {
                    // The current order    -> The new order
                    // prevEle              -> placeholder
                    // draggingEle          -> draggingEle
                    // placeholder          -> prevEle
                    swap(placeholder, draggingEle);
                    swap(placeholder, prevEle);
                    return;
                }

                // The dragging element is below the next element
                // User moves the dragging element to the bottom
                if (nextEle && isAbove(nextEle, draggingEle)) {
                    // The current order    -> The new order
                    // draggingEle          -> nextEle
                    // placeholder          -> placeholder
                    // nextEle              -> draggingEle
                    swap(nextEle, placeholder);
                    swap(nextEle, draggingEle);
                }
            };

            const mouseUpHandler = function () {
                // Remove the placeholder
                placeholder && placeholder.parentNode.removeChild(placeholder);

                draggingEle.style.removeProperty('top');
                draggingEle.style.removeProperty('left');
                draggingEle.style.removeProperty('position');

                x = null;
                y = null;
                draggingEle = null;
                isDraggingStarted = false;

                // Remove the handlers of `mousemove` and `mouseup`
                document.removeEventListener('mousemove', mouseMoveHandler);
                document.removeEventListener('mouseup', mouseUpHandler);
            };

            // Query all items
            [].slice.call(list.querySelectorAll('.draggable')).forEach(function (item) {
                item.addEventListener('mousedown', mouseDownHandler);
            });
        }

        function update_modules_list() {
            let parent_module_id = $('#parent_module_id').val();

            $.ajax({
                method: 'GET',
                url: '/project_version_modules/fetch_modules/' + parent_module_id,
                success: function(response){
                    $('#modules_div').html(response);
                }
            });
        }

        function select_module(module_id) {
            $('#parent_module_id').val(module_id);

            $.ajax({
                method: 'GET',
                url: '/project_version_modules/fetch_module_details_lists/' + module_id,
                success: function(response){
                    let decoded_json = JSON.parse(response);
                    $('#module_details_div').html(decoded_json["html"]);
                    $('#module_div_title').text(decoded_json["title"] + " - Module Print Order");
                    $('#current_module_parent_id').val(decoded_json["parent_module_id"]);

                    update_modules_list();
                    load_draggable_lists();
                }
            });
        }

        function modules_back_button() {
            let parent_module_id = $('#current_module_parent_id').val();

            select_module(parent_module_id);
        }
    </script>
@endpush
