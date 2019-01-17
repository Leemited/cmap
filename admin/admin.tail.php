</div><!--full-width-->
<!--footer-->
<footer>

</footer>
<!--footer-->
<div class="modal">
    <form action="<?php echo G5_URL?>/admin/content_update.php" method="post" name="etcform" id="etcform" enctype="multipart/form-data" onsubmit="return false">
        <input type="hidden" value="" name="content_id" id="content_id">
        <fieldset>
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname1" value="" class="text ui-widget-content ui-corner-all" placeholder="링크명">
            <input type="text" name="link[]" id="link1" value="" class="text ui-widget-content ui-corner-all" placeholder="링크">
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname2" value="" class="text ui-widget-content ui-corner-all" placeholder="링크명">
            <input type="text" name="link[]" id="link2" value="" class="text ui-widget-content ui-corner-all" placeholder="링크">
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname3" value="" class="text ui-widget-content ui-corner-all" placeholder="링크명">
            <input type="text" name="link[]" id="link3" value="" class="text ui-widget-content ui-corner-all" placeholder="링크">
            <label for="file1">참고파일 1</label>
            <input type="file" name="file[]" id="file1" class="text ui-widget-content ui-corner-all" >
            <div class="add_file1"></div>
            <label for="file2">참고파일 2</label>
            <input type="file" name="file[]" id="file2" class="text ui-widget-content ui-corner-all" >
            <div class="add_file2"></div>
            <label for="file3">참고파일 3</label>
            <input type="file" name="file[]" id="file3" class="text ui-widget-content ui-corner-all" >
            <div class="add_file3"></div>
            <label for="name">사례1</label>
            <input type="text" name="etc1name[]" id="etcname1_1" value="" class="text ui-widget-content ui-corner-all" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_1" value="" class="text ui-widget-content ui-corner-all">
            <label for="name">사례2</label>
            <input type="text" name="etc1name[]" id="etcname1_2" value="" class="text ui-widget-content ui-corner-all" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_2" value="" class="text ui-widget-content ui-corner-all">
            <label for="name">사례3</label>
            <input type="text" name="etc1name[]" id="etcname1_3" value="" class="text ui-widget-content ui-corner-all" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_3" value="" class="text ui-widget-content ui-corner-all">
            <label for="files1">사례파일 1</label>
            <input type="file" name="files[]" id="files1" class="text ui-widget-content ui-corner-all" >
            <div class="add_file1"></div>
            <label for="files2">사례파일 2</label>
            <input type="file" name="files[]" id="files2" class="text ui-widget-content ui-corner-all" >
            <div class="add_file2"></div>
            <label for="files3">사례파일 3</label>
            <input type="file" name="files[]" id="files3" class="text ui-widget-content ui-corner-all" >
            <div class="add_file3"></div>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>
<div class="img_modal">
    <fieldset>
        <img src="" alt="">
    </fieldset>
</div>

<div class="submenu_modal">
    <form action="<?php echo G5_URL?>/admin/menu_sub_insert.php" name="sub_menu_form" method="post" id="sub_menu_form" >
        <input type="hidden" name="menu_code" value="" class="menu_code">
        <fieldset>
            <label for="name">서브메뉴명</label>
            <input type="text" name="menu_name" id="menu_name" value="" class="text ui-widget-content ui-corner-all" placeholder="서브메뉴명" required>
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px" value="">
        </fieldset>
    </form>
</div>

<script type="text/javascript" src="<?php echo G5_JS_URL; ?>/jquery.accordion.js"></script><!--아코디언-->
<script type="text/javascript">
    $('.accordion').accordion({
        "transitionSpeed": 400
    });
</script>
<script src="<?php echo G5_JS_URL ?>/jquery-ui.min.js"></script>

