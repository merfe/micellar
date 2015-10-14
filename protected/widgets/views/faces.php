<div class="wrapper widget-faces">
	<div>
		<div class="list">
			<div class="item">
				<img src="/images/temp/face-1.jpg" />
				<img class="over" src="/images/temp/face-1-over.jpg" />
			</div>
			<div class="item">
				<img src="/images/temp/face-1.jpg" />
			</div>
			<div class="item">
				<img src="/images/temp/face-1.jpg" />
			</div>
		</div>
	</div>
</div>


<script>
	$(function()
	{
		$('.widget-faces .item .over').eraser({
		    completeRatio: .5,
		    completeFunction: function() {
		    	alert('50% стерто!');
		    }
		});
	});
</script>