<div style="padding: 50px;">
<p>Имя: <?php print $user -> name ?></p>
<p>Google ID: <?php print $user -> google_id; ?></p>
<h3>Список задач</h3>
<ul>
	<?php if ( $tasklists ): ?>
	<?php foreach($tasklists as $tasklist): ?>
		<li><?php print $tasklist['name']; ?>
			<ul>
				<?php if ( $tasklist['tasks'] ): ?>
				<?php foreach($tasklist['tasks'] as $task): ?>
					<li><?php print $task; ?></li>
				<?php endforeach; ?>
				<?php endif; ?>
				<li>
					<form action="/tasks/users/addtask" method="post">
						<input type="hidden" name="user_id" value="<?php print $user -> id ?>" />
						<input type="hidden" name="tasklist_id" value="<?php print $tasklist["id"]; ?>" />
						<input type="text" name="task" />
						<input type="submit" value="Добавить задачу" />
					</form>
				</li>
			</ul>
		</li>
	<?php endforeach; ?>
	<?php endif; ?>
	<li>
		<form action="/tasks/users/addtasklist" method="post">
			<input type="hidden" name="user_id" value="<?php print $user -> id ?>" />
			<input type="text" name="tasklist" />
			<input type="submit" value="Добавить список задач" />
		</form>
	</li>
</ul>
</div>