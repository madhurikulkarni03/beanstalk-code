<?php

$bucket = 'MAIN BUCKET';

    // upload files to S3
    if (isset($_POST['save'])){
        // name of the uploaded file
        $filename = $_FILES['myfile']['name'];
        $file = $_FILES['myfile']['tmp_name'];
        // destination of the file on the server
        $destination = '/var/app/current/uploads/' . $filename;
        move_uploaded_file($file, $destination);
        require '/var/app/current/aws/aws-autoloader.php';
    
        $s3 = new Aws\S3\S3Client([
            'region'  => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => "ENTER YOUR ACCESS KEY",
                'secret' => "ENTER YOUR SECRET ACCESS KEY"
                ]
            ]);
            
    
            // Send a PutObject request and get the result object.
            $key = $filename;
            $time = date("h:i:s");
            $response = $s3->doesObjectExist($bucket, $filename);    
            if($response){
            $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $time.$key,
            'SourceFile' => $destination
            ]);
            }
            else{
                $result = $s3->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $key,
                    'SourceFile' => $destination
                    ]);
                    }
            }
?>      
