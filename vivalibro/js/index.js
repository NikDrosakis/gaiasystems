var bookdefaultimg= "/img/empty.png";
var book_status={"0" : "lost","1":"not owned","2" :"desired","3" : "shelve"};
var isread={0:"not read",1 :"reading",2 :"read"};
//s.ajaxfile='/bks/ajax.php';
function updateCardSize() {
    const screenWidth = window.innerWidth;
    let X = screenWidth / 1200; // Assuming 1200px as the base width for X=1
    document.documentElement.style.setProperty('--X', X);
}
async function loadN(N){
    for (var i in N){
            $('.'+i).html('<em class="c_circular cred">'+N[i]+'</em>');
    }
}

function booklist(q) {
    var pagenum=!coo('pagenum') ? 1: coo('pagenum');    
	var params= {a:'booklist',page:G.page,pagenum:pagenum,q:q,mode:G.mode,name:G.name};
    console.log(params)
	//url,div,data,callback,method,datatype
 s.db.get(params,function(res){
	 console.log(res)
	 if(G.page!='books' && res.titles.length>0){
	 console.log(Object.values(res.titles))
	 //top5 of all titles 
	 var top5=get_top_words(Object.values(res.titles));
	 console.log(top5)
	 var query=!!$('#search_book').val() ? $('#search_book').val(): top5.join(' ');
	search_googlebookapi(query);
	 }
	 //results to main page 
			$('#'+G.page).html(res.html);
             $('#count_'+G.page).text(res.count+ " "+G.page+"s");           
            s.ui.pagination.get(pagenum, res.count, 12,'book');
 });
 
}
/*
* EVENTS
* */
$(document)
    .on("click",".submit-comment,.submit-reply",function(){
        var commentid=$(this).attr('reply_id');
        var textareaid=this.id.replace('sent_','');
        console.log(textareaid)
        var content= $('#'+textareaid).val();
        var params={a:"comment",content:content,reply_id:commentid,type:'book',typeid:G.id,uid:G.my.id,created:time()};

        console.log(params);
        s.db.post(params,function(res){
            if(res!='ΝΟ') {
                $('#'+textareaid).val('');
                if(params.reply_id==0) {
                    var html = `<div class="comment" id="commentBox${res}"><img src="/media/${G.my.img}" alt="User"><div class="comment-content"><div class="comment-author">${G.my.fullname}</div><div class="comment-datetime">${date("F j, Y, H:i", params.created)}</div><div class="comment-text">${content}</div>`;
                    $('#commentContainer').prepend(html);
                }else{
                    var html=`<div class="comment" style="margin-left: 65px;"><img src="/media/${G.my.img}" alt="User"><div class="comment-content"><div class="comment-author">${G.my.fullname}</div><div class="comment-datetime">${date("F j, Y, H:i",params.created)}?></div><div class="comment-text">${content}</div>`
                    $('#replyBox'+params.reply_id).prepend(html);
                    $(this).parent().css('display','none');
                }
                console.log(res)
                console.log(html)
            }

        })
    })
    .on("click",'input[name="rating"]',function() {
        var params={a:this.name,stars:this.id.replace('star',''),id:G.id};
        console.log(params)
        $(this).prop("checked", true);
        s.db.post(params, function (res) {
            console.log(res)
        })
    })
    .on("click",'button[id^="save_"]',function() {
        var col=this.id.replace('save_','');
        var val=$('#'+col).html();
        var params={a:"save",col:col,id:G.id,val:val};
        console.log(params)
        s.db.post(params, function (res) {
            console.log(res)
        })
    })
    .on("click",'input[name="display"]',function(){
       var val=this.checked ? 'dark':'light'
        coo('display',val);
        $('body').attr('class',val);
    })
    .on('click', "#ssearch_book", function () {
        var q= $('#search_book').val().trim()
        coo('page',1)
        if(!!q){coo('q',q)}
            booklist(q);
    })
