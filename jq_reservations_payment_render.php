<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

Session::init();

$step = 'el pago';

$needStep1 = '
                Antes de realizar '.$step.',
                debe proporcionar la cantidad de niños e invitados adultos, así como la fecha de la celebración del cumpleaños<br>
                <a href="reservation.php?step=reset" title="Clic para seleccionar el lugar, niños e invitados"><span class="hilight">Ir al primer paso</span></a>
                ';

$needStep2 = '
                Antes de realizar '.$step.',
                Debe seleccionar el paquete de cumpleaños<br>
                <a href="reservation.php?step=paquete" title="Clic para seleccionar el paquete de compleaños"><span class="hilight">Seleccinar el paquete</span></a>
                ';


$Splace = Session::get('place');
//    $Splace = Session::destroy('place');
$Schildren = Session::get('children');
$Sadults = Session::get('adults');
$Stime = Session::get('time');

$Spackage = Session::get('package');

if(!$Splace || !$Schildren || !$Sadults || !$Stime){
    die($needStep1);
}

if(!$Spackage){
    die($needStep2);
}


?>
<div>
<table><tbody><tr><td><span id="ErrorMessage2" style="color:Red;"></span><div id="ValidationSummary2" style="color:Red;display:none;">

            </div></td></tr></tbody></table>


