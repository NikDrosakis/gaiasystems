<div class="table-container">
<table class="styled-table">
    <thead>
	<tr class="">
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_sort">sort</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_id">id</button></th>
		<th>img</th><th><button onclick="s.table.orderby(this);" class="orderby" id="order_name">name</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_status">status</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_uri">uri</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_title">title</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_subtitle">taxonomy</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_postgrpid">postgrpid</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_published">published</button></th>
		<th><button onclick="s.table.orderby(this);" class="orderby" id="order_delete">delete</button></th>
	</tr></thead>

	<tbody id="list1" class="group1">
	<?php 
	$langprefix=$G['langprefix'];
	for($i=0;$i<count($sel);$i++){
		$postid=$sel[$i]['id'];
		?>
		<tr id="nodorder1_<?=$postid?>" style="cursor:move;">
			<td><span id="menusrt<?=$postid?>"><?=$sel[$i]['sort']?></span></td>
			<td id="id<?=$postid?>"><span id="id<?=$postid?>"><?=$postid?></span></td>
			<td><img id="img<?=$postid?>" src="<?=$sel[$i]['img']=='' ? '/admin/img/myface.jpg': UPLOADS.$sel[$i]['img']?>" width="30" height="30"></td>
			<td><a href="/admin/user?uid=<?=$sel[$i]['uid']?>"><?=$sel[$i]['username']?></a></td>
			<td id="status<?=$postid?>"><span id="status<?=$postid?>"><?=$sel[$i]['status']?></span></td>
			<td><a href="/<?=$sel[$i]['uri']?>"><?=$sel[$i]['uri']?></a></td>
			<td><a href="/admin/post?id=<?=$sel[$i]['id']?>"><?=$sel[$i]['title'.$langprefix]?></a></td>
			<td><?=$sel[$i]['taxname']?></td>
			<td id="postgrpid<?=$postid?>"><span id="postgrpid3"><?=$postid?></span></td>
			<td id="published<?=$postid?>"><?=date('Y-m-d H:i',$sel[$i]['published'])?></td>
			<td><button id="delete<?=$postid?>" value="<?=$postid?>" name="DELETE FROM post WHERE id=@id" title="delete" class="btn btn-default btn-xs" onclick="s.ui.table.execute(this.id,this.name,this.value,this.title,'nodorder1_')">Delete</button></td>
		</tr>
	<?php } ?>

	</tbody></table></div>