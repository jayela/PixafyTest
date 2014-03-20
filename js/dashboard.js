// JavaScript Document

var marking = false;
var tmp = "";
$(function(){
	$('.upload').click(function(e) {
        $('.uploadform').slideToggle();
    });
	$('.photocontainer img').click(function(e) {
		if (!marking) {
			$('.blackscreen .inner').attr('src', $(this).attr('src'));
			$('#positionInput').val($(this).attr('orderNum'));
			$('.blackscreen').fadeIn();
			$('#deleteButton').attr('imageId', $(this).attr('imageId'));
			$('#reorderId').val($(this).attr('imageId'));
		} else {
			if ($(this).hasClass('marked')) {
				$(this).removeClass('marked');
			} else {
				$(this).addClass('marked');
			}
		}
    });
    $('.blackscreen .close').click(function(e) {
        $('.blackscreen').fadeOut();
    });
    $('#deleteButton').click(function(e) {
        $.ajax({type:'POST', url: 'delete.php', data:{id:$('#deleteButton').attr('imageId')}, success: function(response) {
			if (response == 'success') {
        		$('.blackscreen').fadeOut();
				$('img[imageId='+$('#deleteButton').attr('imageId')+']').remove();
			}
		}});
    });
	$('.markMulti').click(function(e) {
        if (marking) {
			marking = false;
			$(this).attr('src', 'img/mark_off.png');
			$('.deleteMulti').css('display', 'none');
			$('.marked').each(function(index, element) {
                $(this).removeClass('marked');
            });
		} else {
			marking = true;
			$(this).attr('src', 'img/mark_on.png');
			$('.deleteMulti').css('display', 'inline-block');
		}
    });
	$('.deleteMulti').click(function(e) {
        if($('.marked').length > 0) {
			tmp = '';
			$('.marked').each(function(index, element) {
                tmp += 'x'+$(this).attr('imageId');
            });
			tmp = tmp.substr(1);
			$.ajax({type:'POST', url: 'deleteMulti.php', data:{id:tmp}, success: function(response) {
				var a = response.split("x");
				for (var i in a) {
					$('img[imageId='+a[i]+']').remove();
				}
			}});
		}
    });
	$('#changeMail').click(function(e) {
        $('.emailChange').slideToggle();
    });
	$('input').each(function(index, element) {
        JFLProcessField(this);
    });
});

function JFLProcessField(el) {
	var label = $(el).attr('label');
	if (label != undefined) {
		var color = $(el).attr('labelColor');
		var type = $(el).attr('type');
		var pass = type == 'password';
		JFLBlur(el, label, color, pass);
		$(el).focus(function(e) {
			JFLFocus(el, label, color, pass);
		});
		$(el).blur(function(e) {
			JFLBlur(el, label, color, pass);
		});
	}
}

function JFLChangeColor(el, color) {
	if (color != undefined) {
		$(el).css('color', color);
	}
}

function JFLBlur(el, label, color, pass) {
	if ($(el).val() == "") {
		if (pass) {
			$(el).attr('type', 'text');
		}
		$(el).val(label);
		JFLChangeColor(el, color);
	}
}

function JFLFocus(el, label, color, pass) {
	$(el).css('border', '');
	if ($(el).val() == label) {
		if (pass) {
			$(el).attr('type', 'password');
		}
		$(el).val("");
		JFLChangeColor(el, "");
	}
}