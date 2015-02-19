


















































































                                             <script>

    function SetOptionSelected() {
        var PtyID = document.getElementById("PartyTypeID").getAttribute("value");
        var iFirst = 0;
        var nButtons = document.getElementsByTagName("input").length;


        for (var i = 0; i < nButtons; i++) {
            var v = document.getElementsByTagName("input").item(i).getAttribute("type")
            if (v == "radio") {
                if (iFirst == 0) {
                    if ((PtyID == "") || (PtyID == null)) {
                    }
                    iFirst = iFirst + 1;
                }
                var istrue = document.getElementsByTagName("input").item(i).getAttribute("value")
                if (istrue == PtyID) {
                    document.getElementsByTagName("input").item(i).setAttribute("checked", true);
                }
                else {   //Modified by Tony Lee 8/10/2007
                }
            }
        }
    }

    function SetPartyType(ID) {
        document.getElementById("PartyTypeID").value = ID;
    }

</script>




