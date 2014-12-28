	/***********************************************
	* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
	* http://www.dynamicdrive.com/dynamicindex1/ddsmoothmenu.htm
	***********************************************/
		
var ddsmoothmenu = {
	/* Specify full URL to down and right arrow images (23 is padding-right added to top level LIs with drop downs): */
	arrowimages: {down:['downarrowclass', '/webb/web2/images/theme/down.gif', 23], right:['rightarrowclass', '/webb/web2/images/tool/right.gif']},
	transition: {overtime:300, outtime:300}, /* /duration of slide in/ out animation, in milliseconds */
	shadow: {enable:false, offsetx:5, offsety:5},
	
	/* Stop configuring beyond here */
	detectwebkit: navigator.userAgent.toLowerCase().indexOf("applewebkit")!=-1, /*/detect WebKit browsers (Safari, Chrome etc) */
	detectie6: document.all && !window.XMLHttpRequest,
	getajaxmenu:function($, setting){ /* function to fetch external page containing the panel DIVs */
		var $menucontainer=$('#'+setting.contentsource[0]); /* reference empty div on page that will hold menu */
		$menucontainer.html("Loading Menu...");
		$.ajax({
			url: setting.contentsource[1], /* path to external menu file */
			async: true,
			error:function(ajaxrequest){
				$menucontainer.html('Error fetching content. Server Response: '+ajaxrequest.responseText);
			},
			success:function(content){
				$menucontainer.html(content);
				ddsmoothmenu.buildmenu($, setting);
			}
		})
	},

	buildmenu:function($, setting)
	{
		var smoothmenu=ddsmoothmenu;
		var $mainmenu=$("#"+setting.mainmenuid+">ul"); /* reference main menu UL */
		/*$mainmenu.parent().get(0).className=setting.classname || "ddsmoothmenu";*/
		var $headers=$mainmenu.find("ul").parent();
		$headers.hover(
			function(e){
				$(this).children('a:eq(0)').addClass('selected');
			},
			function(e){
				$(this).children('a:eq(0)').removeClass('selected');
			}
		)
		$headers.each(function(i){ /* loop through each LI header */
			var $curobj=$(this).css({zIndex: 100-i}); /* reference current LI header */
			var $subul=$(this).find('ul:eq(0)').css({display:'block'});
			this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()};
			this.istopheader=$curobj.parents("ul").length==1? true : false; /*/is top level header?   */
			$subul.css({top:this.istopheader && setting.orientation!='v'? this._dimensions.h+"px" : 0});
			$curobj.children("a:eq(0)").css(this.istopheader? {paddingRight: smoothmenu.arrowimages.down[2]} : {}).append( /* add arrow images  */
				'<img src="'+ (this.istopheader && setting.orientation!='v'? smoothmenu.arrowimages.down[1] : smoothmenu.arrowimages.right[1])
				+'" class="' + (this.istopheader && setting.orientation!='v'? smoothmenu.arrowimages.down[0] : smoothmenu.arrowimages.right[0])
				+ '" style="border:0;" />'
			);
			$curobj.hover(
				function(e){	
					var $targetul=$(this).children("ul:eq(0)");
					this._offsets={left:$(this).offset().left, top:$(this).offset().top};           // pofix ?
					var menuleft=this.istopheader && setting.orientation!='v'? 0 : this._dimensions.w - 100;
					menuleft=(this._offsets.left+menuleft+this._dimensions.subulw>$(window).width())? (this.istopheader && setting.orientation!='v'? -this._dimensions.subulw+this._dimensions.w : -this._dimensions.w) : menuleft; /* calculate this sub menu's offsets from its parent */
					if ($targetul.queue().length<=1){ /* if 1 or less queued animations */
						$targetul.css({left:menuleft+"px", width:'160px'}).animate({height:'show',opacity:'show'}, ddsmoothmenu.transition.overtime);
					}
				},
				function(e){
					var $targetul=$(this).children("ul:eq(0)");
					$targetul.animate({height:'hide', opacity:'hide'}, ddsmoothmenu.transition.outtime);
					if (smoothmenu.shadow.enable){
						if (ddsmoothmenu.detectwebkit){ /* in WebKit browsers, set first child shadow's opacity to 0, as "overflow:hidden" doesn't work in them */
							this.$shadow.children('div:eq(0)').css({opacity:0});
						}
						this.$shadow.css({overflow:'hidden'}).animate({height:0}, ddsmoothmenu.transition.outtime);
					}
				}
			); /* end hover */
		}) /* end $headers.each() */
		$mainmenu.find("ul").css({display:'none', visibility:'visible'});
	},

	init:function(setting) 
	{
		if (typeof setting.customtheme=="object" && setting.customtheme.length==2){ /* override default menu colors (default/hover) with custom set? */
			var mainmenuid='#'+setting.mainmenuid;
			var mainselector=(setting.orientation=="v")? mainmenuid : mainmenuid+', '+mainmenuid;
			document.write('<style type="text/css">\n'
				+mainselector+' ul li a {background:'+setting.customtheme[0]+';}\n'
				+mainmenuid+' ul li a:hover {background:'+setting.customtheme[1]+';}\n'
			+'</style>');
		}
		this.shadow.enable=(document.all && !window.XMLHttpRequest)? false : this.shadow.enable; /* in IE6, always disable shadow */
		jQuery(document).ready(function($){ /* ajax menu? */
			if (typeof setting.contentsource=="object"){ /* if external ajax menu */
				ddsmoothmenu.getajaxmenu($, setting);
			}
			else{ /* else if markup menu */
				ddsmoothmenu.buildmenu($, setting);
			}
		})
	}
};