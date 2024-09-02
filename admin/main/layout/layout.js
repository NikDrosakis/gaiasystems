/*updated:2020-02-05 06:09:52 widget - v.0.73 - Author:Nikos Drosakis - License: GPL License*/
//check if name exists
var page_selected=!coo('page_selected') ? "books" : coo('page_selected');
document.addEventListener('DOMContentLoaded', function () {
    var widgetsContainer = document.getElementById('areas');
    var pageSelect = document.getElementById('page');
    var droppableAreas = document.querySelectorAll('.droppable');
    // Initialize Sortable for widgets container
    Sortable.create(widgetsContainer, {
        group: {
            name: 'shared',
            pull: true,  // Allow dragging out of the container
            put: false   // Prevent dropping back into the original container
        },
        sort: false,
        animation: 150,
        handle: '.draggable'
    });

    // Initialize Sortable for each droppable area
    droppableAreas.forEach(function (area) {
        Sortable.create(area, {
            group: 'shared',
            animation: 150,
            sort: true,  // Allow sorting within droppable areas
            onAdd: function (evt) {
                var target = evt.to;
                var item = evt.item;

                // Check if target container already has an item
                if (target.children.length > 1) {
                    // Swap the existing item with the new one
                    var existingItem = target.children[0];
                    evt.from.insertBefore(existingItem, evt.from.children[evt.oldIndex]);
                    target.appendChild(item);
                }

                // Save the state
                saveState();
            },
            onUpdate: function (evt) {
                // Save the state when items are reordered
                saveState();
            }
        });
    });

    // Function to save the state
    function saveState() {
        var state = {};
        droppableAreas.forEach(function (area) {
            var parent = area.parentElement.id;
            if (!state[parent]) {
                state[parent] = {};
            }
            if (area.children.length > 0) {
                var widget = area.children[0];
                state[parent][area.id] = widget.id;
            } else {
                state[parent][area.id] = null;
            }
        });
        var selectedPage = pageSelect.value;
        var query = JSON.stringify(state);
        s.db.post({a:"widget_save",file:G.ADMIN_ROOT+"main/layout/xhr.php",b:selectedPage,c:query},function(res){
            console.log(res+'Saved state for', selectedPage, ':', query);
        })
    }
    // Function to set droppable areas to starting point
    function setStartingPoint() {
        droppableAreas.forEach(function (area) {
            // Clear all droppable areas
            while (area.firstChild) {
                widgetsContainer.appendChild(area.firstChild);
            }
        });
     //   console.log('Set to starting point');
    }

// Function to restore the state
    function restoreState() {
        var selectedPage = pageSelect.value;
       // console.log({a:"widget_get",b:selectedPage})
        s.db.get({file:G.ADMIN_ROOT+"main/layout/xhr.php",a:"widget_get",b:selectedPage},function(savedState){
        //    console.log(savedState)
            if (savedState!='NO') {
				var state=JSON.parse(savedState);
                // Clear all droppable areas
                droppableAreas.forEach(function (area) {
                    while (area.firstChild) {
                        widgetsContainer.appendChild(area.firstChild);
                    }
                });
                // Place widgets in their saved positions
                for (var parent in state) {
                    for (var childId in state[parent]) {
                        if (state[parent][childId]) {
                            var widget = document.getElementById(state[parent][childId]);
                            var area = document.getElementById(childId);
                            if (widget && area) {
                                area.appendChild(widget);
                            }
                        }
                    }
                }
       //         console.log('Restored state for', selectedPage);
            } else {
                setStartingPoint();
            }
        });
    }


    // Event listener for page selection change
    pageSelect.addEventListener('change', function () {         
		restoreState();
    });

    // Restore state on page load
    restoreState();
});
/*

function save_html2file(filename){
					var html = $('#phpeditor').html();
					console.log(filename)
					console.log(html)
					s.file.put_contents(filename, html, function (data) {
						console.log(data)
						if (data == 'ok') {
							s.ui.notify("info","FILE SAVED!")
						} else {
							s.ui.notify("danger","FILE NOT SAVED!")
						}
					},"html")
		}
if(G.sub=='') {
        function create_order(){
            //var url=G.SITE_URL+'templates/'+G.template+'/'+page_selected+'.json';
            var uri=G.TEMPLATESURI+G.template+'/'+page_selected+'.json';
			   var newjsondata= {areas:[],order:{}};
                s.file.put_contents(uri, newjsondata,function(data){
                    console.log(data);
					location.reload()
                });
                console.log(newjsonfile);
        }

	function save_order(sortlist){
	//	var area=sortlist[0].split('-')[0];
		var neworder={};
		var url=G.SITE_URL+'templates/'+G.template+'.json';
        var uri=G.SITE_ROOT+'templates/'+G.template+'/pages.json';
		$.get(url,{},function(res){
			var newres=res;
			for (var i in sortlist){
			var widget=sortlist[i].split('-')[1];
            var form = $("#form-"+widget).serializeArray();
			neworder[widget]={}
			for(var j in form){neworder[widget][form[j].name]=form[j].value;}
			}
			newres[page_selected]=neworder;
            g.file.put_contents(uri, newres,function(data){
				console.log(data)
				if(data=='ok'){
					s.ui.notify("info","Order saved")
				}
				})
        })
	}
//create_order('index.json');
        function transform_widget(obj){
            //get new sorting
            var newarray=[];
			//read order from localstorage
			var porder= JSON.parse(local('pageorder_'+page_selected));
			//loop all items
            $(obj.item).parent().find('.list-group-item').each(function() {
                var  wid = $(this).attr('id').replace('wid-','');
                newarray.push(wid);
            })
			var to=$(obj.item).parent().attr('id').replace('area-','');
			var widtype= $(obj.item).hasClass("local") ? "local" : ($(obj.item).hasClass("global") ? "global":"");
				var widget=obj.item.id.split('-')[1];
				var path= widtype=="local" ? G.WIDGETLOCALPATH+widget+'.json': G.WIDGETPATH+widget+'.json';
				//add prefix
				var tag = 0;
				var key = true;
				while (key) {
				if(!JSON.parse(local('pageorder_'+page_selected)).hasOwnProperty(widget+"_"+tag)){
					key = false;
				}tag += 1;
				}
				var widgetprefix=widget+"_"+tag;

				$.get(path,{},function(wres){
                    	console.log(wres)
                   obj.item.innerHTML='<form method="POST" id="form-'+widgetprefix+'">'+
                        '<input type="hidden" name="version" value="'+wres.version+'">'+
                        '<input type="hidden" name="method" value="'+wres.method+'">'+
                        '<input type="hidden" name="to" value="'+to+'">'+
					    '<div class="widheader"><label>'+widgetprefix+'</label>'+
                        '<button onclick="event.preventDefault();s.ui.switcher(&quot;#subox-'+to+widget+'&quot;)" style="float:left;margin-right:20px" class="btn btn-info btn-xs">></button>'+
                        '<button style="float:right" class="btn btn-danger btn-xs" id="delwid-'+widgetprefix+'">X</button>'+
                        '<span style="margin-left:20px">v.'+wres.version+'</span>'+
                        '</div>'+
                        '<div id="subox-'+to+widget+'" background="antiquewhite" class="subox">'+
                        '<label>Headline</label><input name="headline" class="form-control" value="'+wres.headline+'">'+
						'<div>Where:<input name="wherekey" class="form-small" value="'+wres.wherekey+'">'+
						'<input name="where" class="form-small" value="'+wres.where+'">'+
						'</div>'+
                        '<div>Limit:<input name="limit" type="number" min="1" class="form-small" value="'+wres.limit+'"></div>'+
                        '<div>Orderby:<input name="orderby" class="form-small" value="'+wres.orderby+'"></div>'+
                        '<button class="btn btn-success btn-xs" id="savewid-'+to+'-'+widget+'">Save</button>'+
                        '</div></form>';
                    $(obj.item).attr('id','wid-'+to+'-'+widgetprefix).removeClass('nested-'+widget).addClass('nested-'+widgetprefix);

					//	save_order(newarray);
				//add to localstorage dnd effect
				console.log(newarray)
				//save to neworder
				for(var i in newarray){
				var widget=newarray[i].indexOf('-') > 1 ?  newarray[i].split('-')[1] : widgetprefix;
				console.log(widget)
				if(!porder[widget]){porder[widget]=s.json.form("#form-"+widget);}
				porder[widget]['sort']=i;
				}
				//saving
				local('pageorder_'+page_selected,JSON.stringify(porder));

				},'json');
        }

        function areaboard(){
                //create sortable areas
                var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
                for (var z = 0; z < nestedSortables.length; z++) {
                    new Sortable(nestedSortables[z], {
                        group: 'nested',
                        //sort: false,
                        pull:false,
                        easing: "cubic-bezier(1, 0, 0, 1)",
                        filter: ".ignore-elements",
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        invertSwap :true,
                        draggable: ".wid",
					//	filter: '.subox',
                        onEnd: function(evt) {
							//transform_widget(evt)
							var newarray=[];
							$(evt.item).parent().find('.list-group-item').each(function() {
								var  wid = $(this).attr('id').replace('wid-','');
								newarray.push(wid);
							})

							//internal dnd saved to localstorage
						//	var porder= JSON.parse(s.local.get('pageorder_'+page_selected));
						//	var area=newarray[0].split('-')[0];
						//	for(var i in newarray){
						//	var widget=newarray[i].split('-')[1];
						//	porder[widget]['sort']=i;
						//	}
						//	local('pageorder_'+page_selected,JSON.stringify(porder));
						//save_order(newarray);
						}

					});
                }
                //create sortable widgets
                new Sortable(wid, {
                    group: {
                        name: 'nested',
                        draggable: ".wid",
                        sort: false,
                        pull: 'clone' // To clone: set pull to 'clone'
                    },
                    animation: 150,
                    onEnd: function(evt) {
						var newarray=[];
						$(evt.item).parent().find('.list-group-item').each(function() {
							var  wid = $(this).attr('id').replace('wid-','');
							newarray.push(wid);
						})
                        transform_widget(evt);
                    }
                });
        }

		//append loop with widget_loop.php request

		s.file.include(s.ajaxfile,{a:"load_widgetloop",page:page_selected,order:s.local.get("pageorder_"+page_selected)},function(res){
		$.when($('#areas').html(res)).then(function(){
		areaboard();
		})
		},'POST');


  }
*/
/************************************
 EVENTS
 ************************************/
