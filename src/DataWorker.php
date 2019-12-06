<?php


namespace Snowb\DataWorker;


use Upload\File;

/**
 * Class DataWorker
 * @package Snowb\DataWorker
 */
class DataWorker
{

    /** @var string  */
    public $uploadDir;
    /** @var File */
    private $uploadedFile;
    /**
     * @var array
     */
    private $output;

    /**
     * DataWorker constructor.
     * @param string $uploadDir
     */
    public function __construct($uploadDir)
    {
        if (empty($uploadDir))
            throw new \InvalidArgumentException('Upload directory not configured');
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        if (!empty($_POST)) return true;

        return false;
    }

    public function uploadFile($key = 'file')
    {
        $storage = new \Upload\Storage\FileSystem($this->uploadDir);
        $file = new \Upload\File($key, $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            // Ensure file is of type "image/png"
            new \Upload\Validation\Mimetype(['text/csv', 'text/plain']),

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('5M')
        ));

        // Try to upload file
        try {
            // Success!
            $file->upload();
            $this->uploadedFile = $file;
        } catch (\Exception $e) {
            // Fail!
            var_dump($file->getErrors());
        }

        return true;
    }

    public function convertFile()
    {
        $data = array_map('str_getcsv', file($this->uploadDir . DIRECTORY_SEPARATOR .$this->uploadedFile->getNameWithExtension()));
        $output = [];
        foreach ($data as $row) {
            $ii = 0;
            $lastElement = (integer) end($row);
            while ($ii < $lastElement) {
                $output[][0]= $row[0];
                $ii++;
            }
         }

        $this->output = $output;

    }

    public function outputData()
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $out = fopen('php://output', 'w');

        foreach ($this->output as $row) {
            fputcsv($out, $row);
        }
        fclose($out);

    }


}