<script>
    var dialog,img_dialog,menu_dialog;
    $(function(){
        dialog = $( ".modal" ).dialog({
            autoOpen: false,
            height: 600,
            width: 600,
            modal: true,
            draggable:false,
            buttons: {
                "등록": addEtc,
                Cancel: function() {
                    dialog.dialog( "close" );
                }
            },
            close: function() {
                //form[ 0 ].reset();
                //allFields.removeClass( "ui-state-error" );
            }
        });

        img_dialog = $( ".img_modal" ).dialog({
            autoOpen: false,
            height: 800,
            width: 600,
            modal: true,
            draggable:false,
            buttons: {
                Cancel: function() {
                    img_dialog.dialog( "close" );
                }
            },
            close: function() {
                //form[ 0 ].reset();
                //allFields.removeClass( "ui-state-error" );
            }
        });

        menu_dialog = $( ".submenu_modal" ).dialog({
            autoOpen: false,
            height: 200,
            width: 600,
            modal: true,
            draggable:false,
            title:'서브메뉴 추가',
            buttons: {
                "등록": addMenu,
                Cancel: function() {
                    $(".submenu_modal .menu_code").val('');
                    menu_dialog.dialog( "close" );
                }
            },
            close: function() {
                //form[ 0 ].reset();
                //allFields.removeClass( "ui-state-error" );
            }
        });
    });
    function addEtc(){
        var form = $("#etcform")[0];
        var formData = new FormData(form);
        $.ajax({
            url:g5_url+"/admin/content_update.php",
            processData:false,
            contentType:false,
            type:"POST",
            data: formData,
            dataType:"json",
            success: function(data){
                alert(data.msg);
                $("#"+data.id+" #links").html('');
                $("#"+data.id+" #etc1").html('');
                $("#"+data.id+" #files").html('');

                if(data.links!=""){
                    var addlink = data.links.split(",");
                    var addlinkname = data.linknames.split(",");
                    for(var i = 0 ; i < addlink.length; i++){
                        if(addlink[i] != "") {
                            if(addlinkname[i]!="") {
                                var linkitem = '<a href="' + addlink[i] + '" target="_blank">'+addlinkname[i]+'</a><br>';
                            }else {
                                var linkitem = '<a href="' + addlink[i] + '" target="_blank">링크' + (i + 1) + '</a><br>';
                            }
                            $("#"+data.id+" #links").append(linkitem);
                        }
                    }
                }
                if(data.etc1s!=""){
                    var addetc = data.etc1s.split(",");
                    var addetcname = data.etc1names.split(",");
                    for(var i = 0 ; i < addetc.length; i++){
                        if(addetc[i] != "") {
                            if(addetcname[i] != ""){
                                var etcitem = '<a href="' + addetc[i] + '" target="_blank">'+addetcname[i]+'</a><br>';
                            }else {
                                var etcitem = '<a href="' + addetc[i] + '" target="_blank">사례' + (i + 1) + '</a><br>';
                            }
                            $("#"+data.id+" #etc1").append(etcitem);
                        }
                    }
                }
                if(data.filename){
                    var addfile = data.filename.split(",");
                    for(var i = 0 ; i < addfile.length; i++){
                        if(addfile[i] != "") {
                            var fileitem = '<a href="javascript:fnImage(\'' + addfile[i] + '\')" >파일'+(i+1)+'</a><br>';
                            $("#"+data.id+" #files").append(fileitem);
                        }
                    }
                }
                if(data.filename2){
                    var addfile = data.filename2.split(",");
                    for(var i = 0 ; i < addfile.length; i++){
                        if(addfile[i] != "") {
                            var fileitem = '<a href="javascript:fnImage(\'' + addfile[i] + '\')" >사례파일'+(i+1)+'</a><br>';
                            $("#"+data.id+" #files2").append(fileitem);
                        }
                    }
                }
                //console.log(data.sql);
                dialog.dialog( "close" );
            }
        });
    }
    function addMenu(){

        var form = $("#sub_menu_form")[0];
        var formData = new FormData(form);
        if(form.menu_name.value == ""){
            alert("메뉴명을 입력해주세요.");
            return false;
        }
        $.ajax({
            url: g5_url + "/admin/menu_sub_insert.php",
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function (data) {
                if(data == "1"){
                    location.reload();
                }else if(data=="2" || data=="3"){
                    alert("등록 실패");
                }
            }
        });
    }
</script>
<script src="<?php echo G5_URL ?>/admin/admin.js"></script>

<?php
include_once (G5_PATH."/tail.sub.php");
?>