<!---EDITOR----->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!---UPLOADER----->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.js"></script>

<div id="mainpage">

<?php if($this->sub!='new'){ ?>
<a href="/admin/post?sub=new" class="btn btn-success" >New Post</button>
<?php } ?>
<a href="/admin/post?sub=groups" class="btn btn-info" id="groups">Postgroups</a>

<?php $cols= $this->db->columns('post'); ?>
<?php if($this->id!=""){

    //include "post_edit.php";
    include SITE_ROOT."main/post/post_read.php";
    ?>



<?php }elseif($this->sub=='groups'){ ?>

    <button class="btn btn-info" id="newgroupsbtn">New Post Group</button>

<?php }elseif($this->sub=='new'){ ?>
<!-----------------------------------------------------
                POST NEW
------------------------------------------------------>

<?php
//create new post form
echo $this->form($this->page,array('title','titlegr','uri','subtitle','subtitlegr','status','sort','excerpt','content','excerptgr','contentgr','html1','html2','html3'),true);
?>
<br/>
<br/>

<?php }elseif($this->sub=='' || in_array($this->sub,$this->tax_uri)){ ?>
    <!-----------------------------------------------------
                    POST LIST
    ------------------------------------------------------>
<div class="stylebox">
       <button type="button" class="btn btn-<?=$_COOKIE['list_style']=='boxy' ? 'success':'info'?>" onclick="coo('list_style','archieve');location.reload()">Archieve Style</button>
        <button type="button" class="btn btn-<?=$_COOKIE['list_style']=='table' ? 'success':'info'?>" onclick="coo('list_style','table');location.reload()">Table Style</button>
    </div>
<div class="flag_books" id="count_post"></div>	
<div id="pagination" class="paginikCon"></div>
<div id="post">
<!--APPEND BOXY OR ARCHIVE STYLE-->
</div>

<?php } ?>

</div>
<script>
    $(document)
        .on("click","span[id^='lastname']",function(){
            s.ui.table.editable(this.id);
        })
        .on("keyup","input[id^='lastname']",function() {
            // console.log('UPDATE user SET lastname="'+this.value+'" WHERE id=1')
            s.db.query('UPDATE user SET lastname="'+this.value+'" WHERE id=1');
        })
        .on('click',"#submitnewpostgrp",function(){
            var grpname= $('#newpostgrp').val().trim();
            if(grpname!=""){
                s.db.query('INSERT INTO postgrp (name,status) VALUES("'+grpname+'",1)',function(){
                    location.reload();
                });
            }else{
                s.ui.notify('alert','Please insert a postgroup name.')
            }
        })
        /*********************************************
         * TOP BUTTONS
         ***********************************************/
        .on("click","#newgroupsbtn",function(){
            if(!$('#submitnewpostgrp').html()) {
                $("<input class='form-control' placeholder='New postgroup name' id='newpostgrp'><button class='btn btn-success' id='submitnewpostgrp'>Create</button>").insertAfter(this)
            }else{
                $("#newpostgrp").remove();
                $("#submitnewpostgrp").remove();
            }
        })
        //make sortable
        //    s.ui.sortable("UPDATE post SET sort=? WHERE id=?", "tr");
        /*********************************************
         * POST EDIT
         ***********************************************/
        .on('keyup change', "#title,#titlegr,#uri,#subtitle,#subtitlegr,#sort,#seodescription,#seopriority", function () {
            var value=this.value.trim();
            if (this.id == 'title') {
                $('#title').text(value);
            };
            //g.db.queryone('post', this, G.id);
            s.db.query("UPDATE post SET "+this.id+"='"+value+"',modified="+s.time()+" WHERE id="+G.id,function(data){
                console.log(data);
            });
        })
        .on('change', "#status,#postgrpid,#seopriority", function () {
            s.db.queryone('post', this, G.id);
        })
        .on('click', "#submit_content,#submit_contentgr,#submit_excerpt,#submit_excerptgr,#submit_html1,#submit_html2,#submit_html3", function () {
            var row =this.id.replace('submit_', ''),
                value=$('#' + row).summernote('code'),
                id=parseInt(G.id),
                query="UPDATE post SET "+row+"='"+encodeURIComponent(value)+"' WHERE id="+id;
            console.log(query);
            //var obj = {
            //id : id,
            //value : ,
            //table : "post",
            //where : "id="+G.id
            //}
            s.db.query(query, function(data){console.log(data);});
            //g.db.queryhtml(obj, function(data){console.log(data);},"POST");
        })
        .on('click', "button[id^='delete']", function () {
            var id=this.id.replace('delete','');
            s.confirm("This post will be deleted. Are you sure?",function(res){
                if(res){
                    s.db.query("DELETE FROM post WHERE id="+id, function(data){
                        console.log(data);
                        if(data=='yes'){$('#nodorder1_'+id).hide();}else{s.modal("problem deleting")}
                    })
                }
            })
        })
        /*********************************************
         * POST NEW
         ***********************************************/
        .on('click', "#submit_post", function () {
            var formid=$("#form_post");
            event.preventDefault();
            var form = formid.serializeArray();
            form[s.size(form)]={name:'uid',value: coo('GSID')}
            form[s.size(form)]={name:'created',value: s.time()}
            form[s.size(form)]={name:'modified',value: s.time()}
            //  form[s.size(form)]={name:'excerpt',value: $('#excerpt').summernote('code')}
//    form[s.size(form)]={name:'content',value: $('#content').summernote('code')}
            console.log(form)
            $.post(s.ajaxfile, form, function (data, textStatus, jqXHR) {
                console.log(data)
                if (data == 'no') {
                    console.log(textStatus)
                    console.log(jqXHR)
                    s.modal("Form cannot be submitted");
                } else {
                    console.log(data)
                    location.href="/admin/post?id="+data;
                    // formid.reset();
                }
            },'json');
        })

    /*
    * POST TABLE
    * */
    function postlist(q){
        var page=!coo('post_page') ? 1: coo('post_page');
        var params= {a:"load_posts",page:page,q:q,page:G.page,name:G.name};
        //url,div,data,callback,method,datatype
        console.log(params)
        s.dg.get(params,function(res){
            console.log(res)
            $('#post').html(res.html);
            $('#count_post').text(res.count+ " posts saved");
            s.ui.pagination.get(page, res.count, 12,G.page);
        },'json');
    }
</script>