/*updated:2020-01-29 20:20:34 media - v.0.73 - Author:Nikos Drosakis - License: GPL License*/

$(document).ready(function() {
//post uploader 
        var mediagrp=0;
		//G.mode
		s.media.uploader("media", 1, 0,function(data){
            console.log(data)
        });
		
    if (s.get.sub == 'groups') {

        s.f = {
            adata: 'mediagrp',
            fetch: ["fa", "SELECT * FROM mediagrp"],
            order: ["id", "ASC"],
            list: {
                0: {row: 'id'},
                1: {row: 'name'}
            }
        };
        s.ui.table.get(s.f);
    }
})
	 
	 s.ui.viewer.img();
/*************************************************
					EVENTS
****************************************************/	
	//set new post group
    $(document).on("click", "#newobjgrpbtn", function () {
            if ($('#newobjgrp').html() == "") {
                s.ui.form.get({
                    adata: "mediagrp",
                    nature: "new",
                    append: "#newobjgrp",
                    list: {
                        0: {row: 'name', placeholder: "Media Group Name", params: "required"}
                    }
                }, function () {
                    location.reload();
                })
            } else {
                $('#newobjgrp').html('');
            }
        })
	.on("click", "button[id^='del']", function () {		
		var id = this.id.replace('del','');
		var file = $(this).attr('file');
		s.file.unlink (s.UPLOADS_ROOTPATH+file);
		s.file.unlink (G.UPLOADS_ROOTPATH+'thumbs/icon_'+file);
		s.db.query ("DELETE FROM media where id="+id);
		$( "#photo"+id ).hide()        
    })
	.on("keyup", "input[id^='title_'], textarea[id^='summary_']", function () {		
		var spl= this.id.split('_');		
		var val=this.value.trim();
		if(val!=''){
			var query='UPDATE obj SET '+spl[0]+'="'+val+'" WHERE id='+spl[1];
		//	console.log(query)
			s.db.query(query);
		}
	})

