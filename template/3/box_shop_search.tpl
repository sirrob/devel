<script type="text/javascript">
var ckatid = {CKATID};
var Purl = '{URL}';
</script>

<!-- wyszukiwarka -->

<!-- <form action="{URL}{LANG}/sklep/szukaj/" method="post" class="form-szukaj jqTransform"> -->
<form action="{URL}{LANG}/wyszukiwarka/" method="get" class="form-szukaj jqTransform">
    <input type="hidden" name="a" value="s" />
    <fieldset>
        <legend>{T_SZ_PRODUKT}</legend>
        <div class="form-element">
            <!-- <select size="1" name="spd" id="szspd" onchange="fszspd(this.value)"> --> <!-- id="szspd"spd class="NFOnChange" -->
            <select size="1" name="brands[]" id="szspd" onchange="fszspd(this.value)">
                <option value="-1" id="szspd0">- {T_WPRODUCENT} -</option>
                {PRODUCENT}
            </select>
        </div>
        <div class="form-element" id="wkategoria"> <!-- onchange="set_rozmiar(this.options[this.selectedIndex].value);" -->
            <!-- <select size="1" name="ska" id="szska" onchange="fszska(this.value)"> -->
            <select size="1" name="kategoria" id="szska" onchange="fszska(this.value)">
                <option value="-1" id="szska0">- {T_WKATEGORIA} -</option>
                {KATEGORIA}
            </select>
        </div>
        <div class="form-element" id="wrozmiar">
            <!-- <select size="1" name="sro" id="szsro" onchange="fszsro(this.value)"> -->
            <select size="1" name="size" id="szsro" onchange="fszsro(this.value)">
                <option value="-1" id="szsro0">- {T_WROZMIAR} -</option>
                {ROZMIAR}
            </select>
        </div>
        <div class="form-element" style="padding-right: 0px;width: 188px;">       

        <input type="text" name="sna" maxlength="30" value="{SNA}" id="sna" class="txt" style="width: 145px;" />
        <!-- <input type="submit" name="s_" value="{T_SZUKAJ}" class="pushx" /> -->
        <input type="image" src="{URL}images/but_search.jpg" name="s_" value="{T_SZUKAJ}" style="float: right">
        </div>
    </fieldset>
</form>
<br>