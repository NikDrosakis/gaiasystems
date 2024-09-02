<style>
#widget-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; 
    padding: 10px;
    box-sizing: border-box;
    position: relative;
}

.widget-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 20px; /* Gap between the boxes */
    margin: 20px 0;
}

.widget-box {
    border: 1px solid #ccc;
    padding: 10px;
    background-color: aliceblue;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px);
    box-sizing: border-box;
    margin-bottom: 20px;
}

.widget-input, .widget-textarea, .widget-select {
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 10px;
    border:none;
}

.widget-textarea {
    height: 100px;
}

label {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.widget-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px;
}

.widget-version {
background-color: #4CAF50;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 1em;
    margin: 2px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .widget-box {
        width: calc(50% - 20px);
    }
}

@media screen and (max-width: 480px) {
    .widget-box {
        width: 100%;
    }
}

</style>
<button id="create_new_cubo">New Cubo</button>
<h1>Cubos Management</h1>
 <div id="widget-container"></div>


<script>
    //newwidget name check
 $("#create_new_cubo").on("click", function() {
 var newhtml =`
 <div class="widget-box">
     <div class="widget-header">
 	</div>
     <label style="color:darkblue" for="widget-name-5"><input type="text" placeholder="Insert Name of Cubo" id="newwidget-name" class="widget-input" value="10"></label>
     <label style="display:block;clear:left" for="widget-status-5">Status:</label>
     <select id="widget-status-5" class="widget-select">
                     <option value="0" selected="selected">archived</option>
                     <option value="1">deprecated</option>
                     <option value="2">pending</option>
                     <option value="3">active</option>
             </select>

     <label style="display:block;clear:left" for="widget-description-5">Description:</label>
     <textarea id="widget-description-5" class="widget-textarea" placeholder="Enter description"></textarea>

     <label style="display:block;clear:left" for="widget-valuability-5">Valuability (1-10):</label>
     <input type="number" id="widget-valuability-5" class="widget-input" value="">
     <label style="display:block;clear:left" for="widget-flag-5">Flag:</label>
     <select id="widget-flag-5" class="widget-select">
         <option value="0" selected="selected">Off</option>
         <option value="1">On</option>
     </select>
     <label style="display:block;clear:left" for="widget-ideally-5">Ideally:</label>
     <textarea id="widget-ideally-5" class="widget-textarea" placeholder="Enter ideally"></textarea>
 </div>
 `
		if(!$('#submitnewtaxgrp').html()) {
			$(newhtml).insertAfter(this)
		}else{
			$("#newtaxgrpbox").remove();
		}
	});


s.ajaxfile = G.ADMIN_ROOT+"main/cubos/xhr.php";
console.log(s.ajaxfile)
$.get("/admin/index.php", {a: 'cubos_get',file:s.ajaxfile}, function(res){
    console.log(res);
    $("#widget-container").html(res.html);
}, 'json');

$(document).on("keyup change", "textarea[id^='widget-'], input[id^='widget-'], select[id^='widget-']", function () {
    var exp = this.id.split('-');
    var id = exp[2];
    var col = exp[1];
    var query = `UPDATE cubos SET ${col}='${this.value}' WHERE id=${id}`;
    console.log(query);
    s.db.query(query);
});
</script>