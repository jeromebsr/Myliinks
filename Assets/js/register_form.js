$(document).ready(function() {
	$("#username").blur(function () {
		setTimeout(function () {
			var regex = new RegExp('^[a-zA-Z0-9]{2,}[a-zA-Z]+[0-9]*$');

			if (regex.test($("#username").val()) === true)
			{
				$.post("Assets/ajax/ajax_checkUsername.php", { username:$("#username").val() }, function(data)
				{
					if(data.trim()=='no')
					{
						$("#username").css("border", "1px solid red");
						$(".username").html('Username already exist !');
						$("#submit").attr("disabled", "disabled");
						$(".username").fadeIn(500);
						$(".username-field-icon-check").html('<i class="bi bi-x-circle-fill"></i>');
						$(".username-field-icon-check").css('color', 'red');
					}else {
						$(".username").html('Username avalaible !');
						$("#username").css("border", "1px solid green");
						$("#submit").removeAttr("disabled");
						$(".submit").html("");
						$(".username-field-icon-check").html('<i class="bi bi-patch-check-fill"></i>');
						$(".username-field-icon-check").css('color', '#198754');
						$(".username").fadeOut(500);
					}
				});
			} else {
				$("#username").css("border", "1px solid red");
				$(".username").html('Usernames may only contain letters ans numbers. 3 characters min.');
				$("#submit").attr("disabled", "disabled");
				$(".username").fadeIn(500);
			}
		}, 100);
	});

	$("#email").blur(function(){
		setTimeout(function(){
			var recup = new RegExp('^[a-z][\.\\-\_a-z0-9]+@([a-z]{3,15})+(\\.)([a-z]){2,3}$');
			
			if (recup.test($("#email").val()) === true){
				$.post("Assets/ajax/ajax_checkEmail.php", { email:$("#email").val() }, function(data)
				{
					if(data.trim()=='no')
					{
						$("#email").css("border", "1px solid red");
						$(".email").html('Email already exist !');
						$("#submit").attr("disabled", "disabled");
						$(".email-field-icon-check").html('<i class="bi bi-x-circle-fill"></i>');
						$(".email-field-icon-check").css('color', 'red');
					}else {
						$("#email").css("border", "1px solid green");
						$("#submit").removeAttr("disabled");
						$(".submit").html("");
						$(".email-field-icon-check").html('<i class="bi bi-patch-check-fill"></i>');
						$(".email-field-icon-check").css('color', '#198754');
					}
				});
			}else {
				$("#email").css("border", "1px solid red");
				$(".email").html("Veuillez saisir une adresse email correcte.");
				$("#submit").attr("disabled", "disabled");
				$(".email-field-icon-check").html('<i class="bi bi-x-circle-fill"></i>');
				$(".email-field-icon-check").css('color', 'red');
				$(".email").fadeIn(500);
			}
		},100);
	});

	/**
	 * Vérifie en AJAX si l'utilisateur n'existe pas déjà (email)
	 */
	$("#email").blur(function()
	{
		$.post("Assets/ajax/ajax_checkEmail.php" ,{ email:$("#email").val() } ,function(data)
		{
			if(data.trim()=='no')
			{
				$("#email").css("border", "1px solid red");
				$(".email").html('Cette adresse email est déjà utilisée ! Veuillez en choisir une autre.').fadeTo(900,1);
				$("#submit").attr("disabled", "disabled");
			}
		});
	});

	$("#password").blur(function(){
		setTimeout(function(){
			var myInput = document.getElementById("password");
			var letter = document.getElementById("letter");
			var capital = document.getElementById("capital");
			var number = document.getElementById("number");
			var length = document.getElementById("length");

			// When the user clicks on the password field, show the message box
			myInput.onfocus = function() {
				document.getElementById("message").style.display = "block";
			}

			// When the user clicks outside of the password field, hide the message box
			myInput.onblur = function() {
				document.getElementById("message").style.display = "none";
			}

			// When the user starts to type something inside the password field
			myInput.onkeyup = function() {
				// Validate lowercase letters
				var lowerCaseLetters = /[a-z]/g;
				if(myInput.value.match(lowerCaseLetters)) {
					letter.classList.remove("invalid");
					letter.classList.add("valid");
					$("#letteri").html('<i class="bi bi-patch-check-fill"></i>')
				} else {
					letter.classList.remove("valid");
					letter.classList.add("invalid");
					$("#letteri").html('<i class="bi bi-x-circle-fill"></i>')
				}


				// Validate capital letters
				var upperCaseLetters = /[A-Z]/g;
				if(myInput.value.match(upperCaseLetters)) {
					capital.classList.remove("invalid");
					capital.classList.add("valid");
					$("#capitali").html('<i class="bi bi-patch-check-fill"></i>')
				} else {
					capital.classList.remove("valid");
					capital.classList.add("invalid");
					$("#capitali").html('<i class="bi bi-x-circle-fill"></i>')
				}

				// Validate numbers
				var numbers = /[0-9]/g;
				if(myInput.value.match(numbers)) {
					number.classList.remove("invalid");
					number.classList.add("valid");
					$("#numberi").html('<i class="bi bi-patch-check-fill"></i>')
				} else {
					number.classList.remove("valid");
					number.classList.add("invalid");
					$("#numberi").html('<i class="bi bi-x-circle-fill"></i>')
				}

				// Validate length
				if(myInput.value.length >= 8) {
					length.classList.remove("invalid");
					length.classList.add("valid");
					$("#lenghti").html('<i class="bi bi-patch-check-fill"></i>')
				} else {
					length.classList.remove("valid");
					length.classList.add("invalid");
					$("#lenghti").html('<i class="bi bi-x-circle-fill"></i>')
				}
				$("#password").blur(function() {
					if (myInput.value.match(lowerCaseLetters)
						&& myInput.value.match(upperCaseLetters)
						&& myInput.value.match(numbers)
						&& myInput.value.length >= 8) {
						$("#password").css("border", "1px solid green");
						$(".psw-field-icon-check").html('<i class="bi bi-patch-check-fill"></i>');
						$(".psw-field-icon-check").css('color', '#198754');
						$(".password").html("");
					} else {
						$("#password").css("border", "1px solid red");
						$(".psw-field-icon-check").html('<i class="bi bi-x-circle-fill"></i>');
						$(".psw-field-icon-check").css('color', 'red');
						$(".password").html("Mot de passe invalide.");
						$("#submit").attr("disabled", "disabled");
					}
				});
			}
		},100);
	});

	$("#password_repeat").blur(function(){
		setTimeout(function(){
			var recup = $("#password_repeat").val();
			var pass = $("#password").val();
			if (recup === pass && pass != false){
				console.log(pass);
				$("#password_repeat").css("border", "1px solid green");
				$("#submit").removeAttr("disabled");
				$(".submit").html("");
				$(".password_repeat").fadeOut(500);
				$(".psw-repeat-field-icon-check").html('<i class="bi bi-patch-check-fill"></i>');
				$(".psw-repeat-field-icon-check").css('color', '#198754');
			}else {
				$("#password_repeat").css("border", "1px solid red");
				$(".password_repeat").html("Les mots de passe ne sont pas indentique.");
				$("#submit").attr("disabled", "disabled");
				$(".password_repeat").fadeIn(500);
				$(".psw-repeat-field-icon-check").html('<i class="bi bi-x-circle-fill"></i>');
				$(".psw-repeat-field-icon-check").css('color', 'red');
			}
		},100);
	});
	
	$(".toggle-password").click(function () {
		$(this).toggleClass("bi bi-eye");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});

	$("#submit").on("click", function() {
		setTimeout(function() {
			if($('#accept-cgu').is(':checked')) {
				$(".accept-cgu-alert").fadeOut(500);
			}else {
				$(".accept-cgu-alert").html("Vous devez accepter nos conditions générales d'utilisation.");
				$(".accept-cgu-alert").fadeIn(500);
			}
		}, 100);
	});
});