<div class="span7">
    <span id="PaymentText" style="font-weight:bold; color:#333366;">Birthday Child Information:</span><br>

    <script language="javascript">
        function validateGuestList()
        {
            var guestList = document.getElementById("FormGuestInfoBirthday1_GuestList");

            if(guestList.length < 1)
                document.getElementById("FormGuestInfoBirthday1_ErrorMessage").value='* At least one birthday guest is required.'

        }

        String.prototype.trim = function() {

            // skip leading and trailing whitespace
            // and return everything in between
            var x=this;
            x=x.replace(/^\s*(.*)/, "$1");
            x=x.replace(/(.*?)\s*$/, "$1");
            return x;
        }


        function setAddButton()
        {
//    alert("here");
            var name = document.getElementById("FormGuestInfoBirthday1_GuestName");
            var age = document.getElementById("FormGuestInfoBirthday1_GuestAge");
            var Btn = document.getElementById("FormGuestInfoBirthday1_BtnAdd");
            // alert('vars set');
            if (name == null)
            {
                alert("name not found");
                return;
            }

            if (age == null)
            {
                alert("age not found");
                return;
            }

            if (Btn == null)
            {
                alert("Btn not found");
                return;
            }

            //(name.value.trim().length > 0 && age != null && age.trim().length > 0 && !isNaN(age.trim()));

            Btn.disabled = !(name.value.trim().length > 0 && age.value.trim().length > 0 && !isNaN(age.value.trim()));
        }

        function setRemoveButton()
        {
            document.getElementById("FormGuestInfoBirthday1_BtnRemove").disabled = false;
        }

    </script>
    <table>
        <tbody><tr>
            <td valign="top">
                <table>
                    <tbody><tr>
                        <td class="span1">Name:<span style="color:Red;">*</span>&nbsp;</td>
                        <td class="span2" valign="top"><input name="FormGuestInfoBirthday1:GuestName" type="text" maxlength="30" size="30" id="FormGuestInfoBirthday1_GuestName" onmousedown="setAddButton()" onkeyup="setAddButton()" onkeypress="return onlyAlphaNumeric(event);" style="width:117px;"></td>
                        <td class="span1" valign="top" align="left"><input type="submit" name="FormGuestInfoBirthday1:BtnAdd" value="Add->" id="FormGuestInfoBirthday1_BtnAdd" disabled="disabled" class="btn" style="width:85px;">&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" class="span2" rowspan="2" align="right"><select size="4" name="FormGuestInfoBirthday1:GuestList" id="FormGuestInfoBirthday1_GuestList" onchange="setRemoveButton()" style="width:200px;">

                            </select></td>
                    </tr>
                    <tr>
                        <td class="span1">Age:<span style="color:Red;">*</span>&nbsp;</td>
                        <td class="span2"><input name="FormGuestInfoBirthday1:GuestAge" type="text" maxlength="3" size="3" id="FormGuestInfoBirthday1_GuestAge" title="Please enter child's age on birthday" onkeyup="setAddButton()" onkeypress="return onlyNumber(event);" numberonly="true" style="width:25px;">
                            <span style="font-size:11px; color:#5dba44;">Age on Birthday</span></td>
                        <td class="span1" valign="top" align="left"><input type="submit" name="FormGuestInfoBirthday1:BtnRemove" value="<-Remove" id="FormGuestInfoBirthday1_BtnRemove" disabled="disabled" class="btn" style="width:85px;">&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table id="FormGuestInfoBirthday1_GuestGender" cellspacing="0" cellpadding="0" border="0" style="color:Black;border-style:None;font-size:Small;font-weight:bold;border-collapse:collapse;">
                                <tbody><tr>
                                    <td><input id="FormGuestInfoBirthday1_GuestGender_0" type="radio" name="FormGuestInfoBirthday1:GuestGender" value="M" checked="checked"><label for="FormGuestInfoBirthday1_GuestGender_0">Male&nbsp;&nbsp;&nbsp;</label></td><td><input id="FormGuestInfoBirthday1_GuestGender_1" type="radio" name="FormGuestInfoBirthday1:GuestGender" value="F"><label for="FormGuestInfoBirthday1_GuestGender_1">Female</label></td>
                                </tr>
                                </tbody></table></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div id="FormGuestInfoBirthday1_pnlPS">

                                <table width="100%">
                                    <tbody><tr>
                                        <td width="50%"><img id="FormGuestInfoBirthday1_imgSuperHero" src="imgs/superhero.png" border="0"></td>
                                        <td width="50%"><img id="FormGuestInfoBirthday1_imgPrincess" src="imgs/princess.png" border="0"></td>
                                    </tr>
                                    </tbody></table>

                                <table id="FormGuestInfoBirthday1_GuestPstype" cellspacing="0" cellpadding="0" border="0" style="color:Black;border-style:None;font-size:Small;font-weight:bold;width:530px;border-collapse:collapse;">
                                    <tbody><tr>
                                        <td><span id="SuperHero"><input id="FormGuestInfoBirthday1_GuestPstype_0" type="radio" name="FormGuestInfoBirthday1:GuestPstype" value="SuperHero" tabindex="3"><label for="FormGuestInfoBirthday1_GuestPstype_0">SuperHero</label></span></td><td><span id="Princess"><input id="FormGuestInfoBirthday1_GuestPstype_1" type="radio" name="FormGuestInfoBirthday1:GuestPstype" value="Princess" tabindex="3"><label for="FormGuestInfoBirthday1_GuestPstype_1">Princess</label></span></td>
                                    </tr>
                                    </tbody></table><span id="FormGuestInfoBirthday1_lblGuestPstype" style="color:Red;"></span>

                            </div>
                        </td>
                    </tr>
                    </tbody></table>
            </td>


        </tr>
        <tr>
            <td class="span6">
                If you have more than one child celebrating a birthday, please enter the additional names above. You will be charged the regular Party Package price plus $4.99 for the additional birthday child to also receive the Ticket Blaster Experience, Superhero cape &amp; Mask or Princess Cape and Tiara and Superhero or Princess table top photo cutout.
            </td>
        </tr>
        </tbody></table>


    <br>
</div>


