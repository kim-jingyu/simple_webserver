<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

    use Aws\S3\S3Client;
    use Aws\Exception\AwsException;

    class S3Manager {
        public function __construct() {
        }

        public static function getClient() {
            $awsConfigs = [
                'version' => 'latest',
                'region' => 'ap-northeast-2',
                'credentials' => [
                    'key' => getenv("S3_ACCESS_KEY_ID"),
                    'secret' => getenv("S3_SECRET_ACCESS_KEY"),
                ],
            ];

            $s3Client = new Aws\S3\S3Client($awsConfigs);
            return $s3Client;
        }

        public static function getBucketName() {
            $bucket = getenv("S3_BUCKET_NAME");
            return $bucket;
        }
    }
?>