$(document).ready(function() {
	var user = 1;
	var Note = {
		add: function(name, description){
			var new_note = {
				name: name,
				description: description
			};

			$.ajax({
				type: "POST",
				data: { data: new_note, action:'add_note'},
				url: "php/Note.php",
				success:  function(data){
					Note.get(user);
					$("#addNoteModal").modal("hide");
					$("#note-title").val("");
					$("#note-description").val("");
				}
			})
		},
		get: function(user_id){
			$.ajax({
				type: "POST",
				data: { user_id: user_id, action:'get_notes'},
				url: "php/Note.php",
				success:  function(data){
					Note.list = jQuery.parseJSON(data);

					$(".notes_list").empty();
					$.each(Note.list, function(index, value){
						var date_display;

						if(value.updated_at == '0000-00-00 00:00:00'){
							date_display = value.created_at;					
						}else{
							date_display = value.updated_at;
							console.log(date_display);
						}

						$(".notes_list").append(
							"<li class=\"note\">" 
						+"<div class=\"card\">"
						+	"<div class=\"card-header\">" + value.title
						+ "<small class=\"float-right\">"
						+ 		date_display
						+ "</small>"
						+"</div>"
						+	  "<div class=\"card-body clearfix\">"
						+		  value.description
						+		"<p class=\"card-text\">"
						+		"</p>"
						+		"<button data-note_id=\""+value.id+"\" class=\"pull-right btn btn-primary m-1 edit_note\">"
						+		  "<i class=\"fa fa-edit\"></i>"
						+		"</button>"
						+		"<button data-note_id=\""+value.id+"\" class=\"pull-right btn btn-primary m-1 delete_note\">"
						+		  "<i class=\"fa fa-trash\"></i>"
						+		"</button>"
						+	  "</div>"
						+	"</div>"
						+  "</li>"
					)
					});
				}
			})
		},
		delete: function(note_id){
			if(confirm("Are you sure? This action cannot be undone.")){
				$.ajax({
					type: "POST",
					data: {id:note_id, action:'delete_note'},
					url: 'php/Note.php',
					success: function(data){
						Note.get(user);
					}
				})
			};
		},
		edit_trigger: function(to_edit){
			$("#edit-note-title").val(to_edit.title);
			$("#edit-note-description").val(to_edit.description);
			$("#editNoteModal").modal("show");	
		},
		save_changes: function(){
			if(confirm("Are you sure you want to edit this note?")){
				var edited_note ={
					id:Note.currently_editting,
					name: $("#edit-note-title").val(),
					description: $("#edit-note-description").val(),
				}

				$.ajax({
					type: "POST",
					data: {data: edited_note, action:'edit_note'},
					url: 'php/Note.php',
					success: function(data){
						Note.get(user);
						$("#editNoteModal").modal("hide");
					}
				})
			};
		}
	};

	Note.get(user)

	$("#add_note").click(function(){
		Note.add( $("#note-title").val(), $("#note-description").val() );
	});

	$("#edit_note").click(function(){
		Note.save_changes();
	});

	$(".notes_list").on('click', '.delete_note', function(e){
		Note.delete(this.dataset.note_id);
	});

	$(".notes_list").on('click', '.edit_note', function(e){
		Note.currently_editting = this.dataset.note_id;
		Note.edit_trigger(Note.list.find(obj => obj.id == this.dataset.note_id));
	});

});