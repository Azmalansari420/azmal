

	<?php if(count($alerts) > 0){?>
		<ul>
			<?php foreach($alerts as $alert){?>

				<?php
				if($alert->module == 'order'){

					$url = admin_url("orders/view/{$alert->ref_id}") . "?ref=" . $alert->id;
				}
				?>

				<li>
					<a class="ico-link" href="<?php echo $url?>">
						<div class="icon">
							<i class="ico">monetization_on</i>
							<!-- <span class="badge"></span> -->
						</div>
						<div>
							<strong>New order placed - <?php echo time_elapsed_string($alert->added_on)?></strong>
							<span class="time"><?php echo date("D j M h:i A", strtotime($alert->added_on))?></span>
						</div>
					</a>
				</li>
			<?php }?>
		</ul>
	<?php }?>