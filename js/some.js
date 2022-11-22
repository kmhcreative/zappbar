/*	Share On Mastodon Easily 
	========================
	Script URI:  https://gist.github.com/kmhcreative/ae6816253c3eabafb7338e3b20c9d4b9
	Description: Makes sharing to Mastodon instances as easy as any other social media platform.
	Version: 	 1.0
	Author: 	 K.M. Hansen <software@kmhcreative.com>
	Author URI:  http://www.kmhcreative.com
	License: 	 GPLv3
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	HOW TO USE THIS SCRIPT
	-----------------------
	1.	Create an link or button element in your site with the class name "mastodon"
		(or with whatever query match you define in the Settings below)
		
	2. 	Load this script on your site.
		A.	Any Site: In the <head> of your page code add: 
			<script type="text/javascript" src="path/to/some.js"></script>
			
		B.	WordPress: Add the following to your wp_enqueue_scripts function in your theme's "functions.php" file:	
			wp_enqueue_script( 'share-on-mastodon-easily', 'path/to/some.js', '', '1.0', true );

	Your link/button elements does not have to have an href, the script will get
	the current window location if no usable href is found on the element itself.
	
	Do not encode the URL before sending it to this script, it will result in an
	unusable link on Mastodon.	
*/
var some = some || {};	// to prevent name collisions

