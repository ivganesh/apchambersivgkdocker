<?PHP

// Create the custom table on theme activation
// function create_application_form_table() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'application_form';
//     $charset_collate = $wpdb->get_charset_collate();

//     $sql = "CREATE TABLE $table_name (
//         id mediumint(9) NOT NULL AUTO_INCREMENT,
//         organization_name varchar(255) NOT NULL,
//         applicant_name varchar(255) NOT NULL,
//         designation varchar(100) NOT NULL,
//         mobile_number varchar(20) NOT NULL,
//         full_address text NOT NULL,
//         pin varchar(10) NOT NULL,
//         landline1 varchar(20),
//         landline2 varchar(20),
//         fax varchar(20),
//         email varchar(255) NOT NULL,
//         website varchar(255),
//         company_turnover varchar(50) NOT NULL,
//         business_constitution varchar(50) NOT NULL,
//         year_of_establishment varchar(4) NOT NULL,
//         gst_number varchar(50),
//         import_export_code varchar(50),
//         pan varchar(50) NOT NULL,
//         tin varchar(50),
//         din varchar(50),
//         main_line_of_business varchar(50) NOT NULL,
//         business_industry varchar(255),
//         contact_person varchar(255) NOT NULL,
//         additional_service varchar(255),
//         certificate_of_origin varchar(3),
//         visa_recommendation varchar(3),
//         payment_mode varchar(20) NOT NULL,
//         bank_name varchar(255) NOT NULL,
//         branch varchar(255) NOT NULL,
//         payment_date date NOT NULL,
//         declaration text NOT NULL,
//         PRIMARY KEY (id)
//     ) $charset_collate;";

//     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//     dbDelta($sql);
// }
// add_action('after_switch_theme', 'create_application_form_table');

