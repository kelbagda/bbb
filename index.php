<?php include($_SERVER['REAL_DOCUMENT_ROOT'].'/header.php'); ?>

<script type="text/javascript">
 $(document).ready(function(){
	$('form#register a#submit_form').click(function(e){
		// prevent default link action/redirect
		e.preventDefault();
		$('div#result_msgs').empty();

		var errcount = 0;
		
		var formIndex = $('form#register');
		
		$('input[name$="[password]"]', formIndex).each(function() {
			if (($(this).val().length < 6) || ($(this).val().length > 22)) {
				$('div#result_msgs').append('<div style="color:#d90000;">Your password must contain 6-22 characters.</div>');
				errcount++;
			}
		});
		$('input#cpassword', formIndex).each(function() {
			if ($(this).val() != $('input[name$="[password]"]', formIndex).val()) {
				$('div#result_msgs').append('<div style="color:#d90000;">Password mismatch.</div>');
				errcount++;
			}
		});
		$('input#agreement', formIndex).each(function() {
			if (!$(this).prop('checked')) {
				$('div#result_msgs').append('<div style="color:#d90000;">You must accept our Terms of Service.</div>');
				errcount++;
			}
		});
		$('input[name$="[email_addr]"]', formIndex).each(function() {
			validate_email(errcount,$(this));
		});
	});
	$('form#register').submit(function(e){
		// prevent default link action/redirect
		e.preventDefault();
		
		$.ajax ({
			type: 'POST',
			url: 'register.php',
			data: $(this).serialize(),
			success: function(result){
				if (result == 1) {
					$('form#register')[0].reset();
					$('div#result_msgs').append('<div style="color:#48d900;">New account created successfully!</div>');
				}else if (result == 0) $('div#result_msgs').append('<div style="color:#d90000;">Failed to create new account!</div>');
				else $('div#result_msgs').append(result);
			}
		});
	});
 });

//function to check email address availability
function validate_email(errcount, emailIndex){
	if (emailIndex.val().length > 0) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if ((emailIndex.val().length > 256) || (!emailReg.test(emailIndex.val()))) $('div#result_msgs').append('<div style="color:#d90000;">Invalid email address.</div>');
		else {
			//use ajax to run the check
			$.post("validate-email.php", { email_addr: emailIndex.val() },
			function(result) {
				if (result == 1) $('form#register').submit();
				else if (result == 0) $('div#result_msgs').append('<div style="color:#d90000;">An account already exists with this email address.</div>');
				else $('div#result_msgs').append(result);
			});
		}
	}
}
</script>

<div id="sign-up-cont" class="shadow1 text-center text1 gradient">
 <span class="text2">Sign up - it's EASY and FREE!</span>
 <div class="spacer1"></div>
 <form id="register" method="post" autocomplete="off">
	 <input class="text1 input" type="text" name="field[email_addr]" placeholder="Email"/><br />
	 <input class="text1 input" type="password" name="field[password]" placeholder="Password"/><br />
	 <input class="text1 input" type="password" id="cpassword" placeholder="Confirm Password"/><br />
	 <input type="checkbox" id="agreement"/> I accept the <a href="#">terms of service</a>.<br />
	 <a id="submit_form" class="register gradient2 text1 gradient" href="#">Create your new account</a>
 </form>
 <div id="result_msgs"></div>
 <div class="spacer1"></div>
 Or sign up with:<br />
 <a class="facebook" href="#"></a><a class="google" href="#"></a><a class="twitter" href="#"></a>
</div>

<?php include($_SERVER['REAL_DOCUMENT_ROOT'].'/footer.php'); ?>