/*
    $(document)
	//check if unique
	.on("keyup","#name",function(){

	})
	.on("click","#savepagejson",function(){
			var url=G.SITE_URL+'templates/'+G.template+'/page.json';
			var uri=G.SITE_ROOT+'templates/'+G.template+'/page.json';
			var newres={}
			$.get(url,{},function(res){
			newres=res;
			newres[page_selected]=JSON.parse(local.get('pageorder_'+page_selected));
			console.log(newres)
			g.file.put_contents(uri, newres,function(data){
			console.log(data)
			if(data=='ok'){
				s.ui.notify("info","Order saved")
			}
			})
		})
	})
	.on("click","button[id^='delwid-']",function(){
        event.preventDefault();
     //   var area=g.explode('-',this.id)[1];
        var widget=this.id.replace('delwid-','');

		//del from localstorage
		//add to localstorage dnd effect
			var porder= JSON.parse(local.get('pageorder_'+page_selected));
			delete porder[widget];
			local('pageorder_'+page_selected,JSON.stringify(porder));
		$("div[id*='"+widget+"']").hide();
        //var url=G.SITE_URL+'templates/'+G.template+'/pages/'+page_selected+'.json';
        //var uri=G.SITE_ROOT+'templates/'+G.template+'/pages/'+page_selected+'.json';
        //$.get(url,{},function(res){
            //var newres=res;
            //delete newres.order[area][widget];
            //g.file.put_contents(uri, newres,function(data){
                //$('#wid-'+area+'-'+widget).hide();
            //})
        //})
    })
    .on("click","button[id^='savewid-']",function(){
        console.log('saving edit widget')
		event.preventDefault();
        var area=g.explode('-',this.id)[1];
        var widget=g.explode('-',this.id)[2];

		var porder= JSON.parse(local.get('pageorder_'+page_selected));
		porder[widget]=s.json.form("#form-"+widget);
		local('pageorder_'+page_selected,JSON.stringify(porder));
		//create widget php file
      /*  var url=G.SITE_URL+'templates/'+G.template+'/page.json';
        var uri=G.SITE_ROOT+'templates/'+G.template+'/page.json';
        $.get(url,{},function(res){
            var neworder={};
            for(var i in form){neworder[form[i].name]=form[i].value;}
            var newres=res;
			//reorder index
            if(!newres.order[area]){newres.order[area]={};}
            newres.order[area][widget]=neworder;
            g.file.put_contents(uri, newres,function(data){
                console.log(data);
				if(data=='ok'){
					s.ui.notify("info","Order saved")
				}
            })
        })


    })
	//new & edit widget
    .on("click", "#savewid", function () {
		console.log('saving new widget')
        event.preventDefault();
        var widget = $('#name').val().trim();
        var form = $("#form-new").serializeArray();
        var newid = {};
        for (var i in form) {
            newid[form[i].name] = form[i].value;
        }
        //var urijson = G.SITE_ROOT + 'templates/' + G.template + '/widgets/' + newid.widget + '.json';
        var urijson = G.WIDGETLOCALURI + widget + '.json';
        g.file.put_contents(urijson, newid, function (data) {
         //   console.log(data);

        if(G.sub=='new' || G.sub=='edit'){
            //var phpjson = G.SITE_ROOT + 'templates/' + G.template + '/widgets/' + newid.widget + '.php';
            var phpwidget = G.WIDGETLOCALURI + newid.widget + '.php';
            save_html2file(phpwidget);
        }
			if(G.sub=='new'){
			location.href = '/admin/widget?sub=edit&name=' + newid.widget;
			}
		 })
        //save file
    })
	.on("click","#create_json",function(){
            //var newjson=this.value.split('.')[0]+'.json';
            create_order()
    })
*/