// Form Shortcode
function application_form_shortcode() {
    ob_start();
    ?>

    <style>
        .form-container {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: center;
            gap: 20px;
            margin: auto;
            padding: 20px;
        }
        .form-container img {
            max-width: 100%;
            height: auto;
            flex: 1 1 300px;
            border-radius: 5px;
        }
        .application-form {
            flex: 2 1 500px;
            max-width: 800px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: auto;
        }
        .application-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            color: blue;
        }
        .application-form .required {
            color: red;
        }
        .application-form input, 
        .application-form select, 
        .application-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .application-form input[type="submit"] {
            width: auto;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
        }
        .application-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        header {
            background-color: #000;
            padding: 10px;
            text-align: center;
            font-size: 2vw;
            color: white;
        }
        @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
            }
            header {
                font-size: 2.5vw;
            }
        }
        @media (max-width: 480px) {
            header {
                font-size: 2.5vw;
            }
        }
        .image-preview {
                display: block;
                max-width: 100px;
                max-height: 100px;
                margin-bottom: 10px;
            }
    </style>

    <div class="form-container">
        <form class="application-form" method="post" action="" enctype="multipart/form-data">
        <img src="https://apchambers.in/wp-content/uploads/2024/11/apchamberapplicationformheadertwo.png" alt="Form Image">    
        <header>GENERAL MEMBERSHIP APPLICATION FORM</header>
            
            <!-- Organization Details -->
            <label for="organization_name">Name of the Organisation: <span class="required">*</span></label>
            <input type="text" name="organization_name" id="organization_name" required>

            <label for="applicant_name">Name of the Applicant: <span class="required">*</span></label>
            <input type="text" name="applicant_name" id="applicant_name" required>

            <label for="designation">Designation: <span class="required">*</span></label>
            <input type="text" name="designation" id="designation" required>

            <label for="mobile_number">Mobile No: <span class="required">*</span></label>
            <input type="text" name="mobile_number" id="mobile_number" required>

            <label for="full_address">Full Address: <span class="required">*</span></label>
            <textarea name="full_address" id="full_address" required></textarea>

            <label for="pin">PIN: <span class="required">*</span></label>
            <input type="text" name="pin" id="pin" required>

            <label for="landline1">Landline 1:</label>
            <input type="text" name="landline1" id="landline1">

            <label for="landline2">Landline 2:</label>
            <input type="text" name="landline2" id="landline2">

            <label for="fax">Fax:</label>
            <input type="text" name="fax" id="fax">

            <label for="email">Email ID: <span class="required">*</span></label>
            <input type="email" name="email" id="email" required>

            <label for="website">Website:</label>
            <input type="text" name="website" id="website">

            <!-- Business Details -->
            <label for="company_turnover">Company Turnover (in lakhs): <span class="required">*</span></label>
            <input type="text" name="company_turnover" id="company_turnover" required>

            <label for="business_constitution">Constitution of Business: <span class="required">*</span></label>
            <select name="business_constitution" id="business_constitution" required>
                <option value="Proprietorship">Proprietorship</option>
                <option value="Partnership">Partnership</option>
                <option value="HUF">HUF</option>
                <option value="Government">Government</option>
                <option value="PSU">PSU</option>
                <option value="Company (Private)">Company (Private)</option>
                <option value="Company (Public)">Company (Public)</option>
                <option value="LLP">LLP</option>
                <option value="Other">Other</option>
            </select>

            <label for="year_of_establishment">Year of Establishment: <span class="required">*</span></label>
            <input type="text" name="year_of_establishment" id="year_of_establishment" required>

            <label for="gst_number">GST Number:</label>
            <input type="text" name="gst_number" id="gst_number">

            <label for="import_export_code">Import Export Code:</label>
            <input type="text" name="import_export_code" id="import_export_code">

            <label for="pan">PAN: <span class="required">*</span></label>
            <input type="text" name="pan" id="pan" required>

            <label for="tin">TIN:</label>
            <input type="text" name="tin" id="tin">

            <label for="din">DIN:</label>
            <input type="text" name="din" id="din">

            <label for="main_line_of_business">Main Line of Business: <span class="required">*</span></label>
            <select name="main_line_of_business" id="main_line_of_business" required>
                <option value="Manufacturing">Manufacturing</option>
                <option value="Trade">Trade</option>
                <option value="Services">Services</option>
                <option value="IT">IT</option>
                <option value="Exports">Exports</option>
                <option value="Imports">Imports</option>
            </select>

            <label for="business_industry">Business / Industry Mainly:</label>
            <input type="text" name="business_industry" id="business_industry">

            <!-- Additional Information -->
            <label for="contact_person">Contact Person / Personal Assistant Name: <span class="required">*</span></label>
            <input type="text" name="contact_person" id="contact_person" required>

            <label for="additional_service">Addl. Service Required:</label>
            <input type="text" name="additional_service" id="additional_service">

            <label for="certificate_of_origin">Certificate of Origin:</label>
            <select name="certificate_of_origin" id="certificate_of_origin">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <label for="visa_recommendation">Visa Recommendation:</label>
            <select name="visa_recommendation" id="visa_recommendation">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <!-- Payment Details -->
            <label for="payment_mode">Payment Mode: <span class="required">*</span></label>
            <select name="payment_mode" id="payment_mode" required>
                <option value="Cash">Cash</option>
                <option value="DD">DD</option>
                <option value="BC">BC</option>
                <option value="Cheque">Cheque</option>
            </select>

            <label for="bank_name">Bank Name: <span class="required">*</span></label>
            <input type="text" name="bank_name" id="bank_name" required>

            <label for="branch">Branch: <span class="required">*</span></label>
            <input type="text" name="branch" id="branch" required>

            <label for="payment_date">Payment Date: <span class="required">*</span></label>
            <input type="date" name="payment_date" id="payment_date" required>

            <label for="declaration">Declaration: <span class="required">*</span></label>
            <textarea name="declaration" id="declaration" required></textarea>

             <!-- Image Uploads -->
             <label for="company_logo">Company Logo (Optional):</label>
            <input type="file" name="company_logo" id="company_logo" accept="image/*" onchange="previewImage(event, 'companyLogoPreview')">
            <img id="companyLogoPreview" class="image-preview" src="#" alt="Company Logo Preview" style="display: none;">

            <label for="applicant_image">Applicant Image (Optional):</label>
            <input type="file" name="applicant_image" id="applicant_image" accept="image/*" onchange="previewImage(event, 'applicantImagePreview')">
            <img id="applicantImagePreview" class="image-preview" src="#" alt="Applicant Image Preview" style="display: none;">
            
            <input type="submit" name="submit_application" value="Submit">
        </form>
    </div>
    <script>
        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <?php
    if (isset($_POST['submit_application'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'application_form';

         // Handle file uploads
         $upload_dir = wp_upload_dir();
         $company_logo_url = '';
         $applicant_image_url = '';
 
         if (!empty($_FILES['company_logo']['name'])) {
             $company_logo = $_FILES['company_logo'];
             $company_logo_path = $upload_dir['path'] . '/' . basename($company_logo['name']);
             if (move_uploaded_file($company_logo['tmp_name'], $company_logo_path)) {
                 $company_logo_url = $upload_dir['url'] . '/' . basename($company_logo['name']);
             }
         }
 
         if (!empty($_FILES['applicant_image']['name'])) {
             $applicant_image = $_FILES['applicant_image'];
             $applicant_image_path = $upload_dir['path'] . '/' . basename($applicant_image['name']);
             if (move_uploaded_file($applicant_image['tmp_name'], $applicant_image_path)) {
                 $applicant_image_url = $upload_dir['url'] . '/' . basename($applicant_image['name']);
             }
         }

        // Insert into the database with date_submitted and date_updated fields
        $wpdb->insert(
            $table_name,
            array(
                'organization_name' => sanitize_text_field($_POST['organization_name']),
                'applicant_name' => sanitize_text_field($_POST['applicant_name']),
                'designation' => sanitize_text_field($_POST['designation']),
                'mobile_number' => sanitize_text_field($_POST['mobile_number']),
                'full_address' => sanitize_textarea_field($_POST['full_address']),
                'pin' => sanitize_text_field($_POST['pin']),
                'landline1' => isset($_POST['landline1']) ? sanitize_text_field($_POST['landline1']) : '',
                'landline2' => isset($_POST['landline2']) ? sanitize_text_field($_POST['landline2']) : '',
                'fax' => isset($_POST['fax']) ? sanitize_text_field($_POST['fax']) : '',
                'email' => sanitize_email($_POST['email']),
                'website' => isset($_POST['website']) ? sanitize_text_field($_POST['website']) : '',
                'company_turnover' => sanitize_text_field($_POST['company_turnover']),
                'business_constitution' => sanitize_text_field($_POST['business_constitution']),
                'year_of_establishment' => sanitize_text_field($_POST['year_of_establishment']),
                'gst_number' => isset($_POST['gst_number']) ? sanitize_text_field($_POST['gst_number']) : '',
                'import_export_code' => isset($_POST['import_export_code']) ? sanitize_text_field($_POST['import_export_code']) : '',
                'pan' => sanitize_text_field($_POST['pan']),
                'tin' => isset($_POST['tin']) ? sanitize_text_field($_POST['tin']) : '',
                'din' => isset($_POST['din']) ? sanitize_text_field($_POST['din']) : '',
                'main_line_of_business' => sanitize_text_field($_POST['main_line_of_business']),
                'business_industry' => isset($_POST['business_industry']) ? sanitize_text_field($_POST['business_industry']) : '',
                'contact_person' => sanitize_text_field($_POST['contact_person']),
                'additional_service' => isset($_POST['additional_service']) ? sanitize_text_field($_POST['additional_service']) : '',
                'certificate_of_origin' => isset($_POST['certificate_of_origin']) ? sanitize_text_field($_POST['certificate_of_origin']) : '',
                'visa_recommendation' => isset($_POST['visa_recommendation']) ? sanitize_text_field($_POST['visa_recommendation']) : '',
                'payment_mode' => sanitize_text_field($_POST['payment_mode']),
                'bank_name' => sanitize_text_field($_POST['bank_name']),
                'branch' => sanitize_text_field($_POST['branch']),
                'payment_date' => sanitize_text_field($_POST['payment_date']),
                'declaration' => sanitize_textarea_field($_POST['declaration']),
                'company_logo_url' => $company_logo_url,
                'applicant_image_url' => $applicant_image_url,
                'date_submitted' => current_time('mysql'),
                'date_updated' => current_time('mysql')
            )
        );

        echo '<p>Application submitted successfully!</p>';
    }

    return ob_get_clean();
}
add_shortcode('application_form', 'application_form_shortcode');


function view_application_forms_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'application_form';

    // Fetch all the records from the table
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // Start output buffering
    ob_start();

    if ($results) {
        ?>
        <style>
            .search-bar {
                margin-bottom: 20px;
                font-size: 16px;
            }
            .application-forms-wrapper {
                overflow-x: auto;
                overflow-y: auto;
                max-height: 500px; /* Adjust as needed */
                border: 1px solid #ddd;
            }
            .application-forms-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
                text-align: left;
            }
            .application-forms-table th, .application-forms-table td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            .application-forms-table th {
                background-color: #f4f4f4;
                font-weight: bold;
            }
        </style>

        <input type="text" id="searchInput" class="search-bar" onkeyup="searchTable()" placeholder="Search for records...">
        <button onclick="exportTableToExcel('application-forms-table', 'application_data')">Export to Excel</button>

        <div class="application-forms-wrapper">
            <table class="application-forms-table" id="application-forms-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Organization Name</th>
                        <th>Applicant Name</th>
                        <th>Designation</th>
                        <th>Mobile Number</th>
                        <th>Full Address</th>
                        <th>PIN</th>
                        <th>Landline 1</th>
                        <th>Landline 2</th>
                        <th>Fax</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Company Turnover</th>
                        <th>Business Constitution</th>
                        <th>Year of Establishment</th>
                        <th>GST Number</th>
                        <th>Import Export Code</th>
                        <th>PAN</th>
                        <th>TIN</th>
                        <th>DIN</th>
                        <th>Main Line of Business</th>
                        <th>Business / Industry</th>
                        <th>Contact Person</th>
                        <th>Additional Service</th>
                        <th>Certificate of Origin</th>
                        <th>Visa Recommendation</th>
                        <th>Payment Mode</th>
                        <th>Bank Name</th>
                        <th>Branch</th>
                        <th>Payment Date</th>
                        <th>Declaration</th>
                        <th>Company Logo</th>
                        <th>Applicant Image</th>
                        <th>Date Submitted</th>
                        <th>Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                        <tr>
                            <td><?php echo esc_html($row['id']); ?></td>
                            <td><?php echo esc_html($row['organization_name']); ?></td>
                            <td><?php echo esc_html($row['applicant_name']); ?></td>
                            <td><?php echo esc_html($row['designation']); ?></td>
                            <td><?php echo esc_html($row['mobile_number']); ?></td>
                            <td><?php echo esc_html($row['full_address']); ?></td>
                            <td><?php echo esc_html($row['pin']); ?></td>
                            <td><?php echo esc_html($row['landline1']); ?></td>
                            <td><?php echo esc_html($row['landline2']); ?></td>
                            <td><?php echo esc_html($row['fax']); ?></td>
                            <td><?php echo esc_html($row['email']); ?></td>
                            <td><?php echo esc_html($row['website']); ?></td>
                            <td><?php echo esc_html($row['company_turnover']); ?></td>
                            <td><?php echo esc_html($row['business_constitution']); ?></td>
                            <td><?php echo esc_html($row['year_of_establishment']); ?></td>
                            <td><?php echo esc_html($row['gst_number']); ?></td>
                            <td><?php echo esc_html($row['import_export_code']); ?></td>
                            <td><?php echo esc_html($row['pan']); ?></td>
                            <td><?php echo esc_html($row['tin']); ?></td>
                            <td><?php echo esc_html($row['din']); ?></td>
                            <td><?php echo esc_html($row['main_line_of_business']); ?></td>
                            <td><?php echo esc_html($row['business_industry']); ?></td>
                            <td><?php echo esc_html($row['contact_person']); ?></td>
                            <td><?php echo esc_html($row['additional_service']); ?></td>
                            <td><?php echo esc_html($row['certificate_of_origin']); ?></td>
                            <td><?php echo esc_html($row['visa_recommendation']); ?></td>
                            <td><?php echo esc_html($row['payment_mode']); ?></td>
                            <td><?php echo esc_html($row['bank_name']); ?></td>
                            <td><?php echo esc_html($row['branch']); ?></td>
                            <td><?php echo esc_html($row['payment_date']); ?></td>
                            <td><?php echo esc_html($row['declaration']); ?></td>
                            <td>
                                <?php if (!empty($row['company_logo_url'])): ?>
                                    <img src="<?php echo esc_url($row['company_logo_url']); ?>" alt="Company Logo" style="max-width: 100px; height: auto;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['applicant_image_url'])): ?>
                                    <img src="<?php echo esc_url($row['applicant_image_url']); ?>" alt="Applicant Image" style="max-width: 100px; height: auto;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($row['date_submitted']); ?></td>
                            <td><?php echo esc_html($row['date_updated']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <script>
            // Function to search the table
            function searchTable() {
                let input = document.getElementById("searchInput");
                let filter = input.value.toLowerCase();
                let table = document.getElementById("application-forms-table");
                let tr = table.getElementsByTagName("tr");

                for (let i = 1; i < tr.length; i++) {
                    let cells = tr[i].getElementsByTagName("td");
                    let found = false;
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().includes(filter)) {
                            found = true;
                            break;
                        }
                    }
                    tr[i].style.display = found ? "" : "none";
                }
            }

            // Function to export table to Excel
            function exportTableToExcel(tableID, filename = "") {
                let table = document.getElementById(tableID);
                let tableHTML = table.outerHTML.replace(/ /g, '%20');
                
                let downloadLink = document.createElement("a");
                document.body.appendChild(downloadLink);
                downloadLink.href = 'data:application/vnd.ms-excel,' + tableHTML;
                downloadLink.download = filename ? filename + '.xls' : 'data.xls';
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        </script>
        <?php
    } else {
        echo '<p>No applications found.</p>';
    }

    return ob_get_clean();
}
add_shortcode('view_application_forms', 'view_application_forms_shortcode');