<div class="span7">
    <span id="Label1" style="font-weight:bold; color:#333366;">Contact Information:</span><br>


    <table>
        <tbody><tr>
            <td class="span2">Name:<span style="color:Red;">*</span>&nbsp;</td>
            <td class="span4" valign="top">
                <input name="FormContact1:Name" type="text" maxlength="30" id="FormContact1_Name" class="span3" onkeypress="return onlyAlphaNumeric(event);">
                <span controltovalidate="FormContact1_Name" errormessage="Contact Name is required." display="None" id="FormContact1_RequiredFieldValidator1" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span></td>
        </tr>
        <tr>
            <td class="span2">Email:<span style="color:Red;">*</span>&nbsp;</td>
            <td class="span4" align="left">
                <input name="FormContact1:Email" type="text" maxlength="100" id="FormContact1_Email" class="span3">
                <span controltovalidate="FormContact1_Email" errormessage="Invalid Email address." display="None" id="FormContact1_RegularExpressionValidator1" title=".+@.+\..+" evaluationfunction="RegularExpressionValidatorEvaluateIsValid" validationexpression="\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*" style="color:Red;display:none;"></span>
                <span controltovalidate="FormContact1_Email" errormessage="Email is required." display="None" id="FormContact1_Requiredfieldvalidator3" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span>
            </td>
        </tr>
        <tr>
            <td class="span2">Confirm Email:<span style="color:Red;">*</span>&nbsp;</td>
            <td class="span4" align="left">
                <input name="FormContact1:ConfirmEmail" type="text" maxlength="100" id="FormContact1_ConfirmEmail" class="span3">
                <span controltovalidate="FormContact1_ConfirmEmail" errormessage="Invalid Email address." display="None" id="FormContact1_RegularExpressionValidator2" title=".+@.+\..+" evaluationfunction="RegularExpressionValidatorEvaluateIsValid" validationexpression="\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*" style="color:Red;display:none;"></span>
                <span controltovalidate="FormContact1_ConfirmEmail" errormessage="Confirm Email is required." display="None" id="FormContact1_RequiredFieldValidator4" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span>
            </td>
        </tr>
        <tr>
            <td class="span2">Phone:<span style="color:Red;">*</span>&nbsp;</td>
            <td class="span4" align="left">
                <input name="FormContact1:Phone" type="text" maxlength="20" id="FormContact1_Phone" class="span3">
                <span controltovalidate="FormContact1_Phone" errormessage="Contact Phone is required." display="None" id="FormContact1_Requiredfieldvalidator2" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span></td>
        </tr>
        <tr>
            <td class="span2">2nd Phone:&nbsp;</td>
            <td class="span4" align="left">
                <input name="FormContact1:Phone2" type="text" maxlength="20" id="FormContact1_Phone2" class="span3" onkeypress="return onlyNumber(event);"></td>
        </tr>
        </tbody></table>







    <br>
</div>


<div class="span7">
<span id="DepInfo" style="font-weight:bold; color:#333366;">Deposit Information:</span><br>


<table border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <td class="span6">
            <label id="FormDeposit1_RowInstruction1">A $30.00 non-refundable, non-transferable cash or credit card deposit is required to hold your reservation.  We apologize for any inconvenience, but the web site is not able to accept prepaid debit/credit cards.</label>
        </td>
    </tr>
    </tbody></table>

<div id="FormDeposit1_pnlDeposit">

<table border="0" cellpadding="0" cellspacing="0">
<tbody><tr>
    <td align="left"><table id="FormDeposit1_DepositType" border="0" style="border-style:None;font-size:X-Small;width:200px;">
            <tbody><tr>
                <td><input id="FormDeposit1_DepositType_0" type="radio" name="FormDeposit1:DepositType" value="1" checked="checked"><label for="FormDeposit1_DepositType_0">Online</label></td><td><input id="FormDeposit1_DepositType_1" type="radio" name="FormDeposit1:DepositType" value="0" onclick="javascript:setTimeout('__doPostBack(\'FormDeposit1$DepositType$1\',\'\')', 0)" language="javascript"><label for="FormDeposit1_DepositType_1">In-Store</label></td>
            </tr>
            </tbody></table><br><br></td>
</tr>
<tr>
<td>

