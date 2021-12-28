@extends('layouts.app')

@section('content')
    <div class="row chat-row">
        <div class="col-md-3">
            <div class="users">
                <h5>Users</h5>

                <ul class="list-group list-chat-item">
                    @if($users->count())
                        @foreach($users as $user)
                            <li class="chat-user-list
                                @if($user->id == $friendInfo->id) active @endif">
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
        </div>

        <div class="col-md-9 chat-section">
            <div class="chat-header">
                <div class="chat-image">
                    {!! makeImageFromName($friendInfo->name) !!}
                </div>

                <div class="chat-name font-weight-bold">
                    {{ $friendInfo->name }}
                    <i class="fa fa-circle user-status-head" title="away"
                     id="userStatusHead{{ $friendInfo->id }}"></i>
                </div>
            </div>

            <div class="chat-body" id="chatBody">
                <div class="message-listing" id="messageWrapper">
                    <div class="row message align-item-center mb-2">
                        <div class="col-md-12 user-info">
                            <div class="chat-image">
                                {!! makeImageFromName("User Name") !!}
                            </div>

                            <div class="chat-name font-weight-bold">
                                {{ $friendInfo->name }}
                                <span class="small time text-gray-500" title="2020-05-06 10:30 PM">
                                    10:30 pm
                                </span>
                            </div>
                        </div>

                        <div class="col-md-12 message-content">
                            <div class="message-text">
                                Message here
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chat-box">
                <div class="chat-input bg-white" id="chatInput" contenteditable="">

                </div>

                <div class="chat-input-toolbar">
                    <button title="Add File" class="btn btn-light btn-sm btn-file-upload">
                        <i class="fa fa-paperclip"></i>
                    </button> |

                    <button title="Bold" class="btn btn-light btn-sm tool-items"
                            onclick="document.execCommand('bold', false, '');">
                        <i class="fa fa-bold tool-icon"></i>
                    </button>

                    <button title="Italic" class="btn btn-light btn-sm tool-items"
                            onclick="document.execCommand('italic', false, '');">
                        <i class="fa fa-italic tool-icon"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection



@push('scripts')
    <script>
        let $chatInput = $(".chat-input");
        let $chatInputToolbar = $(".chat-input-toolbar");
        let $chatBody = $(".chat-body");
        let $messageWrapper = $("#messageWrapper");
        let friendID = "{{ $friendInfo->id }}";

        $chatInput.keypress((e) => {
            let message= $chatInput.html();
            if(e.which === 13 && !e.shiftkey){
                $chatInput.html("");
                sendMessage(message);
                return false;
            }
        });

        function sendMessage(message){
            let url = "{{ route('message.send-message') }}"
            let form = $(this);
            let formData = new FormData();
            let token = "{{ csrf_token() }}";

            formData.append('message', message);
            formData.append('_token', token);
            formData.append('receiver_id', friendID);
            console.log(formData);
            $.ajax({
                url : url,
                type : "POST",
                data : formData,
                processData: false,
                contentType: false,
                success : function(response){
                    if(response.success){

                    }
                }
            });
        }
    </script>

    @include('scripts.push')
@endpush
