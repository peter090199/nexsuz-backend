<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>User Profile</h1>
    

    <img src="{{$imagePath}}" alt="" srcset="">
        <!-- Photo Upload -->
        <label for="photo_pic">Profile Photo:</label>
        <input type="file" id="photo_pic" name="photo_pic"><br><br>

        <!-- Contact Information -->
        <label for="contact_no">Contact Number:</label>
        <input type="text" id="contact_no" name="contact_no"><br><br>

        <label for="contact_visibility">Show Contact Number:</label>
        <input type="checkbox" id="contact_visibility" name="contact_visibility" value="1"><br><br>

        <label for="email_visibility">Show Email:</label>
        <input type="checkbox" id="email_visibility" name="email_visibility" value="1"><br><br>

        <label for="date_birth">Date of Birth:</label>
        <input type="date" id="date_birth" name="date_birth"><br><br>

        <label for="date_birth">Date of Birth:</label>
        <input type="date" id="summary" name="summary"><br><br>

        <label for="home_country">Home Country:</label>
        <input type="text" id="home_country" name="home_country"><br><br>

        <label for="current_location">Current Location:</label>
        <input type="text" id="current_location" name="current_location"><br><br>

        <!-- Capabilities -->
        <h2>Capabilities</h2>
        <div id="capabilities">
            <div class="capability">
                <label>Language:</label>
                <input type="text" name="language_1" class="language">
                <label>Skills:</label>
                <input type="text" name="skills_1" class="skills">
            </div>
        </div>
        <button type="button" id="addCapability">Add Capability</button><br><br>

        <!-- Education -->
        <h2>Education</h2>
        <div id="educations">
            <div class="education">
                <label>Highest Education:</label>
                <input type="text" name="highest_education_1" class="highest_education">
                <label>School Name:</label>
                <input type="text" name="school_name_1" class="school_name">
                <label>Year Entry:</label>
                <input type="number" name="year_entry_1" class="year_entry">
                <label>Year End:</label>
                <input type="number" name="year_end_1" class="year_end">
                <label>Status:</label>
                <input type="text" name="status_1" class="status">
            </div>
        </div>
        <button type="button" id="addEducation">Add Education</button><br><br>
        <button type="submit" id="save">Save Profile</button>


    <script>
      $('#save').on('click', function(event) {
        
        // return console.log($('#photo_pic')[0].files[0]);
    event.preventDefault();

    // Create the structured data object
    const formDataObject = {
        "photo_pic": $('#photo_pic')[0].files[0], // File input
        "contact_no": $('#contact_no').val(),
        "contact_visibility": 0,
        "email_visibility": 1,
        "summary" : "this is the summary of my self example.",
        "date_birth": "1990-01-01",
        "home_country": "USA",
        "home_state" : "yeah",
        "current_location": "New York",
        "current_state" : "current state",
        "lines": {
            "capability": [
                {
                    "language": "English",
                    "skills": "Programming"
                },
                {
                    "language": "Spanish",
                    "skills": "Translation"
                }
            ],
            "education": [
                {
                    "highest_education": "Bachelor's Degree",
                    "school_name": "Harvard University",
                    "year_entry": 2010,
                    "year_end": 2014,
                    "status": "Graduated"
                },
                {
                    "highest_education": "Master's Degree",
                    "school_name": "MIT",
                    "year_entry": 2015,
                    "year_end": 2017,
                    "status": "Graduated"
                }
            ],
           "training": [
                      {
                        "training_title": "Leadership Training",
                        "training_provider": "Leadership Institute",
                        "trainingdate": "2020-06-15"
                      },
                            {
                        "training_title": "Leadership Training2",
                        "training_provider": "Leadership Institute",
                        "trainingdate": "2020-06-15"
                      }
                    ],
                    "seminar": [
                      {
                        "seminar_title": "Advanced Laravel",
                        "seminar_provider": "Laravel Academy",
                        "seminardate": "2022-03-20"
                      },
                      {
                        "seminar_title": "Advanced Laravel2",
                        "seminar_provider": "Laravel Academy2",
                        "seminardate": "2022-03-20"
                      }
                    ],
                    "employment": [
                        {
                            "company_name": "Tech Corp",
                            "position": "Software Engineer",
                            "job_description": "Developing web applications",
                            "date_completed": "2023-11-01"
                        },
                        {
                            "company_name": "Dev Solutions",
                            "position": "Senior Developer",
                            "job_description": "Leading a development team and building scalable solutions",
                            "date_completed": "2024-02-01"
                        }
                    ],
                    "certificate" : [
                        {
                            'certificate_title' :"certifacate title" ,
                            'certificate_provider' : "certificate_provider",
                            'date_completed' : "2024-02-01",
                        },
                        {
                            'certificate_title' :"certifacate title2" ,
                            'certificate_provider' : "certificate_provider2",
                            'date_completed' : "2024-02-01",
                        }
                    ]
        }
    };

    // Convert the structured object into FormData
    const formData = new FormData();

    // Append file separately
    formData.append('photo_pic', formDataObject.photo_pic);

    // // Append the rest of the fields dynamically
    // for (const key in formDataObject) {
    //     if (key !== 'lines' && key !== 'photo_pic') {
    //         formData.append(key, formDataObject[key]);
    //     }
    // }

    // // Handle nested objects for `lines`
    // formDataObject.lines.capability.forEach((capability, index) => {
    //     formData.append(`lines[capability][${index}][language]`, capability.language);
    //     formData.append(`lines[capability][${index}][skills]`, capability.skills);
    // });

    // formDataObject.lines.education.forEach((education, index) => {
    //     formData.append(`lines[education][${index}][highest_education]`, education.highest_education);
    //     formData.append(`lines[education][${index}][school_name]`, education.school_name);
    //     formData.append(`lines[education][${index}][year_entry]`, education.year_entry);
    //     formData.append(`lines[education][${index}][year_end]`, education.year_end);
    //     formData.append(`lines[education][${index}][status]`, education.status);
    // });

    // formDataObject.lines.training.forEach((training, index) => {
    //     formData.append(`lines[training][${index}][training_title]`, training.training_title);
    //     formData.append(`lines[training][${index}][training_provider]`, training.training_provider);
    //     formData.append(`lines[training][${index}][trainingdate]`, training.trainingdate);
    // });

    // formDataObject.lines.seminar.forEach((seminar, index) => {
    //     formData.append(`lines[seminar][${index}][seminar_title]`, seminar.seminar_title);
    //     formData.append(`lines[seminar][${index}][seminar_provider]`, seminar.seminar_provider);
    //     formData.append(`lines[seminar][${index}][seminardate]`, seminar.seminardate);
    // });

    
    // formDataObject.lines.employment.forEach((employment, index) => {
    //     formData.append(`lines[employment][${index}][company_name]`, employment.company_name);
    //     formData.append(`lines[employment][${index}][position]`, employment.position);
    //     formData.append(`lines[employment][${index}][job_description]`, employment.job_description);
    //     formData.append(`lines[employment][${index}][date_completed]`, employment.date_completed);
    // });


    // formDataObject.lines.employment.forEach((employment, index) => {
    //     formData.append(`lines[employment][${index}][company_name]`, employment.company_name);
    //     formData.append(`lines[employment][${index}][position]`, employment.position);
    //     formData.append(`lines[employment][${index}][job_description]`, employment.job_description);
    //     formData.append(`lines[employment][${index}][date_completed]`, employment.date_completed);
    // });

    // formDataObject.lines.certificate.forEach((certificate, index) => {
    //     formData.append(`lines[certificate][${index}][certificate_title]`, certificate.certificate_title);
    //     formData.append(`lines[certificate][${index}][certificate_provider]`, certificate.certificate_provider);
    //     formData.append(`lines[certificate][${index}][date_completed]`, certificate.date_completed);
    // });


    // Get the Bearer token (assuming it's stored in localStorage or cookies)
    const bearerToken = '1|Cv56XPrOQStZ1HmPYOlK1tUObl3QtX8obmzp3kzR18d23075';  

    // Send the FormData object via Axios with Bearer Token
    axios.post('http://127.0.0.1:8000/api/profile_pic', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${bearerToken}`,
        },
    })
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error(error.response ? error.response.data : error.message); // Handle errors
        alert('An error occurred. Please try again.');
    });
});



    </script>
</body>
</html>
