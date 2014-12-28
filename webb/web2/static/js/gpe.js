/*
Graculini's Popup Engine - GPE v1.0
Copyright? Gracjan Lech 2003.
Contact me: gracjan.lech@poczta.fm
This notice must remain untouched at all times.

*/
$(document).ready(
	function(){	
		popup=document.getElementById("popup_okno"); 
		OP=NS=IE=false
		if (navigator.userAgent.indexOf("Opera")!=-1) OP=true
		else if (navigator.appName=="Netscape") NS=true
		else if (navigator.appName=="Microsoft Internet Explorer") IE=true
	}
);
/* document.write("<div id=\"popup_okno\" class=\"popup_user\"> Seky User System </div>") */

function displayConfig() {
	popupOffsetX=20
	popupOffsetY=20
	popupAlign="center" /*Inne opcje - left, center */
	popupWidth=170
	popupBorderColor="#CCCCCC"
	popupOpacity=80
	captionBackgroundColor=popupBorderColor
	captionBackgroundImage=""
	captionBackgroundImageProperties=""
	textBackgroundImage=" "
	textBackgroundImageProperties=""
	
	/*fadeIn  */
	if ((fadeIn=params.indexOf("fadeIn"))!=-1)
		fadeIn=true
	/*Domy?lny czas fadeIn */
	if (fadeIn && ((fadeInTime=getValue("fadeInTime"))==NA))
		fadeInTime=1
	/*fadeOut  */
	if ((fadeOut=params.indexOf("fadeOut"))!=-1)
		fadeOut=true
	/*Domy?lny czas fadeOut */
	if (fadeOut && ((fadeOutTime=getValue("fadeOutTime"))==NA))
		fadeOutTime=1

	/*DODATKOWE SZATY */
	layout[1]="textBackgroundColor: #ffffe1; popupBorderColor: #000000; textFontFamily: verdana; textFontSize: 11px;"
	layout[2]="textFontStyle: italic;"

}
function gpe(arguments) {
	fadingIn=false
	fadeOut=false
	params=arguments
	layout=new Array()
	text=getValue("text")
	NA=null
	displayConfig()
	if (popupBorderColor==NA && captionBackgroundColor!=NA) popupBorderColor=captionBackgroundColor
	po=eval(popupOpacity)
	if (NS) NSpo=(po/100)
	if (textBackgroundImage!=null) textBackgroundImage="url('"+textBackgroundImage+"') "+textBackgroundImageProperties+""
	if (captionBackgroundImage!=null) captionBackgroundImage="url('"+captionBackgroundImage+"') "+captionBackgroundImageProperties+""
	popupContent="<table cellpadding=0 cellspacing=0 class='a'>" /* style=\"width: "+popupWidth+";\"   */
	popupContent+="<tr><td align=\"center\" class=\"b\">";
	popupContent+= text;
	popupContent+="</td></tr></table>";
	if (NS) {
		content=document.createRange();
		content.setStartBefore(popup);
		content=content.createContextualFragment(popupContent);
		with (popup) {
			if (hasChildNodes()) removeChild(lastChild)
				appendChild(content)
		}
	}
	else popup.innerHTML=popupContent
		pO(po)
	if (IE) gpePos()
		document.onmousemove=function gpePos(evt) {
	        if (NS) {
		        coordX=evt.pageX
		        coordY=evt.pageY
		        winWidth=innerWidth
	        }
	        else {
		        coordX=event.clientX+document.body.scrollLeft
		        coordY=event.clientY+document.body.scrollTop
		        winWidth=document.body.clientWidth
	        }
	        switch (popupAlign) {
		        case "left" :
			        if (coordX-popupWidth-popupOffsetX<0) coordX=popupWidth+popupOffsetX
			        coordX-=popupWidth+(popupOffsetX*2)
			        break
		        case "center" :
			        if (coordX+popupWidth/2>winWidth) {coordX=winWidth-popupWidth/2; popupOffsetX=0}
			        if (coordX-popupWidth/2<0) {coordX=popupWidth/2; popupOffsetX=0}
			        coordX-=popupWidth/2+popupOffsetX
			        break
		        case "right" :
			        if (coordX+popupWidth+popupOffsetX>winWidth) coordX=winWidth-popupWidth-popupOffsetX
	        }
            $(popup).css("left", coordX+popupOffsetX );
		    $(popup).css("top", coordY+popupOffsetY );
	}
	popup.style.display="block"
	if (fadeIn==true) {
		pO(0)
		fade(1)
	}
	popup.style.display="block"
	document.onmouseout=function() {
		if (fadeOut==true) {
			if (fadeIn) fadeIn=false
			else pO(po)
			fade(2)
		}
		else popup.style.display="none"
	}
}
function getValue(keyWord) {
	with (params) {
		if (indexOf(keyWord+":")==-1) return
		keyInd=lastIndexOf(keyWord+":")
		value=substring(keyInd+(keyWord.length+1),indexOf(";",keyInd))
	}
	while (value.indexOf("|:|")!=-1) {
		with (value) {
			value=substring(0,indexOf("|:|"))+";"+substring(indexOf("|:|")+3)
		}
	}
	while (value.indexOf(" ")!=-1 && keyWord!="text" && keyWord!="caption") {
		with (value) {
			value=substring(0,indexOf(" "))+substring(indexOf(" ")+1)
		}
	}
	if (keyWord=="popupOffsetX" || keyWord=="popupOffsetY" || keyWord=="popupWidth") {
		value=eval(value)
	}
	return value
}
function fade(num) {
	if (num==1 && fadeIn==true) {
		if (pO()!=po) {
			pO(2,"+")
			fadingIn=setTimeout("fade(1)",fadeInTime)
		} else fadeIn=false
	}
	if (num==2 && fadeOut==true) {
		if (pO()!=0) {
			pO(2,"-")
			fadingOut=setTimeout("fade(2)",fadeOutTime)
		} else {
			fadeOut=false
			if (NS==false && IE==false) popup.style.display="none"
		}
	}
}
function pO(oL,op) {
	if (IE) {
		with (popup.filters.alpha) {
			if (oL==NA) return opacity
			if (op==NA) opacity=oL
			if (op=="+") {
				if (opacity>=po) opacity=po
				else opacity+=oL
			}
			if (op=="-") opacity-=oL
		}
	} else if (NS) {
		with (popup.style) {
			if (oL==NA) return MozOpacity
			else oL=(oL/100)
			if (op==NA) MozOpacity=oL
			if (op=="+") {
				if (MozOpacity>=NSpo) 
					MozOpacity=NSpo
				else MozOpacity=parseFloat(MozOpacity)+oL
			}
			if (op=="-") MozOpacity=parseFloat(MozOpacity)-oL
		}
	}
	else return false
}