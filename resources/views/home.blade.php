@extends('layouts.app')

@section('content')
    <div class="row chat-row">
        <div class="col-md-3">
            <div class="users">
                <h5>Users</h5>

                <ul class="list-group list-chat-item">
                    @if($users->count())
                        @foreach($users as $user)
                            <li class="chat-user-list">
                                <a href="{{ route('message.conversation', $user->id) }}">
                                    <div class="chat-image">
                                            {!! makeImageFromName($user->name) !!}
                                        <i class="fa fa-circle user-status-icon user-icon-{{ $user->id }}" title="away"></i>
                                    </div>

                                    <div class="chat-name font-weight-bold">
                                        {{ $user->name }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="groups mt-5">
                <h5>Groups <i class="fa fa-plus btn-add-group ml-3"></i></h5>

                <ul class="list-group list-chat-item">
                    @if($groups->count())
                        @foreach($groups as $group)
                            <li class="chat-user-list">
                                <a href="{{ route('message-groups.show', $group->id) }}">
                                {{ $group->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <h1>
                Message Section
            </h1>

            <p class="lead">
                Select user from the list to begin conversation.
            </p>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="addGroupModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('message-groups.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Group Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>

                        <div class="form-group">
                            <label for="">Select Member</label>
                            <select id="selectMember" class="form-control" name="user_id[]" id="" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    @include('scripts.push')
    <script>
        let $addGroupModal = $("#addGroupModal");
            $(document).on("click", ".btn-add-group", function (){
                $addGroupModal.modal("toggle");
            });

            $("#selectMember").select2();
    </script>
@endpush