.on('click', "#submit_cat", function () {
	    var formid=$("#form_cat")
    event.preventDefault();
    var form = formid.serializeArray();
	s.db.post(form, function (data, textStatus, jqXHR) {
        if (data == 'no') {		
            console.log(textStatus)
            console.log(jqXHR)
            alert("Form cannot be submitted");
        } else {
            console.log(data)         
              location.href="/bks/cat";
            // formid.reset();
        }
    },'json');
	
})
.on('click', "#newbks", function () {
	s.db.post({a:"newbks"}, function (res) {
		console.log(res)
		location.href="/book?id="+res;
	})
})
.on('click', "#submit_book", function () {
    var formid=$("#form_book");
    event.preventDefault();
    var form = formid.serializeArray();
 //   form[s.size(form)]={name:'uid',value: coo('GID')}
   form[s.size(form)]={name:'saved',value: s.time()}
    // form[s.size(form)]={name:'status',value: {0:'unread',1:'reading',2:'read'}}
    //form[s.size(form)]={name:'excerpt',value: $('#excerpt').summernote('code')}
    //form[s.size(form)]={name:'content',value: $('#content').summernote('code')}
    console.log(form)
s.db.post(form, function (data, textStatus, jqXHR) {
		  console.log(data)
        if (data == 'no') {		
            console.log(textStatus)
            console.log(jqXHR)
            alert("Form cannot be submitted");
        } else {
            console.log(data)         
          //    location.href="/bks";
            // formid.reset();
        }
    });
})
/*
.on('keyup', "#writer, #publisher, #cat", function () {
	var id=this.id;
	var val=this.value.trim();	
	  s.db.get({a:"radiolist",b:id,c:val},function(data){
	 console.log(data);
	 var list='';
	 if(data!='no'){
		 for(var i in data){		 
		 list +='<div style="display:flex"><input type="radio" name="'+id+'list" value='+i+' data-name="'+data[i]+'">'+data[i]+'</div>';
		 }
	 $('#'+id+'list').html(list)
	 }
	 
	 },'json');
})

.on('click', "input[name='writerlist'], input[name='publisherlist'], input[name='catlist']", function () {
	var name = this.name.replace('list','');
	//if($(this).is(':checked')){$('input[name="'+this.name+'"]').prop("checked", false);}
	var sel= $("input[name='"+this.name+"']:checked").data('name');
	$('#'+name).val(sel)
})
*/	
.on('change', "select[id^='parent']", function () {
			var catid=this.id.replace('parent','')
			var obj = {
            id :catid,
            value : "parent='"+this.value+"'",
            table : "cat",
            where : "id="+catid
			}
			console.log(obj)
			s.db.queryhtml(obj, function(data){console.log(data);},"POST");
	})
	
	.on('keyup', "#name", function () {
		var name= this.value.trim();
		 s.db.query('UPDATE cat SET name="'+name+'" WHERE id='+G.id);
		 console.log(name)
	})
	/*
.on('change', "#status","#isread", function () {
	var obj = {
            id :G.id,
            value : this.id+"='"+this.value+"'",
            table : "book",
            where : "id="+s.get.id
			}
	s.db.queryhtml(obj, function(data){console.log(data);},"POST");
	})
	*/
.on('click', "#savewri", function () {
    var writer = $('#writer').val().trim();
    var writerlist = $('input[name=writerlist]:checked').val();
        if(typeof(writerlist)!='undefined'){
            s.db.func("q",'UPDATE book SET writer='+writerlist+' WHERE id='+G.id);
            var dataname= $('input[name=writerlist]:checked').attr('data-name');
            $('#writer').val(dataname);
        }else if(writer!=""){
            s.db.get({a:"inse",table:"writer",name:writer},function(data){
                console.log(data)
				if(data!='no'){
					s.db.func("q",'UPDATE book SET writer='+data+' WHERE id='+G.id);
					s.ui.notify('alert','Writer saved')
					}
            },'json');
        }else{
            s.ui.notify('alert','Please insert a writer');
        }
})
.on('click', "#savecat", function () {
        var cat= $('#cat').val().trim();
		var catlist = $('input[name=catlist]:checked').val();
         if(typeof(catlist)!='undefined'){
			 console.log('case update')
            s.db.query('UPDATE book SET cat='+catlist+' WHERE id='+G.id);
            var dataname= $('input[name=catlist]:checked').attr('data-name');
            $('#cat').val(dataname);
        }else if(cat!=""){
            s.db.get({a:"inse",table:"cat",name:cat},function(data){
                console.log(data)
				if(data!='no'){
					s.db.func("q",'UPDATE book SET cat='+data+' WHERE id='+G.id);
					s.ui.notify('alert','Category saved')
					}
            },'json');
        }else{
            s.ui.notify('alert','Please insert a category.')
        }
})
.on('click', "#savedi", function () {
        var publisher= $('#publisher').val().trim();
		var publisherlist = $('input[name=publisherlist]:checked').val();
         if(typeof(publisherlist)!='undefined'){
			 console.log('case update')
            s.db.query('UPDATE book SET publisher='+publisherlist+' WHERE id='+G.id);
            var dataname= $('input[name=publisherlist]:checked').attr('data-name');
            $('#publisher').val(dataname);
        }else if(publisher!=""){
			console.log('case insert')
            s.db.get({a:"inse",table:"publisher",name:publisher},function(data){
                console.log(data)
				if(data!='no'){
					s.db.func("q",'UPDATE book SET publisher='+data+' WHERE id='+G.id);
					s.ui.notify('alert','publisher saved')
					}
            },'json');
        }else{
            s.ui.notify('alert','Please insert a category.')
        }
})
.on('keyup change', "#title, #tag, #vol, #status, #isread", function () {
	var id=this.id,val=this.value;
	/*
	if (this.id == 'title') {
		$('#titlebig').text(this.value);
	}
	         var obj = {
            id :G.id,
            value : this.id+"='"+this.value+"'",
            table : "book",
            where : "id="+s.get.id
			}
	s.db.queryhtml(obj, function(data){console.log(data);},"POST");
*/		
		var params={a:"bookedit",b:id,val:val,id:parseInt(G.id)};
	  console.log(params)
	  s.db.get(params,res=>{
		  console.log(res);
		  if(id=="title")$('#titlebig').text(val)
	  })
})
//delete
.on('click', "button[id^='del']", function () {
	var id=this.id.replace('del','');
	s.confirm("This book record will be deleted. Are you sure?",function(res){
	if(res){
		var params={a:"del",b:G.page,c:id};
		console.log(params)
		 s.db.get(params,function(data){
			$('#nodorder1_'+id).hide();
		 })
		 }
	})	
})
//find image from google api
.on('click', "#savefinfo", function () {
    var sel= $("input[name='fitems']:checked"). val();
    $('#bookimg').attr('src',sel);
    //download to media
    //save to db
})
    .on("click","a[id^='order_']",function(){
        var name= this.id.replace('order_','')
//log(name)
        var orderby= coo('orderby')== name+' ASC' ? name+' DESC': name+' ASC';
        coo('orderby',orderby);
        coo.delete('page');
        // reset('mgr')
        location.reload();
    })


