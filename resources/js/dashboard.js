$(document).ready(function () {
    $('#editIcon').on('click', function () {
        $('#profileImageInput').click();
    });

    $('#profileImageInput').on('change', function () {
        var fileInput = this;
        if (fileInput.files && fileInput.files[0]) {
            var formData = new FormData();
            formData.set('ProfileImage', fileInput.files[0]);
            
            $.ajax({
                url: '../Controller/profilePicUpdate.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = response.trim();
                    if (response.includes("successfully")) {
                        var timestamp = new Date().getTime();
                        $('#profileImage').attr('src', `../uploads/${fileInput.files[0].name}?t=${timestamp}`);
                        showSuccess(response);
                    } else {
                        showError(response);
                    }
                },
                error: function (xhr) {
                    showError('Error during AJAX request. Please try again.');
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        }
    });

    // function success messages
    function showSuccess(message) {
        $('#updateSet').html(message).slideDown().delay(2000).slideUp();
    }

    // Function errors messages
    function showError(message) {
        alert(message);
    }

    // Add Qualification
    $('#addQualificationBtn').on('click', function () {
        if ($('#qualificationInputContainer input').length === 0) {
            $('#qualificationInputContainer').html(` <p><input type="text" id="newQualification" placeholder="Enter new qualification" required>
                 &nbsp &nbsp<button id="submitQualification">Add</button></p>`);
        }
    });

    $(document).on('click', '#submitQualification', function () {
        var qualificationName = $('#newQualification').val().trim();
        if (qualificationName) {
            $.ajax({
                url: '../Controller/addQualification.php',
                type: 'POST',
                data: { qualification_name: qualificationName },
                success: function (response) {
                    if (response.includes("successfully")) {
                        $('#addQualificationBtn').prepend(`<p class="editable-field edit-qualification qualification-item" 
                           data-user-id="" data-field="qualification"  style="width: 100%;">     ${qualificationName} </p>`);
                        $('#qualificationInputContainer').empty();
                        alert('Qualification added successfully!');
                        $('#qualificationList').load(window.location.href + ' #qualificationList> *');
                    } else {
                        alert('Failed to add qualification: ' + response);
                    }
                },
                error: function () {
                    showError('An error occurred during the AJAX request.');
                }
            });
        } else {
            showError('Please enter a qualification name.');
        }
    });

    // Add Experience
    $('#addExperienceBtn').on('click', function () {
        if ($('#experienceInputContainer input').length === 0) {
            $('#experienceInputContainer').html(`<p><input type="text" id="newJobTitle" placeholder="Enter job title" required>
                 &nbsp &nbsp<button id="submitExperience">Add</button></p>`);
        }
    });

   
$(document).on('click', '#submitExperience', function () {
    var jobTitle = $('#newJobTitle').val();
    if (jobTitle) {
        $.ajax({
            url: '../Controller/addExperience.php',
            type: 'POST',
            data: { jobTitle: jobTitle },
            success: function (response) {
                if (response.includes( "Experience Added successfully"))  {
                    var newExperience = `<p class="editable-field edit-experience experience-item">
                                            ${jobTitle} </p>`;
                    $('#addExperienceBtn').prepend(newExperience); 
                    $('#experienceInputContainer').html('');
                    // Refresh the experience list
                    $('#experienceList').load(window.location.href + ' #experienceList > *');
                    alert('Experience added successfully!');
                  showSuccess(data);
                } else {
                    alert( response);
                }
            },
            error: function () {
                showError('An error occurred during the AJAX request.');
            }
        });
    } else {
        alert('Please enter a job title.');
    }
})
  

//Add Address
$(document).on('click', '.add-address-btn', function(e) {
    $('#address-model').show(); 
});
$(document).on('click', '.save', function(e) {
    var line1 =$('#line1').val();
    var line2 =$('#line2').val();
    var city =$('#city').val();
    var state =$('#state').val();
    $.ajax(
       {
           url: '../controller/addAddress.php',
           type: 'POST',
           data:{line1:line1, line2:line2, city:city,state:state},
           success:function(data)
           {
               if(data==1)
               {
                   alert('Current Address Added Successfully');
                // showSuccess('Current Address Added Successfully');
               }else{
                   alert(data);
               }
           }
        }
    ) 
});

$(document).on('click', '#close-btn', function(e) {
    $('#address-model').hide(); 
});

//Edit Inline
function enableInlineEdit(selector) {
    $(document)
        .off('click', selector)
        .off('click', '.save-inline-edit')
        .on('click', selector, function () {
            $('.inline-editor').parent().html($('.inline-editor').val());  
            var currentText = $(this).text().trim();
            var itemId = $(this).data('user-id');
            var field = $(this).data('field');
            var addressType = $(this).data('type'); 
            if (!$(this).find('input').length) {
                var inputField = `
                    <input type='text' class='inline-editor' value='${currentText}' data-user-id='${itemId}' data-field='${field}' ${addressType ? `data-type='${addressType}'` : ''} style='width: max-content;'>
                    <button class='save-inline-edit' data-user-id='${itemId}' data-field='${field}' ${addressType ? `data-type='${addressType}'` : ''} style='margin-left: 5px;'><i class="fa-solid fa-floppy-disk"></i></button>`;
                $(this).html(inputField);
                $(this).find('input').focus();
            }
        })
        .on('click', '.save-inline-edit', function () {
            var inputElement = $(this).siblings('.inline-editor');
            var newValue = inputElement.val().trim();
            var itemId = $(this).data('user-id');
            var field = $(this).data('field');
            var addressType = $(this).data('type');
            var parentElement = $(this).parent();

            if (newValue && itemId) {
                var postData = {};
                var updateUrl = '';

                if (['qualification', 'name', 'experience'].includes(field)) {
                    postData = { id: itemId, newValue: newValue, updateType: field };
                    updateUrl = '../controller/profileUpdate.php';
                } else if (['line1', 'line2', 'city', 'state'].includes(field)) {
                    postData = { address_id: itemId, field: field, newValue: newValue, addressType: addressType };
                    updateUrl = '../controller/UpdateAddress.php';
                }

                $.ajax({
                    url: updateUrl,
                    type: 'POST',
                    data: postData,
                    success: function (response) {
                        response = response.trim();
                        if (response === 'updated successfully!') {
                            parentElement.text(newValue);
                            showSuccess(response);
                        } else {
                            showError('Update failed: ' + response);
                        }
                    },
                    error: function (xhr) {
                        showError('An error occurred while updating.');
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            } else {
                parentElement.text(inputElement.attr('value'));
            }
        });
}


// Enable inline editing for profile and address fields
enableInlineEdit('.edit-name');
enableInlineEdit('.edit-qualification');
enableInlineEdit('.edit-experience');
enableInlineEdit('.edit-line1');
enableInlineEdit('.edit-line2');
enableInlineEdit('.edit-city');

});
