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
    function fnFind(type){
        if(type=="hp"){
            var name = $("#hpname").val();
            var hp = $("#hp1").val()+$("#hp2").val();
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
                data:{name:name,hp:hp,type:type},
                dataType:"json"
            }).done(function(data){
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    if (data.sns == true) {
                        alert("현재 회원님의 계정은 [" + data.snsid + "]계정으로 가입되어 있습니다.\r해당 SNS로 로그인 바랍니다.");
                        return false;
                    } else {
                        console.log(data.mb_id);
                    }
                }
            });
        }else if(type=="email"){
            var name = $("#emailname").val();
            var email = $("#email1").val()+"@"+$("#email2").val();
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
                data:{name:name,email:email,type:type},
                dataType:"json"
            }).done(function(data){
                if(data.msg==2){
                    alert("일치하는 회원정보가 없습니다.");
                }else {
                    if (data.sns == true) {
                        alert("현재 회원님의 계정은 [" + data.snsid + "]계정으로 가입되어 있습니다.\r해당 SNS로 로그인 바랍니다.");
                        return false;
                    } else {
                        console.log(data.mb_id);
                    }
                }
            });
        }
    }
</script>
<span></span>