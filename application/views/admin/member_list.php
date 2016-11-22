<thead>
	<tr>
		<th></th>
		<th>아이디</th>
		<th>이름</th>
		<th>레벨</th>
	</tr>
</thead>
<tbody>
	<?php if($total_count > 0) : ?>
	        <?php for($i=0; $i<count($list); $i++) : ?>
				<tr>
					<td>
					<input type="checkbox" class="cb">
					</td>
					<td><?=$list[$i]['m_id'] ?></td>
					<td><?=$list[$i]['m_name'] ?></td>
					<td><?php echo $list[$i]['m_level'] ==9 ? '관리자' : $list[$i]['m_level']; ?></td>
				</tr>
			<?php endfor; ?>
	<?php else : ?>
		<tr>
			<td colspan="4">회원이 없습니다.</td>
		</tr>
	<?php endif; ?>
</tbody>
