// Add Qualification and Experience dynamically
$(document).ready( function(){
    $("#addQualification").on("click", function () {
        const count = $("input[name='qualifications[]']").length + 1;
        $(this).before(`<label class="suggession" style="margin-top: 10px;margin-bottom:5px;">Qualification ${count}</label>`);
        $("<input>")
            .attr("type", "text")
            .attr("name", "qualifications[]")
            .attr("placeholder", `Qualification ${count}`)
            .insertBefore($(this));
     });

    $("#addExperience").on("click", function () {
        const count = $("input[name='experiences[]']").length + 1;
        $(this).before(`<label class="suggession" style="margin-top: 10px;margin-bottom:5px;">Experience ${count}</label>`);
        $("<input>")
            .attr("type", "text")
            .attr("name", "experiences[]")
            .attr("placeholder", `Experience ${count}`)
            .insertBefore($(this));
     });
     
     //profile Inmage Preview
     $("#profilePic").on("change", function(event) {
        const file = event.target.files[0];
        if (file) {
         const reader = new FileReader();
         reader.onload = function(e) {
        $("#profilePreview").attr("src", e.target.result);
           }
        reader.readAsDataURL(file);
       }
     });

     //Verify Password
     $("#register-form").on("submit",function(e){
        let line1 = $('#permanentLine1').val();
        let line2 = $("#permanentLine2").val();
        let city= $('#permanentCity').val();
        let state= $('#permanentState').val();
        if(line1 === "" || line2 === "" || city === "" || state === "")
        {
            alert("Permanent Address is Required!");
            e.preventDefault(); 
            return false;
        }
        let password = $('#password').val();
        let confirmPassword = $("#confirmPassword").val();
        if(password !== confirmPassword)
        {
            alert("Password not match!");
            e.preventDefault(); 
            return false;
        }
    });
    
});
