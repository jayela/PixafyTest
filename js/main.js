// JavaScript Document

var valid = true;

$(function(){
	$('input').each(function(index, element) {
        JFLProcessField(this);
    });
	$('#registerformm, #loginformm').submit(function(e) {
		valid = true;
		$(this).parent().children("error").each(function(index, element) {
            $(this).remove();
        });
        $(this).children('input').each(function(index, element) {
			$(this).css('border', '');
            if ($(this).val() == "" || $(this).val() == $(this).attr('label')) {
				$(this).css('border', '1px solid #C00');
				valid = false;
			}
        });
		if (valid && $(this).attr('id') == '#registerformm') {
			valid = $('#pass').val() == $('#cpass').val();
			$(this).parent().append("<error>Passwords don't match.</error>");
		}
		if (!valid) {
			$(this).parent().append("<error>You haven't filled out all the fields.</error>");
		}
		return valid;
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