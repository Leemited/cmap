<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i>비밀번호 찾기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="modal_content">
        <!--<div class="downmenu">
            <h3>휴대폰 번호로 찾기</h3>
            <div class="inmenu">
                <input type="text" class="modal_input width100 mbtm10" name="mb_id" id="hpid" required placeholder="아이디">
                <input type="text" class="modal_input width100 mbtm10" name="name" id="hpname" required placeholder="성명">
                <select name="hp[]" id="hp1" required class="modal_sel width30 groups mbtm10">
                    <option value="010">010</option>
                    <option value="070">070</option>
                </select>
                <input type="text" class="modal_input width70 groups mbtm10" name="hp[]" id="hp2" required placeholder="'-'없이 입력">
                <input type="button" onclick="fnFind('hp');" class="modal_btn01 width100" value="확인">
            </div>
        </div>-->
        <div class=" active">
            <!--<h3>비밀번호 재설정</h3>-->
            <div class="inmenu">
                <input type="radio" name="mb_level" value="3" id="normal" checked><label for="normal">개인회원</label>
                <input type="radio" name="mb_level" value="6" id="company"><label for="company">기업회원</label>
                <input type="text" class="modal_input width100 mbtm10" name="mb_id" id="mb_id" required placeholder="아이디">
                <input type="text" class="modal_input width100 mbtm10" name="mb_name" id="mb_name" required placeholder="성명">
                <input type="text" class="modal_input width100 groups mbtm10" name="mb_hp" id="mb_hp" required placeholder="휴대폰번호 (-)제외 숫자만 입력" >
                <input type="button" onclick="fnFind('');" class="modal_btn01 width100" value="확인">
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("input[name=mb_level]").click(function(){
            if($(this).val()==3){
                $("#mb_id").attr("placeholder","아이디");
                $("#mb_name").attr("placeholder","성명");
                $("#mb_hp").attr("placeholder","휴대폰번호 (-)제외 숫자만 입력");
            }else{
                $("#mb_id").attr("placeholder","아이디");
                $("#mb_name").attr("placeholder","회사명");
                $("#mb_hp").attr("placeholder","사업자번호 (-)제외 숫자만 입력");
            }
        });
    });
    function fnFind(){
        /*if(type=="hp"){
            var mb_id = $("#mb_id").val();
            var name = $("#mb_name").val();
            var hp = $("#mb_hp").val();
            if(mb_id==""){
                alert("아이디를 입력해주세요.");
                $("#hpid").focus();
                return false;
            }
            if(name==""){
                alert("이름을 입력해주세요.");
                $("#hpname").focus();
                return false;
            }
            if($("#hp2").val()==""){
                alert("휴대폰번호를 입력해주세요.");
                $("#hp2").focus();
                return false;
            }
            $.ajax({
                url:g5_url+"/page/ajax/ajax.find_pw.php",
                method:"post",
                data:{name:name,hp:hp,type:type,mb_id:mbid},
                dataType:"json"
            }).done(function(data){
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    /!*var indata = "<div class='idinfo'>";
                    indata += "회원 님의 비밀번호는 [" + data.pw + "]입니다";
                    indata += "</div>";
                    indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                    $(".modal_content").html(indata);

                    fnModalTop();*!/
                }
            });
        }else if(type=="email"){*/
            var mbid = $("#mb_id").val();
            var name = $("#mb_name").val();
            var mb_hp = $("#mb_hp").val();
            var mb_level = $("input[name=mb_level]:checked").val();
            if(mbid==""){
                alert("아이디를 입력해주세요.");
                $("#mb_id").focus();
                return false;
            }
            if(name==""){
                alert("이름을 입력해주세요.");
                $("#mb_name").focus();
                return false;
            }
            if($("#mb_hp").val()==""){
                alert("전화번호를 입력해주세요.");
                $("#mb_hp").focus();
                return false;
            }
            $.ajax({
                url:g5_url+"/page/ajax/ajax.find_pw.php",
                method:"post",
                data:{name:name,mb_hp:mb_hp,mb_id:mbid,mb_level:mb_level},
                dataType:"json"
            }).done(function(data){
                console.log(data);
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    alert("회원님의 등록된 ["+data.mb_email+"]이메일주소로 재설정된 비밀번호를 전달하였습니다.")
                    /*var indata = "<div class='idinfo'>";
                    indata += "회원 님의 아이디는 [" + data.mb_id + "]입니다";
                    indata += "</div>";
                    indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                    $(".modal_content").html(indata);

                    fnModalTop();*/
                }
            });
        //}
    }
</script>
<span class="bg"></span>
