jQuery(document).ready(function($) {

	$(".mp3-link").on('click',function(){
		//data-profileid
	});
	
	$(".play-button").on('click',function(){
		var audioPlayer = document.getElementById('voice-over-player');     
        var sourceUrl = $(this).attr("voicelink");
	    audioPlayer.pause();
	    audioPlayer.src = sourceUrl;
	    audioPlayer.load();//suspends and restores all audio element
	
	    //audio[0].play(); changed based on Sprachprofi's comment below
	    audioPlayer.oncanplaythrough = audioPlayer.play();
	    console.log('request to play');
	    return false;
	});
	
	
	$("ul.media-categories-link li a").on('click',function(){
		$("ul.media-categories-link li a").removeClass("active");
		$(this).addClass("active");
		var classDisplay = $(this).attr('media-cate-id');
		
		//console.log(classDisplay);
		if(classDisplay == 'all'){
			$('.rbprofile-list').show();
			//$('.profile-voiceover ul.links li').show();
		}else{
			$('.rbprofile-list').hide();
			$('.rbprofile-list.'+classDisplay).show();
			//$('.profile-voiceover ul.links li:NOT(.site_link)').hide();
			//$('.profile-voiceover ul.links li.'+classDisplay).show();
		}
		return false;
	});
	
});