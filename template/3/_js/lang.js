$(document).ready(function(){
    var lang = ('{LANG}'==='pl')?0:1 ;
    var links = $('#lang-list').find('li');
    links[lang].style.borderColor = '#a71F23';
    links[lang].children[0].style.color= '#a71F23';
});