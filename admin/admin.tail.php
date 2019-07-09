</div><!--full-width-->
<!--footer-->
<footer>

</footer>
<!--footer-->
<div class="modal">
    <form name="etcform" id="etcform" enctype="multipart/form-data" onsubmit="return false">
        <input type="hidden" value="" name="content_id" id="content_id">
        <fieldset>
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname1" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="링크명">
            <input type="text" name="link[]" id="link1" value="" class="text ui-widget-content ui-corner-all grid_70" placeholder="링크">
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname2" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="링크명">
            <input type="text" name="link[]" id="link2" value="" class="text ui-widget-content ui-corner-all grid_70" placeholder="링크">
            <label for="name">참고링크</label>
            <input type="text" name="linkname[]" id="linkname3" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="text" name="link[]" id="link3" value="" class="text ui-widget-content ui-corner-all grid_70" placeholder="링크">
            <label for="file1">참고파일 1</label>
            <input type="text" name="filenames[]" id="filename1" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="file[]" id="file1" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_file1"></div>
            <label for="file2">참고파일 2</label>
            <input type="text" name="filenames[]" id="filename2" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="file[]" id="file2" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_file2"></div>
            <label for="file3">참고파일 3</label>
            <input type="text" name="filenames[]" id="filename3" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="링크명">
            <input type="file" name="file[]" id="file3" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_file3"></div>
            <!--<label for="name">사례1</label>
            <input type="text" name="etc1name[]" id="etcname1_1" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_1" value="" class="text ui-widget-content ui-corner-all grid_70">
            <label for="name">사례2</label>
            <input type="text" name="etc1name[]" id="etcname1_2" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_2" value="" class="text ui-widget-content ui-corner-all grid_70">
            <label for="name">사례3</label>
            <input type="text" name="etc1name[]" id="etcname1_3" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="사례명">
            <input type="text" name="etc1[]" id="etc1_3" value="" class="text ui-widget-content ui-corner-all grid_70">-->
            <label for="files1">다운로드 1</label>
            <input type="text" name="filenames2[]" id="filesnames1" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="files[]" id="files1" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files1"></div>
            <label for="files2">다운로드 2</label>
            <input type="text" name="filenames2[]" id="filesnames2" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="files[]" id="files2" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files2"></div>
            <label for="files3">다운로드 3</label>
            <input type="text" name="filenames2[]" id="filesnames3" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="files[]" id="files3" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files3"></div>
            <label for="files11">감사사례 1</label>
            <input type="text" name="filenames3[]" id="filesnames11" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="filess[]" id="files11" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files11"></div>
            <label for="files22">감사사례 2</label>
            <input type="text" name="filenames3[]" id="filesnames22" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="filess[]" id="files22" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files22"></div>
            <label for="files33">감사사례 3</label>
            <input type="text" name="filenames3[]" id="filesnames33" value="" class="text ui-widget-content ui-corner-all grid_30" placeholder="참고파일명">
            <input type="file" name="filess[]" id="files33" class="text ui-widget-content ui-corner-all grid_70" >
            <div class="add_files33"></div>
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
    <form action="<?php echo G5_URL?>/admin/menu_sub_insert" name="sub_menu_form" method="post" id="sub_menu_form" >
        <input type="hidden" name="menu_code" value="" class="menu_code">
        <fieldset>
            <label for="name">서브메뉴명</label>
            <input type="text" name="menu_name" id="menu_name" value="" class="text ui-widget-content ui-corner-all" placeholder="서브메뉴명" required>
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px" value="">
        </fieldset>
    </form>
</div>

<div class="restore_modal">
    <ul class="list">

    </ul>
</div>

<script type="text/javascript" src="<?php echo G5_JS_URL; ?>/jquery.accordion.js"></script><!--아코디언-->
<script type="text/javascript">
    $('.accordion').accordion({
        "transitionSpeed": 400
    });
</script>
<script src="<?php echo G5_JS_URL ?>/jquery-ui.min.js"></script>

