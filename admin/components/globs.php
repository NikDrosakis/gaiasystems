<style>
    textarea.input-sm, select[multiple].input-sm {
        height: 300px;
    }
.globs-setBox{
background:white;
color:black;
float: left;
width: 335px;
position:relative;
margin:3px
}
.jsoneditor-container, .codeditor{
height:300px;
margin:42px 0 0 0;
color:black;
}
.jsoneditor .jsoneditor-menu {
    display: none;
}
</style>

<div id="globs_buffer"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.0/jsoneditor.min.js" integrity="sha512-PInE2t9LrzM/U5c/sB27ZCv/thNDKIA1DgRBzOcvaq21qlnQ/yI/YvzJMLdzsM1MvmX9j4TQLFi8+2+rTkdR4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document)
    .on("click", "#globs_menu", function () {
              var params={};
              params.file=G.ADMIN_ROOT+"components/globs_buffer";
              worker(params,function(result){
                  console.log(result);
                $('#globs_buffer').html(result.data)
                opener('globals_menu');
              },'GET');
          })
    .on("click", "button[id^='globtitle']", function () {
    	$(".gs-titleActive").attr('class', 'gs-title');
    	this.className="gs-titleActive";
    	var globname = this.id.replace('globtitle', '');
    	$('.gs-databox-inside').hide();
    	s.ui.switcher('#globs_'+globname);
    	coo('globs_tab',globname);
    })
    .on("click", "#newGlobalBtn", function () {
    		if ($('#newglobal').html() == "") {
    			s.ui.form.get({
    				adata: "globs",
    				nature: "new",
    				append: "#newglobal",
    				list: {
    					0: {
    						row: 'name', placeholder: "Global Name",
    						params: "required onkeyup='this.value=s.greeklish(this.value)'"
    					},
    					1: {row: G.LOC, placeholder: "Global Value"},
    					2: {
    						row: 'tag',
    						type: "drop",
    						global: G.globs_tags,
    						globalkey: true,
    						placeholder: "Select Tag"
    					},
    					3:{
    						row: 'type',
    						type: "drop",
    						global: G.globs_types,
    						globalkey: true,
    						placeholder: "Select type"
    					}
    				}
    			}, function (res) {
    			$('#newglobal').html('');
    				//	console.log(res)
    				//  location.reload();
    			})
    		} else {
    			$('#newglobal').html('');
    		}
    })
    //REMOVE
    .on("click", "button[id^='switchSet']", function () {
    	var id = this.id.replace('switchSet', '');
    	var val = $(this).text() == 'Open' ? 1 : 0;
    	s.db.query("UPDATE globs SET status=" + val + " WHERE id=" + id, function (data) {
    		if (data == 'yes') {
    			if (val == 1) {
    				$('#setBox' + id).css('backgroundColor', '#d3ffb1');
    				$('#switchSet' + id).text('Close').removeClass('btn-success').addClass('btn-danger');
    			} else {
    				$('#setBox' + id).css('backgroundColor', '#d8d6d6');
    				$('#switchSet' + id).text('Open').removeClass('btn-danger').addClass('btn-success');
    			}
    		} else {
    			s.modal("Setting cannot be switched!");
    		}
    	})
    })
    //EDIT
    .on("keyup change click keyup", "input[id^='set']", function () {
    	//	console.log(this.value)
    	var id = this.id.replace('set', '');
    	var query='UPDATE globs SET en="' + encodeURIComponent(this.value) + '" WHERE id=' + id;
    	s.db.query(query);
    })
    .on("click", "button[id^='delpvar']", function () {
    	var id = this.id.replace('delpvar', '');
    	s.db.query("DELETE FROM globs WHERE id=" + id, function (res) {
    		if (res != 'No') {
    			$('#setBox' + id).remove();
    		}
    	})
    })
    .on("change", "select[id^='pvartype']", function () {
    	var id = this.id.replace('pvartype', '');
    	s.db.query("UPDATE globs SET type='"+this.value+"' WHERE id=" + id, function (res) {
    		if (res == 'yes') {
    			s.modal('true')
    		}
    	});
    })




        //JSONEditor for json fields
           document.addEventListener('DOMContentLoaded', function() {

            // Select all textareas with the class 'codeditor'
            const codeareas = document.querySelectorAll('.codeditor');

            // Loop through each textarea and initialize CodeMirror
            codeareas.forEach((textarea) => {
                CodeMirror.fromTextArea(textarea, {
                    mode: { name: "javascript", json: true }, // JSON mode
                    lineNumbers: true,
                    theme: "material-darker", // Optional: Change theme
                    autoCloseBrackets: true, // Auto-close brackets
                    matchBrackets: true, // Highlight matching brackets
                    lineWrapping: true // Wrap long lines
                });
            });
              // Select all textareas with the class 'jsoneditor-textarea'
               const jsontextareas = document.querySelectorAll('.jsoneditor-textarea');
               jsontextareas.forEach(textarea => {
                   const textareaId = textarea.id;  // Get the unique ID of the textarea
                   const containerId = textareaId.replace('jsoneditor-', 'jsoneditor-container-');
                   const container = document.getElementById(containerId); // Find the container using the constructed ID
                   const jsonContent = textarea.value;
                   const dbid = textareaId.replace('jsoneditor-','');
                   // Define options for JSONEditor
                   const options = {
                       mode: 'tree',
                       onError: function (err) {
                           console.warn(err.toString());
                       },
                       onModeChange: function (newMode, oldMode) {
                           console.log('Mode switched from', oldMode, 'to', newMode);
                       },
                       onChange: function () {
                         //  console.log(textarea.value);
                           var value = JSON.stringify(editor.get());
                          var id = dbid;
                                var query='UPDATE globs SET en="' + encodeURIComponent(value) + '" WHERE id=' + id;
                                s.db.query(query);
                       }
                   };
                   const editor = new JSONEditor(container, options);
                   try {
                       const json = JSON.parse(decodeURIComponent(jsonContent));
                       editor.set(json); // Use editor.set() instead of editor.update()
                   } catch (e) {
                       console.error('Invalid JSON in textarea', e);
                   }
                              // Button event to validate and format JSON
                           document.getElementById('save-json'+dbid).addEventListener('click', function() {
                                const jsonOutput = document.getElementById('save-json'+dbid);
                                      try {
                                          const json = JSON.parse(editor.getValue());
                                          jsonOutput.innerText = JSON.stringify(json, null, 2); // Pretty print JSON
                                          jsonOutput.className = ''; // Reset error class
                                      } catch (e) {
                                          jsonOutput.innerText = 'Invalid JSON format';
                                          jsonOutput.className = 'error';
                                      }
                                  });
               });


           });
    </script>