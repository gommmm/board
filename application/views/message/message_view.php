<div class="msg_btn">
<?php if($mode == 'recv') : ?>
<a class="btn btn-default del" href="<?=MAIN_URL ?>/message/delete/<?=$message['no']?>?mode=recv">삭제</a>
<a class="btn btn-default" href="<?=MAIN_URL ?>/message/send?receiverId=<?=$message['senderId']?>">답장</a>
<a class="btn btn-default" href="<?=MAIN_URL ?>/message?mode=recv">목록</a>
</div>
<div class="msg_header">
	<div>
		<span>보낸사람</span>
		<span><?=$message['senderNick']?>(<?=$message['senderId']?>)</span>
	</div>
	<div>
		<span>받은시간</span>
		<span><?=$message['read_time']?></span>
	</div>
</div>
	</div>
<?php else : ?>
<a class="btn btn-default del" href="<?=MAIN_URL ?>/message/delete/<?=$message['no']?>?mode=send">삭제</a>
<a class="btn btn-default" href="<?=MAIN_URL ?>/message?mode=send">목록</a>
</div>
<div class="msg_header">
	<div>
		<span>받는사람</span>
		<span><?=$message['receiverNick']?>(<?=$message['receiverId']?>)</span>
	</div>
	<div>
		<span>보낸시간</span>
		<span><?=$message['write_time']?></span>
	</div>
</div>
<?php endif; ?>
</div>
<div class="msg_content">
	<p><?=$message['content']?></p>
</div>
<script>
	$(".del").bind("click", function() {
		var input = confirm('쪽지를 삭제하시겠습니까?');

		if (input == true) {
			return true;
		} else {
			return false;
		}
	})
</script>