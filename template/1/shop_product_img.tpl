<script type="text/javascript">
//Automatyczne dostosowanie rozmiaru okna do wielkosci zdjecia
function m_resize() {
var obj = document.getElementById("pi");

//Tu trzeba dodać odpowiednie wartości
var y=obj.height+196;
var x=obj.width+60;

self.resizeTo(x,y);
}
</script>

<div class="zdj_pow">Zdjęcia produktu</div>
<div class="zdj_d"><img src="{IMG}" alt="" id="pi" onclick="window.close();" /></div>
<div class="strony">{STRONY}</div>
<div class="zamk"><a href="javascript:;" onclick="window.close()">Zamknij okno</a></div>



</script>