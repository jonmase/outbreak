(function() {
	angular.module('flu')
		.factory('mediaFactory', mediaFactory);
		
	function mediaFactory() {
		var factory = {
			getIntroVideo: getIntroVideo,
			loadJWPlayer: loadJWPlayer,
		}
		return factory;
		
		function getIntroVideo() {
			/*var introVideo = {
				file: '../../videos/nerve.mp4',
				image: '../../videos/nerveImage.jpg',
			}*/
			var introVideo = 'https://www.youtube.com/embed/78m9Y_armGM?rel=0';
			return introVideo;
		}
		
		function loadJWPlayer(elementId, video, width, aspectratio) {
			if(!width) { width = '100%'; }
			if(!aspectratio) { aspectratio = '16:9'; }
			var playerInstance = jwplayer(elementId);
			playerInstance.setup({
				//file: "../../videos/nerve.mp4",
				//image: "../../videos/nerveImage.jpg",
				file: video.file,
				image: video.image,
				width: width,
				aspectratio: aspectratio,
				//title: 'Basic Video Embed',
				//description: 'A video with a basic title and description!'
			});
		}
	}
})();