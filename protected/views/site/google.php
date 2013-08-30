<h1>Добро пожаловать, <?php print $info['name'] ?></h1>
<p>Ваш Google ID: <?php print $info['id']; ?></p>
<h3>Список задач</h3>
<ul>
	<?php foreach($tasklists as $tasklist): ?>
		<li><?php print $tasklist['name']; ?>
			<ul>
				<?php foreach($tasklist['tasks'] as $task): ?>
					<li><?php print $task; ?></li>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
</ul>