<script>
    var dialog,img_dialog,menu_dialog,restore_modal;
    $(function(){
        $('table.resizable').resizableColumns();

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

        restore_modal = $( ".restore_modal" ).dialog({
            autoOpen: false,
            height: 320,
            width: 600,
            modal: true,
            draggable:false,
            title:"복구",
            buttons: {
                Cancel: function() {
                    restore_modal.dialog( "close" );
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
            error:function(data){
              console.log("error : " + data);
            },
            success: function(data){
                console.log(data);
                alert(data.msg);
                $("#"+data.id+" #links").html('');
                $("#"+data.id+" #etc1").html('');
                $("#"+data.id+" #files").html('');

                if(data.links!=""){
                    var addlink = data.links.split("``");
                    var addlinkname = data.linknames.split("``");
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
                /*if(data.etc1s!=""){
                    var addetc = data.etc1s.split("``");
                    var addetcname = data.etc1names.split("``");
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
                }*/
                if(data.filename){
                    var addfile = data.filename.split("``");
                    var addfilename = data.file_names.split("``");
                    var basicname = "참고파일";
                    for(var i = 0 ; i < addfile.length; i++){
                        if(addfile[i] != "") {
                            if(addfilename[i] != "") {
                                basicname = addfilename[i];
                            }else{
                                basicname = basicname + i;
                            }
                            var fileitem = '<a href="javascript:fnImage(\'' + addfile[i] + '\')" >'+basicname+'</a><br>';
                            $("#"+data.id+" #files").append(fileitem);
                        }
                    }
                }else{
                    $("#"+data.id+" #files").html('');
                }

                if(data.filename2){
                    var addfile = data.filename2.split("``");
                    var addfilename = data.file_names2.split("``");
                    var basicname = "다운로드";
                    for(var i = 0 ; i < addfile.length; i++){
                        if(addfile[i] != "") {
                            if(addfilename[i] != "") {
                                basicname = addfilename[i];
                            }else{
                                basicname = basicname + i;
                            }
                            var fileitem = '<a href="javascript:fnImage(\'' + addfile[i] + '\')" >'+basicname+'</a><br>';
                            $("#"+data.id+" #files2").append(fileitem);
                        }
                    }
                }else{
                    $("#"+data.id+" #files2").html('');
                }

                if(data.filename3){
                    var addfile = data.filename3.split("``");
                    var addfilename = data.file_names3.split("``");
                    var basicname = "감사사례";
                    for(var i = 0 ; i < addfile.length; i++){
                        if(addfile[i] != "") {
                            if(addfilename[i] != "") {
                                basicname = addfilename[i];
                            }else{
                                basicname = basicname + i;
                            }
                            var fileitem = '<a href="javascript:fnImage(\'' + addfile[i] + '\')" >'+basicname+'</a><br>';
                            $("#"+data.id+" #files3").append(fileitem);
                        }
                    }
                }else{
                    $("#"+data.id+" #files3").html('');
                }
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
    function fnBackup(){
        if(confirm("백업은 현재 화면 및 메뉴에 해당하는 데이터의 전체 백업입니다. \r백업을 진행 하시겠습니까?")) {
            $.ajax({
                url: g5_url + "/admin/ajax.backup.php",
                method: "POST"
            }).done(function (data) {
                if(data.indexOf("백업파일") != -1){
                    alert("백업 되었습니다.\r백업 복구는 복구 버튼을 통해 가능합니다.");
                }
            });
        }
    }
    function fnRestore(){
        $.ajax({
            url:g5_url+"/admin/ajax.getBackupList.php",
            method:"post"
        }).done(function(data){
            if(data){
                $(".restore_modal .list").html('');
                $(".restore_modal .list").append(data);
                restore_modal.dialog("open", "restore_modal", true);
            }else{
                alert("백업된 데이터가 없습니다.");
            }
        });
    }

    function fnRestoreItem(file){
        $.ajax({
            url:g5_url+"/admin/ajax.restore.php",
            method:"post",
            data:{filename:file}
        }).done(function(data){
            console.log(data);
        });
    }
    function fnRestoreDel(file,id){
        $.ajax({
            url:g5_url+"/admin/ajax.restoreDel.php",
            method:"post",
            data:{filename:file}
        }).done(function(data){
            if(data==1){
                $("#restore_"+id).remove();
            }else if(data==2){
                alert("백업파일 삭제를 할 수 없습니다.");
            }
        });
    }
</script>
<script src="<?php echo G5_URL ?>/admin/admin.js"></script>

<?php
include_once (G5_PATH."/tail.sub.php");
?>