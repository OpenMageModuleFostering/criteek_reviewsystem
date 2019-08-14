 function PrintDivOnly(str) {
	   
        var popupWin = window.open('', '_blank', 'width=400,height=400');
        popupWin.document.open();
        popupWin.document.write('<html><body>'+htmlEscape(str)+'</html>');
    }
	function htmlEscape(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}
 function setonoff(str) {
	 new Ajax.Request(str, {
           method: 'post',
           onComplete: function(data) {
			
		$('html-body').innerHTML = data.responseText;
				

           }
       });
	   return false;
    }