// (c) 2005 by Ulrik Günther <ulrik@00t.org>
// inspired by 10x10 <www.tenbyten.com>

var linkPrefix = "link";
var linkCount = 6;
var hoverThreshold = 5;

function blurAll() {

	for(var i = 1; i <= linkCount; i++) {
		linkId = linkPrefix + i;
		document.getElementById(linkPrefix + i).setAttribute("class", "grade0");
	}

}

function onLink(elementId) {

	var n = hoverThreshold;

	for(var i = elementId; i >= elementId - hoverThreshold; i--) {
		if(i <= 0) {
			break;
		}
		document.getElementById(linkPrefix + i).setAttribute("class", "grade" + n);
		n--;
		if(n <= 0) {
			break;
		}
	}

	n = hoverThreshold;
	for(var i = elementId; i <= elementId + hoverThreshold; i++) {
		if(i >= linkCount) {
			break;
		}
		document.getElementById(linkPrefix + i).setAttribute("class", "grade" + n);
		n--;
		if(n <= 0) {
			break;
		}
	}
	
	document.getElementById(linkPrefix + elementId).setAttribute("class", "grade5");
}

function offLink(elementId) {
	// doh...
}
