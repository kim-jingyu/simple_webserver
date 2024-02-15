<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

    use Aws\S3\S3Client;
    use Aws\Exception\AwsException;
    use Aws\Credentials\CredentialProvider;

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

            $sdk = new Aws\Sdk($awsConfigs);
            $s3Client = $sdk->createS3();
            return $s3Client;
        }

        public static function getBucketName() {
            $bucket = getenv("S3_BUCKET_NAME");
            return $bucket;
        }
    }
?>