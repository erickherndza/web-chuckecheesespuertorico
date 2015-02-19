$(function(){

    $("ul#menu li.dropdown").hover(
    	function () { $(this).find("ul.dropdown-menu").stop(true, true).slideDown("fast"); },
    	function () { $(this).find("ul.dropdown-menu").stop(true, true).slideUp("fast"); }
 	);

}); 