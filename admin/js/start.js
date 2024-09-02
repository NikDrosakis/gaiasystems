// Start the WebSocket connection

const socapi = soc(G.aconf.config.ws_port, 'indicator');

function soc_success() {
    console.log('WebSocket connection successfully opened');
    document.getElementById('indicator').className = 'green';
}
socapi.start(soc_success());



//admin tabs
var at= coo(s.get.mode+'_tab');
if(at!=false){
    $('#t'+at).attr('class','gs-titleActive');
    $('#'+at).css('display','block');
}
//prevent contenteditable creative divs
$('code[contenteditable]').keydown(function(e) {
    // trap the return key being pressed
    if (e.keyCode === 13) {
        // insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
        document.execCommand('insertHTML', false, '');
        // prevent the default behaviour of return key pressed
        return false;
    }
});

//set notification bar to sidebar 
//bufAsync('sidebar','widgets/notification/public');
// Instantiate the ActivityManager
/*
	const activityManager = new ActivityManager();            
	$.get('/admin/xhr.php', {a:'errors'},function(res) {
		// Assuming errors is an array of strings
		//console.log(res)	
		for(var i in res){
		activityManager.addActivity(res[i]);
		}                
	},'json');
*/
//set globals_menu
//const bufferize = new AsyncBufferWorker();
//bufferize.asyncBufferWorker('#globals_menu', '/var/www/admin/components/global')
//POST

if(G.mode=='post'){
    if(G.id!='') {
        $('.wysiwyg').summernote();
        //post uploader
        var mediagrp=1; //id of mediagrp table post
        console.log(G.mode, mediagrp, G.id)
        s.media.uploader(G.mode, mediagrp, G.id,function(data){
            console.log(data)
        });
    }else if(G.sub=='') {
        postlist();
    }else if(G.sub=='new') {

    }else if(G.sub=='groups') {
        s.ui.table.get({
            adata: 'postgrp',
            fetch: ["fa", "SELECT * FROM postgrp"],
            order: ["id", "ASC"],
            list: {
                0: {row: 'id'},
                1: {row: 'name'}
            }
        });
    }
}