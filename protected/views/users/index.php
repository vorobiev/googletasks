<div style="padding: 50px;">
<table>
	<tr>
		<td>Имя</td>
		<td>Google ID</td>
	</tr>	
<?php foreach($users as $user ): ?>
	<tr>
		<td><a href="/tasks/users/show?id=<?php print $user -> id ?>"><?php print $user -> name; ?></a></td>
		<td><?php print $user -> google_id; ?></td>
	</tr>
<?php endforeach; ?>
</table>
</div>