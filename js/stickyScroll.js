window.onscroll = function() {
	setSticky();
};
window.onbeforeunload = function() {
	window.scrollTo(0,0);
}
window.onload = function() {
	for(var i = 0; i < 7; i++){
		document.getElementById("nav_ar").children[i].style.minWidth = (document.getElementById("nav_ar_o").children[i].clientWidth - 10) + "px";
	}
	for(var i = 0; i < 8; i++){
		document.getElementById("nav_co").children[i].style.minWidth = (document.getElementById("nav_co_o").children[i].clientWidth - 10) + "px";
	}
	for(var i = 0; i < 8; i++){
		document.getElementById("nav_bo").children[i].style.minWidth = (document.getElementById("nav_bo_o").children[i].clientWidth - 10) + "px";
	}
	if(document.getElementById("loadAnimWrapper")){
		document.getElementById("loadAnimWrapper").style = "display:none;";
	}
}
var nAr = document.getElementById("nav_ar");
var stickyAr = document.getElementById("nav_ar_o").getBoundingClientRect().top + document.getElementById("wpadminbar").style.height;
var stickyArEnd = document.getElementById("nav_ar_e").getBoundingClientRect().top + 40;
var nCo = document.getElementById("nav_co");
var stickyCo = document.getElementById("nav_co_o").getBoundingClientRect().top + document.getElementById("wpadminbar").style.height;
var stickyCoEnd = document.getElementById("nav_co_e").getBoundingClientRect().top + 40;
var nBo = document.getElementById("nav_bo");
var stickyBo = document.getElementById("nav_bo_o").getBoundingClientRect().top + document.getElementById("wpadminbar").style.height;
var stickyBoEnd = document.getElementById("nav_bo_e").getBoundingClientRect().top + 40;

function setSticky(){
	if (window.pageYOffset >= stickyAr) {
		if(window.pageYOffset >= stickyArEnd) {
			nAr.classList.remove("sticky");
			nAr.classList.add("hidden");
		} else {
			nAr.classList.add("sticky");
			nAr.classList.remove("hidden");
		}
	} else {
		nAr.classList.remove("sticky");
		nAr.classList.add("hidden");
	}
	if(window.pageYOffset >= stickyCo) {
		if(window.pageYOffset >= stickyCoEnd) {
			nCo.classList.remove("sticky");
			nCo.classList.add("hidden");
		} else {
			nCo.classList.add("sticky");
			nCo.classList.remove("hidden");
		}
	} else {
		nCo.classList.remove("sticky");
		nCo.classList.add("hidden");
	}
	if(window.pageYOffset >= stickyBo) {
		if(window.pageYOffset >= stickyBoEnd) {
			nBo.classList.remove("sticky");
			nBo.classList.add("hidden");
		} else {
			nBo.classList.add("sticky");
			nBo.classList.remove("hidden");
		}
	} else {
		nBo.classList.remove("sticky");
		nBo.classList.add("hidden");
	}
}