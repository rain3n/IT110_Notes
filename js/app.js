$(document).ready(function() {

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
					console.log(data);
				}
			});
		}
	};

	$("#add_note").click(function(){
		Note.add( $("#note-title").val(), $("#note-description").val() );
	});

});