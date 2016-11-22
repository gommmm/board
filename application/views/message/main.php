<h3 class="message_title">쪽지함</h3>
<ul class="list_menu">
	<li class="<?=$mode == 'recv' ? 'on' : ''?>"><a href="<?=MAIN_URL ?>/message?mode=recv">받은쪽지함</a></li>
	<li class="<?=$mode == 'send' ? 'on' : ''?>"><a href="<?=MAIN_URL ?>/message?mode=send">보낸쪽지함</a></li>
</ul>
<table class="table msg">
	<?php if($mode == NULL || $mode == 'recv') : ?>
	<tr>
		<th>보낸사람</th>
		<th>내용</th>
		<th>날짜</th>
	</tr>
	<?php foreach($message as $row) : ?>
	<tr>
		<td><?=$row['senderNick'] ?>(<?=$row['senderId']?>)</td>
		<td><a href="<?=MAIN_URL?>/message/view/<?=$row['no'] ?>?mode=recv"><?=cut_str($row['content'])?></a></td>
		<td><?=$row['write_time'] ?></td>
	</tr>
	<?php endforeach; ?>
	<?php else : ?>
	<tr>
		<th>받는사람</th>
		<th>내용</th>
		<th>보낸날짜</th>
		<th>받은날짜</th>
	</tr>
	<?php foreach($message as $row) : ?>
	<tr>
		<td><?=$row['receiverNick'] ?>(<?=$row['receiverId']?>)</td>
		<td><a href="<?=MAIN_URL?>/message/view/<?=$row['no'] ?>?mode=<?=$mode?>"><?=cut_str($row['content'])?></a></td>
		<td><?=$row['write_time'] ?></td>
		<td><?=$row['read_message'] == 1 ? $row['read_time'] : '읽지않음' ?></td>
	</tr>
	<?php endforeach; ?>
	<?php endif; ?>
</table>

<div class="row text-center">
	<ul class="pagination prev-next" role="navigation" aria-label="Pagination">
	<?=$pagination ?>
	</ul>
</div>
