/*updated:2020-01-29 20:20:34 user - v.0.73 - Author:Nikos Drosakis - License: GPL License*/

/*
@user Page Javascript-- Dashboard
developed by Nikos Drosakis
*/
 $('.wysiwyg').summernote();
 
$(document).ready(function() {
//set new post group

	/*********************************************
	 * TOP BUTTONS
	 ***********************************************/
	$("#newuserbtn").click(function(){location.href='/admin/user?sub=new';})
	$("#groups").click(function(){location.href='/admin/user?sub=groups';})

/*	$("#newgroupsbtn").click(function(){
		if(!$('#submitnewusergrp').html()) {
			$("<input class='form-control' placeholder='New usergroup name' id='newusergrp'><button class='btn btn-success' id='submitnewusergrp'>Create</button>").insertAfter(this)
		}else{
			$("#newusergrp").remove();
			$("#submitnewusergrp").remove();
		}
	})
	$(document).on('click',"#submitnewusergrp",function(){
		var grpname= $('#newusergrp').val().trim();
		if(grpname!=""){
			s.db.query('INSERT INTO usergrp (name) VALUES("'+grpname+'")',function(){
				location.reload();
			});
		}else{
			s.ui.notify('danger','Please insert a usergroup name.')
		}
	})
*/
	/*********************************************
	 * USER EDIT
	 ***********************************************/
if(my.uid!=""){
	var table= 'user';

	$(document).on('keyup', "#name,#url,#mail,#tel,#firstname,#lastname,#title,#seodescription", function () {
		if (this.id == 'name') {
			$('#name').text(this.value)
		}		
		var value=this.value.trim();
		s.db.query("UPDATE user SET "+this.id+"='"+value+"' WHERE id="+my.uid);
	})
	.on('change', "#status,#grp,#seopriority", function () {
		s.db.queryone(table, this, my.uid);
	})
	.on('click', "#submit_content", function () {
		//var media = {};
		//media.id = this.id.replace('submit_', '');
		//media.uid = my.uid;
		//media.value = "content='"+$('#' + media.id).summernote('code')+"'";
        //media.table = "user";
        //media.where= "id="+my.uid;
		//s.db.queryhtml(media, function(data){s.ui.notify('alert',media.id+' updated!');},"POST");
		var row =this.id.replace('submit_', '');
		var value=$('#' + row).summernote('code');
		 s.db.query("UPDATE user SET "+row+"='"+value+"' WHERE id="+my.uid, function(data){console.log(data);});
	})

		
	//uploader
	var mediagroup=3; //user
        s.media.uploader(my.mode, mediagroup, my.uid,function(data){console.log(data)});


}else if(my.sub=='new'){
	/*********************************************
	 * USER NEW
	 ***********************************************/
//greeklish (sql name is set as unique)
	$(document).on('keyup', "#name", function () {
		this.value=s.greeklish(this.value)
	})	
	$(document).on('click', "#submit_user", function () {
		var formid=$("#form_user");
		event.preventDefault();
		var form = formid.serializeArray();
		form[s.size(form)]={name:'registered',value: s.time()}
		form[s.size(form)]={name:'modified',value: s.time()}
		console.log(form)
		$.post(s.ajaxfile, form, function (data, textStatus, jqXHR) {
			console.log(data)
			if (data == 'no') {
				console.log(textStatus)
				console.log(jqXHR)
				s.ui.notify("danger","Form cannot be submitted or username exists.");
			} else {
				// console.log(data)
				location.href="/admin/user?uid="+data;
				// formid.reset();
			}
		},'json');
	})

}else if(my.sub=='groups'){
	/*********************************************
	 * USER groups
	 ***********************************************/
	$(document).on("click","input[id^='per']",function(){
		var e=s.explode('-',this.id),
		id= e[1],
		page= e[2];
		
	if(!$(this).is(':checked')){		
		s.db.query("UPDATE usergrp SET permissions= JSON_REMOVE(permissions, REPLACE(JSON_SEARCH(permissions, 'one', '"+page+"'),\'\"','')) WHERE id="+id,function(data){console.log(data)});
	}else{
		s.db.query("UPDATE usergrp SET permissions= JSON_ARRAY_APPEND(permissions, '$', '"+page+"') WHERE id="+id,function(data){console.log(data)});
	}
		
		/*
		value= this.checked ? 1 : 0;		
		s.db.func('fetchList1', "permissions,usergrp",function (p) {		
			
			if(p.length >0){
				p = s.json_decode(p);
			console.log(p)	
				if(value==1) {
					p.push(page);
				}else{
					var index = p.indexOf(page);
					if (index > -1) {
						p.splice(index, 1);
					}
				}				
				
				s.db.query("UPDATE usergrp SET permissions='"+s.json_encode(p)+"' WHERE id="+id);
			}else{
				pageq= '["'+page+'"]';
				s.db.query("UPDATE usergrp SET permissions='"+pageq+"' WHERE id="+id);
			}
		})
		*/
	})

}

	 //delete
	$(document).on('click', "button[id^='delete']", function () {
		var id=this.id.replace('delete','');
		s.confirm("This user will be deleted. Are you sure?",function(res){
		if(res){
  	    s.db.query("DELETE FROM user WHERE id="+id, function(data){console.log(data);
		if(data!='No'){
			$('#nodorder1_'+id).hide();
			}else{
			s.modal("problem deleting");
			}
			 })
			 }
		})	
		})	

})