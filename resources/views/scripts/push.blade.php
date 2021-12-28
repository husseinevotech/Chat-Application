<script>
    $(function(){
       let user_id = "{{ auth()->user()->id }}";

       let ip_address = "{{ env('APP_URL') }}";
       let socket_port = '8009';

       let path = `${ip_address}:${socket_port}`;
       let socket = io(path);

       socket.on('connect', () => {
           socket.emit('user_connected', user_id);
       });

       socket.on('updateUserStatus', (data) => {
           let $userStatusIcon = $('.user-status-icon');
           $userStatusIcon.removeClass('text-success');
           $userStatusIcon.attr('title', 'Away');

           $.each(data, function (key, val) {
               if (val !== null && val !== 0) {
                   let $userIcon = $(".user-icon-"+key);
                   $userIcon.addClass('text-success');
                   $userIcon.attr('title','Online');
               }
           });
       });

    });
</script>
