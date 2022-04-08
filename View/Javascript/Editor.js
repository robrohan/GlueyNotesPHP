Sortie.Core.$({
	include:new Array(
		"/View/Javascript/Common.js",
		"/View/Javascript/json.js"
	)
});

function openFile() {
	var file = new Object();
	file.list = GlueyNotes.selected_list;
	file.hash = GlueyNotes.current_hash;
	
	var json_file = JSON.stringify(file);
	
	GlueyNotes.gateway.DoRequest({
		method:"POST",
		url:"/Remote/GetFile",
		handler:function(conn) {
			if(conn.status == 200) {
				var item_text = JSON.parse(conn.responseText);
				var txtarea = document.getElementById('list_editor');
				
				txtarea.value = item_text.contents;
				GlueyNotes.current_hash = item_text.hash;
			} else {
				//?
			}
		},
		body:json_file
	});
}

function saveFile() {
	var feedback = document.getElementById('feedback_div');
	feedback.innerHTML = "<img src='/View/Style/Images/busy.gif'>";
	
	var txtarea = document.getElementById('list_editor');
	
	var file = new Object();
	file.list = GlueyNotes.selected_list;
	file.contents = txtarea.value;
	file.hash = GlueyNotes.current_hash;
	
	var json_file = JSON.stringify(file);
	
	GlueyNotes.gateway.DoRequest({
		method:"POST",
		url:"/Remote/SaveFile",
		handler:function(conn) {
			if(conn.status == 200) {
				var response = JSON.parse(conn.responseText);
				
				if(response.hash) {
					feedback.innerHTML = "File saved " + new Date();
					GlueyNotes.current_hash = response.hash;
				} else if(response.code) {
					feedback.innerHTML = response.text;
					if(response.code == 409) {
						if(confirm(response.text + "\nLoad the new list? (if you have local changes they will be lost.)")){
							txtarea.value = '';
							//firefox seems to need a pause here or it wont update
							//the text area properly
							setTimeout("openFile()", 500);
						}
					}
				} else {
					feedback.innerHTML = "Error on file save.";
				}
			} else {
				//?
			}
		},
		body:json_file
	});
}