//page
.on('click', "button[id^='page_']", function () {
    var page= this.id.replace('page_', '');
    coo('pagenum',page);
    s.ui.reset('#bookbox');
    booklist()
})

//experimental run neo4j
// $.get("https://aimd5.com:7473/db/data/",function(neo){
// console.log(neo)
// },'json')
//lista με writers , publishers
  .on('keyup',"input[fun='lookup']",function(){
	lookup(this)
  })
  //specific job help
  .on('click',"button[id^='new_']",function(){
	  var param=this.id.replace("new_","");
	  var val=$('#'+param).val().trim();
	  var params={a:"new",b:param,c:val,id:parseInt(G.id)};
	  	  s.db.post(params,res=>{
		  console.log(res);
		  $(this).hide();
	  })
  })
  .on('click',"li[id^='loo']",function(){
    var param=this.parentNode.id.replace('loolist_','');
	console.log(param)
      var optionvalue= this.id.replace('loo','');
      var optionid= $(this).attr('val');
      $('#loolist_'+param).hide();
	  //save 
	  var params={a:"lookupsave",b:param,c:parseInt(optionid),id:parseInt(G.id)};
	  console.log(params)
	  s.db.get(params,res=>{
		  console.log(res);
		  $('#'+param).val(optionvalue)
	  })
  })
  
  function lookup(obj){
	  	var val=obj.value.trim().charAt(0).toUpperCase() + obj.value.trim().slice(1),param=obj.id,
	listi='',length=obj.value.length,counter=0;
	 s.db.get({a:"lookup",b:param,c:val},function(newd){
	var re=new RegExp(val,"i"),keys=Object.keys(newd),values=Object.values(newd),
     z={},newd= keys.filter(val=>re.test(val)).map(x=>{z[x]=values[keys.indexOf(x)];return z})[0]
    for (var j in newd){
    var piece= j.split(val);
	console.log(piece)
    listi +='<li id="loo'+j+'" val="'+newd[j]+'">' +piece[0]+
        (!!piece[1] ? '<span style="background:yellow">'+val+'</span>'+piece[1]:'')+
        '</li>';
      counter +=1;
    }
	  if (counter >0 && length >0){
      $('#loolist_'+param).html(listi).show();
      //$('#lookupcounter').text(counter)
    } else {
      $('#loolist_'+param).hide()
      if(length > 8){$('#new_'+param).show();}
	  
 //     $('#lookupcounter').text(0);
    }
	});
  }
  
  $(document).on("click",'#forgot_password',function () {
    s.dialog({
        message: "<input type='text' id='forgotmail'>",
        title: "INSERT_EMAIL_ADDRESS",
        buttons: {
            main: {
                label: "Send",
                className: "btn-primary",
                callback: function () {
                    var forgotmail = $('#forgotmail').val();
                    // console.log(forgotmail)
                    if (forgotmail != '') {
                        s.db.get( {a: 'forgot_password', b: forgotmail}, function (data) {
                            // console.log(data)
                            if (data == 'yes') {
                                s.modal("EMAIL_SENT_MAILBOX");
                            } else {
                                s.modal('Email can not be sent right now');
                            }
                        })
                    }
                }
            }
        }
    })
});