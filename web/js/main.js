$(function(){
	//alert('hello');
	//$('#postitList').html('ok');

	function getList(){
		$.ajax({
			type: 'GET',
			url: 'list',
			success: function(response){
				var html = '';
				html += '<table>';

				var l = response.length;

				for(var i = 0; i < l; i++){
					if(i % 4 == 0){
						html += '<tr>';
					}
					html += '<td class="' + response[i]['color'] + '">';
					html += '<h2>' + response[i]['date'] + '</h2>'; 
					//console.log(response[i]['date']);
					html += ' ';
					html += '<p>' + response[i]['content'] + '</p>';
					//html += '<button id="' + response[i]['id'] + '" class="delete">Supprimer</button>'
					html += '<a href="#" id="' + response[i]['id'] + '" onclick="return false">X</a>'	
					html += '</td>';

					if(i % 4 == 3){
						html += '</tr>';
					}					
				}

				html += '</table>';

				$('#postitList').html(html);

				//$('button[class=delete]').click(function(){
				$('a').click(function(){
					//$('#postitList').html('clické!');
					//alert('click!');
					deletePostit($(this).attr('id'));
				});
			},
			error: function(){
				$('#postitList').html('échec');
			}
		});
	}

	function deletePostit(postitId){
		$.ajax({
			type: 'POST',
			url: 'delete',
			data: {id: postitId},
			success: function(){
				getList();
			},
			error: function(){

			}
		});
	}

	function addPostit(postitContent){
		$.ajax({
			type: 'POST',
			url: 'add',
			data: {content: postitContent},
			success: function(){
				getList();
				$('textarea').val('');
			}
		});
	}

	getList();

	$('#add').click(function(){
		var content = $('textarea').val();
		content = content.replace(/(?:\r\n|\r|\n)/g, '<br />');
		addPostit(content);
	});

});