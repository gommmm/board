<h4><?=$board_name?></h4>
<div class=".table">
	<table class="table article">
		<?php if(count($list) > 0) : ?>
		<!-- DB결과를 받아와서 뿌려주기 -->
		<?php foreach ($list as $row) : ?>
		<tr>
			<td>ㆍ</td>
			<td><a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>"><?=reply_str($row['b_reply']) ?><?=$row['title'] ?>&nbsp;<?=comment_count($row['c_cnt']) ?></a></td>
			<td class="nickname" id="article_<?= $row['m_id'] ?>"><a href="#"><?= $row['name'] ?></a></td>
			<td><?= today_check($row['b_regdate']) ?></td>
			<td><?= $row['b_cnt'] ?></td>
		</tr>
		<?php endforeach ?>
		<?php else : ?>
		<tr>
			<td colspan="5">등록된 게시물이 없습니다.</td>
		</tr>
		<?php endif; ?>
	</table>
</div>

<form name="nickSaveFrm" id="nickSaveFrm">
	<input type="hidden" id="nickname" name="nickname" value="">
</form>

<form id="multiForm" name="multiForm" method="post">
	<input type="hidden" name="id_list" id="id_list">
</form>