/*	Settings */
some['settings'] = {
	queryobj	:	'.mastodon',// query to find mastodon share elements
	titletoo	:	true,		// whether to include the page title and URL
	rememberme	:	true,		// whether to offer to remember instance or not
	skipdialog	:	true,		// whether to skip the instance dialog if remembered
	openapopup	:	true,		// whether to open in a pop-up window, false opens in new tab
};
some['window'] = null;			// used to track if pop-up is closed or not
/*	ZAPPBAR ADDS ITS OWN EVENT LISTENERS
//	Find Share Buttons/Links and attach event listeners
window.onload = function(){
	some['buttons'] = document.querySelectorAll(some.settings.queryobj);
	console.log(some.buttons.length);
	for (var b=0; b < some.buttons.length; b++){
		// see if this actually has an href or not
		if (some.buttons[b].hasAttribute('href') && (some.buttons[b].href.startsWith("https://") || some.buttons[b].href.startsWith("http://"))  ){
			var url = some.buttons[b].href;
		} else {
			var url = window.location.href;
		}
		// add our event listener
		some.buttons[b].addEventListener('click',function(){
				event.preventDefault();	// prevent normal link action
				some.share(url);
				event.stopImmediatePropagation();	// prevent multi-fire bubbling
		});
	}
};
*/
//	Do the sharing bit...
some['share'] = function(url){
	// Get current page URL if none was sent to function
	if (!url){
		url = window.location.href;
	}
	// If get Title Too is set
	if (some.settings.titletoo){
		var title = document.querySelectorAll('meta[property="og:title"]')[0];
		if (!title){
			var title = document.getElementsByTagName('title')[0].innerHTML;
		} else {
			title = title.getAttribute("content");
		}
		var link = encodeURIComponent(title+'\n\n'+url);
	} else {
		var title = '';
		var link = encodeURIComponent(url);
	}
	// Close any existing Mastodon Share Window
	if (some.window && !some.window.closed){
		some.window.close();
	}
	// Create New Share Window or Tab
	if (some.settings.openapopup){
		var params = 'height=488,width=440';
	} else {
		var params = '';
	}
	some.window = window.open('','some.window', params); // size assumes people are logged into Mastodon

	// Build Dialog if necessary or skip it
	if (!localStorage['some_mastodon_instance'] || some.settings.skipdialog == false){
		var instance = localStorage['some_mastodon_instance'];
		if (!instance){ instance = "https://";}
		some.window.document.body.innerHTML = ''; // prevent appends
		var html = '<!DOCTYPE html>'+
		'<html lang="en-US">'+
		'<head><title>Share On Mastodon</title>'+
		'<style>'+
		'body{max-width:450px;padding:0px 20px;margin:0px auto;background-color:#1f232b;font-family:roboto,Arial,sans-serif;font-size:13px;color:#9baec8;}'+
		'h1 {font-size:24px;line-height:1.48;font-weight:600;color:#d9e1e8;padding-bottom:35px;border-bottom:1px solid #303643;text-align:center;}'+
		'svg{height:42px;vertical-align:middle;margin-right:10px;}'+
		'strong {color:#d9e1e8;font-weight:500}'+
		'textarea,input[type="text"] {box-sizing:border-box;resize:vertical;padding:10px;outline:0;width:100%;border:1px solid #282c37;border-radius:4px;background:#131419;color:#d9e1e8;transition:background-color 300ms ease,border 300ms ease;}'+
		'textarea:focus,input:focus{background-color:#1d1f26;border:1px solid #2b90d9;}'+
		'button {display: block;width:45%;border: 0;border-radius: 4px;background: #595aff;color: #fff;font-size: 18px;line-height: inherit;height: auto;padding: 10px;text-decoration: none;text-transform: uppercase;text-align: center;box-sizing: border-box;cursor: pointer;font-weight: 500;outline: 0;margin: 50px 0px;}'+
		'#close {float:left;background:#df405a;}'+
		'#close:hover{background:#e3566d;}'+
		'#share {float:right;background:#595aff;}'+
		'#share:hover{background:#6364ff;}'+
		'footer {border-top:1px solid #303643;padding:1rem 0;width:100%;display:flex;flex-direction:row;align-items:center;justify-content:center;flex-wrap:wrap;}'+
		'form::after{content:\'\';clear:both;display:block;}'+
		'footer section {margin:.5rem 1rem;}'+
		'a {color:#9baec8;}'+
		'</style>'+
		'<scri'+'pt>'+
		'function shareIt(){'+
		'	var title = "'+title+'";'+
		'	var new_instance = document.getElementById("instance").value;'+
		'	if (!new_instance.startsWith("https://") && !new_instance.startsWith("http://")){'+
		'		new_instance = "https://"+new_instance;'+
		'	}'+
		'	if (!new_instance.endsWith("/")){'+
		'		new_instance = new_instance + "/";'+
		'	}';
		if (some.settings.rememberme){
			html += '	if (document.getElementById("remember").checked){'+
					'		localStorage["some_mastodon_instance"] = new_instance;'+
					'	}';
		}
		html += '	window.location = new_instance+"share?text='+link+'";'+
		'}'+
		'</scr'+'ipt>'+
		'</head>'+
		'<body>'+
		'<h1>'+
	'<svg width="313" height="81" viewBox="0 0 313 81" fill="none" xmlns="http://www.w3.org/2000/svg">'+
	'<path d="M72.9455 18.4505C71.8169 9.95203 64.5038 3.24329 55.846 1.95268C54.3811 1.73352 48.8453 0.929932 36.0206 0.929932H35.9245C23.0879 0.929932 20.338 1.73352 18.873 1.95268C10.4433 3.21894 2.75813 9.23367 0.884866 17.8418C-0.00373551 22.0789 -0.0998005 26.7787 0.0683132 31.0888C0.308475 37.274 0.356508 43.4348 0.908881 49.5957C1.29314 53.6867 1.95359 57.7412 2.90223 61.7347C4.67943 69.1131 11.8603 75.2497 18.8971 77.7456C26.4261 80.3512 34.5317 80.7894 42.2888 78.9996C43.1414 78.7927 43.982 78.5614 44.8226 78.2935C46.7079 77.6847 48.9173 77.0029 50.5504 75.8097C50.5745 75.7975 50.5865 75.7732 50.5985 75.7488C50.6105 75.7245 50.6225 75.7001 50.6225 75.6635V69.6975C50.6225 69.6975 50.6225 69.6488 50.5985 69.6245C50.5985 69.6002 50.5745 69.5758 50.5504 69.5636C50.5264 69.5515 50.5024 69.5392 50.4785 69.5271C50.4543 69.5271 50.4304 69.5271 50.4064 69.5271C45.4349 70.7325 40.3316 71.3413 35.2281 71.3291C26.4261 71.3291 24.0605 67.0919 23.3881 65.3387C22.8477 63.8168 22.4995 62.2217 22.3554 60.6146C22.3554 60.5902 22.3554 60.5659 22.3674 60.5415C22.3674 60.5172 22.3914 60.4928 22.4155 60.4806C22.4394 60.4685 22.4634 60.4563 22.4874 60.4441H22.5715C27.4588 61.6373 32.4782 62.2461 37.5096 62.2461C38.7225 62.2461 39.9232 62.2461 41.1361 62.2096C46.1915 62.0634 51.5232 61.8077 56.5065 60.8216C56.6265 60.7972 56.7587 60.7729 56.8667 60.7485C64.7201 59.2144 72.1891 54.4172 72.9455 42.266C72.9697 41.7912 73.0417 37.2497 73.0417 36.7627C73.0417 35.0702 73.582 24.7941 72.9576 18.475L72.9455 18.4505Z" fill="url(#paint0_linear_539_135)"/>'+
	'<path d="M14.8143 23.1993C14.8143 20.7155 16.7717 18.7188 19.1973 18.7188C21.623 18.7188 23.5802 20.7277 23.5802 23.1993C23.5802 25.6709 21.623 27.6799 19.1973 27.6799C16.7717 27.6799 14.8143 25.6709 14.8143 23.1993Z" fill="white"/>'+
	'<path d="M80.0185 27.059V47.66H72.0331V27.6677C72.0331 23.455 70.304 21.3242 66.8336 21.3242C63.003 21.3242 61.0697 23.869 61.0697 28.873V39.8189H53.1444V28.873C53.1444 23.8445 51.2351 21.3242 47.3804 21.3242C43.9222 21.3242 42.1809 23.455 42.1809 27.6677V47.6479H34.2075V27.059C34.2075 22.8583 35.2522 19.51 37.3536 17.0263C39.5271 14.5424 42.373 13.2883 45.8914 13.2883C49.9742 13.2883 53.0723 14.8955 55.1137 18.1098L57.1071 21.5191L59.1004 18.1098C61.1417 14.9077 64.2279 13.2883 68.3226 13.2883C71.841 13.2883 74.6869 14.5547 76.8604 17.0263C78.9618 19.51 80.0065 22.834 80.0065 27.059H80.0185ZM107.493 37.2985C109.15 35.5087 109.931 33.2928 109.931 30.5898C109.931 27.8868 109.138 25.6466 107.493 23.942C105.908 22.1522 103.891 21.2998 101.453 21.2998C99.0153 21.2998 97.01 22.1522 95.413 23.942C93.8279 25.6466 93.0354 27.8868 93.0354 30.5898C93.0354 33.2928 93.8279 35.5331 95.413 37.2985C96.998 39.0031 99.0153 39.8677 101.453 39.8677C103.891 39.8677 105.896 39.0153 107.493 37.2985ZM109.931 14.1163H117.796V47.0634H109.931V43.1794C107.553 46.4059 104.263 48.0009 99.9881 48.0009C95.7132 48.0009 92.4229 46.3572 89.5049 42.9967C86.635 39.6363 85.1821 35.4844 85.1821 30.6142C85.1821 25.7439 86.647 21.6529 89.5049 18.2925C92.4349 14.9321 95.9173 13.2275 99.9881 13.2275C104.059 13.2275 107.553 14.8103 109.931 18.0247V14.1407V14.1163ZM144.262 29.9689C146.58 31.7586 147.733 34.2547 147.673 37.4082C147.673 40.7686 146.52 43.4107 144.142 45.237C141.765 47.0269 138.894 47.94 135.412 47.94C129.132 47.94 124.869 45.298 122.611 40.1111L129.433 35.9593C130.344 38.784 132.35 40.2451 135.412 40.2451C138.222 40.2451 139.615 39.3319 139.615 37.4203C139.615 36.0323 137.789 34.7782 134.067 33.8042C132.662 33.4145 131.498 33.0127 130.597 32.684C129.311 32.1605 128.219 31.5639 127.306 30.8334C125.049 29.0436 123.897 26.6814 123.897 23.6497C123.897 20.4233 124.989 17.8542 127.186 16.0035C129.445 14.092 132.193 13.1787 135.484 13.1787C140.731 13.1787 144.562 15.4799 147.072 20.1554L140.371 24.1002C139.399 21.8599 137.741 20.7398 135.484 20.7398C133.106 20.7398 131.953 21.6529 131.953 23.4427C131.953 24.8308 133.779 26.0849 137.501 27.059C140.371 27.7164 142.628 28.7026 144.262 29.9689H144.274H144.262ZM169.263 22.2739H162.37V35.9836C162.37 37.6273 162.983 38.6257 164.147 39.0763C165 39.405 166.705 39.4658 169.275 39.3441V47.0512C163.979 47.7087 160.137 47.1729 157.88 45.4075C155.622 43.7029 154.529 40.5251 154.529 35.9958V22.2739H149.233V14.1041H154.529V7.45625L162.394 4.8872V14.1163H169.287V22.2861H169.275L169.263 22.2739ZM194.336 37.1038C195.921 35.3992 196.713 33.2197 196.713 30.5777C196.713 27.9355 195.921 25.7805 194.336 24.0515C192.738 22.347 190.793 21.4825 188.416 21.4825C186.038 21.4825 184.093 22.3348 182.495 24.0515C180.971 25.8413 180.178 27.9965 180.178 30.5777C180.178 33.1588 180.971 35.314 182.495 37.1038C184.081 38.8083 186.038 39.6728 188.416 39.6728C190.793 39.6728 192.738 38.8206 194.336 37.1038ZM176.961 42.9602C173.85 39.5998 172.325 35.521 172.325 30.5777C172.325 25.6343 173.85 21.6165 176.961 18.256C180.07 14.8955 183.913 13.1909 188.416 13.1909C192.918 13.1909 196.774 14.8955 199.872 18.256C202.97 21.6165 204.567 25.7683 204.567 30.5777C204.567 35.387 202.97 39.5998 199.872 42.9602C196.761 46.3207 192.979 47.9643 188.416 47.9643C183.853 47.9643 180.058 46.3207 176.961 42.9602ZM230.864 37.2864C232.449 35.4966 233.242 33.2807 233.242 30.5777C233.242 27.8747 232.449 25.6343 230.864 23.9298C229.28 22.1399 227.263 21.2877 224.824 21.2877C222.387 21.2877 220.369 22.1399 218.725 23.9298C217.139 25.6343 216.347 27.8747 216.347 30.5777C216.347 33.2807 217.139 35.521 218.725 37.2864C220.381 38.991 222.447 39.8554 224.824 39.8554C227.203 39.8554 229.268 39.0031 230.864 37.2864ZM233.242 0.917969H241.107V47.0512H233.242V43.1672C230.925 46.3937 227.634 47.9887 223.36 47.9887C219.085 47.9887 215.747 46.3451 212.804 42.9845C209.934 39.6241 208.482 35.4723 208.482 30.602C208.482 25.7318 209.946 21.6408 212.804 18.2804C215.722 14.9198 219.264 13.2153 223.36 13.2153C227.454 13.2153 230.925 14.7981 233.242 18.0125V0.930144V0.917969ZM268.738 37.0672C270.323 35.3626 271.116 33.1833 271.116 30.5411C271.116 27.8991 270.323 25.7439 268.738 24.015C267.153 22.3105 265.208 21.446 262.818 21.446C260.429 21.446 258.496 22.2983 256.898 24.015C255.373 25.8049 254.58 27.9599 254.58 30.5411C254.58 33.1224 255.373 35.2774 256.898 37.0672C258.484 38.7719 260.441 39.6363 262.818 39.6363C265.196 39.6363 267.141 38.784 268.738 37.0672ZM251.362 42.9237C248.265 39.5632 246.727 35.4844 246.727 30.5411C246.727 25.5979 248.253 21.5799 251.362 18.2195C254.472 14.859 258.315 13.1544 262.818 13.1544C267.321 13.1544 271.176 14.859 274.274 18.2195C277.383 21.5799 278.969 25.7318 278.969 30.5411C278.969 35.3505 277.383 39.5632 274.274 42.9237C271.164 46.2841 267.381 47.9279 262.818 47.9279C258.255 47.9279 254.46 46.2841 251.362 42.9237ZM313 26.7789V47.0146H305.135V27.8382C305.135 25.6587 304.594 24.015 303.478 22.7732C302.445 21.6529 300.98 21.0685 299.094 21.0685C294.652 21.0685 292.394 23.7715 292.394 29.2383V47.0269H284.529V14.1041H292.394V17.8055C294.279 14.7128 297.281 13.1909 301.472 13.1909C304.822 13.1909 307.572 14.372 309.71 16.8071C311.908 19.2422 313 22.5418 313 26.8154" fill="white"/>'+
	'<defs>'+
	'<linearGradient id="paint0_linear_539_135" x1="36.621" y1="0.929932" x2="36.621" y2="80.0712" gradientUnits="userSpaceOnUse">'+
	'<stop stop-color="#6364FF"/>'+
	'<stop offset="1" stop-color="#563ACC"/>'+
	'</linearGradient>'+
	'</defs>'+
	'</svg><br/>'+
		'Share On Mastodon</h1>'+
		'<form>'+
		'<p><label for="instance">Your Instance URL:</label>'+
		'<input type="text" id="instance" value="'+instance+'" required /><br/>';
		if (some.settings.rememberme){
			html += '<input type="checkbox" id="remember"/> Remember my instance on this website</p>';
		}
		html += '<button id="close" onclick="self.close();">Close</button>'+
		'<button id="share" onclick="event.preventDefault();shareIt();">Share!</button>'+
		'</form>'+
		'<footer>'+
		'<section><a href="https://joinmastodon.org" target="_blank">What is Mastodon?</a></section>'+
		'<section><a href="https://github.com/mastodon/mastodon" target="_blank">Mastodon on GitHub</a></section>'+
		'</footer>'+
		'</body></html>';
		// write HTML into popup dialog
		some.window.document.write(html);
	} else {
		var instance = localStorage['some_mastodon_instance'];
		// check that stored instance has protocol
		if (!instance.startsWith("https://") && !instance.startsWith("http://")){
			instance = "https://"+instance;
		}
		// fix trailing slash
		if (!instance.endsWith("/")){
			instance = instance + "/";
		}
		// Send pop-up window directly to instance login or publish
		some.window.location = instance+"share?text="+link;
	}
}