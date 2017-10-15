<?php
namespace JPCaparas\Tests\Xerovel\Feature;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use JPCaparas\Xerovel\Contracts\Xerovel as XerovelContract;
use JPCaparas\Xerovel\Providers\XerovelServiceProvider;
use JPCaparas\Xerovel\Xerovel;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    const TEST_DISK_NAME = 'xerovel';

    protected function setUp()
    {
        parent::setUp();

        Storage::fake(self::TEST_DISK_NAME);
    }

    protected function getPackageProviders($app)
    {
        return [XerovelServiceProvider::class];
    }

    protected function getTestDisk() : FilesystemAdapter
    {
        return Storage::disk(self::TEST_DISK_NAME);
    }

    public function testBindingHasSucceeded()
    {
        $fileName = 'fake-rsa-file';
        $this->getTestDisk()->put($fileName, 'Fake rsa contents');
        $rsaPrivateKey  = $this->getTestDisk()->getAdapter()->getPathPrefix() . $fileName;

        $this->app['config']->set('xerovel.callback_url', 'Test');
        $this->app['config']->set('xerovel.consumer_key', 'Test');
        $this->app['config']->set('xerovel.consumer_secret', 'Test');
        $this->app['config']->set('xerovel.rsa_private_key', $rsaPrivateKey);

        $xerovel = app(XerovelContract::class);
        $this->assertInstanceOf(Xerovel::class, $xerovel);
    }

    public function testBindingHasFailedWithMissingCallbackUrl()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fileName = 'fake-rsa-file';
        $this->getTestDisk()->put($fileName, 'Fake rsa contents');
        $rsaPrivateKey  = $this->getTestDisk()->getAdapter()->getPathPrefix() . $fileName;

        $this->app['config']->set('xerovel.consumer_key', '');
        $this->app['config']->set('xerovel.consumer_secret', '');
        $this->app['config']->set('xerovel.rsa_private_key', $rsaPrivateKey);

        app(XerovelContract::class);
    }

    public function testBindingHasFailedWithMissingConsumerKeyAndSecret()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fileName = 'fake-rsa-file';
        $this->getTestDisk()->put($fileName, 'Fake rsa contents');
        $rsaPrivateKey  = $this->getTestDisk()->getAdapter()->getPathPrefix() . $fileName;

        $this->app['config']->set('xerovel.callback_url', 'Test');
        $this->app['config']->set('xerovel.rsa_private_key', $rsaPrivateKey);

        app(XerovelContract::class);
    }

    public function testBindingHasFailedWithEmptyRsaContents()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fileName = 'fake-rsa-file';
        $this->getTestDisk()->put($fileName, '');
        $rsaPrivateKey  = $this->getTestDisk()->getAdapter()->getPathPrefix() . $fileName;

        $this->app['config']->set('xerovel.callback_url', 'Test');
        $this->app['config']->set('xerovel.consumer_key', 'Test');
        $this->app['config']->set('xerovel.consumer_secret', 'Test');
        $this->app['config']->set('xerovel.rsa_private_key', $rsaPrivateKey);

        app(XerovelContract::class);
    }

    public function testBindingHasFailedWithNoRsaFile()
    {
        $this->expectException(FileNotFoundException::class);

        $rsaPrivateKey  = $this->getTestDisk()->getAdapter()->getPathPrefix() . 'non-existent-file';

        $this->app['config']->set('xerovel.callback_url', 'Test');
        $this->app['config']->set('xerovel.consumer_key', 'Test');
        $this->app['config']->set('xerovel.consumer_secret', 'Test');
        $this->app['config']->set('xerovel.rsa_private_key', $rsaPrivateKey);

        app(XerovelContract::class);
    }
}
