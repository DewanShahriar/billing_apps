//CSRF token setup for ajax call
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var count;
//message character count
$(document).ready(function(){

    part1Count = 160;
    part2Count = 145;
    part3Count = 152;

    $('#message').keyup(function(){
        var chars = $(this).val().length;
            messages = 0;
            remaining = 0;
            total = 0;
            var ch = $(this).val();
            var rforeign = /[^\u0000-\u007f]/;
            
        if (rforeign.test(ch)) {
            console.log(ch);
            // alert("This is English");
            part1Count = 70;
            part2Count = 64;
            part3Count = 67;
        } else if(ch == ''){
            part1Count = 160;
            part2Count = 145;
            part3Count = 152;

        } else {
            console.log(ch);
            // alert("This is bangla");
            
        }
        if (chars <= part1Count) {
            messages = 1;
            remaining = part1Count - chars;
        } else if (chars <= (part1Count + part2Count)) { 
            messages = 2;
            remaining = part1Count + part2Count - chars;
        } else if (chars > (part1Count + part2Count)) { 
            moreM = Math.ceil((chars - part1Count - part2Count) / part3Count) ;
            remaining = part1Count + part2Count + (moreM * part3Count) - chars;
            messages = 2 + moreM;
        }
        count = messages;
        $('#remaining').text(remaining);
        $('#messages').text(messages);
        $('#total').text(chars);
        if (remaining > 1) $('.cplural').show();
            else $('.cplural').hide();
        if (messages > 1) $('.mplural').show();
            else $('.mplural').hide();
        if (chars > 1) $('.tplural').show();
            else $('.tplural').hide();
    });
    $('#message').keyup();
});



//call from send button 
function quicksms_send(){
	
	let target = $("#target").val();
    var value = $('#client').val();
    var message = $('#message').val();

    if(value !='' && message !=''){
    	swal({
        title: "SMS send to "+value.length+" people",
        text: "Message size "+count,
        type: "info",
        showConfirmButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes",
        closeOnConfirm: false,
        showCancelButton: true,
        cancelButtonText: "No"
        }, 
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                type: "POST",
                url: target + "communication/quicksms_insert",
                dataType: "JSON",
                data: { 
                    value : value,
                    message : message
                },
                success: function(response){
                    swal({
                        title: "Response",
                        text: response.msg,
                        type: response.status,
                        showCancelButton: false,
                        showConfirmButton: true,
                        closeOnConfirm: true,
                        allowEscapeKey: false
                        })
                    }

                });
            }
        })
    } else{

    }
}