
/***!  /media/plg_captcha_recaptcha/js/recaptcha.min.js?78ba29a618aad5f6f8c8e4266984e70b  !***/

((c,s)=>{c.JoomlainitReCaptcha2=()=>{const o=[].slice.call(s.getElementsByClassName("g-recaptcha")),r=["sitekey","theme","size","tabindex","callback","expired-callback","error-callback"];o.forEach(t=>{let a={};t.dataset?a=t.dataset:r.forEach(e=>{const i=`data-${e}`;t.hasAttribute(i)&&(a[e]=t.getAttribute(i))}),t.setAttribute("data-recaptcha-widget-id",c.grecaptcha.render(t,a))})}})(window,document);
