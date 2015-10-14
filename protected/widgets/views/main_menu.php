<div class="wrapper main-menu">
	<div>
		<ul>
		<?php foreach($menu as $key => $item) : ?>
			<li><a href="#" class="<?= $key == 'link2' ? 'active' : ''; ?>"><?= $item; ?></a></li>
		<?php endforeach ?>
		</ul>
	</div>
</div>