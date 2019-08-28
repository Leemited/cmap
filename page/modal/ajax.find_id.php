<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i>아이디 찾기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="modal_content">
        <div class="downmenu">
            <h3>휴대폰 번호로 찾기</h3>
            <div class="inmenu">
                <input type="radio" name="mb_level" value="3" id="normal" checked><label for="normal">개인회원</label>
                <input type="radio" name="mb_level" value="6" id="company"><label for="company">기업회원</label>
                <input type="text" class="modal_input width100 mbtm10" name="name" id="hpname" required placeholder="성명">
                <select name="hp[]" id="hp1" required class="modal_sel width30 groups mbtm10">
                    <option value="010">010</option>
                    <option value="070">070</option>
                </select>
                <input type="text" class="modal_input width70 groups mbtm10" name="hp[]" id="hp2" required placeholder="'-'없이 입력">
                <input type="button" onclick="fnFind('hp');" class="modal_btn01 width100" value="확인">
            </div>
        </div>
        <div class="downmenu">
            <h3>이메일 주소로 찾기</h3>
            <div class="inmenu">
                <input type="radio" name="mb_level2" value="3" id="normal2" checked><label for="normal2">개인회원</label>
                <input type="radio" name="mb_level2" value="6" id="company2"><label for="company2">기업회원</label>
                <input type="text" class="modal_input width100 mbtm10" name="name" id="emailname" required placeholder="성명">
                <input type="text" class="modal_input width30 groups mbtm10" name="email[]" id="email1" required placeholder="이메일아이디">
                <div class="width10 groups email_mark" ></div>
                <input type="text" class="modal_input width30 groups mbtm10" name="email[]" id="email2" required placeholder="직접입력">
                <select name="emailsel" id="emailsel" class="modal_sel width30 groups mbtm10" onchange="$('#email2').val(this.value)">
                    <option value="">직접입력</option>
                    <option value="naver.com">naver.com</option>
                    <option value="gmail.com">gmail.com</option>
                    <option value="hanmail.net">hanmail.net</option>
                    <option value="daum.net">daum.net</option>
                </select>
                <input type="button" onclick="fnFind('email');" class="modal_btn01 width100" value="확인">
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("input[name=mb_level]").click(function(){
            if($(this).val()==3){
                $("#hpname").attr("placeholder","성명");
                $("#hp2").attr("placeholder","'-'없이 입력");
            }else{
                $("#hpname").attr("placeholder","회사명");
                $("#hp2").attr("placeholder","'-'없이 회사전화번호 입력");
            }
        });
        $("input[name=mb_level2]").click(function(){
            if($(this).val()==3){
                $("#emailname").attr("placeholder","성명");
            }else{
                $("#emailname").attr("placeholder","회사명");
            }
        });
    });
    function fnFind(type){
        if(type=="hp"){
            var name = $("#hpname").val();
            var hp = $("#hp1").val()+$("#hp2").val();
            var mb_level = $("input[name=mb_level]:checked").val();
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
                url:g5_url+"/page/ajax/ajax.find_id.php",
                method:"post",
                data:{name:name,hp:hp,type:type,mb_level:mb_level},
                dataType:"json"
            }).done(function(data){
                console.log(data);
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    if (data.sns == true) {
                        var indata = "<div class='idinfo'>";
                        indata += "현재 회원님의 계정은 [" + data.snsid + "]계정으로 가입되어 있습니다.\r해당 SNS로 로그인 바랍니다.";
                        indata += "</div>";
                        indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                        $(".modal_content").html(indata);
                    } else {
                        var indata = "<div class='idinfo'>";
                        indata += "회원 님의 아이디는 [" + data.mb_id + "]입니다";
                        indata += "</div>";
                        indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                        $(".modal_content").html(indata);
                    }
                    fnModalTop();
                }
            });
        }else if(type=="email"){
            var name = $("#emailname").val();
            var email = $("#email1").val()+"@"+$("#email2").val();
            var mb_level = $("input[name=mb_level2]:checked").val();
            if(name==""){
                alert("이름을 입력해주세요.");
                $("#emailname").focus();
                return false;
            }
            if($("#email1").val()=="" || $("#email2").val()==""){
                alert("이메일정보를 입력해주세요.");
                $("#email1").focus();
                return false;
            }
            $.ajax({
                url:g5_url+"/page/ajax/ajax.find_id.php",
                method:"post",
                data:{name:name,email:email,type:type,mb_level:mb_level},
                dataType:"json"
            }).done(function(data){
                console.log(data);
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    if (data.sns == true) {
                        var indata = "<div class='idinfo'>";
                        indata += "현재 회원님의 계정은 [" + data.snsid + "]계정으로 가입되어 있습니다.\r해당 SNS로 로그인 바랍니다.";
                        indata += "</div>";
                        indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                        $(".modal_content").html(indata);

                    } else {
                        var indata = "<div class='idinfo'>";
                        indata += "회원 님의 아이디는 [" + data.mb_id + "]입니다";
                        indata += "</div>";
                        indata += "<input type='button' class='modal_btn01 width100' value='확인' onclick='fnCloseModal()'>"
                        $(".modal_content").html(indata);
                    }
                    fnModalTop();
                }
            });
        }
    }
</script>
<span class="bg"></span>
