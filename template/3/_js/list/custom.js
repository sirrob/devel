function tooltip_go() {
    $('.with-tooltip').tooltip({
        track: true,
        delay: 0,
        showURL: false,
        showBody: "|",
        extraClass: "tooltip",
        fixPNG: true
    });
}

function othermenu(id) {
    if (id == 1) {
        $('#otm1').show('slow');
        $('#otm2').hide('slow');
    } else if (id == 2) {
        $('#otm1').hide('slow');
        $('#otm2').show('slow');
    }
    //alert(id);
}

$(function () {
    // Slider



    $(document).ready(function () {

        $('#slider').slider({
            range: true,
            values: [17, 67]
        });
        
        $('.scroll-pane').jScrollPane({
            horizontalDragMinWidth: 15,
            horizontalDragMaxWidth: 15,
            verticalDragMinHeight: 18,
            verticalDragMaxHeight: 18,
            showArrows: true
        });

        $('#scroll-pane').jScrollPane();

        tooltip_go();

        $(".accordion").accordion();
        othermenu(1);
    });



});