<?php
    /**
     * Created by PhpStorm.
     * User: venpan
     * Date: 18/10/2017
     * Time: 19:48
     */

    namespace DataTransform\CSVParserWriter\Delivery;
    header('Content-type: text/plain');


    class Delivery
    {

        /**
         * @param $filePath
         * @param $fileOnly
         * @return bool
         */
        private function DownloadToClient($filePath, $fileOnly)
        {
            // simple header accumalation to stream download to client
            if ($fileOnly && file_exists($filePath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filePath));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                ob_clean();
                flush();

                // proceed with download and return status
                return readfile($filePath) ? true : false;

            } elseif (false == $fileOnly) {
                // prepare zip download
                $zipPackageName = DataTransformer::LOCAL_DIR . $_SESSION['basename'] . 'ResultPackage.zip';
                // zip all files and make a package to deliver to client
                $zip = new \ZipArchive();
                $zip->open($zipPackageName, ZipArchive::CREATE);
                foreach (glob(DataTransformer::LOCAL_DIR."*") as $file) { /* Add appropriate path to read content of zip */
                    $zip->addFile($file);
                }
                $zip->close();
                header('Content-Type: application/zip');
                header("Content-Disposition: attachment; filename = $zipPackageName");
                header('Content-Length: ' . filesize($zipPackageName));
                header("Location: $zipPackageName");

                return readfile($zipPackageName) ? true : false;
            } else{

                // file didnt created, return false for now.
                return false;
            }
        }

        public function __construct()
        {
        }

    }