<style>
    .direct-chat-msg{margin:15px 0;}
    .direct-chat-text{ border:none; box-shadow:4px 4px 10px rgba(0,0,0,0.4);}
    .left .direct-chat-text{background-color:white;}
    .right .direct-chat-text{background-color:#00f2ff;}
    .dark-mode .left .direct-chat-text{background-color:gray !important;}
    .dark-mode .right .direct-chat-text{background-color:#0eacb5 !important;}
    .direct-chat-msg .direct-chat-text{margin:0;}
    .direct-chat-msg.left{padding-right:40px;}
    .direct-chat-msg.right{padding-left:40px;}
    #chat_message{
        height:55vh; 
        overflow:auto; 
        background: url('https://img.freepik.com/premium-vector/seamless-pattern-with-social-media-networking-global-internet-communication-chatting-instant-messaging-symbols-white-background-vector-illustration-line-art-style-wallpaper_198278-7649.jpg?w=360'); 
        background-repeat: repeat; background-size: auto; background-size: 100px; 
        background-repeat: repeat;  background-blend-mode: color; 
        background-color: #00ff2826;
        display:flex;
        flex-direction: column-reverse;
    }
    .dark-mode #chat_message{
        background-color: #00000094 !important;
    }
    .tr_slc_user:hover{background:#eee7; cursor:pointer;}
</style>
<div class="row">

    <div class="col-md-4 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mt-1">Chat User</h3>
                <div class="card-tools">
                    <button class="btn btn-success btn-sm" id="btn-add-close" type="button"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body p-0" style="height:63vh; margin-bottom:12px; overflow:auto" id="card_chat_user">
                <div id="table_user_chat">
                    <div class="p-2">
                        <input type="search" placeholder="Cari User" class="form-control" id="src_user_chat">
                    </div>
                    <table class="table">
                        <tbody id="tbl_user_chat_x">
                            <?php foreach($userChat as $v){ ?>
                                <tr class="tr_slc_user" data-user="<?=$v->user_id?>" data-nama="<?=$v->nama?>" data-search="<?=strtolower($v->nama)?>">
                                    <td width="20" align="center" valign="middle">
                                        <img width="30" style="border:thin solid #eee;" src="<?=empty($v->foto)?'':base_url($v->foto)?>" onerror="this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbNOpai32_rwcRrMxmpF4sNJG3CIR7yTPv7MD9qK4Ft5OdltMU6DymiRqxXRb0qtgGJoE&amp;usqp=CAU'" class="img-circle elevation-1">
                                    </td>
                                    <td class="align-middle" valign="middle"><?=$v->nama?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="table_user" style="display:none">
                    <div class="p-2">
                        <input type="search" placeholder="Cari User" class="form-control" id="src_user">
                    </div>
                    <table class="table">
                        <tbody id="tbl_user_x">
                            <?php foreach($user as $v){ ?>
                                <tr class="tr_slc_user" data-user="<?=$v->user_id?>" data-nama="<?=$v->nama?>" data-search="<?=strtolower($v->nama)?>">
                                    <td width="20" align="center" valign="middle">
                                        <img width="30" style="border:thin solid #eee;" src="<?=empty($v->foto)?'':base_url($v->foto)?>" onerror="this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbNOpai32_rwcRrMxmpF4sNJG3CIR7yTPv7MD9qK4Ft5OdltMU6DymiRqxXRb0qtgGJoE&amp;usqp=CAU'" class="img-circle elevation-1">
                                    </td>
                                    <td class="align-middle" valign="middle"><?=$v->nama?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="card" style="display:none" id="card_chat">
            <div class="card-header py-0">
                <table class="table table-borderless table-sm my-1">
                    <tr>
                        <td class="align-middle" width="5">
                            <img width="40" id="chat_img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbNOpai32_rwcRrMxmpF4sNJG3CIR7yTPv7MD9qK4Ft5OdltMU6DymiRqxXRb0qtgGJoE&amp;usqp=CAU" class="img-circle elevation-1">
                        </td>
                        <td class="align-middle">
                            <b id="chat_nama">Nama User</b>
                            <div id="chat_sts" class="small"><i class="fa fa-circle"></i> offline</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-body p-0 px-3" id="chat_message"> </div>
            <div class="card-footer">
                <form action="#" id="form_chat_msg">
                    <div class="input-group">
                        <input type="text" placeholder="Pesan" id="chat_msg" class="form-control" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    var slcUser='';
    var slcUserNama='';
    var slcUserPicture='';
    var lastChat = '';
    var showUserChat=true;
    var ajaxchat;
    $('#btn-add-close').on('click',function(e){
        e.preventDefault();
        if(showUserChat){
            $('#table_user_chat').hide('fast');
            $('#table_user').show('fast');
            $(this).addClass('btn-danger').removeClass('btn-success');
            $(this).html('<i class="fa fa-times"></i>');
        }else{
            $('#table_user_chat').show('fast');
            $('#table_user').hide('fast');
            $(this).removeClass('btn-danger').addClass('btn-success');
            $(this).html('<i class="fa fa-plus"></i>');
        }
        showUserChat=!showUserChat;
    });
    $('#src_user').on('input',function(){
        if(this.value){
            $('#tbl_user_x .tr_slc_user').hide();
            $('#tbl_user_x').find('[data-search*="'+this.value.toLowerCase()+'"]').show();
        }else{
            $('#tbl_user_x .tr_slc_user').show();
        }
    });
    $('#card_chat_user').on('click','.tr_slc_user',function(e){
        e.preventDefault();
        var el = $(this);
        slcUser = el.data('user');
        slcUserNama = el.data('nama');
        slcUserPicture = el.find('img').prop('src');
        
        $('#chat_img').prop('src',slcUserPicture);
        $('#chat_nama').html(slcUserNama);
        if(ajaxchat){ ajaxchat.abort(); }
        lastChat = '';
        $('#chat_message').html('');
        getChat();
        $('#card_chat').show();
    });
    function getChat(){
        ajaxchat = $.ajax({
            url: '<?=site_url('chat/get_chat')?>',type:'POST', data:{usr:slcUser,last:lastChat}, dataType:'JSON',
            complete:()=>refreshChat(),
            noerror: true,
            success:(r)=>{
                lastChat = r.last??'0';
                generateChat(r.chats??[]);
                if(r.usr_is_online){
                    $('#chat_sts').addClass('text-success').removeClass('text-secondary');
                    $('#chat_sts').html('<i class="fa fa-circle"></i> online');
                }else{
                    $('#chat_sts').html('<i class="fa fa-circle"></i> offline');
                    $('#chat_sts').removeClass('text-success').addClass('text-secondary');
                }
            }
        });
    }
    $('#form_chat_msg').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=site_url('chat/send_message')?>',type:'POST', data:{usr:slcUser,msg:$('#chat_msg').val()}, dataType:'JSON',
            beforeSend:()=>$('#form_chat_msg').parent().block({message:''}),
            complete:()=>setTimeout(() => { $('#form_chat_msg').parent().unblock(); }, 500),
            success:(r)=>{
                if(r.status==200){
                    setTimeout(() => {
                        $('#chat_msg').val('');
                    }, 500);
                }else{
                    toast('error',r.message);
                }
            }
        });
    });
    function refreshChat(timeout=3000){
        if(slcUser&&slcUser!=''){
            setTimeout(() => { getChat(); }, timeout);
        }
    }
    function generateChat(data){
        data.forEach((d,i)=>{
            $('#chat_message').prepend(`
                <div class="direct-chat-msg ${d.position}">
                    <div class="direct-chat-text" style="display:none;">
                        ${d.message}
                        <div class="text-right small">${d.time}</div>
                    </div>
                </div>
            `);
        });
        if(data){ $('.direct-chat-text').show(300); }
    }
</script>