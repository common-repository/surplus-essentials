jQuery(document).ready(function($) {

	$(document).on('click','.expand-faq', function(e){
		e.preventDefault();
		$(this).children('i').toggleClass('fa-toggle-on');
		if(!$('.surplustheme-faq-holder .inner').hasClass('open'))
		{
			$('.surplustheme-faq-holder .inner').addClass('open');
			$('.surplustheme-faq-holder .inner').slideDown('slow');
		}
		else{
			$('.surplustheme-faq-holder .inner').removeClass('open');
			$('.surplustheme-faq-holder .inner').slideUp('slow');
		}
	});
	$('.faq-answer').slideUp();
	$('.toggle').click(function(e) {
	  	e.preventDefault();
	  
	    var $this = $(this);
	  
	    if ($this.hasClass('show')) {
	        $this.removeClass('show');
	        $this.next().slideUp(350);
	    } else {
	        $this.removeClass('show');
	        $this.next().slideUp(350);
	        $this.toggleClass('show');
	        $this.next().slideToggle(350);
	    }
	});
   
    $(".event-start-date").datepicker({dateFormat: 'yy-mm-dd'});
});