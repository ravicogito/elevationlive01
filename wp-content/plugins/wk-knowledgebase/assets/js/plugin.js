(function($){
	$(window).on("load",function(){ 
		$("#helpful").click(function(eve){  
			heart = $(this);
			post_id = heart.data("post-id"); 
			var wk_elm=$(".post-loves");
			$.ajax({
				type: "post",
				url: knw_ajax_var.url,
				data: "action=wk_knowledgebase_like&nonce="+knw_ajax_var.nonce+"&post_like=&post_id="+post_id,
				success: function(count){ 
					if(count.trim() != "already")
					{ 	
						$("#not-helpful").empty();
						$(".wk-help a").remove();
						$("#feedback-text").text("Thanks for your feedback!");
						if(wk_elm.length){
							if(count>1)
								wk_elm.text(count+' Likes');
							else
								wk_elm.text(count+' Like');	
						}
					}
					else{ 
						$("#not-helpful").empty();
						$("#feedback-text").val("Thanks for your feedback!");
						if(wk_elm.length){
							wk_elm.text(count+' Likes');	
						}
						
					}
				}
			});
			eve.preventDefault();
					
			return false;
		});

		$("#not-helpful").click(function(eve){  
			not_liked = $(this);					 
			$(".wk-feedback").toggleClass("knw-visible");
			$(".wk-help #not-helpful").addClass("no-selected");	
			eve.preventDefault();
					 
		});

		$(document).on('submit','#query-form' ,function(evt){
			var user_name = $('#user_name').val();
			var user_email = $('#user_email').val();
			var query_subject = $('#query_subject').val();
			var query_message = $('#query_message').val();
			var response = grecaptcha.getResponse();
			if(response.length == 0){
		  		$("#recaptcha").after("<span class='ver-rob'>Please verify that you are not a robot.</span>");
				$(".ver-rob").fadeOut(5000);
			}
			else{
				$.ajax({
					type: "post",
					url: knw_ajax_var.url,
					data: {
						action: 'submit_query_form',
						"nonce": knw_ajax_var.nonce,
						"user_name": user_name,
						"user_email": user_email,
						"query_subject": query_subject,
						"query_message": query_message,
						"captcha": response
					},
					beforeSend: function() {
		                $("div.wait").addClass("active");
		            },
					success: function(html){
						$("div.wait").removeClass("active");
						if(html=='captcha-empty') {
							$(".modal-body").append("<div class='wk-alert-danger text-center alert-dismissible space-top-15' role='alert'>Please check the the captcha form</div>");
                        	$(".wk-alert-danger").delay(2000).fadeOut();
						}
						else if(html=='captcha-fail') {
							$(".modal-body").append("<div class='wk-alert-danger text-center alert-dismissible space-top-15' role='alert'>You are spammer ! You Cannot Spam Post</div>");
                        	$(".wk-alert-danger").delay(2000).fadeOut();
						}
						else if(html=='field-empty') {
							$(".modal-body").append("<div class='wk-alert-danger text-center alert-dismissible space-top-15' role='alert'>Please Fill All The Fields</div>");
                        	$(".wk-alert-danger").delay(2000).fadeOut();
						}
						else if(html=='confirm') {
							$(".modal-body").append("<div class='wk-alert-success text-center alert-dismissible space-top-15' role='alert'>Query Submitted Successfully</div>");                     
                        	$(".wk-alert-success").delay(2000).fadeOut();
						}
						setTimeout(function(){$('#modal-query').hide(); },2500);
						$(".frm-btn").fadeIn(4000);
					}
				});
			}
			evt.preventDefault();		
		});
		

	});	
	

	$(document).ready(function() {
		$(".frm-btn").click(function(e) {
			$("#blind").css("display", "block");
		});

		$("#modal-query .close-modal").click(function(){
			$("#blind").css("display", "none");
		});

	});
	
})(jQuery);