<script language="javascript">
    function validateExpDate(src, arg)
    {
        var input = document.all[src.controltovalidate];

        if(input.value != null && input.value.length == 4 &&
            input.value.match("(0[0-9])|(1[12])[0-9][0-9]") != null)
        {
            var m = input.value.substring(0,2);
            var y = input.value.substring(2,4);
            var today = new Date();
            var thisYear = today.getFullYear() % 100;

            if(y > thisYear ||
                (y == thisYear && m > today.getMonth()))
                arg.IsValid = true;
            else
                arg.IsValid = false;
        }
        else
            arg.IsValid = false;

        if(!arg.Isvalid)
            input.focus();

    }


    function validateCardNumber(src, arg)
    {

        var input = document.all[src.controltovalidate];

        var cardType = document.getElementById("FormDeposit1_FormCreditCard1_CardType");
        //var cardType = document.getElementById("FormDeposit1_FormCreditCard1_ExpirationDate");

        if(input.value != null &&
            input.value.match("[0-9]+") != null)
        {
            if(cardType.value == "AMEX" &&
                input.value.length == 15 &&
                input.value.match("3[47][0-9]{13}") != null)
                arg.IsValid = mod10(input.value);
            else if(cardType.value == "MasterCard" &&
                input.value.length == 16 &&
                input.value.match("5[1-5][0-9]{14}") != null)
                arg.IsValid = mod10(input.value);
            else if(cardType.value == "Visa" &&
                (input.value.length == 16 || input.value.length == 13) &&
                (input.value.match("4[0-9]{15}") != null || input.value.match("4[0-9]{12}")))
                arg.IsValid = mod10(input.value);
            else if(cardType.value == "Discover" &&
                input.value.length == 16 &&
                input.value.match("6011[0-9]{12}") != null)
                arg.IsValid = mod10(input.value);
            else
                arg.IsValid = false;
        }
        else
            arg.IsValid = false;

        if(!arg.Isvalid)
            input.focus();
    }

    function mod10( cardNumber )
    {
        var	cardDigits = new Array(cardNumber.length);

        var i = 0;
        for(i=0; i<cardNumber.length; ++i)
            cardDigits[i] = parseInt(cardNumber.charAt(i));

        for(i=cardDigits.length-2; i>=0; i-=2)
        {
            cardDigits[i] *=2;
            if(cardDigits[i] > 9)
                cardDigits[i] -= 9;
        }

        var sum = 0;
        for(i=0; i<cardDigits.length; ++i)
            sum += cardDigits[i];
        return (((sum%10)==0)? true:false);
    }
    function validateSecurityID(src, arg)
    {
        var input = document.all[src.controltovalidate];
        // arg.IsValid = true;
        if(input.value != null && input.value.length >= 3 && input.value.length <= 4)
        {
            arg.IsValid = chkNumeric(input.value);
        }
        else
        {
            arg.IsValid = false;
        }

        if(!arg.Isvalid)
            input.focus();
    }
    function chkNumeric(str)
    {
        var strValidChars = "0123456789";
        var result = true;
        var strChar;
        for (i = 0; i < str.length && result == true; i++)
        {
            strChar = str.charAt(i);
            if (strValidChars.indexOf(strChar) == -1)
            {
                result = false;
            }
        }
        return result;
    }
</script>



