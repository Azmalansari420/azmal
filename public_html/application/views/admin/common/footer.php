<script type="text/javascript">
_last_alerts_count = 0;
function get_notifications(){
	jQuery(function(){
		jQuery.ajax({
			url: '<?php echo admin_url('dashboard/alerts_count')?>',
			dataType: 'json',
			success: function(a){
				
				jQuery('#notifications_note').text(a.alerts_count);
				
				if(a.alerts_count == 0){
					jQuery('#notifications_note').hide();
					jQuery('#notification_icon').removeClass('wd-shake-horizontal').text('notifications_none');
				}
				if(a.alerts_count > 0 && a.alerts_count > _last_alerts_count){
					jQuery('#notifications_note').show();
					jQuery('#notification_icon').addClass('wd-shake-horizontal').text('notifications_active');
					window.setTimeout(function(){
						jQuery('#notification_icon').removeClass('wd-shake-horizontal');
					}, 1500);
				}
				_last_alerts_count = a.alerts_count;
			}
		});
	});
}
jQuery(function(){
	window.setInterval(function(){

		get_notifications();
	}, 10000);

	jQuery('.navbar-notifications').on('shown.bs.dropdown', function () {

		jQuery('.dash-notifications').append('<div style="padding:20px"><div class="cssload-container"><div class="cssload-whirlpool"></div></div></div>');

		jQuery.ajax({
			url: '<?php echo admin_url('dashboard/alerts')?>',
			dataType: 'json',
			success: function(a){

				jQuery('.dash-notifications').html(a.html);
			}
		});
	});
});

jQuery(function(){
	// jQuery('.main-nav .parent').on('click', function(){
	// 	if(jQuery(this).hasClass('current')){
	// 		jQuery('.main-nav .parent').removeClass('current');
	// 		jQuery(this).removeClass('current');
	// 	}else{
	// 		jQuery('.main-nav .parent').removeClass('current');
	// 		jQuery(this).addClass('current');
	// 	}	
	// });
	
	jQuery('.nav-logo, .main-nav-link').on('click', function(e){	
		if(jQuery(this).hasClass('active')){
			show_nav();			
		}else{
			hide_nav();
		}
		e.preventDefault();
	});

	jQuery(document).on('mouseover', '.main-nav .parent', function(){
		jQuery(this).addClass('current');
	}).on('mouseout', '.main-nav .parent', function(){
		jQuery(this).removeClass('current');
	});
});

function hide_nav(){
	jQuery('.nav-logo').addClass('active');
	jQuery('.main-nav-link').addClass('active').find('.ico').text('close');
	jQuery('.navigation-container').addClass('hide');
	
	window.setTimeout(function(){
		jQuery('.navigation-container').addClass('hidden');
		jQuery('.main-nav').addClass('hide');
		jQuery('.nav-logo').addClass('hide');
	}, 300);

	jQuery('.main-content').addClass('wide');

	jQuery('.navbar-brand').addClass('hide');
	//setCookie("nav_hidden", 1);
	jQuery.cookie('nav_hidden', 1, { path: '/' });
}

function show_nav(){
	jQuery('.nav-logo').removeClass('active');
	jQuery('.main-nav-link').removeClass('active').find('.ico').text('format_indent_decrease');
	jQuery('.navigation-container').removeClass('hide hidden');

	window.setTimeout(function(){
		jQuery('.main-nav').removeClass('hide');
		jQuery('.nav-logo').removeClass('hide');
	}, 300);

	jQuery('.main-content').removeClass('wide');

	jQuery('.navbar-brand').removeClass('hide');
	//setCookie("nav_hidden", 0);
	jQuery.cookie('nav_hidden', 0, { path: '/' });
}

</script>

				</div>
            </div>
        </div>
    </div>
</body>

</html>