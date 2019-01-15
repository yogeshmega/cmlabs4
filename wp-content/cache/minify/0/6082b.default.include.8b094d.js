jQuery.fn.imagesLoaded=function(){var imgs=this.find('img[src!=""]');if(!imgs.length){return jQuery.Deferred().resolve().promise();}
var dfds=[];imgs.each(function(){var dfd=jQuery.Deferred();dfds.push(dfd);var img=new Image();img.onload=function(){dfd.resolve();}
img.onerror=function(){dfd.resolve();}
img.src=this.src;});return jQuery.when.apply(jQuery,dfds);}