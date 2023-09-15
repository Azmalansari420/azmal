<ul class="thumbnails">
	<li class="span6">
    	<div class="thumbnail">
        	<h5 class="alert alert-info">Media Thumb Settings</h5>
            <?php echo $media_grid?>
  		</div>
    </li>
  	<li class="span6">
    	<div class="thumbnail">
        	<h5 class="alert alert-info">Media Groups</h5>
            <a href="<?php echo admin_url('media/media_group')?>" class="btn btn-primary btn-mini">Create New Group</a>
            <table class="table table-striped table-bordered">
            <tr>
            	<th>Id</th>
                <th>Group Name</th>
                <th>Details</th>
                <th></th>
            </tr>
			<?php foreach($config_groups as $config_group){?>
            	<tr>
                	<td><?php echo $config_group->id?></td>
                    <td><?php echo clean_text($config_group->config_group_code)?></td>
                    <td><?php echo clean_text($config_group->description)?></td>
                    <td><a href="<?php echo admin_url()?>media/media_group/<?php echo $config_group->id?>" class="btn btn-info btn-mini">Edit</a></td>
                </tr>
            <?php }?>
            </table>
  		</div>
    </li>
</ul>