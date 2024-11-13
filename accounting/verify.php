<?php
// Include config file
include_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include TCPDF library
//Load Composer's autoloader
require '../registrar/vendor/autoload.php';
require_once('../tcpdf/tcpdf.php');

// Check if the ID parameter is set and is a valid integer
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    // Prepare an update statement
    $sql = "UPDATE transactions SET status = 1 WHERE user_id = :user_id";

    if($stmt = $conn->prepare($sql)){
        // Bind parameters
        $stmt->bindParam(":user_id", $param_id, PDO::PARAM_INT);

        // Set parameters
        $param_id = trim($_GET['id']);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Fetch the user's email address
            $email_sql = "SELECT *,users.email 
            FROM student 
            INNER JOIN users ON student.userId = users.id 
            WHERE student.student_id = :user_id";
            if($email_stmt = $conn->prepare($email_sql)) {
                $email_stmt->bindParam(":user_id", $param_id, PDO::PARAM_INT);
                if($email_stmt->execute()) {
                    $row = $email_stmt->fetch(PDO::FETCH_ASSOC);
                    $recipient_email = $row['email'];
                    $student_name = $row['name'];

                    // Generate PDF
                    $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor('EAATI - Certificate of Registration');
                    $pdf->SetTitle('Certificate of Registration');
                    $pdf->SetSubject('COR');

                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);

                    // Add a page
                    $pdf->AddPage();

                    // Set font
                    $pdf->SetFont('helvetica', '', 12);

                    $content = '
<div style="text-align: center;">
    <div style="display: inline-flex; align-items: center;">
        <img src="../images/logo_another.jpg" alt="Logo" style="width: 100px; height: auto;">
        <p style="margin-left: 10px; font-weight: bold;">EASTERN ACHIEVER ACADEMY OF TAGUIG INC.<br><br>Blk 2 L-23 Ph.1 Pinagsama Village Taguig City</p>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <div>
        <p style="font-weight: bold;">CERTIFICATE OF REGISTRATION</p>
    </div>
</div>

<div style="text-align: center;">
    <div style="display: inline-block; text-align: left; margin-top: 10px;">
        <p style="text-indent: 60px;">
            This is to certify that '.$student_name.' with is officially enrolled in GRADE<br> VIII at Eastern Achiever Academy of Taguig Inc. School Year 2022- 2023 for blended<br> learning Online from August to October. Fully face-to-face from November to the end<br> of SY 2022-2023.
        </p>
    </div>
</div>

<div style="text-align: center;">
    <div style="display: inline-block; text-align: left; margin-top: 10px;">
        <p style="text-indent: -15px;">
            This certification is issued for whatever purpose this may serve.
        </p>
    </div>
</div>

<div style="text-align: center;">
    <div style="display: inline-block; text-align: left; margin-top: 10px;">
        <p style="text-indent: 40px;">
            Issued on this 31st day of August, 2022 at Eastern Achiever Academy of<br>
            Taguig Inc.
        </p>
    </div>
</div>';

                    $pdf->writeHTML($content, true, false, true, false, '');

                    // Get PDF content as binary string
                    $cor_content = $pdf->Output('', 'S');

                    // Prepare statement to save PDF to database
                    // Insert PDF into pdf_documents table
                    $insert_sql = "INSERT INTO generatedcor (userId,cor_content) VALUES (:userId, :cor_content)";
                    if ($insert_stmt = $conn->prepare($insert_sql)) {
                        $insert_stmt->bindParam(':userId', $param_id, PDO::PARAM_INT);
                        $insert_stmt->bindParam(':cor_content', $cor_content, PDO::PARAM_LOB);
                        if ($insert_stmt->execute()) {
                            try {
                                //Instantiation and passing `true` enables exceptions
                                $mail = new PHPMailer(true);
        
                                //Enable verbose debug output
                                $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;
        
                                //Send using SMTP
                                $mail->isSMTP();
        
                                //Set the SMTP server to send through
                                $mail->Host = 'smtp.gmail.com';
        
                                //Enable SMTP authentication
                                $mail->SMTPAuth = true;
        
                                //SMTP username
                                $mail->Username = 'easternachieveracademyoftaguig@gmail.com';
        
                                //SMTP password
                                $mail->Password = 'bqqitkwxkwzmaexx';
        
                                //Enable TLS encryption;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        
                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                                $mail->Port = 587;
        
                                //Recipients
                                $mail->setFrom('easternachieveracademyoftaguig@gmail.com', 'EAATI - ACCOUNTING');
        
                                //Add a recipient
                                $mail->addAddress($recipient_email);
        
                                //Set email format to HTML
                                $mail->isHTML(true);
        
                                
                                $mail->Subject = 'Accounting';
                                $mail->Body = '<p>We are delighted to inform you that we have received your payment and your enrollment has been successfully verified. Your registration process is now complete.</p>
        
                                <p>If you have any further questions or concerns, please do not hesitate to contact us.</p>
                                <p>Thank you,</p>
                                <p>Accounting|Eastern Achiever Academy Of Taguig</p>';
        
                                $mail->send();
                                header("location: {$_SERVER['HTTP_REFERER']}?verified=1");
                                exit();
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                            header("location: {$_SERVER['HTTP_REFERER']}?verified=1");
                            exit();
                        } else {
                            echo "Oops! Something went wrong while saving PDF to database.";
                            print_r($insert_stmt->errorInfo());
                        }
                    } else {
                        echo "Oops! Something went wrong while preparing PDF database query.";
                        print_r($insert_stmt->errorInfo());
                    }
                } else {
                    echo "Oops! Something went wrong while fetching email address.";
                    print_r($insert_stmt->errorInfo());
                }
            } else {
                echo "Oops! Something went wrong while preparing email query.";
                print_r($insert_stmt->errorInfo());
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
            print_r($insert_stmt->errorInfo());
        }
    }
     
    // Close statements
    unset($stmt);
    unset($email_stmt);
    
    // Close connection
    unset($conn);
} else{
    // ID parameter is missing or invalid. Redirect to error page
    header("location: error.php");
    exit();
}
?>
