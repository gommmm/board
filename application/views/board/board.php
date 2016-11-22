<h2><?=$board_name?></h2>
<div class="row">
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
				<?php if(!empty($notice_list)) : ?>
					<?php foreach ($notice_list as $row) : ?>
					<tr>
						<?php if($user_level == 9) : ?>
						<td>
							<a id="<?=$row['b_idx'] ?>" class="removeNotice" href="#">-</a>
						</td>
						<?php endif; ?>
						<td>공지</td>
						<td>
							<a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>">
							<?=$row['title'] ?>&nbsp;
							<?=comment_count($row['c_cnt']) ?>
							</a>
						</td>
						<td class="nickname" id="article_<?= $row['m_id'] ?>">
							<a href="#"><?= $row['name'] ?></a>
						</td>
						<td><?= today_check($row['b_regdate']) ?></td>
						<td><?= $row['b_cnt'] ?></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if(count($list) > 0) : ?>
				<!-- DB결과를 받아와서 뿌려주기 -->
				<?php foreach ($list as $row) : ?>
				<tr>
					<?php if($user_level == 9) : ?>
					<td>
						<input type="checkbox" name="chk" class="chk" value="<?=$row['b_idx'] ?>">
					</td>
					<?php endif; ?>
					<td><?= $row['b_reply'] == '' ? $row['b_idx'] : '' ?></td>
					<td>
						<a href="<?=MAIN_URL ?>/<?=$row['bc_code'] ?>/view/<?=$row['b_idx'] ?>">
							<?=reply_str($row['b_reply']) ?>
							<?php if($row['parent_deleted'] == 1) : ?>
							<span class=\"del_parent\">[원글이 삭제된 답글]</span>
							<?php endif; ?>
							<?=$row['title'] ?>
							<?=comment_count($row['c_cnt']) ?>
						</a>
						<?php if($row['file_count'] > 0) : ?>
						<i class="fa fa-file" aria-hidden="true"></i>
						<?php endif; ?>
					</td>
					<td class="nickname" id="article_<?= $row['m_id'] ?>">
						<a href="#"><?= $row['name'] ?></a>
					</td>
					<td><?= today_check($row['b_regdate']) ?></td>
					<td><?= $row['b_cnt'] ?></td>
				</tr>
				<?php endforeach ?>
				<?php else : ?>
				<tr>
					<td colspan="5">등록된 게시물이 없습니다.</td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<form name="nickSaveFrm" id="nickSaveFrm">
			<input type="hidden" id="nickname" name="nickname" value="">
		</form>

		<form id="multiForm" name="multiForm" method="post">
			<input type="hidden" name="id_list" id="id_list">
		</form>


		<div class="list-btn text-right">
			<?php if($user_level == 9) : ?>
			<div>
				<input type="checkbox" class="checkAll">
				<label>전체선택</label>
			</div>
			<a class="remove" href="#">삭제</a>
			<a class="move" href="#">이동</a>
			<?php endif; ?>
			<?php if ($user_level > 0 && $write_level <= $user_level) : ?>
			<a href="<?=MAIN_URL ?>/<?=$bc_code ?>/write">글쓰기</a>
			<?php endif; ?>
		</div>


		<?php if($paging_str != '') : ?>
			<ul class="pagination prev-next" role="navigation" aria-label="Pagination">
				<?=$paging_str ?>
			</ul>
		<?php endif; ?>
		<?php $this->load->view('board/search'); ?>
