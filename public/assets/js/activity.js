$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function user_search() {

	var search_content = $('#search').val();
	
	let target = $("#target").val();
    var list = '';
	if(search_content!=''){
        $.ajax ({
            type: 'GET',
            url: target+'activity/user_search',
            data: { search_content: search_content },
            success : function(res) {
                $.each(res.data,function(key,value){
                    list +='<a  class="client-list" href="#" onclick="show_conversation('+value.id+')"><div class="chat-user" id="'+value.d+'"><div class="chat-user-name">'+value.name+'</div></div></a>';
                });
                $('#users-list').html(list);
            }
        });
    } else {
    	all_user_show();
    }
}

function all_user_show() {

	let target = $("#target").val();
    var list = '';
	var search_content = '';
    $.ajax ({
        type: 'GET',
        url: target+'activity/user_search',
        data: { search_content: search_content },
        success : function(res) {
            
            $.each(res.data,function(key,value){
                list +='<a class="client-list" href="#" onclick="show_conversation('+value.id+')"><div class="chat-user" id="'+value.id+'"><div class="chat-user-name">'+value.name+'</div></div></a>';
            });
            $('#users-list').html(list);
        }
    });
}
//conversation show  start here
var id = 0;
var msg = '';
var start = 0;
var limit = 20;

function show_conversation(user_id) {
    $('#chat-discussion').scrollTop(0);
    start = 0;
    id = user_id;
    let target = $("#target").val();
    var list = '';
    name_select();

    $.ajax ({
        type: 'GET',
        url: target+'activity/show_conversation',
        data: { id: id, start: start, limit: limit},
        success : function(res) {

            if(res.data == ''){
                var no_data = '<div class="chat-message right"><div class="message"><span style="color:blue" class="message-author" ></span><span class="message-date"></span><span class="message-content">No data found</span></div></div>';

                $('#chat-discussion').html(no_data);
                

            } else {
                $.each(res.data,function(key,value){
                list +='<div class="chat-message right"><div class="message"><span style="color:blue" class="message-author" >'+value.clientname+'</span><span class="message-date">'+value.created_at+'</span><span class="message-content">'+value.message+'</span></div></div>';
                
                });
                
                if(res.data.length >= limit){
                    list += '<div class="chat-message right see_btn" id="btn"><div class="message see_msg_btn"><span style="color:blue" class="message-author" ></span><span class="message-date"></span><span class="message-content"><button class="btn btn-info" id="see_more_btn" onclick="call_again()">See more</button></span></div></div>';
                }

                $('#chat-discussion').html(list);
                
            }
            
            
            
        }
    });
}


function call_again() {
    
    $(".see_btn").hide();
    let target = $("#target").val();
    var list = '';
    start = start + limit;
    console.log(start);
    $.ajax ({
        type: 'GET',
        url: target+'activity/show_conversation',
        data: { id: id, start: start, limit: limit},
        success : function(res) {
            $.each(res.data,function(key,value){
                list +='<div class="chat-message right"><div class="message"><span style="color:blue" class="message-author" >'+value.clientname+'</span><span class="message-date">'+value.created_at+'</span><span class="message-content">'+value.message+'</span></div></div>';
                
            });

            if(res.data.length >= limit){
                list += '<div class="chat-message right see_btn" id="btn"><div class="message see_msg_btn"><span style="color:blue" class="message-author" ></span><span class="message-date"></span><span class="message-content"><button class="btn btn-info" id="see_more_btn" onclick="call_again()">See more</button></span></div></div>';
            }

            $('#chat-discussion').append(list); 
            
        }
    });
}

//conversation end here


function name_select() {

    let target = $("#target").val();
    $('.chat-user').css('background-color', '');
    $('#'+id).css('background-color', '#99ffcc');

    if(id !=0){
        $.ajax ({
            type: 'GET',
            url: target+'activity/user_name',
            data: { id: id },
            success : function(res) {
                
                $('#heading').html('Activity log of '+res.data);
            }
        });
    }
}

