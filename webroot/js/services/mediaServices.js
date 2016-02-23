/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/

(function() {
	angular.module('flu')
		.factory('mediaFactory', mediaFactory);
		
	function mediaFactory() {
		var factory = {
			getIntroVideo: getIntroVideo,
			//loadJWPlayer: loadJWPlayer,
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
		
		/*function loadJWPlayer(elementId, video, width, aspectratio) {
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
		}*/
	}
})();