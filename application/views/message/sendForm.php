<div class="send_header">
	<strong>받는사람</strong>
	<span class="receiver"><?=$receiverNick?>(<?=$receiverId ?>)</span>
</div>
<textarea class="s_content" name="content"></textarea>
<div class="send_btn">
	<button type="button" class="button submit">보내기</button>
	<button type="button" class="button cancel">취소</button>
</div>

<script src="<?=NODE?>/socket.io/socket.io.js"></script>

<script>
var id = "<?=$senderId ?>";
var nickname = "<?=$senderNick?>";
var receiverInfo = {
			"receiverId" : <?=divideList(',', $receiverId)?>,
			"receiverNick" : <?=divideList(',', $receiverNick)?>
		}
var socket = io('<?=NODE?>');

$(document).on("click", ".submit", function() {
	var param = {};
	var content = $(".s_content").val();
	var url = "<?=MAIN_URL?>/message/send";

	if(content == "") {
		alert("쪽지 내용을 입력해주세요.");
		return false;
	}

	param['senderId'] = id;
	param['senderNick'] = nickname;
	param['receiverId'] = receiverInfo['receiverId'];
	param['receiverNick'] = receiverInfo['receiverNick'];
	param['content'] = content;
	$.ajax({
        type:"POST",
        url:url,
        data:{"param" : param},
				dataType:"json",
        success:function(receiverId){
						if(receiverId != '') {
							socket.emit('send success', receiverId);
	            alert("쪽지를 보냈습니다.");
	            window.close();
						}
        }
    });
});

$(document).on("click", ".cancel", function() {
	window.close();
});
</script>