<table width="490px" border="0">
    <tbody><tr>
        <td>
            <table>
                <tbody><tr>
                    <td align="right" valign="top" style="width: 110px">Card Type&nbsp;<span style="color: red">*</span>&nbsp;&nbsp;</td>
                    <td align="left" valign="top" style="width: 140px"><select name="FormDeposit1:FormCreditCard1:CardType" id="FormDeposit1_FormCreditCard1_CardType" tabindex="1" style="width:130px;">
                            <option selected="selected" value="MasterCard">MasterCard</option>
                            <option value="Visa">Visa</option>
                            <option value="AMEX">AMEX</option>
                            <option value="Discover">Discover</option>

                        </select></td>
                    <td align="right" valign="top" style="width: 150px">Expiration (mmyy)&nbsp;<span style="color: red">*</span>&nbsp;&nbsp;</td>
                    <td align="left" valign="top" style="width: 90px"><input name="FormDeposit1:FormCreditCard1:ExpirationDate" type="text" maxlength="4" id="FormDeposit1_FormCreditCard1_ExpirationDate" tabindex="3" onkeypress="return onlyNumber(event);" style="width:36px;">
                        <span controltovalidate="FormDeposit1_FormCreditCard1_ExpirationDate" errormessage="Credit card expiration date is required." display="None" id="FormDeposit1_FormCreditCard1_rfvExpirationDate" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span>
                        <span controltovalidate="FormDeposit1_FormCreditCard1_ExpirationDate" errormessage="Invalid or expired credit card" display="None" id="FormDeposit1_FormCreditCard1_cvExpirationDate" evaluationfunction="CustomValidatorEvaluateIsValid" clientvalidationfunction="validateExpDate" style="color:Red;display:none;"></span>
                    </td>
                </tr>
                </tbody></table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tbody><tr>
                    <td align="right" valign="top" style="width: 110px">Card Number&nbsp;<span style="color: red">*</span>&nbsp;&nbsp;</td>
                    <td align="left" valign="top" style="width: 130px"><input name="FormDeposit1:FormCreditCard1:CardNumber" type="text" maxlength="20" size="20" id="FormDeposit1_FormCreditCard1_CardNumber" tabindex="2" onkeypress="return onlyNumber(event);" style="width:130px;">
                        No spaces or hyphens
                        <span controltovalidate="FormDeposit1_FormCreditCard1_CardNumber" errormessage="Credit card number is required." display="None" id="FormDeposit1_FormCreditCard1_rfvCardNumber" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span>
                        <span controltovalidate="FormDeposit1_FormCreditCard1_CardNumber" errormessage="Invalid credit card number" display="None" id="FormDeposit1_FormCreditCard1_cvCardNumber" evaluationfunction="CustomValidatorEvaluateIsValid" clientvalidationfunction="validateCardNumber" style="color:Red;display:none;"></span>
                        <span id="FormDeposit1_FormCreditCard1_LblCardNumber"></span></td>
                    <td valign="top" colspan="2" style="width: 240px">
                        <div id="FormDeposit1_FormCreditCard1_divCardSecurityID">
                            <table>
                                <tbody><tr>
                                    <td align="right" style="width: 150px">Card Security ID&nbsp;<span style="color: red">*</span>&nbsp;&nbsp;
                                        <br><a class="small" onclick="" href="javascript:winOpenCCID('SecurityIdInfo.html')" id="link31">what is this?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" style="width: 90px"><input name="FormDeposit1:FormCreditCard1:CardSecurityID" type="text" maxlength="4" id="FormDeposit1_FormCreditCard1_CardSecurityID" tabindex="4" onkeypress="return onlyNumber(event);" style="width:36px;">
                                        <span controltovalidate="FormDeposit1_FormCreditCard1_CardSecurityID" errormessage="Card Security ID is required." display="None" id="FormDeposit1_FormCreditCard1_rfvCardSecurityID" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span>
                                        <span controltovalidate="FormDeposit1_FormCreditCard1_CardSecurityID" errormessage="Invalid Card Security ID Number" display="None" id="FormDeposit1_FormCreditCard1_cvCardSecurityID" evaluationfunction="CustomValidatorEvaluateIsValid" clientvalidationfunction="validateSecurityID" style="color:Red;display:none;"></span></td>
                                </tr>
                                </tbody></table>
                        </div>
                    </td>
                </tr>
                </tbody></table>
        </td>
    </tr>
    <tr>
        <td>
            <div id="FormDeposit1_FormCreditCard1_divZip">
                <table>
                    <tbody><tr>
                        <td align="right" valign="top" style="width: 110px">Billing Zip Code&nbsp;<span style="color: red">*</span>&nbsp;&nbsp;</td>
                        <td align="left" valign="top" style="width: 140px">
                            <input name="FormDeposit1:FormCreditCard1:ZipCode" type="text" maxlength="10" id="FormDeposit1_FormCreditCard1_ZipCode" tabindex="5" title="Please enter US Zip Code" onkeypress="return onlyAlphaNumeric(event);" style="width:80px;">
                            <span controltovalidate="FormDeposit1_FormCreditCard1_ZipCode" errormessage="Zip/Postal Code is required" display="None" id="FormDeposit1_FormCreditCard1_rfvZipCode" evaluationfunction="RequiredFieldValidatorEvaluateIsValid" initialvalue="" style="color:Red;display:none;"></span></td>

                    </tr>
                    <tr>
                        <td colspan="2" align="center">(Enter only U.S. numeric zip code)</td>
                    </tr>
                    </tbody></table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"><br><span id="FormDeposit1_FormCreditCard1_lblCCMsg" style="width:450px;">Non-refundable credit card deposits will be charged upon clicking the 'BOOK IT' button on the next page. The deposit will be credited on your final bill the day of the party. <font class="disclaimer"> The deposit will appear as CECONLINEORDER  on your credit card statement.</font></span></td>
    </tr>
    </tbody></table>


<table width="550px" border="1">



</table>
</td>
</tr>
</tbody></table>

</div>

<table border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>

    </tr>
    </tbody></table>

</div>

<div class="span7">
    <span id="ErrorMessage" style="color:Red;"></span><div id="ValidationSummary1" style="color:Red;display:none;">

    </div>
</div>

<div class="span7">
    <span id="Label3" style="font-weight:bold; color:red;">* Required Fields</span>
</div>

<table width="100%"><tbody><tr><td align="right">
            <a id="Btnnext" class="btn" href='javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions("Btnnext", "", true, "", "", false, true))'>Next</a>
        </td></tr></tbody></table>

</div>