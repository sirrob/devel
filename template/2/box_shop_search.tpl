<script type="text/javascript">
var act_rozmiar=0;
function set_rozmiar(id) {
  switch(id) {
    case '10':
    case '83':
      str = '<option value="120">24/32</option><option value="124">24/34</option><option value="119">25/32</option><option value="123">25/34</option>';
      str+= '<option value="121">26/32</option><option value="125">26/34</option><option value="126">27/30</option><option value="122">27/32</option>';
      str+= '<option value="118">27/34</option><option value="34">28/32</option><option value="35">28/34</option><option value="36">29/32</option>';
      str+= '<option value="37">29/34</option><option value="70">30/30</option><option value="38">30/32</option><option value="39">30/34</option>';
      str+= '<option value="71">31/30</option><option value="40">31/32</option><option value="41">31/34</option><option value="80">32/30</option>';
      str+= '<option value="42">32/32</option><option value="43">32/34</option><option value="112">32/36</option><option value="73">33/30</option>';
      str+= '<option value="44">33/32</option><option value="83">33/34</option><option value="77">33/36</option><option value="74">34/30</option>';
      str+= '<option value="46">34/32</option><option value="47">34/34</option><option value="78">34/36</option><option value="75">36/30</option>';
      str+= '<option value="48">36/32</option><option value="49">36/34</option><option value="79">36/36</option><option value="64">36/40</option>';
      str+= '<option value="50">38/32</option><option value="51">38/34</option><option value="52">40/32</option><option value="53">40/34</option>';
      str+= '<option value="54">42/32</option><option value="55">42/34</option>';
      act = 1;
    break;
    case '13':
    case '74':
    case '79':
    case '80':
    case '92':
      str = '<option value="127">25</option><option value="128">26</option><option value="127">27</option><option value="115">28</option>';
      str+= '<option value="116">29</option><option value="117">30</option><option value="106">31</option><option value="107">32</option>';
      str+= '<option value="108">33</option><option value="109">34</option><option value="110">36</option><option value="82">38</option>';
      str+= '<option value="45">39</option><option value="22">40</option><option value="103">40,5</option>';
      str+= '<option value="23">41</option><option value="24">42</option><option value="104">42,5</option><option value="25">43</option>';
      str+= '<option value="26">44</option><option value="102">44,5</option><option value="27">45</option><option value="28">46</option>';
      str+= '<option value="105">47</option>';
      act = 2;
    break;
    default: 
      str = '<option value="L">XS</option><option value="6">S</option><option value="2">M</option><option value="3">L</option>';
      str+= '<option value="4">XL</option><option value="5">XXL</option><option value="113">XXXL</option><option value="130">XXXL</option>';
      act = 0;
  }
  if(act==act_rozmiar) return;
  act_rozmiar=act;
  str = '<select name="sro" id="szsro"  class="txt"><option value="-1" id="szsro0">- {T_WROZMIAR} -</option>'+str+'</select>';
  document.getElementById('wrozmiar').innerHTML = str;
  
}
</script>

<form action="sklep,szukaj.htm" method="get">
<input type="hidden" name="a" value="s" />
<select name="spd" id="szspd" class="txt">
<option value="-1" id="szspd0">- {T_WPRODUCENT} -</option>
{PRODUCENT}
</select>
<select name="ska" id="szska" class="txt" onchange="set_rozmiar(this.options[this.selectedIndex].value);">
<option value="-1" id="szska0">- {T_WKATEGORIA} -</option>
{KATEGORIA}
</select>
<div id="wrozmiar"><select name="sro" id="szsro"  class="txt">
<option value="-1" id="szsro0">- {T_WROZMIAR} -</option>
<option value="L">XS</option><option value="6">S</option><option value="2">M</option><option value="3">L</option>
<option value="4">XL</option><option value="5">XXL</option><option value="113">XXXL</option><option value="130">XXXL</option>
</select></div>

<input type="text" name="sna" maxlength="30" value="{SNA}"  class="txt" />
<input type="submit" name="s_" value="{T_SZUKAJ}" class="push" />
</form>


