var animateX = -20;
var animateInterval = 24;

var currentPage = null;
var currentDialog = null;
var currentWidth = 0;
var currentHash = location.hash;
var hashPrefix = "#_";
var pageHistory = [];

var ORIENT_PROFILE = "profile";
var ORIENT_LANDSACPE = "landscape";

//functions: function(layout)
//example:
//	orientChangeListeners[0] = function(a){ alert(a) };
var orientChangeListeners = [];

function checkOrientAndLocation() {
	if (window.innerWidth != currentWidth) {
		currentWidth = window.innerWidth;
		
		var orient = currentWidth == 320 ? ORIENT_PROFILE : ORIENT_LANDSACPE;
		document.body.setAttribute("orient", orient);
		
		//fireoff any orient change listeners
		for(var i=0; i<orientChangeListeners.length; i++) {
			orientChangeListeners[i](orient);
		}
	}
	
	/* */
	if (location.hash != currentHash) {
		currentHash = location.hash;
		
		var pageId = currentHash.substr(hashPrefix.length);
		var page = document.getElementById(pageId);
		
		if (page) {
			var index = pageHistory.indexOf(pageId);
			var backwards = index != -1;
			
			if (backwards)
				pageHistory.splice(index, pageHistory.length);
				
			showPage(page, backwards);
		}
	} 
}

function runOrientorIfNeeded() {
	if ( typeof check_orient_running == "undefined" )
		check_orient_running = setInterval(checkOrientAndLocation, 500);
}

function showPage(page, backwards) {
	if (currentDialog) {
		currentDialog.removeAttribute("selected");
		currentDialog = null;
	}

	if (page.className.indexOf("dialog") != -1) {
		showDialog(page);
	} else {
		location.href = currentHash = hashPrefix + page.id;
		pageHistory.push(page.id);
		
		var fromPage = currentPage;
		currentPage = page;

		var pageTitle = document.getElementById("pageTitle");
		pageTitle.innerHTML = page.title || "";

		var homeButton = document.getElementById("homeButton");
		if (homeButton) {
			homeButton.style.display = ("#"+page.id) == homeButton.hash ? "none" : "inline";
		}

		if (fromPage) {
			setTimeout(swipePage, 0, fromPage, page, backwards);
		}
	}
	
	runOrientorIfNeeded();
}

function swipePage(fromPage, toPage, backwards) {
	toPage.style.left = "100%";
	toPage.setAttribute("selected", "true");
	scrollTo(0, 1);

	var percent = 100;
	var timer = setInterval(function() {
		percent += animateX;
		if (percent <= 0) {
			percent = 0;
			fromPage.removeAttribute("selected");
			clearInterval(timer);
		}

		fromPage.style.left = (backwards ? (100-percent) : (percent-100)) + "%"; 
		toPage.style.left = (backwards ? -percent : percent) + "%"; 
	}, animateInterval);
}

function showDialog(form) {
	currentDialog = form;
	form.setAttribute("selected", "true");

	form.onsubmit = function(event) {
		//event.preventDefault();
		form.removeAttribute("selected");

		/* var index = form.action.lastIndexOf("#");
		if (index != -1)
			showPage(document.getElementById(form.action.substr(index+1)));
		*/
	}
	
	form.onclick = function(event) {
		if (event.target == form)
			form.removeAttribute("selected");
	}
}

/////////////////////////////////////////////////////////////
orientChangeListeners[orientChangeListeners.length] = function(orient) {
	//quick hack to better support the "...""
	var atags = document.getElementsByTagName("A");
	
	var cliplen = CLIP_LENGTH;
	if (orient == ORIENT_LANDSACPE) {
		cliplen = CLIP_LENGTH_LONG;
	}
	for(var i=0; i<atags.length; i++) {
		if(atags.item(i).className == "todoItemText"){
			//these are defined in GlueyNotes.js
			atags.item(i).innerHTML = __displayItemTitle(atags.item(i).title, cliplen);
		}
	}
}

orientChangeListeners[orientChangeListeners.length] = function(orient) {
	var iid = document.getElementById("itemInfoDisplay");
	if (orient == ORIENT_LANDSACPE) {
		iid.style.width = "450px";
	} else {
		iid.style.width = "285px";
	}
}
