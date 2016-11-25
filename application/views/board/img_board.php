<h2 class="b_title"><?=$board_name?></h2>

<?php if(!empty($notice_list)) : ?>
	<table class="article">
		<thead>
			<tr>
				<?php if($user_level == 9) : ?>
				<th></th>
				<?php endif; ?>
				<th>번호</th>
				<th>제목</th>
				<th>작성자</th>
				<th>작성일</th>
				<th>조회수</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($notice_list as $row) : ?>
			<tr>
				<?php if($user_level == 9) : ?>
				<td><a id="<?=$row['b_idx']?>" class="removeNotice" href="#">-</a></td>
				<?php endif; ?>
				<td>공지</td>
				<td><a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>"><?=$row['title'] ?>&nbsp;<?=comment_count($row['c_cnt']) ?></a></td>
				<td class="nickname" id="article_<?= $row['m_id'] ?>"><a href="#"><?= $row['name'] ?></a></td>
				<td><?= today_check($row['b_regdate']) ?></td>
				<td><?= $row['b_cnt'] ?></td>
			</tr>
		<?php endforeach; ?>
		<tbody>
	</table>
	<?php endif; ?>

<?php if(count($list) > 0) : ?>
<div class="article row small-up-2 medium-up-4 large-up-4">
	<?php foreach ($list as $row) : ?>
	<div class="column">
			<a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>">
				<?php $src = getResizeSrc($row['content']); ?>
				<?php if($src != '') : ?>
				<img src="<?=$src?>" class="thumbnail" style="height:100px">
				<?php else : ?>
				<div style="height:100px;position:relative;text-align:center;border:solid 1px #d9d9d9;">
					<div style="position:absolute;left:0;right:0;top:50%;margin-top:-10px;color:#d9d9d9;">noimage</div>
				</div>
				<?php endif; ?>
			</a>
			<dl>
				<dt>
				<a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>"><?=$row['title'] ?> <?=comment_count($row['c_cnt']) ?></a>
				</dt>
				<dd>조회 <?= $row['b_cnt'] ?> | <?= today_check($row['b_regdate']) ?></dd>
				<dd class="nickname" id="article_<?= $row['m_id'] ?>"><a href="#"><?= $row['name'] ?></a></dd>
			</dl>
	</div>
	<?php endforeach ?>
</div>
<?php else : ?>
<div>등록된 게시물이 없습니다.</div>
<?php endif; ?>

<form name="nickSaveFrm" id="nickSaveFrm">
	<input type="hidden" id="nickname" name="nickname" value="">
</form>

<form id="multiForm" name="multiForm" method="post">
	<input type="hidden" name="id_list" id="id_list">
</form>

<?php if ($user_level > 0 && $write_level <= $user_level) : ?>
<div class="list-btn"><a href="<?=MAIN_URL ?>/<?=$bc_code ?>/write">글쓰기</a></div>
<?php endif; ?>

<?php if($paging_str != '') : ?>
	<ul class="pagination prev-next" role="navigation" aria-label="Pagination">
		<?=$paging_str ?>
	</ul>
<?php endif; ?>
<?php $this->load->view('board/search'); ?>
